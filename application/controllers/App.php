<?php 
class App extends CI_Controller{

	/**
	 * Scrapper preventer
	 *
	 * it check proccess ip with an algorithm
	 * if ip was in black list it show error
	 * if ip was in gray list it show captcha
	 * if ip was in white list it show requested page without any process
	 */
	public function index()
	{
		$this->load->model('m_storeip');
		$this->load->model('m_captcha');
		$this->load->model('m_setting');
		$this->load->model('m_visitors');
		
		//get app status
		$app_status = $this->m_setting->get_app_status();
		//if status be up then it work normally
		if($app_status == m_setting::APP_STATUS_UP)
		{
			//get user ip address
			$ip = $this->input->ip_address() ;
			//get ip color if color be in storeip table
			$ip_color = $this->m_storeip->get_ip_color($ip);
			if($ip_color != NULL)
			{	
				//if ip is in black list user get access denied
				if($ip_color == m_storeip::COLOR_BLACK)
				{
					die('You Are Block !!!') ;
				}
				//if ip is in gray list visitor get captcha
				else if($ip_color == m_storeip::COLOR_GRAY)
				{
					if(isset($_POST['cp-submit']))
					{
						if(!$this->m_captcha->verify($_POST['cp-response'],$ip) )
						{
							echo 'Captcha was incorrect.Retry';
							echo $this->m_captcha->get_captcha_tag();
							return ;
						}
						
					}
					else
					{
						//show captcha
						echo $this->m_captcha->get_captcha_tag();
						return ;
					}
				}
				//else color == white -> do nothing

			}
			else
			{
				//save visitor ip
				$this->m_visitors->visit($ip);
			}
		}
		else if($app_status == m_setting::APP_STATUS_MAINTENANCE)
			die('Site is down for maintenance .') ;
		//else m_setting::APP_STATUS_DESABLE

		@include('../test_site/' . $_GET['url']) ;
		
	}
}
