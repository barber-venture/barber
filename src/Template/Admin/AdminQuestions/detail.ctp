<?php 
$this->assign('title', 'Profile');
use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard',['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Questions',['controller' => 'admin_questions', 'action' => 'index']);
$this->Html->addCrumb('Detail','');

?>
<div data-widget-group="group1">
    <?php echo $this->Flash->render(); ?>
    <div class="row">
        
        <div class="col-sm-9">
            <div class="tab-content">               

                <div class="tab-pane active" id="tab-about">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2>Question Detail</h2>
                        </div>
                        <div class="panel-body">

                            <div class="about-area">
                                <div class="table-responsive">
                                    <table class="table about-table">
                                        <tbody>
                                            								           
                                            <tr>
                                                <th width="20%">Question</th>
                                                <td><?php echo $question['question']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>options</th>
                                                <td>
													<?php
													$options = Configure::read("Question.".$question['id']);
													if($options !== null){
														
														if($question['id'] == 12){
															echo 'Min Height: ' . $options[0] . ' cm<br>';
															echo 'Max Height: ' . $options[1] . ' cm';
														}
														else
														if($question['id'] == 24){
															echo 'Min Age: ' . $options[0] . ' years<br>';
															echo 'Max Age: ' . $options[1] . ' years';
														}
														else{
															foreach($options as $k => $option){
																echo $k+1; echo '. ' . $option.'<br>';
															}
														}
														
														
													}else{
														echo 'No Options Found.';													
													} ?>
												</td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- .tab-content -->
        </div><!-- col-sm-8 -->
    </div>
</div>