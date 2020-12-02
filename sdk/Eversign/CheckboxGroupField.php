<?php

/*
 * The MIT License
 *
 * Copyright 2020 Eversign.
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

use Eversign\CheckboxField;
use JMS\Serializer\Annotation\Type;

/**
 * Radio Button fields come with a fixed pixel width and height of 14x14.
 * The additional group parameter is used to identify radio button groups
 *
 * @author Alex K.
 */
class CheckboxGroupField extends CheckboxField {

     /**
     * This parameter is used to identify CheckboxGroup button groups.
     * CheckboxGroupFields belonging to the same group must carry the same group parameter.
     * @var integer $group
     * @Type("integer")
     */
    private $group;

    public function __construct() {
        parent::__construct();
        $this->setGroup(0);
    }

    public function getGroup() {
        return $this->group;
    }

    public function setGroup($group) {
        $this->group = $group;
    }



}
