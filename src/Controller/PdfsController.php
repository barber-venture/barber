<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Http\Client;
use Cake\Core\Configure;
use Cake\Utility\Xml;
use Cake\I18n\Number;
use Cake\I18n\Time;
use Financial\Financial;
use Cake\Routing\Router;
use Cake\Filesystem\File;
use Cake\Utility\Hash;
use Cake\I18n\Date;

/**
 * Projects Controller
 *
 * @property \App\Model\Table\ProjectsTable $Projects
 */
class PdfsController extends AppController {

    public function initialize() {
        parent::initialize();
        //$this->loadModel('Terms');
        $this->loadComponent('RequestHandler');
        $this->loadModel('Projects');
        $this->loadModel('ProjectCalculatePayments');
        $this->loadModel('Equipments');
        $this->loadComponent('Common');
        $this->loadModel('SystemMails');
        $this->loadModel('ProjectTypes');
        $this->loadModel('EquipmentInformations');
        $this->loadModel('ContractDetails');
        $this->loadModel('CustomerDetails');
        $this->loadModel('Countries');
        $this->loadModel('States');
        $this->loadModel('Cities');
        $this->loadModel('Users');
        $this->loadModel('ProjectDocuments');
    }

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
       $this->Auth->allow('noti');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index11() {
        $this->loadComponent('Docusign');
	 $config = $this->Docusign->login();
       // print_r($config);die; 
         
	$signature[0]['email_subject']="PACE Funding Contract";
	$signature[0]['email_content']="Custom Message";
	
	$signature[0]['user']['email']="kiplphp42@konstantinfosolutions.com";
	$signature[0]['user']['name']="Test 53";
	$signature[0]['user']['receiptId']="1";
	
	$signature[0]['sign'][0]['pageNumber']="8";
	$signature[0]['sign'][0]['receiptId']="1";
	$signature[0]['sign'][0]['documentId']="1";
	$signature[0]['sign'][0]['xpos']="250";
	$signature[0]['sign'][0]['ypos']="140";
	
	$signature[0]['dateSign'][0]['xpos']="250";
	$signature[0]['dateSign'][0]['ypos']="218";
	$signature[0]['dateSign'][0]['pageNumber']="8";
	$signature[0]['dateSign'][0]['receiptId']="1";
	$signature[0]['dateSign'][0]['documentId']="1";
	
	$signature[0]['dateSign'][1]['xpos']="220";
	$signature[0]['dateSign'][1]['ypos']="621";
	$signature[0]['dateSign'][1]['pageNumber']="13";
	$signature[0]['dateSign'][1]['receiptId']="1";
	$signature[0]['dateSign'][1]['documentId']="1";
	
	
	$signature[0]['initialSign'][0]['xpos']="250";
	$signature[0]['initialSign'][0]['ypos']="700";
	$signature[0]['initialSign'][0]['pageNumber']="4";
	$signature[0]['initialSign'][0]['receiptId']="1";
	$signature[0]['initialSign'][0]['documentId']="1";
	
	$signature[0]['initialSign'][1]['xpos']="95";
	$signature[0]['initialSign'][1]['ypos']="600";
	$signature[0]['initialSign'][1]['pageNumber']="13";
	$signature[0]['initialSign'][1]['receiptId']="1";
	$signature[0]['initialSign'][1]['documentId']="1";
	 
	
	
	$signature[1]['user']['email']="kiplphp42@konstantinfosolutions.com";
	$signature[1]['user']['name']="test 54";
	$signature[1]['user']['receiptId']="2";
	
	$signature[1]['sign'][0]['pageNumber']="8";
	$signature[1]['sign'][0]['receiptId']="2";
	$signature[1]['sign'][0]['documentId']="1";
	$signature[1]['sign'][0]['xpos']="250";
	$signature[1]['sign'][0]['ypos']="300";
	
	$signature[1]['dateSign'][0]['xpos']="250";
	$signature[1]['dateSign'][0]['ypos']="380";
	$signature[1]['dateSign'][0]['pageNumber']="8";
	$signature[1]['dateSign'][0]['receiptId']="2";
	$signature[1]['dateSign'][0]['documentId']="1";
	
	$signature[1]['dateSign'][1]['xpos']="470";
	$signature[1]['dateSign'][1]['ypos']="621";
	$signature[1]['dateSign'][1]['pageNumber']="13";
	$signature[1]['dateSign'][1]['receiptId']="2";
	$signature[1]['dateSign'][1]['documentId']="1";
	
	
	$signature[1]['initialSign'][0]['xpos']="250";
	$signature[1]['initialSign'][0]['ypos']="740";
	$signature[1]['initialSign'][0]['pageNumber']="4";
	$signature[1]['initialSign'][0]['receiptId']="2";
	$signature[1]['initialSign'][0]['documentId']="1";
	
	$signature[1]['initialSign'][1]['xpos']="315";
	$signature[1]['initialSign'][1]['ypos']="600";
	$signature[1]['initialSign'][1]['pageNumber']="13";
	$signature[1]['initialSign'][1]['receiptId']="2";
	$signature[1]['initialSign'][1]['documentId']="1";
//pr($signature);

	
        $config = $this->Docusign->login();

       // $res = $this->Docusign->getEnvelopeInfo($config, '0899f5c0-0ae5-41ec-84ed-c7299bd744f1');
       // pr($config);
        //die;
        /* Set Variables */

        $this->set('owner1', "Test53");
        $this->set('owner2', "Test54");
        $this->set('todayDate', "April 20,2016");
        $this->set('country', "USA");
        $this->set('identificationCode', "123456");
        $this->set('JPASignerName', "Test50");
        $this->set('AddressProperty', "A-12,Rentza");
        $this->set('ParcelNumber', "4589");
        $this->set('LegalDesc', "Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum");
        $this->set('AddressMailing', "45, Rock Garden");
        $this->set('CityMailing', "Houston");
        $this->set('StateMailing', "Texas");
        $this->set('ZIPMailing', "85001");
        $this->set('ProductMfr1', "PMFR1");
        $this->set('ProductMfr2', "PMFR2");
        $this->set('ProductMfr3', "PMFR3");
        $this->set('ProductMfr4', "PMFR4");
        $this->set('ProductMfr5', "PMFR5");
        $this->set('ProductModel1', "PM1");
        $this->set('ProductModel2', "PM2");
        $this->set('ProductModel3', "PM3");
        $this->set('ProductModel4', "PM4");
        $this->set('ProductModel5', "PM5");
        $this->set('ProductSKU1', "PSKU1");
        $this->set('ProductSKU2', "PSKU2");
        $this->set('ProductSKU3', "PSKU3");
        $this->set('ProductSKU4', "PSKU4");
        $this->set('ProductSKU5', "PSKU5");
        $this->set('ProductQuantity1', "PQ1");
        $this->set('ProductQuantity2', "PQ2");
        $this->set('ProductQuantity3', "PQ3");
        $this->set('ProductQuantity4', "PQ4");
        $this->set('ProductQuantity5', "PQ5");
        $this->set('Assessment', "7856");
        $this->set('TotalFees', "5000");
        $this->set('CapInterest', "875");
        $this->set('TotalProjectCost', "10000");
        $this->set('Rate', "5");
        $this->set('MaturityDate', "May 12,2016");
        $this->set('APR', "456");
        $this->set('AnnualPayment', "500");
        $this->set('AnnualAdminFee', "200");
        $this->set('TotalAnnualPayment', "800");
        $this->set('EmailOwner1', "owner1@gmail.com");
        $this->set('Municipality', "Municipality");
        $this->set('todaysDate', date("M d, Y",strtotime("now")));


        $this->render(false);
        $CakePdf = new \CakePdf\Pdf\CakePdf();
        $CakePdf->template('contract', 'default');
        $CakePdf->viewVars($this->viewVars);
        // Get the PDF string returned
        $pdf = $CakePdf->output();
        // Or write it to file directly
        $id = rand(1, 99) . strtotime("now");
        $pdf = $CakePdf->write(WWW_ROOT . 'files' . DS . $id . '.pdf');	
        $sendRequest = $this->Docusign->signatureRequestOnDocument($config, "sent", false, array(SITE_FULL_URL. 'files/' . $id . '.pdf'),$signature);
		
       $sendRequest1 = $this->Docusign->downloadEnvelope($sendRequest);
        pr($sendRequest1);
        die;
    }
	
