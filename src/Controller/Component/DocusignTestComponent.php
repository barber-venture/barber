<?php

namespace App\Controller\Component;

require ROOT . DS . 'vendor' . DS . 'docusign' . DS . 'config.php';

use Cake\Core\Configure;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Docusign\Config;
use DocuSign\eSign\Api\AuthenticationApi;
use DocuSign\eSign\Configuration;
use DocuSign\eSign\ApiClient;
use DocuSign\eSign\Api\AuthenticationApi\LoginOptions;
use DocuSign\eSign\Api\EnvelopesApi;
use DocuSign\eSign\Model\Document;
use DocuSign\eSign\Model\SignHere;
use DocuSign\eSign\Model\InitialHere;
use DocuSign\eSign\Model\DateSigned;
use DocuSign\eSign\Model\EnvelopeDefinition;
use DocuSign\eSign\Api\EnvelopesApi\CreateEnvelopeOptions;
use DocuSign\eSign\Model\Signer;
use DocuSign\eSign\Model\Recipients;
use DocuSign\eSign\Model\Tabs;
use DocuSign\eSign\Model\Envelope;
use DocuSign\eSign\Api\EnvelopesApi\GetEnvelopeOptions;


/**
 * Docusign component
 * 
 * Author: Kipl59
 * 
 */
class DocusignTestComponent extends Component {

    public function login() {
         if(Configure::read('Docusign.IsLive')==1){
         $username = Configure::read('Docusign.Username');
         $password = Configure::read('Docusign.Password');
         $integratorKey = Configure::read('Docusign.IntegratorKey');
         $host = Configure::read('Docusign.HostLive');   
        }else{
         $username = Configure::read('Docusign.DemoUsername');
         $password = Configure::read('Docusign.DemoPassword');
         $integratorKey = Configure::read('Docusign.DemoIntegratorKey');
         $host = Configure::read('Docusign.HostDemo');    
        } 
        $loginConfig = new Config($username, $password, $integratorKey, $host);
        $config = new Configuration();
        $config->setHost($loginConfig->getHost());
        $config->addDefaultHeader("X-DocuSign-Authentication", "{\"Username\":\"" . $loginConfig->getUsername() . "\",\"Password\":\"" . $loginConfig->getPassword() . "\",\"IntegratorKey\":\"" . $loginConfig->getIntegratorKey() . "\"}");

        $loginConfig->setApiClient(new ApiClient($config));


        $authenticationApi = new AuthenticationApi($loginConfig->getApiClient());

        $options = new LoginOptions();

        $loginInformation = $authenticationApi->login($options);
        if (isset($loginInformation) && count($loginInformation) > 0) {
            $loginAccount = $loginInformation->getLoginAccounts()[0];
            if (isset($loginInformation)) {
                $accountId = $loginAccount->getAccountId();
                if (!empty($accountId)) {
                    $loginConfig->setAccountId($accountId);
                }
            }
        }



        return $loginConfig;
    }

    /*
     * 
     * Send Document for Signature
     * 
     */

