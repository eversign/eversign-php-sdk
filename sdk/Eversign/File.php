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
 * Each Document has 1 or more Files that are associated with it
 * Files must be uploaded before the can be used inside a Document
 * Each File has a unique fileId
 *
 * @author Patrick Leeb
 */
class File {

     /**
     * The unique ID of the uploaded File
     * @var string $fileId
     * @Type("string")
     */
    private $fileId;

    /**
     * A URL leading to the file you would like to upload as your document file.
     * @var string $fileUrl
     * @Type("string")
     */
    private $fileUrl;

    /**
     * Specify a base64 string of the file you would like to upload.
     * @var string $fileBase64
     * @Type("string")
     */
    private $fileBase64;

    /**
     * The name of the File
     * @var string $name
     * @Type("string")
     */
    private $name;

    /**
     * The number of pages of the File
     * @var integer $pages
     * @Type("integer")
     */
    private $pages;

    /**
     * The number of pages of the converted File
     * @var integer $totalPages
     * @Type("integer")
     */
    private $totalPages;

    /**
     * Setting this Property will upload the File as soon as createDocument
     * or uploadFile on the Client is called. Cannot be used in conjuction with other
     * File Links or Ids. After the Upload the fileId will be set automatically
     * @var string $filePath
     */
    private $filePath;


    public function getFileId() {
        return $this->fileId;
    }

    public function getName() {
        return $this->name;
    }

    public function getPages() {
        return $this->pages;
    }

    public function getTotalPages() {
        return $this->totalPages;
    }

    public function getFileUrl() {
        return $this->fileUrl;
    }

    public function getFileBase64() {
        return $this->fileBase64;
    }

    public function getFilePath() {
        return $this->filePath;
    }

    private function clearFileFields() {
        $this->filePath = "";
        $this->fileUrl = "";
        $this->fileId = "";
        $this->fileBase64 = "";
    }

    public function setFileId($fileId) {
        $this->clearFileFields();
        $this->fileId = $fileId;
    }

    public function setFileUrl($fileUrl) {
        $this->clearFileFields();
        $this->fileUrl = $fileUrl;
    }

    public function setFileBase64($fileBase64) {
        $this->clearFileFields();
        $this->fileBase64 = $fileBase64;
    }

    public function setFilePath($filePath) {
        if (!file_exists($filePath)) {
            throw new \Exception('The file on the specified path cannot be found.');
        }

        $this->clearFileFields();
        $this->filePath = $filePath;
    }


    public function setName($name) {
        $this->name = $name;
    }



}
