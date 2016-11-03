<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\I18n\Number;
use Cake\Network\Http\Client;
use Cake\Core\Configure;
use Cake\Utility\Xml;


/**
 * Common component
 */
class CommonComponent extends Component {

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * 
     * Payment Calculator and Savings Formulas
     * 
     * 
     */
    function getPaymentFormulaInfo($term = null) {
        $this->Rates = TableRegistry::get('Rates');
        $this->MasterRates = TableRegistry::get('MasterRates');
        $current_date = date('Y-m-d');
        $rate_data = $this->Rates->find('all', ['contain' => ['Terms', 'RateRecords' => ['sort'=>'from_date asc','conditions' => ['RateRecords.status' => 1,'OR'=>['to_date'=>'0000-00-00','to_date >='=>date('Y-m-d')]]]], 'conditions' => ['Terms.term' => $term]])->first();       
        if (!empty($rate_data['rate_records'])) {
            $rate = $rate_data['rate_records'][0]['rate'];
        } else {
            $rate = $rate_data['rate'];
        }
        $master_rate_data = $this->MasterRates->find('all', ['contain' => ['MasterRateRecords' => ['sort'=>'from_date asc','conditions' => ['MasterRateRecords.status' => 1,'OR'=>['to_date'=>'0000-00-00','to_date >='=>date('Y-m-d')]]]]]);
        $master_rate_arr = array();
        foreach ($master_rate_data as $master_rate) {
            if (!empty($master_rate['master_rate_records'])) {
                $master_rate_arr[$master_rate['slug']] = $master_rate['master_rate_records'][0]['rate'];
            } else {
                $master_rate_arr[$master_rate['slug']] = $master_rate['rate'];
            }
        }         
        return array_merge($master_rate_arr, array('rate' => $rate));
    }

    function getPaymentTableData($assessment, $pmt, $term, $date, $tax_rate, $formula_data) {
        $i = 0;
        $payment_data = array();
        $total_net_payment = 0;
        $year=date('Y', strtotime($date));
        for ($pnum = 1; $pnum <= $term; $pnum++) {
            $start_date = $year+$i;
            $j = $i + 1;
            $end_date = $year+$j;
            
            $date_range = $start_date . " - " . $end_date;
            $payment = $assessment;
            $interest = $payment * ($formula_data['rate'] / 100);
            $principal = $pmt - $interest;
            $expenses = $formula_data['annual_administrative_fee'];
            $total = $interest + $principal + $expenses;
            $estimated_tax = ($interest * $tax_rate) / 100;
            $net_payment = $total - $estimated_tax;
            $assessment = ($payment - $principal);
            $total_net_payment = $total_net_payment + $net_payment;
            $payment_data['data'][] = array(
                'date' => $date_range,
                'payment' => number_format((float) $pmt, 2, '.', ''),
                'principal' => number_format((float) $principal, 2, '.', ''),
                'interest' => number_format((float) $interest, 2, '.', ''),
                'expenses' => $expenses,
                'total' => number_format((float) $total, 2, '.', ''),
                'estimated_tax' => number_format((float) $estimated_tax, 2, '.', ''),
                'net_payment' => number_format((float) $net_payment, 2, '.', ''),
                'monthly_net_payment' => round(($net_payment/12),2), 
                'tax_deduction_payment' => number_format((float) $pmt - $estimated_tax, 2, '.', '')
            );
            $i++;
        }
        $payment_data['total_net_payment'] = number_format((float) $total_net_payment, 2, '.', '');
        return $payment_data;
    }

    function getAssessment($amount, $first_payment_date, $completion_date, $formula_data) {
        $daily_rate = ($formula_data['rate'] / 360) / 100;
        $issuance_delay = $formula_data['issuance_delay'];
        $completion_date = date('Y-m-d', strtotime($completion_date));
        $issuance_date = date('Y-m-d', strtotime($completion_date . '+ ' . $issuance_delay . ' days'));
        $assessment_subtotal_1 = $amount + $formula_data['lien_recording_fee'] + $formula_data['loan_loss_reserve'] + $formula_data['foreclosure_expense_reserve'] + $formula_data['annual_administrative_fee'];

        $days_between = (strtotime($first_payment_date) - strtotime($issuance_date)) / (60 * 60 * 24);
        $cap_int_factor = $daily_rate * $days_between;
        //Origination Fee Total = (Assessment Subtotal 1 / (1-Origination Fee) – Assessment Subtotal 1) x ((1+ (CapIntFactor/(1-CapIntFactor))*(1+Origination Fee))  
        $formula_data['origination_fee'] = $formula_data['origination_fee'] / 100;
        $origination_fee_total = ($assessment_subtotal_1 / (1 - $formula_data['origination_fee']) - $assessment_subtotal_1) * ((1 + ($cap_int_factor / (1 - $cap_int_factor)) * (1 + $formula_data['origination_fee'])));

        //Assessment Subtotal 2 = Assessment Subtotal 1 + Origination Fee        
        $assessment_subtotal_2 = $assessment_subtotal_1 + $origination_fee_total;

        //CapitalizedInterest = Assessment Subtotal 2 * CapIntFactor / (1 – CapIntFactor)
        $capitalized_interest = ($assessment_subtotal_2 * ($cap_int_factor / (1 - $cap_int_factor)));

        //Assessment = Assessment Subtotal 2 + CapitalizedInterest

        $assessment = ($assessment_subtotal_2 + $capitalized_interest);
        $assessment = round($assessment, 2);
        return array('assessment_1' => $assessment_subtotal_1, 'assessment' => $assessment, 'capitalized_interest' => $capitalized_interest, 'origination_fee_total' => $origination_fee_total);
    }

