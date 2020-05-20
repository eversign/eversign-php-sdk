# eversign PHP SDK

eversign PHP SDK is the official PHP Wrapper around the eversign [API](https://eversign.com/api/documentation).

**Quick Links:**
  - [Create Document Example](/example/create-document.php)
  - [Use Template Example](/example/create-document-from-template.php)
  - [Document Operations](/example/document-operations.php)
  - [Create Iframe Signature](/example/iframe.php)
  - [Create Iframe Signature From Template](/example/iframe-template.php)
  - [OAuth Flow (start)](/example/oauth/index.php)
  - [OAuth Flow (callback)](/example/oauth/callback.php)

## Requirements

  - The latest version of the SDK requires PHP version 7.1 or higher.
  - Installation via [Composer](https://getcomposer.org/) is recommended

## Installation
- Install Composer if you haven't already

```
curl -sS https://getcomposer.org/installer | php
```

- Add the eversign PHP SDK as a dependency using the composer.phar CLI:

```
php composer.phar require eversign/eversign-php-sdk:~1.0
```

  or add it directly to your composer.json

```
{
  "require": {
    "eversign/eversign-php-sdk": "~1.0"
  }
}
```

- After installing, you need to require Composer's autoloader

```
require 'vendor/autoload.php';
```

## Usage
All eversign API requests are made using the `Eversign\Client` class, which contains all methods for creating, retrieving and saving documents. This class must be initialized with your API access key string. [Where is my API access key?](https://eversign.com/api/documentation/intro#api-access-key)


Please also specify the ID of the eversign business you would like this API request to affect. [Where is my Business ID?](https://eversign.com/api/documentation/intro#business-selection)

```
$client = new Client("MY_API_KEY", $businessId);
```

### Fetch businesses
Using the `getBusinesses()` function all businesses on the eversign account will be fetched and listed along with their Business IDs.

```
$businesses = $client->getBusinesses();
echo $businesses[0]->getBusinessId();

$client->setSelectedBusiness($businesses[0]);
```

If you know the `businessId` beforehand you can also set it with `setSelectedBusinessById(businessId)`

```
$client->setSelectedBusinessById(1337);
```

### Create document from template [Method: Use Template]
To create a document based on an already created template you can use the class `Eversign\DocumentTemplate`. In order to identify which template should be used, please pass the template's ID into the `setTemplateId("MY_TEMPLATE_ID")` function.


Additionally, `setTitle()` and `setMessage()` can be used to set a title and message for the newly created document.


```
$documentTemplate = new DocumentTemplate();
$documentTemplate->setTemplateId("MY_TEMPLATE_ID");
$documentTemplate->setTitle("Form Test");
$documentTemplate->setMessage("Test Message");
```

#### Fill signing roles [Method: Use Template]
A template's signing and CC roles are filled just using the functions below. Each role is identified using the `setRole()` function, must carry a name and email address and is appended to the document using the `appendSigner()` function.

```
$signer = new Signer();
$signer->setRole("Testrole");
$signer->setName("John Doe");
$signer->setEmail("john.doe@eversign.com");
$documentTemplate->appendSigner($signer);
```

#### Saving the document object [Method: Use Template]
Your document object can now be saved using the `createDocumentFromTemplate()` function. Once this function has been called successfully, your document is created and the signing process is started.


```
$newlyCreatedDocument = $client->createDocumentFromTemplate($documentTemplate);
$newlyCreatedDocument->getDocumentHash();
```

### Creating a document [Method: Create Document]
A document is created by instantiating the `Eversign\Document` object and setting your preferred document properties. There is a series of `set` methods available used to specify options for your document. All available methods can be found inside our extensive [Create Document Example](/example/create-document.php).


```
$document = new Document();
$document->setTitle("My Title");
$document->setMessage("My Message");
```

#### Adding signers to a document [Method: Create Document]
Signers are added to an existing document object by instantiating the `Eversign\Signer` object and appending each signer to the document object. Each signer object needs to come with a Signer ID, which is later used to assign fields to the respective signer. If no Signer ID is specified, the `appendSigner()` method will set a default incremented Signer ID. Each signer object also must contain a name and email address and is appended to the document using the `appendSigner()` method.

```
$signer = new Signer();
$signer->setId("1");
$signer->setName("John Doe");
$signer->setEmail("john.doe@eversign.com");
$document->appendSigner($signer);
```

#### Adding recipients (CCs) to a document [Method: Create Document]
Recipients (CCs) are added by instantiating the `Eversign\Recipient` object and appending each recipient to the document object. Just like signers, recipients must carry a name and email address.

```
$recipient = new Recipient();
$recipient->setName("John Doe");
$recipient->setEmail("john.doe@eversign.com");
$document->appendRecipient($recipient);
```

#### Adding files to the Document [Method: Create Document]
Files are added to a document by instantiating an `Eversign\File` object. The standard way of choosing a file to upload is appending the file's path using the `setFilePath()` method and then appending your file using the `appendFile()` method.

Uploading a file is mandatory without which the createDocument method will fail. As an alternative you may upload a blank PDF and add 'note' or 'text' field types.

```
$file = new File();
$file->setName("My File");
$file->setFilePath(getcwd() . "/file.pdf");
$document->appendFile($file);
```

#### Adding fields [Method: Create Document]
There is a number of fields that can be added to a document, each coming with different options and parameters. ([View Full list of fields »](https://eversign.com/api/documentation/fields))

A field is appended to the document using the `appendFormField($signatureField, $fileIndex)` method. The first function parameter is the field object, and the second parameter must contain the index of the file it should be added to. If your field should be placed onto the first uploaded file, set this parameter to `0`. This parameter also default to `0`.

Signature and Initials fields are required to be assigned to a specific signer. Fields are assigned to a signer by passing the **Signer ID** into the `setSigner()` function.


```
$signatureField = new SignatureField();
$signatureField->setFileIndex(0);
$signatureField->setPage(2);
$signatureField->setX(30);
$signatureField->setY(150);
$signatureField->setRequired(true);
$signatureField->setSigner("1");
$document->appendFormField($signatureField, $fileIndex);
```

A full example containing instructions and methods for each available field type can be found here: [Create Document Example](/example/create-document.php)

**Available field types**

Please find below all available field types:

| Field Type  | Class |
| ------------- | ------------- |
| date_signed  | Eversign\DateSignedField  |
| signature  | Eversign\SignatureField  |
| initials  | Eversign\InitialsField  |
| note  | Eversign\NoteField  |
| text  | Eversign\TextField  |
| checkbox  | Eversign\CheckboxField  |
| radio  | Eversign\RadioField  |
| dropdown  | Eversign\DropdownField  |
| attachment  | Eversign\AttachmentField  |


#### Saving a document [Method: Create Document]
A document is saved and sent out by passing the final document object into the `createDocument` method. The API will return the entire document object array in response.

```
$newDocument = $client->createDocument($document);
$newDocument->getDocumentHash();
```

#### Loading a document
*Class: Document*

A document is loaded by passing its document hash into the `getDocumentByHash()` method.

```
$document = $client->getDocumentByHash("MY_HASH");
```

#### Downloading the raw or final document
*Class: Client*

A document can be downloaded either in its raw or in its final (completed) state. In both cases, the respective method must contain the document object and a path to save the PDF document to. When downloading a final document, you can choose to attach the document's Audit Trail by setting the third parameter to `true`.

```
$client->downloadFinalDocumentToPath($document, "final.pdf", $attachAuditTrail);
$client->downloadRawDocumentToPath($document, "raw.pdf");
```

#### Get a list of documents or templates
*Class: Client*

The Client class is also capable fo listing all available documents templates based on their status. Each method below returns an array of document objects.

```
$client->getAllDocuments();
$client->getCompletedDocuments();
$client->getDraftDocuments();
$client->getCanceledDocuments();
$client->getActionRequiredDocuments();
$client->getWaitingForOthersDocuments();

$client->getTemplates();
$client->getArchivedTemplates();
$client->getDraftTemplates();
```

#### Delete or cancel a document
*Class: Client*

A document is cancelled or deleted using the methods below.

```
$client->deleteDocument($document);
$client->cancelDocument($document);
```

### Contact us
Any feedback? Please feel free to [contact our support team](https://eversign.com/contact).

### Development
```
# Install composer dependencies
docker run --rm -v $(pwd):/app composer/composer install

# run docker compose
docker-compose up -d

# point your browser to localhost:8080
curl http://localhost:8080

# run tests
docker-compose exec eversign-sdk-php vendor/bin/phpunit sdk/Eversign/Test/ClientTest.php --colors=never
```