    function signatureRequestOnDocument($loginConfig, $status = "sent", $embeddedSigning = false, $filepath, $signature, $contract_key) {

        $allDocument = array();
        foreach ($filepath as $key => $val) {
            $documentFileName = $val;
            $documentName = pathinfo($documentFileName, PATHINFO_BASENAME);
            $document = new Document();
            $document->setDocumentBase64(base64_encode(file_get_contents($documentFileName)));
            $document->setName($documentName);
            $document->setDocumentId($key + 1);
            $allDocument[] = $document;
        }

        $envelop_summary = null;
        if (!empty($loginConfig->getAccountId())) {

            $envelopeApi = new EnvelopesApi($loginConfig->getApiClient());

            // Add a document to the envelope


            /* Using Array */
            $i = 1;
            $recipients = new Recipients();
            $allSigners = array();
            foreach ($signature as $sign) {

                if (!empty($sign['user'])) {
                    $signerVar = "Signer" . $i;
                    $signerVar = new Signer();
                    $signerVar->setEmail($sign['user']['email']);
                    $signerVar->setName($sign['user']['name']);
                    $signerVar->setRecipientId($sign['user']['receiptId']);
                    //$signerVar->setRoutingOrder($sign['user']['order']);
                   
                }


                $tabsVar = "tabs" . $i;
                $tabsVar = new Tabs();


                if (!empty($sign['sign'])) {
                    $j = 1;
                    $allSigns = array();
                    foreach ($sign['sign'] as $signValue) {
                        $signHereVar = "signHere" . $i . $j;
                        $signHereVar = new SignHere();
                        $signHereVar->setXPosition($signValue["xpos"]);
                        $signHereVar->setYPosition($signValue["ypos"]);
                        $signHereVar->setDocumentId($signValue["documentId"]);
                        $signHereVar->setPageNumber($signValue["pageNumber"]);
                        $signHereVar->setRecipientId($signValue["receiptId"]);
                        $allSigns[] = $signHereVar;
                        $j++;
                        
                    }

                    $tabsVar->setSignHereTabs($allSigns);
                   
                }


                if (!empty($sign['dateSign'])) {
                    $k = 1;
                    $allDate = array();
                    foreach ($sign['dateSign'] as $dateSignValue) {
                        $dateSignedVar = "dateSigned" . $i . $k;
                        $dateSignedVar = new DateSigned();
                        $dateSignedVar->setXPosition($dateSignValue["xpos"]);
                        $dateSignedVar->setYPosition($dateSignValue["ypos"]);
                        $dateSignedVar->setDocumentId($dateSignValue["documentId"]);
                        $dateSignedVar->setPageNumber($dateSignValue["pageNumber"]);
                        $dateSignedVar->setRecipientId($dateSignValue["receiptId"]);
                        $allDate[] = $dateSignedVar;
                        $k++;
                    }

                    $tabsVar->setDateSignedTabs($allDate);
                }



                if (!empty($sign['initialSign'])) {
                    $l = 1;
                    $allInitial = array();
                    foreach ($sign['initialSign'] as $initialSignValue) {
                        $initialHereVar = "initialHere" . $i . $l;
                        $initialHereVar = new InitialHere();
                        $initialHereVar->setXPosition($initialSignValue["xpos"]);
                        $initialHereVar->setYPosition($initialSignValue["ypos"]);
                        $initialHereVar->setDocumentId($initialSignValue["documentId"]);
                        $initialHereVar->setPageNumber($initialSignValue["pageNumber"]);
                        $initialHereVar->setRecipientId($initialSignValue["receiptId"]);
                        $allInitial[] = $initialHereVar;
                        $l++;
                    }

                    $tabsVar->setInitialHereTabs($allInitial);
                }


                $signerVar->setTabs($tabsVar);
                $allSigners[] = $signerVar;
                $i++;
            }
            $recipients->setSigners($allSigners);           
            /* END */

            $envelope_events = [
                (new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("sent"),
                (new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("delivered"),
                (new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("completed"),
                (new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("declined"),
                (new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("voided"),
                (new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("sent"),
                (new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("sent")
            ];
            $recipient_events = [
                (new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("Sent"),
                (new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("Delivered"),
                (new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("Completed"),
                (new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("Declined"),
                (new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("AuthenticationFailed"),
                (new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("AutoResponded")
            ];
            $webhook_url = SITE_FULL_URL . 'projects/docu-sign-track-and-download/' . $contract_key;
            $event_notification = new \DocuSign\eSign\Model\EventNotification();
            $event_notification->setUrl($webhook_url);
            $event_notification->setLoggingEnabled("true");
            $event_notification->setRequireAcknowledgment("true");
            $event_notification->setUseSoapInterface("false");
            $event_notification->setIncludeCertificateWithSoap("false");
            $event_notification->setSignMessageWithX509Cert("false");
            $event_notification->setIncludeDocuments("true");
            $event_notification->setIncludeEnvelopeVoidReason("true");
            $event_notification->setIncludeTimeZone("true");
            $event_notification->setIncludeSenderAccountAsCustomField("true");
            $event_notification->setIncludeDocumentFields("true");
            $event_notification->setIncludeCertificateOfCompletion("true");
            $event_notification->setEnvelopeEvents($envelope_events);
            $event_notification->setRecipientEvents($recipient_events);

            $envelop_definition = new EnvelopeDefinition();
            $envelop_definition->setEmailSubject($signature[0]['email_subject']);
            $envelop_definition->setEmailBlurb($signature[0]['email_content']);

            // set envelope status to "sent" to immediately send the signature request
            $envelop_definition->setStatus($status);
            $envelop_definition->setRecipients($recipients);
            $envelop_definition->setDocuments($allDocument);
            $envelop_definition->setEventNotification($event_notification);
            $options = new CreateEnvelopeOptions();
            $options->setCdseMode(null);
            $options->setMergeRolesOnDraft(null);

            $envelop_summary = $envelopeApi->createEnvelope($loginConfig->getAccountId(), $envelop_definition, $options);
            if (!empty($envelop_summary)) {
                if ($status == "created") {
                    $loginConfig->setCreatedEnvelopeId($envelop_summary->getEnvelopeId());
                } else {
                    $loginConfig->setEnvelopeId($envelop_summary->getEnvelopeId());
                }
            }
        }

        return $loginConfig;
    }

     function signatureRequestOnDocumentTest($loginConfig, $status = "sent", $embeddedSigning = false, $filepath, $signature, $contract_key) {

        $allDocument = array();
        foreach ($filepath as $key => $val) {
            $documentFileName = $val;
            $documentName = pathinfo($documentFileName, PATHINFO_BASENAME);
            $document = new Document();
            $document->setDocumentBase64(base64_encode(file_get_contents($documentFileName)));
            $document->setName($documentName);
            $document->setDocumentId($key + 1);
            $allDocument[] = $document;
        }

        $envelop_summary = null;
        if (!empty($loginConfig->getAccountId())) {

            $envelopeApi = new EnvelopesApi($loginConfig->getApiClient());

            // Add a document to the envelope


            /* Using Array */
            $i = 1;
            $recipients = new Recipients();
            $allSigners = array();
            foreach ($signature as $sign) {

                if (!empty($sign['user'])) {
                    $signerVar = "Signer" . $i;
                    $signerVar = new Signer();
                    $signerVar->setEmail($sign['user']['email']);
                    $signerVar->setName($sign['user']['name']);
                    $signerVar->setRecipientId($sign['user']['receiptId']);
                    $signerVar->setRoutingOrder($sign['user']['order']);
                   
                   
                }


                $tabsVar = "tabs" . $i;
                $tabsVar = new Tabs();


                if (!empty($sign['sign'])) {
                    $j = 1;
                    $allSigns = array();
                    foreach ($sign['sign'] as $signValue) {
                        $signHereVar = "signHere" . $i . $j;
                        $signHereVar = new SignHere();
                        $signHereVar->setXPosition($signValue["xpos"]);
                        $signHereVar->setYPosition($signValue["ypos"]);
                        $signHereVar->setDocumentId($signValue["documentId"]);
                        $signHereVar->setPageNumber($signValue["pageNumber"]);
                        $signHereVar->setRecipientId($signValue["receiptId"]);
                        $allSigns[] = $signHereVar;
                        $j++;
                        
                    }

                    $tabsVar->setSignHereTabs($allSigns);
                   
                }


                if (!empty($sign['dateSign'])) {
                    $k = 1;
                    $allDate = array();
                    foreach ($sign['dateSign'] as $dateSignValue) {
                        $dateSignedVar = "dateSigned" . $i . $k;
                        $dateSignedVar = new DateSigned();
                        $dateSignedVar->setXPosition($dateSignValue["xpos"]);
                        $dateSignedVar->setYPosition($dateSignValue["ypos"]);
                        $dateSignedVar->setDocumentId($dateSignValue["documentId"]);
                        $dateSignedVar->setPageNumber($dateSignValue["pageNumber"]);
                        $dateSignedVar->setRecipientId($dateSignValue["receiptId"]);
                        $allDate[] = $dateSignedVar;
                        $k++;
                    }

                    $tabsVar->setDateSignedTabs($allDate);
                }



                if (!empty($sign['initialSign'])) {
                    $l = 1;
                    $allInitial = array();
                    foreach ($sign['initialSign'] as $initialSignValue) {
                        $initialHereVar = "initialHere" . $i . $l;
                        $initialHereVar = new InitialHere();
                        $initialHereVar->setXPosition($initialSignValue["xpos"]);
                        $initialHereVar->setYPosition($initialSignValue["ypos"]);
                        $initialHereVar->setDocumentId($initialSignValue["documentId"]);
                        $initialHereVar->setPageNumber($initialSignValue["pageNumber"]);
                        $initialHereVar->setRecipientId($initialSignValue["receiptId"]);
                        $allInitial[] = $initialHereVar;
                        $l++;
                    }

                    $tabsVar->setInitialHereTabs($allInitial);
                }


                $signerVar->setTabs($tabsVar);
                $allSigners[] = $signerVar;
                $i++;
            }
            $recipients->setSigners($allSigners);           
            /* END */

            $envelope_events = [
                (new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("sent"),
                (new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("delivered"),
                (new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("completed"),
                (new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("declined"),
                (new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("voided"),
                (new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("sent"),
                (new \DocuSign\eSign\Model\EnvelopeEvent())->setEnvelopeEventStatusCode("sent")
            ];
            $recipient_events = [
                (new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("Sent"),
                (new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("Delivered"),
                (new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("Completed"),
                (new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("Declined"),
                (new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("AuthenticationFailed"),
                (new \DocuSign\eSign\Model\RecipientEvent())->setRecipientEventStatusCode("AutoResponded")
            ];
            $webhook_url = SITE_FULL_URL . 'docu/web.php';
            $event_notification = new \DocuSign\eSign\Model\EventNotification();
            $event_notification->setUrl($webhook_url);
            $event_notification->setLoggingEnabled("true");
            $event_notification->setRequireAcknowledgment("true");
            $event_notification->setUseSoapInterface("false");
            $event_notification->setIncludeCertificateWithSoap("false");
            $event_notification->setSignMessageWithX509Cert("false");
            $event_notification->setIncludeDocuments("true");
            $event_notification->setIncludeEnvelopeVoidReason("true");
            $event_notification->setIncludeTimeZone("true");
            $event_notification->setIncludeSenderAccountAsCustomField("true");
            $event_notification->setIncludeDocumentFields("true");
            $event_notification->setIncludeCertificateOfCompletion("true");
            $event_notification->setEnvelopeEvents($envelope_events);
            $event_notification->setRecipientEvents($recipient_events);

            $envelop_definition = new EnvelopeDefinition();
            $envelop_definition->setEmailSubject($signature[0]['email_subject']);
            $envelop_definition->setEmailBlurb($signature[0]['email_content']);

            // set envelope status to "sent" to immediately send the signature request
            $envelop_definition->setStatus($status);
            $envelop_definition->setRecipients($recipients);
            $envelop_definition->setDocuments($allDocument);
            $envelop_definition->setEventNotification($event_notification);
            $options = new CreateEnvelopeOptions();
            $options->setCdseMode(null);
            $options->setMergeRolesOnDraft(null);

            $envelop_summary = $envelopeApi->createEnvelope($loginConfig->getAccountId(), $envelop_definition, $options);
            if (!empty($envelop_summary)) {
                if ($status == "created") {
                    $loginConfig->setCreatedEnvelopeId($envelop_summary->getEnvelopeId());
                } else {
                    $loginConfig->setEnvelopeId($envelop_summary->getEnvelopeId());
                }
            }
        }

        return $loginConfig;
    }

    function getEnvelopeInfo($loginConfig, $envelope_id) {
        $envelopeApi = new EnvelopesApi($loginConfig->getApiClient());
        $options = new GetEnvelopeOptions();
        $options->setInclude(null);
        $envelop_summary = $envelopeApi->getEnvelope($loginConfig->getAccountId(), $envelope_id);
        return $envelop_summary;
    }

    function sendDraftEnvelope($loginConfig, $envelope_id) {
        $envelopeApi = new EnvelopesApi($loginConfig->getApiClient());
        $options = new GetEnvelopeOptions();
        $options->setInclude(null);
        $response = $envelopeApi->update($loginConfig->getAccountId(), $envelope_id);
        return $response;
    }

    function downloadEnvelope($loginConfig) {

        $envelopeApi = new EnvelopesApi($loginConfig->getApiClient());

        $docsList = $envelopeApi->listDocuments($loginConfig->getAccountId(), $loginConfig->getEnvelopeId());
        //$this->assertNotEmpty($docsList);
        //$this->assertNotEmpty($docsList->getEnvelopeId());

        $docCount = count($docsList->getEnvelopeDocuments());
        if (intval($docCount) > 0) {
            foreach ($docsList->getEnvelopeDocuments() as $document) {
                echo "------------------<br />" . $loginConfig->getAccountId();
                echo "<br />" . $loginConfig->getEnvelopeId();
                echo "<br />" . $document->getDocumentId();
                //$this->assertNotEmpty($document->getDocumentId());
                //$file = $envelopeApi->getDocument($loginConfig->getAccountId(), $loginConfig->getEnvelopeId(), $document->getDocumentId());
                //$this->assertNotEmpty($file);
            }
        }

        return $docsList;
    }

    public function assertNotEmpty($file) {


        //$contents = $file->fread();


        $name = "docusign.pdf";
        $uploads_dir = dirname(__FILE__) . '\Docs\\';

        $tmp_name = $file->getRealPath();
        //$tmp_name1 = new splFileInfo($tmp_name);
        //echo "<pre>";
        //print_r($file);
        //die;
    }

}
