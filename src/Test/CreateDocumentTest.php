<?php

namespace Eversign\Test;

use Eversign\Client;
use Eversign\Document;
use Eversign\Field;
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

class CreateDocumentTest extends \PHPUnit_Framework_TestCase
{

    public function testCall()
    {

        $client = new Client('test_access_key', 1337, getenv('SDK_TESTING_MOCK_URL') ? getenv('SDK_TESTING_MOCK_URL') : 'http://localhost:8888/api/');

        $document = new Document();
        $document->setSandbox(true);
        $document->setTitle('test_title');
        $document->setMessage('test_message');

        //Create a Signer for the Document
        $signer = new Signer();
        $signer->setName('test_name');
        $signer->setEmail('test_email');
        $document->appendSigner($signer);

        //Set Custom Meta Tags to the Document
        $document->setMeta([
           'test1' => 'value1',
           'test2' => 'value2'
        ]);

        //Add a File to the Document
        $file = new File();
        $file->setName('test_file_name');
        $file->setFilePath(__DIR__ . '/raw.pdf');
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
        $createdDocument = $client->createDocument($document);

        $this->assertSame($createdDocument->getDocumentHash(), 'test_document_hash');
        $this->assertSame($createdDocument->getRequesterEmail(), 'test_requester_email');
        $this->assertSame($createdDocument->getSandbox(), false);
        $this->assertSame($createdDocument->getIsDraft(), false);
        $this->assertSame($createdDocument->getIsCompleted(), false);
        $this->assertSame($createdDocument->getIsArchived(), false);
        $this->assertSame($createdDocument->getIsDeleted(), false);
        $this->assertSame($createdDocument->getIsTrashed(), false);
        $this->assertSame($createdDocument->getIsCancelled(), false);
        $this->assertSame($createdDocument->getEmbedded(), false);
        $this->assertSame($createdDocument->getTitle(), 'test_title');
        $this->assertSame($createdDocument->getMessage(), 'test_message');
        $this->assertSame($createdDocument->getUseSignerOrder(), false);
        $this->assertSame($createdDocument->getIsTemplate(), false);
        $this->assertSame($createdDocument->getReminders(), false);
        $this->assertSame($createdDocument->getRequireAllSigners(), false);
        $this->assertSame($createdDocument->getRedirect(), '');
        $this->assertSame($createdDocument->getEmbeddedSigningEnabled(), false);
        $this->assertSame($createdDocument->getRedirectDecline(), '');
        $this->assertSame($createdDocument->getClient(), '');
        $this->assertEquals($createdDocument->getExpires(), (new \DateTime())->setTimestamp(1337133713));

        foreach ($createdDocument->getSigners() as $signer) {
            $this->assertSame($signer->getId(), 1);
            $this->assertSame($signer->getOrder(), 0);
            $this->assertSame($signer->getPin(), '');
            $this->assertSame($signer->getSigned(), false);
            $this->assertSame($signer->getSigned_timestamp(), ''); // TODO: check format in JSON
            $this->assertSame($signer->getDeclined(), false);
            $this->assertSame($signer->getSent(), true);
            $this->assertSame($signer->getViewed(), false);
            $this->assertSame($signer->getStatus(), 'waiting_for_signature');
            $this->assertSame($signer->getDeliverEmail(), true);
        }

        foreach ($createdDocument->getFiles() as $file) {
            $this->assertSame($file->getFileId(), 'test_file_id');
            $this->assertSame($file->getName(), 'test_file_name');
            $this->assertSame($file->getPages(), 1);
            $this->assertSame($file->getTotalPages(), null);
            $this->assertSame($file->getFileUrl(), null);
            $this->assertSame($file->getFileBase64(), null);
            $this->assertSame($file->getFilePath(), null);

        }

        foreach ($createdDocument->getLog() as $log) {
            $this->assertSame($log->getEvent(), 'document_created');
            $this->assertSame($log->getSigner(), 0);
            $this->assertEquals($log->getTimestamp(), (new \DateTime())->setTimestamp(1337133713));
        }

        foreach ($createdDocument->getRecipients() as $resipient) {
            $this->assertSame($resipient->getName(), 'test_name');
            $this->assertSame($resipient->getEmail(), 'test_email');
            $this->assertSame($resipient->getRole(), '');
        }

        $field = $createdDocument->getFields()[0][0];

        $this->assertSame(get_class($field), 'Eversign\SignatureField');
        $this->assertSame($field->getIdentifier(), 'SignatureField_0');
        $this->assertSame($field->getSigner(), '1');
        $this->assertSame($field->getPage(), 1);
        $this->assertSame($field->getWidth(), 120);
        $this->assertSame($field->getHeight(), 35);
        $this->assertSame($field->getX(), 30.0);
        $this->assertSame($field->getY(), 150.0);
        $this->assertSame($field->getFileIndex(), null);

        $this->assertArrayHasKey('test1', $createdDocument->getMeta());
        $this->assertArrayHasKey('test2', $createdDocument->getMeta());
        $this->assertSame($createdDocument->getMeta()['test1'], 'value1');
        $this->assertSame($createdDocument->getMeta()['test2'], 'value2');
    }
}
