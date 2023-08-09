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

use Eversign\FormField;
use Eversign\Readable;
use Eversign\Signable;
use Eversign\Requireable;

use JMS\Serializer\Annotation\Type;


/**
 * Checkbox fields come with a fixed pixel width and height of 14x14.
 *
 * @author Patrick Leeb
 */
class CheckboxField extends FormField{

    use Readable, Signable, Requireable;

     /**
     * The label of the Field
     * @var string $name
     * @Type("string")
     */
    private $name;


    /**
     * Set to 0 or leave empty to mark unchecked; Set to 1 to mark checked
     * @var string $value
     * @Type("string")
     */
    private $value;


    public function __construct() {
        parent::__construct();
        parent::setWidth(14);
        parent::setHeight(14);
        $this->setReadOnly(false);
        $this->setValue("0");
    }

    public function getWidth() {
        return 14;
    }

    public function getHeight() {
        return 14;
    }

    public function getValue() {
        return $this->value;
    }

    public function getName() {
        return $this->name;
    }


    public function setValue($value) {
        if($value != "1" && $value != "0"){
            throw new \Exception('Checkbox Value can only be 0 or 1');
        }
        $this->value = $value;
    }

    public function setWidth($width) {
        throw new \Exception('CheckboxFields have a fixed width and height of 14 that cannot be changed');
    }

    public function setHeight($height) {
        throw new \Exception('CheckboxFields have a fixed width and height of 14 that cannot be changed');
    }

    public function setName($name) {
        $this->name = $name;
    }

}
