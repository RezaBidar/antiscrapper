<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * captcha table model
 * 
 * @package CaptchaWS       
 */
class m_captcha extends My_Model{

	const COLUMN_ID 	= 'cp_id' ;
	const COLUMN_TIME 	= 'cp_time' ;
	const COLUMN_IP 	= 'cp_ip_address' ;
	const COLUMN_WORD 	= 'cp_word' ;

	protected $_table_name = 'captcha';
	protected $_primary_key = 'cp_id';

	const CAPTCHA_EXPIRATION = 600 ;
	const CAPTCHA_FOLDER = './captcha/' ;

	/**
	 * delete expired captcha from databse
	 */
	public function delete_expire()
	{
		$expiration = time() - self::CAPTCHA_EXPIRATION; // Two hour limit
		$this->db->where(self::COLUMN_TIME . ' < ', $expiration)->delete($this->_table_name);

	}

	/**
	 * get captcha form
	 * 
	 * @return string captcha html tag
	 */
	public function get_captcha_tag()
	{
		$CI =& get_instance();
		$CI->load->helper('captcha');
		$CI->load->helper('path');
		
		$vals = array(
	        'img_path'      => self::CAPTCHA_FOLDER,
	        'img_url'       => base_url() . self::CAPTCHA_FOLDER,
	        'expiration'	=> self::CAPTCHA_EXPIRATION,
	        'colors'        => array(
	                'background' => array(255, 100, 255),
	                'border' => array(255, 255, 255),
	                'text' => array(0, 0, 0),
	                'grid' => array(255, 40, 40)
	        )
		);

		$cap = create_captcha($vals);
		$output = '<html><body>';
		$output .= '<form action="#" method="post">';
		$output .= $cap['image'] ;
		$output .= '<br/><input type="text" name="cp-response"/>' ;
		$output .= '<br/><input type="submit" name="cp-submit"/>' ;
		$output .= '</form>';
		$output .= '</body></html>';

		$data = array(
	        self::COLUMN_TIME  => $cap['time'],
	        self::COLUMN_IP    => $this->input->ip_address(),
	        self::COLUMN_WORD  => strtolower($cap['word']) ,
		);
		$this->delete_expire();
		$this->save($data);

		return $output ;

	}

	
	/**
	 * check user response was correct or not
	 *
	 * if response be correct it remove ip from graylist
	 * if not , then it plus wrongcaptcha column in storeip table
	 * @param  string 	$response 
	 * @param  string 	$ip       
	 * @return boolean           
	 */
	public function verify($response,$ip)
	{
		$CI =& get_instance();
		$CI->load->model('m_storeip');

		// First, delete old captchas
		$this->delete_expire();

		// Then see if a captcha exists:
		$this->db->where(self::COLUMN_WORD,strtolower($response));
		$this->db->where(self::COLUMN_IP , $ip);
		$exists = $this->get(NULL,TRUE);
		if($exists != NULL)
		{
			//delete gray
			$CI->m_storeip->delete_gray($ip);
			return TRUE ;
		}
		else
		{
			//plus one wrong captcha column
			$CI->m_storeip->plus_wrong_captcha($ip);
			return FALSE ;
		}

	}

	

}

?>