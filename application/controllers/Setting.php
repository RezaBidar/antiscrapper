<?php 
class Setting extends CI_Controller{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('btform');
		$this->load->model('m_setting');
		$this->load->model('m_storeip');
	}

	/**
	 * Setting page
	 *
	 * in prepare a from to change setting options
	 */
	public function index()
	{
		

		if(isset($_POST['submit']))
		{
			$this->m_setting->set_block_time($_POST['block_time']);
			$this->m_setting->set_app_status($_POST['app_status']);
			$this->m_setting->set_max_request($_POST['max_request']);
			$this->m_setting->set_max_request_time($_POST['max_request_time']);
			$this->m_setting->set_max_wrong_captcha($_POST['max_wrong_captcha']);
		}

		$this->data['c_data']['app_status'] = $this->m_setting->get_app_status() ;
		$this->data['c_data']['block_time'] = $this->m_setting->get_block_time() ;
		$this->data['c_data']['max_request'] = $this->m_setting->get_max_request() ;
		$this->data['c_data']['max_request_time'] = $this->m_setting->get_max_request_time() ;
		$this->data['c_data']['max_wrong_captcha'] = $this->m_setting->get_max_wrong_captcha() ;

		
		$this->data['content_view'] = 'setting';
		$this->load->view('layout',$this->data);
	}

	/**
	 * show white list table and a from to add ip to white list
	 */
	public function add_to_whitelist()
	{
		if(isset($_POST['submit']))
		{
			if($this->input->valid_ip($_POST['ip']))
			{
				$this->m_storeip->add_to_whitelist($_POST['ip']);
				$this->session->set_flashdata('success', 'ip added to list');
			}
			else
				$this->session->set_flashdata('error', 'ip is not valid');
		}

		$this->data['c_data']['table'] = $this->m_storeip->get_whitelist_table();
		$this->data['content_view'] = 'add_whitelist';
		$this->load->view('layout',$this->data);	
	}


 }