    function encrypt($data, $secret = 'secret') {
        //Generate a key from a hash
        $key = md5(utf8_encode($secret), true);
        //Take first 8 bytes of $key and append them to the end of $key.
        $key .= substr($key, 0, 8);
        //Pad for PKCS7
        $blockSize = mcrypt_get_block_size('tripledes', 'ecb');
        $len = strlen($data);
        $pad = $blockSize - ($len % $blockSize);
        $data .= str_repeat(chr($pad), $pad);
        //Encrypt data
        $encData = mcrypt_encrypt('tripledes', $key, $data, 'ecb');
        return base64_encode($encData);
    }

    function decrypt($data, $secret = 'secret') {
        //Generate a key from a hash
        $key = md5(utf8_encode($secret), true);
        //Take first 8 bytes of $key and append them to the end of $key.
        $key .= substr($key, 0, 8);
        $data = base64_decode($data);
        $data = mcrypt_decrypt('tripledes', $key, $data, 'ecb');
        $block = mcrypt_get_block_size('tripledes', 'ecb');
        $len = strlen($data);
        $pad = ord($data[$len - 1]);
        return substr($data, 0, strlen($data) - $pad);
    }

    function generateCode($Length) {

        $characters = '0123456789ABCDEF0123456789ABCDEFGHIJKL0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZMNOPQRSTUVWXYZGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $Length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getZestimate($project) {
        $http = new Client();
        $url = 'http://www.zillow.com/webservice/GetDeepSearchResults.htm';
        $response = $http->get($url, ['address' => $project['address'], 'citystatezip' => $project['city'] . ', ' . $project['state'] . ', ' . $project['zipcode'], 'zws-id' => Configure::read('Zillow.ZwsId')]);
        if ($response->code == 200) {
            $data = Xml::toArray(Xml::build($response->body()));
            if ($data['searchresults']['message']['code'] == 0) {
                if (isset($data['searchresults']['response']['results']['result']['zestimate']['amount']['@'])) {
                    $zestimate = $data['searchresults']['response']['results']['result']['zestimate']['amount']['@'];
                } else {
                    $zestimate = 0;
                }
            } else {
                $zestimate = 0;
            }
        } else {
            $zestimate = 0;
        }
        return $zestimate;
    }

