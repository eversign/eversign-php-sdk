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
use Eversign\FormField;
use Doctrine\Common\Annotations\AnnotationRegistry;


/**
 * Documents are used by Signers to create legally Binding electronic signatures
 * They can create custom FormFields, multiple Files, custom Meta Tags and extra Recipients
 *
 * @author Patrick Leeb
 *
 */
class Document {

    /**
     * Document Hash to identify its authenticity
     * @property string $documentHash
     * @Type("string")
     */
    private $documentHash;

    /**
     * E-Mail address of the requester
     * @var string $requesterEmail
     * @Type("string")
     */
    private $requesterEmail;

    /**
     * Set to true in order to save this document as a draft.
     * @var boolean $isDraft
     * @Type("boolean")
     */
    private $isDraft;

    /**
     * Check if the document is completed.
     * @var boolean $isCompleted
     * @Type("boolean")
     */
    private $isCompleted;

    /**
     * Check if the document is archived.
     * @var boolean $isArchived
     * @Type("boolean")
     */
    private $isArchived;

     /**
     * Check if the document is deleted.
     * @var boolean $isDeleted
     * @Type("boolean")
     */
    private $isDeleted;

     /**
     * Check if the document is in the trash.
     * @var boolean $isTrashed
     * @Type("boolean")
     */
    private $isTrashed;

    /**
     * Check if the document has been canceled.
     * @var boolean $isCancelled
     * @Type("boolean")
     */
    private $isCanceled;

    /**
     *
     * @var boolean $embedded
     * @Type("boolean")
     */
    private $embedded;

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
     * Set to true to define a specific order of the Signers
     * @var boolean $embedded
     * @Type("boolean")
     */
    private $useSignerOrder;

    /**
     * Whether the Document is a Template or not
     * @var boolean $isTemplate
     * @Type("boolean")
     */
    private $isTemplate;

    /**
     * Set to true to enable Auto Reminders for this Document
     * @var boolean $embedded
     * @Type("boolean")
     */
    private $reminders;

    /**
     * Set to true requires all signers to sign the document to complete it
     * @var boolean $embedded
     * @Type("boolean")
     */
    private $requireAllSigners;

    /**
     * This parameter is used to specify a custom completion redirect URL.
     * If empty the default Post-Sign Completion URL of the current Business will be used
     * @var string $redirect
     * @Type("string")
     */
    private $redirect;

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
     * Array of LogEntry Objects which are associated with the Document
     *
     * @var array<Eversign\LogEntry> $log
     * @Type("array<Eversign\LogEntry>")
     */
    private $log;

    /**
     * Array of FormField Objects and there respective Subclass
     *
     * @var array<array<Eversign\FormField>> $fields
     * @Type("array<array<Eversign\FormField>>")
     */
    private $fields;

     /**
     * Array of File Objects which are associated with the Document
     *
     * @var array<Eversign\File> $files
     * @Type("array<Eversign\File>")
     */
    private $files;

     /**
     * Array of Custom Meta Tags which are associated with the Document
     *
     * @var array<string, string> $meta
     * @Type("array<string, string>")
     */
    private $meta;

    public function __construct() {
        AnnotationRegistry::registerLoader('class_exists');

        $this->setIsDraft(false);
        $this->setUseSignerOrder(false);
        $this->setReminders(false);
        $this->setRequireAllSigners(false);
        $this->signers = [];
        $this->recipients = [];
        $this->files = [];
        $this->fields = [];
        $this->meta = [];
    }

    /**
     * Appends a \Eversign\Signer instance to the document.
     * Will set a default Signer Id if it was not set previously on the Signer.
     * @param \Eversign\Signer $signer
     * @throws \Exception
     */
    public function appendSigner(Signer $signer) {
        if (!$signer->getName() || !$signer->getEmail()) {
            throw new \Exception('Signer needs at least a Name and an E-Mail address');
        }
        if(!$signer->getId()) {
            $signer->setId(count($this->signers) +1);
        }
        $this->signers[] = $signer;
    }

