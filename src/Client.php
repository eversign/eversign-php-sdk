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

use Eversign\ApiRequest;
use Eversign\OAuthTokenRequest;
use Eversign\Business;
use Eversign\Config;
use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\SerializerBuilder;

class Client {

    /**
     * The API base url
     *
     * @var Url $apiBaseUrl
     */
    protected $apiBaseUrl;

    /**
     * Reference to the Access Key
     *
     * @var Access Key $accessKey
     */
    protected $accessKey;

    /**
     * All available Businesses (as Array) associated with the current Client
     * @var \Business[] $selectedBusiness
     */
    private $businesses;

    private $businessId;

    /**
     * The selected Business which will be used for subsequent API requests
     * @var \Business $selectedBusiness
     */
    private $selectedBusiness;

    private $apiRequestTimeout;

    /**
      * Constructor
      *
      * @param string $accessKey
      */
     public function __construct($accessKey = null, $businessId = 0, $apiBaseUrl = null, int $apiRequestTimeout = Config::GUZZLE_TIMEOUT)
     {
        if (!class_exists('Doctrine\Common\Annotations\AnnotationRegistry', false) && class_exists('Doctrine\Common\Annotations\AnnotationRegistry')) {
            AnnotationRegistry::registerLoader('class_exists');
        }
        $this->accessKey = $accessKey;

        if($businessId != 0) {
            $this->businessId = $businessId;
        }
        $this->apiBaseUrl = $apiBaseUrl;
        $this->fetchBusinesses();

        $this->apiRequestTimeout = $apiRequestTimeout;
     }

     /**
      * Sets the Business from which all subsequent API requests will be called
      * @param Business $selectedBusiness
      */
     public function setSelectedBusiness($selectedBusiness) {
         $this->selectedBusiness = $selectedBusiness;
     }

    /**
    * Sets the Business from which all subsequent API requests will be called
    * @param Integer $businessId
    */
    public function setSelectedBusinessById($businessId) {
        $filteredBusinesses = array_filter(
            $this->businesses,
            function ($e) use ($businessId) {
                return $e->getBusinessId() == $businessId;
            }
        );

        if(!$filteredBusinesses || count($filteredBusinesses) == 0) {
            throw new \Exception('No Business found with the specified Business Id');
        } else {
            $this->selectedBusiness = array_values($filteredBusinesses)[0];
        }
    }

     public function getBusinesses() {
         return $this->businesses;
     }


    /**
    * Requests a OAuth Access Token
    *
    * @return $oauthAccessToken
    */
    public function generateOAuthAuthorizationUrl($obj) {
        if(!array_key_exists('client_id', $obj)) {
            throw new \Exception('Please specify client_id');
        }

        if(!array_key_exists('state', $obj)) {
            throw new \Exception('Please specify state');
        }

        return Config::OAUTH_URL . 'authorize?client_id=' . $obj['client_id'] . '&state=' . $obj['state'];
    }

     /**
      * Requests a OAuth Access Token
      *
      * @return $oauthAccessToken
      */
     public function requestOAuthToken(OAuthTokenRequest $token_request) {
        $request = new ApiRequest(
            'POST',
            'oauth',
            'oauth',
            null,
            null,
            null,
            $this->apiBaseUrl,
            $this->apiRequestTimeout
        );

        return $request->requestOAuthToken($token_request);
     }

     /**
      * Sets a OAuth Access Token to beeing used as the access_key
      *
      * @return $oauthAccessToken
      */
     public function setOAuthAccessToken($oauthToken) {
         $this->accessKey = 'Bearer ' . $oauthToken;
         $this->fetchBusinesses();
     }

     /**
      * Retrieves all available Business for the current Client
      *
      * @return \Business[]
      */
     public function fetchBusinesses($setDefault = true) {
        if($this->accessKey) {
            $request = new ApiRequest(
                "GET",
                $this->accessKey,
                Config::BUSINESS_URL,
                "array<Eversign\Business>",
                null,
                null,
                $this->apiBaseUrl,
                $this->apiRequestTimeout
            );
            $this->businesses = $request->startRequest();

            if ($setDefault && count($this->businesses) > 0) {

                if (!$this->businessId) {
                    //Set the default Business to the primary Business of the Client
                    $this->selectedBusiness = array_filter(
                        $this->businesses,
                        function ($e) {
                            return $e->getIsPrimary() == 1;
                        }
                    )[0];
                }
                else {
                    //Search the Array for the specified BusinessId
                    $filteredBusinesses = array_filter(
                        $this->businesses,
                        function ($e) {
                            return $e->getBusinessId() == $this->businessId;
                        }
                    );

                    if(!$filteredBusinesses || count($filteredBusinesses) == 0) {
                        throw new \Exception('No Business found with the specified Business Id');
                    }
                    else {
                        $this->selectedBusiness = array_values($filteredBusinesses)[0];
                    }
                }
            }
        }
     }

