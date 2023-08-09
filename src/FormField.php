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
use JMS\Serializer\Annotation\Discriminator;
use JMS\Serializer\Annotation\Exclude;


/**
 * Each Document can have multiple FormFields that the user is able to fill in
 * The following types are available: signature, initials, date_signed,
 * note, text, checkbox, radio, dropdown, attachment
 *
 * @author Patrick Leeb
 * @Discriminator(field = "type", map = {
 *    "date_signed": "Eversign\DateSignedField",
 *    "signature": "Eversign\SignatureField",
 *    "initials": "Eversign\InitialsField",
 *    "note": "Eversign\NoteField",
 *    "text": "Eversign\TextField",
 *    "checkbox": "Eversign\CheckboxField",
 *    "radio": "Eversign\RadioField",
 *    "dropdown": "Eversign\DropdownField",
 *    "attachment": "Eversign\AttachmentField",
 *    "checkboxGroup": "Eversign\CheckboxGroupField"
 *
 * })
 */
abstract class FormField {


     /**
     * A unique alphanumeric identifier which distinguishes the different form
     * fields from another
     * @var string $identifier
     * @Type("string")
     */
    private $identifier;

     /**
     * The number of the page where the FormField should be displayed
     * @var integer $page
     * @Type("integer")
     */
    private $page;

     /**
     * The width of the FormField in pixels.
     * @var integer $width
     * @Type("integer")
     */
    protected $width;

    /**
     * The height of the FormField in pixels.
     * @var integer $height
     * @Type("integer")
     */
    protected $height;


    /**
     * The FormField's horizontal margin from the left
     * side of the document in pixels.
     * @var float $x
     * @Type("float")
     */
    private $x;


    /**
     * The FormField's vertical margin from the top of the document in pixels
     * @var float $y
     * @Type("float")
     */
    private $y;


    /**
     * The FormField's file index on which it will be placed
     * @var integer
     * @Exclude
     */
    private $fileIndex;


    public function __construct() {
        $this->setFileIndex(0);
        $this->setPage(1);
        $this->setX(0);
        $this->setY(0);
        if (method_exists($this, "setSigner")) {
            $this->setSigner("OWNER");
        }
    }


    /**
     * Validates the current FormField
     * @return boolean success
     */
    public function validate() {
        if(!$this->getHeight() || !$this->getWidth() || !$this->getX() || !$this->getY() || !$this->getPage()){
            return false;
        }
        return true;
    }


    public function getIdentifier() {
        return $this->identifier;
    }

    public function getPage() {
        return $this->page;
    }

    public function getWidth() {
        return $this->width;
    }

    public function getHeight() {
        return $this->height;
    }

    public function getX() {
        return $this->x;
    }

    public function getY() {
        return $this->y;
    }

    public function getFileIndex() {
        return $this->fileIndex;
    }

    public function setIdentifier($identifier) {
        $this->identifier = $identifier;
    }

    public function setPage($page) {
        $this->page = $page;
    }

    public function setWidth($width) {
        $this->width = $width;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function setX($x) {
        $this->x = $x;
    }

    public function setY($y) {
        $this->y = $y;
    }

    public function setFileIndex($fileIndex) {
        $this->fileIndex = $fileIndex;
    }




}
