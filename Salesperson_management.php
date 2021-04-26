<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Salesperson_management extends CI_Controller {

    public function __construct()
	 {
        parent::__construct();

    }

    public function index() 
	{
        $data = array();
		$data['menu'] = 'tab1';
        $data['content'] = $this->load->view('salesperson/salesperson_form_entry', $data, true);
        $data['container'] = $this->load->view('salesperson/salesperson_page', $data, true);
        $this->load->view('masteradmin', $data);
    }

    public function view_info() {
        $data = array();
		$data['menu'] = 'tab2';
        $data['salesperson'] = $this->db->query("select * from t_salesperson ")->result();
		$data['salespersonlist'] = $this->mm->getinfo('t_salesperson');
        $data['content'] = $this->load->view('salesperson/view_salesperson_info', $data, true);
        $data['container'] = $this->load->view('salesperson/salesperson_page', $data, true);
        $this->load->view('masteradmin', $data);
    }

    public function insert() 
	{
		$code = $this->input->post('code');
		$username = $this->input->post('username');//
		$password = $this->input->post('password');//
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$address = $this->input->post('address');
		$shift = $this->input->post('shift');
		$status = $this->input->post('status');
		$category = $this->input->post('category');
		$joindate = $this->input->post('joindate');
		$err = 0;
		$msg ="";
		
		if($code ==""){ $msg .=++$err." . Sales Person Code Required<br>";}
		if($code !="")
		{
			$allsalespersoncode = $this->db->query("select code from t_salesperson")->result();
			if(count($allsalespersoncode)>0) 
			{
				foreach($allsalespersoncode as $as)
				{
					if($code == $as->code)
					{
						$msg .=++$err." . Sales Person code already exist<br>";
						break;
					}
				}
			}
		}
		if($username ==""){ $msg .=++$err." . Sales Person Username Required<br>";}//
		else if(($username !="")&& strlen($username)>10)
		{
			$msg .=++$err." . Username Maximum 10 Character<br>";
		}
		else if(!empty($username))
		{ 
			$salesperson = $this->db->query("Select * from t_salesperson where username='".$username."'")->result();
			if(count($salesperson)>0)
			{
				$msg .=++$err." . Sales Person already exist<br>";
			}
		}//
		if(empty($password)){ $msg .=++$err." . Sales Person Password Required<br>";}//
		else if(strlen($password)>10)
		{
			$msg .=$err." . Password Maximum 10 Character<br>";
		}
		if(empty($name)){ $msg .=++$err." . Sales Person Name Required<br>";}
		if(empty($email)){ $msg .=++$err." . Sales Person Email Required<br>";}
		else
		{
			if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)	{$msg .=++$err." . Sales Person Email Invalid<br>";}
		}
		if(empty($phone)){ $msg .=++$err." . Sales Person Phone Required<br>";}
		if(empty($address)){ $msg .=++$err." . Sales Person Address Required<br>";}
		if(empty($joindate)){ $msg .=++$err." . Sales Person Joindate Required<br>";}
		
				$p = pathinfo($_FILES['pic']['name']);
				$picname = "";
				if(count($p)>2)
				{
					$rand = rand(1000,10000);
					$picname = $rand.'.png';
				}
		if($err == 0)
		{
			$data = array();
			$data['code'] = $code;
			$data['username'] = $username;//
			$data['password'] = $password;//
			$data['name'] = $name;
			$data['email'] = $email;
			$data['phone'] = $phone;
			$data['address'] = $address;
			$data['shift'] = $shift;
			$data['status'] = $status;
			$data['category'] = $category;
			$data['joindate'] = $joindate;
			if(!empty($picname)){
				$data['picture'] = $picname;}
				
			if($this->mm->insert_data('t_salesperson', $data)) 
			{
				$p = pathinfo($_FILES['pic']['name']);
				if(count($p)>2)
				{
					$imgfilename = $picname;
					$this->mm->image_upload('./img/salesperson/' , '15000000', '5000', '3000', $imgfilename ,'270','310','pic');
				}
				redirect(base_url() . "salesperson_management/index?sk=Saved Successfully");
			} 
			else
			{
				redirect(base_url() . "salesperson_management/index?esk=Database too busy, Try later");
			}
		}
		else
		{
			redirect(base_url() . "salesperson_management/index?esk=".$msg);
		} 
    }

    public function edit_data() {
        $id = array("id" => $this->uri->segment(3));

        $data = array();
		$data['menu'] = 'tab2';
        $data['salesperson'] = $this->mm->get_all_info_by_id('t_salesperson', $id);
        $data['content'] = $this->load->view('salesperson/salesperson_form_edit', $data, true);
        $data['container'] = $this->load->view('salesperson/salesperson_page', $data, true);
        $this->load->view('masteradmin', $data);
    }

    public function update_salesperson() {
		$editid = $this->input->post('id');
        $id = array("id" => $this->input->post('id'));
        $code = $this->input->post('code');
		$username = $this->input->post('username');//
		$password = $this->input->post('password');//
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$address = $this->input->post('address');
		$shift = $this->input->post('shift');
		$status = $this->input->post('status');
		$category = $this->input->post('category');
		$joindate = $this->input->post('joindate');
		$err = 0;
		$msg ="";
		if(empty($code)){ $msg .=$err." . Sales Person ID Required<br>";}
		if($code !="")
		{
			$allsalespersoncode = $this->db->query("select code from t_salesperson")->result();
			if(count($allsalespersoncode)>0) 
			{
				foreach($allsalespersoncode as $as)
				{
					if($code == $as->code)
					{
						$msg .=$err." . Sales Person code already exist<br>";
						break;
					}
				}
			}
		}
		if(empty($username)){ $msg .=$err." . Sales Person Username Required<br>";}//
		if(empty($password)){ $msg .=$err." . Sales Person Password Required<br>";}//
		else if(strlen($password)>10)
		{
			$msg .=$err." . Password Maximum 10 Character<br>";
		}
		if(empty($name)){ $msg .=$err." . Sales Person Name Required<br>";}
		if(empty($email)){ $msg .=$err." . Sales Person Email Required<br>";}
		else
		{
			if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)	{$msg .=$err." . Sales Person Email Invalid<br>";}
		}
		if(empty($phone)){ $msg .=$err." . Sales Person Phone Required<br>";}
		if(empty($address)){ $msg .=$err." . Sales Person Address Required<br>";}
		if(empty($joindate)){ $msg .=$err." . Sales Person Joindate Required<br>";}
		
			$picname = "";
			$p = pathinfo($_FILES['pic']['name']);
			if(count($p)>2)
				{
					$picname = $this->db->query("Select picture from t_salesperson where id='".$editid."'")->row()->picture; 
					if(empty($picname))
					{
						$rand = rand(1000,10000);
						$picname = $rand.'.png';
					}
					
				}
		if($err == 0)
		{
			$data = array();
			$data['code'] = $code;
			$data['username'] = $username;//
			$data['password'] = $password;//
			$data['name'] = $name;
			$data['email'] = $email;
			$data['phone'] = $phone;
			$data['address'] = $address;
			$data['shift'] = $shift;
			$data['status'] = $status;
			$data['category'] = $category;
			$data['joindate'] = $joindate;
			if(!empty($picname)){
			$data['picture'] = $picname;
			}
			
			if($this->mm->update_info('t_salesperson', $data, $id)) 
			{
				$p = pathinfo($_FILES['pic']['name']);
				if(count($p)>2)
				{
					$imgfilename = $picname;
					$oldfile = $picname;
					$this->mm->image_upload2('./img/salesperson/' , '15000000', '5000', '3000', $imgfilename ,'270','310','pic',$oldfile);
				}
				redirect(base_url() . "salesperson_management/index?sk=Update Successfully", "refresh");
			} 
			else
			{
				redirect(base_url() . "salesperson_management/index?esk=Database too busy, Try later", "refresh");
			}
		}
		else
		{
			redirect(base_url() . "salesperson_management/edit_data/".$editid."?esk=".$msg, "refresh");
		} 
    }

    public function delete_data($id) 
	{
        if ($this->mm->delete_info('t_salesperson', array('id'=>$id))) 
		{
            $msg = "Deleted Successfully";
			redirect(base_url() . "salesperson_management/view_info?sk=".$msg, "refresh");
        } else {
            $emsg = "Database too busy";
			redirect(base_url() . "salesperson_management/view_info?esk=".$emsg, "refresh");
        }
    }
	
}
