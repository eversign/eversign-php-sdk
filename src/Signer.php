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
use Eversign\Recipient;

/**
 * Each Document can have 1 or multiple Signers which are able to sign the
 * Document. Each Signer needs an id, name and an E-Mail address.
 * Each Document needs at least 1 Signer
 *
 * @author Patrick Leeb
 */
class Signer extends Recipient {

    /**
     * Sets the id of the Signer.
     * @var integer $name
     * @Type("integer")
     */
    private $id;


    /**
     * The order number of the Signer
     * Usually starts with 1 for the first Signer.
     * @var integer $order
     * @Type("integer")
     */
    private $order;

    /**
     * Pins are used as an extra level of security and must be put in
     * by the signer before signing the Document.
     * @var string $pin
     * @Type("string")
     */
    private $pin;

     /**
     * True if the signer authentication via SMS is enabled
     * @var integer $signer_authentication_sms_enabled
     * @Type("integer")
     */
    private $signer_authentication_sms_enabled;

    /**
     * Phone number of the signer for signer authentication via SMS
     * @var string $signer_authentication_phone_number
     * @Type("string")
     */
    private $signer_authentication_phone_number;

    /**
     * Sets the required attribute of the Signer.
     * @param int $required
     * @Type("int")
     */
    private $required;

     /**
     * True if the Signer has signed the associated Document
     * @var integer $signed
     * @Type("integer")
     */
    private $signed;

     /**
     * Time of signing the Document if the Signer has signed already
     *
     * @var string $signed_timestamp
     * @Type("string")
     */
    private $signed_timestamp;


     /**
     * True if the Signer declined to sign the Document
     * @var integer $declined
     * @Type("integer")
     */
    private $declined;

    /**
     * True if the Document has been sent to the Signer
     * @var integer $sent
     * @Type("integer")
     */
    private $sent;

    /**
     * True if the Document has been viewed to the Signer
     * @var integer $sent
     * @Type("integer")
     */
    private $viewed;

    /**
     * Status of the Signer
     * @var string $status
     * @Type("string")
     */
    private $status;

    /**
     * Embedded Signer URL
     * @var string $embeddedSigningUrl
     * @Type("string")
     */
    private $embeddedSigningUrl;

    /**
     * If true and embedded singing is enabled than the user will also get an mail
      * @var integer $deliverEmail
      * @Type("integer")
      */
    private $deliverEmail = false;

    /**
     * Display language of Signer
     * @var string $language
     * @Type("string")
     */
    private $language = 'en';

    /**
     * Decline reason of Signer
     * @var string $declinedReason
     * @Type("string")
     */
    private $declinedReason = "";

    public function getId() {
        return $this->id;
    }

    public function getOrder() {
        return $this->order;
    }

    public function getPin() {
        return $this->pin;
    }

    public function getSignerAuthEnabled() {
        return $this->signer_authentication_sms_enabled;
    }

    public function getSignerAuthPhoneNumber() {
        return $this->signer_authentication_phone_number;
    }

    public function getSigned() {
        return !!$this->signed;
    }

    public function getSignedTimestamp() {
        return $this->signed_timestamp;
    }

    /** @deprecated use getSignedTimestamp instead */
    public function getSigned_timestamp() {
        return $this->signed_timestamp;
    }

    public function getDeclined() {
        return !!$this->declined;
    }

    public function getSent() {
        return !!$this->sent;
    }

    public function getViewed() {
        return !!$this->viewed;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getEmbeddedSigningUrl() {
        return $this->embeddedSigningUrl;
    }

    public function getDeliverEmail() {
        return !!$this->deliverEmail;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setOrder($order) {
        $this->order = $order;
    }

    public function setPin($pin) {
        $this->pin = $pin;
    }

    public function setSignerAuthEnabled($signer_authentication_sms_enabled) {
        $this->signer_authentication_sms_enabled = $signer_authentication_sms_enabled;
    }

    public function setSignerAuthPhoneNumber($signer_authentication_phone_number) {
        $this->signer_authentication_phone_number = $signer_authentication_phone_number;
    }

    public function setSigned($signed) {
        $this->signed = !!$signed;
    }

    public function setSignedTimestamp($signed_timestamp) {
        $this->signed_timestamp = $signed_timestamp;
    }

    /** @deprecated use setSignedTimestamp instead */
    public function setSigned_timestamp($signed_timestamp) {
        $this->signed_timestamp = $signed_timestamp;
    }

    public function setDeclined($declined) {
        $this->declined = !!$declined;
    }

    public function setSent($sent) {
        $this->sent = !!$sent;
    }

    public function setViewed($viewed) {
        $this->viewed = !!$viewed;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setEmbeddedSigningUrl($embeddedSigningUrl) {
        return $this->embeddedSigningUrl = $embeddedSigningUrl;
    }

    public function setDeliverEmail($deliverEmail) {
        return $this->deliverEmail = !!$deliverEmail;
    }

    public function setLanguage($language) {
        if(!in_array($language, Config::$AVAILABLE_LANGUAGES)) {
            throw new \Exception('language not supported');
        }
        return $this->language = $language;
    }

    public function setRequired($required)
    {
        $this->required = $required;
    }

    public function setDeclinedReason($declinedReason)
    {
        $this->declinedReason = $declinedReason;
    }

    public function getDeclinedReason()
    {
        return $this->declinedReason;
    }
}
