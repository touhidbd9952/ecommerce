<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_management extends CI_Controller {
	
	public function __construct() 
	{
        parent::__construct();
        
        
    }
	public function index()
	{
		$data = array();
		$data['menu'] = 'tab1';
		$data['content'] = $this->load->view('unit/unit_form_entry','',true);
		$data['container'] = $this->load->view('unit/unit_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	
	public function insert()
	{
		$permission = $this->session->userdata('permission');
		$acl = 108;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$data = array(
							"name" =>$this->input->post('nm')
						);
			if($this->mm->insert_data('unit',$data))
			{
				$msg = "Saved Successfully";
				redirect(base_url()."unit_management/index?sk=".$msg, "refresh");
			}
			else
			{
				$emsg = "Database too busy";
				redirect(base_url()."unit_management/index?esk=".$emsg, "refresh");
			}
		}
		else
		{
			redirect(base_url() . "unit_management/index?esk=No Access");
		}
	}
	public function view_info()
	{
		$data = array();
		$data['menu'] = 'tab2';
		$data['model'] = $this->mm->getinfo('unit');
		$data['content'] = $this->load->view('unit/view_unit_info',$data,true);
		$data['container'] = $this->load->view('unit/unit_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function edit_data()
	{
		$id = array("id" => $this->uri->segment(3));
		
		$data = array();
		$data['menu'] = 'tab2';
		$data['model'] = $this->mm->get_all_info_by_id('unit',$id);
		$data['content'] = $this->load->view('unit/unit_form_edit',$data,true);
		$data['container'] = $this->load->view('unit/unit_page',$data,true);
		$this->load->view('masteradmin',$data);
		
	}
	public function update_unit()
	{
		$permission = $this->session->userdata('permission');
		$acl = 109;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$id = array("id" => $this->input->post('id'));
			$data = array(
							"name" => $this->input->post('nm')
						);
		
			if($this->mm->update_info('unit',$data,$id))
			{
				$msg = "Update Successfully";
				redirect(base_url()."unit_management/view_info?sk=".$msg , "refresh");
			}
			else
			{
				$emsg = "Database too busy";
				redirect(base_url()."unit_management/view_info?esk=".$emsg , "refresh");
			}
		}
		else
		{
			redirect(base_url() . "unit_management/view_info?esk=No Access");
		}
	}
	public function delete_data($uid)
	{
		$permission = $this->session->userdata('permission');
		$acl = 110;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
		
			$id = array("id" => $uid);
			$isexist = "";
			$isexist = $this->db->query("Select * from product where unitid='".$uid."'")->result(); 
			
			if(!empty($isexist)) //is exit record in product table don't delete unit record
			{
				redirect(base_url() . "unit_management/view_info?esk=This unit has record, Can't delete", "refresh");
			}
			else
			{
				if($this->mm->delete_info('unit',$id))
				{
					$msg = "Deleted Successfully";
					redirect(base_url()."unit_management/view_info?sk=".$msg , "refresh");
				}
				else
				{
					$emsg = "Database too busy";
					redirect(base_url()."unit_management/view_info?esk=".$emsg , "refresh");
				}
			}
		}
		else
		{
			redirect(base_url() . "unit_management/view_info?esk=No Access");
		}
	}
}
