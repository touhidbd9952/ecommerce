<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pickuppoint_management extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
    
    }
	public function index()
	{
		$data = array();
		$data['menu'] = 'tab1';
		$data['content'] = $this->load->view('pickuppoint/pickuppoint_form_entry','',true);
		$data['container'] = $this->load->view('pickuppoint/pickuppoint_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	
	public function insert()
	{ 
		$permission = $this->session->userdata('permission');
		$acl = 41;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$areaname = $this->input->post('areaname');
			$amount = $this->input->post('amount');
			$pickuppoint = trim($this->input->post('pickuppoint'));
			$pickuppointaddress = trim($this->input->post('pickuppointaddress'));
			$deliveryday = $this->input->post('deliveryday');
			
			$err=0;
			$emsg="";
			if($areaname =="")
			{
				$emsg = ++$err.". Area Name Required<br>";
			}
			if($amount =="")
			{
				$emsg = ++$err.". Delivery Charge Required<br>";
			}
			else if(is_nan($amount))
			{
				$emsg = ++$err.". Delivery Charge Invalid<br>";
			}
			if($pickuppoint =="")
			{
				$emsg .= ++$err.". Pickup Point Required<br>";
			}
			if($pickuppointaddress =="")
			{
				$emsg .= ++$err.". Pickup Point Address Required<br>";
			}
			if($deliveryday =="")
			{
				$emsg .= ++$err.". Deliveryday Required<br>";
			}
			else if(is_nan($deliveryday))
			{
				$emsg = ++$err.". Delivery Period Invalid<br>";
			}
			if($err==0)
			{
				$data = array();
				$data['area'] = strtolower($areaname);
				$data['amount'] = $amount;
				$data['pickuppoint'] = $pickuppoint;
				$data['pickuppoint_address'] = $pickuppointaddress;
				$data['period'] = $deliveryday;
				
				if($this->mm->insert_data('t_pickuppoint',$data))
				{
					redirect("pickuppoint_management/index?sk=Saved Successfully");
				}
				else
				{
					redirect("pickuppoint_management/index?esk=Database too busy, tray later.");
				}
			}
			else
			{
				redirect("pickuppoint_management/index?esk=".$emsg);
			}
		}
		else
		{
			redirect(base_url() . "pickuppoint_management/index?esk=No Access");
		}
	}
	public function view_info()
	{
		$data = array();
		$data['menu'] = 'tab2';
		$data['model'] = $this->db->query('select * from t_pickuppoint order by area asc')->result();
		$data['content'] = $this->load->view('pickuppoint/view_pickuppoint_info',$data,true);
		$data['container'] = $this->load->view('pickuppoint/pickuppoint_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function edit_data()
	{
		$id = array("id" => $this->uri->segment(3));
		
		$data = array();
		$data['menu'] = 'tab2';
		$data['model'] = $this->mm->get_all_info_by_id('t_pickuppoint',$id);
		$data['content'] = $this->load->view('pickuppoint/pickuppoint_form_edit',$data,true);
		$data['container'] = $this->load->view('pickuppoint/pickuppoint_page',$data,true);
		$this->load->view('masteradmin',$data);
		
	}
	public function update()
	{
		$permission = $this->session->userdata('permission');
		$acl = 42;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$id = array("id" => $this->input->post('id'));
			$areaname = $this->input->post('areaname');
			$amount = $this->input->post('amount');
			$pickuppoint = $this->input->post('pickuppoint');
			$pickuppointaddress = trim($this->input->post('pickuppointaddress'));
			$deliveryday = $this->input->post('deliveryday');
			
			$err=0;
			$emsg="";
			if($areaname =="")
			{
				$emsg = ++$err.". Area Name Required<br>";
			}
			if($amount =="")
			{
				$emsg = ++$err.". Delivery Charge Required<br>";
			}
			else if(is_nan($amount))
			{
				$emsg = ++$err.". Delivery Charge Invalid<br>";
			}
			if($pickuppoint =="")
			{
				$emsg .= ++$err.". Pickup Point Required<br>";
			}
			if($pickuppointaddress =="")
			{
				$emsg .= ++$err.". Pickup Point Address Required<br>";
			}
			if($deliveryday =="")
			{
				$emsg .= ++$err.". Deliveryday Required<br>";
			}
			else if(is_nan($deliveryday))
			{
				$emsg = ++$err.". Delivery Period Invalid<br>";
			}
			
			if($err==0)
			{
				$data = array();
				$data['area'] = strtolower($areaname);
				$data['amount'] = $amount;
				$data['pickuppoint'] = $pickuppoint;
				$data['pickuppoint_address'] = $pickuppointaddress;
				$data['period'] = $deliveryday;
				
				if($this->mm->update_info('t_pickuppoint',$data,$id))
				{
					$msg = "Update Successfully";
					redirect(base_url()."pickuppoint_management/view_info?sk=".$msg , "refresh");
				}
				else
				{
					$emsg = "Database too busy";
					redirect(base_url()."pickuppoint_management/view_info?esk=".$emsg , "refresh");
				}
			}
			else
			{
				$tid = $this->input->post('id');
				redirect("pickuppoint_management/edit_data/".$tid."?esk=".$emsg);
			}
		}
		else
		{
			redirect(base_url() . "pickuppoint_management/edit_data?esk=No Access");
		}
	}
	public function delete_data()
	{
		$permission = $this->session->userdata('permission');
		$acl = 43;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$tid = $this->uri->segment(3);
			$pickuppoint = $this->db->query("SELECT pickuppoint FROM t_pickuppoint WHERE id='".$tid."'")->row()->pickuppoint;
			$purchasedata = $this->db->query("SELECT * FROM  t_purchase WHERE deliveryloc='".$pickuppoint."' and orderstatus='' ")->result();
			if(count($purchasedata)<1)
			{
				$id = array("id" => $this->uri->segment(3));
				
				if($this->mm->delete_info('t_pickuppoint',$id))
				{
					$msg = "Deleted Successfully";
					redirect(base_url()."pickuppoint_management/view_info?sk=".$msg , "refresh");
				}
				else
				{
					$emsg = "Database too busy";
					redirect(base_url()."pickuppoint_management/view_info?esk=".$emsg , "refresh");
				}
			}
			else
			{
				$emsg = "Some order still pending";
				redirect(base_url()."pickuppoint_management/view_info?esk=".$emsg , "refresh");
			}
		}
		else
		{
			redirect(base_url() . "pickuppoint_management/view_info?esk=No Access");
		}	
	}
	
	
}
