<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subcategory_management extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
 
    }
	public function index()
	{
		$data = array();
		$data['menu'] = 'tab1';
		$data['model'] = $this->mm->view_data('category');
		$data['content'] = $this->load->view('sub_category/sub_category_form_entry',$data,true);
		$data['container'] = $this->load->view('sub_category/sub_category_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	
	
	public function insert()
	{
		$permission = $this->session->userdata('permission');
		$acl = 25;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$data = array(
							"name" =>$this->input->post('nm'),
							"categoryid" =>$this->input->post('cid')
						);
			if($this->mm->insert_data('sub_category',$data))
			{
				$message['msg'] = "Saved Successfully";
			}
			else
			{
				$message['emsg'] = "Database too busy";
			}
			redirect(base_url()."subcategory_management/index");
		}
		else
		{
			redirect(base_url() . "subcategory_management/index?esk=No Access");
		}
	}
	
	public function view_info()
	{
		$data = array();
		$data['menu'] = 'tab2';
		$data['model'] = $this->mm->view_data_two_table('sub_category','category','sub_category.id, sub_category.name, category.name as cname','sub_category.categoryid = category.id');
		$data['content'] = $this->load->view('sub_category/view_sub_category_info',$data,true);
		$data['container'] = $this->load->view('sub_category/sub_category_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	
	public function edit_data()
	{
		$id = array("id" => $this->uri->segment(3));
		
		$data = array();
		$data['menu'] = 'tab2';
		$data['all_category'] = $this->mm->getinfo('category');
		$data['subcategory'] = $this->mm->get_all_info_by_id('sub_category',$id);
		$data['content'] = $this->load->view('sub_category/sub_category_form_edit',$data,true);
		$data['container'] = $this->load->view('sub_category/sub_category_page',$data,true);
		$this->load->view('masteradmin',$data);
		
	}
	public function update_category()
	{
		$permission = $this->session->userdata('permission');
		$acl = 26;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$id = array("id" => $this->input->post('id'));
			$data = array(
							"name" => $this->input->post('nm'),
							"categoryid" =>$this->input->post('cid')
						);
		
			if($this->mm->update_info('sub_category',$data,$id))
			{
				$msg = "Update Successfully";
				redirect(base_url()."subcategory_management/view_info?sk=".$msg , "refresh");
			}
			else
			{
				$emsg = "Database too busy";
				redirect(base_url()."subcategory_management/view_info?esk=".$emsg , "refresh");
			}
		
		}
		else
		{
			redirect(base_url() . "subcategory_management/view_info?esk=No Access");
		}
	}
	public function delete_data($sid)
	{
		$permission = $this->session->userdata('permission');
		$acl = 27;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$isexist = "";
			$isexist = $this->db->query("Select * from product where subcategoryid='".$sid."'")->result();
			if(!empty($isexist)) //is exit record in product table don't delete brand record
			{
				redirect(base_url() . "subcategory_management/view_info?esk=This subcategory has record, Can't delete", "refresh");
			}
			else
			{
				if($this->mm->delete_info('sub_category',array("id" =>$sid)))
				{
					$msg = "Deleted Successfully";
					redirect(base_url()."subcategory_management/view_info?sk=".$msg , "refresh");
				}
				else
				{
					$emsg = "Database too busy";
					redirect(base_url()."subcategory_management/view_info?esk=".$emsg , "refresh");
				}
			}
		}
		else
		{
			redirect(base_url() . "subcategory_management/view_info?esk=No Access");
		}
	}
	
	
	
}
