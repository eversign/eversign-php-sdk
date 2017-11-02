<?php
require_once '../vendor/autoload.php';

$config = require('./config.php');

use Eversign\Client;

$client = new Client($config['accessKey'], $config['businessId']);

// Get all documents
$documents = $client->getAllDocuments();
echo sizeof($documents) . ' documents found';

// Get a single document
$document = $client->getDocumentByHash($config['documentHash']);

// download said document
$client->downloadFinalDocumentToPath($document, getcwd() . '/final.pdf', true);
$client->downloadRawDocumentToPath($document, getcwd() .'/raw.pdf');

// send a reminder for a signer
$signers = $document->getSigners();
foreach ($signers as $signer) {
    $client->sendReminderForDocument($document, $signer);
}

// cancel a document
$client->cancelDocument($document);

// delete a document
$client->deleteDocument($document);
