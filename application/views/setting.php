<h2>Setting page</h2>
<?php 

echo btform::form_open();
echo btform::form_input('Max request limit',array('name'=>'max_request'),$max_request) ;
echo btform::form_input('Min request time limit (minutes)',array('name'=>'max_request_time'),$max_request_time) ;
echo btform::form_input('Max wrong answer to captcha',array('name'=>'max_wrong_captcha'),$max_wrong_captcha) ;
echo btform::form_input('Block time (minutes)',array('name'=>'block_time'),$block_time) ;
$ar = array(
	m_setting::APP_STATUS_UP => 'app work normally' , 
	m_setting::APP_STATUS_DISABLE => 'disable app ( all requests will be accept )' , 
	m_setting::APP_STATUS_MAINTENANCE => 'site maintenance ( all requests will be reject )' , 
	);
echo btform::form_select('App status','app_status',$ar,$app_status,'class="form-control"') ;
echo btform::form_submit(array('name'=>'submit'), 'submit');
echo btform::form_close();

echo '<hr/>';

echo '<a href="' . site_url('setting/add_to_whitelist') . '">Click Here See WhiteList Or add to white List </a>' ;
