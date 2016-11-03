<?php
$this->assign('title', 'View Detail');

use Cake\Core\Configure;

$this->Html->addCrumb('Dashboard', ['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Supports', ['controller' => 'Supports', 'action' => 'index']);
$this->Html->addCrumb('View Detail');
?>

<div data-widget-group="group1" >
    <div class="row">
        <div class="col-sm-12">

        </div>

        <div class="tab-pane">

            <div class="panel">

                <div class="panel-heading">
                    <h2><?php
                        echo 'View Detail';
                        ?></h2>
                </div>
                <div class="panel-body">
                    <?php echo $this->Flash->render(); ?>
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label"><?php echo __('Email') ?></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo h($support->email) ?>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label"><?php echo __('Subject') ?></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo h($support->subject) ?>
                                </div>
                            </div>
                        </div>

                    </div>



                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label"><?php echo __('Attachment') ?></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php
                                    $companyFilePath = Configure::read('Site.attachment');


                                    if ($support->attachment != '') {

                                        $filePath = $companyFilePath . $support->attachment;

                                        if (file_exists($filePath)) {
                                            ?>
                                            <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'download', $support->attachment]); ?>"     class="btn btn-success"> <i class="fa fa-download"></i></a>

                                            <?php
                                        } else {
                                            echo "Not any attachment";
                                        }
                                    } else {
                                        echo "Not any attachment";
                                    }
                                    ?>


                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label"><?php echo __('Access Via') ?></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php
                                    $AccessVia = Configure::read('AccessVia');
                                    echo $AccessVia[$this->Number->format($support->access_via)]
                                    ?>

                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label"><?php echo __('Support Category') ?></label>
                                <div class="col-sm-8 tabular-border">



                                    <?php
                                    $SupportCategory = Configure::read('SupportCategory');
                                    echo $SupportCategory[$this->Number->format($support->support_category)]
                                    ?>

                                </div>
                            </div>
                        </div>



                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label"><?php echo __('Message') ?></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo $this->Text->autoParagraph(h($support->message)); ?>

                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="form-name" class="col-sm-4 control-label"><?php echo __('Posted On') ?></label>
                                <div class="col-sm-8 tabular-border">
                                    <?php echo h($support->created) ?>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2">   

                            <a title="Replay" id="replay"  href="javascript:void(0)" class="btn btn-primary" data-id="<?php echo $support->id ?>">
                                <i class="ti ti-share"></i>&nbsp;&nbsp;Replay
                            </a>
                            <a class="btn-default btn" href="<?php echo $this->Url->build(['controller' => 'Supports', 'action' => 'index']); ?>">Back</a>
                        </div>
                    </div>
                </div>

            </div> 
        </div><!-- col-sm-8 -->
    </div>
</div> 




<div aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="UserDetailModel" class="modal fade "  >
    <div class="modal-backdrop fade in"></div>
    <div class="modal-dialog">
        <div class="modal-content modal-body-userDetail">



        </div>  
    </div> 
</div>

<?php echo $this->Common->loadJsClass('Master'); ?>
      