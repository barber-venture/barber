<?php
$this->assign('title', 'Site Setting');
$this->Html->addCrumb('Dashboard',['controller' => 'Users', 'action' => 'dashboard']);
$this->Html->addCrumb('Site Setting');

use Cake\Core\Configure;
use Cake\I18n\Number;
?>
<?php echo  $this->Flash->render(); ?>


<div class="panel panel-default" data-widget='{"draggable": "false"}'>

    <div class="panel-heading">
        <h2>Site Setting</h2>
    </div>

    <?php echo $this->Form->create($SiteSetting, ['class' => 'form-horizontal siteSetting', 'type' => 'file']);
      $this->Form->unlockField('is_online');
      ?>
    <div class="panel-body">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Is Online:</label>
                    <div class="col-sm-8">
                        <label class="radio-inline icheck">
                            <?php
                            echo $this->Form->radio('is_online', Configure::read('SiteSetting.IsOnline'), array());
                            ?> 
                        </label>
                    </div>
                </div>
            </div> 
        </div>
        <div class="row"> 
            <div class="col-md-6">
                <div class="form-group">
                    <label for="focusedinput" class="col-sm-4 control-label">Site Name:</label>
                    <div class="col-sm-8">
                        <?php
                        echo $this->Form->input('site_name', [
                            'label' => false,
                            'div' => false,
                            'class' => 'form-control',
                            'empty' => 'Site Name'
                                ]
                        );
                        ?>                          
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="focusedinput" class="col-sm-4 control-label">Contact No</label>
                    <div class="col-sm-8"> 
                        <?php
                        echo $this->Form->input('site_hotline_no', array(
                            'type' => 'text',
                            'label' => false,
                            'div' => false,
                            'class' => 'form-control',
                            'placeholder' => 'Contact No'
                        ));
                        ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="focusedinput" class="col-sm-4 control-label">Contact Us Email</label>
                    <div class="col-sm-8">
                        <?php
                        echo $this->Form->input('contact_us_email', array(
                            ' type' => 'text',
                            'label' => false,
                            'div' => false,
                            'class' => 'form-control',
                            'placeholder' => 'Contuct Us Email'
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="focusedinput" class="col-sm-4 control-label">Info Email</label>
                    <div class="col-sm-8">
                        <?php
                        echo $this->Form->input('info_email', array(
                            ' type' => 'number',
                            'label' => false,
                            'div' => false,
                            'class' => 'form-control',
                            'placeholder' => 'Info Email'
                        ));
                        ?>

                    </div>
                </div>
            </div>
        </div> 

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="focusedinput" class="col-sm-4 control-label">No Reply Email</label>
                    <div class="col-sm-8">
                        <?php
                        echo $this->Form->input('no_reply_email', array(
                            ' type' => 'text',
                            'label' => false,
                            'div' => false,
                            'class' => 'form-control',
                            'placeholder' => 'No Reply Email'
                        ));
                        ?>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="focusedinput" class="col-sm-4 control-label">Record Per Page</label>
                    <div class="col-sm-8">
                        <?php
                        echo $this->Form->input('per_page_limit', array(
                            ' type' => 'text',
                            'label' => false,
                            'div' => false,
                            'class' => 'form-control',
                            'placeholder' => 'Record Per Page'
                        ));
                        ?>

                    </div>
                </div>
            </div>
        </div> 
        <?php
        $AdminFilePath = Configure::read('Site.AdminImages');
        $logoName = 'defualt.png';
        $faviconName = 'defualt.png';
        if ($SiteSetting['site_logo'] != '') {

            $site_logoPath = $AdminFilePath . $SiteSetting['site_logo'];

            if (file_exists($site_logoPath)) {
                $logoName = $SiteSetting['site_logo'];
            }
        }
        if ($SiteSetting['favicon'] != '') {

            $site_faviconPath = $AdminFilePath . $SiteSetting['favicon'];

            if (file_exists($site_faviconPath)) {
                $faviconName = $SiteSetting['favicon'];
            }
        }
         
        ?>


        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Site Logo</label>
                    <div class="col-sm-8">
                        <div class="fileinput fileinput-new" style="width: 100%;" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; height: 150px;">
                                <img src="<?php echo SITE_FULL_URL .'uploads/sites/'.$logoName ?>" />
                            </div>
                            <div>
                              
                                <span class="btn btn-default btn-file">
                                    <span class="fileinput-new">Select Logo</span>
                                    <span class="fileinput-exists">Select Logo</span>
                                    <?php
                                    
                                    echo $this->Form->file('site_logos', array(
                                        'label' => false,
                                        'div' => false,
                                     
                                    ));
                                    ?>
                                </span>


                                <?php
                                echo $this->Form->input('site_logo', array(
                                    'type' => 'hidden',
                                    'label' => false,
                                    'div' => false
                                ));
                                ?>

                            </div>
                        </div>
                    </div> 
                </div>                 
            </div>
            <div class="col-md-6">

                <div class="form-group">
                    <label class="col-sm-4 control-label">Site Favicon</label>
                    <div class="col-sm-8">
                        <div class="fileinput fileinput-new" style="width: 100%;" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail mb20" data-trigger="fileinput" style="width: 100%; height: 150px;">
                                <img src="<?php echo SITE_FULL_URL .'uploads/sites/'.$faviconName ?>" />
                            </div>
                            <div>
                               
                                <span class="btn btn-default btn-file">
                                    <span class="fileinput-new">Select Favicon</span>
                                    <span class="fileinput-exists">Select Favicon</span>
                                    <?php
                                    echo $this->Form->file('favicons', array(
                                        'label' => false,
                                        'div' => false,
                                    ));
                                    ?>

                                    <?php
                                    echo $this->Form->input('favicon', array(
                                        'type' => 'hidden',
                                        'label' => false,
                                        'div' => false
                                    ));
                                    ?>
                                </span>

                            </div>
                        </div>
                    </div> 
                </div>   

            </div>
        </div>

        <div class="row">
            <div class="col-md-6">

                <div class="form-group">
                    <label for="focusedinput" class="col-sm-4 control-label">Site Address</label>
                    <div class="col-sm-8">
                        <?php
                        echo $this->Form->input('site_address', array(
                            ' type' => 'text',
                            'label' => false,
                            'div' => false,
                            'class' => 'form-control',
                            'placeholder' => 'Site Address'
                        ));
                        ?>

                    </div>
                </div>                 
            </div> 
            <div class="col-md-3">
                <div class="form-group">
                    <label for="focusedinput" class="col-sm-4 control-label">Latitude</label>
                    <div class="col-sm-8">
                        <?php
                        echo $this->Form->input('lat', array(
                            ' type' => 'text',
                            'label' => false,
                            'div' => false,
                            'class' => 'form-control',
                            'placeholder' => 'Latitude',
                            'readonly' => true
                        ));
                        ?>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="focusedinput" class="col-sm-4 control-label">Longitude</label>
                    <div class="col-sm-8">
                        <?php
                        echo $this->Form->input('lng', array(
                            ' type' => 'text',
                            'label' => false,
                            'div' => false,
                            'class' => 'form-control',
                            'placeholder' => 'Longitude',
                            'readonly' => true
                        ));
                        ?>

                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Site Map</label>
                    <div class="col-sm-10">
                        <div id="map_canvas" style="height: 300px;width:100%;margin:0px;padding:0px;"></div>
                    </div> 
                </div>                 
            </div>

        </div>


        <div class="stepy-navigator panel-footer text-center">

<?php echo  $this->Form->button('Save', array('class' => 'btn btn-primary')); ?>
            <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'channelPartnerList']); ?>" class="btn btn-inverse"> Cancel </a>

        </div> 


    </div>



    <?php echo  $this->Form->end() ?>

    <?php
    $key = Configure::read('Site.googlemap_key');
    $this->Html->script(['adminSetting', 'http://maps.google.com/maps/api/js?libraries=places&region=uk&language=en&sensor=true&key='.$key], ['block' => true]);
    $this->Html->scriptStart(['block' => true]);
    ?>
    $(window).load(function () {

    $(function () {
    var activeInfoWindow;
    var lat = '<?php echo $SiteSetting['lat'];?>',
     lng = '<?php echo $SiteSetting['lng'];?>',
    latlng = new google.maps.LatLng(lat, lng),
    image = SITE_URL + '/img/mappin.png';

    //zoomControl: true,
    //zoomControlOptions: google.maps.ZoomControlStyle.LARGE,

    var mapOptions = {
    center: new google.maps.LatLng(lat, lng),
    zoom: 13,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    panControl: true,
    panControlOptions: {
    position: google.maps.ControlPosition.TOP_RIGHT
    },
    zoomControl: true,
    zoomControlOptions: {
    style: google.maps.ZoomControlStyle.LARGE,
    position: google.maps.ControlPosition.TOP_left
    }
    },
    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions),
    marker = new google.maps.Marker({
    position: latlng,
    map: map,
    icon: image,
    draggable: true,
    });

    var input = document.getElementById('site-address');
    var autocomplete = new google.maps.places.Autocomplete(input, {
    types: ["geocode"]
    });

    autocomplete.bindTo('bounds', map);
    var infowindow = new google.maps.InfoWindow();

    google.maps.event.addListener(autocomplete, 'place_changed', function (event) {
    infowindow.close();
    var place = autocomplete.getPlace();
    if (place.geometry.viewport) {
    map.fitBounds(place.geometry.viewport);
    } else {
    map.setCenter(place.geometry.location);
    map.setZoom(17);
    }

    moveMarker(place.name, place.geometry.location);
    $('#lat').val(place.geometry.location.lat());
    $('#lng').val(place.geometry.location.lng());

    });
    google.maps.event.addListener(marker, 'dragend', function (event) {
    $('#lat').val(event.latLng.lat());
    $('#lng').val(event.latLng.lng());
    infowindow.close();
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({
    "latLng": event.latLng
    }, function (results, status) {
    console.log(results, status);
    if (status == google.maps.GeocoderStatus.OK) {
    console.log(results);
    var lat = results[0].geometry.location.lat(),
    lng = results[0].geometry.location.lng(),
    placeName = results[0].address_components[0].long_name,
    latlng = new google.maps.LatLng(lat, lng);

    moveMarker(results[0].formatted_address, event.latLng);
    $("#site-address").val(results[0].formatted_address);
    //                        $("#add_center").valid();
    }
    });
    });

    function moveMarker(placeName, latlng) {

    marker.setIcon(image);
    marker.setPosition(latlng);
    infowindow.setContent(placeName);
    map.setCenter(latlng);
    google.maps.event.addListener(marker, 'click', function () {

    //Close active window if exists - [one might expect this to be default behaviour no?]				
    if (activeInfoWindow != null)
    activeInfoWindow.close();

    // Open InfoWindow - on click 
    infowindow.open(map, marker);

    // Store new open InfoWindow in global variable
    activeInfoWindow = infowindow;
    });
    //infowindow.open(map, marker);

    }
    });
    });
    <?php $this->Html->scriptEnd(); ?>

    <?php echo  $this->Common->loadJsClass('MasterSiteSetting'); ?>