    public function getInfo() {
        if($this->accessKey) {
            $parameters = [
                "business_id" => $this->selectedBusiness->getBusinessId(),
            ];

            $request = new ApiRequest(
                'GET',
                $this->accessKey,
                'info',
                "Eversign\Info",
                $parameters,
                null,
                $this->apiBaseUrl,
                $this->apiRequestTimeout
            );

            return $request->startRequest();
        }
    }


     private function getDocuments($type = "all") {
        $parameters = [
            "business_id" => $this->selectedBusiness->getBusinessId(),
            "type" => $type
        ];

        $request = new ApiRequest(
            "GET",
            $this->accessKey,
            Config::DOCUMENT_URL,
            "array<Eversign\Document>",
            $parameters,
            null,
            $this->apiBaseUrl,
            $this->apiRequestTimeout
        );

        return $request->startRequest();
     }

     /**
      * Returns all Documents for the Client without filtering the state
      * Only exception are deleted Documents
      * @return \Document[]
      */
     public function getAllDocuments() {
         return $this->getDocuments();
     }

     /**
      * Returns all Completed Documents for the Client
      * @return \Document[]
      */
     public function getCompletedDocuments() {
         return $this->getDocuments("completed");
     }

     /**
      * Returns all Documents which are still in Draft
      * @return \Document[]
      */
     public function getDraftDocuments() {
         return $this->getDocuments("drafts");
     }

     /**
      * Returns all canceled Documents for the Client
      * @return \Document[]
      */
     public function getCanceledDocuments() {
         return $this->getDocuments("cancelled");
     }

     /**
      * Returns all Documents for the Client which require Actions
      * from the User
      * @return \Document[]
      */
     public function getActionRequiredDocuments() {
         return $this->getDocuments("my_action_required");
     }

     /**
      * Returns all Documents for the Client which are waiting on responses
      * from others.
      * @return \Document[]
      */
     public function getWaitingForOthersDocuments() {
         return $this->getDocuments("waiting_for_others");
     }

     /**
      * Returns a list of Documents which are set to be Templates
      * @return \Document[]
      */
     public function getTemplates() {
         return $this->getDocuments("templates");
     }

     /**
      * Returns a list of Documents which are set to be Templates
      * which are also set to be archived
      * @return \Document[]
      */
     public function getArchivedTemplates() {
         return $this->getDocuments("templates_archived");
     }

     /**
      * Returns a list of Documents which are set to be Templates
      * which are also set to be drafts
      * @return \Document[]
      */
     public function getDraftTemplates() {
         return $this->getDocuments("template_drafts");
     }

     /**
      * Sending a Reminder to a specific Signer inside a Document
      * Both properties are required in order to send the request.
      * Returns true or false whether the reminder has been sent
      * @param \Eversign\Document $document
      * @param \Eversign\Signer $signer
      * @throws Exception
      * @return boolean success
      */
     public function sendReminderForDocument(Document $document, Signer $signer) {
        if (!$document->getDocumentHash() || !$document->getSigners()) {
            throw new \Exception('Sending Reminders requires the Document Hash and an approriate Signer');
        }

        $parameters = [
            "business_id" => $this->selectedBusiness->getBusinessId(),
        ];

        $payLoad = [
            "document_hash" => $document->getDocumentHash(),
            "signer_id" => $signer->getId()
        ];

        $payLoad = json_encode($payLoad);
        $request = new ApiRequest(
            "POST",
            $this->accessKey,
            Config::REMINDER_URL,
            "Eversign\Result",
            $parameters,
            $payLoad,
            $this->apiBaseUrl,
            $this->apiRequestTimeout
        );

        return $request->startRequest()->success;
     }

