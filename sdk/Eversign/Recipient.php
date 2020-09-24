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


/**
 * Each Document can have 1 or multiple Recipients which are not able to sign the
 * Document but can view it.
 *
 * @author Patrick Leeb
 */
class Recipient {


    /**
     * Sets the full name of the Recipient.
     * @var string $name
     * @Type("string")
     */
    private $name;

    /**
     * Sets the email of the Recipient.
     * @var string $email
     * @Type("string")
     */
    private $email;

    /**
     * Roles are used when creating a document from a template.
     * Please note that all required roles must be specified in order to use a template.
     * @var string $role
     * @Type("string")
     */
    private $role;

    /**
     * Display language of the recipient. Defaults to en
     * @var string $language
     * @Type("string")
     */
    private $language = 'en';
    
    /**
     * This parameter can be used to specify a custom message (upon document delivery) for the current recipient.
     * Please note that for the current recipient the general document message will be overriden by this parameter.
     * @var string $message
     * @Type("string")
     */
    private $message;    

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setEmail($mail) {
        $this->email = $mail;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setLanguage($language) {
        if(!in_array($language, Config::$AVAILABLE_LANGUAGES)) {
            throw new \Exception('language not supported');
        }
        $this->language = $language;
    }
    
    public function getMessage() {
        return $this->message;
    }	
	
    public function setMessage($message) {
        $this->message = $message;
    }    

}
