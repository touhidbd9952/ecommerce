<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Color_management extends CI_Controller {
	
	public function __construct() 
	{
        parent::__construct();
        
        
    }
	public function index()
	{
		$data = array();
		$data['menu'] = 'tab1';
		$data['content'] = $this->load->view('color/color_form_entry','',true);
		$data['container'] = $this->load->view('color/color_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	
	public function insert()
	{
		$permission = $this->session->userdata('permission');
		$acl = 31;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$data = array(
							"name" =>$this->input->post('nm')
						);
			if($this->mm->insert_data('color',$data))
			{
				$msg = "Saved Successfully";
				redirect(base_url()."color_management/index?sk=".$msg, "refresh");
			}
			else
			{
				$emsg = "Database too busy";
				redirect(base_url()."color_management/index?esk=".$emsg, "refresh");
			}
		}
		else
		{
			redirect(base_url() . "color_management/index?esk=No Access");
		}
	}
	public function view_info()
	{
		$data = array();
		$data['menu'] = 'tab2';
		$data['model'] = $this->mm->getinfo('color');
		$data['content'] = $this->load->view('color/view_color_info',$data,true);
		$data['container'] = $this->load->view('color/color_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function edit_data()
	{
		$id = array("id" => $this->uri->segment(3));
		
		$data = array();
		$data['menu'] = 'tab2';
		$data['model'] = $this->mm->get_all_info_by_id('color',$id);
		$data['content'] = $this->load->view('color/color_form_edit',$data,true);
		$data['container'] = $this->load->view('color/color_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function update_color()
	{
		$permission = $this->session->userdata('permission');
		$acl = 32;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$id = array("id" => $this->input->post('id'));
			$data = array(
							"name" => $this->input->post('nm')
						);
		
			if($this->mm->update_info('color',$data,$id))
			{
				$msg = "Update Successfully";
				redirect(base_url()."color_management/view_info?sk=".$msg , "refresh");
			}
			else
			{
				$emsg = "Database too busy";
				redirect(base_url()."color_management/view_info?esk=".$emsg , "refresh");
			}
		}
		else
		{
			redirect(base_url() . "color_management/view_info?esk=No Access");
		}
	}
	public function delete_data($cid)
	{
		$permission = $this->session->userdata('permission');
		$acl = 33;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
		
			$id = array("id" => $cid);
			$isexist = "";
			$isexist = $this->db->query("Select * from product where colorid='".$cid."'")->result(); 
			
			if(!empty($isexist)) //is exit record in product table don't delete brand record
			{
				redirect(base_url() . "color_management/view_info?esk=This color has record, Can't delete", "refresh");
			}
			else
			{
				if($this->mm->delete_info('color',$id))
				{
					$msg = "Deleted Successfully";
					redirect(base_url()."color_management/view_info?sk=".$msg , "refresh");
				}
				else
				{
					$emsg = "Database too busy";
					redirect(base_url()."color_management/view_info?esk=".$emsg , "refresh");
				}
			}
		}
		else
		{
			redirect(base_url() . "color_management/view_info?esk=No Access");
		}
	}
}