    function getExpectedAmountWithMortgage($project, $mortgage,$zestimate=null) {
        if($zestimate==''){
           $zestimate = $this->getZestimate($project);
        }
        $amt_array = array(200000);
        $amount_1 = $zestimate - $mortgage;        
        array_push($amt_array, $amount_1);
        
        if ($zestimate > 700000) {
            $percentage_15 = (700000 * 15) / 100;
            $diff = $zestimate - 700000;
            $percentage_10 = ($diff * 10) / 100;
            $amount_2 = $percentage_15 + $percentage_10;
        } else {
            $amount_2 = ($zestimate * 15) / 100;
        }
        array_push($amt_array, $amount_2);  
        $amount = min($amt_array);
        if ($amount < 0) {
            $amount = 0;
        }else{
            $amount = $amount*Configure::read('Site.ReduceMaxProjectAmountBy');   
        }
        return $amount;
    }
    function postCreditDecisionOnNotifyUrl($project = null) {
        if ($project['notify_url'] != '') {
            $http = new Client();
            $options = array('type' => 'json', 'ssl_verify_peer' => false, 'ssl_verify_host' => false);
            $creditDecision = '';
            if ($project['status'] == 6) {
                $creditDecision = 'Approved';
            } elseif ($project['status'] == 7 || $project['status']==14) {
                $creditDecision = 'Declined';
            } elseif ($project['status'] == 8) {
                $creditDecision = 'Manual';
            }
            if ($creditDecision != '') {
                $data = json_encode(array('projectId' => $project['id'], 'creditDecision' => $creditDecision,'amount'=>$project['auth_amount'],'avm'=>$project['avm'],'apn'=>$project['apn'],'legalDescription'=>$project['legal_description'],'muncipality'=>$project['muncipality;'],'county'=>$project['county']));
                $url = $project['notify_url'];
                try {
                    $http->post($url, $data, $options);
                } catch (\Exception $exc) {
                  
                }
            }
        }
    }
    function submitApplicationOnFni($customers) { 
         if (Configure::read('FNI.IsLive') == 1) {
                $username = Configure::read('FNI.Username');
                $password = Configure::read('FNI.Password');
                $host = Configure::read('FNI.URL');
            } else {
                $username = Configure::read('FNI.DemoUsername');
                $password = Configure::read('FNI.DemoPassword');
                $host = Configure::read('FNI.DemoURL');
         }
         $partnerId=Configure::read('FNI.PartnerId');
         
         $otherName='';
         if ($customers['trust_name'] != '') {
            $otherName = $customers['trust_name'];
          } elseif ($customers['corporation_llc_name'] != '') {
            $otherName = $customers['corporation_llc_name'];
          } elseif ($customers['other_name'] != '') {
            $otherName = $customers['other_name'];
          }
        $applicant=$firstCustomer = array(
            'ApplicantType' => 'PRIM',
            'Name' => array(
                'First' => $customers['fo_first_name'],
                'MI' => '',
                'Last' => $customers['fo_last_name'],
            ),
            'SSN' => str_replace('-', '', $this->decrypt($customers['fo_ssn'])),
            'DateOfBirth' => str_replace('-', '', $this->getDateForFni($this->decrypt($customers['fo_dob']))),
            'PrimaryPhone' => '',
            'Email' => $customers['fo_email'],
            'Addresses' => array(
                'Address' => array(
                    array(
                        'AddressType' => 'PROP',
                        'AddressLine1' => $customers['address'],
                        'AddressLine2' => $customers['unit'],
                        'City' => $customers['city'],
                        'State' => $customers['state'],
                        'PostalCode' => $customers['zipcode']
                    ),
                    array(
                        'AddressType' => 'CURR',
                        'AddressLine1' => $customers['fo_address'],
                        'AddressLine2' => $customers['fo_unit'],
                        'City' => $customers['fo_city'],
                        'State' => $customers['fo_state'],
                        'PostalCode' => $customers['fo_zipcode']
                    )
                )
            )
        );
        $secondCustomer=array();
         if ($customers['so_first_name'] != '' && $customers['so_last_name'] != '') {
            $secondCustomer = array(
                'ApplicantType' => 'COAPP',
                'Name' => array(
                    'First' => $customers['so_first_name'],
                    'MI' => '',
                    'Last' => $customers['so_last_name']
                ),
                'SSN' => str_replace('-', '', $this->decrypt($customers['so_ssn'])), 
                'DateOfBirth' => str_replace('-', '', $this->getDateForFni($this->decrypt($customers['so_dob']))), 
                'PrimaryPhone' => '',
                'Email' => $customers['so_email'],
                'Addresses' => array(
                    'Address' => array(
                        'AddressType' => 'CURR',
                        'AddressLine1' => $customers['so_address'],
                        'AddressLine2' => $customers['so_unit'],
                        'City' => $customers['so_city'],
                        'State' => $customers['so_state'],
                        'PostalCode' => $customers['so_zipcode']
                    )
                )
            );
           $applicant=array($firstCustomer,$secondCustomer);  
        }
        $requestData = array(
            'TransactionControl' => array(
                'UserName' => $username,
                'Password' => $password,
                'TransactionTimeStamp' => date('Y-mm-ddTh:m:s'),
                'Action' => 'APPLY',
                'ExternalReferenceNumber' => $customers['project_id'],
                'PartnerId' => $partnerId
            ),
            'RequestData' => array(
                'AppRequest' => array(
                    'Applicants' => array(
                        'Applicant' => $applicant
                    ),
                    'LenderFields'=>array(
                        'LenderField1'=>$customers['property_ownership'],
                        'LenderField2'=>$customers['property_type'],
                        'LenderField3'=>$otherName
                        )
                )
            )
        );       
        try {
            $soapClient = new \SoapClient($host, array('trace' => 1,'exceptions' => true,"stream_context" => stream_context_create(
                        array(
                            'ssl' => array(
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                            )
                        )
            )));            
            $response = array('error'=>0,'data'=>$soapClient->submitApplication($requestData));
         
        }
        catch (\Exception $exc){
            $response=array('error'=>1);
        }catch (\SoapFault $client) {
            $response=array('error'=>1);
        }        
        return $response;
    }
     function fniProcessApply($customer_id=null,$controller){   
         $this->CustomerDetails=TableRegistry::get('CustomerDetails');
         $this->Projects=TableRegistry::get('Projects');
         $this->SystemMails=TableRegistry::get('SystemMails');
         
         $customer=$this->CustomerDetails->findById($customer_id)->contain('Projects.Users')->first();
         if(!empty($customer)){
            $response=$this->submitApplicationOnFni($customer); 
            $projectData=array();
            if($response['error']==0){
               $projectData['fni_status']=$response['data']->Decision; 
               $projectData['fni_reference_number']=$response['data']->FNIReferenceNumber; 
               $projectData['fni_transaction_id']=$response['data']->TransactionID; 
               $projectData['avm']=$response['data']->PropertyValue; 
               $projectData['apn']=$response['data']->ParcelNumber; 
               $projectData['legal_description']=$response['data']->LegalDescription; 
               $projectData['municipality']=$response['data']->CityName; 
               $projectData['county']=$response['data']->CountyName; 
               if($response['data']->Decision=='A'){                 
                  $projectData['auth_amount']=$response['data']->LineAssignmentAmt;
                  $projectData['annual_property_tax_payment']=$response['data']->AnnualPropertyTax;
                  $projectData['status']=6;
                  $projectData['credit_approved_date']=Time::parseDate(date('Y-m-d'), Configure::read('Site.CakeDateFormat'));
               }elseif($response['data']->Decision=='P'){
                  $projectData['status']=8;
               }elseif($response['data']->Decision=='D'){
                  $projectData['status']=7;
               }
               if($this->Projects->updateAll($projectData, ['id'=>$customer['project_id']])){
                 $this->CustomerDetails->updateAll(array('fo_ssn'=>'','fo_dob'=>'','so_ssn'=>'','so_dob'=>''),array('id'=>$customer_id));  
                 if ($projectData['status'] == 6) {
                         /****************Send*Email*To*Sales*Person**************** */
                        $salesPersonMail = $this->SystemMails->findByEmailType('CreditDecisionToSalesRep')->first()->toArray();
                        $salesPersonMail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $salesPersonMail['message']);
                        $salesPersonMail['message'] = str_replace('[Customer]', $customer['project']['owner_name'], $salesPersonMail['message']);
                        $salesPersonMail['message'] = str_replace('[Amount]', Number::currency($projectData['auth_amount'], 'USD'), $salesPersonMail['message']);
                        $salesPersonMail['message'] = str_replace('[SalesRep]', $customer['project']['user']['name'], $salesPersonMail['message']);
                        $salesPersonMail['subject'] = str_replace('[Customer]', $customer['project']['owner_name'], $salesPersonMail['subject']);
                        $salesPersonMail['to'] =  $customer['project']['user']['email'];
                        if ($controller->sendEmail($salesPersonMail)) {}
                        /***************************Send*Approve*Email*************************** */
                        $mail = $secondMail = $this->SystemMails->findByEmailType('CreditDecisionToCustomer')->first()->toArray();
                        $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
                        $mail['message'] = str_replace('[Customer]', $customer['project']['owner_name'], $mail['message']);
                        $mail['message'] = str_replace('[Amount]', Number::currency($projectData['auth_amount'], 'USD'), $mail['message']);
                        $mail['to'] = $customer['fo_email'];
                        if ($controller->sendEmail($mail)) {}
                        if ($customer['so_first_name'] != '') {
                            $secondMail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $secondMail['message']);
                            $secondMail['message'] = str_replace('[Customer]', $customer['so_first_name'] . ' ' . $customer['so_last_name'], $secondMail['message']);
                            $secondMail['message'] = str_replace('[Amount]', Number::currency($projectData['auth_amount'], 'USD'), $secondMail['message']);
                            $secondMail['to'] = $customer['so_email'];
                            if ($controller->sendEmail($secondMail)) {}
                        }                       
                    }elseif ($projectData['status'] == 7) {
                        /**************************Send*Declined*Email************************** */
                        $declinedEmail = $this->SystemMails->findByEmailType('CreditApplicationDeclined')->first()->toArray();
                        $declinedEmail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $declinedEmail['message']);
                        $declinedEmail['to'][] = $customer['fo_email'];
                        if ($customer['so_first_name'] != '') {
                            $declinedEmail['to'][] = $customer['so_email'];
                        }
                        if ($controller->sendEmail($declinedEmail)) {}
                    }
                }
            }
            unset($projectData['status']);
            return $projectData;
         }
    }
    function getDateForFni($date) {
        $new_date = explode('/', $date);
        return $date = $new_date[2] .$new_date[0] . $new_date[1];
    }
    function bgExec($command) {
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen("start /B " . $command, "r"));
        } else {
            exec($command . " > /dev/null &");
        }
    }
    function executeFniProcess($customer_id) {
         $command = 'wget -qO- '.SITE_FULL_URL.'projects/fni-process/'.$customer_id;
         $this->bgExec($command);
    }
    
     function generateOtpCode($Length) {

        $characters = '012345678901234567890123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $Length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    
}