     /**
      * Fetches the Document with the specified Hash from the API
      * @param string $documentHash
      * @return \Eversign\Document
      */
     public function getDocumentByHash($documentHash) {
        $parameters = [
            "business_id" => $this->selectedBusiness->getBusinessId(),
            "document_hash" => $documentHash
        ];

        $request = new ApiRequest(
            "GET",
            $this->accessKey,
            Config::DOCUMENT_URL,
            "Eversign\Document",
            $parameters,
            null,
            $this->apiBaseUrl,
            $this->apiRequestTimeout
        );

        return $request->startRequest();
     }

     public function createDocumentFromTemplate(DocumentTemplate $template) {
        if (!$template->getTemplateId()) {
            throw new \Exception('Template needs a Template Id to create a document from it');
        }
        if (!$template->getSigners() || count($template->getSigners()) == 0) {
            throw new \Exception('Template needs at least 1 Signer to create a Document');
        }

        $parameters = [
            "business_id" => $this->selectedBusiness->getBusinessId(),
        ];

        $serializer = SerializerBuilder::create()->build();
        $payLoad = $serializer->serialize($template, 'json');

        $request = new ApiRequest(
            "POST",
            $this->accessKey,
            Config::DOCUMENT_URL,
            "Eversign\Document",
            $parameters,
            $payLoad,
            $this->apiBaseUrl,
            $this->apiRequestTimeout
        );

        return $request->startRequest();
     }

     /**
      * Sends the Document instance to the API and returns it with properties
      * filled out by the API.
      * @param \Eversign\Document $document
      * @return \Eversign\Document
      * @throws \Exception
      */
     public function createDocument(Document $document) {
        if (!$document->getSigners() || count($document->getSigners()) == 0) {
            throw new \Exception('Document needs at least 1 Signer to be created');
        }

        $parameters = [
            "business_id" => $this->selectedBusiness->getBusinessId(),
        ];

        foreach($document->getFiles() as $file) {
            if($file->getFilePath()) {
                $this->uploadFile($file);
            }
        }

        $serializer = SerializerBuilder::create()->build();
        $payLoad = $serializer->serialize($document, 'json');

        $request = new ApiRequest(
            "POST",
            $this->accessKey,
            Config::DOCUMENT_URL,
            "Eversign\Document",
            $parameters,
            $payLoad,
            $this->apiBaseUrl,
            $this->apiRequestTimeout
        );

        return $request->startRequest();
     }

     /**
      * Uploads a local file to the API and returns its properties inside the File instance.
      * @param \Eversign\File $file
      * @return \Eversign\File
      * @throws \Exception
      */
     public function uploadFile(File $file) {
        if (!$file->getFilePath()) {
            throw new \Exception('File needs a local file Path to be uploaded');
        }

        $parameters = [
            "business_id" => $this->selectedBusiness->getBusinessId(),
        ];


        $request = new ApiRequest(
            "POST",
            $this->accessKey,
            Config::FILE_URL,
            NULL,
            $parameters,
            $file->getFilePath(),
            $this->apiBaseUrl,
            $this->apiRequestTimeout
        );

        $response = $request->startMultipartUpload();

        if(isset($response->file_id)) {
            $file->setFileId($response->file_id);
        }

        return $response;
     }

     /**
      * Downloads the completed Document. Only works on Documents that have
      * been completed. If you want the Audit Trail on the downloaded File
      * as well, set the auditTrail Parameter to true
      * @param \Eversign\Document $document
      * @param string $path
      * @param boolean $auditTrail
      * @return boolean success
      */
     public function downloadFinalDocumentToPath(Document $document, $path, $auditTrail = false) {
        if (!$document->getIsCompleted()) {
            throw new \Exception('To Download the final File the Document needs to be completed first');
        }
        return $this->downloadDocumentToPath($document, $path, $auditTrail);
     }

    /**
     * Downloads the Audit Trail to the specified Path.
     * Returns true if saving was successful, otherwise false.
     * @param \Eversign\Document $document
     * @param string $path
     * return boolean success
     */
     public function downloadAuditTrail(Document $document, $path, $auditTrail = true){
        if (!$document->getIsCompleted()) {
            throw new \Exception('To Download the final File the Document needs to be completed first');
        }
        return $this->downloadDocumentToPath($document, $path, $auditTrail, $type = Config::DOCUMENT_FINAL_URL, "AT");
     }