    /**
     * Appends a \Eversign\File instance to the document
     * @param \Eversign\File $file
     * @throws \Exception
     */
    public function appendFile(File $file) {
        if (!$file->getFilePath() && !$file->getFileId() && !$file->getFileUrl() && !$file->getFileBase64()) {
            throw new \Exception('File object needs a real File to be associated');
        }
        if(!$file->getName()) {
            if($file->getFilePath()) {
                $file->setName(basename($file->getFilePath()));
            }
            else {
                throw new \Exception('File object needs a name');
            }

        }

        $this->files[] = $file;
    }

    /**
     * Appends a \Eversign\FormField subclass to the document. The second
     * parameter defines the Index of the File instance where the FormField lives.
     * @param FormField $formField
     * @param type $fileIndex
     * @throws \Exception
     */
    public function appendFormField(FormField $formField, $fileIndex = 0) {
        if (count($this->getFiles()) == 0 || $fileIndex  > count($this->getFiles())) {
            throw new \Exception('Please check that at least 1 File was added and the fileIndex isn´t higher than the Amount of files');
        }
        if (!$formField->validate()) {
            throw new \Exception('Please check that all required FormField Properties are set');
        }
        if (!$formField->getIdentifier()) {
            $formField->setIdentifier((new \ReflectionClass($formField))->getShortName() . "_" . count($this->getFields()[$fileIndex]));
        }


        $this->fields[$fileIndex][] = $formField;
    }

    /**
     * Appends a \Eversign\Recipient instance to the document
     * @param \Eversign\Recipient $recipient
     * @throws \Exception
     */
    public function appendRecipient(Recipient $recipient) {
        if (!$recipient->getName() || !$recipient->getEmail()) {
            throw new \Exception('Recipient needs at least a Name and an E-Mail address');
        }

        $this->recipients[] = $recipient;
    }

    /**
     * Converts the document to a JSON String
     * @return string
     */
    public function printJson() {
        $serializer = SerializerBuilder::create()->build();
        return $serializer->serialize($this, 'json');
    }

    public function getDocumentHash() {
        return $this->documentHash;
    }

    public function getRequesterEmail() {
        return $this->requesterEmail;
    }

    public function getIsDraft() {
        return $this->isDraft;
    }

    public function getIsCompleted() {
        return $this->isCompleted;
    }

    public function getIsArchived() {
        return $this->isArchived;
    }

    public function getIsDeleted() {
        return $this->isDeleted;
    }

    public function getIsTrashed() {
        return $this->isTrashed;
    }

    public function getIsCanceled() {
        return $this->isCanceled;
    }

    public function getEmbedded() {
        return $this->embedded;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getUseSignerOrder() {
        return $this->useSignerOrder;
    }

    public function getIsTemplate() {
        return $this->isTemplate;
    }

    public function getReminders() {
        return $this->reminders;
    }

    public function getRequireAllSigners() {
        return $this->requireAllSigners;
    }

    public function getRedirect() {
        return $this->redirect;
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

    public function getFiles() {
        return $this->files;
    }

    public function getLog() {
        return $this->log;
    }

    public function getRecipients() {
        return $this->recipients;
    }

    public function getFields() {
        return $this->fields;
    }

    public function getMeta() {
        return $this->meta;
    }

    public function appendMeta($key, $value) {
       $this->meta[$key] = $value;
    }

    public function removeMeta($key) {
        unset($this->meta[$key]);
    }

    public function setDocumentHash($documentHash) {
        $this->documentHash = $documentHash;
    }

    public function setRequesterEmail($requesterEmail) {
        $this->requesterEmail = $requesterEmail;
    }

    public function setIsDraft($isDraft) {
        $this->isDraft = $isDraft;
    }

    public function setEmbedded($embedded) {
        $this->embedded = $embedded;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setUseSignerOrder($useSignerOrder) {
        $this->useSignerOrder = $useSignerOrder;
    }

    public function setReminders($reminders) {
        $this->reminders = $reminders;
    }

    public function setRequireAllSigners($requireAllSigners) {
        $this->requireAllSigners = $requireAllSigners;
    }

    public function setRedirect($redirect) {
        $this->redirect = $redirect;
    }

    public function setClient($client) {
        $this->client = $client;
    }

    public function setExpires($expires) {
        $this->expires = $expires;
    }

    public function setMeta($meta) {
        $this->meta = $meta;
    }

}
