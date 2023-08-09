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

class Business {


    /**
     * The associated Business ID
     *
     * @var int $businessId
     * @Type("integer")
     */
    private $businessId;

    /**
     * The associated Business Identifier
     *
     * @var string $businessIdentifier
     * @Type("string")
     */
    private $businessIdentifier;

    /**
     * The associated Business Name
     *
     * @var int $businessName
     * @Type("string")
     */
    private $businessName;

    /**
     * The associated Business Connection Id
     *
     * @var int $businessConnectionId
     * @Type("integer")
     */
    private $businessConnectionId;

    /**
     * Check if this business is the primary Business of the Client
     *
     * @var integer $isPrimary
     * @Type("integer")
     */
    private $isPrimary;

    /**
     * Creation Time of the Business, can't be set via Webservice (read-only)
     *
     * @var \DateTime $creationTimeStamp
     * @Type("DateTime<'U'>")
     */
    private $creationTimeStamp;



     /**
     * Returns the Business Id
     *
     * @return int
     */
    public function getBusinessId() {
        return $this->businessId;
    }

    public function setBusinessId($businessId) {
        $this->businessId = $businessId;
    }

    /**
     * Returns the Business Name
     *
     * @return string
     */
    public function getBusinessIdentifier() {
        return $this->businessIdentifier;
    }

    public function setBusinessIdentifier($businessIdentifier) {
        $this->businessIdentifier = $businessIdentifier;
    }

    /**
     * Returns the Business Name
     *
     * @return string
     */
    public function getBusinessName() {
        return $this->businessName;
    }

    public function setBusinessName($businessName) {
        $this->businessName = $businessName;
    }

    /**
     * Returns the Business Name
     *
     * @return int
     */
    public function getBusinessConnectionId() {
        return $this->businessConnectionId;
    }

    public function setBusinessConnectionId($businessConnectionId) {
        $this->businessConnectionId = $businessConnectionId;
    }

    /**
     * Returns the Business Name
     *
     * @return bool
     */
    public function getIsPrimary() {
        return !!$this->isPrimary;
    }

    public function setIsPrimary($isPrimary) {
        $this->isPrimary = !!$isPrimary;
    }

    /**
     * Returns the Creation Date of the Business
     * @return DateTime
     */
    public function getCreationTimeStamp() {
        return $this->creationTimeStamp;
    }

    public function setCreationTimeStamp($creationTimeStamp) {
        $this->creationTimeStamp = $creationTimeStamp;
    }
}