     /**
      * Downloads the raw Document to the specified Path.
      * Returns true if saving was successful, otherwise false
      * @param \Eversign\Document $document
      * @param string $path
      * @return boolean success
      */
     public function downloadRawDocumentToPath(Document $document, $path) {
         return $this->downloadDocumentToPath($document, $path, 0, Config::DOCUMENT_RAW_URL);
     }

    public function downloadDocumentToPath(Document $document, $path, $auditTrail = 0, $type = Config::DOCUMENT_FINAL_URL, $documentId = "") {
        if (!$path || !$document) {
            throw new \Exception('To Download the Document you need to set a path and the document');
        }

        $parameters = [
            "business_id" => $this->selectedBusiness->getBusinessId(),
            "document_hash" => $document->getDocumentHash(),
            "audit_trail" => $auditTrail,
            "document_id" => $documentId
        ];

        $payLoad = [
            "sink" => $path
        ];

        $request = new ApiRequest(
            "GET",
            $this->accessKey,
            $type,
            "Eversign\Document",
            $parameters,
            $payLoad,
            $this->apiBaseUrl,
            $this->apiRequestTimeout
        );

        return $request->startRequest();
     }

    public function downloadFinalDocument(Document $document, $auditTrail = 0, $onlyUrl = false, $documentId = "") {

        $parameters = [
            "business_id" => $this->selectedBusiness->getBusinessId(),
            "document_hash" => $document->getDocumentHash(),
            "audit_trail" => $auditTrail,
            "document_id" => $documentId,
            "url_only" => $onlyUrl
        ];

        $payLoad = $onlyUrl ? null : ["stream" => false];

        $request = new ApiRequest(
            "GET",
            $this->accessKey,
            Config::DOCUMENT_FINAL_URL,
            $onlyUrl ? "Eversign\Url" : "",
            $parameters,
            $payLoad,
            $this->apiBaseUrl,
            $this->apiRequestTimeout
        );

        return $request->startRequest();
    }

     /**
      * Deletes the specified Document. Only works on Drafts and canceled Documents
      * @param \Eversign\Document $document
      * @param string $type
      * @return boolean success
      * @throws \Exception
      */
     public function deleteDocument(Document $document, $type=NULL) {
        if (!$document->getDocumentHash()) {
            throw new \Exception('Deleting/Cancelling the Document requires the Document Hash');
        }
        if ($type !== 'cancel' && !$document->getIsDraft() && !$document->getIsCancelled()) {
            throw new \Exception('Only Drafts and cancelled Documents can be deleted');
        }
        if ($document->getIsDeleted()) {
            throw new \Exception('The Document has been deleted already');
        }

        $parameters = [
            "business_id" => $this->selectedBusiness->getBusinessId(),
            "document_hash" => $document->getDocumentHash()
        ];

        if($type) {
            $parameters[$type] = 1;
        }

        $request = new ApiRequest(
            "DELETE",
            $this->accessKey,
            Config::DOCUMENT_URL,
            "Eversign\Result",
            $parameters,
            null,
            $this->apiBaseUrl,
            $this->apiRequestTimeout
        );

        return $request->startRequest()->success;
     }

     /**
      * Cancels the specified Document. After canceling the Document
      * it can be deleted.
      * @param type $document
      * @return boolean success
      */
     public function cancelDocument($document) {
         return $this->deleteDocument($document, "cancel");
     }

    /**
     * Sends the Document instance to the API and returns it with properties
     * filled out by the API.
     * @param \Eversign\Document $document
     * @return mixed
     * @throws \Exception
     */
    public function updateDocument(Document $document)
    {
        if (!$document->getSigners() || count($document->getSigners()) == 0) {
            throw new \Exception('Document needs at least 1 Signer to be created');
        }

        $parameters = [
            "business_id" => $this->selectedBusiness->getBusinessId(),
        ];

        foreach ($document->getFiles() as $file) {
            if ($file->getFilePath()) {
                $this->uploadFile($file);
            }
        }

        $serializer = SerializerBuilder::create()->build();
        $payLoad = $serializer->serialize($document, 'json');

        $request = new ApiRequest(
            "PUT",
            $this->accessKey,
            Config::DOCUMENT_URL,
            "Eversign\Document",
            $parameters,
            $payLoad,
            $this->apiBaseUrl,
            $this->apiRequestTimeout
        );

        return $request->startRequest();
    }
}
