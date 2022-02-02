<?php
require_once '../vendor/autoload.php';

$config = require('./config.php');

use Eversign\Client;
use Eversign\Document;
use Eversign\Field;
use Eversign\Signer;
use Eversign\Recipient;
use Eversign\File;
use Eversign\SignatureField;
use Eversign\InitialsField;
use Eversign\DateSignedField;
use Eversign\CheckboxField;
use Eversign\RadioField;
use Eversign\DropdownField;
use Eversign\TextField;
use Eversign\AttachmentField;

// Client with default API request timeout
// $client = new Client($config['accessKey'], $config['businessId']);

// Client with custom API request timeout
$client = new Client($config['accessKey'], $config['businessId'], null, $config['api_request_timeout']);

// Create new Document
$document = new Document();
// $document->setSandbox(true);
$document->setTitle('Form Test');
$document->setSandbox(true);
$document->setMessage('Test Message');
$document->setEmbeddedSigningEnabled(true);
$document->setFlexibleSigning(false); // remove all fields to try this
$document->setUseHiddenTags(true);
$document->setRequireAllSigners(true);
$document->setUseSignerOrder(true);
$document->setCustomRequesterName('My Custom Requester Name');
$document->setCustomRequesterEmail($config['requesterEmail']);

$date = new DateTime();
$date->add(new DateInterval('P14D'));
$document->setExpires($date);

//Create a Signer for the Document
$signer = new Signer();
$signer->setName('John Doe');
$signer->setEmail($config['signerEmail']);
$signer->setDeliverEmail(true); // only used if embedded_signing_enabled is used
$signer->setLanguage('de');
$document->appendSigner($signer);

//Create a Recipient for the Document
$recipient = new Recipient();
$recipient->setName('John Doe Recipient');
$recipient->setEmail($config['recipientEmail']);
$recipient->setLanguage('pt');
$document->appendRecipient($recipient);

//Set Custom Meta Tags to the Document
$document->setMeta([
   'test' => 'value',
   'test1' => 'value1'
]);

//Appending and Removing Meta Tags
$document->appendMeta('test2', 'value2');
$document->removeMeta('test');

//Add a File to the Document
$file = new File();
$file->setName('Contract');
$file->setFilePath(getcwd() . '/raw.pdf');
$document->appendFile($file);

//Add FormFields to the Document
$signatureField = new SignatureField();
$signatureField->setFileIndex(0);
$signatureField->setPage(1);
$signatureField->setX(30);
$signatureField->setY(150);
$signatureField->setRequired(true);
$signatureField->setSigner('1');
$document->appendFormField($signatureField);

$initialsField = new InitialsField();
$signatureField->setFileIndex(0);
$initialsField->setPage(1);
$initialsField->setX(30);
$initialsField->setY(250);
$initialsField->setRequired(true);
$initialsField->setSigner('1');
$document->appendFormField($initialsField);

$dateSignedField = new DateSignedField();
$signatureField->setFileIndex(0);
$dateSignedField->setPage(1);
$dateSignedField->setX(30);
$dateSignedField->setY(350);
$dateSignedField->setSigner('1');
$dateSignedField->setTextSize(16);
$dateSignedField->setTextStyle('BU');
$document->appendFormField($dateSignedField);

$textField = new TextField();
$signatureField->setFileIndex(0);
$textField->setSigner(1);
$textField->setReadOnly(0);
$textField->setPage(1);
$textField->setX(10);
$textField->setY(50);
$textField->setValue('Test Textfield');
$document->appendFormField($textField);

$checkboxField = new CheckboxField();
$checkboxField->setName('Test Checkbox');
$signatureField->setFileIndex(0);
$checkboxField->setX(30);
$checkboxField->setPage(1);
$checkboxField->setY(150);
$checkboxField->setValue('1');
$document->appendFormField($checkboxField);

$radioboxField = new RadioField();
$radioboxField->setName('Test Radio');
$signatureField->setFileIndex(0);
$radioboxField->setX(10);
$radioboxField->setY(50);
$radioboxField->setSigner('1');
$radioboxField->setName('Radio 1');
$radioboxField->setGroup('0');
$document->appendFormField($radioboxField);

$radioboxField1 = new RadioField();
$radioboxField1->setName('Test Radio 2');
$signatureField->setFileIndex(0);
$radioboxField1->setX(10);
$radioboxField1->setY(70);
$radioboxField1->setSigner('1');
$radioboxField1->setName('Radio 2');
$radioboxField1->setValue('1');
$radioboxField1->setGroup('0');
$document->appendFormField($radioboxField1);

$attachmentField = new AttachmentField();
$signatureField->setFileIndex(0);
$attachmentField->setX(10);
$attachmentField->setY(100);
$attachmentField->setName('Test Attachment');
$attachmentField->setSigner('1');
$document->appendFormField($attachmentField);

$dropdownField = new DropdownField();
$signatureField->setFileIndex(0);
$dropdownField->setX(10);
$dropdownField->setY(100);
$dropdownField->setWidth(150);
$dropdownField->setTextFont('calibri');
$dropdownField->setSigner('1');
$dropdownField->setOptions(['Test 1', 'Test 2', 'Test 3']);
$dropdownField->setValue('Test 1');
$document->appendFormField($dropdownField);

//Saving the created document to the API.
$newlyCreatedDocument = $client->createDocument($document);
echo $newlyCreatedDocument->getDocumentHash();
