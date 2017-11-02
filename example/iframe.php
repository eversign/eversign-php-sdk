<?php
require_once '../vendor/autoload.php';

$config = require('./config.php');

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

$client = new Client($config['accessKey'], $config['businessId']);

$document = new Document();
$document->setTitle('Embedded Test');

// Create a Signer for the Document
$signer = new Signer();
$signer->setName('John Doe');
$signer->setEmail($config['signerEmail']);
$document->appendSigner($signer);

// Enable embedded signing
$document->setEmbeddedSigningEnabled(true);

// Add a File to the Document
$file = new File();
$file->setName('Contract');
$file->setFilePath(getcwd() . '/raw.pdf');
$document->appendFile($file);

// Add FormFields to the Document
$signatureField = new SignatureField();
$signatureField->setFileIndex(0);
$signatureField->setPage(1);
$signatureField->setX(30);
$signatureField->setY(350);
$signatureField->setRequired(true);
$signatureField->setSigner('1');
$document->appendFormField($signatureField);

// Saving the created document to the API.
$newlyCreatedDocument = $client->createDocument($document);
$signingUrl = $newlyCreatedDocument->getSigners()[0]->getEmbeddedSigningUrl();

?>

<script type="text/javascript" src="https://s3.amazonaws.com/eversign-embedded-js-library/eversign.embedded.latest.js"></script>
<div id="container"></div>
<script>
eversign.open({
    url: '<?php echo $signingUrl; ?>',
    containerID: 'container',
    width: 600,
    height: 600,
    events: {
        loaded: function () {
            console.log('loaded callback');
        },
        signed: function () {
            console.log('signed callback');
        },
        declined: function () {
            console.log('declined callback');
        },
        error: function () {
            console.log('error callback');
        }
    }
});
</script>
