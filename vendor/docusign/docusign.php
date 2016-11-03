<?php
namespace docusign\DocuSign;
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/config.php';
use docusign\Config\Config;
use DocuSign\eSign\Api\AuthenticationApi;
use DocuSign\eSign\Configuration;
use DocuSign\eSign\ApiClient;
use DocuSign\eSign\Api\AuthenticationApi\LoginOptions;
use DocuSign\eSign\Api\EnvelopesApi;
use DocuSign\eSign\Model\Document;
use DocuSign\eSign\Model\SignHere;
use DocuSign\eSign\Model\EnvelopeDefinition;
use DocuSign\eSign\Api\EnvelopesApi\CreateEnvelopeOptions;
use DocuSign\eSign\Model\Signer;
use DocuSign\eSign\Model\Recipients;
use DocuSign\eSign\Model\Tabs;
/**
 * User: Kipl
 * Date: 13/4/16
 * Time: 4:58 PM
 */

class DocuSign
{
        public function __construct() {
        
        }
	/*
	 * Test 0 - login
	 */
	public function login()
	{
	 	$username = 'e85c2c43-5d86-4084-a385-1b8ad1582d4a';
	 	$password = 'PaceDocusign';
	 	$integratorKey = 'PACE-54ae613d-e9b5-4a26-9845-ec4516b21345';
	 	$host = 'https://demo.docusign.net/restapi';

	 	$testConfig = new Config($username, $password, $integratorKey, $host);

	 	$config = new Configuration();
	 	$config->setHost($testConfig->getHost());
	 	$config->addDefaultHeader("X-DocuSign-Authentication", "{\"Username\":\"" . $testConfig->getUsername() . "\",\"Password\":\"" . $testConfig->getPassword() . "\",\"IntegratorKey\":\"" . $testConfig->getIntegratorKey() . "\"}");

	 	$testConfig->setApiClient(new ApiClient($config));


	 	$authenticationApi = new AuthenticationApi($testConfig->getApiClient());

		$options = new LoginOptions();

	 	$loginInformation = $authenticationApi->login($options);
	 	if(isset($loginInformation) && count($loginInformation) > 0)
	 	{
	 		$loginAccount = $loginInformation->getLoginAccounts()[0];
	 		if(isset($loginInformation))
	 		{
	 			$accountId = $loginAccount->getAccountId();
	 			if(!empty($accountId))
	 			{
	 				$testConfig->setAccountId($accountId);
	 			}
	 		}
	 	}
		
		

			return $testConfig;
		}

