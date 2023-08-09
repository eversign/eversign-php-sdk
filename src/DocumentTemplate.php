<?php

/*
 * The MIT License
 *
 * Copyright 2017 Patrick Leeb.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


namespace Eversign;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\SerializerBuilder;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * An existing template can be used by making an HTTP POST request to the
 * document containing some key parameters.
 * All optional and required parameters are listed in the table below.
 *
 * @author Patrick Leeb
 */
class DocumentTemplate {

    /**
     * Sets the sandbox parameter
     * @var integer $sandbox
     * @Type("integer")
     */
    private $sandbox;

     /**
     * Set to the Template ID of the template you would like to use
     * @var string $templateId
     * @Type("string")
     */
    private $templateId;

    /**
     * Sets the title of the Document.
     * @var string $title
     * @Type("string")
     */
    private $title;

     /**
     * Used in order to specify a document message.
     * @var string $message
     * @Type("string")
     */
    private $message;

    /**
     * This parameter is used to specify a custom completion redirect URL.
     * If empty the default Post-Sign Completion URL of the current Business will be used
     * @var string $redirect
     * @Type("string")
     */
    private $redirect;

     /**
     * This parameter is used to specify a custom decline redirect URL.
     * If empty the default Decline URL of the current Business will be used
     * @var string $redirectDecline
     * @Type("string")
     */
    private $redirectDecline;

     /**
     * This parameter is used to specify an internal reference for your application,
     * such as an identification string of the server or client making the API request.
     * @var string $client
     * @Type("string")
     */
    private $client;

    /**
     * Expiration Time of the Document, default expiration time will be used if unset
     *
     * @var \DateTime $expires
     * @Type("DateTime<'U'>")
     */
    private $expires;

     /**
     * Array of Signer Objects which are associated with the Document
     *
     * @var array<Eversign\Signer> $signers
     * @Type("array<Eversign\Signer>")
     */
    private $signers;

     /**
     * Array of Recipient Objects which are associated with the Document
     *
     * @var array<Eversign\Recipient> $signers
     * @Type("array<Eversign\Recipient>")
     */
    private $recipients;

     /**
     * This object must contain a sub array for each Merge Field of this template.
     *
     * @var array<Eversign\Field> $fields
     * @Type("array<Eversign\Field>")
     */
    private $fields;

    /**
     * Whether the Document is embedded signable
     * @var integer $embeddedSigningEnabled
     * @Type("integer")
     */
    private $embeddedSigningEnabled;

    /**
     * Custom E-Mail address of the requester
     * @var string $customRequesterEmail
     * @Type("string")
     */
    private $customRequesterEmail;

    /**
     * Custom name of the requester
     * @var string $customRequesterName
     * @Type("string")
     */
    private $customRequesterName;

    public function __construct($templateId = null) {
        if (!class_exists('Doctrine\Common\Annotations\AnnotationRegistry', false) && class_exists('Doctrine\Common\Annotations\AnnotationRegistry')) {
            AnnotationRegistry::registerLoader('class_exists');
        }
        $this->signers = [];
        $this->recipients = [];
        $this->setSandbox(false);

        if($templateId !== null) {
            $this->setTemplateId($templateId);
        }
    }

    /**
     * Appends a \Eversign\Signer instance to the document created by the template.
     * The Signer Object is required to have a role
     * @param \Eversign\Signer $signer
     * @throws \Exception
     */
    public function appendSigner(Signer $signer) {
        if (!$signer->getRole()) {
            throw new \Exception('Signer needs a role to be added');
        }

        $this->signers[] = $signer;
    }

    /**
     * Appends a \Eversign\Recipient instance to the document
     * @param \Eversign\Recipient $recipient
     * @throws \Exception
     */
    public function appendRecipient(Recipient $recipient) {
        if (!$recipient->getRole() || !$recipient->getName() || !$recipient->getEmail()) {
            throw new \Exception('Recipient needs at least a Name, a Role and an E-Mail address');
        }

        $this->recipients[] = $recipient;
    }

     /**
     * Appends a Field Object for Merge Fields.
     * @param \Eversign\Field $field
     */
    public function appendField(Field $field) {
        $this->fields[] = $field;
    }

    public function getSandbox() {
        return !!$this->sandbox;
    }

    public function getTemplateId() {
        return $this->templateId;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getRedirect() {
        return $this->redirect;
    }

    public function getRedirectDecline() {
        return $this->redirectDecline;
    }

    public function getClient() {
        return $this->client;
    }

    public function getExpires() {
        return $this->expires;
    }

    public function getSigners() {
        return $this->signers;
    }

    public function getRecipients() {
        return $this->recipients;
    }

    public function getFields() {
        return $this->fields;
    }

    public function getEmbeddedSigningEnabled() {
        return !!$this->embeddedSigningEnabled;
    }
    
    public function getCustomRequesterEmail() {
        return $this->customRequesterEmail;
    }

    public function getCustomRequesterName() {
        return $this->customRequesterName;
    }

    public function setSandbox($sandbox) {
        $this->sandbox = !!$sandbox;
    }
    public function setTemplateId($templateId) {
        $this->templateId = $templateId;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setRedirect($redirect) {
        $this->redirect = $redirect;
    }

    public function setRedirectDecline($redirectDecline) {
        $this->redirectDecline = $redirectDecline;
    }

    public function setClient($client) {
        $this->client = $client;
    }

    public function setExpires(\DateTime $expires) {
        $this->expires = $expires;
    }

    public function setSigners($signers) {
        $this->signers = $signers;
    }

    public function setRecipients($recipients) {
        $this->recipients = $recipients;
    }

    public function setFields($fields) {
        $this->fields = $fields;
    }

    public function setEmbeddedSigningEnabled($embeddedSigningEnabled) {
        $this->embeddedSigningEnabled = !!$embeddedSigningEnabled;
    }

    public function setCustomRequesterEmail($customRequesterEmail) {
        $this->customRequesterEmail = $customRequesterEmail;
    }

    public function setCustomRequesterName($customRequesterName) {
        $this->customRequesterName = $customRequesterName;
    }

     /**
     * Converts the document to a JSON String
     * @return string
     */
    public function printJson() {
        $serializer = SerializerBuilder::create()->build();
        return $serializer->serialize($this, 'json');
    }

}
