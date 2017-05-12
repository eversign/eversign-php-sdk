<?php
require_once '../vendor/autoload.php';

use Eversign\Client;

$client = new Client("MY_API_KEY", 123456);

// Get all documents
$documents = $client->getAllDocuments();
print_r($documents);

// Get a single document
$document = $client->getDocumentByHash("MY_DOCUMENT_HASH");

// download said document
$client->downloadFinalDocumentToPath($document, getcwd() . "/final.pdf", true);
$client->downloadRawDocumentToPath($document, getcwd() ."/raw.pdf");

// send a reminder for a signer
$signers = $document->getSigners();
foreach ($signers as $signer) {
    $client->sendReminderForDocument($document, $signer);
}

// cancel a document
$client->cancelDocument($document);

// delete a document
$client->deleteDocument($document);