	public function applicationDisclosures() {

       
	   
	 $this->loadComponent('Docusign');
	
	$config = $this->Docusign->login();

	
	   
	   
	   
        /* Set Variables */

        $this->set('owner1', "Test thirty nine");
        $this->set('owner2', "Test forty");
       


        /*   */


        $this->render(false);
        $CakePdf = new \CakePdf\Pdf\CakePdf();
        $CakePdf->template('application_disclosures', 'default');
        $CakePdf->viewVars($this->viewVars);
        // Get the PDF string returned
        $pdf = $CakePdf->output();
        // Or write it to file directly
        $id = "ApplicationDisclosures".rand(1, 99) . strtotime("now");
        $pdf = $CakePdf->write(WWW_ROOT . 'files' . DS . $id . '.pdf');
        $lastPage = $CakePdf->getPageCount();
		
		
		
		$signature[0]['email_subject']="PACE Funding Contract";
	$signature[0]['email_content']="Custom Message";
	
	$signature[0]['user']['email']="kiplphp42@konstantinfosolutions.com";
	$signature[0]['user']['name']="Test 53";
	$signature[0]['user']['receiptId']="1";
	
	$signature[0]['sign'][0]['pageNumber']=$lastPage;
	$signature[0]['sign'][0]['receiptId']="1";
	$signature[0]['sign'][0]['documentId']="1";
	$signature[0]['sign'][0]['xpos']="100";
	$signature[0]['sign'][0]['ypos']="250";
	
	$signature[0]['dateSign'][0]['xpos']="345";
	$signature[0]['dateSign'][0]['ypos']="240";
	$signature[0]['dateSign'][0]['pageNumber']=$lastPage;
	$signature[0]['dateSign'][0]['receiptId']="1";
	$signature[0]['dateSign'][0]['documentId']="1";
	
	
	
	
	$signature[1]['user']['email']="kiplphp42@konstantinfosolutions.com";
	$signature[1]['user']['name']="test 54";
	$signature[1]['user']['receiptId']="2";
	
	$signature[1]['sign'][0]['pageNumber']=$lastPage;
	$signature[1]['sign'][0]['receiptId']="2";
	$signature[1]['sign'][0]['documentId']="1";
	$signature[1]['sign'][0]['xpos']="100";
	$signature[1]['sign'][0]['ypos']="345";
	
	$signature[1]['dateSign'][0]['xpos']="345";
	$signature[1]['dateSign'][0]['ypos']="340";
	$signature[1]['dateSign'][0]['pageNumber']=$lastPage;
	$signature[1]['dateSign'][0]['receiptId']="2";
	$signature[1]['dateSign'][0]['documentId']="1";
		
		
		
		$sendRequest = $this->Docusign->signatureRequestOnDocument($config, "sent", false, SITE_FULL_URL. 'files/' . $id . '.pdf',$signature);
		
		 $sendRequest1 = $this->Docusign->downloadEnvelope($sendRequest);
        pr($sendRequest1);
        die;

        
    }
	
