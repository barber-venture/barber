<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Utility\Hash;
use Financial\Financial;
use Cake\Filesystem\File;
use Cake\Core\Configure;
use Cake\I18n\Number;
use Cake\Routing\Router;

/**
 * Projects Controller
 *
 * @property \App\Model\Table\ProjectsTable $Projects
 */
class ProjectsController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->loadModel('Terms');
        $this->loadModel('CustomerDetails');
        $this->loadModel('Equipments');
        $this->loadModel('ProjectTypes');
        $this->loadModel('EquipmentInformations');
        $this->loadModel('ContractDetails');
        $this->loadComponent('Common');
        $this->loadModel('ProjectDocuments');
        $this->loadModel('SystemMails');
        $this->loadModel('ProjectVerifications');
        $this->loadModel('RateRecords');
        $this->loadModel('ProjectDocuments');        
    }

    public function beforeFilter(\Cake\Event\Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['application', 'postApplication', 'thankYou', 'docuSignTrackAndDownload','noticeToProceed','fniProcess']);
    }

    /**
     * 
     * Project First Step
     * 
     */
    function addressEligibility($id = null) {
        $project = $this->Projects->findByIdAndUserId($id, $this->Auth->user('id'))->first();
        if ($id != '' && empty($project)) {
            $this->Flash->error(__('Invalid project.'));
            return $this->redirect(['action' => 'addressEligibility']);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $project = $this->Projects->get($this->request->data['id']);
            $project_data['step'] = 1;
            $project = $this->Projects->patchEntity($project, $project_data);
            if ($this->Projects->save($project)) {
                return $this->redirect(['action' => 'paymentCalculator', $this->request->data['id']]);
            }
        } elseif ($id == '') {
            $project = $this->Projects->find('all', ['conditions' => ['Projects.user_id' => $this->Auth->user('id'), 'Projects.zipcode IS' => NULL, 'Projects.step' => 0], 'order' => ['Projects.id' => 'desc']])->first();
            if (empty($project)) {
                $new_project = $this->Projects->newEntity();
                $this->request->data['user_id'] = $this->Auth->user('id');
                $new_project = $this->Projects->patchEntity($new_project, $this->request->data);
                $project = $this->Projects->save($new_project);
            }
        }
        $this->set(compact('project'));
    }

    /**
     * 
     * In-egibility Project Add
     * 
     */
    public function inegibilityProjectAdd($id = null) {
        $project = $this->Projects->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $project = $this->Projects->patchEntity($project, $this->request->data);
            if ($this->Projects->save($project)) {
                $this->Flash->success(__('The project contact detail has been saved.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
            } else {
                $this->Flash->error(__('The project contact detail could not be saved. Please, try again.'));
                return $this->redirect(['action' => 'addressEligibility']);
            }
        }
    }

    /**
     * 
     * Project Second Step
     * 
     */
    public function paymentCalculator($id = null) {
        $project = $this->Projects->findByIdAndUserId($id, $this->Auth->user('id'))->first();
        if (empty($project)) {
            $this->Flash->error(__('Invalid project.'));
            return $this->redirect(['action' => 'addressEligibility']);
        } elseif ($project['step'] == 0) {
            $this->Flash->error(__('Please check address eligibility.'));
            return $this->redirect(['action' => 'addressEligibility']);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $project_data['step'] = 2;
            $project = $this->Projects->patchEntity($project, $project_data);
            if ($this->Projects->save($project)) {
                return $this->redirect(['action' => 'creditApplication', $this->request->data['id']]);
            }
        }
        
        $rates=$this->RateRecords->find('all',['contain'=>['Rates'],'conditions'=>['RateRecords.status'=>1,'RateRecords.deleted'=>0],'fields'=>['Rates.term_id']])->toArray();
       
        $term_ids=Hash::extract($rates, '{n}.Rates.term_id');       
        $loan_term = $this->Terms->find('list', ['conditions'=>['Terms.id IN'=>$term_ids],'order' => ['term' => 'asc'], 'keyField' => 'term', 'valueField' => 'name']);
        $this->set(compact('project', 'loan_term'));
    }

    /**
     * 
     * Project Third Step
     * 
     */
    function creditApplication($id = null) {
        $project = $this->Projects->findByIdAndUserId($id, $this->Auth->user('id'))->contain('ContractDetails')->first();
        if (empty($project)) {
            $this->Flash->error(__('Invalid project.'));
            return $this->redirect(['action' => 'addressEligibility']);
        } elseif ($project['step'] == 0) {
            $this->Flash->error(__('Please check address eligibility.'));
            return $this->redirect(['action' => 'addressEligibility']);
        } elseif ($project['step'] == 1) {
            $this->Flash->error(__('Please calculate payment first.'));
            return $this->redirect(['action' => 'paymentCalculator', $project['id']]);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $project_data['step'] = 3;
            $project = $this->Projects->patchEntity($project, $project_data);
            if ($this->Projects->save($project)) {
                return $this->redirect(['action' => 'contract', $this->request->data['id']]);
            }
        }
        $this->set(compact('project'));
    }

    /**
     * 
     * Project step 4(Contract)
     * 
     */
    function contract($id = null) {       
        $project=$this->Projects->find('all',['contain'=>['ContractDetails'],'conditions'=>['Projects.id'=>$id,'Projects.user_id'=>$this->Auth->user('id')]])->first();
        
        if (empty($project)) {
            $this->Flash->error(__('Invalid project.'));
            return $this->redirect(['action' => 'addressEligibility']);
        } elseif ($project['step'] == 0) {
            $this->Flash->error(__('Please check address eligibility.'));
            return $this->redirect(['action' => 'addressEligibility']);
        } elseif ($project['step'] == 1) {
            $this->Flash->error(__('Please calculate payment first.'));
            return $this->redirect(['action' => 'paymentCalculator', $project['id']]);
        } elseif ($project['step'] == 2) {
            $this->Flash->error(__('Please send credit application first.'));
            return $this->redirect(['action' => 'creditApplication', $project['id']]);
        }
        $project_types = $this->ProjectTypes->find('all', ['conditions' => ['deleted' => 0], 'contain' => ['LoanTerms.Terms'], 'order' => ['name' => 'asc']])->toArray();
        $project_types = array_map(function($elem) {
            $elem['equipments'] = $this->Equipments->find('list', ['order' => ['manufacturer' => 'asc'], 'group' => 'Equipments.manufacturer', 'conditions' => ['Equipments.project_type_id' => $elem->id], 'keyField' => 'manufacturer', 'valueField' => 'manufacturer'])->toArray();
            $elem['term'] = $elem['loan_terms'][0]['term']['term'];
            unset($elem['loan_terms']);
            return $elem;
        }, $project_types);
        $eq_info = array();
        if ($project['status'] >= 9) {
            $eq_data = $this->ContractDetails->findByProjectIdAndStatus($id, 1)->contain('EquipmentInformations')->first();
            foreach ($eq_data['equipment_informations'] as $eq) {
                $eq_info[$eq['project_type_id']] = array('manufacturer' => $eq['manufacturer'], 'model' => $eq['model'], 'sku' => $eq['sku'], 'qty' => $eq['qty'], 'amount' => $eq['amount']);
            }
        }
        $loan_term = $this->Terms->find('list', ['order' => ['term' => 'asc'], 'keyField' => 'term', 'valueField' => 'name']);
        $this->set(compact('project', 'project_types', 'eq_info', 'loan_term'));
    }

    /**
     * 
     * For Customer First step
     * 
     */
    function application($key = null) {
        $this->viewBuilder()->layout('customer');
        $project = $this->Projects->findByAccessKey($key)->first();
        $render = '';
        if (empty($project)) {
            $this->Flash->error(__('Invalid url. Please try with valid url.'));
            $render = 'customer_error';
        } elseif ($project['status'] == 5) {
            $this->Flash->error(__('You have already posted your property information.'));
            $render = 'customer_error';
        }
        $this->set(compact('project'));
        if ($render != '') {
            $this->render($render);
        }
    }

    /**
     * 
     * For Customer Secound step
     * 
     */
    function postApplication($key = null) {
        $this->viewBuilder()->layout('customer');
        $project = $this->Projects->findByAccessKey($key)->first();
        $render = '';
        if (empty($project)) {
            $this->Flash->error(__('Invalid url. Please try with valid url.'));
            $render = 'customer_error';
        } elseif ($project['status'] >= 5) {
            $this->Flash->error(__('You have already post your property information.'));
            $render = 'customer_error';
        }
        $customerDetail = $this->CustomerDetails->newEntity();
        if ($this->request->is('post')) {
            if (isset($this->request->data['so_dob']) && $this->request->data['so_dob'] != '') {
                $this->request->data['so_dob'] = $this->Common->encrypt($this->request->data['so_dob']);
            }
            if (isset($this->request->data['so_ssn']) && $this->request->data['so_ssn'] != '') {
                $this->request->data['so_ssn'] = $this->Common->encrypt($this->request->data['so_ssn']);
            }
            $this->request->data['fo_ssn'] = $this->Common->encrypt($this->request->data['fo_ssn']);
            $this->request->data['fo_dob'] = $this->Common->encrypt($this->request->data['fo_dob']);
            $customerDetail = $this->CustomerDetails->patchEntity($customerDetail, $this->request->data);
            if ($customer=$this->CustomerDetails->save($customerDetail)) {
                $this->Common->executeFniProcess($customer['id']);
                $project_data['status'] = 5;
                $project_data['owner_name'] = $this->request->data['fo_first_name'].' '.$this->request->data['fo_last_name'];
                $project = $this->Projects->patchEntity($project, $project_data);
                $this->Projects->save($project);
                return $this->redirect(['action' => 'thankYou']);
            } else {
                $this->Flash->error(__('The application could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('project', 'customerDetail'));
        if ($render != '') {
            $this->render($render);
        }
    }

    /**
     * 
     * For Customer Thank You
     * 
     */
    function thankYou($key = null) {
        $this->viewBuilder()->layout('customer');
    }

    function downloadPaymentCalculations($id,$type=null) {
        $project = $this->Projects->find('all', ['contain' => ['ProjectCalculatePayments'], 'conditions' => ['Projects.id' => $id, 'Projects.user_id' => $this->Auth->user('id')]])->first();
        if (empty($project)) {
            $this->Flash->error(__('Invalid project.'));
            return $this->redirect(['action' => 'paymentCalculator', $id]);
        }
        $fin = new Financial();
        $formula_data = $this->Common->getPaymentFormulaInfo($project['term_of_assessment']);
        $first_payment_date = date('Y-m-d', strtotime($project['project_calculate_payment']['first_payment_date']));
        $pmt = $project['project_calculate_payment']['pmt'];
        $payment_data = $this->Common->getPaymentTableData($project['project_calculate_payment']['assessment'], $pmt, $project['term_of_assessment'], $first_payment_date, $project['tax_rate'], $formula_data);
        $pmt_all_data = Hash::extract($payment_data['data'], '{n}.total');
        $apr = $fin->apr($project['project_amount_est'] + $project['project_calculate_payment']['capitalized_interest'], $pmt_all_data);
        $re_amortization_data = array();
        if ($project['tax_credit'] == 'Yes') {
            $pmt_re_amortization = $project['project_calculate_payment']['pmt_re_amortization'];
            $amount_after_re_amortization = $project['project_calculate_payment']['amount_re_amortization'];
            $re_amortization_data = $this->Common->getPaymentTableData($amount_after_re_amortization, $pmt_re_amortization, $project['term_of_assessment'], $first_payment_date, $project['tax_rate'], $formula_data);
        }
        if (empty($re_amortization_data)) {
            $payment_calculate_data = $payment_data;
        } else {
            $payment_calculate_data = $re_amortization_data;
        }
        $saving_data = $savings = array();
        if (!empty($project['project_calculate_payment']['savings'])) {
            $rate = $project['project_calculate_payment']['inflation'] / 100;
            $tot = 0;
            for ($i = 0; $i <= 24; $i++) {
                $y = $i + 1;
                $saving_cal = ($project['project_calculate_payment']['savings'] * 12) * (pow((1 + $rate), ($y - 1)));
                $data['saving'] = (isset($payment_calculate_data['data'][$i]['net_payment'])) ? ($saving_cal - $payment_calculate_data['data'][$i]['net_payment']) : $saving_cal;
                array_push($saving_data, $data);
                $tot = $tot + round($data['saving'], 2);
            }
            $savings['total'] = $tot;
            $savings['data'] = $saving_data;
        }
        // pr($saving_data);
        // die;
        $this->set(compact('project', 'payment_data', 're_amortization_data', 'savings', 'apr', 'formula_data','type'));

        $CakePdf = new \CakePdf\Pdf\CakePdf();
        $CakePdf->template('payment_calculation', 'default');
        $CakePdf->viewVars($this->viewVars);
        //$CakePdf->orientation('landscape');
        $pdf = $CakePdf->html();
        $CakePdf->download($pdf, 'project-payment-calculations.pdf', 'D');
        //pr($project);
        exit;
    }

    function goToNextStep($id) {
        $project = $this->Projects->findByIdAndUserId($id, $this->Auth->user('id'))->first();
        if (empty($project)) {
            $this->Flash->error(__('Invalid project.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        if ($project['step'] == 0 && $project['status'] == 1) {
            $this->Projects->updateAll(array('step' => 1), array('id' => $id));
            return $this->redirect(['action' => 'paymentCalculator', $id]);
        } elseif ($project['step'] == 1 && $project['status'] == 3) {
            $this->Projects->updateAll(array('step' => 2), array('id' => $id));
            return $this->redirect(['action' => 'creditApplication', $id]);
        } elseif ($project['step'] == 2 && $project['status'] == 6) {
            $this->Projects->updateAll(array('step' => 3), array('id' => $id));
            return $this->redirect(['action' => 'contract', $id]);
        } else {
            $this->Flash->error(__('Invalid step.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
    }

    function docuSignTrackAndDownload($key = null) {
        if ($key == '') {
            exit;
        }
        $this->viewBuilder()->layout(false);
        $projectFilePath = Configure::read('Site.ProjectsFilePath');
        $contract = $this->ContractDetails->findByAccessKey($key)->contain('Projects.Users.Contractors')->first();
        if (empty($contract)) {
            exit;
        }
        $project_id = $contract['project']['id'];
        $data = file_get_contents('php://input');
        $xml = simplexml_load_string($data, "SimpleXMLElement", LIBXML_PARSEHUGE);
        $envelope_id = (string) $xml->EnvelopeStatus->EnvelopeID;
        $time_generated = (string) $xml->EnvelopeStatus->TimeGenerated;
        $file = new File($projectFilePath . $project_id . DS . 'customer_signed_files' . DS . 'index.html', true, 0777);
        $path = $projectFilePath . $project_id . DS . 'customer_signed_files' . DS;
        $filename = $path . "T" . str_replace(':', '_', $time_generated) . ".xml";
        //$ok = file_put_contents($filename, $data);
        // if ($ok === false) {
        // Couldn't write the file! Alert the humans!
        //   $this->log("!!!!!! PROBLEM DocuSign Webhook: Couldn't store $filename !");
        //   exit;
        // }
        if ($contract['project']['status'] != 15 && (string) $xml->EnvelopeStatus->Status != "Completed") {
               $completed = $declined = $recipeCount = 0;
            foreach ($xml->EnvelopeStatus->RecipientStatuses->RecipientStatus as $recipe) {
                if ($recipe->RoutingOrder == 1) {
                    $recipeCount++;
                    if ((string) $recipe->Status == 'Completed') {
                        $completed++;
                    } elseif ((string) $recipe->Status == 'Declined') {
                        $declined++;
                    }
                }    
            }
            if($completed==$recipeCount){
            /** **************Send*Email*To*Sales*Person**************** */
                $salesPersonMail = $this->SystemMails->findByEmailType('ContractStatusToSalesRep')->first()->toArray();
                $salesPersonMail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $salesPersonMail['message']);
                $salesPersonMail['message'] = str_replace('[SalesRep]', $contract['project']['user']['name'], $salesPersonMail['message']);
                $salesPersonMail['message'] = str_replace('[Customer]', $contract['project']['owner_name'], $salesPersonMail['message']);
                $salesPersonMail['message'] = str_replace('[Contractor]',$contract['project']['user']['contractor']['name'], $salesPersonMail['message']);
                $salesPersonMail['subject'] = str_replace('[Customer]', $contract['project']['owner_name'], $salesPersonMail['subject']);
                $salesPersonMail['to'] = $contract['project']['user']['email'];
                if ($this->sendEmail($salesPersonMail)) {  }  
                $contract_data['customer_signed_date'] = Time::parse();
                $this->Projects->updateAll(array('status' => 10), array('id' => $contract['project']['id']));
                $contract_data['customer_signed_status'] ='Completed';
              }elseif($declined > 0){
                   $this->Projects->updateAll(array('status' => 12), array('id' => $contract['project']['id']));
                   $contract_data['customer_signed_status'] ='Declined';
              }
        }
        if ((string) $xml->EnvelopeStatus->Status === "Completed") {
            // Loop through the DocumentPDFs element, storing each document.
             foreach ($xml->DocumentPDFs->DocumentPDF as $pdf) {
                    $filename = (string) $pdf->Name;
                    $full_filename = $path . $filename;
                    if ($pdf->DocumentType != 'SUMMARY') {
                        $files[] = $full_filename;
                    }
                    file_put_contents($full_filename, base64_decode((string) $pdf->PDFBytes));
                }                
            if ($contract['project']['status'] != 15) {
                $CakePdf = new \CakePdf\Pdf\CakePdf();
                $complete_pdf = $path . 'Countersigned-Assessment-Contract.pdf';
                $lastPage = $CakePdf->mergePDF($files, $complete_pdf);
                
                $projectDocuments = $this->ProjectDocuments->findByProjectId($project_id)->first();
                if (empty($projectDocuments)) {
                    $projectDocuments = $this->ProjectDocuments->newEntity();
                }
                $projectDocumentsData['project_id'] = $project_id;
                $projectDocumentsData['counter_signed_assessment_by_customer'] = 'Countersigned-Assessment-Contract.pdf';
                $projectDocuments = $this->ProjectDocuments->patchEntity($projectDocuments, $projectDocumentsData);
                $this->ProjectDocuments->save($projectDocuments);
                //$this->Projects->updateAll(array('status' => 11), array('id' => $contract['project']['id']));
                
            }elseif($contract['project']['status'] == 15){
                $this->Projects->updateAll(array('status' => 16), array('id' => $contract['project']['id'])); 
            }
            $contract_data['customer_signed_date'] = Time::parse();
            $contract_data['jpa_signed_date'] = Time::parse();
            $contract_data['customer_signed_status'] = (string) $xml->EnvelopeStatus->Status;
        } elseif ((string) $xml->EnvelopeStatus->Status === "Declined") {
            $this->Projects->updateAll(array('status' => 12), array('id' => $contract['project']['id']));
            $contract_data['customer_signed_status'] = (string) $xml->EnvelopeStatus->Status;
        }
        unset($contract['project']);
        
        $contract_data['jpa_signed_status'] = (string) $xml->EnvelopeStatus->Status;
        $contract = $this->ContractDetails->patchEntity($contract, $contract_data);
        $contract_res = $this->ContractDetails->save($contract);
        exit;
    }

    function verifyApplication($id = null) {
        $project = $this->Projects->findById($id)->contain(['Users','CustomerDetails'])->first();  
        
        if (empty($project)) {
            $this->Flash->error(__('Invalid project.'));
            return $this->redirect(['prefix' => false, 'controller' => 'Users', 'action' => 'dashboard']);
        }
        $projectVerifications = $this->ProjectVerifications->findByProjectId($project['id'])->contain('Projects')->first();
        if (empty($projectVerifications)) {
            $projectVerifications = $this->ProjectVerifications->newEntity();
        }
        if ($this->request->is('post')) {
            $auth_amount = $this->Common->getExpectedAmountWithMortgage($project, $this->request->data['outstanding_mortgage'], $this->request->data['zillow_estimate']);
            $projectCancel = false;
            if ($this->request->data['all_property_owners'] == 'No' || $this->request->data['all_debt_secured'] == 'No' || $this->request->data['property_taxes'] == 'No' || $this->request->data['subject_property'] == 'No' || $this->request->data['bankruptcies'] == 'No') {
                $projectCancel = true;
            }
            if (($project['project_amount'] > 0 && $auth_amount < $project['project_amount']) || $auth_amount==0 || $projectCancel) { 
                  /***************Cancel*Send*Email*To*First*Owner*************** */
                  $mail = $secondMail= $this->SystemMails->findByEmailType('ProjectCancellationNoticeToCustomer')->first()->toArray();
                  $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
                  $mail['message'] = str_replace('[Customer]', $project['owner_name'], $mail['message']);                 
                  $mail['to'] = $project['customer_detail']['fo_email'];
                  if($this->sendEmail($mail)){}
                  /***************Cancel*Send*Email*To*Second*Owner*************** */
                  if($project['customer_detail']['property_ownership']=='Joint'){
                  $secondMail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $secondMail['message']);
                  $secondMail['message'] = str_replace('[Customer]', $project['customer_detail']['so_first_name'].' '.$project['customer_detail']['so_last_name'], $secondMail['message']);                 
                  $secondMail['to'] = $project['customer_detail']['so_email'];
                  if($this->sendEmail($secondMail)){}
                  }
                $this->request->data['project']['status'] = 14;
                $status = 0;
            } else {
                $status = 1;
                /****************Send*Email*To*Sales*Person**************** */
                $salesPersonMail = $this->SystemMails->findByEmailType('CreditDecisionToSalesRep')->first()->toArray();
                $salesPersonMail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $salesPersonMail['message']);
                $salesPersonMail['message'] = str_replace('[Customer]', $project['owner_name'], $salesPersonMail['message']);
                $salesPersonMail['message'] = str_replace('[Amount]', Number::currency($auth_amount, 'USD'), $salesPersonMail['message']);
                $salesPersonMail['message'] = str_replace('[SalesRep]', $project['user']['name'], $salesPersonMail['message']);
                $salesPersonMail['subject'] = str_replace('[Customer]', $project['owner_name'], $salesPersonMail['subject']);
                $salesPersonMail['to'] = $project['user']['email'];
                if ($this->sendEmail($salesPersonMail)) {}
                /****************Send*Email*To*First*Owner*************** */
                $mail = $secondMail = $this->SystemMails->findByEmailType('CreditDecisionToCustomer')->first()->toArray();
                $mail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['message']);
                $mail['message'] = str_replace('[Customer]', $project['owner_name'], $mail['message']);
                $mail['message'] = str_replace('[Amount]', Number::currency($auth_amount, 'USD'), $mail['message']);
                $mail['to'] = $project['customer_detail']['fo_email'];
                if ($this->sendEmail($mail)) {}
                /****************Send*Email*To*Second*Owner*************** */
                if ($project['customer_detail']['property_ownership'] == 'Joint') {
                    $secondMail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $secondMail['message']);
                    $secondMail['message'] = str_replace('[Customer]', $project['customer_detail']['so_first_name'] . ' ' . $project['customer_detail']['so_last_name'], $secondMail['message']);
                    $secondMail['message'] = str_replace('[Amount]', Number::currency($auth_amount, 'USD'), $secondMail['message']);
                    $secondMail['to'] = $project['customer_detail']['so_email'];
                    if ($this->sendEmail($secondMail)) {}
                }
            }
            $this->request->data['project']['auth_amount']=$auth_amount;
            $this->request->data['project']['credit_approved_date']=Time::parseDate(date('Y-m-d'), Configure::read('Site.CakeDateFormat'));
            $project_data = $this->request->data['project'];
            $this->Projects->patchEntity($project, $project_data);
            if ($res=$this->Projects->save($project)) {
                $this->Common->postCreditDecisionOnNotifyUrl($res); 
                $this->CustomerDetails->updateAll(array('fo_ssn'=>'','fo_dob'=>'','so_ssn'=>'','so_dob'=>''),array('project_id'=>$id));
                $this->request->data['project_id'] = $id;
                $this->request->data['is_approved'] = $status;
                unset($this->request->data['project']);
                $this->ProjectVerifications->patchEntity($projectVerifications, $this->request->data);
                $this->ProjectVerifications->save($projectVerifications);
            }
            $this->Flash->success(__('Credit decision successfully submitted.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        $zestimate = $this->Common->getZestimate($project);
        $this->set(compact('project', 'zestimate','projectVerifications'));
    }

    function certificateCompletion($id = null) {
       $project = $this->Projects->findById($id)->contain('Users')->first();           
        if (empty($project)) {
            $this->Flash->error(__('Invalid project.'));
            return $this->redirect(['controller'=>'Users','action' => 'dashboard']);
        }elseif ($this->Auth->user('role_id')==3 && $project['user']['parent_id'] != $this->Auth->user('id')) {
            $this->Flash->error(__('Invalid project.'));
            return $this->redirect(['controller'=>'Users','action' => 'dashboard']);
        } elseif ($project['status'] < 10) {
            $this->Flash->error(__('Project not eligible for certificate completion.'));
            return $this->redirect(['controller'=>'Users','action' => 'dashboard']);
        }

        $projectDocuments = $this->ProjectDocuments->findByProjectId($id)->first();

        $this->set(compact('project', 'projectDocuments'));
    }
    /*This function use for send Notice to Proceed email to SalesPerson*/
    function noticeToProceed() {
       $this->viewBuilder()->layout(false);   
       $projectFilePath = Configure::read('Site.ProjectsFilePath');
       $projects=$this->ContractDetails->find('all',['contain'=>['Projects.Users.Contractors','Projects.EquipmentInformations.ProjectTypes','Projects.CustomerDetails'],'conditions'=>['TIMESTAMPDIFF(HOUR,ContractDetails.customer_signed_date,"'.date('Y-m-d H:i:s').'") >='=>Configure::read('Site.NoticeToProceedTime'),'Projects.status'=>10,'ContractDetails.change_status'=>0,'ContractDetails.status'=>1]])->toArray();
       
       // pr($projects);die;
        if(!empty($projects)){
            foreach ($projects as $project) {
                $application_id = $project['application_id'];
                /* Set Variables */
                $this->set('owner1', $project['project']['customer_detail']['fo_first_name'] . ' ' . $project['project']['customer_detail']['fo_last_name']);
                $this->set('country', $project['project']['county']); //this is county 
                $this->set('AddressProperty', $project['project']['address']);
                $this->set('CityProperty', $project['project']['city']);
                $this->set('StateProperty', $project['project']['state']);
                $this->set('ZipProperty', $project['project']['zipcode']);
                $this->set('TotalProjectCost', $project['project']['project_amount']);
                $this->set('todaysDate', date("M d, Y"));
                $this->set('ExpirationDate', date("M d, Y", strtotime('+120 days')));
                $this->set('project', $project);
                $this->set('PropertyValuation', $project['project']['avm']); //this is avm
                $this->set('ApplicationID', $application_id);
                if ($project['project']['customer_detail']['so_first_name'] != '') {
                    $this->set('owner2', $project['project']['customer_detail']['so_first_name'] . ' ' . $project['project']['customer_detail']['so_last_name']);
                }
                $CakePdf = new \CakePdf\Pdf\CakePdf();
                $CakePdf->template('notice_to_proceed', 'default');
                $CakePdf->viewVars($this->viewVars);
                $name = 'Notice-To-Proceed';
                $pdf_path = $projectFilePath . $project['project']['id'] . DS . $name . '.pdf';
                $pdf = $CakePdf->write($pdf_path);
                /*                 * **************Send*Email*To*Sales*Person**************** */
                $propertyCompleteAddress= $project['project']['address'].', '.$project['project']['city'].', '.$project['project']['state'].' '.$project['project']['zipcode'];
                $noticeMail = $this->SystemMails->findByEmailType('NoticeToProceed')->first()->toArray();
                $noticeMail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $noticeMail['message']);
                $noticeMail['message'] = str_replace('[SalesRep]', $project['project']['user']['name'], $noticeMail['message']);
                $noticeMail['message'] = str_replace('[Customer]', $project['project']['owner_name'], $noticeMail['message']);
                $noticeMail['message'] = str_replace('[Contractor]', $project['project']['user']['contractor']['name'], $noticeMail['message']);
                $noticeMail['message'] = str_replace('[PropertyAddress]', $propertyCompleteAddress, $noticeMail['message']);
                $noticeMail['message'] = str_replace('[Project_ID]', $project['project']['id'], $noticeMail['message']);
                $noticeMail['subject'] = str_replace('[Project_ID]', $project['project']['id'], $noticeMail['subject']);
                $noticeMail['to'][] = $project['project']['email_address'];
                $noticeMail['to'][] = $project['project']['user']['email'];
                $noticeMail['to'][] = $project['project']['user']['contractor']['email'];
                $attachments = [
                    pathinfo($pdf_path, PATHINFO_BASENAME) => [
                        'file' => $pdf_path,
                        'mimetype' => 'application/pdf'
                    ]
                ];
                if ($this->sendEmailWithAttachments($noticeMail, $attachments)) {
                 $this->Projects->updateAll(array('status' => 13), array('id' => $project['project']['id']));   
                }              
            }
        }
        exit;
    }  
    /*
     * Use for project archive
     */
    function archive($id = null) {
        if ($this->Auth->user('role_id') == 2) {
            $project = $this->Projects->findById($id)->first();
        } elseif ($this->Auth->user('role_id') == 3) {
            $project = $this->Projects->findById($id)->contain('Users')->first();
            if ($project['user']['parent_id'] != $this->Auth->user('id')) {
                $this->Flash->error(__('Invalid project.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
            }
        } elseif ($this->Auth->user('role_id') == 4) {
            $project = $this->Projects->findByIdAndUserId($id, $this->Auth->user('id'))->first();
        }
        if (empty($project)) {
            $this->Flash->error(__('Invalid project.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        if ($this->Projects->updateAll(array('deleted' => 1), array('id' => $id))) {
            $this->Flash->success(__('Project successfully archived.'));
        } else {
            $this->Flash->error(__('Project could not be archive. Please, try again.'));
        }
        return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
    }
    /*
     * Use for project unarchive
     */
    function unarchive($id = null) {
        if ($this->Auth->user('role_id') == 2) {
            $project = $this->Projects->findById($id)->first();
        } elseif ($this->Auth->user('role_id') == 3) {
            $project = $this->Projects->findById($id)->contain('Users')->first();
            if ($project['user']['parent_id'] != $this->Auth->user('id')) {
                $this->Flash->error(__('Invalid project.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
            }
        } elseif ($this->Auth->user('role_id') == 4) {
            $project = $this->Projects->findByIdAndUserId($id, $this->Auth->user('id'))->first();
        }
        if (empty($project)) {
            $this->Flash->error(__('Invalid project.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        if ($this->Projects->updateAll(array('deleted' => 0), array('id' => $id))) {
            $this->Flash->success(__('Project successfully removed from archive list.'));
        } else {
            $this->Flash->error(__('Project could not be remove from archive list. Please, try again.'));
        }
        return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
    }
    /*
     * Use for project Documents verification
     */
    function verifyDocuments($type=null,$status=null,$project_id=null,$docu_id=null) {
        $project = $this->Projects->findById($project_id)->contain('Users.Contractors')->first();
        if (empty($project)) {
            $this->Flash->error(__('Invalid project.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
        }
        $projectDocuments = $this->ProjectDocuments->findByProjectIdAndId($project_id, $docu_id)->first();
        if (empty($projectDocuments)) {
            $this->Flash->error(__('Invalid project.'));
        } else {
            $new_status = 2;
            $status_title='rejected';
            if ($status == 'approved') {
                $new_status = 1;
                $status_title='accepted';
            }
            $projectDocumentsData[$type . '_status'] = $new_status;
            $documentTypeArr=array('signed_work_contract'=>'Signed Work Contract','signed_work_contract_modified'=>'Signed Work Contract (modified)','permit'=>'Permit','permission_to_operate'=>'Permission to Operate');
            $documentType=$documentTypeArr[$type];
            $projectDocuments = $this->ProjectDocuments->patchEntity($projectDocuments, $projectDocumentsData);
            if ($this->ProjectDocuments->save($projectDocuments)) {
                 /****************Send*Email*To*Contractor*************** */
                $applicationLink = Router::url(['controller' => 'Projects', 'action' => 'certificateCompletion', $project['id']], true);
                $applicationLink='<a href='.$applicationLink.'>'.$applicationLink.'</a>';
                $propertyCompleteAddress= $project['address'].', '.$project['city'].', '.$project['state'].' '.$project['zipcode'];
                $contractorMail = $this->SystemMails->findByEmailType('ProjectDocumentsStatusNotificationToContractor')->first()->toArray();
                $contractorMail['message'] = str_replace('[sitename]', Configure::read('Site.title'), $contractorMail['message']);                
                $contractorMail['message'] = str_replace('[PaceManager]', $this->Auth->user('name'), $contractorMail['message']);                
                $contractorMail['message'] = str_replace('[Customer]', $project['owner_name'], $contractorMail['message']);                
                $contractorMail['message'] = str_replace('[PropertyAddress]', $propertyCompleteAddress, $contractorMail['message']);
                $contractorMail['message'] = str_replace('[Project_ID]', $project['id'], $contractorMail['message']);
                $contractorMail['message'] = str_replace('[DocumentType]', $documentType, $contractorMail['message']);
                $contractorMail['message'] = str_replace('[Status]', $status_title, $contractorMail['message']);
                $contractorMail['message'] = str_replace('[ApplicationLink]', $applicationLink, $contractorMail['message']);
                $contractorMail['subject'] = str_replace('[Project_ID]', $project['id'], $contractorMail['subject']);                
                $contractorMail['subject'] = str_replace('[DocumentType]', $documentType, $contractorMail['subject']);                
                $contractorMail['subject'] = str_replace('[Status]', $status_title, $contractorMail['subject']);                
                $contractorMail['to'] = $project['user']['contractor']['email'];
                if ($this->sendEmail($contractorMail)) {}
                $this->Flash->success(__('Document successfully '.$status_title.'.'));
            } else {
                $this->Flash->error(__('An error occurred. Please, try again.'));
            }
        }
        return $this->redirect(['action' => 'certificateCompletion',$project_id]);
    }
    function changeContract($id = null) {       
        $project=$this->Projects->find('all',['contain'=>['ContractDetails','Users'],'conditions'=>['Projects.id'=>$id,'ContractDetails.status'=>1]])->first();      
      
        if (empty($project)) {
            $this->Flash->error(__('Invalid project.'));
            return $this->redirect(['controller'=>'Users','action' => 'dashboard']);
        }elseif ($this->Auth->user('role_id')==3 && $project['user']['parent_id'] != $this->Auth->user('id')) {
            $this->Flash->error(__('Invalid project.'));
            return $this->redirect(['controller'=>'Users','action' => 'dashboard']);
        } elseif ($project['status'] < 10) {
            $this->Flash->error(__('Project not eligible for certificate completion. Please wait for customer sign on contract.'));
            return $this->redirect(['controller'=>'Users','action' => 'certificateCompletion',$project['id']]);
        }
        
        $project_types = $this->ProjectTypes->find('all', ['conditions' => ['deleted' => 0], 'contain' => ['LoanTerms.Terms'], 'order' => ['name' => 'asc']])->toArray();
        $project_types = array_map(function($elem) {
            $elem['equipments'] = $this->Equipments->find('list', ['order' => ['manufacturer' => 'asc'], 'group' => 'Equipments.manufacturer', 'conditions' => ['Equipments.project_type_id' => $elem->id], 'keyField' => 'manufacturer', 'valueField' => 'manufacturer'])->toArray();
            $elem['term'] = $elem['loan_terms'][0]['term']['term'];
            unset($elem['loan_terms']);
            return $elem;
        }, $project_types);        
            $eq_info = array();
            $eq_data = $this->ContractDetails->findByProjectIdAndStatus($id, 1)->contain('EquipmentInformations')->first();
            foreach ($eq_data['equipment_informations'] as $eq) {
                $eq_info[$eq['project_type_id']] = array('manufacturer' => $eq['manufacturer'], 'model' => $eq['model'], 'sku' => $eq['sku'], 'qty' => $eq['qty'], 'amount' => $eq['amount']);
            }
       
        $loan_term = $this->Terms->find('list', ['order' => ['term' => 'asc'], 'keyField' => 'term', 'valueField' => 'name']);
        $this->set(compact('project', 'project_types', 'eq_info', 'loan_term'));
    }
    function fniProcess($customer_id=null){
            $this->viewBuilder()->layout(false);   
            $response=$this->Common->fniProcessApply($customer_id,$this);
            //pr($response); 
            exit();
    }
}
