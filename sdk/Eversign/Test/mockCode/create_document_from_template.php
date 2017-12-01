<?php

use Eversign\Client;
use Eversign\DocumentTemplate;
use Eversign\Field;
use Eversign\Signer;


$client = new Client('test_access_key', 1337, $stack);

$documentTemplate = new DocumentTemplate();
$documentTemplate->setTemplateId('test_template_id');
$documentTemplate->setTitle('test_title');
$documentTemplate->setMessage('test_message');

// Enable embedded signing
$documentTemplate->setEmbeddedSigningEnabled(true);

// Create a signer for the document via the role specified in the template
$signer = new Signer();
$signer->setRole('test_role');
$signer->setName('test_name');
$signer->setEmail('test_email');
$signer->setDeliverEmail(true);
$documentTemplate->appendSigner($signer);

//Fill out custom fields
$field = new Field();
$field->setIdentifier('test_identifier');
$field->setValue('test_value');

$documentTemplate->appendField($field);

//Creating a new Document from a Template
$client->createDocumentFromTemplate($documentTemplate);
