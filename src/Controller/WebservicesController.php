<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Utility\Xml;
use Cake\I18n\Number;
use Cake\I18n\Time;
use Financial\Financial;
use Cake\Network\Http\Client;
use Cake\Utility\Hash;
use Cake\I18n\Date;
use Cake\Validation\Validator;
use Cake\Filesystem\File;

/**
 * Webservices Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class WebservicesController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadModel('Users');
        $this->loadModel('Projects');
        $this->loadModel('ProjectCalculatePayments');
        $this->loadComponent('Common');
        $this->loadModel('SystemMails');
        $this->loadModel('ProjectTypes');
        $this->loadModel('Equipments');
        $this->loadComponent('Cookie');
        $this->loadModel('EquipmentInformations');
        $this->loadModel('CustomerDetails');
        $this->loadModel('Countries');
        $this->loadModel('States');
        $this->loadModel('ProjectDocuments');
        $this->loadModel('SystemMails');
        $this->loadModel('ContractDetails');
        $this->loadModel('RateRecords');
        $this->loadModel('Terms');
    }

    public function beforeFilter(\Cake\Event\Event $event) {
        $this->eventManager()->off($this->Csrf);
        $this->viewBuilder()->layout();
        $this->autoRender = false;
        $this->Auth->allow();
        parent::beforeFilter($event);
        $this->Security->config(['validatePost'=>false]);
        $postMethod = array('postFniResult', 'checkAddressEligibility', 'gettingCalculatePayment', 'sendCreditApplication', 'postApplication', 'checkProjectStatus', 'sendContract', 'addContractor', 'addSalesperson');
        if (!$this->request->is('post') && in_array($this->request->params['action'], $postMethod)) {
            $response = array('message' => 'Method not allowed.');
            $this->response->statusCode(405);
            $this->response->body(json_encode($response));
            $this->response->type('json');
            $this->response->send();
            exit;
        }
        Configure::write('Site.FniAuthKey','n5faFG5Jtken5LUD');
    }

    /**
     * ********************************************************************
     * ******************  Address  Eligibility APi  **********************
     * *******************************************************************
     */
    public function checkAddressEligibility() {
        $Results = [];
        $Results['status'] = 0;
        $Results['message'] = [];
        $type = 2; //######## type "1" for website "2" for api  
        if (!isset($this->request->data['auth_key'])) {
            $Results['message'][] = 'Auth key is required';
        }
        if (!isset($this->request->data['contractor_code'])) {

            $Results['message'][] = 'Contractor code is required';
        }
        if (!isset($this->request->data['salesperson_code'])) {
            $Results['message'][] = 'Salesperson code is required';
        }
        if (!isset($this->request->data['address'])) {
            $Results['message'][] = 'Property address is required';
        }
        if (!isset($this->request->data['zipcode'])) {
            $Results['message'][] = 'Zip code is required';
        }
        if (!empty($Results['message'])) {
            echo json_encode($Results);
            exit;
        }
        $checkAuthKey = $this->checkAuthKey(); /* check auth key contractor code and salesperson code */
        if ($checkAuthKey['status'] == 0) {
            $Results['message'] = $checkAuthKey['message'];
            echo json_encode($Results);
            exit;
        }
        // create project 
        $project = $this->Projects->newEntity();
        $project['address'] = $this->request->data['address'];
        $project['zipcode'] = $this->request->data['zipcode'];
        if ($checkAuthKey['id'] != null) {
            $project['user_id'] = $checkAuthKey['id'];
        }
        $project['type'] = $type;

        $http = new Client();

        $url = Configure::read('UsGeoCoder.URL');

        $response = $http->get($url, ['address' => $this->request->data['address'], 'zipcode' => $this->request->data['zipcode'], 'authkey' => Configure::read('UsGeoCoder.AuthKey')], ['ssl_verify_peer' => false]);
        if ($response->code == 200) {
            $data = Xml::toArray(Xml::build($response->body()));
            if ($data['usgeocoder']['request_status']['request_status_code']['@'] == 'Success' && isset($data['usgeocoder']['address_info']['address_status']['@incorporated'])) {
                if ($data['usgeocoder']['address_info']['address_status']['@incorporated'] == 'true') {
                    if ($data['usgeocoder']['address_info']['city']['@solar_eligibility'] == 'in') {

                        $Results['status'] = 1;
                        $ResultsR['check'] = 1;
                        $project['status'] = 1;
                        $Results['message'][] = "Property is eligible for PACE Funding";
                    } else {
                        $Results['status'] = 1;
                        $project['status'] = 2;
                        $Results['message'][] = "Property is not eligible for PACE Funding";
                    }
                } else {
                    if ($data['usgeocoder']['address_info']['county']['@solar_eligibility'] == 'in') {
                        $Results['status'] = 1;
                        $ResultsR['check'] = 1;
                        $project['status'] = 1;
                        $Results['message'][] = "Property is eligible for PACE Funding";
                    } else {
                        $Results['status'] = 1;
                        $project['status'] = 2;
                        $Results['message'][] = "Property is not eligible for PACE Funding";
                    }
                }


                $project['step'] = 1;

                $this->request->data['city'] = (isset($data['usgeocoder']['jurisdictions_info']['municipal']['municipal_name'])) ? $data['usgeocoder']['jurisdictions_info']['municipal']['municipal_name'] : (isset($data['usgeocoder']['address_info']['city']['@']) ? $data['usgeocoder']['address_info']['city']['@'] : '');
                $project['state'] = (isset($data['usgeocoder']['address_info']['state'])) ? $data['usgeocoder']['address_info']['state'] : '';
                $projectDetail = $this->Projects->save($project);
                if (isset($ResultsR['check'])) {
                    $Results['projectId'] = $projectDetail['id'];
                    $Results['ExpectedAmountData'] = $this->gettingExpectedAmount($projectDetail['id']);
                }
            } else {
                $Results['message'][] = "Property address invalid.";
            }
        } else {

            $Results['message'][] = "An error occurred, Please try again.";
        }

        echo json_encode($Results);
        exit;
    }

    public function gettingExpectedAmount($id = null) {
        $project = $this->Projects->get($id);

        if ($project['unit'] != '') {
            $amount = 25000;
            $this->request->data['expected_amount'] = $amount;
            $project = $this->Projects->patchEntity($project, $this->request->data);
            $this->Projects->save($project);
            $json = array('error' => 0, 'msg' => 'Maximum expected project amount is ' . Number::currency($amount, 'USD'));
            echo json_encode($json);
            exit;
        }

        $http = new Client();
        $url = 'http://www.zillow.com/webservice/GetDeepSearchResults.htm';
        $response = $http->get($url, ['address' => $project['address'], 'citystatezip' => $project['city'] . ', ' . $project['state'] . ', ' . $project['zipcode'], 'zws-id' => Configure::read('Zillow.ZwsId')]);
        if ($response->code == 200) {

            $data = Xml::toArray(Xml::build($response->body()));
            if ($data['searchresults']['message']['code'] == 0) {

                if(isset($data['searchresults']['response']['results']['result']) && isset($data['searchresults']['response']['results']['result'][0])){
                  $data['searchresults']['response']['results']['result']=$data['searchresults']['response']['results']['result'][0];  
                }
                if (isset($data['searchresults']['response']['results']['result']['zestimate']['amount']['@'])) {
                    $zestimate = $data['searchresults']['response']['results']['result']['zestimate']['amount']['@'];
                    $amt_array = array(200000);
                    if (isset($data['searchresults']['response']['results']['result']['lastSoldPrice']['@']) && isset($data['searchresults']['response']['results']['result']['lastSoldDate'])) {
                        $lastSoldPrice = $data['searchresults']['response']['results']['result']['lastSoldPrice']['@'];
                        $lastSoldDate = date('Y', strtotime($data['searchresults']['response']['results']['result']['lastSoldDate']));
                        $yearDiffrence = date('Y') - $lastSoldDate;
                        $amount_1 = ($zestimate - (0.85 * $lastSoldPrice)) + (0.025 * $yearDiffrence * $lastSoldPrice);
                        array_push($amt_array, $amount_1);
                    }

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
                    } else {
                        $amount = $amount * Configure::read('Site.ReduceMaxProjectAmountBy');
                    }
                    $this->request->data['expected_amount'] = $amount;
                    $project = $this->Projects->patchEntity($project, $this->request->data);
                    $this->Projects->save($project);
                    $json = array('error' => 0, 'msg' => 'Maximum expected project amount is ' . Number::currency($amount, 'USD'));
                } else {
                    $json = array('error' => 1, 'msg' => 'Sorry, we cannot provide an estimated maximum project amount at this time. Please contact support at (844) USE-PACE.');
                }
            } else {
                $json = array('error' => 1, 'msg' => $data['searchresults']['message']['text']);
            }
        } else {
            $json = array('error' => 1, 'msg' => 'An error occurred, Please try again.');
        }

        return $json;
    }

    /**
     * ********************************************************************
     * ******************  Payment Calculator  *****************************
     * *******************************************************************
     */
    function gettingCalculatePayment() {
        $response = [];
        $response['status'] = 0;
        $response['message'] = [];

        if (!isset($this->request->data['auth_key'])) {
            $response['message'][] = 'Auth key is required';
        }
        if (!isset($this->request->data['contractor_code'])) {
            $response['message'][] = 'Contractor code is required';
        }
        if (!isset($this->request->data['salesperson_code'])) {
            $response['message'][] = 'Salesperson code is required';
        }

        if (!isset($this->request->data['projectId'])) {
            $response['message'][] = 'Project id is required.';
        }
        if (!isset($this->request->data['completion_date'])) {
            $response['message'][] = 'Completion date is required.';
        }

        if (!isset($this->request->data['term_of_assessment'])) {
            $response['message'][] = 'Term of assessment is required.';
        }

        if (!isset($this->request->data['project_amount_est'])) {
            $response['message'][] = 'Project assessment amount is required.';
        }

        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }

        $checkAuthKey = $this->checkAuthKey();

        if ($checkAuthKey['status'] == 0) {
            $response['message'][] = $checkAuthKey['message'];
            echo json_encode($response);
            exit;
        }

        $checkProject = $this->Projects->findById($this->request->data['projectId'])->first();
        if (empty($checkProject)) {
            $response['message'][] = 'Invalid Project Id.';
            echo json_encode($response);
            exit;
        }
        $project = $this->Projects->get($this->request->data['projectId']);
        if (empty($project)) {
            $response['message'][] = "Invalid project Id.";
            echo json_encode($response);
            exit;
        }
        if ($checkAuthKey['id'] != $project['user_id']) {
            $response['message'][] = "You are not authorized to access project by this salesperson code.";
            echo json_encode($response);
            exit;
        }
        if ($project['deleted'] == 1) {
            $response['message'][] = 'This project is deleted.';
        }

        if ($project['step'] == 0) {
            $response['message'][] = 'Please check address eligibility.';
        }
        if ($project['step'] > 2) {
            $response['message'][] = 'You have already send credit application.';
        }

        // ------------  //project  exit or not ---------------
        // -------------- Project Amount ----------------------
        if (!is_numeric($this->request->data['project_amount_est'])) {
            $response['message'][] = 'Project Amount is not valid';
        }
        // -------------- //Project Amount----------------------
        // -------------- Project Amount ----------------------
        if (!is_numeric($this->request->data['term_of_assessment'])) {
            $response['message'][] = 'Term of assessment is not valid';
        }
        // -------------- //Project Amount----------------------
        // -------------- Project Amount ----------------------



        if (isset($this->request->data['tax_credit']) && $this->request->data['tax_credit'] == 'Yes') {

            if (isset($this->request->data['amount_eligible_for_credit'])) {

                if (!is_numeric($this->request->data['amount_eligible_for_credit'])) {
                    $response['message'][] = 'Amount eligible for credit is not valid.';
                }

                if ($this->request->data['amount_eligible_for_credit'] > $this->request->data['project_amount_est']) {
                    $response['message'][] = 'Amount eligible for credit greater than Cost Of Project.';
                }
            } else {
                $this->request->data['amount_eligible_for_credit'] = $this->request->data['project_amount_est'];
            }
        }

        if (isset($this->request->data['tax_rate'])) {
            if ($this->request->data['tax_rate'] > 50) {
                $response['message'][] = 'Tax rate must be less than or equal to 50.';
            }
        } else {
            $this->request->data['tax_rate'] = 0;
        }

        if ($this->checkdateFormat($this->request->data['completion_date']) != true) {
            $response['message'][] = 'Completion date  not valid.';
        }


        if (!in_array($this->request->data['term_of_assessment'], $this->currentTerms())) {
            $response['message'][] = 'Term of assessment not valid.';
        }




        $today = date("Y-m-d");
        $completion_date = date("y-m-d", strtotime($this->request->data['completion_date']));

        if (strtotime($today) >= strtotime($completion_date)) {
            $response['message'][] = 'Completion date must be greater than current date.';
        }


        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }
        $project = $this->Projects->get($this->request->data['projectId']);

        if ($checkAuthKey['id'] != null) {
            $project['user_id'] = $checkAuthKey['id'];
        }


        $this->request->data['completion_date'] = $this->getDate($this->request->data['completion_date']);
        $formula_data = $this->Common->getPaymentFormulaInfo($this->request->data['term_of_assessment']);
        $daily_rate = ($formula_data['rate'] / 360) / 100;
        $current_date = date('Y-m-d');
        $june_date = date('Y-06-23');
        $june_date_for_maturity = date('Y-06-23');


        $completion_date = date('Y-m-d', strtotime($this->request->data['completion_date']));


        if ($completion_date > $current_date && $completion_date < $june_date) {
            $first_payment_date = date('Y-09-02');
        } else {
            $first_payment_date = date('Y-09-02', strtotime('+1 year'));
        }
        $date = new Date(date('Y-09-02'));
        if ($completion_date > $current_date && $completion_date < $june_date_for_maturity) {
            $date->modify('+' . $this->request->data['term_of_assessment'] . ' year');
        } else {
            $date->modify('+' . ($this->request->data['term_of_assessment'] + 1) . ' year');
        }
        $maturity_date = $date->format('Y-m-d');
        $this->request->data['maturity_date'] = Time::parseDate($maturity_date, Configure::read('Site.CakeDateFormat'));


        $assessment_data = $this->Common->getAssessment($this->request->data['project_amount_est'], $first_payment_date, $this->request->data['completion_date'], $formula_data);
        $assessment = $assessment_data['assessment'];
        $assessment_1 = $assessment_data['assessment_1'];
        $capitalized_interest = $assessment_data['capitalized_interest'];
        $fin = new Financial();
        $pmt = $fin->PMT(($formula_data['rate'] / 100), $this->request->data['term_of_assessment'], -$assessment); //+$formula_data['annual_administrative_fee'];
        $pmt = round($pmt, 2);
        $payment_data = $this->Common->getPaymentTableData($assessment, $pmt, $this->request->data['term_of_assessment'], $first_payment_date, $this->request->data['tax_rate'], $formula_data);
        $pmt_all_data = Hash::extract($payment_data['data'], '{n}.total');

        $assessment_with_interest = round($assessment_1 + $capitalized_interest, 2);
        $apr = $fin->apr($assessment_with_interest, $pmt_all_data);
        /*         * ***************Payment after re-amortization***************** */
        $payment_data_re_amortization = array();
        if (isset($this->request->data['tax_credit']) && $this->request->data['tax_credit'] == 'Yes') {
            if ($this->request->data['amount_eligible_for_credit'] > 0) {
                $percentage_30 = ($this->request->data['amount_eligible_for_credit'] * 30) / 100;
                $amount_after_re_amortization = $this->request->data['project_amount_est'] - $percentage_30;
            } else {
                $percentage_30 = ($this->request->data['project_amount_est'] * 30) / 100;
                $amount_after_re_amortization = $this->request->data['project_amount_est'] - $percentage_30;
            }
            $pmt_re_amortization_data = $this->Common->getAssessment($amount_after_re_amortization, $first_payment_date, $this->request->data['completion_date'], $formula_data);
            $amount_after_re_amortization = $pmt_re_amortization_data['assessment'];
            $pmt_re_amortization = $fin->PMT(($formula_data['rate'] / 100), $this->request->data['term_of_assessment'], -$amount_after_re_amortization);
            $pmt_re_amortization = round($pmt_re_amortization, 2);
            $payment_data_re_amortization = $this->Common->getPaymentTableData($amount_after_re_amortization, $pmt_re_amortization, $this->request->data['term_of_assessment'], $first_payment_date, $this->request->data['tax_rate'], $formula_data);
        }
        /*         * ***********END*****Payment after re-amortization******END************ */
        $project_cal_pmt = $this->ProjectCalculatePayments->findByProjectId($project['id'])->first();
        if (empty($project_cal_pmt)) {
            $project_cal_pmt = $this->ProjectCalculatePayments->newEntity();
        }
        /*         * ***************Save Project Calculate Payments***************** */
        $project_cal_data['project_id'] = $project['id'];
        $project_cal_data['capitalized_interest'] = $capitalized_interest;
        $project_cal_data['assessment'] = $assessment;
        $project_cal_data['pmt'] = $pmt;
        $project_cal_data['apr'] = $apr;
        $project_cal_data['rate'] = $formula_data['rate'];
        $project_cal_data['annual_administrative_fee'] = $formula_data['annual_administrative_fee'];
        $project_cal_data['first_payment_date'] = Time::parseDate($first_payment_date, Configure::read('Site.CakeDateFormat'));
        $project_cal_data['total'] = $payment_data['data'][0]['total'];
        $project_cal_data['total_net_payment'] = $payment_data['total_net_payment'];
        if (isset($this->request->data['tax_credit']) && $this->request->data['tax_credit'] == 'Yes') {
            $project_cal_data['amount_re_amortization'] = $amount_after_re_amortization;
            $project_cal_data['pmt_re_amortization'] = $pmt_re_amortization;
        } else {
            $project_cal_data['amount_re_amortization'] = 0.00;
            $project_cal_data['pmt_re_amortization'] = 0.00;
        }
        if ($project['status'] <= 2) {
            $this->request->data['status'] = 3;
        }
        $this->request->data['completion_date'] = Time::parseDate($this->request->data['completion_date'], Configure::read('Site.CakeDateFormat'));
        $this->request->data['step'] = 2;
        $project = $this->Projects->patchEntity($project, $this->request->data);

        $this->Projects->save($project);
        $project_cal_pmt = $this->ProjectCalculatePayments->patchEntity($project_cal_pmt, $project_cal_data);
        $this->ProjectCalculatePayments->save($project_cal_pmt);

        $response['status'] = 1;
        $response['message'] = "Success";
        $response['payment_data'] = $payment_data;
        $response['formula_data'] = $formula_data;
        $response['payment_data_re_amortization'] = $payment_data_re_amortization;
        $response['apr'] = $apr;
        //  $response['project'] = $project;
        echo json_encode($response);
        exit;
    }

    /**
     * *******************************************************************
     * ******************  Send Credit Application ***********************
     * *******************************************************************
     */
    function sendCreditApplication() {

        $response = [];
        $response['status'] = 0;
        $response['message'] = [];

        if (!isset($this->request->data['auth_key'])) {
            $response['message'][] = 'Auth key is required!';
        }
        if (!isset($this->request->data['contractor_code'])) {

            $response['message'][] = 'Contractor code is required!';
        }
        if (!isset($this->request->data['salesperson_code'])) {

            $response['message'][] = 'Salesperson code is required!';
        }

        if (!isset($this->request->data['owner_first_name'])) {

            $response['message'][] = 'Owner first name code is required!';
        }

        if (!isset($this->request->data['owner_last_name'])) {

            $response['message'][] = 'Owner last name code is required!';
        }
        if (!isset($this->request->data['email_address'])) {

            $response['message'][] = 'Email address is required!';
        }
        if (!isset($this->request->data['notify_url'])) {

            $response['message'][] = 'Notify URL is required!';
        }elseif(isset($this->request->data['notify_url']) && filter_var($this->request->data['notify_url'], FILTER_VALIDATE_URL) === false){
            $response['message'][] = 'Notify URL not valid!';
        }

        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }

        if (filter_var($this->request->data['email_address'], FILTER_VALIDATE_EMAIL) == false) {

            $response['message'][] = 'Email ID ' . $this->request->data['email_address'] . ' not valid';
            echo json_encode($response);
            exit;
        }
        // ########## check auth key ####################
        $checkAuthKey = $this->checkAuthKey();
        if ($checkAuthKey['status'] == 0) {
            $response['message'] = $checkAuthKey['message'];
            echo json_encode($response);
            exit;
        }

        // ########## //check auth key ####################
        // ############# validation #######################
        // -------------- PROJECT ID ----------------------

        if (!isset($this->request->data['projectId'])) {
            $response['message'][] = 'Project id is required.';
            echo json_encode($response);
            exit;
        }

        $checkProject = $this->Projects->findById($this->request->data['projectId'])->first();

        if (empty($checkProject)) {
            $response['message'][] = 'Invalid Project Id.';
            echo json_encode($response);
            exit;
        }

        // -------------- //PROJECT ID ----------------------
        // ---------------   project  exit or not ------------
        $project = $this->Projects->get($this->request->data['projectId']);
        if (empty($project)) {
            $response['message'][] = 'Invalid project Id.';
            echo json_encode($response);
            exit;
        }


        if ($checkAuthKey['id'] != $project['user_id']) {
            $response['message'][] = "You are not authorized to access project by this salesperson code.";
            echo json_encode($response);
            exit;
        }
        if ($project['deleted'] == 1) {
            $response['message'][] = 'This project is deleted.';
        }

        if ($project['step'] == 0) {
            $response['message'][] = 'Please check address eligibility.';
        }
        if ($project['step'] == 1) {
            $response['message'][] = 'Please calculate payment first.';
        }
        if ($project['status'] > 4) {
            $response['message'][] = 'You have already send credit application.';
        }
        // ------------  //project  exit or not ---------------

        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }
        $this->request->data['user_id'] = $checkAuthKey['id'];
        $this->request->data['owner_name'] = $this->request->data['owner_first_name'] . ' ' . $this->request->data['owner_last_name'];
        $this->request->data['access_key'] = md5(uniqid());
        $url1 = Router::url(['controller' => 'Projects', 'action' => 'application', $this->request->data['access_key']], true);
        $url = "<a href='$url1'>HERE</a>";
        $url_full = "<a href='$url1'>" . $url1 . "</a>";
        $mail = $this->SystemMails->findByEmailType('CreditAppSend')->first();
        $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
        $mail['message'] = str_replace('[customer_name]', $this->request->data['owner_name'], $mail['message']);
        $mail['message'] = str_replace('[click_here]', $url, $mail['message']);
        $mail['message'] = str_replace('[credit_app_url]', $url_full, $mail['message']);
        $mail['message'] = str_replace('[salesperson_email]', $this->Auth->user('email'), $mail['message']);
        $mail['to'] = $this->request->data['email_address'];
        if ($this->sendEmail($mail)) {
            $this->request->data['status'] = 4;
            $this->request->data['step'] = 3;
            $project = $this->Projects->patchEntity($project, $this->request->data);
            $this->Projects->save($project);
            $json = array('status' => 1, 'message' => 'Credit application sent.');
        } else {
            $json = array('status' => 0, 'message' => 'Credit application not send, Please try again.');
        }
        echo json_encode($json);
        exit;
    }

