<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Acl_management extends CI_Controller {
	
	public function __construct() 
	{
        parent::__construct();
		date_default_timezone_set("Asia/Dhaka");
    }

   public function rolecreate_form()
	{
		$data = array();
		$data['menu']= 'role';
		$data['permission']= $this->db->query("select * from t_permission")->result();
		$data['container']= $this->load->view('rolecreationpage',$data,true);
		$this->load->view('masteradmin',$data);
	}
    public function create_role()
	{
		if($_POST)
		{
			$rolegroup = $this->input->post('rolegroup');
			$permissionlist = $this->input->post('permissionlist'); 
			
			$seletedpermission="";
			if(count($permissionlist)>0)
			{
				$count = count($permissionlist);
				foreach($permissionlist as $ap)
				{
					$seletedpermission .=$ap;
					if(--$count >0)
					{
						$seletedpermission .=',';
					}
				}
			}  
			
			$err=0;
			if($rolegroup =="" || $seletedpermission == "")
			{
				$err++;
			}
			if($err==0)
			{
				$data = array();
				$data['group'] = $rolegroup;
				$data['permissionids'] = $seletedpermission;
				
				$result = $this->mm->insert_data('t_role',$data);
				if($result == true)
				{
					redirect('acl_management/rolecreate_form?msg=Saved Successfully');
				}
				else
				{
					redirect('acl_management/rolecreate_form?emsg=Database too busy try later');
				}
			}
			else
			{
				redirect('acl_management/rolecreate_form?emsg=Input Error!!!');
			}
		
		
		}
	}
	public function view_roll()
	{
		$data = array();
		$data['menu']= 'role';
		$data['roles']= $this->db->query("select * from t_role")->result();
		$data['permission']= $this->db->query("select * from t_permission")->result();
		$data['container']= $this->load->view('roleviewpage',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function roleedit($id)
	{//roleeditpage
		$data = array();
		$data['menu']= 'role';
		$data['selectedrole']= $this->db->query("select * from t_role where id='".$id."' ")->result();
		$data['permission']= $this->db->query("select * from t_permission")->result();
		$data['container']= $this->load->view('roleeditpage',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function update_role()
	{
		if($_POST)
		{
			$id = $this->input->post('id');
			$rolegroup = $this->input->post('rolegroup');
			$permissionlist = $this->input->post('permissionlist'); 
			
			$seletedpermission="";
			if(count($permissionlist)>0)
			{
				$count = count($permissionlist);
				foreach($permissionlist as $ap)
				{
					$seletedpermission .=$ap;
					if(--$count >0)
					{
						$seletedpermission .=',';
					}
				}
			}  
			
			$err=0;
			$emsg="";
			if($rolegroup =="")
			{
				$err++; $emsg .='Role group name required<br>';
			}
			if($seletedpermission == "")
			{
				$err++; $emsg .='select role<br>';
			}
			if($err==0)
			{
				$data = array();
				$data['group'] = $rolegroup;
				$data['permissionids'] = $seletedpermission;
				
				$result = $this->mm->update_info('t_role',$data,array('id'=>$id));
				if($result == true)
				{
					redirect('acl_management/view_roll?msg=Saved Successfully');
				}
				else
				{
					redirect('acl_management/view_roll?emsg=Database too busy try later');
				}
			}
			else
			{
				redirect('acl_management/roleedit?emsg='.$emsg);
			}
		
		
		}
	}
	public function delete_data($id) 
	{
		$userdata= $this->db->query("select * from t_user ")->result();
		$isexist="yes";
		if(count($userdata)>0)
		{
			foreach($userdata as $u)
			{
				if($u->roleid == $id)
				{
					$isexist="no";
				}
			}
		}
		if($isexist == "no")
		{
			if ($this->mm->delete_info('t_role', array('id'=>$id))) 
			{
				$msg = "Deleted Successfully";
				redirect("acl_management/view_roll?sk=".$msg, "refresh");
			} else {
				$emsg = "Database too busy";
				redirect("acl_management/view_roll?esk=".$emsg, "refresh");
			}
		}
		else
		{
			redirect("acl_management/view_roll", "refresh");
		}
        
    }
	
	///////User add, edit, delete //////////
	public function usercreate_form()
	{
		$data = array();
		$data['menu']= 'user';
		$data['permission']= $this->db->query("select * from t_permission")->result();
		$data['roles']= $this->db->query("select * from t_role")->result();
		$data['container']= $this->load->view('usercreationpage',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function usercreate_form_with_permission($id, $rolegroup)
	{//roleeditpage
		//$id = $this->input->post('id'); echo $id;exit;
		$data = array();
		$data['menu']= 'user';
		$data['rolegroup']= urldecode($rolegroup);
		$data['selectedrole']= $this->db->query("select * from t_role where id='".$id."' ")->result();
		$data['permission']= $this->db->query("select * from t_permission")->result();
		$data['roles']= $this->db->query("select * from t_role")->result();
		$data['users']= $this->db->query("select * from t_user")->result();
		$data['container']= $this->load->view('usercreationpage',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function create_user()
	{
		// username password name address email phone rolegroup
		if($_POST)
		{
			
			$username = trim($this->input->post('username'));
			$password = trim($this->input->post('password'));
			$usercode = trim($this->input->post('usercode'));
			$name = trim($this->input->post('name'));
			$address = trim($this->input->post('address'));
			$email = trim($this->input->post('email'));
			$phone = trim($this->input->post('phone'));
			$rolegroup = $this->input->post('rolegroup');
			$permissionlist = $this->input->post('permissionlist'); 
			
			$seletedpermission="";
			if(count($permissionlist)>0)
			{
				$count = count($permissionlist);
				foreach($permissionlist as $ap)
				{
					$seletedpermission .=$ap;
					if(--$count >0)
					{
						$seletedpermission .=',';
					}
				}
			} 
			
			$userdata = $this->db->query("SELECT * FROM  t_user WHERE username='".$username."' ")->result(); 
			$err =0;
			$emsg ="";
			if(count($userdata)>0){$emsg .= ++$err.'. User exist<br>';}
			if($rolegroup == ""){$emsg .= ++$err.'. Rolegroup Required<br>';}
			if($seletedpermission ==""){++$err.'. Rolegroup permission Required<br>';}
			
			if($username == ""){$emsg .= ++$err.'. Username Required<br>';}
			else{if(strlen($username)>100){$emsg .= ++$err.'. Username maximum lenght exceed<br>';}}
			
			if($password == ""){$emsg .= ++$err.'. Password Required<br>';}
			else{if(strlen($password)>20){$emsg .= ++$err.'. Password maximum lenght exceed<br>';}}
			
			if($usercode == ""){$emsg .= ++$err.'. Usercode Required<br>';}
			else{if(strlen($usercode)>50){$emsg .= ++$err.'. Usercode maximum lenght exceed<br>';}}
			
			if($name == ""){$emsg .= ++$err.'. Name Required<br>';}
			else{if(strlen($name)>150){$emsg .= ++$err.'. Name maximum lenght exceed<br>';}}
			
			if($address == ""){$emsg .= ++$err.'. Address Required<br>';}
			else{if(strlen($address)>255){$emsg .= ++$err.'. Address maximum lenght exceed<br>';}}
			
			if($email == ""){$emsg .= ++$err.'. Email Required<br>';}
			else
			{
				if(strlen($email)>150){$emsg .= ++$err.'. Email maximum lenght exceed<br>';}
				else{ if (!filter_var($email, FILTER_VALIDATE_EMAIL)){ $emsg .= ++$err.'. Email invalid<br>';}}
			}
			if($phone == ""){$emsg .= ++$err.'. Phone Required<br>';}
			else{if(strlen($phone)>40){$emsg .= ++$err.'. Phone maximum lenght exceed<br>';}}
			
			if($err ==0)
			{//username password name address email phone roleid
				$data = array();
				$data['username'] = $username; 
				$data['password'] = md5($password); 
				$data['access_code'] = $usercode;
				$data['name'] = $name; 
				$data['address'] = $address; 
				$data['email'] = $email; 
				$data['phone'] = $phone; 
				$data['rolegroup'] = $rolegroup; 
				$data['permission'] = $seletedpermission; 
				
				$result = $this->mm->insert_data('t_user',$data);
				if($result == true)
				{
					redirect('acl_management/usercreate_form?msg=Saved Successfully');
				}
				else
				{
					redirect('acl_management/usercreate_form?emsg=Database too busy try later');
				}
			}
			else
			{
				redirect('acl_management/usercreate_form?emsg='.$emsg);
			}
			
		}
		else
		{
			redirect(base_url());
		}
	}
	public function view_user()
	{
		$data = array();
		$data['menu']= 'user';
		$data['roles']= $this->db->query("select * from t_role")->result();
		$data['users']= $this->db->query("select * from t_user")->result();
		$data['container']= $this->load->view('usersviewpage',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function useredit($id)
	{
		$data = array();
		$data['menu']= 'user';
		$data['selecteduser']= $this->db->query("select * from t_user where id='".$id."' ")->result();
		$data['roles']= $this->db->query("select * from t_role")->result();
		$data['permission']= $this->db->query("select * from t_permission")->result();
		$data['container']= $this->load->view('usereditpage',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function useredit_form_with_permission($id, $rolegroup,$uid)
	{
		$data = array();
		$data['menu']= 'user';
		$data['rolegroup']= urldecode($rolegroup);
		$data['selectedrole']= $this->db->query("select * from t_role where id='".$id."' ")->result();
		$data['users']= $this->db->query("select * from t_user")->result();
		$data['selecteduser']= $this->db->query("select * from t_user where id='".$uid."' ")->result();
		$data['roles']= $this->db->query("select * from t_role")->result();
		$data['permission']= $this->db->query("select * from t_permission")->result();
		$data['container']= $this->load->view('usereditpage',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function update_user()
	{
		// username password name address email phone rolegroup
		if($_POST)
		{
			$id = $this->input->post('userid');
			$username = trim($this->input->post('username'));
			$password = trim($this->input->post('password'));
			$usercode = trim($this->input->post('usercode'));
			$name = trim($this->input->post('name'));
			$address = trim($this->input->post('address'));
			$email = trim($this->input->post('email'));
			$phone = trim($this->input->post('phone'));
			$rolegroup = $this->input->post('rolegroup');
			$permissionlist = $this->input->post('permissionlist'); 
			
			$seletedpermission="";
			if(count($permissionlist)>0)
			{
				$count = count($permissionlist);
				foreach($permissionlist as $ap)
				{
					$seletedpermission .=$ap;
					if(--$count >0)
					{
						$seletedpermission .=',';
					}
				}
			} 
			
			$err =0;
			$emsg ="";
			if($rolegroup == ""){$emsg .= ++$err.'. Rolegroup Required<br>';}
			
			if($username == ""){$emsg .= ++$err.'. Username Required<br>';}
			else{if(strlen($username)>100){$emsg .= ++$err.'. Username maximum lenght exceed<br>';}}
			
			if($password == ""){$emsg .= ++$err.'. Password Required<br>';}
			else{if(strlen($password)>20){$emsg .= ++$err.'. Password maximum lenght exceed<br>';}}
			
			if($usercode == ""){$emsg .= ++$err.'. Usercode Required<br>';}
			else{if(strlen($usercode)>50){$emsg .= ++$err.'. Usercode maximum lenght exceed<br>';}}
			
			if($name == ""){$emsg .= ++$err.'. Name Required<br>';}
			else{if(strlen($name)>150){$emsg .= ++$err.'. Name maximum lenght exceed<br>';}}
			
			if($address == ""){$emsg .= ++$err.'. Address Required<br>';}
			else{if(strlen($address)>255){$emsg .= ++$err.'. Address maximum lenght exceed<br>';}}
			
			if($email == ""){$emsg .= ++$err.'. Email Required<br>';}
			else
			{
				if(strlen($email)>150){$emsg .= ++$err.'. Email maximum lenght exceed<br>';}
				else{ if (!filter_var($email, FILTER_VALIDATE_EMAIL)){ $emsg .= ++$err.'. Email invalid<br>';}}
			}
			if($phone == ""){$emsg .= ++$err.'. Phone Required<br>';}
			else{if(strlen($phone)>40){$emsg .= ++$err.'. Phone maximum lenght exceed<br>';}}
			
			if($err ==0)
			{//username password name address email phone roleid
				$data = array();
				$data['username'] = $username; 
				$data['password'] = md5($password); 
				$data['access_code'] = $usercode;
				$data['name'] = $name; 
				$data['address'] = $address; 
				$data['email'] = $email; 
				$data['phone'] = $phone; 
				$data['rolegroup'] = $rolegroup; 
				$data['permission'] = $seletedpermission;  
				
				$result = $this->mm->update_info('t_user',$data,array('id'=>$id));
				if($result == true)
				{
					redirect('acl_management/view_user?msg=Update Successfully');
				}
				else
				{
					redirect('acl_management/view_user?emsg=Database too busy try later');
				}
			}
			else
			{
				redirect('acl_management/useredit/'.$id.'?emsg='.$emsg);
			}
			
		}
		else
		{
			redirect(base_url());
		}
	}
	public function delete_user($tid) 
	{
        $id = array("id" => $tid);
		//is customer exist in purchase table
		$isexist = "";
		$userdata = $this->db->query("Select * from t_user where id='".$tid."'")->result(); 
		
		if(count(userdata)>0) 
		{
			if ($this->mm->delete_info('t_user', $id)) 
			{
				$msg = "User Deleted Successfully";
				redirect("acl_management/view_user?sk=".$msg, "refresh");
			} 
			else 
			{
				$emsg = "Database too busy";
				redirect("acl_management/view_user?esk=".$emsg, "refresh");
			}
		}
    }
    

   

}