	function signatureRequestOnDocument($testConfig, $status = "sent", $embeddedSigning = false,$filepath)
	{
		//$documentFileName = "\Docs\SignTest1.pdf";
		echo $documentFileName = $filepath;
		$documentName = "SignTest1.docx";

		$envelop_summary = null;


		if(!empty($testConfig->getAccountId()))
		{
			echo "hii";
			echo "filepath".$filepath;
			$envelopeApi = new EnvelopesApi($testConfig->getApiClient());

			// Add a document to the envelope
			$document = new Document();
			$document->setDocumentBase64(base64_encode(file_get_contents( $documentFileName)));
			$document->setName($documentName);
			$document->setDocumentId("1");
			//pr($document);
			// Create a |SignHere| tab somewhere on the document for the recipient to sign
			$signHere = new SignHere();
			$signHere->setXPosition("250");
			$signHere->setYPosition("130");
			$signHere->setDocumentId("1");
			$signHere->setPageNumber("8");
			$signHere->setRecipientId("1");
			//pr($signHere);
			
			
			$signHere1 = new SignHere();
			$signHere1->setXPosition("250");
			$signHere1->setYPosition("270");
			$signHere1->setDocumentId("1");
			$signHere1->setPageNumber("8");
			$signHere1->setRecipientId("2");
			
			
			$signHere2 = new SignHere();
			$signHere2->setXPosition("100");
			$signHere2->setYPosition("480");
			$signHere2->setDocumentId("1");
			$signHere2->setPageNumber("8");
			$signHere2->setRecipientId("2");
			
			
			$tabs = new Tabs();
			$tabs->setSignHereTabs(array($signHere));
			
			$tabs1 = new Tabs();
			$tabs1->setSignHereTabs(array($signHere1,$signHere2));
			
			

			$signer = new Signer();
			$signer->setEmail("kipldeveloper@gmail.com");
			$signer->setName("kipl Developer");
			$signer->setRecipientId("1");
			
			$signer1 = new Signer();
			$signer1->setEmail("kiplphp42@konstantinfosolutions.com");
			$signer1->setName("Kipl Test");
			$signer1->setRecipientId("2");
			
			if($embeddedSigning) {
				$signer->setClientUserId($testConfig->getClientUserId());
			}
			
			$signer->setTabs($tabs);
			$signer1->setTabs($tabs1);

			// Add a recipient to sign the document
			$recipients = new Recipients();
			$recipients->setSigners(array($signer,$signer1));

			$envelop_definition = new EnvelopeDefinition();
			$envelop_definition->setEmailSubject("[DocuSign PHP SDK] - Please sign this doc");

			// set envelope status to "sent" to immediately send the signature request
			$envelop_definition->setStatus($status);
			$envelop_definition->setRecipients($recipients);
			$envelop_definition->setDocuments(array($document));

			$options = new CreateEnvelopeOptions();
			$options->setCdseMode(null);
			$options->setMergeRolesOnDraft(null);

			$envelop_summary = $envelopeApi->createEnvelope($testConfig->getAccountId(), $envelop_definition, $options);
			if(!empty($envelop_summary))
			{
				if($status == "created")
				{
					$testConfig->setCreatedEnvelopeId($envelop_summary->getEnvelopeId());
				}
				else
				{
					$testConfig->setEnvelopeId($envelop_summary->getEnvelopeId());
				}
			}
		}

//		$this->assertNotEmpty($envelop_summary);
		return $testConfig;
	}

	
	/**
     * @depends testLogin
     */
    public function testSignatureRequestOnDocument($testConfig, $embeddedSigning = false)
    {
		return $this->signatureRequestOnDocument($testConfig, "sent", $embeddedSigning);
    }

	/**
	 * @depends testLogin
	 */
	public function testSignatureRequestOnDocumentCreated($testConfig, $embeddedSigning = false)
	{
		return $this->signatureRequestOnDocument($testConfig, "created", $embeddedSigning);
	}

	/**
     * @depends testLogin
     */
	public function testRequestSignatureFromTemplate($testConfig)
    {
		$envelop_summary = null;

		if(!empty($testConfig->getAccountId))
		{
			$envelopeApi = new DocuSign\eSign\Api\EnvelopesApi($testConfig->getApiClient());

			// assign recipient to template role by setting name, email, and role name.  Note that the
		    // template role name must match the placeholder role name saved in your account template.
		    $templateRole = new  DocuSign\eSign\Model\TemplateRole();
		    $templateRole->setEmail($testConfig->getRecipientEmail());
		    $templateRole->setName($testConfig->getRecipientName());
			
			
		    $templateRole->setRoleName($testConfig->getTemplateRoleName());

			$envelop_definition = new DocuSign\eSign\Model\EnvelopeDefinition();
			$envelop_definition->setEmailSubject("[DocuSign PHP SDK] - Please sign this template doc");

		    // add the role to the envelope and assign valid templateId from your account
		    $envelop_definition->setTemplateRoles(array($templateRole));
		    $envelop_definition->setTemplateId($testConfig->getTemplateId());

		    // set envelope status to "sent" to immediately send the signature request
		    $envelop_definition->setStatus("sent");

			$options = new \DocuSign\eSign\Api\EnvelopesApi\CreateEnvelopeOptions();
			$options->setCdseMode(null);
			$options->setMergeRolesOnDraft(null);

			$envelop_summary = $envelopeApi->createEnvelope($testConfig->getAccountId(), $envelop_definition, $options);
			if(!empty($envelop_summary))
			{
				$testConfig->setEnvelopeId($envelop_summary->getEnvelopeId());
			}
		}

		//$this->assertNotEmpty($envelop_summary);

		return $testConfig;
	}

