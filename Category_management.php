<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category_management extends CI_Controller {

    public function __construct()
	 {
        parent::__construct();

    }

    public function index() {
        $data = array();
		$data['menu'] = 'tab1';
        $data['content'] = $this->load->view('category/category_form_entry', '', true);
        $data['container'] = $this->load->view('category/category_page', $data, true);
        $this->load->view('masteradmin', $data);
    }

    public function view_info() {
        $data = array();
		$data['menu'] = 'tab2';
        $data['category'] = $this->mm->getinfo('category');
        $data['content'] = $this->load->view('category/view_category_info', $data, true);
        $data['container'] = $this->load->view('category/category_page', $data, true);
        $this->load->view('masteradmin', $data);
    }

    public function insert() {
		$permission = $this->session->userdata('permission');
		$acl = 22;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$data = array(
				"name" => $this->input->post('nm'),
				"metatag" => $this->input->post('metatag'),
				"metades" => $this->input->post('metades')
			);
			if ($this->mm->insert_data('category', $data)) 
			{
				$message['msg'] = "Saved Successfully";
			} 
			else 
			{
				$message['emsg'] = "Database too busy";
			}
			redirect(base_url() . "category_management/index");
		
		}
		else
		{
			redirect(base_url() . "category_management/index?esk=No Access");
		}
    }

    public function edit_data() {
        
		$id = array("id" => $this->uri->segment(3));

		$data = array();
		$data['menu'] = 'tab2';
		$data['category'] = $this->mm->get_all_info_by_id('category', $id);
		$data['content'] = $this->load->view('category/category_form_edit', $data, true);
		$data['container'] = $this->load->view('category/category_page', $data, true);
		$this->load->view('masteradmin', $data);
		
    }

    public function update_category() {
		$permission = $this->session->userdata('permission');
		$acl = 23;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$id = array("id" => $this->input->post('id'));
			$data = array(
				"name" => $this->input->post('nm'),
				"metatag" => $this->input->post('metatag'),
				"metades" => $this->input->post('metades'),
				"indexnumber" => $this->input->post('indexnumber')
			);
	
			if ($this->mm->update_info('category', $data, $id)) {
				$message['msg'] = "Update Successfully";
			} else {
				$message['emsg'] = "Database too busy";
			}
			redirect(base_url() . "category_management/view_info", "refresh");
		}
		else
		{
			redirect(base_url() . "category_management/view_info?esk=No Access");
		}
    }

    public function delete_data($cid) 
	{
		$permission = $this->session->userdata('permission');
		$acl = 24;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$isexist = "";
			$isexist = $this->db->query("Select * from product where categoryid='".$cid."'")->result();
			if(!empty($isexist)) //is exit record in product table don't delete brand record
			{
				redirect(base_url() . "category_management/view_info?esk=This category has record, Can't delete", "refresh");
			}
			else
			{
			
				if ($this->mm->delete_info('category', array('id'=>$cid))) 
				{
					$msg = "Deleted Successfully";
					redirect(base_url() . "category_management/view_info?sk=".$msg, "refresh");
				} else {
					$emsg = "Database too busy";
					redirect(base_url() . "category_management/view_info?esk=".$emsg, "refresh");
				}
			}
		}
		else
		{
			redirect(base_url() . "category_management/view_info?esk=No Access");
		}
    }

}
