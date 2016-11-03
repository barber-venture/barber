<?php
use Cake\Core\Configure; ?>
<div class="grid-popup">
    <div class="grid-sizer"></div>
    <div class="grid-item">
        <?php
        $image = 'no-user.png';
       
        if (!empty($user['user_detail'])) {
            
            if ($user['user_detail']['profile_image'] != '') {
               
                $url = WWW_ROOT . 'uploads' . DS . 'users' . DS . $user['user_detail']['profile_image'];
                $image = $user['user_detail']['profile_image'];
               
            }
        }
        echo $this->Html->image('./../uploads/users/' .$image);
        ?>
    </div>
    <div class="grid-item">
        <div id="exTab1" class="view-profile popup" style="width: 100%;">
            <div class="profile-verification-section">
                <div class="rows">
                    <div class="profile-picture-block">
                        <div class="pop-container">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="profilePopupDetail">
                                        <h2><?php
										if($user['role_id'] == 2)
											echo $user['user_detail']['nike_name'] . ', ' . date_diff(date_create($user['user_detail']['dob']), date_create('today'))->y, "\n";
										else
											echo $user['name'] ;
										if($this->request->Session()->read('Auth.User.user_type') == 4){
											$margin = ($user['role_id'] == 2) ? '10px' : '30px';
										?>										
											<span style="margin-left:<?php echo $margin;?>"><a  style="font-size: 24px;" href="javascript:void(0);" data-to_user_id="<?php echo $user['id']; ?>" id="freelancer_chat"><i class="fa fa-comment"></i></a></span>
										<?php
										}
										//echo $this->Html->link('Chat', ['controller' => 'users', 'action' => 'liveChat', $user['id']]);?>
										<?php if(($this->request->session()->read('Auth.User.role_id') == 2) && ($this->request->session()->read('Auth.User.user_type') == 4) && ($user['role_id'] == 2)){ ?>
											<span class="pull-right"><a href="#" id="suggest">Suggest a Friend</a></span>
										<?php } ?>
										</h2>
                                        <div class="profilePopupInner">
                                            <div class="from-location">
                                                <a href="#">
                                                    <?php
                                                    if ($user['user_detail']['address'] != '') {
                                                        echo $this->Html->image('loaction-icon.png');
                                                        ?>
                                                        <span>From <?php echo $user['user_detail']['address']; ?></span>													<?php } ?>
                                                </a>
												<?php if($user['role_id'] == 3){ ?>
												&nbsp;&nbsp;&nbsp;
												<a class="camera"><?php echo $this->Html->image('camera-icon.png');?>
													<span><?php echo $user['user_detail']['camera']; ?></span>		
												</a>
												<?php } ?>
                                            </div>
											<?php if($user['role_id'] == 2){ ?>
                                            <div class="detail-tabs-block">
                                                <dl class="nav nav-pills resp-tabs-list">
                                                    <dt class="resp-tab-item active"><a href="#1a" data-toggle="tab">Profile Details</a></dt>
                                                    <dt class="resp-tab-item"><a href="#2a" data-toggle="tab">Details</a></dt>
                                                </dl>
                                                <div class="tab-content clearfix">
                                                    <div class="tab-pane active" id="1a">
                                                        <div class="profile-details" id="boxscroll3">
                                                            <p class="animated3 fadeInUp"><?php echo $user['user_detail']['about_me']; ?></p>
                                                            <div class="name-block">
                                                                <?php if (!empty($user['user_tags'])) { ?>
                                                                    <h3>Interests</h3>
                                                                    <h4>
                                                                        <?php
                                                                        foreach ($user['user_tags'] as $tags) {
                                                                            echo '#' . $tags->name . ' ';
                                                                        }
                                                                        ?>
                                                                    </h4>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="2a">
                                                        <div class="details" id="boxscroll2">
                                                            <div class="animated3 fadeInUp">
                                                                <div class="profile-detail">
                                                                    <?php $options = Configure::read('Question'); ?>
                                                                    <dl>
                                                                        <?php
                                                                        if ($user['user_detail']['interested_in_gender'] != '') {
                                                                            ?>
                                                                            <dt>
                                                                                <div class="img"><img src="<?php echo SITE_FULL_URL; ?>/img/interested_icon.png"></div>
                                                                                <div class="info">
                                                                                    <h3>Looking for</h3>
                                                                                    <h3><?php
                                                                                        $act = explode(',', $user['user_detail']['interested_in_gender']);
                                                                                        foreach ($options['interested_in_gender'] as $ke => $interested_in_gender) {
                                                                                            if (in_array($ke, $act))
                                                                                                echo $interested_in_gender;
                                                                                            if (in_array($ke + 1, $act) && count($act) == 2)
                                                                                                echo ' and ';
                                                                                        }
                                                                                        if (($user['user_detail']['interested_in_age_from'] > 0) && ($user['user_detail']['interested_in_age_to'] > 0)) {
                                                                                            echo ' (' . $user['user_detail']['interested_in_age_from'] . '-' . $user['user_detail']['interested_in_age_to'] . ')';
                                                                                        }
                                                                                        ?></h3>
                                                                                </div>
                                                                            </dt>
                                                                            <?php
                                                                        }
                                                                        if ($user['user_detail']['interested_in_activity'] != '') {
                                                                            if ($user['user_detail']['interested_in_activity'] != '0') {
                                                                                ?>
                                                                                <dt>
                                                                                    <div class="img"><img src="<?php echo SITE_FULL_URL; ?>img/icon-filters.png"></div>
                                                                                    <div class="info">
                                                                                        <h3>Intrested In</h3>
                                                                                        <h3><?php
                                                                                            $act = explode(',', $user['user_detail']['interested_in_activity']);
                                                                                            $interested_in_activity_arr = array();
                                                                                            foreach ($options['interested_in_activity'] as $ke => $interested_in_activity) {
                                                                                                if (in_array($ke, $act))
                                                                                                    array_push($interested_in_activity_arr, $interested_in_activity);
                                                                                            }
                                                                                            echo $look = implode(', ', $interested_in_activity_arr);
                                                                                            ?></h3>
                                                                                    </div>
                                                                                </dt>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        if ($user['user_detail']['relationship_status'] > 0) {
                                                                            ?>
                                                                            <dt>
                                                                                <div class="img"><img src="<?php echo SITE_FULL_URL; ?>img/icon-relationship.png"></div>
                                                                                <div class="info">
                                                                                    <h3>Relationship Status</h3>
                                                                                    <h3><?php echo $options['relationship_status'][$user['user_detail']['relationship_status']] ?></h3>
                                                                                </div>
                                                                            </dt>
                                                                            <?php
                                                                        }
                                                                        if (($user['user_detail']['height'] > 0) || ($user['user_detail']['body_type'] > 0)) {
                                                                            ?>
                                                                            <dt>
                                                                                <div class="img"><img src="<?php echo SITE_FULL_URL; ?>img/icon-appearance.png"></div>
                                                                                <div class="info">
                                                                                    <h3>Appearance</h3>
                                                                                    <h3><?php
                                                                                        if ($user['user_detail']['height'] > 0)
                                                                                            echo $user['user_detail']['height'] . ' cm, ';
                                                                                        echo $options['body_type'][$user['user_detail']['body_type']];
                                                                                        ?></h3>
                                                                                </div>
                                                                            </dt>
                                                                            <?php
                                                                        }
                                                                        if ($user['user_detail']['children'] > 0) {
                                                                            ?>
                                                                            <dt>
                                                                                <div class="img"><img src="<?php echo SITE_FULL_URL; ?>img/icon-children.png"></div>
                                                                                <div class="info">
                                                                                    <h3>Children</h3>
                                                                                    <h3><?php echo $options['children'][$user['user_detail']['children']] ?></h3>
                                                                                </div>
                                                                            </dt>
                                                                            <?php
                                                                        }
                                                                        if ($user['user_detail']['religion'] > 0) {
                                                                            ?>
                                                                            <dt>
                                                                                <div class="img"><img src="<?php echo SITE_FULL_URL; ?>img/icon-religion.png"></div>
                                                                                <div class="info">
                                                                                    <h3>Religion</h3>
                                                                                    <h3><?php echo $options['religion'][$user['user_detail']['religion']] ?></h3>
                                                                                </div>
                                                                            </dt>
                                                                            <?php
                                                                        }
                                                                        if ($user['user_detail']['living_situation'] > 0) {
                                                                            ?>
                                                                            <dt>
                                                                                <div class="img"><img src="<?php echo SITE_FULL_URL; ?>img/icon-living-situation.png"></div>
                                                                                <div class="info">
                                                                                    <h3>Living situation</h3>
                                                                                    <h3><?php echo $options['living_situation'][$user['user_detail']['living_situation']] ?></h3>
                                                                                </div>
                                                                            </dt>
                                                                            <?php
                                                                        }
                                                                        if ($user['user_detail']['education'] > 0) {
                                                                            ?>
                                                                            <dt>
                                                                                <div class="img"><img src="<?php echo SITE_FULL_URL; ?>img/icon-education.png"></div>
                                                                                <div class="info">
                                                                                    <h3>Education</h3>
                                                                                    <h3><?php echo $options['education'][$user['user_detail']['education']] ?></h3>
                                                                                </div>
                                                                            </dt>
                                                                            <?php
                                                                        }
                                                                        if ($user['user_detail']['profession'] > 0) {
                                                                            ?>
                                                                            <dt>
                                                                                <div class="img"><img src="<?php echo SITE_FULL_URL; ?>img/icon-profession.png"></div>
                                                                                <div class="info">
                                                                                    <h3>Profession</h3>
                                                                                    <h3><?php echo $options['profession'][$user['user_detail']['profession']] ?></h3>
                                                                                </div>
                                                                            </dt>
                                                                            <?php
                                                                        }
                                                                        $languageVal = explode(',', $user['user_detail']['language']);

                                                                        if ($user['user_detail']['language'] != '') {
                                                                            ?>
                                                                            <dt>
                                                                                <div class="img"><img src="<?php echo SITE_FULL_URL; ?>img/language_icon.png"></div>
                                                                                <div class="info">
                                                                                    <h3>Languages</h3>
                                                                                    <h3><?php
                                                                                        $lang_arr = array();
                                                                                        foreach ($language as $k => $lang) {
                                                                                            if (in_array($k, $languageVal))
                                                                                                array_push($lang_arr, $lang);
                                                                                        }
                                                                                        echo $lang_arr_data = implode(', ', $lang_arr);
                                                                                        ?></h3>
                                                                                </div>
                                                                            </dt>
                                                                        <?php }
                                                                        ?>                                          
                                                                    </dl>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
										<?php } ?>
                                        </div>
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>       
            </div>   
        </div>
    </div>  
    <?php
	/*
    foreach ($user['album_images'] as $i => $imgs) {
        if ($imgs['image_name'] != $user['user_detail']['profile_image']) {
            ?>
            <div class="grid-item">
                <?php
                $url = WWW_ROOT . 'uploads' . DS . 'users' . DS . $imgs['image_name'];
                if (!file_exists($url))
                    $imgs['image_name'] = 'no-user.png';
                echo $this->Html->image('./../uploads/users/' . $imgs['image_name']);
                ?>	
            </div>
            <?php
        }
    }
    */	
	
	$albumlimit = ($user['user_type'] == 1) ? 1 : 4;
	$albumcount = 1;
	if (!empty($user['albums'])) {

		foreach ($user['albums'] as $lkey => $lvalue) {
			if($albumcount <= $albumlimit){
				$albumcount++;
				if (count($lvalue['album_images']) > 0) {
					foreach ($lvalue['album_images'] as $iKey => $ivalue) {
						
						if ($ivalue['image_name'] != $user['user_detail']['profile_image']) {
							?>
						<div class="grid-item">
							<?php
							$url = WWW_ROOT . 'uploads' . DS . 'users' . DS . $ivalue['image_name'];
							if (!file_exists($url))
								$ivalue['image_name'] = 'no-user.png';
							echo $this->Html->image('./../uploads/users/' . $ivalue['image_name']);
							?>	
						</div>
					<?php
						}
					}
				}
			}
		}
	}
    ?>
</div>