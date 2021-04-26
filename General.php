<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Dhaka");
		
	}
	public function index()
	{
		$data = array();
		//$data['content'] = $this->load->view('page_admin','',true);
		$this->load->view('masteradmin',$data);
	}
	public function dashboard()
	{
		$data = array();
		$data['content'] = $this->load->view('dashboard','',true);
		$this->load->view('masteradmin',$data);
	}
	public function order_details($orderno)
	{
		$singleorderreq = $this->db->query("select * from  t_purchase where orderid = '".$orderno."' ")->result();
		if(count($singleorderreq)>0)
		{
			$data = array();
			$data['singleorderreq'] = $singleorderreq;
			$data['customer'] = $this->db->query("SELECT * FROM t_customer")->result();
			$data['orderno'] = $orderno;
			$this->load->view('single_order_details_for_customer_view',$data);
		}
		else
		{
			$this->load->view('404error');
		}
	}
	
	
	
	
	
}