	public function financingStatement() {
		
		
		
	$this->loadComponent('Docusign');
	
	$config = $this->Docusign->login();

	
		
		
		
	

       
        /* Set Variables */

        $this->set('owner1', "Test thirty nine");
        $this->set('owner2', "Test forty");
        $this->set('todaysDate', date("m d, Y",strtotime("now")));
        $this->set('PropertyValuation', "PropertyValuation-Value");
        $this->set('PropertyStreetAddress', "PropertyStreetAddress-Value");
        $this->set('PropertyCityAddress', "PropertyCityAddress-Value");
        $this->set('PropertyState', "PropertyState-Value");
        $this->set('PropertyZIP', "PropertyZIP-Value");
        $this->set('ProjectID', "ProjectID-Value");
        $this->set('ExpirationDate', date("m d, Y",strtotime("+120 Days")));
        $this->set('ProjectType1', "ProjectType1-Value");
        $this->set('ProjectCost1', "ProjectCost1-Value");
		$this->set('ProjectType2', "ProjectType2-Value");
        $this->set('ProjectCost2', "ProjectCost2-Value");
		$this->set('ProjectType3', "ProjectType3-Value");
        $this->set('ProjectCost3', "ProjectCost3-Value");
		$this->set('ProjectType4', "ProjectType4-Value");
        $this->set('ProjectCost4', "ProjectCost4-Value");
		$this->set('ProjectType5', "ProjectType5-Value");
        $this->set('ProjectCost5', "ProjectCost5-Value");
        $this->set('TotalProjectCost', "TotalProjectCost-Value");
        $this->set('Rate', "Rate-Value");
        $this->set('CapInterest', "CapInterest-Value");
        $this->set('APR', "APR-Value");
        $this->set('Assessment', "Assessment-Value");
        $this->set('AnnualPayment', "AnnualPayment-Value");
        $this->set('AnnualAdminFee', "AnnualAdminFee-Value");
        $this->set('AdminFeeTotal', "AdminFeeTotal-Value");
        $this->set('Origination', "Origination-Value");
        $this->set('TotalProjectCost', "TotalProjectCost-Value");
        $this->set('LienFee', "LienFee-Value");
        $this->set('UpfrontCosts', "Upfront Costs-Value");
        $this->set('LoanLossReserve', "LoanLossReserve-Value");
        $this->set('Assessment', "Assessment-Value");
        $this->set('ReserveAccount', "ReserveAccount-Value");
        $this->set('Term', "Term-Value");
        $this->set('ExpectedCompletionDate', date("m d, Y",strtotime("+120 Days")));
       


        /*   */


        $this->render(false);
        $CakePdf = new \CakePdf\Pdf\CakePdf();
        $CakePdf->template('financing_statement', 'default');
        $CakePdf->viewVars($this->viewVars);
        // Get the PDF string returned
        $pdf = $CakePdf->output();
        // Or write it to file directly
        $id = "financingStatement".rand(1, 99) . strtotime("now");
        $pdf = $CakePdf->write(WWW_ROOT . 'files' . DS . $id . '.pdf');
        
		$lastPage = $CakePdf->getPageCount();
		
		$signature[0]['email_subject']="PACE Funding Contract";
	$signature[0]['email_content']="Custom Message";
	
	$signature[0]['user']['email']="kiplphp42@konstantinfosolutions.com";
	$signature[0]['user']['name']="Test 53";
	$signature[0]['user']['receiptId']="1";
	
	$signature[0]['sign'][0]['pageNumber']=$lastPage;
	$signature[0]['sign'][0]['receiptId']="1";
	$signature[0]['sign'][0]['documentId']="1";
	$signature[0]['sign'][0]['xpos']="100";
	$signature[0]['sign'][0]['ypos']="265";
	
	$signature[0]['dateSign'][0]['xpos']="340";
	$signature[0]['dateSign'][0]['ypos']="250";
	$signature[0]['dateSign'][0]['pageNumber']=$lastPage;
	$signature[0]['dateSign'][0]['receiptId']="1";
	$signature[0]['dateSign'][0]['documentId']="1";
	
	
	
	
	$signature[0]['initialSign'][0]['xpos']="480";
	$signature[0]['initialSign'][0]['ypos']="170";
	$signature[0]['initialSign'][0]['pageNumber']=$lastPage-1;
	$signature[0]['initialSign'][0]['receiptId']="1";
	$signature[0]['initialSign'][0]['documentId']="1";
	
	$signature[0]['initialSign'][1]['xpos']="480";
	$signature[0]['initialSign'][1]['ypos']="250";
	$signature[0]['initialSign'][1]['pageNumber']=$lastPage-1;
	$signature[0]['initialSign'][1]['receiptId']="1";
	$signature[0]['initialSign'][1]['documentId']="1";
	
	$signature[0]['initialSign'][2]['xpos']="480";
	$signature[0]['initialSign'][2]['ypos']="370";
	$signature[0]['initialSign'][2]['pageNumber']=$lastPage-1;
	$signature[0]['initialSign'][2]['receiptId']="1";
	$signature[0]['initialSign'][2]['documentId']="1";
	
	$signature[0]['initialSign'][3]['xpos']="480";
	$signature[0]['initialSign'][3]['ypos']="480";
	$signature[0]['initialSign'][3]['pageNumber']=$lastPage-1;
	$signature[0]['initialSign'][3]['receiptId']="1";
	$signature[0]['initialSign'][3]['documentId']="1";
	
	
	
	
	
	
	$signature[1]['user']['email']="kiplphp42@konstantinfosolutions.com";
	$signature[1]['user']['name']="test 54";
	$signature[1]['user']['receiptId']="2";
	
	$signature[1]['sign'][0]['pageNumber']=$lastPage;
	$signature[1]['sign'][0]['receiptId']="2";
	$signature[1]['sign'][0]['documentId']="1";
	$signature[1]['sign'][0]['xpos']="100";
	$signature[1]['sign'][0]['ypos']="380";
	
	$signature[1]['dateSign'][0]['xpos']="340";
	$signature[1]['dateSign'][0]['ypos']="370";
	$signature[1]['dateSign'][0]['pageNumber']=$lastPage;
	$signature[1]['dateSign'][0]['receiptId']="2";
	$signature[1]['dateSign'][0]['documentId']="1";
	
	
	
	
	$signature[1]['initialSign'][0]['xpos']="480";
	$signature[1]['initialSign'][0]['ypos']="200";
	$signature[1]['initialSign'][0]['pageNumber']=$lastPage-1;
	$signature[1]['initialSign'][0]['receiptId']="2";
	$signature[1]['initialSign'][0]['documentId']="1";
	
	$signature[1]['initialSign'][1]['xpos']="480";
	$signature[1]['initialSign'][1]['ypos']="285";
	$signature[1]['initialSign'][1]['pageNumber']=$lastPage-1;
	$signature[1]['initialSign'][1]['receiptId']="2";
	$signature[1]['initialSign'][1]['documentId']="1";
	
	$signature[1]['initialSign'][2]['xpos']="480";
	$signature[1]['initialSign'][2]['ypos']="400";
	$signature[1]['initialSign'][2]['pageNumber']=$lastPage-1;
	$signature[1]['initialSign'][2]['receiptId']="2";
	$signature[1]['initialSign'][2]['documentId']="1";
	
	$signature[1]['initialSign'][3]['xpos']="480";
	$signature[1]['initialSign'][3]['ypos']="520";
	$signature[1]['initialSign'][3]['pageNumber']=$lastPage-1;
	$signature[1]['initialSign'][3]['receiptId']="2";
	$signature[1]['initialSign'][3]['documentId']="1";
		
		
		
	$sendRequest = $this->Docusign->signatureRequestOnDocument($config, "sent", false, SITE_FULL_URL. 'files/' . $id . '.pdf',$signature);
		
	$sendRequest1 = $this->Docusign->downloadEnvelope($sendRequest);
    pr($sendRequest1);
        die;

        
    }
	public function checkAndDownload() {
		$params=$this->request->params['pass'];
		$this->loadComponent('Docusign');
        $config = $this->Docusign->login();
		$login="https://demo.docusign.net/restapi/v2/accounts/".$params[0]."/envelopes/".$params[1]."/recipients?include_tabs=false";
		
		$ch = curl_init($login);                                                                 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
			'X-DocuSign-Authentication:<DocuSignCredentials><Username>'.$config->getUsername().'</Username><Password>'.$config->getPassword().'</Password><IntegratorKey>'.$config->getIntegratorKey().'</IntegratorKey></DocuSignCredentials>',
			'Content-Type: application/json',  
			'Accept: application/json'
			)                                                                       
		);
		
		$result1 = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($result1);
		$hasError=0;
		$count=count($result->signers);
		foreach($result->signers as $signer)
		{
			if($signer->status!="completed")
			{
				$hasError=1;
			}
		}

		if($hasError==0)
		{
				$login="https://demo.docusign.net/restapi/v2/accounts/".$params[0]."/envelopes/".$params[1]."/documents/1";
				
				$ch = curl_init($login);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
					'X-DocuSign-Authentication:<DocuSignCredentials><Username>'.$config->getUsername().'</Username><Password>'.$config->getPassword().'</Password><IntegratorKey>'.$config->getIntegratorKey().'</IntegratorKey></DocuSignCredentials>',
					'Content-Type: application/json',  
					'Accept: application/json'
					)                                                                       
				);
				$result2 = curl_exec($ch);
				$fp = fopen(WWW_ROOT . 'downloded_files' . DS . $params[1] . '.pdf', "w");
				fwrite($fp, $result2);
				fclose($fp);
				curl_close($ch);
				$this->loadComponent('Docusign');
				$config = $this->Docusign->login();
				
				$signature[0]['email_subject']="PACE Funding Contract";
				$signature[0]['email_content']="Custom Message";
				
				$signature[0]['user']['email']="kiplphp42@konstantinfosolutions.com";
				$signature[0]['user']['name']="KIPL Dinesh";
				$signature[0]['user']['receiptId']="1";
	
				$signature[0]['sign'][0]['pageNumber']="8";
				$signature[0]['sign'][0]['receiptId']="1";
				$signature[0]['sign'][0]['documentId']="1";
				$signature[0]['sign'][0]['xpos']="100";
				$signature[0]['sign'][0]['ypos']="520";
	
				$signature[0]['dateSign'][0]['xpos']="350";
				$signature[0]['dateSign'][0]['ypos']="550";
				$signature[0]['dateSign'][0]['pageNumber']="8";
				$signature[0]['dateSign'][0]['receiptId']="1";
				$signature[0]['dateSign'][0]['documentId']="1";
				
				
				$sendRequest = $this->Docusign->signatureRequestOnDocument($config, "sent", false, SITE_FULL_URL. 'downloded_files/' . $params[1] . '.pdf',$signature);

						
		}
		else
		{
			echo "not fully completed";
		}
		pr($result);
		pr($params);
		die;
	}
	
	function checkMultiple()
	{
		
		$this->loadComponent('Docusign');	
		$config = $this->Docusign->login();

	
	   
	   
	   
        /* Set Variables */

        $this->set('owner1', "Test thirty nine");
        $this->set('owner2', "Test forty");
       


        /*   */


       
		
		
	//Document 1	
	
	 $this->render(false);
        $CakePdf = new \CakePdf\Pdf\CakePdf();
        $CakePdf->template('application_disclosures', 'default');
        $CakePdf->viewVars($this->viewVars);
        // Get the PDF string returned
        $pdf = $CakePdf->output();
        // Or write it to file directly
        $id = "ApplicationDisclosures".rand(1, 99) . strtotime("now");
        $pdf = $CakePdf->write(WWW_ROOT . 'files' . DS . $id . '.pdf');
        $pdfURI =WWW_ROOT . 'files' . DS . $id . '.pdf';
        $lastPage = $CakePdf->getPageCount();
	
	$signature[0]['email_subject']="PACE Funding Contract";
	$signature[0]['email_content']="Custom Message";
	
	$signature[0]['user']['email']="kiplphp40@konstantinfosolutions.com";
	$signature[0]['user']['name']="Test 53";
	$signature[0]['user']['receiptId']="1";
	
	$signature[0]['sign'][0]['pageNumber']=$lastPage;
	$signature[0]['sign'][0]['receiptId']="1";
	$signature[0]['sign'][0]['documentId']="1";
	$signature[0]['sign'][0]['xpos']="100";
	$signature[0]['sign'][0]['ypos']="250";
	
	$signature[0]['dateSign'][0]['xpos']="345";
	$signature[0]['dateSign'][0]['ypos']="240";
	$signature[0]['dateSign'][0]['pageNumber']=$lastPage;
	$signature[0]['dateSign'][0]['receiptId']="1";
	$signature[0]['dateSign'][0]['documentId']="1";
	
	
	
	
	$signature[1]['user']['email']="kipldeveloper2@gmail.com";
	$signature[1]['user']['name']="test 54";
	$signature[1]['user']['receiptId']="2";
	
	$signature[1]['sign'][0]['pageNumber']=$lastPage;
	$signature[1]['sign'][0]['receiptId']="2";
	$signature[1]['sign'][0]['documentId']="1";
	$signature[1]['sign'][0]['xpos']="100";
	$signature[1]['sign'][0]['ypos']="345";
	
	$signature[1]['dateSign'][0]['xpos']="345";
	$signature[1]['dateSign'][0]['ypos']="340";
	$signature[1]['dateSign'][0]['pageNumber']=$lastPage;
	$signature[1]['dateSign'][0]['receiptId']="2";
	$signature[1]['dateSign'][0]['documentId']="1";
		
	//Document 1	
	
	
	//Document 2
	
	$this->render(false);
        $CakePdf = new \CakePdf\Pdf\CakePdf();
        $CakePdf->template('financing_statement', 'default');
        $CakePdf->viewVars($this->viewVars);
        // Get the PDF string returned
        $pdf = $CakePdf->output();
        // Or write it to file directly
        $id = "financingStatement".rand(1, 99) . strtotime("now");
        $pdf = $CakePdf->write(WWW_ROOT . 'files' . DS . $id . '.pdf');
        $pdfURI1 = WWW_ROOT . 'files' . DS . $id . '.pdf';
        
		$lastPage = $CakePdf->getPageCount();
		
	
	
	$signature[0]['sign'][1]['pageNumber']=$lastPage;
	$signature[0]['sign'][1]['receiptId']="1";
	$signature[0]['sign'][1]['documentId']="2";
	$signature[0]['sign'][1]['xpos']="100";
	$signature[0]['sign'][1]['ypos']="265";
	
        
	$signature[0]['dateSign'][1]['xpos']="340";
	$signature[0]['dateSign'][1]['ypos']="250";
	$signature[0]['dateSign'][1]['pageNumber']=$lastPage;
	$signature[0]['dateSign'][1]['receiptId']="1";
	$signature[0]['dateSign'][1]['documentId']="2";
	
	
	
	
	$signature[0]['initialSign'][0]['xpos']="480";
	$signature[0]['initialSign'][0]['ypos']="170";
	$signature[0]['initialSign'][0]['pageNumber']=$lastPage-1;
	$signature[0]['initialSign'][0]['receiptId']="1";
	$signature[0]['initialSign'][0]['documentId']="2";
	
	$signature[0]['initialSign'][1]['xpos']="480";
	$signature[0]['initialSign'][1]['ypos']="250";
	$signature[0]['initialSign'][1]['pageNumber']=$lastPage-1;
	$signature[0]['initialSign'][1]['receiptId']="1";
	$signature[0]['initialSign'][1]['documentId']="2";
	
	$signature[0]['initialSign'][2]['xpos']="480";
	$signature[0]['initialSign'][2]['ypos']="370";
	$signature[0]['initialSign'][2]['pageNumber']=$lastPage-1;
	$signature[0]['initialSign'][2]['receiptId']="1";
	$signature[0]['initialSign'][2]['documentId']="2";
	
	$signature[0]['initialSign'][3]['xpos']="480";
	$signature[0]['initialSign'][3]['ypos']="480";
	$signature[0]['initialSign'][3]['pageNumber']=$lastPage-1;
	$signature[0]['initialSign'][3]['receiptId']="1";
	$signature[0]['initialSign'][3]['documentId']="2";
	
	$signature[1]['sign'][1]['pageNumber']=$lastPage;
	$signature[1]['sign'][1]['receiptId']="2";
	$signature[1]['sign'][1]['documentId']="2";
	$signature[1]['sign'][1]['xpos']="100";
	$signature[1]['sign'][1]['ypos']="380";
	
	$signature[1]['dateSign'][1]['xpos']="340";
	$signature[1]['dateSign'][1]['ypos']="370";
	$signature[1]['dateSign'][1]['pageNumber']=$lastPage;
	$signature[1]['dateSign'][1]['receiptId']="2";
	$signature[1]['dateSign'][1]['documentId']="2";
	
	$signature[1]['initialSign'][0]['xpos']="480";
	$signature[1]['initialSign'][0]['ypos']="200";
	$signature[1]['initialSign'][0]['pageNumber']=$lastPage-1;
	$signature[1]['initialSign'][0]['receiptId']="2";
	$signature[1]['initialSign'][0]['documentId']="2";
	
	$signature[1]['initialSign'][1]['xpos']="480";
	$signature[1]['initialSign'][1]['ypos']="285";
	$signature[1]['initialSign'][1]['pageNumber']=$lastPage-1;
	$signature[1]['initialSign'][1]['receiptId']="2";
	$signature[1]['initialSign'][1]['documentId']="2";
	
	$signature[1]['initialSign'][2]['xpos']="480";
	$signature[1]['initialSign'][2]['ypos']="400";
	$signature[1]['initialSign'][2]['pageNumber']=$lastPage-1;
	$signature[1]['initialSign'][2]['receiptId']="2";
	$signature[1]['initialSign'][2]['documentId']="2";
	
	$signature[1]['initialSign'][3]['xpos']="480";
	$signature[1]['initialSign'][3]['ypos']="520";
	$signature[1]['initialSign'][3]['pageNumber']=$lastPage-1;
	$signature[1]['initialSign'][3]['receiptId']="2";
	$signature[1]['initialSign'][3]['documentId']="2";
	
	//
	
	
	$pdfArray=array($pdfURI,$pdfURI1);	
		$sendRequest = $this->Docusign->signatureRequestOnDocument($config, "sent", false, $pdfArray,$signature);
		
		 $sendRequest1 = $this->Docusign->downloadEnvelope($sendRequest);
        pr($sendRequest1);
        die;
		
		
	}
   function sendContractToCustomer($id) {
        $this->render(false);        
          $projectFilePath = Configure::read('Site.ProjectsFilePath');
        /*         * ******************Start*Electronic Consent Form****************************** */
          $this->loadComponent('DocusignTest');
        $config = $this->DocusignTest->login();
        $CakePdf = new \CakePdf\Pdf\CakePdf();
        $CakePdf->template('electronic_consent_form', 'default');
        $CakePdf->viewVars($this->viewVars);
        $pdf = $CakePdf->output();       
        $file = new File($projectFilePath . $id . DS . 'index.html', true, 0777);
        $name = 'Electronic-Consent-Form';
        $pdf_path1 = $projectFilePath . $id . DS . $name . '.pdf';
        $pdf = $CakePdf->write($pdf_path1);
        $signature[0]['email_subject'] = "PACE Funding Contract";
        $signature[0]['email_content'] = "Please review your contract detail.";
        $signature[0]['user']['email'] = 'testkiplinfo@gmail.com';
        $signature[0]['user']['name'] = 'Sonu Verma';
        $signature[0]['user']['receiptId'] = "1";
        $signature[0]['user']['order'] = 1;


        $signature[0]['initialSign'][0]['xpos'] = "200";
        $signature[0]['initialSign'][0]['ypos'] = "720";
        $signature[0]['initialSign'][0]['pageNumber'] = "1";
        $signature[0]['initialSign'][0]['receiptId'] = "1";
        $signature[0]['initialSign'][0]['documentId'] = "1";
        
            $signature[1]['user']['email'] = 'testkiplmail@gmail.com';
            $signature[1]['user']['name'] = 'Sonu Kumar';
            $signature[1]['user']['receiptId'] = "2";
            $signature[1]['user']['order'] = 1;

            $signature[1]['initialSign'][0]['xpos'] = "420";
            $signature[1]['initialSign'][0]['ypos'] = "720";
            $signature[1]['initialSign'][0]['pageNumber'] = "1";
            $signature[1]['initialSign'][0]['receiptId'] = "2";
            $signature[1]['initialSign'][0]['documentId'] = "1";
            
            
            $signature[2]['user']['email'] = 'kiplphp59@yopmail.com';
            $signature[2]['user']['name'] = 'Kipl Kumar';
            $signature[2]['user']['receiptId'] = "3";
            $signature[2]['user']['order'] = 2;
            
            $signature[3]['user']['email'] = 'kiplphp95@yopmail.com';
            $signature[3]['user']['name'] = 'Kipltest Kumar';
            $signature[3]['user']['receiptId'] = "4";
            $signature[3]['user']['order'] = 3;
/*
            $signature[2]['initialSign'][0]['xpos'] = "380";
            $signature[2]['initialSign'][0]['ypos'] = "720";
            $signature[2]['initialSign'][0]['pageNumber'] = "1";
            $signature[2]['initialSign'][0]['receiptId'] = "3";
            $signature[2]['initialSign'][0]['documentId'] = "1";
       
       */
        
        /*         * ******************End*Right to Cancel***************************** */
        $pdfFiles = array($pdf_path1);        
        $sendRequest = $this->DocusignTest->signatureRequestOnDocumentTest($config, "sent", false, $pdfFiles, $signature, 'dsfsdfsdf');
      echo   $sendRequest->getEnvelopeId();  die;     
        if ($sendRequest->getEnvelopeId() != '') {
            $res = array('error' => 0, 'envelope_id' => $sendRequest->getEnvelopeId());
        } else {
            $res = array('error' => 1);
        }

        return $res;
    }
    function noti() {
        //$this->Common->postCreditDecisionOnNotifyUrl();
        //$data=array('email'=>'e85c2c43-5d86-4084-a385-1b8ad1582d4a','password'=>'PaceDocusign','integratorKey'=>'PACE-54ae613d-e9b5-4a26-9845-ec4516b21345');
        $data=array('email'=>'pace@sungagefinancial.com','password'=>'pfgSungageDev','integratorKey'=>'d440906d-9fbc-4981-9866-505eda2a700d');
       // $this->Common->validateDocusignAccount($array);        
         $http = new Client();
         $options = array('headers' => ['X-DocuSign-Authentication'=>'<DocuSignCredentials><Username>'.$data['email'].'</Username><Password>'.$data['password'].'</Password><IntegratorKey>'.$data['integratorKey'].'</IntegratorKey></DocuSignCredentials>']);
         $url= 'https://demo.docusign.net/restapi/v2/login_information/';
       // pr($options) ;
         $response=$http->get($url, array(), $options);
         pr($response->json);die;
        exit;
    }
    function td() {
        
       $config= $this->Common->getPaymentFormulaInfo(10);
        
//        
//        $data=array('docusign_username'=>'e85c2c43-5d86-4084-a385-1b8ad1582d4a','docusign_password'=>'PaceDocusign','docusign_integrator_key'=>'PACE-54ae613d-e9b5-4a26-9845-ec4516b21345');
//         $this->loadComponent('Docusign');
//         try {
//              $config = $this->Docusign->login($data);
//         } catch (\Exception $exc) {
//             
//         }
//
      echo '<pre>';     
         print_R($config);
         die;
    }
    function fni(){        
        // $this->Common->executeFniProcess(38);
      /*  die;
        $customer=$this->CustomerDetails->findById(38)->first();
        $dd=$this->Common->submitApplicationOnFni($customer,$this);
        pr($dd);die;
        */
        
        
        $soapClient=new \SoapClient('https://tstprivfrontpcf.fni-stl.com/PCFApplicationService/services/ApplicationPort?wsdl',array("stream_context" => stream_context_create(
            array(
                'ssl' => array(
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                )
            )
        )));        
        $param = array(
            'TransactionControl' => array(
                'UserName' => 'paceuser',
                'Password' => 'testme66',
                'TransactionTimeStamp' => date('Y-mm-ddTh:m:s'),
                'Action' => 'APPLY',
                'ExternalReferenceNumber' => '10127',
                'PartnerId' => 'PACEFUNDING'
            ),
            'RequestData' => array(
                'AppRequest' => array(
                    'Applicants' => array(
                        'Applicant' => array(
                            array(
                                'ApplicantType' => 'PRIM',
                                'Name' => array(
                                    'First' => 'Sonu',
                                    'MI' => '',
                                    'Last' => 'Verma'
                                ),
                                'SSN' => '484066970',
                                'DateOfBirth' => '19880604',
                                'PrimaryPhone' => '',
                                'Email' => 'test1@yopmail.com',
                                'Addresses' => array(
                                    'Address' => array(
                                        array(
                                            'AddressType' => 'PROP',
                                            'AddressLine1' => '663 budd ave',
                                            'AddressLine2' => '',
                                            'City' => 'Campbell',
                                            'State' => 'CA',
                                            'PostalCode' => '95008'
                                        ),
                                        array(
                                            'AddressType' => 'CURR',
                                            'AddressLine1' => '711 Evelyn Ave',
                                            'AddressLine2' => '',
                                            'City' => 'Albany',
                                            'State' => 'CA',
                                            'PostalCode' => '94706'
                                        )
                                    )
                                )
                            ),
                            array(
                                'ApplicantType' => 'COAPP',
                                'Name' => array(
                                    'First' => 'Sonu',
                                    'MI' => '',
                                    'Last' => 'Kumar'
                                ),
                                'SSN' => '484066970',
                                'DateOfBirth' => '19880604',
                                'PrimaryPhone' => '9639639633',
                                'Email' => 'test@yopmail.com',
                                'Addresses' => array(
                                    'Address' => array(
                                        'AddressType' => 'CURR',
                                        'AddressLine1' => '711 Evelyn Ave',
                                        'AddressLine2' => '',
                                        'City' => 'Albany',
                                        'State' => 'CA',
                                        'PostalCode' => '94706'
                                    )
                                )
                            )
                        )
                    ),
                    'LenderFields'=>array(
                        'LenderField1'=>'Individual',
                        'LenderField2'=>'Single Family',
                        'LenderField3'=>'Test'
                    )
                )
            )
        );
        // pr($param);die;
        // pr($soapClient->__getFunctions());
        $ss=$soapClient->submitApplication($param);
        pr($ss);die;
    }
}
