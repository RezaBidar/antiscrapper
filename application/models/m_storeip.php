<?php 
class m_storeip extends MY_Model{
	//cels neme
	const COLUMN_ID = 'sip_id';
	const COLUMN_IP = 'sip_ip';
	const COLUMN_COLOR = 'sip_color';
	const COLUMN_WRONG_CAPTCHA = 'sip_wrongcaptcha';
	const COLUMN_DATE = 'sip_date';

	const COLOR_WHITE = '1' ;
	const COLOR_BLACK = '2' ;
	const COLOR_GRAY = '3' ;

	
	protected $_table_name = 'storeip';
	protected $_primary_key = 'sip_id';

	/**
	 * return color of ip
	 *
	 * if ip didnt exists in storeip table then return null 
	 * @param  string 	$ip User Ip
	 * @return complex   	
	 */
	public function get_ip_color($ip)
	{
		$this->clear_expire_blocks();
		$this->db->where(self::COLUMN_IP , $ip);
		$ip = $this->get(NULL,TRUE);
		return ($ip != NULL) ? $ip->{self::COLUMN_COLOR} : NULL ;
	}

	/**
	 * plus one wrongcaptcha column
	 *
	 * it add one to wrongcaptcha column
	 * and if wrongcaptcha was more than {max_wrong_captcha} 
	 * then change colore of it to black
	 * @param  string $ip User Ip
	 */
	public function plus_wrong_captcha($ip)
	{

		$CI =& get_instance();
		$CI->load->model('m_setting');

		$stored_ip = $this->get_by(array(self::COLUMN_IP => $ip) , TRUE);
		if($stored_ip != NULL)
		{
			$wrong_captcha = ($stored_ip->{self::COLUMN_WRONG_CAPTCHA} == NULL) ? 0 : $stored_ip->{self::COLUMN_WRONG_CAPTCHA} ;
			$max_wrong_captcha = $CI->m_setting->get_max_wrong_captcha();

			if($wrong_captcha >= $max_wrong_captcha)
			{
				//change color it to black
				// and set new time
				$data = array(
					self::COLUMN_COLOR => self::COLOR_BLACK ,
					self::COLUMN_DATE => date('Y-m-d H:i:s'),
					self::COLUMN_WRONG_CAPTCHA => NULL
					);
				$this->save($data,$stored_ip->{self::COLUMN_ID});
			}
			else
			{
				//update wrong captcha
				$data = array(
					self::COLUMN_WRONG_CAPTCHA => ($wrong_captcha + 1)
					);
				$this->save($data,$stored_ip->{self::COLUMN_ID});
			}
		}
	}

	/**
	 * delete gray ip
	 *
	 * it used for when user enter correct captcha
	 * 
	 * @param  string $ip User IP
	 */
	public function delete_gray($ip)
	{
		$data = array(
			self::COLUMN_COLOR => self::COLOR_GRAY ,
			self::COLUMN_IP => $ip
			);
		$this->delete($data);
	}

	/**
	 * remvoe expire black and gray from table
	 *
	 * it remove all blacks and grays that 
	 * their date is less than now() - {block_time}
	 */
	public function clear_expire_blocks()
	{
		$CI =& get_instance();
		$CI->load->model('m_setting');
		$max_block_time = $CI->m_setting->get_block_time() ;
		$expire_time = date("Y-m-d H:i:s", strtotime("-{$max_block_time} minutes", time()));
		
		$this->db->where_in(self::COLUMN_COLOR , array(self::COLOR_BLACK,self::COLOR_GRAY));
		$this->delete(array(self::COLUMN_DATE . ' <' => $expire_time));
	}

	/**
	 * get white list table
	 *
	 * it use for add_to_whitelist controller
	 * @return string  	Whitelist in table format
	 */
	public function get_whitelist_table()
	{
		$this->db->where(self::COLUMN_COLOR,self::COLOR_WHITE);
		$ips = $this->get();
		$output = '<table class="table table table-hover table-striped table-bordered">' ;
		$output .= '<thead><tr><td>IP</td></tr></thead>';
		$output .= '<tbody>' ;
		foreach ($ips as $ip) 
		{
			$output .= 	'<tr><td>' . $ip->{self::COLUMN_IP} . '</td></tr>' ;
		}
		$output .= '</tbody>' ;
		$output .= '</table>' ;
		return $output ;
	}

	/**
	 * add this ip to white list
	 * @param  string $ip 
	 * @return int 			id of inserted row
	 */
	public function add_to_whitelist($ip)
	{
		$ip_obj = $this->get_by(array(self::COLUMN_IP => $ip) , TRUE) ;
		if($ip_obj == NULL)
		{
			$data = array(
				self::COLUMN_IP => $ip ,
				self::COLUMN_COLOR => self::COLOR_WHITE ,
				);
			return $this->save($data);
		}
		else
		{
			$data = array(
				self::COLUMN_COLOR => self::COLOR_WHITE ,
				);
			return $this->save($data,$ip_obj->{self::COLUMN_ID});	
		}
	}

}

 ?>