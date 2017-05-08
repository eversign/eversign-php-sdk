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
use JMS\Serializer\Annotation\Type;

/**
 * Abstract class form Fields which contain text that is style-able.
 *
 * @author Patrick Leeb
 */
abstract class TextFormField extends FormField{

    /**
     * Set to your preferred font size number
     * @var integer $textSize
     * @Type("integer")
     */
    private $textSize;

    /**
     * Color of the Text as Hex color code, e.g. #003399
     * @var string $textColor
     * @Type("string")
     */
    private $textColor;

    /**
     * Font of the TextFormField
     * Supported fonts are: arial, calibri, courier_new, helvetica, georgia
     * and times_new_roman
     * @var string $textFont
     * @Type("string")
     */
    private $textFont;

    /**
     * Text Style of the TextFormField
     * The letters B for bold, U for underlined and I for italic,
     * in an order of your choice. Example: BUI
     * @var string $textStyle
     * @Type("string")
     */
    private $textStyle;



    public function __construct() {
        parent::__construct();
        $this->setTextSize(14);
        $this->setTextStyle(" ");
        $this->setTextColor("#000000");
        $this->setTextFont("arial");
    }

    public function validate() {
        $success = true;
        if(!$this->getTextSize() || !$this->getTextFont() || !$this->getTextColor() || !$this->getTextStyle()){
            echo "false";
            $success = false;
        }

        return $success & parent::validate();
    }


    public function getTextSize() {
        return $this->textSize;
    }

    public function getTextColor() {
        return $this->textColor;
    }

    public function getTextFont() {
        return $this->textFont;
    }

    public function getTextStyle() {
        return $this->textStyle;
    }


    public function setTextSize($textSize) {
        $this->textSize = $textSize;
    }

    public function setTextColor($textColor) {
        $this->textColor = $textColor;
    }

    public function setTextFont($textFont) {
        if($textFont != "arial" && $textFont != "calibri" && $textFont != "courier_new"
                && $textFont != "helvetica" && $textFont != "georgia") {
            throw new \Exception('The selected Font is not available for this Property');
        }
        $this->textFont = $textFont;
    }

    public function setTextStyle($textStyle) {
        $this->textStyle = $textStyle;
    }


}
