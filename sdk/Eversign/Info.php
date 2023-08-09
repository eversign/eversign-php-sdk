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


/**
 * Info Object
 *
 * @author Dominik Kukacka
 *
 */
class Info {

    /**
     * The logged in user (for e.g. for oauth)
     * @property string $userId
     * @Type("string")
     */
    private $userId;

    /**
     * E-Mail address of the logged in user
     * @var string $userEmail
     * @Type("string")
     */
    private $userEmail;

    /**
     * Business url for the logged in user (and the requests business id)
     * @var string $businessUrl
     * @Type("string")
     */
    private $businessUrl;

    /**
     * Converts the document to a JSON String
     * @return string
     */
    public function printJson() {
        $serializer = SerializerBuilder::create()->build();
        return $serializer->serialize($this, 'json');
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getUserEmail() {
        return $this->userEmail;
    }

    public function setUserEmail($userEmail) {
        $this->userEmail = $userEmail;
    }

    public function getBusinessUrl() {
        return $this->businessUrl;
    }

    public function setBusinessUrl($businessUrl) {
        $this->businessUrl = $businessUrl;
    }

}
