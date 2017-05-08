# eversign PHP SDK

Eversign PHP SDK is the official PHP Wrapper around the eversign [API](https://eversign.com/api/documentation).


## Requirements

  - The latest version of the SDK requires PHP version 5.5 or higher.
  - Installation via [Composer](https://getcomposer.org/) is recommended

## Installation
- Install Composer if you haven't already

```
curl -sS https://getcomposer.org/installer | php
```

- Add the eversign PHP SDK as a dependency using the composer.phar CLI:

```
php composer.phar require apilayer/eversign-php-sdk:~1.0
```

  or add it directly to your composer.json

```
{
  "require": {
    "apilayer/eversign-php-sdk": "~1.0"
  }
}
```

- After installing, you need to require Composer's autoloader

```
require 'vendor/autoload.php';
```

## Usage
All eversign API requests are made using the Eversign\Client class. This class must be initialized with your Authentication Token before using anything else. All available Methods for saving, retrieving ie. can all be found inside the Client class.

```
$client = new Client("MY_API_KEY");
```

### Choose the business to be used
As soon as a new Client is created all available Businesses for it will be fetched and saved. By default your primary Business will be selected for all requests.
Selecting a different Business for consequent requests can be accomplished with the setSelectedBusiness Method. To see all available businesses use the getBusinesses Method.

```
$businesses = $client->getBusinesses();
$client->setSelectedBusiness($client->getBusinesses()[2]);
```

### Creating Documents
For creating documents via the API you need to instantiate an Eversign\Document object and set your desired properties with the setter Methods provided.

```
$document = new Document();
$document->setTitle("My Title");
$document->setMessage("My Message");
// Set all other Properties defined in the API with Getters and Setters on the Document object.
```

#### Adding signers to the Document
Ever document can have 1 or multiple signers which are required to sign the document. You can add signers to an existing Document object by instantiating the Eversign\Signer object. You then append the Signer to the Document. AppendSigners Method will set a default Signer Id if it was not set previously. By default it takes the Index of the last element in the Signers array.

```
$signer = new Signer();
$signer->setName("John Doe");
$signer->setEmail("john.doe@eversign.com");
$signer->setRequired(true);
$document->appendSigner($signer);
```

#### Adding recipients to the Document
Adding recipients is exactly the same as adding Signers. You use the Recipient class instead of the Signer class and append it to the Document.

```
$recipient = new Recipient();
$recipient->setName("John Doe");
$recipient->setEmail("john.doe@eversign.com");
$document->appendRecipient($recipient);  
```

#### Adding files to the Document
Adding files to a document is also made very easy. Just create a new Eversign\File object and set the filePath, fileId (if it already exists on the API) or your own Base 64 string of the file you want to create. After creation you can append the file to a document. When the document is saved the file is automatically uploaded for you.

```
$file = new File();
$file->setName("My File");
$file->setFilePath(getcwd() . "/file.pdf");
$document->appendFile($file);
```

#### Adding FormFields
There are quite a lot different FormFields you can add to your document. Every FormField has different fields which are required for eversign to know how to handle the Field. Please check the official [FormFields Documentation](https://eversign.com/api/documentation/fields). When creating Fields that require Signers to be set, please use your provided Signer id and NOT the Signer object. While appending with appendFormField you can also specify the Index of the file as the second Parameter where the FormField should be added. If left empty the FormField will be added to the first File of the Document.

```
$signatureField = new SignatureField();
$signatureField->setX(30);
$signatureField->setY(150);
$signatureField->setPage(2);
$signatureField->setRequired(true);
$signatureField->setSigner("1");
$document->appendFormField($signatureField, 0);
```

#### Saving the Document
Saving a new Document couldn't be simpler. Just call the createDocument Method on the client with the document to be saved. After successful saving of the document the method returns the document with everything added by the API.

```
$client->createDocument($document);
```

#### Loading a document
Loading a document requires the documentHash to be known. If thats the case its a simple call to the client

```
$document = $client->getDocumentWithHash("MY_HASH");
```

#### Downloading the raw or final Document
There are 2 methods provided for you to download the Document, either in its signed or raw state. Just pass the Document object and a filePath where the document should be saved. Final Documents can also have an Audit Trail. Set the last parameter to true to have it attached automatically.

```
$client->downloadFinalDocumentToPath($document, "final.pdf", true);
$client->downloadRawDocumentToPath($document, "raw.pdf");
```

#### Get a list of Documents
Getting a list of Document Objects based on the different states (all, completed, draft, etc.) is also handled via the client. There are different distinct methods for you to choose from. The methods all return an array of Document classes

```
$client->getAllDocuments();
$client->getCompletedDocuments();
$client->getDraftedDocuments();
```

#### Delete or cancel a Document
As with all other methods to the API cancel and delete are also handled through the Client class.

```
$client->deleteDocument($document);
$client->cancelDocument($document);
```

### Disclaimer
This is the very first version of our PHP SDK and therefore not 100% complete.
We encourage you to help us advance the project in the future. If you would like to contribute please let us know.

### Todos

 - Test the whole codebase
 - Add more field validation
