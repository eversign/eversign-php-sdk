<?php
require_once '../vendor/autoload.php';

$config = require('./config.php');

use Eversign\Client;
use Eversign\DocumentTemplate;
use Eversign\Field;
use Eversign\Signer;

$client = new Client($config['accessKey'], $config['businessId']);

$documentTemplate = new DocumentTemplate();
// $documentTemplate->setSandbox(true);
$documentTemplate->setTemplateId($config['templateId']);
$documentTemplate->setTitle('Form Test');
$documentTemplate->setMessage('Test Message ');

// Create a signer for the document via the role specified in the template
$signer = new Signer();
$signer->setRole('Client');
$signer->setName('John Doe');
$signer->setEmail($config['signerEmail']);
$documentTemplate->appendSigner($signer);

//Fill out custom fields
$field = new Field();
$field->setIdentifier($config['fieldIdentifier']);
$field->setValue('value 1');

$documentTemplate->appendField($field);

//Creating a new Document from a Template
$newlyCreatedDocument = $client->createDocumentFromTemplate($documentTemplate);
echo $newlyCreatedDocument->getDocumentHash();
