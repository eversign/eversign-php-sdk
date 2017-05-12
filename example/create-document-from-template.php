<?php
require_once '../vendor/autoload.php';

use Eversign\Client;
use Eversign\DocumentTemplate;
use Eversign\Field;
use Eversign\Signer;

$client = new Client("MY_API_KEY", 123456);

$documentTemplate = new DocumentTemplate();
$documentTemplate->setId("MY_TEMPLATE_ID");
$documentTemplate->setTitle("Form Test");
$documentTemplate->setMessage("Test Message ");

// Create a signer for the document via the role specified in the template
$signer = new Signer();
$signer->setRole("Testrole");
$signer->setName("John Doe");
$signer->setEmail("john.doe@eversign.com");
$documentTemplate->appendSigner($signer);

//Fill out custom fields
$field = new Field();
$field->setIdentifier("identifier1");
$field->setValue("value 1");

$documentTemplate->appendField($field);

//Creating a new Document from a Template
$newlyCreatedDocument = $client->createDocumentFromTemplate($documentTemplate);
echo $newlyCreatedDocument->getDocumentHash();
