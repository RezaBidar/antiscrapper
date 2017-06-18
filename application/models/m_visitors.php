<?php 
class m_visitors extends MY_Model{
	//cels neme
	const COLUMN_ID = 'vst_id';
	const COLUMN_IP = 'vst_ip';
	const COLUMN_DATE = 'vst_date';

	
	protected $_table_name = 'visitors';
	protected $_primary_key = 'vst_id';

	/**
	 * visit operation
	 *
	 * add ip and time to visitors table
	 * and check if user request was more than max_request_limit 
	 * then push it to storeip table with gray color
	 * @param  string $ip User Ip
	 */
	public function visit($ip)
	{
		//clear olds
		$this->clear_expire_ip() ;
		//add ip
		$data = array(self::COLUMN_IP => $ip);

		$this->save($data);
		
		//if max request is over then change color it to gray
		$this->check_is_gray($ip);

	}

	/**
	 * delete expire ip
	 *
	 * delete all rows that their date is before now() - {max_request_time}
	 * @return [type] [description]
	 */
	public function clear_expire_ip()
	{
		$CI =& get_instance();
		$CI->load->model('m_setting');
		$max_request_time = $CI->m_setting->get_max_request_time() ;
		$expire_time = date("Y-m-d H:i:s", strtotime("-{$max_request_time} minutes", time()));
		$this->delete(array(self::COLUMN_DATE . ' <' => $expire_time));
		
	}

	/**
	 * check ip should be gray or not
	 *
	 * it compare user request number with max request number
	 * and if user request was more , then push it to storeip table with gray color
	 * @param  String $ip User Ip
	 */
	public function check_is_gray($ip)
	{
		$CI =& get_instance();
		$CI->load->model('m_setting');
		$CI->load->model('m_storeip');
		$max_request = $CI->m_setting->get_max_request() ;
		$visits = $this->get_by(array(self::COLUMN_IP));

		if(sizeof($visits) >= $max_request)
		{
			//add this ip to storeip table
			$data = array(
				m_storeip::COLUMN_COLOR => m_storeip::COLOR_GRAY ,
				m_storeip::COLUMN_IP => $ip ,
				m_storeip::COLUMN_WRONG_CAPTCHA => '0'
				);
			$CI->m_storeip->save($data);
			///delete this ip from visit table
			$this->delete(array(self::COLUMN_IP => $ip));
		}

	}

}

 ?>