	/**
     * @depends testSignatureRequestOnDocument
     */
	public function testGetEnvelopeInformation($testConfig)
    {
		$envelopeApi = new DocuSign\eSign\Api\EnvelopesApi($testConfig->getApiClient());

		$options = new \DocuSign\eSign\Api\EnvelopesApi\GetEnvelopeOptions();
		$options->setInclude(null);

	    $envelope = $envelopeApi->getEnvelope($testConfig->getAccountId(), '2aba2883-daea-450a-9da8-f1e3ce9141c9', $options);
		//$this->assertNotEmpty($envelope);

	    return $testConfig;
	}

	/**
     * @depends testSignatureRequestOnDocument
     */
	public function testListRecipients($testConfig)
    {
    	$envelopeApi = new DocuSign\eSign\Api\EnvelopesApi($testConfig->getApiClient());
        $recipients = $envelopeApi->listRecipients($testConfig->getAccountId(), $testConfig->getEnvelopeId());

        $this->assertNotEmpty($recipients);
		$this->assertNotEmpty($recipients->getRecipientCount());

    	return $testConfig;
    }

	/**
	 * @depends testLogin
	 */
	public function testListStatusChanges($testConfig)
	{
		date_default_timezone_set('America/Los_Angeles');

		$envelopeApi = new DocuSign\eSign\Api\EnvelopesApi($testConfig->getApiClient());

		$options = new \DocuSign\eSign\Api\EnvelopesApi\ListStatusChangesOptions();
		$options->setInclude(null);
		$options->setPowerformids(null);
		$options->setAcStatus(null);
		$options->setBlock(null);
		$options->setSearchText(null);
		$options->setStartPosition(null);
		$options->setStatus(null);
		$options->setToDate(null);
		$options->setTransactionIds(null);
		$options->setUserFilter(null);
		$options->setFolderTypes(null);
		$options->setUserId(null);
		$options->setCount(10);
		$options->setEmail(null);
		$options->setEnvelopeIds(null);
		$options->setExclude(null);
		$options->setFolderIds(null);
		$options->setFromDate(date("Y-m-d", strtotime("-30 days")));
		$options->setCustomField(null);
		$options->setFromToStatus(null);
		$options->setIntersectingFolderIds(null);
		$options->setOrder(null);
		$options->setOrderBy(null);
		$options->setUserName(null);

		$envelopesInformation = $envelopeApi->listStatusChanges($testConfig->getAccountId(), $options);

		$this->assertNotEmpty($envelopesInformation);

		return $testConfig;
	}

	/**
     * @depends testSignatureRequestOnDocument
     */
    public function testListDocumentsAndDownload($testConfig)
	{
		$envelopeApi = new DocuSign\eSign\Api\EnvelopesApi($testConfig->getApiClient());

		$docsList = $envelopeApi->listDocuments($testConfig->getAccountId(), $testConfig->getEnvelopeId());
		//$this->assertNotEmpty($docsList);
		//$this->assertNotEmpty($docsList->getEnvelopeId());

		$docCount = count($docsList->getEnvelopeDocuments());
		if (intval($docCount) > 0)
		{
			foreach($docsList->getEnvelopeDocuments() as $document)
			{
				//$this->assertNotEmpty($document->getDocumentId());
				$file = $envelopeApi->getDocument($testConfig->getAccountId(), $testConfig->getEnvelopeId(), $document->getDocumentId());
				$this->assertNotEmpty($file);
			}
		}

    	return $testConfig;
    }

    /**
     * @depends testSignatureRequestOnDocumentCreated
     */
	public function testCreateEmbeddedSendingView($testConfig)
    {
    	$testConfig = $this->testSignatureRequestOnDocument($testConfig, "created", true);

    	$envelopeApi = new DocuSign\eSign\Api\EnvelopesApi($testConfig->getApiClient());

		$return_url_request = new \DocuSign\eSign\Model\ReturnUrlRequest();
		$return_url_request->setReturnUrl($testConfig->getReturnUrl());
		
		$senderView = $envelopeApi->createSenderView($testConfig->getAccountId(), $testConfig->getCreatedEnvelopeId(), $return_url_request);

		$this->assertNotEmpty($senderView);
		$this->assertNotEmpty($senderView->getUrl());

    	return $testConfig;
    }

