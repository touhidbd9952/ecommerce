<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brand_management extends CI_Controller {
	
	public function __construct() 
	{
        parent::__construct();
        
        
    }
	public function index()
	{
		$data = array();
		$data['menu'] = 'tab1';
		$data['content'] = $this->load->view('brand/brand_form_entry','',true);
		$data['container'] = $this->load->view('brand/brand_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	
	public function insert()
	{
		$permission = $this->session->userdata('permission');
		$acl = 28;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$name = trim($this->input->post('nm'));
			$isexist = "";
			$isexist = $this->db->query("Select * from brand where name='".$name."'")->result(); 
			
			if(!empty($isexist)) //is exit record in product table don't delete brand record
			{
				redirect(base_url() . "brand_management/index?esk=Brand already exist", "refresh");
			}
			else
			{
				$data = array(
								"name" =>$this->input->post('nm')
							);
				if($this->mm->insert_data('brand',$data))
				{
					$msg = "Saved Successfully";
					redirect(base_url()."brand_management/index?sk=".$msg, "refresh");
				}
				else
				{
					$emsg = "Database too busy";
					redirect(base_url()."brand_management/index?esk=".$emsg, "refresh");
				}
			}
		}
		else
		{
			redirect(base_url() . "brand_management/index?esk=No Access");
		}
	}
	public function view_info()
	{
		$data = array();
		$data['menu'] = 'tab2';
		$data['model'] = $this->mm->getinfo('brand');
		$data['content'] = $this->load->view('brand/view_brand_info',$data,true);
		$data['container'] = $this->load->view('brand/brand_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function edit_data()
	{
		$id = array("id" => $this->uri->segment(3));
		
		$data = array();
		$data['menu'] = 'tab2';
		$data['model'] = $this->mm->get_all_info_by_id('brand',$id);
		$data['content'] = $this->load->view('brand/brand_form_edit',$data,true);
		$data['container'] = $this->load->view('brand/brand_page',$data,true);
		$this->load->view('masteradmin',$data);
		
	}
	public function update_brand()
	{
		$permission = $this->session->userdata('permission');
		$acl = 29;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$id = array("id" => $this->input->post('id'));
			$data = array(
							"name" => $this->input->post('nm')
						);
		
			if($this->mm->update_info('brand',$data,$id))
			{
				$msg = "Update Successfully";
				redirect(base_url()."brand_management/view_info?sk=".$msg , "refresh");
			}
			else
			{
				$emsg = "Database too busy";
				redirect(base_url()."brand_management/view_info?esk=".$emsg , "refresh");
			}
		}
		else
		{
			redirect(base_url() . "brand_management/view_info?esk=No Access");
		}
	}
	public function delete_data($brandid)
	{
		$permission = $this->session->userdata('permission');
		$acl = 30;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$id = array("id" => $brandid);
			//$picturename = $picturename;
			$isexist = "";
			$isexist = $this->db->query("Select * from product where brandid='".$brandid."'")->result(); 
			
			if(!empty($isexist)) //is exit record in product table don't delete brand record
			{
				redirect(base_url() . "brand_management/view_info?esk=This brand has record, Can't delete", "refresh");
			}
			else
			{
			
				if($this->mm->delete_info('brand',$id))
				{
					$msg = "Deleted Successfully";
					redirect(base_url()."brand_management/view_info?sk=".$msg , "refresh");
				}
				else
				{
					$emsg = "Database too busy";
					redirect(base_url()."brand_management/view_info?esk=".$emsg , "refresh");
				}
			}
		}
		else
		{
			redirect(base_url() . "brand_management/view_info?esk=No Access");
		}
	}
}