//   ---------------------------  postApplication  ---------------------------------
    function postApplication() {
        $response = array();
        $response['status'] = 0;
        $response['message'] = [];

        $customerDetailArray = [];
        if (!isset($this->request->data['auth_key'])) {
            $response['message'][] = 'Auth key is required';
        }
        if (!isset($this->request->data['contractor_code'])) {
            $response['message'][] = 'Contractor code is required';
        }
        if (!isset($this->request->data['salesperson_code'])) {
            $response['message'][] = 'Salesperson code is required';
        }
        if (!isset($this->request->data['projectId'])) {
            $response['message'][] = 'Project Id is required';
        }

        if (!isset($this->request->data['property_type'])) {
            $response['message'][] = 'Property type is required';
        } else {
            $customerDetailArray['property_type'] = $this->request->data['property_type'];
        }

        if (!isset($this->request->data['property_ownership'])) {
            $response['message'][] = 'Property ownership is required';
        } else {
            $customerDetailArray['property_ownership'] = $this->request->data['property_ownership'];
        }
        if (!isset($this->request->data['notify_url'])) {

            $response['message'][] = 'Notify URL is required!';
        }elseif(isset($this->request->data['notify_url']) && filter_var($this->request->data['notify_url'], FILTER_VALIDATE_URL) === false){
            $response['message'][] = 'Notify URL not valid!';
        }else{
            $project_data['notify_url']=$this->request->data['notify_url'];
        }

        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }
        $checkAuthKey = $this->checkAuthKey();
        if ($checkAuthKey['status'] == 0) {
            $response['message'][] = $checkAuthKey['message'];
            echo json_encode($response);
            exit;
        }

        $checkProject = $this->Projects->findById($this->request->data['projectId'])->first();

        if (empty($checkProject)) {
            $response['message'][] = 'Invalid Project Id.';
            echo json_encode($response);
            exit;
        }
        $project = $this->Projects->findById($this->request->data['projectId'])->first();
        if (empty($project)) {
            $response['message'][] = 'Invalid Project Id.';
            echo json_encode($response);
            exit;
        } elseif ($project['status'] == 5) {
            $response['message'][] = 'You have already post customer property information.';
            echo json_encode($response);
            exit;
        }

        if (trim($this->request->data['property_type']) == "") {
            $response['message'][] = 'Property type is required';
        }
        if ($checkAuthKey['id'] != $project['user_id']) {
            $response['message'][] = "You are not authorized to access project by this salesperson code.";
            echo json_encode($response);
            exit;
        }
        if ($project['deleted'] == 1) {
            $response['message'][] = 'This project is deleted.';
        }

        if ($project['step'] == 0) {
            $response['message'][] = 'Please check address eligibility.';
        }
        if ($project['step'] == 1) {
            $response['message'][] = 'Please calculate payment first.';
        }
        if ($project['status'] > 5) {
            $response['message'][] = 'You have already post customer property information.';
        }
        if (trim($this->request->data['property_ownership']) == "") {
            $response['message'][] = 'Property ownership is required.';
        }

        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }

        if ($this->request->data['property_ownership'] == 'Trust') {
            if (isset($this->request->data['property_ownership_name'])) {
//                $this->request->data['trust_name'] = $this->request->data['property_ownership_name'];
                $customerDetailArray['trust_name'] = $this->request->data['property_ownership_name']; //new ----
            } else {
                $response['message'][] = 'Property ownership name required.';
                echo json_encode($response);
                exit;
            }
        }
        if ($this->request->data['property_ownership'] == 'Corporation or LLC') {
            if (isset($this->request->data['property_ownership_name'])) {
//                $this->request->data['corporation_llc_name'] = $this->request->data['property_ownership_name'];
                $customerDetailArray['corporation_llc_name'] = $this->request->data['property_ownership_name']; //new ----
            } else {
                $response['message'][] = 'Property ownership name required.';
                echo json_encode($response);
                exit;
            }
        }
        if ($this->request->data['property_ownership'] == 'Other') {
            if (isset($this->request->data['property_ownership_name'])) {
//                $this->request->data['other_name'] = $this->request->data['property_ownership_name'];
                $customerDetailArray['other_name'] = $this->request->data['property_ownership_name']; //new ----
            } else {
                $response['message'][] = 'Property ownership name required.';
                echo json_encode($response);
                exit;
            }
        }



        if (!isset($this->request->data['fo_first_name'])) {
            $response['message'][] = 'First property owner first name is required.';
        } else {
            $customerDetailArray['fo_first_name'] = $this->request->data['fo_first_name']; //new ----
        }

        if (!isset($this->request->data['fo_last_name'])) {
            $response['message'][] = 'First property owner last name is required.';
        } else {
            $customerDetailArray['fo_last_name'] = $this->request->data['fo_last_name']; //new ----
        }

        if (!isset($this->request->data['fo_ssn'])) {
            $response['message'][] = 'First property owner SSN is required.';
        }
        if (isset($this->request->data['fo_suffix'])) {
            $customerDetailArray['fo_suffix'] = $this->request->data['fo_suffix']; //new ----
        }
        if (!isset($this->request->data['fo_dob'])) {
            $response['message'][] = 'First property owner dob is required.';
        }
        if (!isset($this->request->data['fo_email'])) {
            $response['message'][] = 'First property owner email is required.';
        } else {
            $customerDetailArray['fo_email'] = $this->request->data['fo_email']; //new ----
        }
        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }
        if (trim($this->request->data['fo_first_name']) == "") {
            $response['message'][] = 'First property owner first name is required.';
        }

        if (trim($this->request->data['fo_last_name']) == "") {
            $response['message'][] = 'First property owner last name is required.';
        }
        if (filter_var($this->request->data['fo_email'], FILTER_VALIDATE_EMAIL) == false) {
            $response['message'][] = 'First property owner email not valid';
            echo json_encode($response);
            exit;
        }

        if ($this->checkdateFormat($this->request->data['fo_dob']) != true) {
            $response['message'][] = 'First Property Owner dob  not valid.';
        }

        $today = date("y-m-d");
        if (strtotime($today) <= strtotime(date("y-m-d", strtotime($this->request->data['fo_dob'])))) {
            $response['message'][] = 'First property owner dob be greater than current date.';
        }

        if (isset($this->request->data['fo_ssn'])) {
            if ($this->validateSSN($this->request->data['fo_ssn']) == 0) {
                $response['message'][] = 'First property owner SSN ' . $this->request->data['fo_ssn'] . ' not valid.';
            }
        }
        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }


        if (isset($this->request->data['fo_same_as']) && $this->request->data['fo_same_as'] == 1) {
            $customerDetailArray['fo_address'] = $project['address'];
            $customerDetailArray['fo_state'] = $project['state'];
            $customerDetailArray['fo_city'] = $project['city'];
            $customerDetailArray['fo_zipcode'] = $project['zipcode'];
            $customerDetailArray['fo_unit'] = $project['unit'];
        } else {
            if (!isset($this->request->data['fo_address'])) {
                $response['message'][] = 'First property owner address is required.';
            } else {
                $customerDetailArray['fo_address'] = $this->request->data['fo_address'];
            }
            if (!isset($this->request->data['fo_state'])) {
                $response['message'][] = 'First property owner state is required.';
            } else {
                $customerDetailArray['fo_state'] = $this->request->data['fo_state'];
            }
            if (!isset($this->request->data['fo_city'])) {
                $response['message'][] = 'First property owner city is required.';
            } else {
                $customerDetailArray['fo_city'] = $this->request->data['fo_city'];
            }
            if (!isset($this->request->data['fo_zipcode'])) {
                $response['message'][] = 'First property owner zipcode is required.';
            } else {
                $customerDetailArray['fo_zipcode'] = $this->request->data['fo_zipcode'];
            }

            if (isset($this->request->data['fo_unit'])) {
                $customerDetailArray['fo_unit'] = $this->request->data['fo_unit'];
            }
        }
        /*  -- second property owner */

        if ($this->request->data['property_ownership'] == 'Joint') {

            if (!isset($this->request->data['so_first_name'])) {
                $response['message'][] = 'Second property owner first name is required.';
            } else {
                $customerDetailArray['so_first_name'] = $this->request->data['so_first_name'];
            }

            if (!isset($this->request->data['so_last_name'])) {
                $response['message'][] = 'Second property owner last name is required.';
            } else {
                $customerDetailArray['so_last_name'] = $this->request->data['so_last_name'];
            }
            if (!isset($this->request->data['so_ssn'])) {
                $response['message'][] = 'Second property owner SSN is required.';
            }
            if (!isset($this->request->data['so_dob'])) {
                $response['message'][] = 'Second property owner dob is required.';
            }
            if (!isset($this->request->data['so_email'])) {
                $response['message'][] = 'Second property owner email is required.';
            } else {
                $customerDetailArray['so_email'] = $this->request->data['so_email'];
            }
            if (isset($this->request->data['so_dob'])) {
                if ($this->checkdateFormat($this->request->data['so_dob']) != true) {
                    $response['message'][] = 'Second Property Owner dob  not valid.';
                }
            }
            if (isset($this->request->data['so_dob'])) {
                if (strtotime($today) <= strtotime(date("y-m-d", strtotime($this->request->data['so_dob'])))) {
                    $response['message'][] = 'Second property owner dob be greater than current date.';
                }
            }

            if (!empty($response['message'])) {
                echo json_encode($response);
                exit;
            }

            if (isset($this->request->data['so_same_as']) && $this->request->data['so_same_as'] == 1) {
                $customerDetailArray['so_address'] = $project['address'];
                $customerDetailArray['so_state'] = $project['state'];
                $customerDetailArray['so_city'] = $project['city'];
                $customerDetailArray['so_zipcode'] = $project['zipcode'];
                $customerDetailArray['so_unit'] = $project['unit'];
            } else {

                if (!isset($this->request->data['so_address'])) {
                    $response['message'][] = 'Second Property Owner address is required.';
                } else {
                    $customerDetailArray['so_address'] = $this->request->data['so_address'];
                }
                if (!isset($this->request->data['so_state'])) {
                    $response['message'][] = 'Second Property Owner state is required.';
                } else {
                    $customerDetailArray['so_state'] = $this->request->data['so_state'];
                }
                if (!isset($this->request->data['so_city'])) {
                    $response['message'][] = 'Second Property Owner city is required.';
                } else {
                    $customerDetailArray['so_city'] = $this->request->data['so_city'];
                }
                if (!isset($this->request->data['so_zipcode'])) {
                    $response['message'][] = 'Second Property Owner zipcode is required.';
                } else {
                    $customerDetailArray['so_zipcode'] = $this->request->data['so_city'];
                }
                if (isset($this->request->data['so_unit'])) {
                    $customerDetailArray['so_unit'] = $this->request->data['so_unit'];
                }
                if (!empty($response['message'])) {
                    echo json_encode($response);
                    exit;
                }
            }


            if (trim($this->request->data['so_first_name']) == "") {
                $response['message'][] = 'Second property owner first name is required.';
            }

            if (trim($this->request->data['so_last_name']) == "") {
                $response['message'][] = 'Second property owner last name is required.';
            }
            if (filter_var($this->request->data['so_email'], FILTER_VALIDATE_EMAIL) == false) {
                $response['message'][] = 'Second property owner email not valid';
                echo json_encode($response);
                exit;
            }
            if (isset($this->request->data['so_ssn'])) {
                if ($this->validateSSN($this->request->data['so_ssn']) == 0) {
                    $response['message'][] = 'Second property owner SSN ' . $this->request->data['so_ssn'] . ' not valid.';
                }
            }

            if (isset($this->request->data['so_dob']) && $this->request->data['so_dob'] != '') {
                $customerDetailArray['so_dob'] = $this->Common->encrypt($this->request->data['so_dob']);
            }
            if (isset($this->request->data['so_ssn']) && $this->request->data['so_ssn'] != '') {
                $customerDetailArray['so_ssn'] = $this->Common->encrypt($this->request->data['so_ssn']);
            }
        }

        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }

        $customerDetailArray['fo_ssn'] = $this->Common->encrypt($this->request->data['fo_ssn']);
        $customerDetailArray['fo_dob'] = $this->Common->encrypt($this->request->data['fo_dob']);
        $customerDetailArray['project_id'] = $this->request->data['projectId'];


        $customerDetail = $this->CustomerDetails->newEntity();
        $customerDetail = $this->CustomerDetails->patchEntity($customerDetail, $customerDetailArray);

        if ($this->CustomerDetails->save($customerDetail)) {

            $project_data['status'] = 5;
            //$project_data['step'] = 4;
            $project = $this->Projects->patchEntity($project, $project_data);
            $this->Projects->save($project);
            unset($customerDetail['auth_key'], $customerDetail['fo_ssn'], $customerDetail['contractor_code'], $customerDetail['salesperson_code'], $customerDetail['fo_dob'], $customerDetail['so_ssn'], $customerDetail['so_dob'], $customerDetail['project_id'], $customerDetail['id']);
            $response['message'] = 'The application successfully  saved.';
            $response['status'] = 1;
            $response['customerDetail'] = $customerDetail;

            echo json_encode($response);
            exit;
        } else {
            $response['message'] = 'The application could not be saved.';
            echo json_encode($response);
            exit;
        }
    }

    function checkProjectStatus() {
        $response = array();
        $response['status'] = 0;
        $response['message'] = [];



        if (!isset($this->request->data['auth_key'])) {
            $response['message'][] = 'Auth key is required';
        }
        if (!isset($this->request->data['contractor_code'])) {
            $response['message'][] = 'Contractor code is required';
        }
        if (!isset($this->request->data['salesperson_code'])) {
            $response['message'][] = 'Salesperson code is required';
        }

        if (!isset($this->request->data['projectId'])) {
            $response['message'][] = 'Project id is required.';
        }

        // ########## check auth key ####################
        $checkAuthKey = $this->checkAuthKey();
        if ($checkAuthKey['status'] == 0) {
            $response['message'] = $checkAuthKey['message'];
        }

        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }

        // -------------- //PROJECT ID ----------------------
        // ---------------   project  exit or not ------------


        $checkProject = $this->Projects->findById($this->request->data['projectId'])->first();
        if (empty($checkProject)) {
            $response['message'] = 'Invalid Project Id.';
            echo json_encode($response);
            exit;
        }

        $project = $this->Projects->get($this->request->data['projectId']);

        if (empty($project)) {
            $response['message'] = 'Invalid Project Id.';
            echo json_encode($response);
            exit;
        }
        if ($checkAuthKey['id'] != $project['user_id']) {
            $response['message'][] = "You are not authorized to access project by this salesperson code.";
            echo json_encode($response);
            exit;
        }
        if ($project['deleted'] == 1) {
            $response['message'][] = 'This project is deleted.';
        }

        // ------------  //project  exit or not ---------------
        $json = array('status' => 1, 'message' => 'Success.', 'projectCurrentStatus' => $project['status'], 'label' => $this->getStatusTitle($project['status']), 'projectId' => $project['id']);
        echo json_encode($json);
        exit;
    }

    function getStatusTitle($status) {
        switch ($status) {
            case 1:
                return 'Address Eligible';
                break;
            case 2:
                return 'Address Ineligible';
                break;
            case 3:
                return 'Payment Calculated';
                break;
            case 4:
                return 'Credit App Sent';
                break;
            case 5:
                return 'Credit App Received';
                break;
            case 6:
                return 'Credit Approved';
                break;
            case 7:
                return 'Credit Declined';
                break;
            case 8:
                return 'Credit Manual';
                break;
            case 9:
                return 'Contract Sent';
                break;
            case 10:
                return 'Contract Signed By Customer';
                break;
            case 11:
                return 'Contract Signed By JPA';
                break;
            case 12:
                return 'Contract Declined By Customer';
                break;
            case 13:
                return 'OK to Proceed';
                break;
            case 14:
                return 'Terminated by Customer';
                break;
            case 15:
                return 'Certificate of Completion Sent';
                break;
            case 16:
                return 'Completed';
                break;
            default:
                break;
        }
    }

    function sendContract() {

        $response = array();
        $response['status'] = 0;
        $response['message'] = [];

        // ############   Check auth key ###############

        if (!isset($this->request->data['auth_key'])) {
            $response['message'][] = 'Auth key is required';
        }
        if (!isset($this->request->data['contractor_code'])) {
            $response['message'][] = 'Contractor code is required';
        }
        if (!isset($this->request->data['salesperson_code'])) {
            $response['message'][] = 'Salesperson code is required';
        }

        if (!isset($this->request->data['projectId'])) {
            $response['message'][] = 'Project id is required.';
        }

        if (!isset($this->request->data['term_of_assessment'])) {
            $response['message'][] = 'Term of assessment is required.';
        }

        if (!isset($this->request->data['type'])) {
            $response['message'][] = 'Type is required.';
        }


        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }

        $checkAuthKey = $this->checkAuthKey();
        if ($checkAuthKey['status'] == 0) {
            $response['message'][] = $checkAuthKey['message'];
            echo json_encode($response);
            exit;
        }

        if (!is_numeric($this->request->data['projectId'])) {
            $response['message'][] = 'Project Id is not valid.';
        }
        if (trim($this->request->data['type']) == "") {
            $response['message'][] = 'Type is not valid.';
        }


        if (!is_numeric($this->request->data['term_of_assessment']) || $this->request->data['term_of_assessment'] > 25) {
            $response['message'][] = 'Term of assessment is invalid.';
        }


        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }

        if (!in_array($this->request->data['term_of_assessment'], $this->currentTerms())) {
            $response['message'][] = 'Term of assessment not valid.';
        }

        $project = $checkProject = $this->Projects->findById($this->request->data['projectId'])->first();

        if (empty($checkProject)) {
            $response['message'][] = 'Invalid Project Id.';
        }

        if ($checkAuthKey['id'] != $project['user_id']) {
            $response['message'][] = "You are not authorized to access project by this salesperson code.";
        }
        if ($project['deleted'] == 1) {
            $response['message'][] = 'This project is deleted.';
        }

        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }

        if ($project['step'] == 0) {
            $response['message'][] = 'Please check address eligibility.';
        }

        if ($project['status'] < 5) {
            $response['message'][] = 'Please post appliction first.';
        }



        if ($this->request->data['type'] == 'N') {

            if ($project['status'] > 6) {
                $response['message'][] = 'Contract already sent.';
            }

            $contract = $this->ContractDetails->findByProjectIdAndStatus($this->request->data['projectId'], 1)->first();
            if (!empty($contract)) {
                $response['message'][] = 'Contract already sent.';
                echo json_encode($response);
                exit;
            }
        }

        if ($this->request->data['type'] == 'E') {

            if ($project['status'] != 9) {
                $response['message'][] = 'No contract found for edit. Please create new contract.';
                echo json_encode($response);
                exit;
            }

        }


        if (!isset($this->request->data['manufacturer'])) {
            $response['message'][] = 'Equipment Information is required.';
        }

        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }

        if (count(array_filter($this->request->data['manufacturer'])) == 0) {
            $response['message'][] = 'Equipment Information is required.';
            echo json_encode($response);
            exit;
        }

        if (!empty($this->request->data['manufacturer'])) {
            $maxCost = 0;
            $project_typeid = 0;
            $termNew = 0;
            $projecttypename = '';
            $total_cost = 0;

           
            foreach ($this->request->data['manufacturer'] as $key => $manufacturer) {


                if (!empty($manufacturer)) {

                    if (!isset($this->request->data['project_type_id'][$key])) {
                        $response['message'][] = 'Project type is required.';
                    }

                    if (!isset($this->request->data['model'][$key])) {

                        $response['message'][] = 'Model is required.';
                    }
                    if (!isset($this->request->data['sku'][$key])) {
                        $response['message'][] = 'SKU is required.';
                    }
                    if (!isset($this->request->data['quantity'][$key])) {
                        $response['message'][] = 'Quantity is required.';
                    }
                    if (!isset($this->request->data['cost'][$key])) {
                        $response['message'][] = 'Cost is required.';
                    }
                    if (!empty($response['message'])) {
                        echo json_encode($response);
                        exit;
                    }


                    if (($this->request->data['project_type_id'][$key]) == "") {

                        $response['message'][] = 'Project type is required.';
                    }
                    if (($this->request->data['model'][$key]) == "") {
                        $response['message'][] = 'Model is required.';
                    }
                    if (($this->request->data['sku'][$key]) == "") {
                        $response['message'][] = 'SKU is required.';
                    }
                    if (($this->request->data['quantity'][$key]) == "") {
                        $response['message'][] = 'Quantity is required.';
                    }
                    if (($this->request->data['cost'][$key]) == "") {
                        $response['message'][] = 'Cost is required.';
                    }
                    if (!empty($response['message'])) {
                        echo json_encode($response);
                        exit;
                    }

                    $checkProjectType = $this->ProjectTypes->find()->contain('LoanTerms.Terms')->where(['name' => $this->request->data['project_type_id'][$key]])->select('id')->first();


                    if (empty($checkProjectType)) {
                        $response['message'][] = 'Project Types ' . $this->request->data['project_type_id'][$key] . ' not found.';
                    }
                    if (!is_numeric($this->request->data['quantity'][$key])) {
                        $response['message'][] = 'Quantity ' . $this->request->data['quantity'][$key] . ' not numeric.';
                    }

                    if (!is_numeric($this->request->data['cost'][$key])) {
                        $response['message'][] = 'Cost ' . $this->request->data['cost'][$key] . ' not numeric.';
                    }
                    if (!empty($response['message'])) {
                        echo json_encode($response);
                        exit;
                    }

                    if (count($this->request->data['manufacturer']) > 1) {

                        if ($maxCost < $this->request->data['cost'][$key]) {
                            $project_typeid = $checkProjectType['id'];
                            $termNew = $checkProjectType['loan_terms'][0]['term']['term'];
                            $maxCost = $this->request->data['cost'][$key];
                            $projecttypename = $this->request->data['project_type_id'][$key];
                        }
                    }
                    $total_cost = $total_cost + $this->request->data['cost'][$key];
                }
            }
            if ($total_cost > $project['expected_amount']) {

                $response['message'][] = 'Cost must be less then expected amount ' . $project['expected_amount']; // need to changes message
                echo json_encode($response);
                exit;
            }


            if (count($this->request->data['manufacturer']) > 1) {

                if ($this->request->data['term_of_assessment'] < $termNew) {

                    $response['message'][] = 'Maximum term for ' . $projecttypename . ' is ' . $this->request->data['term_of_assessment'] . '  years. Please choose another term.'; // need to changes message
                    echo json_encode($response);
                    exit;
                }
            }

            /*********************Check*Docisign*Working**************************/
             $docusignConfig=$this->getDocusignDetailByAuthKay($this->request->data['auth_key']);
             $this->loadComponent('Docusign');
             try {
                 $this->Docusign->login($docusignConfig);
                 $docusignError=0;
             } catch (\Exception $exc) {
                 $docusignError=1;
             }
             if ($docusignError) {
                $response['message'][] = 'Docusign credential not working. Please contact our support team.'; // need to changes message
                echo json_encode($response);
                exit;
             }
            
            $this->request->data['total_cost'] = $this->request->data['project_amount'] = $total_cost;
            if ($this->request->data['type'] != 'C') {
                $this->request->data['status'] = 9;
                $this->request->data['step'] = 4;
            }

            $project = $this->Projects->patchEntity($project, $this->request->data);
            /*             * ************Create*Contract******************* */
            /*             * ***********Start*payment*Calculations************************** */
             $this->ContractDetails->updateAll(['status' => 0], ['project_id' => $project['id']]);
            $formula_data = $this->Common->getPaymentFormulaInfo($this->request->data['term_of_assessment']);
            if ($this->request->data['type'] == 'C') {
                $past30daysDate = date('Y-m-d', strtotime('-30 days'));
                $new30daysDate = date('Y-m-d', strtotime('+30 days'));
                $completion_date = date('Y-m-d', strtotime($project['completion_date']));
                if ($past30daysDate != $completion_date || $new30daysDate != $completion_date) {
                    $completion_date = date('Y-m-d');
                }
            } else {
                $completion_date = date('Y-m-d', strtotime($project['completion_date']));
            }

           
            
            $current_date = date('Y-m-d');
            $june_date = date('Y-06-23');
            $june_date_for_maturity = date('Y-06-23');

            if ($completion_date > $current_date && $completion_date < $june_date) {
                $first_payment_date = date('Y-09-02');
            } else {
                $first_payment_date = date('Y-09-02', strtotime('+1 year'));
            }
            $date = new Date(date('Y-09-02'));
            if ($completion_date > $current_date && $completion_date < $june_date_for_maturity) {
                $date->modify('+' . $this->request->data['term_of_assessment'] . ' year');
            } else {
                $date->modify('+' . ($this->request->data['term_of_assessment'] + 1) . ' year');
            }
            $maturity_date = $date->format('Y-m-d');

            $assessment_data = $this->Common->getAssessment($this->request->data['total_cost'], $first_payment_date, $completion_date, $formula_data);
            $assessment = $assessment_data['assessment'];
            $assessment_1 = $assessment_data['assessment_1'];
            $capitalized_interest = $assessment_data['capitalized_interest'];
            $origination_fee_total = $assessment_data['origination_fee_total'];
            $fin = new Financial();
            $pmt = $fin->PMT(($formula_data['rate'] / 100), $this->request->data['term_of_assessment'], -$assessment);
            $pmt = round($pmt, 2);
            $payment_data = $this->Common->getPaymentTableData($assessment, $pmt, $this->request->data['term_of_assessment'], $first_payment_date, $project['tax_rate'], $formula_data);
            $pmt_all_data = Hash::extract($payment_data['data'], '{n}.total');

            $assessment_with_interest = round($assessment_1 + $capitalized_interest, 2);
            $apr = $fin->apr($assessment_with_interest, $pmt_all_data);
            /*             * ***********End*payment*Calculations************************** */
            $contractDetail = $this->ContractDetails->newEntity();
            $contract_data['project_id'] = $project['id'];
            $contract_data['capitalized_interest'] = $capitalized_interest;
            $contract_data['origination_fee_total'] = $origination_fee_total;
            $contract_data['assessment'] = $assessment;
            $contract_data['maturity_date'] = Time::parseDate($maturity_date, Configure::read('Site.CakeDateFormat'));
            $contract_data['pmt'] = $pmt;
            $contract_data['apr'] = $apr;
            $contract_data['term_of_assessment'] = $this->request->data['term_of_assessment'];
            $contract_data['rate'] = $formula_data['rate'];
            $contract_data['total'] = $payment_data['data'][0]['total'];
            $contract_data['total_net_payment'] = $payment_data['total_net_payment'];
            $contract_key = $contract_data['access_key'] = md5(uniqid());
            $contract_data['application_date'] = Time::parseDate(date("Y-m-d"), Configure::read('Site.CakeDateFormat'));
            $contractDetail = $this->ContractDetails->patchEntity($contractDetail, $contract_data);

            $contract_res = $this->ContractDetails->save($contractDetail);

            foreach ($this->request->data['manufacturer'] as $key => $manufacturer) {
                $checkProjectType = array();
                $checkProjectType = $this->ProjectTypes->find()->where(['name' => $this->request->data['project_type_id'][$key]])->select('id')->first();


                if (!empty($manufacturer)) {

                    $eq_info = $this->EquipmentInformations->newEntity();
                    $eq_info_data['project_id'] = $this->request->data['projectId'];
                    $eq_info_data['project_type_id'] = $checkProjectType['id'];
                    ;
                    $eq_info_data['contract_detail_id'] = $contract_res['id'];
                    $eq_info_data['manufacturer'] = $manufacturer;
                    $eq_info_data['model'] = $this->request->data['model'][$key];
                    $eq_info_data['sku'] = $this->request->data['sku'][$key];
                    $eq_info_data['qty'] = $this->request->data['quantity'][$key];
                    $eq_info_data['amount'] = $this->request->data['cost'][$key];
                    $eq_info = $this->EquipmentInformations->patchEntity($eq_info, $eq_info_data);

                    $asda = $this->EquipmentInformations->save($eq_info);
                }
            }
           //$channelPartnerDocusign = $this->getDocusignDetailByAuthKay($this->request->data['auth_key']);
            if ($this->Projects->save($project)) {

                if ($this->request->data['type'] == 'C') {

                    $doc_res = $this->sendCertificate($this->request->data['projectId'], 1,$docusignConfig);
                    $doc_res['error'] = 0;
                    if ($doc_res['error'] == 0) {
                        $response['status'] = 1;
                        $response['envelope_id'] = $doc_res['envelope_id'];
                        $response['message'][] = 'Certificate for e-signature sent.';
                        echo json_encode($response);
                        exit;
                    } else {
                        $response['status'] = 0;
                        $response['message'][] = 'Certificate not send, Please try again.';
                        echo json_encode($response);
                        exit;
                    }
                } else {
                    $doc_res = $this->sendContractToCustomer($this->request->data['projectId'],$docusignConfig);
                    $doc_res['error'] = 0;
                    if ($doc_res['error'] == 0) {
                        $response['status'] = 1;
                        $response['envelope_id'] = $doc_res['envelope_id'];
                        $response['message'][] = 'Contract for e-signature sent.';
                        echo json_encode($response);
                        exit;
                    } else {
                        $response['status'] = 0;
                        $response['message'][] = 'Contract not send, Please try again.';
                        echo json_encode($response);
                        exit;
                    }
                }
            }
        } else {
            $json = array('error' => 1, 'msg' => 'Contract not send, Please try again.');
        }
        echo json_encode($json);
    }

    protected function sendContractToCustomer($id,$docusignConfig=array()) {
        $this->render(false);
        $projectFilePath = Configure::read('Site.ProjectsFilePath');
        $project = $this->Projects->find('all', ['contain' => ['ContractDetails.EquipmentInformations.ProjectTypes', 'ProjectCalculatePayments', 'CustomerDetails'], 'conditions' => ['ContractDetails.status' => 1, 'ContractDetails.change_status' => 0, 'Projects.id' => $id]])->first();
        $formula_data = $this->Common->getPaymentFormulaInfo($project['contract_detail']['term_of_assessment']);
        $totalFees = ($formula_data['origination_fee'] / 100) + $formula_data['lien_recording_fee'] + $formula_data['loan_loss_reserve'] + $formula_data['foreclosure_expense_reserve'] + $formula_data['annual_administrative_fee'];
        $first_payment_date = date('Y-m-d', strtotime($project['project_calculate_payment']['first_payment_date']));
        $pmt = $project['contract_detail']['pmt'];
        $payment_data = $this->Common->getPaymentTableData($project['contract_detail']['assessment'], $pmt, $project['contract_detail']['term_of_assessment'], $first_payment_date, $project['tax_rate'], $formula_data);

        $TotalInterest = array_sum(array_column($payment_data['data'], 'interest'));
        $this->loadComponent('Docusign');
        $config = $this->Docusign->login($docusignConfig);
        $contract_res = $project['contract_detail'];
        $contract_key = $contract_res['access_key'];
        $application_id = $project['id'];
        /* Set Variables */
        $identificationCode = rand(100000, 999999);
        $ProgramOrigination = ($project['project_amount'] * $formula_data['origination_fee']) / 100;
        $this->set('owner1', $project['customer_detail']['fo_first_name'] . ' ' . $project['customer_detail']['fo_last_name']);
        $this->set('country', $project['county']); //this is county
        $this->set('identificationCode', $identificationCode);
        $this->set('JPASignerName', Configure::read('Site.JpaAuthorityName'));
        $this->set('AddressProperty', $project['address']);
        $this->set('CityProperty', $project['city']);
        $this->set('StateProperty', $project['state']);
        $this->set('ZipProperty', $project['zipcode']);
        $this->set('ParcelNumber', $project['apn']); //this is apn
        $this->set('LegalDesc', $project['legal_description']);
        $this->set('AddressMailing', $project['customer_detail']['fo_address']);
        $this->set('CityMailing', $project['customer_detail']['fo_city']);
        $this->set('StateMailing', $project['customer_detail']['fo_state']);
        $this->set('ZIPMailing', $project['customer_detail']['fo_zipcode']);
        $this->set('Assessment', $project['contract_detail']['assessment']);
        $this->set('TotalFees', round($totalFees, 2));
        $this->set('CapInterest', $project['contract_detail']['capitalized_interest']);
        $this->set('TotalProjectCost', $project['project_amount']);
        $this->set('Rate', $project['contract_detail']['rate']);
        $this->set('term', $project['contract_detail']['term_of_assessment']);
        $this->set('MaturityDate', date('F d, Y', strtotime($project['contract_detail']['maturity_date'])));
        $this->set('APR', $project['contract_detail']['apr']);
        $this->set('AnnualPayment', $project['contract_detail']['pmt']);
        $this->set('AnnualAdminFee', $formula_data['annual_administrative_fee']);
        $this->set('TotalAnnualPayment', $project['contract_detail']['total']);
        $this->set('EmailOwner1', $project['customer_detail']['fo_email']);
        $this->set('Municipality', $project['municipality']);
        $this->set('todaysDate', date("M d, Y"));
        $this->set('ExpirationDate', date("M d, Y", strtotime('+120 days')));
        $this->set('ExpectedCompletionDate', date("M d, Y", strtotime($project['completion_date'])));
        $this->set('project', $project);
        $this->set('payment_data', $payment_data);
        $this->set('PropertyValuation', $project['avm']); //this is avm
        $this->set('ApplicationID', $application_id);
        $this->set('UpfrontCosts', ($project['contract_detail']['assessment'] - $project['project_amount']));
        $this->set('TotalAssessmentObligation', (count($payment_data['data']) * $payment_data['data'][0]['total']));
        $this->set('TotalInterest', $TotalInterest);
        $this->set('formula_data', $formula_data);
        $this->set('ProgramOrigination', $project['contract_detail']['origination_fee_total']);

        if ($project['customer_detail']['so_first_name'] != '') {
            $this->set('owner2', $project['customer_detail']['so_first_name'] . ' ' . $project['customer_detail']['so_last_name']);
        }
        $this->set('AuthorizedSignatoryName', Configure::read('Site.AuthorizedSignatoryName'));
        $authorizedSignatoryEmail = Configure::read('Site.AuthorizedSignatoryEmail');
        $authorizedSignatoryName = Configure::read('Site.AuthorizedSignatoryName');
        $operationEmail = Configure::read('Site.OperationEmail');
        $operationName = Configure::read('Site.OperationName');
        /*         * ******************Start*Electronic Consent Form****************************** */
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
        $signature[0]['user']['email'] = $project['customer_detail']['fo_email'];
        $signature[0]['user']['name'] = $project['customer_detail']['fo_first_name'] . ' ' . $project['customer_detail']['fo_last_name'];
        $signature[0]['user']['receiptId'] = "1";
        $signature[0]['user']['order'] = 1;


        $signature[0]['initialSign'][0]['xpos'] = "200";
        $signature[0]['initialSign'][0]['ypos'] = "720";
        $signature[0]['initialSign'][0]['pageNumber'] = "1";
        $signature[0]['initialSign'][0]['receiptId'] = "1";
        $signature[0]['initialSign'][0]['documentId'] = "1";
        if ($project['customer_detail']['so_first_name'] != '') {
            $signature[1]['user']['email'] = $project['customer_detail']['so_email'];
            $signature[1]['user']['name'] = $project['customer_detail']['so_first_name'] . ' ' . $project['customer_detail']['so_last_name'];
            $signature[1]['user']['receiptId'] = "2";
            $signature[1]['user']['order'] = 1;

            $signature[1]['initialSign'][0]['xpos'] = "420";
            $signature[1]['initialSign'][0]['ypos'] = "720";
            $signature[1]['initialSign'][0]['pageNumber'] = "1";
            $signature[1]['initialSign'][0]['receiptId'] = "2";
            $signature[1]['initialSign'][0]['documentId'] = "1";
        }
        /*         * ******************End*Electronic Consent Form****************************** */

        /*         * ******************Start*Application Disclosures***************************** */
        $CakePdf = new \CakePdf\Pdf\CakePdf();
        $CakePdf->template('application_disclosures', 'default');
        $CakePdf->viewVars($this->viewVars);
        $pdf = $CakePdf->output();
        $name = 'Application-Disclosures';
        $pdf_path2 = $projectFilePath . $id . DS . $name . '.pdf';
        $pdf = $CakePdf->write($pdf_path2);
        $lastPage = $CakePdf->getPageCount();
        $signature[0]['sign'][0]['pageNumber'] = $lastPage;
        $signature[0]['sign'][0]['receiptId'] = "1";
        $signature[0]['sign'][0]['documentId'] = "2";
        $signature[0]['sign'][0]['xpos'] = "100";
        $signature[0]['sign'][0]['ypos'] = "250";

        $signature[0]['dateSign'][0]['xpos'] = "345";
        $signature[0]['dateSign'][0]['ypos'] = "240";
        $signature[0]['dateSign'][0]['pageNumber'] = $lastPage;
        $signature[0]['dateSign'][0]['receiptId'] = "1";
        $signature[0]['dateSign'][0]['documentId'] = "2";
        if ($project['customer_detail']['so_first_name'] != '') {
            $signature[1]['sign'][0]['pageNumber'] = $lastPage;
            $signature[1]['sign'][0]['receiptId'] = "2";
            $signature[1]['sign'][0]['documentId'] = "2";
            $signature[1]['sign'][0]['xpos'] = "100";
            $signature[1]['sign'][0]['ypos'] = "345";

            $signature[1]['dateSign'][0]['xpos'] = "345";
            $signature[1]['dateSign'][0]['ypos'] = "340";
            $signature[1]['dateSign'][0]['pageNumber'] = $lastPage;
            $signature[1]['dateSign'][0]['receiptId'] = "2";
            $signature[1]['dateSign'][0]['documentId'] = "2";
        }
        /*         * ******************End*Application Disclosures****************************** */
        /*         * ******************Start*Financing Statement***************************** */
        $CakePdf = new \CakePdf\Pdf\CakePdf();
        $CakePdf->template('financing_statement', 'default');
        $CakePdf->viewVars($this->viewVars);
        $pdf = $CakePdf->output();
        $name = 'Financing-Statement';
        $pdf_path3 = $projectFilePath . $id . DS . $name . '.pdf';
        $pdf = $CakePdf->write($pdf_path3);
        $lastPage = $CakePdf->getPageCount();
        $signature[0]['sign'][1]['pageNumber'] = $lastPage;
        $signature[0]['sign'][1]['receiptId'] = "1";
        $signature[0]['sign'][1]['documentId'] = "3";
        $signature[0]['sign'][1]['xpos'] = "100";
        $signature[0]['sign'][1]['ypos'] = "265";

        $signature[0]['dateSign'][1]['xpos'] = "340";
        $signature[0]['dateSign'][1]['ypos'] = "250";
        $signature[0]['dateSign'][1]['pageNumber'] = $lastPage;
        $signature[0]['dateSign'][1]['receiptId'] = "1";
        $signature[0]['dateSign'][1]['documentId'] = "3";
        $signature[0]['initialSign'][1]['xpos'] = "480";
        $signature[0]['initialSign'][1]['ypos'] = "170";
        $signature[0]['initialSign'][1]['pageNumber'] = $lastPage - 1;
        $signature[0]['initialSign'][1]['receiptId'] = "1";
        $signature[0]['initialSign'][1]['documentId'] = "3";

        $signature[0]['initialSign'][2]['xpos'] = "480";
        $signature[0]['initialSign'][2]['ypos'] = "250";
        $signature[0]['initialSign'][2]['pageNumber'] = $lastPage - 1;
        $signature[0]['initialSign'][2]['receiptId'] = "1";
        $signature[0]['initialSign'][2]['documentId'] = "3";

        $signature[0]['initialSign'][3]['xpos'] = "480";
        $signature[0]['initialSign'][3]['ypos'] = "370";
        $signature[0]['initialSign'][3]['pageNumber'] = $lastPage - 1;
        $signature[0]['initialSign'][3]['receiptId'] = "1";
        $signature[0]['initialSign'][3]['documentId'] = "3";

        $signature[0]['initialSign'][4]['xpos'] = "480";
        $signature[0]['initialSign'][4]['ypos'] = "450";
        $signature[0]['initialSign'][4]['pageNumber'] = $lastPage - 1;
        $signature[0]['initialSign'][4]['receiptId'] = "1";
        $signature[0]['initialSign'][4]['documentId'] = "3";
        if ($project['customer_detail']['so_first_name'] != '') {
            $signature[1]['sign'][1]['pageNumber'] = $lastPage;
            $signature[1]['sign'][1]['receiptId'] = "2";
            $signature[1]['sign'][1]['documentId'] = "3";
            $signature[1]['sign'][1]['xpos'] = "100";
            $signature[1]['sign'][1]['ypos'] = "380";

            $signature[1]['dateSign'][1]['xpos'] = "340";
            $signature[1]['dateSign'][1]['ypos'] = "370";
            $signature[1]['dateSign'][1]['pageNumber'] = $lastPage;
            $signature[1]['dateSign'][1]['receiptId'] = "2";
            $signature[1]['dateSign'][1]['documentId'] = "3";

            $signature[1]['initialSign'][1]['xpos'] = "480";
            $signature[1]['initialSign'][1]['ypos'] = "200";
            $signature[1]['initialSign'][1]['pageNumber'] = $lastPage - 1;
            $signature[1]['initialSign'][1]['receiptId'] = "2";
            $signature[1]['initialSign'][1]['documentId'] = "3";

            $signature[1]['initialSign'][2]['xpos'] = "480";
            $signature[1]['initialSign'][2]['ypos'] = "285";
            $signature[1]['initialSign'][2]['pageNumber'] = $lastPage - 1;
            $signature[1]['initialSign'][2]['receiptId'] = "2";
            $signature[1]['initialSign'][2]['documentId'] = "3";

            $signature[1]['initialSign'][3]['xpos'] = "480";
            $signature[1]['initialSign'][3]['ypos'] = "400";
            $signature[1]['initialSign'][3]['pageNumber'] = $lastPage - 1;
            $signature[1]['initialSign'][3]['receiptId'] = "2";
            $signature[1]['initialSign'][3]['documentId'] = "3";

            $signature[1]['initialSign'][4]['xpos'] = "480";
            $signature[1]['initialSign'][4]['ypos'] = "485";
            $signature[1]['initialSign'][4]['pageNumber'] = $lastPage - 1;
            $signature[1]['initialSign'][4]['receiptId'] = "2";
            $signature[1]['initialSign'][4]['documentId'] = "3";
        }
        /*         * ******************End*Financing Statement***************************** */
        /*         * ******************Start*Assessment Contract***************************** */

        $CakePdf = new \CakePdf\Pdf\CakePdf();
        $CakePdf->template('contract', 'default');
        $CakePdf->viewVars($this->viewVars);
        // Get the PDF string returned        
        $pdf = $CakePdf->output();
        $name = 'Assessment-Contract';
        $pdf_path4 = $projectFilePath . $id . DS . $name . '.pdf';
        $pdf = $CakePdf->write($pdf_path4);
        $lastPage = $CakePdf->getPageCount();

        $signature[0]['sign'][2]['pageNumber'] = "8";
        $signature[0]['sign'][2]['receiptId'] = "1";
        $signature[0]['sign'][2]['documentId'] = "4";
        $signature[0]['sign'][2]['xpos'] = "250";
        $signature[0]['sign'][2]['ypos'] = "140";

        $signature[0]['dateSign'][2]['xpos'] = "250";
        $signature[0]['dateSign'][2]['ypos'] = "218";
        $signature[0]['dateSign'][2]['pageNumber'] = "8";
        $signature[0]['dateSign'][2]['receiptId'] = "1";
        $signature[0]['dateSign'][2]['documentId'] = "4";

        $signature[0]['dateSign'][3]['xpos'] = "220";
        $signature[0]['dateSign'][3]['ypos'] = "621";
        $signature[0]['dateSign'][3]['pageNumber'] = "$lastPage";
        $signature[0]['dateSign'][3]['receiptId'] = "1";
        $signature[0]['dateSign'][3]['documentId'] = "4";



        $signature[0]['initialSign'][5]['xpos'] = "250";
        $signature[0]['initialSign'][5]['ypos'] = "690";
        $signature[0]['initialSign'][5]['pageNumber'] = "4";
        $signature[0]['initialSign'][5]['receiptId'] = "1";
        $signature[0]['initialSign'][5]['documentId'] = "4";

        $signature[0]['initialSign'][6]['xpos'] = "95";
        $signature[0]['initialSign'][6]['ypos'] = "600";
        $signature[0]['initialSign'][6]['pageNumber'] = "$lastPage";
        $signature[0]['initialSign'][6]['receiptId'] = "1";
        $signature[0]['initialSign'][6]['documentId'] = "4";

        if ($project['customer_detail']['so_first_name'] != '') {
            $signature[1]['sign'][2]['pageNumber'] = "8";
            $signature[1]['sign'][2]['receiptId'] = "2";
            $signature[1]['sign'][2]['documentId'] = "4";
            $signature[1]['sign'][2]['xpos'] = "250";
            $signature[1]['sign'][2]['ypos'] = "300";

            $signature[1]['dateSign'][2]['xpos'] = "250";
            $signature[1]['dateSign'][2]['ypos'] = "380";
            $signature[1]['dateSign'][2]['pageNumber'] = "8";
            $signature[1]['dateSign'][2]['receiptId'] = "2";
            $signature[1]['dateSign'][2]['documentId'] = "4";

            $signature[1]['dateSign'][3]['xpos'] = "470";
            $signature[1]['dateSign'][3]['ypos'] = "621";
            $signature[1]['dateSign'][3]['pageNumber'] = "$lastPage";
            $signature[1]['dateSign'][3]['receiptId'] = "2";
            $signature[1]['dateSign'][3]['documentId'] = "4";


            $signature[1]['initialSign'][5]['xpos'] = "250";
            $signature[1]['initialSign'][5]['ypos'] = "710";
            $signature[1]['initialSign'][5]['pageNumber'] = "4";
            $signature[1]['initialSign'][5]['receiptId'] = "2";
            $signature[1]['initialSign'][5]['documentId'] = "4";

            $signature[1]['initialSign'][6]['xpos'] = "330";
            $signature[1]['initialSign'][6]['ypos'] = "600";
            $signature[1]['initialSign'][6]['pageNumber'] = "$lastPage";
            $signature[1]['initialSign'][6]['receiptId'] = "2";
            $signature[1]['initialSign'][6]['documentId'] = "4";
        }
        /*         * *****************JPA*sign******************* */
        $signature[2]['user']['email'] = $authorizedSignatoryEmail;
        $signature[2]['user']['name'] = $authorizedSignatoryName;
        $signature[2]['user']['receiptId'] = "4";
        $signature[2]['user']['order'] = 3;
        if ($project['customer_detail']['so_first_name'] != '') {
            $signXpos = 100;
            $signYpos = 525;
            $dateSignXpos = 355;
            $dateSignYpos = 555;
        } else {
            $signXpos = 100;
            $signYpos = 358;
            $dateSignXpos = 355;
            $dateSignYpos = 387;
        }
        $signature[2]['sign'][0]['pageNumber'] = "8";
        $signature[2]['sign'][0]['receiptId'] = "4";
        $signature[2]['sign'][0]['documentId'] = "4";
        $signature[2]['sign'][0]['xpos'] = $signXpos;
        $signature[2]['sign'][0]['ypos'] = $signYpos;

        $signature[2]['dateSign'][0]['xpos'] = $dateSignXpos;
        $signature[2]['dateSign'][0]['ypos'] = $dateSignYpos;
        $signature[2]['dateSign'][0]['pageNumber'] = "8";
        $signature[2]['dateSign'][0]['receiptId'] = "4";
        $signature[2]['dateSign'][0]['documentId'] = "4";

        /*         * *****************Operation-Email*Before*JPA***************** */
        $signature[3]['user']['email'] = $operationEmail;
        $signature[3]['user']['name'] = $operationName;
        $signature[3]['user']['receiptId'] = "3";
        $signature[3]['user']['order'] = 2;
        /*         * ******************End*Assessment Contract***************************** */
        /*         * ******************Start*Right to Cancel***************************** */

        $CakePdf = new \CakePdf\Pdf\CakePdf();
        $CakePdf->template('right_to_cancel', 'default');
        $CakePdf->viewVars($this->viewVars);
        // Get the PDF string returned
        $pdf = $CakePdf->output();
        $name = 'Right-To-Cancel';
        $pdf_path5 = $projectFilePath . $id . DS . $name . '.pdf';
        $pdf = $CakePdf->write($pdf_path5);
        $lastPage = $CakePdf->getPageCount();
        $signature[0]['sign'][3]['pageNumber'] = "1";
        $signature[0]['sign'][3]['receiptId'] = "1";
        $signature[0]['sign'][3]['documentId'] = "5";
        $signature[0]['sign'][3]['xpos'] = "227";
        $signature[0]['sign'][3]['ypos'] = "510";
        /*
          $signature[0]['sign'][4]['pageNumber'] = "2";
          $signature[0]['sign'][4]['receiptId'] = "1";
          $signature[0]['sign'][4]['documentId'] = "5";
          $signature[0]['sign'][4]['xpos'] = "240";
          $signature[0]['sign'][4]['ypos'] = "430";
         */
        $signature[0]['dateSign'][4]['xpos'] = "390";
        $signature[0]['dateSign'][4]['ypos'] = "553";
        $signature[0]['dateSign'][4]['pageNumber'] = "1";
        $signature[0]['dateSign'][4]['receiptId'] = "1";
        $signature[0]['dateSign'][4]['documentId'] = "5";
        /*
          $signature[0]['dateSign'][5]['xpos'] = "390";
          $signature[0]['dateSign'][5]['ypos'] = "471";
          $signature[0]['dateSign'][5]['pageNumber'] = "2";
          $signature[0]['dateSign'][5]['receiptId'] = "1";
          $signature[0]['dateSign'][5]['documentId'] = "5";
         */

        if ($project['customer_detail']['so_first_name'] != '') {
            $signature[1]['sign'][3]['pageNumber'] = "1";
            $signature[1]['sign'][3]['receiptId'] = "2";
            $signature[1]['sign'][3]['documentId'] = "5";
            $signature[1]['sign'][3]['xpos'] = "227";
            $signature[1]['sign'][3]['ypos'] = "562";
            /*
              $signature[1]['sign'][4]['pageNumber'] = "2";
              $signature[1]['sign'][4]['receiptId'] = "2";
              $signature[1]['sign'][4]['documentId'] = "5";
              $signature[1]['sign'][4]['xpos'] = "240";
              $signature[1]['sign'][4]['ypos'] = "482";
             */
            $signature[1]['dateSign'][4]['xpos'] = "390";
            $signature[1]['dateSign'][4]['ypos'] = "605";
            $signature[1]['dateSign'][4]['pageNumber'] = "1";
            $signature[1]['dateSign'][4]['receiptId'] = "2";
            $signature[1]['dateSign'][4]['documentId'] = "5";
            /*
              $signature[1]['dateSign'][5]['xpos'] = "390";
              $signature[1]['dateSign'][5]['ypos'] = "525";
              $signature[1]['dateSign'][5]['pageNumber'] = "2";
              $signature[1]['dateSign'][5]['receiptId'] = "2";
              $signature[1]['dateSign'][5]['documentId'] = "5";
             */
        }
        /*         * ******************End*Right to Cancel***************************** */
        $pdfFiles = array($pdf_path1, $pdf_path2, $pdf_path3, $pdf_path4, $pdf_path5);

        $sendRequest = $this->Docusign->signatureRequestOnDocument($config, "sent", false, $pdfFiles, $signature, $contract_key);
        if ($sendRequest->getEnvelopeId() != '') {
            $this->ContractDetails->updateAll(array('identity_verification_code' => $identificationCode, 'application_id' => $application_id, 'envelope_id' => $sendRequest->getEnvelopeId()), array('id' => $contract_res['id']));
            $res = array('error' => 0, 'envelope_id' => $sendRequest->getEnvelopeId());
        } else {
            $res = array('error' => 1);
        }

        return $res;
    }

    public function checkAuthKey() {
        $Results = [];
        $Results['status'] = 0;
        $UserAuthKeyResult = $this->Users->find()->where(['auth_key' => $this->request->data['auth_key']])->select('id')->first();

        if (empty($UserAuthKeyResult)) {
            $Results['message'] = "Auth key not match.";
            return $Results;
            exit;
        } else {

            $UserAuthKeyValidate = $this->Users->find()->where(['auth_key' => $this->request->data['auth_key'], 'deleted' => 0])->select('id')->first();
            if (empty($UserAuthKeyValidate)) {
                $Results['message'] = "Your Auth key is disabled. please contact to support team.";
                return $Results;
                exit;
            } else {
                $checkContractorCode = $this->Users->find()->where([ 'and' => ['parent_id' => $UserAuthKeyResult['id']], 'or' => ['user_code' => $this->request->data['contractor_code']]])->select('id')->first();

                if (empty($checkContractorCode)) {
                    $Results['message'] = "Contractor Code not match.";
                    return $Results;
                    exit;
                }
                $checkContractorCodeValidate = $this->Users->find()->where([ 'and' => ['parent_id' => $UserAuthKeyResult['id'], 'deleted' => 0], 'or' => ['user_code' => $this->request->data['contractor_code']]])->select('id')->first();

                if (empty($checkContractorCodeValidate)) {
                    $Results['message'] = "Your Contractor Code is disabled. please contact to support team.";
                    return $Results;
                    exit;
                } else {

                    $checkSalespersonCode = $this->Users->find()->where([ 'and' => ['parent_id' => $checkContractorCode['id']], 'or' => ['user_code' => $this->request->data['salesperson_code']]])->select('id')->first();
                    if (empty($checkSalespersonCode)) {
                        $Results['message'] = "Salesperson Code not match.";
                        return $Results;
                        exit;
                    } else {
                        $checkSalespersonCodeValidate = $this->Users->find()->where([ 'and' => ['parent_id' => $checkContractorCode['id'], 'deleted' => 0], 'or' => ['user_code' => $this->request->data['salesperson_code']]])->select('id')->first();

                        if (empty($checkSalespersonCodeValidate)) {
                            $Results['message'] = "Your Salesperson Code is disabled. please contact to support team.";
                            return $Results;
                            exit;
                        }
                    }
                }
            }
        }
        $Results['status'] = 1;
        $Results['id'] = $checkSalespersonCode['id'];
        return $Results;
        exit;
    }

    public function validateSSN($str) {

        return preg_match("/^\d{3}\-\d{2}\-\d{4}$/", $str);
    }

    public function checkdateFormat($date) {
        $dateInput = explode('/', $date);
        if (count($dateInput) == 3) {
            return checkdate($dateInput[0], $dateInput[1], $dateInput[2]);
        } else {
            return FALSE;
        }
    }

    public function currentTerms() {
        $rates = $this->RateRecords->find('all', ['contain' => ['Rates'], 'conditions' => ['RateRecords.status' => 1, 'RateRecords.deleted' => 0], 'fields' => ['Rates.term_id']])->toArray();

        $term_ids = Hash::extract($rates, '{n}.Rates.term_id');
        return $loan_term = $this->Terms->find('list', ['conditions' => ['Terms.id IN' => $term_ids], 'order' => ['term' => 'asc'], 'keyField' => 'id', 'valueField' => 'term'])->toArray();
    }

    function addContractor() {


        if (!isset($this->request->data['auth_key'])) {
            $response['message'][] = 'Auth key is required';
        }
        if (!isset($this->request->data['phone'])) {
            $response['message'][] = 'Phone number is required.';
        }
        if (!isset($this->request->data['title'])) {
            $response['message'][] = 'Title is required.';
        }
        if (!isset($this->request->data['first_name'])) {
            $response['message'][] = 'First name is required.';
        }
        if (!isset($this->request->data['last_name'])) {
            $response['message'][] = 'Last name is required.';
        }
        if (!isset($this->request->data['mobile'])) {
            $response['message'][] = 'Mobile number is required.';
        }
        if (!isset($this->request->data['email'])) {
            $response['message'][] = 'EmailId is required.';
        }
        if (!isset($this->request->data['company_name'])) {
            $response['message'][] = 'Company name is required.';
        }
        if (!isset($this->request->data['website'])) {
            $response['message'][] = 'Website is required.';
        }
        if (!isset($this->request->data['company_phone'])) {
            $response['message'][] = 'Company phone is required.';
        }
        if (!isset($this->request->data['license_number'])) {
            $response['message'][] = 'License number is required.';
        }
        if (!isset($this->request->data['license_state'])) {
            $response['message'][] = 'License state is required.';
        }
        if (!isset($this->request->data['license_expiration'])) {
            $response['message'][] = 'License expiration is required.';
        }
        if (!isset($this->request->data['company_address'])) {
            $response['message'][] = 'Company address is required.';
        }
        if (!isset($this->request->data['company_city'])) {
            $response['message'][] = 'Company city is required.';
        }
        if (!isset($this->request->data['company_state'])) {
            $response['message'][] = 'Company state is required.';
        }
        if (!isset($this->request->data['company_pincode'])) {
            $response['message'][] = 'Company zipcode is required.';
        }
        if (!isset($this->request->data['project_type'])) {
            $response['message'][] = 'Project type is required.';
        }
        if (!isset($this->request->data['administrator_name'])) {
            $response['message'][] = 'Administrator name is required.';
        }
        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }


        $response = array();
        $response['status'] = 0;
        $UserAuthKeyResult = $this->Users->find()->where(['auth_key' => $this->request->data['auth_key']])->select('id')->first();
        if (empty($UserAuthKeyResult)) {
            $response['message'] = "Auth key not match.";
            echo json_encode($response);
            exit;
        } else {
            $UserAuthKeyValidate = $this->Users->find()->where(['auth_key' => $this->request->data['auth_key'], 'deleted' => 0])->select('id')->first();
            if (empty($UserAuthKeyValidate)) {
                $response['message'] = "Your Auth key is disabled. please contact to support team.";
                echo json_encode($response);
                exit;
            } else {
                $this->request->data['parent_id'] = $UserAuthKeyResult['id'];
            }
        }

        if ($this->validate_phoneUS($this->request->data['phone']) == false) {
            $response['message'][] = 'Phone Number  ' . $this->request->data['phone'] . ' not valid';
        }

        if ($this->validate_phoneUS($this->request->data['mobile']) == false) {
            $response['message'][] = 'Work Number  ' . $this->request->data['mobile'] . ' not valid';
        }

        if (filter_var($this->request->data['email'], FILTER_VALIDATE_EMAIL) == false) {

            $response['message'][] = 'Email ID ' . $this->request->data['email'] . ' not valid';
        }

        if ($this->Users->find()->where(['email' => $this->request->data['email']])->select('id')->count() > 0) {

            $response['message'][] = 'Email ID ' . $this->request->data['email'] . ' already exist';
        }

        $this->request->data['contractor_detail']['company_name'] = $this->request->data['company_name'];
        //-----Website validetion -----------------------
        if (filter_var($this->request->data['website'], FILTER_VALIDATE_URL) == false) {

            $response['message'][] = 'Website ' . $this->request->data['website'] . ' url not valid';
        }
        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }
        $this->request->data['contractor_detail']['website'] = $this->request->data['website'];


        if ($this->validate_phoneUS($this->request->data['company_phone']) == false) {
            $response['message'][] = 'Company Phone Number ' . $this->request->data['company_phone'] . ' not valid';
        }


        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }
        $this->request->data['contractor_detail']['company_phone'] = $this->request->data['company_phone'];
        $this->request->data['contractor_detail']['license_number'] = $this->request->data['license_number'];
        $this->request->data['contractor_detail']['license_state'] = $this->request->data['license_state'];

        if ($this->checkdateFormat($this->request->data['license_expiration']) != true) {

            $response['message'] = 'License Expiration Date ' . $this->request->data['license_expiration'] . ' not valid';
            echo json_encode($response);
            exit;
        }

        if (isset($this->request->data['license_expiration']) && $this->request->data['license_expiration'] != '') {
            $this->request->data['contractor_detail']['license_expiration'] = $this->getDate($this->request->data['license_expiration']);
        }
        $this->request->data['contractor_detail']['company_address'] = $this->request->data['company_address'];
        if (isset($this->request->data['company_address2'])) {
            $this->request->data['contractor_detail']['company_address2'] = $this->request->data['company_address2'];
        }
        $this->request->data['contractor_detail']['company_city'] = $this->request->data['company_city'];

        $StateName = $this->States->findByName($this->request->data['company_state'])->select('id')->first();
        if (!isset($StateName['id'])) {

            $response['message'] = 'Company State ' . $this->request->data['company_state'] . ' not valid';
            echo json_encode($response);
            exit;
        } else {
            $this->request->data['contractor_detail']['state_id'] = $StateName['id'];
        }

        $StateName = $this->States->findByName($this->request->data['license_state'])->select('id')->first();
        if (!isset($StateName['id'])) {

            $response['message'] = 'License state ' . $this->request->data['license_state'] . ' not valid';
            echo json_encode($response);
            exit;
        }

        $this->request->data['contractor_detail']['company_pincode'] = $this->request->data['company_pincode'];

        $ProjectTypes = $this->ProjectTypes->findByName($this->request->data['project_type'])->select('id')->first();
        if (!isset($ProjectTypes['id'])) {

            $response['message'] = 'Project type ' . $this->request->data['project_type'] . ' not valid';
            echo json_encode($response);
            exit;
        } else {
            $this->request->data['contractor_detail']['project_type'] = $ProjectTypes['id'];
        }

        $this->request->data['contractor_detail']['administrator_name'] = $this->request->data['administrator_name'];

        $user = $this->Users->newEntity();
        $this->request->data['role_id'] = 3;
        $this->request->data['name'] = $this->request->data['first_name'] . ' ' . $this->request->data['last_name'];
        $this->request->data['user_code'] = $this->Common->generateCode(6);
        $password = $this->Common->generateCode(7);
        $this->request->data['password'] = $password;
        // echo   (new DefaultPasswordHasher)->hash('12345'); die;

        unset($this->request->data['auth_key'], $this->request->data['country']);
        // create random code//
        $user = $this->Users->patchEntity($user, $this->request->data);

        if ($this->Users->save($user)) {


            $url1 = Router::url(['prefix' => false, 'controller' => 'Users', 'action' => 'login'], true);
            $url = "<a href='$url1'>$url1</a>";
            $mail = $this->SystemMails->findByEmailType('AddUserWithApi')->first();
            $mail['message'] = str_replace('[name]', $this->request->data['name'], $mail['message']);
            $mail['message'] = str_replace('[email]', $this->request->data['email'], $mail['message']);
            $mail['message'] = str_replace('[password]', $password, $mail['message']);
            $mail['message'] = str_replace('[login_url]', $url, $mail['message']);
            $mail['message'] = str_replace('[Code]', $this->request->data['user_code'], $mail['message']);
            $mail['to'] = $this->request->data['email'];
            $this->sendEmail($mail);

            $response['status'] = 1;
            $response['message'] = 'The Contractor has been saved';
            unset($user['role_id'], $user['contractor_detail'], $user['parent_id'], $user['created'], $user['modified']);
            $response['userDetail'] = $user;

            echo json_encode($response);
            exit;
        } else {

            $response['status'] = 0;
            $response['message'] = 'The Contractor has been not saved.Please, try again.';
            echo json_encode($response);
            exit;
        }
        echo json_encode($response);
        exit;
    }

    function addSalesperson() {



        $response = array();
        $response['status'] = 0;
        $response['message'] = [];

        if (!isset($this->request->data['auth_key'])) {
            $response['message'][] = 'Auth key is required';
        }
        if (!isset($this->request->data['contractor_code'])) {
            $response['message'][] = 'Contractor code is required';
        }

        if (!isset($this->request->data['phone'])) {
            $response['message'][] = 'Phone number is required.';
        }
        if (!isset($this->request->data['title'])) {
            $response['message'][] = 'Title is required.';
        }
        if (!isset($this->request->data['first_name'])) {
            $response['message'][] = 'First name is required.';
        }
        if (!isset($this->request->data['last_name'])) {
            $response['message'][] = 'Last name is required.';
        }
        if (!isset($this->request->data['mobile'])) {
            $response['message'][] = 'Mobile number is required.';
        }
        if (!isset($this->request->data['email'])) {
            $response['message'][] = 'EmailId is required.';
        }
        if (!isset($this->request->data['address1'])) {
            $response['message'][] = 'Address1 is required.';
        }
        if (!isset($this->request->data['city'])) {
            $response['message'][] = 'City is required.';
        }
        if (!isset($this->request->data['state'])) {
            $response['message'][] = 'State is required.';
        }
        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }


        $UserAuthKeyResult = $this->Users->find()->where(['auth_key' => $this->request->data['auth_key']])->select('id')->first();

        if (empty($UserAuthKeyResult)) {
            $response['status'] = 2;
            $response['message'] = "Auth key not match.";
            echo json_encode($response);
            exit;
        } else {
            $UserAuthKeyValidate = $this->Users->find()->where(['auth_key' => $this->request->data['auth_key'], 'deleted' => 0])->select('id')->first();
            if (empty($UserAuthKeyValidate)) {
                $response['status'] = 2;
                $response['message'] = "Your Auth key is disabled. please contact to support team.";
                echo json_encode($response);
                exit;
            } else {
                $checkContractorCode = $this->Users->find()->where([ 'and' => ['parent_id' => $UserAuthKeyResult['id']], 'or' => ['user_code' => $this->request->data['contractor_code']]])->select('id')->first();

                if (empty($checkContractorCode)) {
                    $response['status'] = 2;
                    $response['message'] = "Contractor Code not match.";
                    echo json_encode($response);
                    exit;
                } else {
                    $checkContractorCodeValidate = $this->Users->find()->where([ 'and' => ['parent_id' => $UserAuthKeyResult['id'], 'deleted' => 0], 'or' => ['user_code' => $this->request->data['contractor_code']]])->select('id')->first();

                    if (empty($checkContractorCodeValidate)) {
                        $response['status'] = 2;
                        $response['message'] = "Your Contractor Code is disabled. please contact to support team.";
                        echo json_encode($response);
                        exit;
                    } else {
                        $this->request->data['parent_id'] = $checkContractorCode['id'];
                    }
                }
            }
        }

        if ($this->validate_phoneUS($this->request->data['phone']) == false) {
            $response['message'][] = 'Phone number ' . $this->request->data['phone'] . ' not valid';
        }
        if ($this->validate_phoneUS($this->request->data['mobile']) == false) {
            $response['message'][] = 'Mobile number ' . $this->request->data['mobile'] . ' not valid';
        }
        //---------- Email validetion---------------------------
        if (filter_var($this->request->data['email'], FILTER_VALIDATE_EMAIL) == false) {

            $response['message'][] = 'Email ID ' . $this->request->data['email'] . ' not valid';
        }
        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }
        if ($this->Users->find()->where(['email' => $this->request->data['email']])->select('id')->count() > 0) {

            $response['message'] = 'Email ID ' . $this->request->data['email'] . ' already exist';
            echo json_encode($response);
            exit;
        }
        $StateName = $this->States->findByName($this->request->data['state'])->select('id')->first();
        if (!isset($StateName['id'])) {
            $response['message'] = 'state ' . $this->request->data['state'] . ' not valid';
            echo json_encode($response);
            exit;
        } else {
            $this->request->data['state_id'] = $StateName['id'];
        }

        $user = $this->Users->newEntity();
        $this->request->data['role_id'] = 4;
        $this->request->data['name'] = $this->request->data['first_name'] . ' ' . $this->request->data['last_name'];
        // create random code


        $this->request->data['user_code'] = $this->Common->generateCode(8);
        $password = $this->Common->generateCode(7);
        $this->request->data['password'] = $password;
        unset($this->request->data['auth_key'], $this->request->data['country']);
        // create random code//

        $user = $this->Users->patchEntity($user, $this->request->data);

        if ($this->Users->save($user)) {

            $url1 = Router::url(['prefix' => false, 'controller' => 'Users', 'action' => 'login'], true);
            $url = "<a href='$url1'>$url1</a>";
            $mail = $this->SystemMails->findByEmailType('AddUserWithApi')->first();
            $mail['message'] = str_replace('[name]', $this->request->data['name'], $mail['message']);
            $mail['message'] = str_replace('[email]', $this->request->data['email'], $mail['message']);
            $mail['message'] = str_replace('[password]', $password, $mail['message']);
            $mail['message'] = str_replace('[login_url]', $url, $mail['message']);
            $mail['message'] = str_replace('[Code]', $this->request->data['user_code'], $mail['message']);
            $mail['to'] = $this->request->data['email'];
            $this->sendEmail($mail);

            $response['status'] = 1;
            $response['message'] = 'The Sales person has been saved';

            unset($user['role_id'], $user['parent_id'], $user['state_id'], $user['name'], $user['created'], $user['modified']);
            $user['state'] = $this->request->data['state'];
            $response['userDetail'] = $user;
            echo json_encode($response);
            exit;
        } else {

            $response['status'] = 0;
            $response['message'] = 'The Sales person has been not saved.Please, try again.';
            echo json_encode($response);
            exit;
        }
        echo json_encode($response);
        exit;
    }

    /**
     * ********************************************************************
     * ******************  Post Fni Result APi  **********************
     * *******************************************************************
     */
    public function postFniResult() {
        $Results = [];
        $Results['status'] = 0;
        $Results['message'] = [];   
        if (!isset($this->request->data['auth_key'])) {
            $Results['message'][] = 'Auth key is required';
            echo json_encode($Results);
            exit;
        }elseif(isset($this->request->data['auth_key']) && Configure::read('Site.FniAuthKey')!=$this->request->data['auth_key']){
            $Results['message'][] = 'Auth key invalid.';
            echo json_encode($Results);
            exit;
        }       
        if (!isset($this->request->data['projectId'])) {
            $Results['message'][] = 'Project id is required.';
            echo json_encode($Results);
            exit;
        }
        $checkProject = $this->Projects->findById($this->request->data['projectId'])->first();
        if (empty($checkProject)) {
            $Results['message'][] = 'Invalid  Project Id.';
            echo json_encode($Results);
            exit;
        }
        if (!isset($this->request->data['fni_status'])) {
            $Results['message'][] = 'FNI status is required.';
//            echo json_encode($Results);
//            exit;
        }

        if ($this->request->data['fni_status'] == 'A') {
            if (!isset($this->request->data['auth_amount']) || !is_numeric($this->request->data['auth_amount'])) {
                $Results['message'][] = 'FNI Amount not valid';
//                echo json_encode($Results);
//                exit;
            }

            if (!isset($this->request->data['avm'])) {
                $Results['message'][] = 'AVM is required.';
//                echo json_encode($Results);
//                exit;
            }
            if (!isset($this->request->data['apn'])) {
                $Results['message'][] = 'APN is required.';
//                echo json_encode($Results);
//                exit;
            }
            if (!isset($this->request->data['legal_description'])) {
                $Results['message'][] = 'Description is required.';
//                echo json_encode($Results);
//                exit;
            }

            if (!isset($this->request->data['municipality'])) {
                $Results['message'][] = 'Legal municipality is required.';
//                echo json_encode($Results);
//                exit;
            }
            if (!isset($this->request->data['county'])) {
                $Results['message'][] = 'County is required.';
//                echo json_encode($Results);
//                exit;
            }
        }

        if (empty($Results['message'])) {
            echo json_encode($Results);
            exit;
        }

        $project = $this->Projects->get($this->request->data['projectId']);
        if (empty($project)) {
            $Results['message'][] = 'Invalid project.';
            echo json_encode($Results);
            exit;
        }


        if ($project['status'] != 8) {
            $Results['message'][] = 'Invalid project id.';
            echo json_encode($Results);
            exit;
        }
        if (empty($Results['message'])) {
            if ($this->request->data['fni_status'] == 'A') {               
                $this->request->data['status'] = 6;
                $this->request->data['credit_approved_date'] = Time::parseDate(date('Y-m-d'), Configure::read('Site.CakeDateFormat'));
            } elseif ($this->request->data['fni_status'] == 'P') {
                $this->request->data['status'] = 8;
            } elseif ($this->request->data['fni_status'] == 'D') {
                $this->request->data['status'] = 7;
            }
            $project = $this->Projects->patchEntity($project, $this->request->data);
            if ($this->Projects->save($project)) {
                $Results['message'] = 'Success';
                $Results['status'] = 1;
            }
        }
        echo json_encode($Results);
        exit;
    }

    public function postProjectFile($folder = 'contractor') {
        $Results = array();
        $Results['status'] = 0;
        $Results['message'] = [];

        if (!isset($this->request->data['auth_key'])) {
            $Results['message'][] = 'Auth key is required';
        }
        if (!isset($this->request->data['contractor_code'])) {
            $Results['message'][] = 'Contractor code is required';
        }
        if (!isset($this->request->data['projectId'])) {
            $Results['message'][] = 'Project id is required.';
        }
        if (!isset($this->request->data['file'])) {
            $Results['message'][] = 'File is required.';
        }


        if (!empty($Results['message'])) {
            echo json_encode($response);
            exit;
        }
        $UserAuthKeyResult = $this->Users->find()->where(['auth_key' => $this->request->data['auth_key']])->select('id')->first();

           
        if (empty($UserAuthKeyResult)) {
            $Results['status'] = 2;
            $Results['message'] = "Auth key not match.";
            echo json_encode($Results);
            exit;
        } else {
            $UserAuthKeyValidate = $this->Users->find()->where(['auth_key' => $this->request->data['auth_key'], 'deleted' => 0])->select('id')->first();
            if (empty($UserAuthKeyValidate)) {
                $Results['status'] = 2;
                $Results['message'] = "Your Auth key is disabled. please contact to support team.";
                echo json_encode($Results);
                exit;
            } else {
                $checkContractorCode = $this->Users->find()->where([ 'and' => ['parent_id' => $UserAuthKeyResult['id']], 'or' => ['user_code' => $this->request->data['contractor_code']]])->select('id')->first();

                if (empty($checkContractorCode)) {
                    $Results['status'] = 2;
                    $Results['message'] = "Contractor Code not match.";
                    echo json_encode($Results);
                    exit;
                } else {
                    $checkContractorCodeValidate = $this->Users->find()->where([ 'and' => ['parent_id' => $UserAuthKeyResult['id'], 'deleted' => 0], 'or' => ['user_code' => $this->request->data['contractor_code']]])->select('id')->first();

                    if (empty($checkContractorCodeValidate)) {
                        $Results['status'] = 2;
                        $Results['message'] = "Your Contractor Code is disabled. please contact to support team.";
                        echo json_encode($Results);
                        exit;
                    } else {
                        $contractor_id = $checkContractorCode['id'];
                    }
                }
            }
        }


        $checkProject = $this->Projects->findById($this->request->data['projectId'])->contain('Users')->first();

        if (empty($checkProject)) {
            $Results['message'][] = 'Invalid  Project Id.';
            echo json_encode($Results);
            exit;
        } elseif ($checkProject['user']['parent_id'] != $contractor_id) {
            $Results['message'][] = 'Please use owen project id.';
            echo json_encode($Results);
            exit;
        }

        if ($checkProject['status'] < 10) {
            $Results['message'][] = 'Project not eligible for upload any documents.';
            echo json_encode($Results);
            exit;
        }


        if (!isset($this->request->data['type'])) {
            $Results['message'][] = 'type id is required.';
            echo json_encode($Results);
            exit;
        } else {
            $type = $this->request->data['type'];
        }
        $projectFilePath = Configure::read('Site.ProjectsFilePath');
        $file = base64_decode($this->request->data['file']);
        $f = finfo_open();
        $file_type = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
        if ($file_type != 'application/pdf') {
            $Results['message'][] = 'Invalid file.Use only pdf file';
            echo json_encode($Results);
            exit;
        }

        $project_id = $this->request->data['projectId'];


        $filename = str_replace(' ', '-', ucwords(str_replace('_', ' ', $type))) . '.' . 'pdf';
        $projectDocuments = $this->ProjectDocuments->findByProjectId($project_id)->first();

        if (empty($projectDocuments)) {
            $projectDocuments = $this->ProjectDocuments->newEntity();
        }



        $path = $projectFilePath . $project_id . DS . 'contractor_files' . DS;
        new File($path . 'index.html', true, 0777);
        $file_path = $path . $filename;


        $approvalFiles = array('signed_work_contract', 'signed_work_contract_modified', 'permit', 'permission_to_operate');
        if (!in_array($type, $approvalFiles)) {
            $Results['message'][] = 'Invalid type.';
            echo json_encode($Results);
            exit;
        }

        $documentTypeArr = array('signed_work_contract' => 'Signed Work Contract', 'signed_work_contract_modified' => 'Signed Work Contract(modified)', 'permit' => 'Permit', 'permission_to_operate' => 'Permission to Operate');

        if (file_put_contents($file_path, $file)) {

            $projectDocumentsData['project_id'] = $project_id;
            $projectDocumentsData[$type] = $filename;
            if (in_array($type, $approvalFiles)) {
                $project = $this->Projects->findById($project_id)->contain(['Users.Contractors'])->first();
                $projectDocumentsData[$type . '_status'] = 0;
                $applicationLink = Router::url(['controller' => 'Projects', 'action' => 'certificateCompletion', $project['id']], true);
                $documentType = $documentTypeArr[$type];

                $paceManagerMail = $this->SystemMails->findByEmailType('ProjectDocumentsNotificationToPaceManager')->first()->toArray();

                $paceManagerMail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $paceManagerMail['message']);
                $paceManagerMail['message'] = str_replace('[Contractor]', $project['user']['contractor']['name'], $paceManagerMail['message']);
                $paceManagerMail['message'] = str_replace('[DocumentType]', $documentType, $paceManagerMail['message']);
                $paceManagerMail['message'] = str_replace('[Project_ID]', $project['id'], $paceManagerMail['message']);
                $paceManagerMail['message'] = str_replace('[ApplicationLink]', $applicationLink, $paceManagerMail['message']);
                $paceManagerMail['subject'] = str_replace('[Project_ID]', $project['id'], $paceManagerMail['subject']);
                $paceManagerMail['subject'] = str_replace('[DocumentType]', $documentType, $paceManagerMail['subject']);
                $paceManagerMail['to'] = Configure::read('Site.NotifyEmailForUploadedContractorDoc');
                if ($this->sendEmail($paceManagerMail)) {
                    
                }
            }
            $projectDocuments = $this->ProjectDocuments->patchEntity($projectDocuments, $projectDocumentsData);
            $this->ProjectDocuments->save($projectDocuments);
            $Results['status'] = 1;
            $Results['message'][] = 'File uploaded successfully.';
            echo json_encode($Results);
            exit;
        } else {
            $Results['message'][] = 'An error occurred. Please try again';
            echo json_encode($Results);
            exit;
        }

        echo json_encode($Results);
        exit;
    }

    public function PostProjectCancellation($type = 'customer_cancellation_form', $folder = 'contractor') {
        $Results = array();
        $Results['status'] = 0;
        $Results['message'] = [];

        if (!isset($this->request->data['auth_key'])) {
            $Results['message'][] = 'Auth key is required';
        }
        if (!isset($this->request->data['contractor_code'])) {
            $Results['message'][] = 'Contractor code is required';
        }

        if (!isset($this->request->data['projectId'])) {
            $Results['message'][] = 'Project id is required.';
        }

        if (!empty($Results['message'])) {
            echo json_encode($Results);
            exit;
        }

        $UserAuthKeyResult = $this->Users->find()->where(['auth_key' => $this->request->data['auth_key']])->select('id')->first();
        if (empty($UserAuthKeyResult)) {
            $Results['status'] = 2;
            $Results['message'] = "Auth key not match.";
            echo json_encode($Results);
            exit;
        } else {
            $UserAuthKeyValidate = $this->Users->find()->where(['auth_key' => $this->request->data['auth_key'], 'deleted' => 0])->select('id')->first();
            if (empty($UserAuthKeyValidate)) {
                $Results['status'] = 2;
                $Results['message'] = "Your Auth key is disabled. please contact to support team.";
                echo json_encode($Results);
                exit;
            } else {
                $checkContractorCode = $this->Users->find()->where([ 'and' => ['parent_id' => $UserAuthKeyResult['id']], 'or' => ['user_code' => $this->request->data['contractor_code']]])->select('id')->first();

                if (empty($checkContractorCode)) {
                    $Results['status'] = 2;
                    $Results['message'] = "Contractor Code not match.";
                    echo json_encode($Results);
                    exit;
                } else {
                    $checkContractorCodeValidate = $this->Users->find()->where([ 'and' => ['parent_id' => $UserAuthKeyResult['id'], 'deleted' => 0], 'or' => ['user_code' => $this->request->data['contractor_code']]])->select('id')->first();

                    if (empty($checkContractorCodeValidate)) {
                        $Results['status'] = 2;
                        $Results['message'] = "Your Contractor Code is disabled. please contact to support team.";
                        echo json_encode($Results);
                        exit;
                    } else {
                        $contractor_id = $checkContractorCode['id'];
                    }
                }
            }
        }




        $checkProject = $project = $this->Projects->findById($this->request->data['projectId'])->contain(['Users', 'CustomerDetails'])->first();
        if (empty($checkProject)) {
            $Results['message'][] = 'Invalid  Project Id.';
            echo json_encode($Results);
            exit;
        } elseif ($checkProject['user']['parent_id'] != $contractor_id) {
            $Results['message'][] = 'Please use owen project id.';
            echo json_encode($Results);
            exit;
        }

        if ($checkProject['status'] < 9) {
            $Results['message'][] = 'Project not eligible for upload any cancellation documents.';
            echo json_encode($Results);
            exit;
        }

        $projectFilePath = Configure::read('Site.ProjectsFilePath');
        $file = base64_decode($this->request->data['file']);
        $f = finfo_open();
        $file_type = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
        if ($file_type != 'application/pdf') {
            $Results['message'][] = 'Invalid file.Use only pdf file';
            echo json_encode($Results);
            exit;
        }

        $project_id = $this->request->data['projectId'];

        $filename = str_replace(' ', '-', ucwords(str_replace('_', ' ', $type))) . '.' . 'pdf';
        $projectDocuments = $this->ProjectDocuments->findByProjectId($project_id)->first();

        if (empty($projectDocuments)) {
            $projectDocuments = $this->ProjectDocuments->newEntity();
        }



        $path = $projectFilePath . $project_id . DS . 'contractor_files' . DS;
        new File($path . 'index.html', true, 0777);
        $file_path = $path . $filename;


        if (file_put_contents($file_path, $file)) {

            $projectDocumentsData['project_id'] = $project_id;
            $projectDocumentsData[$type] = $filename;

            $projectDocuments = $this->ProjectDocuments->patchEntity($projectDocuments, $projectDocumentsData);
            $this->ProjectDocuments->save($projectDocuments);

            $project = $this->Projects->findById($project_id)->contain(['Users', 'CustomerDetails'])->first();
            /*             * **************Send*Email*To*Sales*Person**************** */
            $salesPersonMail = $this->SystemMails->findByEmailType('CustomerCancellationNoticeToSalesRep')->first()->toArray();

            $salesPersonMail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $salesPersonMail['message']);
            $salesPersonMail['message'] = str_replace('[Customer]', $project['owner_name'], $salesPersonMail['message']);
            $salesPersonMail['message'] = str_replace('[SalesRep]', $project['user']['name'], $salesPersonMail['message']);
            $salesPersonMail['subject'] = str_replace('[Customer]', $project['owner_name'], $salesPersonMail['subject']);
            $salesPersonMail['to'] = $project['user']['email'];

            if ($this->sendEmail($salesPersonMail)) {
                
            }
            /*             * **************Send*Email*To*First*Owner*************** */
            $mail = $secondMail = $this->SystemMails->findByEmailType('CustomerCancellationNoticeToCustomer')->first()->toArray();

            $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
            $mail['message'] = str_replace('[Customer]', $project['owner_name'], $mail['message']);
            $mail['to'] = $project['customer_detail']['fo_email'];

            if ($project['customer_detail']['fo_email'] != "") {

                if ($this->sendEmail($mail)) {
                    
                }
            }

            /*             * **************Send*Email*To*Second*Owner*************** */
            if ($project['customer_detail']['property_ownership'] == 'Joint') {
                $secondMail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $secondMail['message']);
                $secondMail['message'] = str_replace('[Customer]', $project['customer_detail']['so_first_name'] . ' ' . $project['customer_detail']['so_last_name'], $secondMail['message']);
                $secondMail['to'] = $project['customer_detail']['so_email'];
                if ($project['customer_detail']['so_email'] != "") {
                    if ($this->sendEmail($secondMail)) {
                        
                    }
                }
            }
            $this->Projects->updateAll(['status' => 14], ['id' => $project_id]);



            $Results['status'] = 1;
            $Results['message'][] = 'File uploaded successfully.';
            echo json_encode($Results);
            exit;
        } else {
            $Results['message'][] = 'An error occurred. Please try again';
            echo json_encode($Results);
            exit;
        }

        echo json_encode($Results);
        exit;
    }

    public function validate_phoneUS($number) {
        $numStripX = array('(', ')', '-', '.', '+');
        $numCheck = str_replace($numStripX, '', $number);
        $firstNum = substr($number, 0, 1);
        if (($firstNum == 0) || ($firstNum == 1)) {
            return false;
        } elseif (!is_numeric($numCheck)) {
            return false;
        } elseif (strlen($numCheck) > 10) {
            return false;
        } elseif (strlen($numCheck) < 10) {
            return false;
        } else {
            $formats = array('###-###-####', '(###) ###-####', '(###)###-####', '##########', '###.###.####', '(###) ###.####', '(###)###.####');
            $format = trim(ereg_replace("[0-9]", "#", $number));
            return (in_array($format, $formats)) ? true : false;
        }
    }

    public function sendCertificate($id, $change = 0,$docusignConfig=array()) {
        $projectFilePath = Configure::read('Site.ProjectsFilePath');
        $project = $this->Projects->find('all', ['contain' => ['Users.Contractors.ContractorDetails', 'ContractDetails.EquipmentInformations.ProjectTypes', 'ProjectCalculatePayments', 'CustomerDetails'], 'conditions' => ['ContractDetails.status' => 1, 'Projects.id' => $id]])->first();
        $formula_data = $this->Common->getPaymentFormulaInfo($project['contract_detail']['term_of_assessment']);
        $totalFees = ($formula_data['origination_fee'] / 100) + $formula_data['lien_recording_fee'] + $formula_data['loan_loss_reserve'] + $formula_data['foreclosure_expense_reserve'] + $formula_data['annual_administrative_fee'];
        $first_payment_date = date('Y-m-d', strtotime($project['project_calculate_payment']['first_payment_date']));
        $pmt = $project['contract_detail']['pmt'];
        $payment_data = $this->Common->getPaymentTableData($project['contract_detail']['assessment'], $pmt, $project['contract_detail']['term_of_assessment'], $first_payment_date, $project['tax_rate'], $formula_data);
        $TotalInterest = array_sum(array_column($payment_data['data'], 'interest'));
        $this->loadComponent('Docusign');
        $config = $this->Docusign->login($docusignConfig);
        $contract_res = $project['contract_detail'];
        $contract_key = $contract_res['access_key'];
        $application_id = $project['id'];
        /* Set Variables */
        $identificationCode = rand(100000, 999999);
        $ProgramOrigination = ($project['project_amount'] * $formula_data['origination_fee']) / 100;
        $this->set('owner1', $project['customer_detail']['fo_first_name'] . ' ' . $project['customer_detail']['fo_last_name']);
        $this->set('country', $project['county']); //this is county
        $this->set('creditApprovedDate', $project['credit_approved_date']);
        $this->set('identificationCode', $identificationCode);
        $this->set('JPASignerName', Configure::read('Site.JpaAuthorityName'));
        $this->set('AddressProperty', $project['address']);
        $this->set('CityProperty', $project['city']);
        $this->set('StateProperty', $project['state']);
        $this->set('ZipProperty', $project['zipcode']);
        $this->set('ParcelNumber', $project['apn']); //this is apn
        $this->set('LegalDesc', $project['legal_description']);
        $this->set('AddressMailing', $project['customer_detail']['fo_address']);
        $this->set('CityMailing', $project['customer_detail']['fo_city']);
        $this->set('StateMailing', $project['customer_detail']['fo_state']);
        $this->set('ZIPMailing', $project['customer_detail']['fo_zipcode']);
        $this->set('Assessment', $project['contract_detail']['assessment']);
        $this->set('TotalFees', round($totalFees, 2));
        $this->set('CapInterest', $project['contract_detail']['capitalized_interest']);
        $this->set('TotalProjectCost', $project['project_amount']);
        $this->set('Rate', $project['contract_detail']['rate']);
        $this->set('term', $project['contract_detail']['term_of_assessment']);
        $this->set('MaturityDate', date('F d, Y', strtotime($project['contract_detail']['maturity_date'])));
        $this->set('APR', $project['contract_detail']['apr']);
        $this->set('AnnualPayment', $project['contract_detail']['pmt']);
        $this->set('AnnualAdminFee', $formula_data['annual_administrative_fee']);
        $this->set('TotalAnnualPayment', $project['contract_detail']['total']);
        $this->set('EmailOwner1', $project['customer_detail']['fo_email']);
        $this->set('Municipality', $project['municipality']);
        $this->set('todaysDate', date("M d, Y"));
        $this->set('ExpirationDate', date("M d, Y", strtotime('+120 days')));
        $this->set('ExpectedCompletionDate', date("M d, Y", strtotime($project['completion_date'])));
        $this->set('project', $project);
        $this->set('payment_data', $payment_data);
        $this->set('PropertyValuation', $project['avm']); //this is avm
        $this->set('ApplicationID', $application_id);
        $this->set('UpfrontCosts', ($project['contract_detail']['assessment'] - $project['project_amount']));
        $this->set('TotalAssessmentObligation', (count($payment_data['data']) * $payment_data['data'][0]['total']));
        $this->set('TotalInterest', $TotalInterest);
        $this->set('formula_data', $formula_data);
        $this->set('ProgramOrigination', $project['contract_detail']['origination_fee_total']);
        $this->set('ContractorName', $project['user']['contractor']['name']);
        $this->set('ContractorCompanyName', $project['user']['contractor']['contractor_detail']['company_name']);
        $this->set('ContractorLicense', $project['user']['contractor']['contractor_detail']['license_number']);
        $this->set('ContractorPhone', $project['user']['contractor']['contractor_detail']['company_phone']);
        $this->set('ContractorAddress', $project['user']['contractor']['contractor_detail']['company_address']);
        $this->set('AuthorizedSignatoryName', Configure::read('Site.AuthorizedSignatoryName'));
        $authorizedSignatoryEmail = Configure::read('Site.AuthorizedSignatoryEmail');
        $authorizedSignatoryName = Configure::read('Site.AuthorizedSignatoryName');
        $operationEmail = Configure::read('Site.OperationEmail');
        $operationName = Configure::read('Site.OperationName');

        if ($project['customer_detail']['so_first_name'] != '') {
            $this->set('owner2', $project['customer_detail']['so_first_name'] . ' ' . $project['customer_detail']['so_last_name']);
        }

        $CakePdf = new \CakePdf\Pdf\CakePdf();
        $CakePdf->template('completion_certificate', 'default');
        $CakePdf->viewVars($this->viewVars);
        $pdf = $CakePdf->output();
        $file = new File($projectFilePath . $id . DS . 'index.html', true, 0777);
        $name = 'Completion-Certificate';
        $pdf_path1 = $projectFilePath . $id . DS . $name . '.pdf';
        $pdf = $CakePdf->write($pdf_path1);
        $lastPage = $CakePdf->getPageCount();
        $signature[0]['email_subject'] = "PACE Funding Completion Certificate";
        $signature[0]['email_content'] = "Please review Completion Certificate.";

        /*         * *****************contractor*sign******************* */
        $signature[0]['user']['email'] = $project['user']['contractor']['email'];
        $signature[0]['user']['name'] = $project['user']['contractor']['name'];
        $signature[0]['user']['receiptId'] = "1";
        $signature[0]['user']['order'] = 1;

        $signature[0]['sign'][0]['pageNumber'] = $lastPage - 1;
        $signature[0]['sign'][0]['receiptId'] = "1";
        $signature[0]['sign'][0]['documentId'] = "1";
        $signature[0]['sign'][0]['xpos'] = "165";
        $signature[0]['sign'][0]['ypos'] = "450";

        $signature[0]['dateSign'][0]['xpos'] = "399";
        $signature[0]['dateSign'][0]['ypos'] = "490";
        $signature[0]['dateSign'][0]['pageNumber'] = $lastPage - 1;
        $signature[0]['dateSign'][0]['receiptId'] = "1";
        $signature[0]['dateSign'][0]['documentId'] = "1";
        /*         * *****************Owner First *sign******************* */
        $signature[1]['user']['email'] = $project['customer_detail']['fo_email'];
        $signature[1]['user']['name'] = $project['customer_detail']['fo_first_name'] . ' ' . $project['customer_detail']['fo_last_name'];
        $signature[1]['user']['receiptId'] = "2";
        $signature[1]['user']['order'] = 1;


        $signature[1]['sign'][0]['pageNumber'] = $lastPage;
        $signature[1]['sign'][0]['receiptId'] = "2";
        $signature[1]['sign'][0]['documentId'] = "1";
        $signature[1]['sign'][0]['xpos'] = "195";
        $signature[1]['sign'][0]['ypos'] = "490";

        $signature[1]['dateSign'][0]['xpos'] = "399";
        $signature[1]['dateSign'][0]['ypos'] = "530";
        $signature[1]['dateSign'][0]['pageNumber'] = $lastPage;
        $signature[1]['dateSign'][0]['receiptId'] = "2";
        $signature[1]['dateSign'][0]['documentId'] = "1";
        /*         * *****************Owner Second *sign******************* */
        if ($project['customer_detail']['so_first_name'] != '') {
            $signature[2]['user']['email'] = $project['customer_detail']['so_email'];
            $signature[2]['user']['name'] = $project['customer_detail']['so_first_name'] . ' ' . $project['customer_detail']['so_last_name'];
            $signature[2]['user']['receiptId'] = "3";
            $signature[2]['user']['order'] = 1;


            $signature[2]['sign'][1]['pageNumber'] = $lastPage;
            $signature[2]['sign'][1]['receiptId'] = "3";
            $signature[2]['sign'][1]['documentId'] = "1";
            $signature[2]['sign'][1]['xpos'] = "195";
            $signature[2]['sign'][1]['ypos'] = "555";

            $signature[2]['dateSign'][1]['xpos'] = "399";
            $signature[2]['dateSign'][1]['ypos'] = "595";
            $signature[2]['dateSign'][1]['pageNumber'] = $lastPage;
            $signature[2]['dateSign'][1]['receiptId'] = "3";
            $signature[2]['dateSign'][1]['documentId'] = "1";
        }
        if ($change == 1) {
            $CakePdf = new \CakePdf\Pdf\CakePdf();
            $CakePdf->template('contract_addendum', 'default');
            $CakePdf->viewVars($this->viewVars);
            $pdf = $CakePdf->output();
            $file = new File($projectFilePath . $id . DS . 'index.html', true, 0777);
            $name = 'Assessment-Contract-Addendum';
            $pdf_path2 = $projectFilePath . $id . DS . $name . '.pdf';
            $pdf = $CakePdf->write($pdf_path2);
            $lastPage = $CakePdf->getPageCount();
            /*             * *****************Owner First *sign******************* */
            $signature[1]['sign'][1]['pageNumber'] = "2";
            $signature[1]['sign'][1]['receiptId'] = "1";
            $signature[1]['sign'][1]['documentId'] = "2";
            $signature[1]['sign'][1]['xpos'] = "250";
            $signature[1]['sign'][1]['ypos'] = "140";

            $signature[1]['dateSign'][1]['xpos'] = "250";
            $signature[1]['dateSign'][1]['ypos'] = "218";
            $signature[1]['dateSign'][1]['pageNumber'] = "2";
            $signature[1]['dateSign'][1]['receiptId'] = "1";
            $signature[1]['dateSign'][1]['documentId'] = "2";

            /*             * *****************Owner Second *sign******************* */
            if ($project['customer_detail']['so_first_name'] != '') {
                $signature[2]['sign'][2]['pageNumber'] = "2";
                $signature[2]['sign'][2]['receiptId'] = "3";
                $signature[2]['sign'][2]['documentId'] = "2";
                $signature[2]['sign'][2]['xpos'] = "250";
                $signature[2]['sign'][2]['ypos'] = "300";

                $signature[2]['dateSign'][2]['xpos'] = "250";
                $signature[2]['dateSign'][2]['ypos'] = "385";
                $signature[2]['dateSign'][2]['pageNumber'] = "2";
                $signature[2]['dateSign'][2]['receiptId'] = "3";
                $signature[2]['dateSign'][2]['documentId'] = "2";
            }
            /*             * *****************JPA*sign******************* */
            $signature[3]['user']['email'] = $authorizedSignatoryEmail;
            $signature[3]['user']['name'] = $authorizedSignatoryName;
            $signature[3]['user']['receiptId'] = "4";
            $signature[3]['user']['order'] = 3;
            if ($project['customer_detail']['so_first_name'] != '') {
                $signXpos = 100;
                $signYpos = 545;
                $dateSignXpos = 355;
                $dateSignYpos = 555;
            } else {
                $signXpos = 100;
                $signYpos = 378;
                $dateSignXpos = 355;
                $dateSignYpos = 387;
            }
            $signature[3]['sign'][0]['pageNumber'] = "2";
            $signature[3]['sign'][0]['receiptId'] = "4";
            $signature[3]['sign'][0]['documentId'] = "2";
            $signature[3]['sign'][0]['xpos'] = $signXpos;
            $signature[3]['sign'][0]['ypos'] = $signYpos;

            $signature[3]['dateSign'][0]['xpos'] = $dateSignXpos;
            $signature[3]['dateSign'][0]['ypos'] = $dateSignYpos;
            $signature[3]['dateSign'][0]['pageNumber'] = "2";
            $signature[3]['dateSign'][0]['receiptId'] = "4";
            $signature[3]['dateSign'][0]['documentId'] = "2";

            /*             * *****************Operation-Email*Before*JPA***************** */
            $signature[4]['user']['email'] = $operationEmail;
            $signature[4]['user']['name'] = $operationName;
            $signature[4]['user']['receiptId'] = "5";
            $signature[4]['user']['order'] = 2;

            $pdfFiles = array($pdf_path1, $pdf_path2);
        } else {
            $pdfFiles = array($pdf_path1);
        }
        $sendRequest = $this->Docusign->signatureRequestOnDocument($config, "sent", false, $pdfFiles, $signature, $contract_key);
        if ($sendRequest->getEnvelopeId() != '') {
            $this->ContractDetails->updateAll(array('identity_verification_code' => $identificationCode, 'application_id' => $application_id, 'envelope_id' => $sendRequest->getEnvelopeId()), array('id' => $contract_res['id']));
            $this->Projects->updateAll(['status' => 15], ['id' => $id]);
            $res = array('error' => 0, 'envelope_id' => $sendRequest->getEnvelopeId());
        } else {
            $res = array('error' => 1);
        }

        return $res;
    }

    function sendCompletionCertificate() {

        $response = [];
        $response['status'] = 0;
        $response['message'] = [];

        if (!isset($this->request->data['auth_key']) && $this->request->data['auth_key'] == '') {
            $response['message'][] = 'Auth key is required';
        }
        if (!isset($this->request->data['contractor_code']) && $this->request->data['contractor_code'] == '') {
            $response['message'][] = 'Contractor code is required';
        }
        if (!isset($this->request->data['salesperson_code']) && $this->request->data['salesperson_code'] == '') {
            $response['message'][] = 'Salesperson code is required';
        }

        if (!isset($this->request->data['projectId'])) {
            $response['message'][] = 'Project id is required.';
        }

        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }
        // *************** check auth key *********************
        $checkAuthKey = $this->checkAuthKey();
        if ($checkAuthKey['status'] == 0) {
            $response['message'][] = $checkAuthKey['message'];
            echo json_encode($response);
            exit;
        }

        $checkProject = $this->Projects->findById($this->request->data['projectId'])->first();

        if (empty($checkProject)) {
            $response['message'][] = 'Invalid  Project Id.';
            echo json_encode($response);
            exit;
        }

        $project = $this->Projects->get($this->request->data['projectId']);
        if (empty($project)) {
            $response['message'][] = "Invalid project Id.";
            echo json_encode($response);
            exit;
        }

        if ($checkAuthKey['id'] != $project['user_id']) {
            $response['message'][] = "You are not authorized to access project by this salesperson code.";
            echo json_encode($response);
            exit;
        }
        if ($project['deleted'] == 1) {
            $response['message'][] = 'This project is deleted.';
        }

        if (!empty($response['message'])) {
            echo json_encode($response);
            exit;
        }

