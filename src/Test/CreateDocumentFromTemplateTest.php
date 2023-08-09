<?php

namespace Eversign\Test;

use Eversign\Client;
use Eversign\DocumentTemplate;
use Eversign\Field;
use Eversign\Signer;
use Eversign\SignatureField;


class CreateDocumentFromTemplateTest extends \PHPUnit_Framework_TestCase
{

    public function testCall()
    {

        $client = new Client('test_access_key', 1337, getenv('SDK_TESTING_MOCK_URL') ? getenv('SDK_TESTING_MOCK_URL') : 'http://localhost:8888/api/');

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
        $createdDocument = $client->createDocumentFromTemplate($documentTemplate);

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
        $this->assertSame($createdDocument->getEmbeddedSigningEnabled(), true);
        $this->assertSame($createdDocument->getRedirectDecline(), '');
        $this->assertSame($createdDocument->getClient(), '');
        $this->assertEquals($createdDocument->getExpires(), (new \DateTime())->setTimestamp(1337133713));

        foreach ($createdDocument->getSigners() as $signer) {
            $this->assertSame($signer->getId(), 1);
            $this->assertSame($signer->getOrder(), 1);
            $this->assertSame($signer->getPin(), '');
            $this->assertSame($signer->getSigned(), false);
            $this->assertSame($signer->getSigned_timestamp(), ''); // TODO: check format in JSON
            $this->assertSame($signer->getDeclined(), false);
            $this->assertSame($signer->getSent(), true);
            $this->assertSame($signer->getViewed(), false);
            $this->assertSame($signer->getStatus(), 'waiting_for_signature');
            $this->assertSame($signer->getEmbeddedSigningUrl(), 'test_embedded_signing_url');
            $this->assertSame($signer->getDeliverEmail(), false);
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

        foreach ($createdDocument->getFields()[0] as $field) {
            // $this->assertSame($field->getValue(), '');
            $this->assertSame(get_class($field), 'Eversign\SignatureField');
            $this->assertSame($field->getIdentifier(), 'test_signature_identifier');
            $this->assertSame($field->getSigner(), '1');

            $this->assertSame($field->getPage(), 1);
            $this->assertSame($field->getWidth(), 13);
            $this->assertSame($field->getHeight(), 37);
            $this->assertSame($field->getX(), 13.37);
            $this->assertSame($field->getY(), 13.37);
            $this->assertSame($field->getFileIndex(), null);
        }

        $this->assertArrayHasKey('test1', $createdDocument->getMeta());
        $this->assertArrayHasKey('test2', $createdDocument->getMeta());
        $this->assertSame($createdDocument->getMeta()['test1'], 'value1');
        $this->assertSame($createdDocument->getMeta()['test2'], 'value2');
    }
}
