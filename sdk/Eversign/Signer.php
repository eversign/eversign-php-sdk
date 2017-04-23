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
     * True if the Signer has signed the associated Document
     * @var boolean $signed 
     * @Type("boolean")
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
     * @var boolean $declined 
     * @Type("boolean")
     */
    private $declined;
    
    /**
     * True if the Document has been sent to the Signer
     * @var boolean $sent 
     * @Type("boolean")
     */
    private $sent;
    
    /**
     * True if the Document has been viewed to the Signer
     * @var boolean $sent 
     * @Type("boolean")
     */
    private $viewed;
    
    /**
     * URL of the Signing Request that is sent to the Signer
     * @var string $signingUrl 
     * @Type("string")
     */
    private $signingUrl; 
    
    /**
     * Status of the Signer
     * @var string $status 
     * @Type("string")
     */
    private $status; 
    
    
    public function getId() {
        return $this->id;
    }

    public function getOrder() {
        return $this->order;
    }

    public function getPin() {
        return $this->pin;
    }

    public function getSigned() {
        return $this->signed;
    }

    public function getSigned_timestamp() {
        return $this->signed_timestamp;
    }

    public function getRequired() {
        return $this->required;
    }

    public function getDeclined() {
        return $this->declined;
    }

    public function getSent() {
        return $this->sent;
    }

    public function getViewed() {
        return $this->viewed;
    }

    public function getSigningUrl() {
        return $this->signingUrl;
    }

    public function getStatus() {
        return $this->status;
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

    public function setSigned($signed) {
        $this->signed = $signed;
    }

    public function setSigned_timestamp($signed_timestamp) {
        $this->signed_timestamp = $signed_timestamp;
    }

    public function setRequired($required) {
        $this->required = $required;
    }

    public function setDeclined($declined) {
        $this->declined = $declined;
    }

    public function setSent($sent) {
        $this->sent = $sent;
    }

    public function setViewed($viewed) {
        $this->viewed = $viewed;
    }

    public function setSigningUrl($signingUrl) {
        $this->signingUrl = $signingUrl;
    }

    public function setStatus($status) {
        $this->status = $status;
    }


}
