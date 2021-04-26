<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_management extends CI_Controller {

    public function index() {
        $data = array();
        $data['city'] = $this->mm->getinfo('city');
        $data['content'] = $this->load->view('customer/customer_form_entry', $data, true);
        $data['container'] = $this->load->view('customer/customer_page', $data, true);
        $this->load->view('masteradmin', $data);
    }

    public function insert() {
        $p = pathinfo($_FILES['pic']['name']);
		if(count($p)>2)
		{
			$picname = "";
			$rand = rand(1000,10000);
			$picname = $rand.'.png';
		}
		
		//status creating
		//$arr = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		//$status = "";
		//$last = count($arr)-1; //index count count($arr), character count strlen($a)
		//for($i = 0; $i <= $last; $i++)
		//{
			//$number =  rand($i, $last);
			//$status .= $arr[$number];		
		//}
		$status = "a"; // a for active customer
        
        $data = array(
            "name" => $this->input->post('nm'),
            "email" => $this->input->post('email'),
            "password" => $this->input->post('pass'),
            "gender" => $this->input->post('gen'),
            "contact" => $this->input->post('contact'),
            "cityid" => $this->input->post('cid'),
            "picture" => $picname,
            "status" => $status,
			 "type"    => $this->input->post('ctype') 
        );
        $insertId = $this->mm->InsertWithImage('customer', $data);
        if ($insertId != "") 
		{
			$imgfilename = $picname;
			$this->mm->image_upload('./img/customer/' , '15000000', '5000', '3000', $imgfilename ,'270','310','pic');
			
            $msg = "For active your account, <a href='".  base_url() . "account/verify/{$status}"."'>Click Here</a>";
            mail($this->input->post('email'), "Mail Verification", $msg); //mail(to,subject,message)
            $msg = "Saved Successfully";
			redirect(base_url() . "customer_management/index?sk=".$msg);
        } 
		else 
		{
            $emsg = "Database too busy";
			redirect(base_url() . "customer_management/index?esk=".$emsg);
        }
    }

    public function view_info() {
        $data = array();
        $data['city'] = $this->mm->getinfo('city');
        $data['country'] = $this->mm->getinfo('country');
        $data['customer'] = $this->mm->getinfo('customer');
        $data['content'] = $this->load->view('customer/view_customer_info', $data, true);
        $data['container'] = $this->load->view('customer/customer_page', $data, true);
        $this->load->view('masteradmin', $data);
    }

    public function edit_data() {
        $id = array("id" => $this->uri->segment(3));

        $data = array();
        $data['city'] = $this->mm->getinfo('city');
        $data['customer'] = $this->mm->get_all_info_by_id('customer', $id);
        $data['content'] = $this->load->view('customer/customer_form_edit', $data, true);
        $data['container'] = $this->load->view('customer/customer_page', $data, true);
        $this->load->view('masteradmin', $data);
    }

    public function update_customer() {
        $id = array("id" => $this->input->post('id'));
		$tid = $this->input->post('id');
		
        $p = pathinfo($_FILES['pic']['name']);
		if(count($p)>2)
		{
			$picname = "";
			$picname = $this->db->query("Select picture from customer where id='".$tid."'")->row()->picture; 
			if(empty($picname))
			{
				$rand = rand(1000,10000);
				$picname = $rand.'.png';
			}
		}
		
		$status = "a"; // a for active customer
        
        $data = array(
            "name" => $this->input->post('nm'),
            "email" => $this->input->post('email'),
            "password" => $this->input->post('pass'),
            "gender" => $this->input->post('gen'),
            "contact" => $this->input->post('contact'),
            "cityid" => $this->input->post('cid'),
            "picture" => $picname,
            "status" => $status,
			 "type"  => $this->input->post('ctype') 
        );
       
        if ($this->mm->update_info('customer', $data, $id)) 
		{
			$imgfilename = $picname;
			$oldfile = $picname;
			$this->mm->image_upload2('./img/customer/' , '15000000', '5000', '3000', $imgfilename ,'270','310','pic',$oldfile);
            $msg = "Updated Successfully";
			redirect(base_url() . "customer_management/view_info?sk=".$msg);
        } 
		else 
		{
            $emsg = "Database too busy";
			redirect(base_url() . "customer_management/view_info?esk=".$emsg);
        }
    }

    public function delete_data($tid,$picturename) 
	{
        $id = array("id" => $tid);
		$picturename = $picturename;
		//is customer exist in purchase table
		$isexist = "";
		$isexist = $this->db->query("Select * from purchase where customerid='".$tid."'")->result(); 
		
		if(!empty($isexist)) //is exit record in purchase table don't delete customer record
		{
			redirect(base_url() . "customer_management/view_info?esk=This customer has record, Can't delete", "refresh");
		}
		else
		{
		
			if ($this->mm->delete_info('customer', $id)) 
			{
				if(!empty($picturename))
				{
					$this->mm->delete_image('img/customer/'.$picturename);
				}
				
				$msg = "Deleted Successfully";
				redirect(base_url() . "customer_management/view_info?sk=".$msg, "refresh");
			} 
			else 
			{
				$emsg = "Database too busy";
				redirect(base_url() . "customer_management/view_info?esk=".$emsg, "refresh");
			}
		}
    }

    

}