    /**
     * @depends testSignatureRequestOnDocument
     */
	public function testCreateEmbeddedSigningView($testConfig)
    {
		$envelopeApi = new DocuSign\eSign\Api\EnvelopesApi($testConfig->getApiClient());

		$recipient_view_request = new \DocuSign\eSign\Model\RecipientViewRequest();
		$recipient_view_request->setReturnUrl($testConfig->getReturnUrl());
		$recipient_view_request->setClientUserId($testConfig->getClientUserId());
		$recipient_view_request->setAuthenticationMethod("email");
		$recipient_view_request->setUserName($testConfig->getRecipientName());
		$recipient_view_request->setEmail($testConfig->getRecipientEmail());

		$signingView = $envelopeApi->createRecipientView($testConfig->getAccountId(), $testConfig->getEnvelopeId(), $recipient_view_request);

		$this->assertNotEmpty($signingView);
		$this->assertNotEmpty($signingView->getUrl());

    	return $testConfig;
    }

    /**
     * @depends testSignatureRequestOnDocument
     */
    public function testCreateEmbeddedConsoleView($testConfig)
    {
		$envelopeApi = new DocuSign\eSign\Api\EnvelopesApi($testConfig->getApiClient());

		$console_view_request = new \DocuSign\eSign\Model\ConsoleViewRequest();
		$console_view_request->setEnvelopeId($testConfig->getEnvelopeId());
		$console_view_request->setReturnUrl($testConfig->getReturnUrl());

		$view_url = $envelopeApi->createConsoleView($testConfig->getAccountId(), $console_view_request);

		$this->assertNotEmpty($view_url);
		$this->assertNotEmpty($view_url->getUrl());

    	return $testConfig;
    }
	
	public function testListCustomFields($testConfig){
		$accountApi = new DocuSign\eSign\Api\AccountsApi($testConfig->getApiClient());
		echo $testConfig->getAccountId();
		$view_fields = $accountApi->listCustomFields($testConfig->getAccountId(),"fb8b6e51-9379-4e90-893b-a52593705c28");
		

    	return $view_fields;
	}
	
	public function testListCustomFields1($testConfig){
		//echo "<pre>";
		//print_r($testConfig);
		//$accountApi = new DocuSign\eSign\Api\DocuSign\eSign\Api\EnvelopesApi();
		$accountApi = new DocuSign\eSign\Api\EnvelopesApi($testConfig->getApiClient());
		echo $testConfig->getAccountId();
		//$view_fields = $accountApi->listCustomFields($testConfig->getAccountId(),"fb8b6e51-9379-4e90-893b-a52593705c28");
		//af644815-5c34-4490-b70a-949ca76aabb7
		//56835075
		
		/*$view_fields = $accountApi->listDocumentsWithHttpInfo($testConfig->getAccountId(),"fb8b6e51-9379-4e90-893b-a52593705c28");*/
		/*$view_fields = $accountApi->get($testConfig->getAccountId(),'01e54577-6dc5-4596-8ddc-bcc2dce4db49');*/
		$view_fields = $accountApi->listRecipients($testConfig->getAccountId(),'34616222-6e4a-455d-8d09-8fb2ecbf867e');
		
    	return $view_fields;
	}

	public function assertNotEmpty($file){
		
		
		//$contents = $file->fread();
		
		
		$name = "docusign.pdf";
		$uploads_dir = dirname(__FILE__). '\Docs\\';
		
		$tmp_name = $file->getRealPath();
		//$tmp_name1 = new splFileInfo($tmp_name);
		
		
		
		
		
		
		//echo "<pre>";
		//print_r($file);
		//die;
		
		
		
	}
}

?>