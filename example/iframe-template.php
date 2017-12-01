<?php
require_once '../vendor/autoload.php';

$config = require('./config.php');

use Eversign\Client;
use Eversign\DocumentTemplate;
use Eversign\Field;
use Eversign\Signer;

$client = new Client($config['accessKey'], $config['businessId']);

$documentTemplate = new DocumentTemplate();
$documentTemplate->setTemplateId($config['templateId']);
$documentTemplate->setTitle('Form Test');
$documentTemplate->setMessage('Test Message ');

// Enable embedded signing
$documentTemplate->setEmbeddedSigningEnabled(true);

// Create a signer for the document via the role specified in the template
$signer = new Signer();
$signer->setRole('Client');
$signer->setName('John Doe ' . (new DateTime())->format('Y-m-d H:i:s'));
$signer->setEmail($config['signerEmail']);
$signer->setDeliverEmail(0); // false is the default. set this to true to send an additional email to the signer
$documentTemplate->appendSigner($signer);

//Fill out custom fields
$field = new Field();
$field->setIdentifier($config['fieldIdentifier']);
$field->setValue('value 1');

$documentTemplate->appendField($field);

//Creating a new Document from a Template
$newlyCreatedDocument = $client->createDocumentFromTemplate($documentTemplate);

// getting the signing url
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
            alert('loaded callback');
        },
        signed: function () {
            alert('signed callback');
        },
        declined: function () {
            alert('declined callback');
        },
        error: function () {
            alert('error callback');
        }
    }
});
</script>
