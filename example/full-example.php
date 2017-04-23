<?php
require_once '../vendor/autoload.php';

use Eversign\Client;
use Eversign\Document;
use Eversign\Signer;
use Eversign\File;
use Eversign\SignatureField;
use Eversign\InitialsField;
use Eversign\DateSignedField;
use Eversign\CheckboxField;
use Eversign\RadioField;
use Eversign\DropdownField;
use Eversign\TextField;
use Eversign\AttachmentField;

echo "<h1>Create Client</h1>";
$client = new Client("MY_API_KEY");

echo "<h1>Check Documents</h1>";
$documents = $client->getAllDocuments();

echo "<h1>Load Document with Hash and download</h1>";
$document = $client->getDocumentWithHash("MY_HASH");

$client->downloadFinalDocumentToPath($document, getcwd() . "/final.pdf", true);
$client->downloadRawDocumentToPath($document, getcwd() ."/raw.pdf");

echo "<h1>Send Reminder</h1>";
$client->sendReminderForDocument($document, $document->getSigners()[0]);

echo "<h1>Delete Document</h2>";
$client->deleteDocument($document);

echo "<h1>Cancel Document</h2>";
$client->cancelDocument($document);

echo "<h1>Create Document</h1>";
$document = new Document();
$document->setTitle("formtest1");
$document->setMessage("jaja");

//Create a Signer for the Document
$signer = new Signer();
$signer->setName("Patrick");
$signer->setEmail("patrick.leeb@gmail.com");
$signer->setRequired(true);
$document->appendSigner($signer);

//Add a File to the Document
$file = new File();
$file->setName("GIS");
$file->setFilePath(getcwd() . "/GIS.pdf");
$document->appendFile($file);

//Add FormFields to the Document
$signatureField = new SignatureField();
$signatureField->setX(30);
$signatureField->setY(150);
$signatureField->setPage(2);
$signatureField->setRequired(true);
$signatureField->setSigner("1");
$document->appendFormField($signatureField);

$initialsField = new InitialsField();
$initialsField->setX(30);
$initialsField->setY(250);
$initialsField->setPage(2);
$initialsField->setRequired(true);
$initialsField->setSigner("1");
$document->appendFormField($initialsField);

$dateSignedField = new DateSignedField();
$dateSignedField->setX(30);
$dateSignedField->setY(350);
$dateSignedField->setPage(2);
$dateSignedField->setSigner("1");
$dateSignedField->setTextSize(16);
$dateSignedField->setTextStyle("BU");
$document->appendFormField($dateSignedField);

$textField = new TextField();
$textField->setX(10);
$textField->setY(50);
$textField->setPage(2);
$textField->setValue("blablablabla");

$document->appendFormField($textField);

$checkboxField = new CheckboxField();
$checkboxField->setName("Test Checkbox");
$checkboxField->setX(30);
$checkboxField->setY(150);
$checkboxField->setValue("1");
$checkboxField->setPage(2);

$document->appendFormField($checkboxField);

$radioboxField = new RadioField();
$radioboxField->setName("Test Radio");
$radioboxField->setX(10);
$radioboxField->setY(50);
$radioboxField->setSigner("1");
$radioboxField->setName("Radio 1");
$radioboxField->setGroup("0");
$document->appendFormField($radioboxField);

$radioboxField1 = new RadioField();
$radioboxField1->setName("Test Radio 2");
$radioboxField1->setX(10);
$radioboxField1->setY(70);
$radioboxField1->setSigner("1");
$radioboxField1->setName("Radio 2");
$radioboxField1->setValue("1");
$radioboxField1->setGroup("0");
$document->appendFormField($radioboxField1);

$attachmentField = new AttachmentField();
$attachmentField->setX(10);
$attachmentField->setY(100);
$attachmentField->setName("My Attachment");
$attachmentField->setSigner("1");
$document->appendFormField($attachmentField);


$dropdownField = new DropdownField();
$dropdownField->setX(10);
$dropdownField->setY(100);
$dropdownField->setWidth(150);
$dropdownField->setTextFont("calibri");
$dropdownField->setSigner("1");
$dropdownField->setOptions(["Test 1", "Test 2", "Test 3"]);
$dropdownField->setValue("Test 1");
$document->appendFormField($dropdownField);

//Saving the created document to the API.
$client->createDocument($document);
