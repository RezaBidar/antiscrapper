<?php 
class m_setting extends MY_Model{
	//cels neme
	const COLUMN_ID = 'sng_id';
	const COLUMN_NAME = 'sng_name';
	const COLUMN_VALUE = 'sng_value';

	//defaults values
	const DEFAULT_MAX_REQUEST = 15 ;
	const DEFAULT_MAX_REQUEST_TIME = 20 ; //minutes
	const DEFAULT_APP_STATUS = 'up' ;//up / down / free
	const DEFAULT_BLOCK_TIME = 1 ;// 60 * 24 minutes
	const DEFAULT_MAX_WRONG_CAPTCHA = 10 ;

	const NAME_BLOCK_TIME = 'block_time' ;
	const NAME_MAX_REQUEST = 'max_request' ;
	const NAME_MAX_REQUEST_TIME = 'max_request_time' ;
	const NAME_MAX_WRONG_CAPTCHA = 'max_wrong_captcha' ;
	const NAME_APP_STATUS = 'max_app_status' ;

	const APP_STATUS_UP = 'up';
	const APP_STATUS_DISABLE = 'disable'; // all request will be accept
	const APP_STATUS_MAINTENANCE = 'maintenance'; // all request will be reject

	protected $_table_name = 'setting';
	protected $_primary_key = 'sng_id';

	/**
	 * return block time
	 * block time is exactly how long user should be in blacklist 
	 * @return int 	minutes
	 */
	public function get_block_time()
	{
		$this->db->where(self::COLUMN_NAME , self::NAME_BLOCK_TIME);
		$val = $this->get(NULL,TRUE);
		return ($val == NULL) ? self::DEFAULT_BLOCK_TIME : $val->{self::COLUMN_VALUE} ;

	}

	/**
	 * set block time
	 *
	 * block time is exactly how long user should be in blacklist 
	 * @param int $minutes 
	 */
	public function set_block_time($minutes)
	{
		if(!is_numeric($minutes))
			return FALSE ;
		$data = array(self::COLUMN_NAME => self::NAME_BLOCK_TIME);
		$option = $this->get_by($data,TRUE);
		$data[self::COLUMN_VALUE] = $minutes ;
		if($option == NULL)
		{
			return $this->save($data);
		}
		else
		{	
			return $this->save($data,$option->{self::COLUMN_ID});
		}
	}

	/**
	 * get max request
	 * 
	 * how many request in time is valid 
	 * if user request more than max_request should get captcha
	 * @return int 
	 */
	public function get_max_request()
	{
		$this->db->where(self::COLUMN_NAME,self::NAME_MAX_REQUEST);
		$val = $this->get(NULL,TRUE);
		return ($val == NULL) ? self::DEFAULT_MAX_REQUEST : $val->{self::COLUMN_VALUE} ;
	}

	/**
	 * set max request
	 *
	 * how many request in time is valid 
	 * if user request more than max_request should get captcha
	 * 
	 * @param int $number 
	 */
	public function set_max_request($number)
	{
		if(!is_numeric($number))
			return FALSE ;
		$data = array(self::COLUMN_NAME => self::NAME_MAX_REQUEST);
		$option = $this->get_by($data,TRUE);
		$data[self::COLUMN_VALUE] = $number ;
		if($option == NULL)
		{
			return $this->save($data);
		}
		else
		{	
			return $this->save($data,$option->{self::COLUMN_ID});
		}
	}

	/**
	 * get request time limit
	 *
	 * this is limit of time to count user request
	 * for example if it set 20 and max_request 90 
	 * then visitors can request 90 per 20 minutes
	 * @return int 
	 */
	public function get_max_request_time()
	{
		$this->db->where(self::COLUMN_NAME,self::NAME_MAX_REQUEST_TIME);
		$val = $this->get(NULL,TRUE);
		return ($val == NULL) ? self::DEFAULT_MAX_REQUEST_TIME : $val->{self::COLUMN_VALUE} ;
	}

	/**
	 * set request time 
	 * 
	 * @param int $minutes 
	 */
	public function set_max_request_time($minutes)
	{
		if(!is_numeric($minutes))
			return FALSE ;
		$data = array(self::COLUMN_NAME => self::NAME_MAX_REQUEST_TIME);
		$option = $this->get_by($data,TRUE);
		$data[self::COLUMN_VALUE] = $minutes ;
		if($option == NULL)
		{
			return $this->save($data);
		}
		else
		{	
			return $this->save($data,$option->{self::COLUMN_ID});
		}
	}

	/**
	 * get max wrong captcha
	 *
	 * how many times user can answer incorrect to captcha
	 * @return int
	 */
	public function get_max_wrong_captcha()
	{
		$this->db->where(self::COLUMN_NAME,self::NAME_MAX_WRONG_CAPTCHA);
		$val = $this->get(NULL,TRUE);
		return ($val == NULL) ? self::DEFAULT_MAX_WRONG_CAPTCHA : $val->{self::COLUMN_VALUE} ;
	}

	/**
	 * set wrong captcha
	 * @param int $number 
	 */
	public function set_max_wrong_captcha($number)
	{
		if(!is_numeric($number))
			return FALSE ;
		$data = array(self::COLUMN_NAME => self::NAME_MAX_WRONG_CAPTCHA);
		$option = $this->get_by($data,TRUE);
		$data[self::COLUMN_VALUE] = $number ;
		if($option == NULL)
		{
			return $this->save($data);
		}
		else
		{	
			return $this->save($data,$option->{self::COLUMN_ID});
		}
	}

	/**
	 * get app status
	 *
	 * up => app work normally
	 * disable => app will be desable and all request will be accept
	 * maintenance => site will be down and all request will be reject 
	 * @return int  status code 
	 */
	public function get_app_status()
	{
		$this->db->where(self::COLUMN_NAME,self::NAME_APP_STATUS);
		$val = $this->get(NULL,TRUE);
		return ($val == NULL) ? self::DEFAULT_APP_STATUS : $val->{self::COLUMN_VALUE} ;	
	}

	/**
	 * set app status
	 * @param int $status status code
	 */
	public function set_app_status($status)
	{
		if($status != self::APP_STATUS_UP && $status != self::APP_STATUS_MAINTENANCE && $status != self::APP_STATUS_DISABLE)
			return FALSE ;
		$data = array(self::COLUMN_NAME => self::NAME_APP_STATUS);
		$option = $this->get_by($data,TRUE);
		$data[self::COLUMN_VALUE] = $status ;
		if($option == NULL)
		{
			return $this->save($data);
		}
		else
		{	
			return $this->save($data,$option->{self::COLUMN_ID});
		}
	}

}

 ?>