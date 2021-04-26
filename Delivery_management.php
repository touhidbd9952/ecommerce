<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery_management extends CI_Controller {
	
	public function __construct() {
        parent::__construct();
    
    }
	public function index()
	{
		$data = array();
		$data['menu'] = 'tab1';
		$data['content'] = $this->load->view('delivery/delivery_form_entry','',true);
		$data['container'] = $this->load->view('delivery/delivery_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	
	public function insert()
	{
		$nm = $this->input->post('nm');
		$amount = $this->input->post('amount');
		$local_location = $this->input->post('local_location');
		$national_location = $this->input->post('national_location');
		$international_location = $this->input->post('international_location');
		$deliveryday = $this->input->post('deliveryday');
		$err=0;
		$emsg="";
		$deliverydata = $this->db->query("SELECT * FROM delivery WHERE name='".$nm."'")->result();
		if(count($deliverydata)>0)
		{
			redirect("delivery_management/index?esk=Delivery Location '".$nm."' already inserted, you can only edit");
		}
		if($deliveryday =="")
		{
			++$err;
			$emsg = "Delivery Period Required";
		}
		else if(is_nan($deliveryday))
		{
			++$err;
			$emsg = "Delivery Period Invalid";
		}
		if($err==0)
		{
			$data = array();
			$data['name'] = $nm;
			if($nm == 'Local'){ $data['address'] = $local_location;}
			else if($nm == 'National'){ $data['address'] = $national_location;}
			else if($nm == 'International'){ $data['address'] = $international_location;}
			$data['amount'] = $amount;
			$data['period'] = $deliveryday;
			
			if($this->mm->insert_data('delivery',$data))
			{
				$msg = "Saved Successfully";
				redirect("delivery_management/index?sk=".$msg);
			}
			else
			{
				$emsg = "Database too busy";
				redirect("delivery_management/index?esk=".$emsg);
			}
		}
		else
		{
			redirect("delivery_management/index?esk=".$emsg);
		}
		
	}
	public function view_info()
	{
		$data = array();
		$data['menu'] = 'tab2';
		$data['model'] = $this->mm->getinfo('delivery');
		$data['content'] = $this->load->view('delivery/view_delivery_info',$data,true);
		$data['container'] = $this->load->view('delivery/delivery_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function edit_data()
	{
		$id = array("id" => $this->uri->segment(3));
		
		$data = array();
		$data['menu'] = 'tab2';
		$data['model'] = $this->mm->get_all_info_by_id('delivery',$id);
		$data['content'] = $this->load->view('delivery/delivery_form_edit',$data,true);
		$data['container'] = $this->load->view('delivery/delivery_page',$data,true);
		$this->load->view('masteradmin',$data);
		
	}
	public function update()
	{
		$permission = $this->session->userdata('permission');
		$acl = 40;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$id = array("id" => $this->input->post('id'));
			$nm = $this->input->post('nm'); 
			$amount = $this->input->post('amount');
			$local_location = $this->input->post('local_location');
			$national_location = $this->input->post('national_location');
			$international_location = $this->input->post('international_location');    
			$deliveryday = $this->input->post('deliveryday');
			$err=0;
			$emsg="";
			if($deliveryday =="")
			{
				++$err;
				$emsg = "Delivery Period Required";
			}
			else if(is_nan($deliveryday))
			{
				++$err;
				$emsg = "Delivery Period Invalid";
			}
			
			if($err==0)
			{
				$data = array();
				$data['name'] = $nm;
				if($nm == 'Local'){ $data['address'] = $local_location;} 
				else if($nm == 'National'){ $data['address'] = $national_location;}
				else if($nm == 'International'){ $data['address'] = $international_location;}
				$data['amount'] = $amount;
				$data['period'] = $deliveryday;
				
				if($this->mm->update_info('delivery',$data,$id))
				{
					$msg = "Update Successfully";
					redirect(base_url()."delivery_management/view_info?sk=".$msg , "refresh");
				}
				else
				{
					$emsg = "Database too busy";
					redirect(base_url()."delivery_management/view_info?esk=".$emsg , "refresh");
				}
			}
			else
			{
				$tid = $this->input->post('id');
				redirect("delivery_management/edit_data/".$tid."?esk=".$emsg);
			}
		}
		else
		{
			redirect(base_url() . "delivery_management/edit_data?esk=No Access");
		}
	}
	public function delete_data()
	{
		$id = array("id" => $this->uri->segment(3));
		
		if($this->mm->delete_info('delivery',$id))
		{
			$msg = "Deleted Successfully";
			redirect(base_url()."delivery_management/view_info?sk=".$msg , "refresh");
		}
		else
		{
			$emsg = "Database too busy";
			redirect(base_url()."delivery_management/view_info?esk=".$emsg , "refresh");
		}	
	}
}