//        $project = $this->Projects->findById($this->request->data['projectId'])->first();
        if ($project['status'] >= 15) {
            $response['message'][] = 'Certificate completion already sent.';
            echo json_encode($response);
            exit;
        }
        if ($project['status'] < 10) {

            $response['message'][] = 'Project not eligible for certificate completion.';
            echo json_encode($response);
            exit;
        }
        $docusignConfig=$this->getDocusignDetailByAuthKay($this->request->data['auth_key']);
             $this->loadComponent('Docusign');
             try {
                 $this->Docusign->login($docusignConfig);
                 $docusignError=0;
             } catch (\Exception $exc) {
                 $docusignError=1;
             }
             if ($docusignError) {
                $response['message'][] = 'Docusign credential not working. Please contact our support team.'; // need to changes message
                echo json_encode($response);
                exit;
         }
        $contractDetail = $this->ContractDetails->findByProjectIdAndStatus($this->request->data['projectId'], 1)->first();
        $formula_data = $this->Common->getPaymentFormulaInfo($contractDetail['term_of_assessment']);
        $past30daysDate = date('Y-m-d', strtotime('-30 days'));
        $new30daysDate = date('Y-m-d', strtotime('+30 days'));
        $completion_date = date('Y-m-d', strtotime($project['completion_date']));
        if ($past30daysDate != $completion_date || $new30daysDate != $completion_date) {          
            $current_date = date('Y-m-d');
            $june_date = date('Y-06-23');
            $june_date_for_maturity = date('Y-06-23');
            if ($completion_date > $current_date && $completion_date < $june_date) {
                $first_payment_date = date('Y-09-02');
            } else {
                $first_payment_date = date('Y-09-02', strtotime('+1 year'));
            }
            $date = new Date(date('Y-09-02'));
            if ($completion_date > $current_date && $completion_date < $june_date_for_maturity) {
                $date->modify('+' . $contractDetail['term_of_assessment'] . ' year');
            } else {
                $date->modify('+' . ($contractDetail['term_of_assessment'] + 1) . ' year');
            }
            $maturity_date = $date->format('Y-m-d');

            $assessment_data = $this->Common->getAssessment($project['project_amount'], $first_payment_date, $completion_date, $formula_data);
            $assessment = $assessment_data['assessment'];
            $assessment_1 = $assessment_data['assessment_1'];
            $capitalized_interest = $assessment_data['capitalized_interest'];
            $origination_fee_total = $assessment_data['origination_fee_total'];
            $fin = new Financial();
            $pmt = $fin->PMT(($formula_data['rate'] / 100), $contractDetail['term_of_assessment'], -$assessment);
            $pmt = round($pmt, 2);
            $payment_data = $this->Common->getPaymentTableData($assessment, $pmt, $contractDetail['term_of_assessment'], $first_payment_date, $project['tax_rate'], $formula_data);
            
            $pmt_all_data = Hash::extract($payment_data['data'], '{n}.total');

            $assessment_with_interest = round($assessment_1 + $capitalized_interest, 2);
            $apr = $fin->apr($assessment_with_interest, $pmt_all_data);
            /*             * ***********End*payment*Calculations************************** */

            $contract_data['project_id'] = $project['id'];
            $contract_data['capitalized_interest'] = $capitalized_interest;
            $contract_data['origination_fee_total'] = $origination_fee_total;
            $contract_data['assessment'] = $assessment;
            $contract_data['maturity_date'] = Time::parseDate($maturity_date, Configure::read('Site.CakeDateFormat'));
            $contract_data['pmt'] = $pmt;
            $contract_data['apr'] = $apr;
            $contract_data['rate'] = $formula_data['rate'];
            $contract_data['total'] = $payment_data['data'][0]['total'];
            $contract_data['total_net_payment'] = $payment_data['total_net_payment'];
            $contract_key = $contract_data['access_key'] = md5(uniqid());
            $contract_data['application_date'] = Time::parseDate(date("Y-m-d"), Configure::read('Site.CakeDateFormat'));
            $contractDetail = $this->ContractDetails->patchEntity($contractDetail, $contract_data);            
            if ($this->ContractDetails->save($contractDetail)) {
                $doc_res = $this->sendCertificate($this->request->data['projectId'], 1,$docusignConfig);
            }
        } else {
            $doc_res = $this->sendCertificate($this->request->data['projectId'], 0,$docusignConfig);
        }
        if ($doc_res['error'] == 0) {
            $response['status'] = 1;
            $response['envelope_id'] = $doc_res['envelope_id'];
            $response['message'][] = 'Certificate for e-signature sent';
        } else {
            $response['status'] = 0;
            $response['message'][] = 'Certificate for e-signature not sent';
        }
        echo json_encode($response);
        exit;
    }
    protected function getDocusignDetailByAuthKay($auth_key){
        $channelPartner = $this->Users->findByAuthKey($auth_key)->select(['docusign_username','docusign_password','docusign_integrator_key'])->first()->toArray();
        if(!empty($channelPartner) && $channelPartner['docusign_username']!='' && $channelPartner['docusign_password']!='' && $channelPartner['docusign_integrator_key']!=''  ){
            return $channelPartner; 
        }else{
            return array(); 
        }
    }
}
