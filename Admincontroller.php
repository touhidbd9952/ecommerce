<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admincontroller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Dhaka");
		//$this->mm->isLogin();  ///////// Call isLogin() for all function ////////////////////
	}
	public function index()
	{
		$this->admin_login();
		//$this->load->view('masteradmin');
	}
	public function admin_login()
	{
		$data = array();
		$data['content'] = $this->load->view('admin_login_form');
	}
	public function check_admin_login()
	{
		if($_POST)
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			if($username == 'admin')
			{
				$adminpass = $this->db->query("Select * from t_admin where username = '".$username."' ")->row()->password; 
				$adminpass = $this->mm->read_rc4_pass($username,$adminpass);
				
				if((string)$adminpass == (string)$password)
				{
					$ses = array();
					$usernamepass = $username.$adminpass;
					$ses['sid'] = md5(base64_encode($usernamepass));
					$ses['username'] = trim($username);
					$ses['userid'] = '1';
					$ses['time'] = time();
					$ses['login_time'] = date('Y-m-d h:i:s');
					$ses['ip'] = $_SERVER['REMOTE_ADDR'];
					$ses['loged_in'] = 'true';
					$this->mm->delete_info('t_user_online',array('username'=>$username));
					$this->mm->insert_data('t_user_online',$ses);
				
					$this->session->set_userdata($ses);
					redirect('admincontroller/adminpanel');
				}
				else{redirect('admincontroller/index?esk=Wrong username or password');}
			}
			else
			{
				//$operator = $this->db->query("Select * from t_salesperson where username = '".$username."' ")->result(); //print_r($operator);exit;
				$operator = $this->db->query("Select * from t_user where username = '".$username."' ")->result(); 
				$operatorpass="";$userid="";$permission="";$accesscode="";
				if(count($operator)>0)
				{
					foreach($operator as $d)
					{
						$operatorpass=$d->password;
						$userid=$d->access_code;
						$permission=$d->permission;
						$accesscode=$d->access_code;
					}
					if($operatorpass == md5($password))
					{
						$ses = array();
						$usernamepass = $username.$operatorpass;
						$ses['sid'] = md5(base64_encode($usernamepass));
						$ses['username'] = trim($username);
						$ses['userid'] = $userid;//code
						$ses['time'] = time();
						$ses['login_time'] = date('Y-m-d h:i:s');
						$ses['ip'] = $_SERVER['REMOTE_ADDR'];
						$ses['loged_in'] = 'true';
						$ses['permission'] = $permission;
						$ses['accesscode'] = $accesscode;
						$this->mm->delete_info('t_salesman_online',array('username'=>$username));
						$this->mm->insert_data('t_salesman_online',$ses);
					
						$this->session->set_userdata($ses); 
						$this->mm->SaveLogs('Login',"Operator ".$userid);
						redirect('admincontroller/adminpanel');
					}
					else{redirect('admincontroller/index?esk=Wrong username or password.');}
				}
				else{redirect('admincontroller/index?esk=Wrong username or password.');}
			}
		}
		else
		{
			redirect('main/index');
		}
	}
	public function adminpanel()
	{
		$username = $this->session->userdata('username');
		$sid = $this->session->userdata('sid');
		if($username == 'admin')
		{ 
			$siddb = $this->db->query("Select sid from t_user_online where sid='".$sid."'")->row()->sid;
		}
		else
		{
			$siddb = $this->db->query("Select sid from t_salesman_online where sid='".$sid."'")->row()->sid;
		}
		if((string)$siddb == (string)$sid)
		{
			redirect('admincontroller/dashboard');
		}		
	}
	public function admin_logout()
	{
		$username = $this->session->userdata('username');
		if($username != 'admin')
		{
			$userid = $this->session->userdata('userid');
			$this->mm->SaveLogs('Logout',"Sales Person ".$userid);
		}
		$this->session->unset_userdata('sid');
		$this->session->sess_destroy();
		redirect('main/index');
	}

	public function dashboard()
	{
		$purchase = $this->db->query("select * from  t_purchase")->result();
		$cyear = date("Y");
		$jan = $cyear.'-01';$feb = $cyear.'-02';$mar = $cyear.'-03';$apr = $cyear.'-04';
		$may = $cyear.'-05';$jun = $cyear.'-06';$jul = $cyear.'-07';$aug = $cyear.'-08';
		$sep = $cyear.'-09';$oct = $cyear.'-10';$nov = $cyear.'-11';$dec = $cyear.'-12';
		$m=array('0'=>'jan','1'=>'feb','2'=>'mar','3'=>'apr','4'=>'may','5'=>'jun','6'=>'jul','7'=>'aug','8'=>'sep','9'=>'oct','10'=>'nov','11'=>'dec'); 
		$salesmonth = array('0'=>$jan,'1'=>$feb,'2'=>$mar,'3'=>$apr,'4'=>$may,'5'=>$jun,'6'=>$jul,'7'=>$aug,'8'=>$sep,'9'=>$oct,'10'=>$nov,'11'=>$dec); 
		$salesorder = array();
		for($i=0;$i<count($salesmonth);$i++)
		{
			if($salesmonth[$i] != "")
			{
				$gr = "";$gr = $this->db->query("SELECT * FROM t_purchase WHERE dateoforder like '".$salesmonth[$i]."%'")->result();
				if(count($gr)>0)
				{
					$month = ""; $orpending = 0; $orcancel = 0; $orconfirm = 0; $orcomplete = 0;$month = $m[$i]; $totalor=0;
					foreach($gr as $p){++$totalor;if($p->orderstatus == ""){++$orpending;}if($p->orderstatus == "Conform"){++$orconfirm;}if($p->orderstatus == "Cencel"){++$orcancel;}if($p->orderstatus == "Complete"){++$orcomplete;}} array_push($salesorder,array("month"=>$month,"orpending"=>$orpending,"orconfirm"=>$orconfirm,"orcomplete"=>$orcomplete,"orcancel"=>$orcancel,"totalor"=>$totalor));
				}
			}
		}
		$preyear = $cyear-1;
		$data = array();
		$data['visitorinfo'] = $this->db->query("select * from t_visitor")->result();  //visitor info
		$data['customer'] = $this->db->query("select count(*)as totalcustomer from  t_customer ")->result();
		$data['updatedproduct'] = $this->db->query("select * from product order by date desc limit 4 ")->result();
		$data['salesorder'] = $salesorder;
		$data['preyearsalesorder'] = $this->db->query("SELECT * FROM t_purchase WHERE dateoforder like '".$preyear."%'")->result();
		$data['purchase'] = $purchase;
		$data['allorders'] = $this->db->query("select * from  t_purchase order by id desc limit 7 ")->result();
		$data['container'] = $this->load->view('dashboard',$data,true);
		$this->load->view('masteradmin',$data);
	}
	
	function image_upload ($destinationFolder , $maxSize , $maxWidth , $maxHeight , $fileName,$image1_width,$image1_height,$pic) 
	{
 		$config = array ();
		$config['upload_path'] = $destinationFolder ; 
		$config['allowed_types'] = 'gif|jpg|png|jpeg' ; 
		$config['max_size'] = $maxSize ; 
		$config['max_width'] = $maxWidth ;  
		$config['max_height'] = $maxHeight ; 
		$config['file_name'] = $fileName ; 

 		$this->upload->initialize($config ); 
 		$this->upload->do_upload($pic); 
		
		//Image Thumbnail
		
		$config = array();
		$config['source_image'] = $destinationFolder.$fileName;
		$config['new_image'] = $destinationFolder;
		$config['admintain_ratio'] = FALSE;
		if($image1_width == "" || $image1_height == "")
		{
			$image2_width = 300;
			$image2_height = 300;
		}
		$config['width'] = $image1_width;
		$config['height'] = $image1_height;
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		$this->image_lib->clear(); 
		
 	}
	function image_upload2 ($destinationFolder , $maxSize , $maxWidth , $maxHeight , $fileName,$image1_width,$image1_height,$pic,$oldfile) 
	{
		//delete orginal file
		$oldfile = $destinationFolder.$oldfile;
		if(file_exists($oldfile))
		{
			unlink($oldfile);
		} 
		//new file
 		$config = array ();
		$config['upload_path'] = $destinationFolder ; 
		$config['allowed_types'] = 'gif|jpg|png|jpeg' ; 
		$config['max_size'] = $maxSize ; 
		$config['max_width'] = $maxWidth ;  
		$config['max_height'] = $maxHeight ; 
		$config['file_name'] = $fileName ; 

 		$this->upload->initialize($config ); 
 		$this->upload->do_upload($pic); 
		
		//Image Thumbnail
		
		$config = array();
		$config['source_image'] = $destinationFolder.$fileName;
		$config['new_image'] = $destinationFolder;
		$config['admintain_ratio'] = FALSE;
		if($image1_width == "" || $image1_height == "")
		{
			$image2_width = 300;
			$image2_height = 300;
		}
		$config['width'] = $image1_width;
		$config['height'] = $image1_height;
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		$this->image_lib->clear(); 
		
		
 	}
	
	/////////first delete old file/////////////////////
	function image_upload3 ($destinationFolder , $maxSize , $maxWidth , $maxHeight , $fileName,$image1_width,$image1_height,$pic,$oldfile) 
	{
		//first delete old file
		$oldfile = $destinationFolder.$oldfile;
		if(file_exists($oldfile))
		{
			unlink($oldfile);
		}
		
 		$config = array ();
		$config['upload_path'] = $destinationFolder ; 
		$config['allowed_types'] = 'gif|jpg|png|jpeg' ; 
		$config['max_size'] = $maxSize ; 
		$config['max_width'] = $maxWidth ;  
		$config['max_height'] = $maxHeight ; 
		$config['file_name'] = $fileName ; 

 		$this->upload->initialize($config ); 
 		$this->upload->do_upload($pic); 
		
		//Image Thumbnail
		
		$config = array();
		$config['source_image'] = $destinationFolder.$fileName;
		$config['new_image'] = $destinationFolder;
		$config['admintain_ratio'] = FALSE;
		if($image1_width == "" || $image1_height == "")
		{
			$image2_width = 300;
			$image2_height = 300;
		}
		$config['width'] = $image1_width;
		$config['height'] = $image1_height;
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		$this->image_lib->clear();  
 	}
	public function chatlink()
	{
		$data = array();
		$data['menu'] = 'chatlink';
		$data['container'] = $this->load->view('setting_chatting_form',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function update_chatlink()
	{
		$chatlink = $this->input->post('chatlink');
		$data = array();
		$data['value'] = $chatlink;
		if($this->mm->update_info('t_settings',$data,array('name'=>'chatlink')))
		{
			redirect("admincontroller/chatlink?sk=Successfully updated");
		}
		else
		{
			redirect("admincontroller/chatlink?esk=Error!!!, No updated");
		}
	}
	public function branding()
	{
		$data = array();
		$data['menu'] = 'branding';
		$data['container'] = $this->load->view('setting_branding_form',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function update_branding()
	{
		$permission = $this->session->userdata('permission');
		$acl = 77;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$companyName = $this->input->post('companyName');
			$aboutCompanywork = $this->input->post('aboutCompanywork');
			$address = $this->input->post('address');
			$phone = $this->input->post('phone');
			$fax = $this->input->post('fax');
			$email = $this->input->post('email');
			
			$logo=""; if(isset($_FILES['pic'])){ $p1 = pathinfo($_FILES['pic']['name']); if(count($p1)>2){$logo = 'logo.png'; }}
			$favicon=""; if(isset($_FILES['pic1'])){ $p2 = pathinfo($_FILES['pic1']['name']); if(count($p2)>2){$favicon = 'favicon.png'; }}
			$payment_methodimg=""; if(isset($_FILES['pic2'])){ $p3 = pathinfo($_FILES['pic2']['name']); if(count($p3)>2){$payment_methodimg = 'paymethodimg.png'; }}
			$setbrandnameaslogo = $this->input->post('setbrandnameaslogo'); 
			
			$footerCopyrighttext = $this->input->post('footerCopyrighttext');
			if(!empty($companyName)){$data = array();$data['value'] = trim($companyName);
				$this->mm->update_info('t_settings',$data,array('name'=>'Company Name'));}	
			if(!empty($aboutCompanywork)){$data = array();$data['value'] = trim($aboutCompanywork);
				$this->mm->update_info('t_settings',$data,array('name'=>'About Company work'));}
			if(!empty($address)){$data = array();$data['value'] = trim($address);
				$this->mm->update_info('t_settings',$data,array('name'=>'Address'));}
			if(!empty($phone)){$data = array();$data['value'] = trim($phone);
				$this->mm->update_info('t_settings',$data,array('name'=>'phone'));}
			if(!empty($fax)){$data = array();$data['value'] = trim($fax);
				$this->mm->update_info('t_settings',$data,array('name'=>'fax'));}
				
			if(!empty($email)){$data = array();$data['value'] = trim($email);
				$this->mm->update_info('t_settings',$data,array('name'=>'servermail'));}
				
			if(!empty($footerCopyrighttext)){$data = array();$data['value'] = trim($footerCopyrighttext);
				$this->mm->update_info('t_settings',$data,array('name'=>'Footer Copyright text'));}
			
			if($setbrandnameaslogo =='on'){$data = array();$data['value'] = 'brand';
				$this->mm->update_info('t_settings',$data,array('name'=>'brand or logo'));}
			else if($setbrandnameaslogo !='on'){$data = array();$data['value'] = 'logo';
				$this->mm->update_info('t_settings',$data,array('name'=>'brand or logo'));}
				
			if($logo!="")
			{
				$data = array();$data['value'] = trim($logo);
				$this->mm->update_info('t_settings',$data,array('name'=>'logo'));
				
				$imgfilename = $logo;
				$oldfile = $logo;
				$this->mm->image_upload2('./img/' , '15000000', '5000', '3000', $imgfilename ,'250','60','pic',$oldfile);
			}
			if($favicon!="")
			{
				$data = array();$data['value'] = trim($favicon);
				$this->mm->update_info('t_settings',$data,array('name'=>'favicon'));
				
				$imgfilename = $favicon;
				$oldfile = $favicon;
				$this->mm->image_upload2('./img/' , '15000000', '5000', '3000', $imgfilename ,'20','20','pic1',$oldfile);
			}
			if($payment_methodimg!="")
			{
				$data = array();$data['value'] = trim($payment_methodimg);
				$this->mm->update_info('t_settings',$data,array('name'=>'paymethodimg'));
				
				$imgfilename = $payment_methodimg;
				$oldfile = $payment_methodimg;
				$this->mm->image_upload2('./img/' , '15000000', '5000', '3000', $imgfilename ,'250','50','pic2',$oldfile);
			}
			
			$this->mm->SaveLogs('update','update branding');	
			redirect("admincontroller/branding?sk=Updated");
		}
		else
		{
			redirect(base_url() . "admincontroller/branding?esk=No Access");
		}
	}
	public function mail_setting()
	{
		$data = array();
		$data['menu'] = 'mail_setting';
		$data['container'] = $this->load->view('mail_setting_form',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function mail_setting_update()
	{
		$permission = $this->session->userdata('permission');
		$acl = 82;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$smtpname=$this->input->post('smtpname');
			$smtpport=$this->input->post('smtpport');
			$smtpuser=$this->input->post('smtpuser');
			$smtppass=$this->input->post('smtppass');
			$mailsendvia = $this->input->post('mailsendvia');
			
			if(!empty($smtpname)){$data = array();$data['value'] = trim($smtpname);
				$this->mm->update_info('t_settings',$data,array('name'=>'smtp_host'));}
			if(!empty($smtpport)){$data = array();$data['value'] = trim($smtpport);
				$this->mm->update_info('t_settings',$data,array('name'=>'smtp_port'));}
			if(!empty($smtpuser)){$data = array();$data['value'] = trim($smtpuser);
				$this->mm->update_info('t_settings',$data,array('name'=>'smtp_user'));}
			if(!empty($smtppass)){$data = array();$data['value'] = trim($smtppass);
				$this->mm->update_info('t_settings',$data,array('name'=>'smtp_pass'));}	
			if(!empty($mailsendvia)){$data = array();$data['value'] = trim($mailsendvia);
				$this->mm->update_info('t_settings',$data,array('name'=>'mailsendvia'));}	
			
			$this->mm->SaveLogs('update','sptp info');	
			redirect("admincontroller/mail_setting?sk=Updated");
		}
		else
		{
			redirect(base_url() . "admincontroller/mail_setting?esk=No Access");
		}
	}
	public function about_company()
	{
		$data = array();
		$data['menu'] = 'about_company';
		$data['container'] = $this->load->view('about_company_form',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function update_company_info()
	{
		$permission = $this->session->userdata('permission');
		$acl = 78;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$aboutCompanywork = $this->input->post('aboutcompany'); 
			if(!empty($aboutCompanywork)){$data = array();$data['value'] = trim($aboutCompanywork);
				$this->mm->update_info('t_settings',$data,array('name'=>'About Company work'));}
			$this->mm->SaveLogs('update','update_company_info');
			redirect("admincontroller/about_company?sk=Updated");
		}
		else
		{
			redirect(base_url() . "admincontroller/about_company?esk=No Access");
		}
	}
	
	public function company_policy()
	{
		$data = array();
		$data['menu'] = 'company_policy';
		$data['container'] = $this->load->view('policy_form',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function update_company_policy()
	{
		$permission = $this->session->userdata('permission');
		$acl = 80;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$policy = $this->input->post('policy'); 
			
			if($policy !="")
			{
				$data = array();$data['value'] = trim($policy);
				if($this->mm->update_info('t_settings',$data,array('name'=>'policy')))
				{
					$this->mm->SaveLogs('update','update_company_policy');
				}
			}	
			
			redirect("admincontroller/company_policy?sk=Updated");
		}
		else
		{
			redirect(base_url() . "admincontroller/company_policy?esk=No Access");
		}
	}
	
	public function return_policy()
	{
		$data = array();
		$data['menu'] = 'return_policy';
		$data['container'] = $this->load->view('return_policy_form',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function update_return_policy()
	{
		$permission = $this->session->userdata('permission');
		$acl = 84;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$policy = $this->input->post('policy'); 
			
			if($policy !="")
			{
				$data = array();$data['value'] = trim($policy);
				if($this->mm->update_info('t_settings',$data,array('name'=>'return policy')))
				{
					$this->mm->SaveLogs('update','update_return_policy');
				}
			}	
			
			redirect("admincontroller/return_policy?sk=Updated");
		}
		else
		{
			redirect(base_url() . "admincontroller/return_policy?esk=No Access");
		}
	}
	
	public function company_termsandcondition()
	{
		$data = array();
		$data['menu'] = 'company_policy';
		$data['container'] = $this->load->view('termsandcondition_form',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function update_company_terms()
	{
		$permission = $this->session->userdata('permission');
		$acl = 81;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$terms = $this->input->post('terms');
			
			if(!empty($terms)){$data = array();$data['value'] = trim($terms);
				$this->mm->update_info('t_settings',$data,array('name'=>'terms'));}	
			$this->mm->SaveLogs('update','update_company_terms');
			redirect("admincontroller/company_termsandcondition?sk=Updated");
		}
		else
		{
			redirect(base_url() . "admincontroller/company_termsandcondition?esk=No Access");
		}
	}
	public function help_center()
	{
		$data = array();
		$data['menu'] = 'help_center';
		$data['container'] = $this->load->view('help_center_form',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function update_help_center()
	{
		$permission = $this->session->userdata('permission');
		$acl = 85;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$helpcenterdata = $this->input->post('helpcenterdata');
			
			if(!empty($helpcenterdata)){$data = array();$data['value'] = trim($helpcenterdata);
				$this->mm->update_info('t_settings',$data,array('name'=>'helpcenter'));}	
			$this->mm->SaveLogs('update','update_help_center');
			redirect("admincontroller/help_center?sk=Updated");
		}
		else
		{
			redirect(base_url() . "admincontroller/help_center?esk=No Access");
		}
	}
	public function sociallink()
	{
		$data = array();
		$data['menu'] = 'link';
		$data['container'] = $this->load->view('setting_link_form',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function update_sociallink()
	{
		$permission = $this->session->userdata('permission');
		$acl = 79;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$facebook = $this->input->post('facebook');
			$googleplus = $this->input->post('googleplus');
			$twitter = $this->input->post('twitter');
			$forum = $this->input->post('forum');
			
			if(!empty($facebook)){$data = array();$data['value'] = trim($facebook);
				$this->mm->update_info('t_settings',$data,array('name'=>'facebook'));}	
			if(!empty($googleplus)){$data = array();$data['value'] = trim($googleplus);
				$this->mm->update_info('t_settings',$data,array('name'=>'google plus'));}	
			if(!empty($twitter)){$data = array();$data['value'] = trim($twitter);
				$this->mm->update_info('t_settings',$data,array('name'=>'twitter'));}	
			if(!empty($forum)){$data = array();$data['value'] = trim($forum);
				$this->mm->update_info('t_settings',$data,array('name'=>'forum'));}
			$this->mm->SaveLogs('update','update_sociallink');
			redirect("admincontroller/sociallink?sk=Updated");
		}
		else
		{
			redirect(base_url() . "admincontroller/sociallink?esk=No Access");
		}
	}
	////
	
	public function change_admin()
	{
		$data = array();
		$data['menu'] = 'login';
		$data['container'] = $this->load->view('admin_login_info',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function change_admin_logininfo()
	{
		if($_POST)
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			$err = 0;
			$msg = "";
			if($username == ""){$msg .=++$err.". UserName Required<br>";}
			if($password == ""){$msg .=++$err.". Password Required<br>";}
			if($err == 0)
			{
				$query = $this->db->query("Select * from t_admin")->result();
				$data = array();
				$data['username'] = trim($username);
				$data['password'] = $this->mm->insert_rc4_pass($username,$password);
				
				if(count($query)>0)
				{
					if($this->mm->update_info('t_admin',$data,array('id'=>1))){
						$this->mm->SaveLogs('update','change_admin_logininfo');
						redirect('admincontroller/change_admin?sk=Updated Successfully');	
					}
					else{redirect('admincontroller/change_admin?esk=Error!!! Try later');}
				}
				else
				{	
					$insertid = $this->mm->insert_data('t_admin',$data);
					if($insertid != ""){redirect('admincontroller/change_admin?sk=Saved Successfully');}
					else{redirect('admincontroller/change_admin?esk=Error!!! Try later');}
				}
			}
			else{redirect('admincontroller/change_admin?esk='.$msg);}
		}
		else{redirect('main/index');}
	}
	
	////////////// Product Section Start //////////////////////////////////////////////////////////////////
	
	public function product_category()
	{
		$data = array();
		$data['menu'] = 'category';
		$data['allcategory'] = $this->db->query("select * from t_productcategory")->result();
		$data['content'] = $this->load->view('product_category_form',$data,true);
		$this->load->view('masteradmin',$data);
	}
	
	////////////// Product Section End //////////////////////////////////////////////////////////////////
	public function order_request()
	{
		$data = array();
		$data['menu'] = 'orderreq';
		$data['allorderreq'] = $this->db->query("select * from  t_purchase where paymentstatus = 'Pending' and orderstatus !='Conform' and orderstatus !='Cencel' order by id desc ")->result();
		$data['allorderreqlist'] = $this->db->query("select * from  t_purchase where paymentstatus = 'Pending' and orderstatus !='Conform' and orderstatus !='Cencel' order by id desc")->result();
		$data['container'] = $this->load->view('order_request_show',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function single_order_request_show($orderno)
	{
		$data = array();
		$data['menu'] = 'orderreq';
		$data['singleorderreq'] = $this->db->query("select * from  t_purchase where orderid = '".$orderno."'")->result();
		$data['orderno'] = $orderno;
		$data['container'] = $this->load->view('single_order_request_show',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function single_order_cancelled_show($orderno)
	{
		$data = array();
		$data['menu'] = 'orderreq';
		$data['singleorderreq'] = $this->db->query("select * from  t_purchase where orderid = '".$orderno."'")->result();
		$data['orderno'] = $orderno;
		$data['container'] = $this->load->view('single_order_canceled_show',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function orders()
	{
		$data = array();
		$data['menu'] = 'orders';
		
		$data['menu'] = 'orderreq';
		$data['allorders'] = $this->db->query("select * from  t_purchase order by id desc ")->result();
		$data['allorderreqlist'] = $this->db->query("select * from  t_purchase order by id desc")->result();
		$data['container'] = $this->load->view('total_orders_show',$data,true);
		
		$this->load->view('masteradmin',$data);
	}
	public function order_cancelled()
	{
		$data = array();
		$data['menu'] = 'order_cancelled';
		
		$data['menu'] = 'orderreq';
		$data['allordercencelled'] = $this->db->query("select * from  t_purchase where paymentstatus = 'Pending' and orderstatus ='Cencel' order by id desc ")->result();
		$data['allorderreqlist'] = $this->db->query("select * from  t_purchase where paymentstatus = 'Pending' and  orderstatus ='Cencel' order by id desc")->result();
		$data['container'] = $this->load->view('order_cancelled_show',$data,true);
		
		$this->load->view('masteradmin',$data);
	}
	public function order_shipped()
	{
		$data = array();
		$data['menu'] = 'order_shipped';
		
		$data['menu'] = 'orderreq';
		$data['allorder'] = $this->db->query("select * from  t_purchase where orderstatus !='Cencel'  order by id desc ")->result();
		//$data['allorderlist'] = $this->db->query("select * from  t_purchase  order by id desc")->result();
		$data['container'] = $this->load->view('order_shipped_status',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function confirm_salesperson_purchase_order()
	{//tcc rpayment discount
		$permission = $this->session->userdata('permission');
		$acl = 70;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$orderno = $this->input->post('orderno');
			 $tcc = $this->input->post('tcc');//
			 $rpayment = $this->input->post('rpayment');//
			 $discount = $this->input->post('discount');//
			 $returnamt = $this->input->post('returnamt');//
			$salesperson = $this->input->post('salesperson'); 
			$purchaseinfo = $this->db->query("select * from  t_purchase where orderid = '".$orderno."'")->result(); 
			$details = "";
			$customerename ="";
			$customeremail = "";
			$purchaseid="";
			foreach($purchaseinfo as $pur)
			{
				$details = json_decode($pur->details);
				
				$customeredata =$this->db->query("select * from  t_customer where id = '".$pur->customerid."'")->result(); 
				$purchaseid=$pur->id;
			}
			if(count($customeredata)>0)
			{
				$customerename = $customeredata[0]->firstname;
				if($customeredata[0]->lastname !=""){$customerename .= " ".$customeredata[0]->lastname;}
				$customeremail = $customeredata[0]->email;
			}
			//puduct update
			foreach($details as $d)
			{
				$productid = $d->productid;
				$qty = $d->qty;
				$productstock = $this->db->query("select stock from product where id='".$productid."'")->row()->stock;
				if(($productstock - $qty) >= 0)
				{
					$productstock = $productstock - $qty;
				}
				
				$data = array();
				$data['stock'] = $productstock;
				$this->mm->update_info('product',$data,array('id'=>$productid));
			}
			//purchase update
			//create invoiceno
			if($purchaseid != "")
			{
				$invoiceno = $purchaseid;
			}
			else
			{
				$invoiceno = 1;
			}
			$rpayment = $rpayment - $returnamt;
			
			$data = array();
			$data['order_total_price'] = $tcc;//
			$data['receive_amt'] = $rpayment;//
			$data['discount_on_order'] = $discount;//
			$data['orderstatus'] = "Conform";
			$data['salespersonid'] = $salesperson;
			$data['invoiceno'] = $invoiceno;
			if($this->mm->update_info('t_purchase',$data,array('orderid'=>$orderno)))
			{
				$this->mm->SaveLogs('Conform',"Order '".$orderno."' Conform by ".$salesperson);
				
				///////notification///////////////////////////
				$notify = array();
				$notify['date'] = date('Y-m-d h:i:s');
				$notify['notification'] = 'Order: '.$orderno.' is confirmed';
				$this->mm->insert_data('t_notification',$notify);
				
				///////mail start //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			$settingdata = $this->db->query("SELECT *  FROM t_settings ")->result();
			$storename="";
			$smtp_host = "";$smtp_port = "";$smtp_user = "";$smtp_pass = "";$mailsendvia =""; $servermail ="";$companyname="";$companyaddress = "";$companyemail = "";$companyphone = "";
			$siteurl = "";$brandorlogo="brand";
			
			
			$customername = $this->session->userdata('cusfirstname');
			$customerlastname = $this->session->userdata('cuslastname');
			if($customerlastname !=""){$customername .= $customerlastname;}
			
			foreach($settingdata as $d)
			{
				if($d->name == 'smtp_host'){$smtp_host = $d->value;}
				if($d->name == 'smtp_port'){$smtp_port = $d->value;}
				if($d->name == 'smtp_user'){$smtp_user = $d->value;}
				if($d->name == 'smtp_pass'){$smtp_pass = $d->value;}
				if($d->name == 'mailsendvia'){$mailsendvia = $d->value;}
				if($d->name == 'servermail'){$servermail = $d->value;}
				if($d->name == 'Company Name'){$companyname = $d->value;}
				if($d->name == 'Address'){$companyaddress = $d->value;}
				if($d->name == 'servermail'){$companyemail = $d->value;}
				if($d->name == 'phone'){$companyphone = $d->value;}
				if($d->name == 'brand or logo'){$brandorlogo = $d->value;}
			}
			$siteurl = base_url();
			$orderdata = $this->db->query("SELECT * FROM t_purchase WHERE orderid='".$orderno."' ")->result();$customername =""; $cusemai="";$cusphone="";$cuscountry="";$deliverylocation="";$contacturl = base_url()."main/contact";$paymethod="";
			$deliverylocation="";
			foreach($orderdata as $od)
			{
				$customerid = $od->customerid;
				$deliverylocation = $od->deliveryloc;
				$paymethod = $od->paymethod;
				$details = json_decode($od->details); 
				$customer = $this->db->query("SELECT * FROM t_customer WHERE id ='".$customerid."'")->result(); 
				foreach($customer as $cus)
				{
					$customername = $cus->firstname;
					if($cus->lastname !=""){$customername .=' '.$cus->lastname;}
					$cusemail = $cus->email;$cusphone = $cus->phone;
					$cuscountry = $cus->country;
				}
			}
$message = '<table class="m_-8065932370111914007m_-3734552349517890535body" style="height:100%!important;width:100%!important;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table class="m_-8065932370111914007m_-3734552349517890535header m_-8065932370111914007m_-3734552349517890535row" style="width:100%;border-spacing:0;border-collapse:collapse;margin:40px 0 20px"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535header__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table class="m_-8065932370111914007m_-3734552349517890535row" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535shop-name__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <h1 class="m_-8065932370111914007m_-3734552349517890535shop-name__text" style="font-weight:normal;font-size:30px;color:#333;margin:0"> <a href="'.$siteurl.'" style="font-size:30px;color:#333;text-decoration:none" target="_blank">';

if($brandorlogo=='logo'){
$message .= '<img src="'.$siteurl.'img/logo.png" alt="'.$companyname.'">';
} else{ 
$message .= '<span style="color:#2777BB">'.$companyname.'</span>';}

$message .= '</a> </h1> </td> <td class="m_-8065932370111914007m_-3734552349517890535order-number__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;text-transform:uppercase;font-size:14px;color:#999" align="right"> <span class="m_-8065932370111914007m_-3734552349517890535order-number__text" style="font-size:16px"> Order #'.$orderno.' </span> </td> </tr> </tbody></table> </td> </tr> </tbody></table> </center> </td> </tr></tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535content" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535content__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <h2 style="font-weight:normal;font-size:24px;margin:0 0 10px">Thank you for your purchase! </h2> <p style="color:#777;line-height:150%;font-size:16px;margin:0"> Hi '.$customername.', Your products have been shipped. </p> </td> </tr> </tbody></table> </center> </td> </tr></tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535section" style="width:100%;border-spacing:0;border-collapse:collapse;border-top-width:1px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535section__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:40px 0"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <h3 style="font-weight:normal;font-size:20px;margin:0 0 25px">Order summary</h3> </td> </tr> </tbody></table>'; 
					
					$grandtotal = 0;$subtotal = 0;$totaldiscount = 0;$totalvat = 0;$totalprice = 0;$customerid = "";$couponvalue =0;foreach($orderdata as $s){$customerid = $s->customerid;$customer = $this->db->query("SELECT * FROM t_customer WHERE id ='".$customerid."'")->result(); 
					
						
						
						foreach($details as $d)
						{
							$productid = $d->productid;$price = $d->price; //unitprice
							$discount = $d->discount;$vat = $d->vat;$qty = $d->qty; //$size = $d->size;
							$color = $d->color;
							$product = $this->db->query("SELECT * FROM product WHERE id ='".$productid."'")->result(); 
							foreach($product as $p)
							{
								$code = $p->code; // code
								$title = $p->title; // title
								$stock = $p->stock;
								$imagename = $p->picture;
							
$message .= '<table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table class="m_-8065932370111914007m_-3734552349517890535row" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr class="m_-8065932370111914007m_-3734552349517890535order-list__item" style="width:100%"> <td class="m_-8065932370111914007m_-3734552349517890535order-list__item__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table style="border-spacing:0;border-collapse:collapse"> <tbody> <tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <img src="'.$siteurl.'img/product/'.$imagename.'" class="m_-8065932370111914007m_-3734552349517890535order-list__product-image CToWUd" style="margin-right:15px;border-radius:8px;border:1px solid #e5e5e5" width="60" height="60" align="left"> </td> <td class="m_-8065932370111914007m_-3734552349517890535order-list__product-description-cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;width:100%"> <span class="m_-8065932370111914007m_-3734552349517890535order-list__item-title" style="font-size:16px;font-weight:600;line-height:1.4;color:#555">'.$title.'</span><br> <span class="m_-8065932370111914007m_-3734552349517890535order-list__item-variant" style="font-size:14px;color:#999">'.$qty.'</span><br> </td> <td class="m_-8065932370111914007m_-3734552349517890535order-list__price-cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;white-space:nowrap"> <p class="m_-8065932370111914007m_-3734552349517890535order-list__item-price" style="color:#555;line-height:150%;font-size:16px;font-weight:600;margin:0 0 0 15px" align="right">'. number_format($price,2).'</p> </td> </tr></tbody></table> </td> </tr></tbody></table>';$totalprice += $price * $qty; 

			//total
			$totaldiscount += $discount; //total discount
			$totalvat += $vat; //total vat
			$subtotal += (($price * $qty)+ $vat) ; //grand total
						}
					}
			$couponvalue = $s->couponvalue; //coupon
			$deliverycharge = $s->deliverycharge; // delivery charge
			$paymethod = $s->paymethod;$paymentstatus = $s->paymentstatus;}
			$subtotal = $subtotal+$deliverycharge;
$message .=' <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535subtotal-lines" style="width:100%;border-spacing:0;border-collapse:collapse;margin-top:15px;border-top-width:1px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-spacer" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;width:40%"></td> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535subtotal-table" style="width:100%;border-spacing:0;border-collapse:collapse;margin-top:20px"> <tbody><tr class="m_-8065932370111914007m_-3734552349517890535subtotal-line"> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__title" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0"> <p style="color:#777;line-height:1.2em;font-size:16px;margin:0"> <span style="font-size:16px">Subtotal</span> </p> </td> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__value" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0" align="right"> <strong style="font-size:16px;color:#555">'.number_format($totalprice,2).'</strong> </td></tr> <tr class="m_-8065932370111914007m_-3734552349517890535subtotal-line"> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__title" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0"> <p style="color:#777;line-height:1.2em;font-size:16px;margin:0"> <span style="font-size:16px">Shipping</span> </p> </td> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__value" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0" align="right"> <strong style="font-size:16px;color:#555">'.number_format($deliverycharge,2).'</strong> </td></tr> <tr class="m_-8065932370111914007m_-3734552349517890535subtotal-line"> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__title" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0"> <p style="color:#777;line-height:1.2em;font-size:16px;margin:0"> <span style="font-size:16px">VAT</span> </p> </td> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__value" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0" align="right"> <strong style="font-size:16px;color:#555">'.number_format($totalvat,2).'</strong> </td></tr> </tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535subtotal-table m_-8065932370111914007m_-3734552349517890535subtotal-table--total" style="width:100%;border-spacing:0;border-collapse:collapse;margin-top:20px;border-top-width:2px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr class="m_-8065932370111914007m_-3734552349517890535subtotal-line"> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__title" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:20px 0 0"> <p style="color:#777;line-height:1.2em;font-size:16px;margin:0"> <span style="font-size:16px">Total</span> </p> </td> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__value" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:20px 0 0" align="right"> <strong style="font-size:24px;color:#555">'.number_format($subtotal,2).'</strong> </td></tr> </tbody></table> </td> </tr></tbody></table> </td> </tr> </tbody></table> </center> </td> </tr></tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535section" style="width:100%;border-spacing:0;border-collapse:collapse;border-top-width:1px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535section__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:40px 0"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <h3 style="font-weight:normal;font-size:20px;margin:0 0 25px">Customer information</h3> </td> </tr> </tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table class="m_-8065932370111914007m_-3734552349517890535row" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td colspan="2" class="m_-8065932370111914007m_-3734552349517890535customer-info__item" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px;width:50%"> <h4 style="font-weight:500;font-size:16px;color:#555;margin:0 0 5px">Shipping address</h4> <p style="color:#777;line-height:150%;font-size:16px;margin:0">'.$customername.'<br>'.$deliverylocation.'</p> </td> </tr> </tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td colspan="2" class="m_-8065932370111914007m_-3734552349517890535customer-info__item" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px;width:50%"> <h4 style="font-weight:500;font-size:16px;color:#555;margin:0 0 5px">Payment method</h4> <p class="m_-8065932370111914007m_-3734552349517890535customer-info__item-content" style="color:#777;line-height:150%;font-size:16px;margin:0"> '.$paymethod.' — <strong style="font-size:16px;color:#555">'.number_format($subtotal,2).'</strong> </p> </td> </tr> </tbody></table> </td> </tr> </tbody></table> </center> </td> </tr></tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535footer" style="width:100%;border-spacing:0;border-collapse:collapse;border-top-width:1px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535footer__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:35px 0"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <p class="m_-8065932370111914007m_-3734552349517890535disclaimer__subtext" style="color:#999;line-height:150%;font-size:14px;margin:0"><a href="'.$siteurl.'main/customer_login" style="font-size:14px;text-decoration:none;color:#1990c6" target="_blank">View your order</a> \ <a href="'.$siteurl.'">Visit our store</a> \ <a href="'.$contacturl.'" style="font-size:14px;text-decoration:none;color:#1990c6" target="_blank">contact us</a></p><br><br> </td></tr><tr><td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"><p class="m_-8065932370111914007m_-3734552349517890535disclaimer__subtext" style="color:#999;line-height:150%;font-size:14px;margin: -20px 0 0 0;">'.$companyname.', Address: '.$companyaddress.', Email: '.$companyemail.', Phone: '.$companyphone.'</p></td> </tr></tbody></table> </td> </tr> </tbody></table>';
			
			
				$to = $cusemail;
				$subject = "Order #".$orderno." confirmed";
				$sendresult="";
				if($mailsendvia == "smtp")
				{
					$sendresult = $this->mm->send_mail($smtp_host,$smtp_port,$smtp_user,$smtp_pass,$to,$storename,$subject,$message);
				}
				else
				{ 
					// To send HTML mail
					$headers[] = 'MIME-Version: 1.0';
					$headers[] = 'Content-type: text/html; charset=iso-8859-1';
					// Additional headers
					$headers[] = 'To:'.$servermail;
					$headers[] = 'From:'.$servermail;
					
					$sendresult = mail($to,$subject,$message,implode("\r\n", $headers)); 
				} 
			
			
			////////////////  Mail End///////////////////////////////////////////////////
				
				redirect('admincontroller/single_order_sales_report/'.$orderno);
			}
		}
		else
		{
			redirect("admincontroller/order_request?esk=No Access");
		}
	}
	
	public function single_order_sales_report($orderno)
	{
		$data = array();
		$data['menu'] = 'orderreq';
		$data['singleorderreq'] = $this->db->query("select * from  t_purchase where orderid = '".$orderno."'")->result();
		$data['orderno'] = $orderno;
		//print_r($data['allorderreq']);exit;
		$data['container'] = $this->load->view('single_order_sales_report',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function order_confirm_show()
	{
		//select all paid order for print and show in a page
		$data = array();
		$data['menu'] = 'order_confirm';
		$data['per_page'] = 25;
		$limit_start = 0;
		$limit_end = 0;
        if (isset($_GET['page'])&&!empty($_GET['page']) ) 
		{
            $data['start'] = ($_GET['page'] - 1) * $data['per_page'] + 1;  //$data['start'] = ($_GET['page'] - 1) * $data['per_page'] + 1; 
			$limit_start = $data['start']-1; 
        } 
		else 
		{
            $data['start'] = 1;
			$limit_start = 0;
        }
		if (isset($_GET['page'])&&!empty($_GET['page']) ) 
		{
        	$data['end'] = $data['start'] + $data['per_page'];
			$limit_end = $data['per_page']; 
		}
		else
		{
			$data['end'] = $data['start'] + $data['per_page'];
			$limit_end = $data['per_page']; 
		}
		$data['menu'] = 'orderreqreport';
		$data['allorderreq'] = $this->db->query("select * from  t_purchase where orderstatus = 'Conform' LIMIT $limit_start , $limit_end ")->result();
		$data['allorderreqlist'] = $this->db->query("select * from  t_purchase where orderstatus = 'Conform'  ")->result();
		$data['container'] = $this->load->view('order_confirm_show',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function single_order_sales_paid_report($orderno)
	{
		$data = array();
		$data['menu'] = 'orderreqreport';
		$data['singleorderreq'] = $this->db->query("select * from  t_purchase where orderid = '".$orderno."'")->result();
		$data['orderno'] = $orderno;
		$data['container'] = $this->load->view('single_order_sales_paid_report',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function single_order_details($orderno)
	{
		$data = array();
		$data['menu'] = 'orderreqreport';
		$data['singleorderreq'] = $this->db->query("select * from  t_purchase where orderid = '".$orderno."'")->result();
		$data['orderno'] = $orderno;
		$data['container'] = $this->load->view('single_order_details',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function single_order_detail($orderno,$returnpage)
	{
		if($returnpage==1){$returnpage='admincontroller/complete_orders_report';}else if($returnpage==2){$returnpage='admincontroller/pending_orders_report';}else if($returnpage==3){$returnpage='admincontroller/cancel_orders_report';}
		$data = array();
		$data['menu'] = 'orderreqreport';
		$data['rp'] = $returnpage;
		$data['singleorderreq'] = $this->db->query("select * from  t_purchase where orderid = '".$orderno."'")->result();
		$data['orderno'] = $orderno;
		$data['container'] = $this->load->view('single_order_details',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function cencelorder($orderno,$customerid,$salespersonid)//cencel order before confirm
	{
		//purchase table
		$data = array();
		$data['orderstatus'] = "Cencel";
		$data['salespersonid'] = $salespersonid;
		if($this->mm->update_info('t_purchase',$data,array('orderid'=>$orderno)))
		{
			$this->mm->SaveLogs('Cencel',"Order '".$orderno."' Cencel by ".$salespersonid);
			//customer
			$ordercencelstatus = $this->db->query("select ordercencelstatus from  t_customer where id = '".$customerid."'")->row()->ordercencelstatus;
			$data = array();
			$data['ordercencelstatus'] = $ordercencelstatus + 1;
			$this->mm->update_info('t_customer',$data,array('id'=>$customerid));
			
			
			///////notification///////////////////////////
			$notify = array();
			$notify['date'] = date('Y-m-d h:i:s');
			$notify['notification'] = 'Order: '.$orderno.' is cenceled';
			$this->mm->insert_data('t_notification',$notify);
			
			///////mail start //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			$settingdata = $this->db->query("SELECT *  FROM t_settings ")->result();
			$storename="";
			$smtp_host = "";$smtp_port = "";$smtp_user = "";$smtp_pass = "";$mailsendvia =""; $servermail ="";$companyname="";$companyaddress = "";$companyemail = "";$companyphone = "";
			$siteurl = "";$brandorlogo="brand";
			
			
			$customername = $this->session->userdata('cusfirstname');
			$customerlastname = $this->session->userdata('cuslastname');
			if($customerlastname !=""){$customername .= $customerlastname;}
			
			foreach($settingdata as $d)
			{
				if($d->name == 'smtp_host'){$smtp_host = $d->value;}
				if($d->name == 'smtp_port'){$smtp_port = $d->value;}
				if($d->name == 'smtp_user'){$smtp_user = $d->value;}
				if($d->name == 'smtp_pass'){$smtp_pass = $d->value;}
				if($d->name == 'mailsendvia'){$mailsendvia = $d->value;}
				if($d->name == 'servermail'){$servermail = $d->value;}
				if($d->name == 'Company Name'){$companyname = $d->value;}
				if($d->name == 'Address'){$companyaddress = $d->value;}
				if($d->name == 'servermail'){$companyemail = $d->value;}
				if($d->name == 'phone'){$companyphone = $d->value;}
				if($d->name == 'brand or logo'){$brandorlogo = $d->value;}
			}
			$siteurl = base_url();
			$orderdata = $this->db->query("SELECT * FROM t_purchase WHERE orderid='".$orderno."' ")->result();$customername =""; $cusemai="";$cusphone="";$cuscountry="";$deliverylocation="";$contacturl = base_url()."main/contact";$paymethod="";
			$deliverylocation="";
			foreach($orderdata as $od)
			{
				$customerid = $od->customerid;
				$deliverylocation = $od->deliveryloc;
				$paymethod = $od->paymethod;
				$details = json_decode($od->details); 
				$customer = $this->db->query("SELECT * FROM t_customer WHERE id ='".$customerid."'")->result(); 
				foreach($customer as $cus)
				{
					$customername = $cus->firstname;
					if($cus->lastname !=""){$customername .=' '.$cus->lastname;}
					$cusemail = $cus->email;$cusphone = $cus->phone;
					$cuscountry = $cus->country;
				}
			}
$message = '<table class="m_-8065932370111914007m_-3734552349517890535body" style="height:100%!important;width:100%!important;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table class="m_-8065932370111914007m_-3734552349517890535header m_-8065932370111914007m_-3734552349517890535row" style="width:100%;border-spacing:0;border-collapse:collapse;margin:40px 0 20px"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535header__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table class="m_-8065932370111914007m_-3734552349517890535row" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535shop-name__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <h1 class="m_-8065932370111914007m_-3734552349517890535shop-name__text" style="font-weight:normal;font-size:30px;color:#333;margin:0"> <a href="'.$siteurl.'" style="font-size:30px;color:#333;text-decoration:none" target="_blank">';


if($brandorlogo=='logo'){
$message .= '<img src="'.$siteurl.'img/logo.png" alt="'.$companyname.'">';
} else{ 
$message .= '<span style="color:#2777BB">'.$companyname.'</span>';}

$message .= '</a> </h1> </td> <td class="m_-8065932370111914007m_-3734552349517890535order-number__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;text-transform:uppercase;font-size:14px;color:#999" align="right"> <span class="m_-8065932370111914007m_-3734552349517890535order-number__text" style="font-size:16px"> Order #'.$orderno.' </span> </td> </tr> </tbody></table> </td> </tr> </tbody></table> </center> </td> </tr></tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535content" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535content__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <h2 style="font-weight:normal;font-size:24px;margin:0 0 10px">Thank you for your purchase! </h2> <p style="color:#777;line-height:150%;font-size:16px;margin:0"> Hi '.$customername.', your order is cancelled. </p> </td> </tr> </tbody></table> </center> </td> </tr></tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535section" style="width:100%;border-spacing:0;border-collapse:collapse;border-top-width:1px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535section__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:40px 0"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <h3 style="font-weight:normal;font-size:20px;margin:0 0 25px">Order summary</h3> </td> </tr> </tbody></table>'; 
					
					$grandtotal = 0;$subtotal = 0;$totaldiscount = 0;$totalvat = 0;$totalprice = 0;$customerid = "";$couponvalue =0;foreach($orderdata as $s){$customerid = $s->customerid;$customer = $this->db->query("SELECT * FROM t_customer WHERE id ='".$customerid."'")->result(); 
					
						
						
						foreach($details as $d)
						{
							$productid = $d->productid;$price = $d->price; //unitprice
							$discount = $d->discount;$vat = $d->vat;$qty = $d->qty; //$size = $d->size;
							$color = $d->color;
							$product = $this->db->query("SELECT * FROM product WHERE id ='".$productid."'")->result(); 
							foreach($product as $p)
							{
								$code = $p->code; // code
								$title = $p->title; // title
								$stock = $p->stock;
								$imagename = $p->picture;
							
$message .= '<table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table class="m_-8065932370111914007m_-3734552349517890535row" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr class="m_-8065932370111914007m_-3734552349517890535order-list__item" style="width:100%"> <td class="m_-8065932370111914007m_-3734552349517890535order-list__item__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table style="border-spacing:0;border-collapse:collapse"> <tbody> <tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <img src="'.$siteurl.'img/product/'.$imagename.'" class="m_-8065932370111914007m_-3734552349517890535order-list__product-image CToWUd" style="margin-right:15px;border-radius:8px;border:1px solid #e5e5e5" width="60" height="60" align="left"> </td> <td class="m_-8065932370111914007m_-3734552349517890535order-list__product-description-cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;width:100%"> <span class="m_-8065932370111914007m_-3734552349517890535order-list__item-title" style="font-size:16px;font-weight:600;line-height:1.4;color:#555">'.$title.'</span><br> <span class="m_-8065932370111914007m_-3734552349517890535order-list__item-variant" style="font-size:14px;color:#999">'.$qty.'</span><br> </td> <td class="m_-8065932370111914007m_-3734552349517890535order-list__price-cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;white-space:nowrap"> <p class="m_-8065932370111914007m_-3734552349517890535order-list__item-price" style="color:#555;line-height:150%;font-size:16px;font-weight:600;margin:0 0 0 15px" align="right">'. number_format($price,2).'</p> </td> </tr></tbody></table> </td> </tr></tbody></table>';$totalprice += $price * $qty; 

			//total
			$totaldiscount += $discount; //total discount
			$totalvat += $vat; //total vat
			$subtotal += (($price * $qty)+ $vat) ; //grand total
						}
					}
			$couponvalue = $s->couponvalue; //coupon
			$deliverycharge = $s->deliverycharge; // delivery charge
			$paymethod = $s->paymethod;$paymentstatus = $s->paymentstatus;}
			$subtotal = $subtotal+$deliverycharge;
$message .=' <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535subtotal-lines" style="width:100%;border-spacing:0;border-collapse:collapse;margin-top:15px;border-top-width:1px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-spacer" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;width:40%"></td> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535subtotal-table" style="width:100%;border-spacing:0;border-collapse:collapse;margin-top:20px"> <tbody><tr class="m_-8065932370111914007m_-3734552349517890535subtotal-line"> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__title" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0"> <p style="color:#777;line-height:1.2em;font-size:16px;margin:0"> <span style="font-size:16px">Subtotal</span> </p> </td> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__value" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0" align="right"> <strong style="font-size:16px;color:#555">'.number_format($totalprice,2).'</strong> </td></tr> <tr class="m_-8065932370111914007m_-3734552349517890535subtotal-line"> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__title" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0"> <p style="color:#777;line-height:1.2em;font-size:16px;margin:0"> <span style="font-size:16px">Shipping</span> </p> </td> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__value" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0" align="right"> <strong style="font-size:16px;color:#555">'.number_format($deliverycharge,2).'</strong> </td></tr> <tr class="m_-8065932370111914007m_-3734552349517890535subtotal-line"> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__title" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0"> <p style="color:#777;line-height:1.2em;font-size:16px;margin:0"> <span style="font-size:16px">VAT</span> </p> </td> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__value" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0" align="right"> <strong style="font-size:16px;color:#555">'.number_format($totalvat,2).'</strong> </td></tr> </tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535subtotal-table m_-8065932370111914007m_-3734552349517890535subtotal-table--total" style="width:100%;border-spacing:0;border-collapse:collapse;margin-top:20px;border-top-width:2px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr class="m_-8065932370111914007m_-3734552349517890535subtotal-line"> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__title" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:20px 0 0"> <p style="color:#777;line-height:1.2em;font-size:16px;margin:0"> <span style="font-size:16px">Total</span> </p> </td> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__value" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:20px 0 0" align="right"> <strong style="font-size:24px;color:#555">'.number_format($subtotal,2).'</strong> </td></tr> </tbody></table> </td> </tr></tbody></table> </td> </tr> </tbody></table> </center> </td> </tr></tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535section" style="width:100%;border-spacing:0;border-collapse:collapse;border-top-width:1px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535section__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:40px 0"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <h3 style="font-weight:normal;font-size:20px;margin:0 0 25px">Customer information</h3> </td> </tr> </tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table class="m_-8065932370111914007m_-3734552349517890535row" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td colspan="2" class="m_-8065932370111914007m_-3734552349517890535customer-info__item" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px;width:50%"> <h4 style="font-weight:500;font-size:16px;color:#555;margin:0 0 5px">Shipping address</h4> <p style="color:#777;line-height:150%;font-size:16px;margin:0">'.$customername.'<br>'.$deliverylocation.'</p> </td> </tr> </tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td colspan="2" class="m_-8065932370111914007m_-3734552349517890535customer-info__item" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px;width:50%"> <h4 style="font-weight:500;font-size:16px;color:#555;margin:0 0 5px">Payment method</h4> <p class="m_-8065932370111914007m_-3734552349517890535customer-info__item-content" style="color:#777;line-height:150%;font-size:16px;margin:0"> '.$paymethod.' — <strong style="font-size:16px;color:#555">'.number_format($subtotal,2).'</strong> </p> </td> </tr> </tbody></table> </td> </tr> </tbody></table> </center> </td> </tr></tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535footer" style="width:100%;border-spacing:0;border-collapse:collapse;border-top-width:1px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535footer__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:35px 0"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <p class="m_-8065932370111914007m_-3734552349517890535disclaimer__subtext" style="color:#999;line-height:150%;font-size:14px;margin:0"><a href="'.$siteurl.'main/customer_login" style="font-size:14px;text-decoration:none;color:#1990c6" target="_blank">View your order</a> \ <a href="'.$siteurl.'">Visit our store</a> \ <a href="'.$contacturl.'" style="font-size:14px;text-decoration:none;color:#1990c6" target="_blank">contact us</a></p><br><br> </td></tr><tr><td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"><p class="m_-8065932370111914007m_-3734552349517890535disclaimer__subtext" style="color:#999;line-height:150%;font-size:14px;margin: -20px 0 0 0;">'.$companyname.', Address: '.$companyaddress.', Email: '.$companyemail.', Phone: '.$companyphone.'</p></td> </tr></tbody></table> </td> </tr> </tbody></table>';
			
			
				$to = $cusemail;
				$subject = "Order #".$orderno." cancelled";
				$sendresult="";
				if($mailsendvia == "smtp")
				{
					$sendresult = $this->mm->send_mail($smtp_host,$smtp_port,$smtp_user,$smtp_pass,$to,$storename,$subject,$message);
				}
				else
				{ 
					// To send HTML mail
					$headers[] = 'MIME-Version: 1.0';
					$headers[] = 'Content-type: text/html; charset=iso-8859-1';
					// Additional headers
					$headers[] = 'To:'.$servermail;
					$headers[] = 'From:'.$servermail;
					
					$sendresult = mail($to,$subject,$message,implode("\r\n", $headers)); 
				} 
			
			
			////////////////  Mail End///////////////////////////////////////////////////
			redirect("admincontroller/order_request?sk=Order Cencel");
		}
		else{
		redirect("admincontroller/order_request?esk=Order Cencel Problem, Try later");}
	}
	public function order_complete_form()
	{
		$data = array();
		$data['menu'] = 'ordercomplete';
		$data['container'] = $this->load->view('single_order_sales_complete',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function confirm_order_complete()
	{//ortotalprice ordiscount
		$orderno = $this->input->post("orno");
		$salespersonid = $this->input->post("spid"); 
		$order = $this->db->query("select * from  t_purchase where orderid = '".$orderno."' and orderstatus='Conform'")->result();
		if(count($order)==1)
		{
			$data = array();
			$data['paymentstatus'] = 'Paid';
			$data['paiddate'] = date('Y-m-d h:i:s');
			$data['orderstatus'] = 'Complete';
			$data['shipped'] = 'yes';
			$data['salespersonid'] = $salespersonid;
			$this->mm->update_info('t_purchase',$data,array('orderid'=>$orderno));
			
			$this->mm->SaveLogs('Complete',"Order '".$orderno."' Complete by ".$salespersonid);
			
			///////notification///////////////////////////
			$notify = array();
			$notify['date'] = date('Y-m-d h:i:s');
			$notify['notification'] = 'Order: '.$orderno.' is completed';
			$this->mm->insert_data('t_notification',$notify);
			
			///////mail start //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			$settingdata = $this->db->query("SELECT *  FROM t_settings ")->result();
			$storename="";
			$smtp_host = "";$smtp_port = "";$smtp_user = "";$smtp_pass = "";$mailsendvia =""; $servermail ="";$companyname="";$companyaddress = "";$companyemail = "";$companyphone = "";
			$siteurl = "";$brandorlogo="brand";
			
			
			$customername = $this->session->userdata('cusfirstname');
			$customerlastname = $this->session->userdata('cuslastname');
			if($customerlastname !=""){$customername .= $customerlastname;}
			
			foreach($settingdata as $d)
			{
				if($d->name == 'smtp_host'){$smtp_host = $d->value;}
				if($d->name == 'smtp_port'){$smtp_port = $d->value;}
				if($d->name == 'smtp_user'){$smtp_user = $d->value;}
				if($d->name == 'smtp_pass'){$smtp_pass = $d->value;}
				if($d->name == 'mailsendvia'){$mailsendvia = $d->value;}
				if($d->name == 'servermail'){$servermail = $d->value;}
				if($d->name == 'Company Name'){$companyname = $d->value;}
				if($d->name == 'Address'){$companyaddress = $d->value;}
				if($d->name == 'servermail'){$companyemail = $d->value;}
				if($d->name == 'phone'){$companyphone = $d->value;}
				if($d->name == 'brand or logo'){$brandorlogo = $d->value;}
			}
			$siteurl = base_url();
			$orderdata = $this->db->query("SELECT * FROM t_purchase WHERE orderid='".$orderno."' ")->result();$customername =""; $cusemai="";$cusphone="";$cuscountry="";$deliverylocation="";$contacturl = base_url()."main/contact";$paymethod="";
			$deliverylocation="";
			foreach($orderdata as $od)
			{
				$customerid = $od->customerid;
				$deliverylocation = $od->deliveryloc;
				$paymethod = $od->paymethod;
				$details = json_decode($od->details); 
				$customer = $this->db->query("SELECT * FROM t_customer WHERE id ='".$customerid."'")->result(); 
				foreach($customer as $cus)
				{
					$customername = $cus->firstname;
					if($cus->lastname !=""){$customername .=' '.$cus->lastname;}
					$cusemail = $cus->email;$cusphone = $cus->phone;
					$cuscountry = $cus->country;
				}
			}
$message = '<table class="m_-8065932370111914007m_-3734552349517890535body" style="height:100%!important;width:100%!important;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table class="m_-8065932370111914007m_-3734552349517890535header m_-8065932370111914007m_-3734552349517890535row" style="width:100%;border-spacing:0;border-collapse:collapse;margin:40px 0 20px"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535header__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table class="m_-8065932370111914007m_-3734552349517890535row" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535shop-name__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <h1 class="m_-8065932370111914007m_-3734552349517890535shop-name__text" style="font-weight:normal;font-size:30px;color:#333;margin:0"> <a href="'.$siteurl.'" style="font-size:30px;color:#333;text-decoration:none" target="_blank">';


if($brandorlogo=='logo'){
$message .= '<img src="'.$siteurl.'img/logo.png" alt="'.$companyname.'">';
} else{ 
$message .= '<span style="color:#2777BB">'.$companyname.'</span>';}

$message .= '</a> </h1> </td> <td class="m_-8065932370111914007m_-3734552349517890535order-number__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;text-transform:uppercase;font-size:14px;color:#999" align="right"> <span class="m_-8065932370111914007m_-3734552349517890535order-number__text" style="font-size:16px"> Order #'.$orderno.' </span> </td> </tr> </tbody></table> </td> </tr> </tbody></table> </center> </td> </tr></tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535content" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535content__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <h2 style="font-weight:normal;font-size:24px;margin:0 0 10px">Thank you for your purchase! </h2> <p style="color:#777;line-height:150%;font-size:16px;margin:0"> Hi '.$customername.', we are getting your order ready to be shipped. We will notify you when it has been sent. </p> </td> </tr> </tbody></table> </center> </td> </tr></tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535section" style="width:100%;border-spacing:0;border-collapse:collapse;border-top-width:1px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535section__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:40px 0"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <h3 style="font-weight:normal;font-size:20px;margin:0 0 25px">Order summary</h3> </td> </tr> </tbody></table>'; 
					
					$grandtotal = 0;$subtotal = 0;$totaldiscount = 0;$totalvat = 0;$totalprice = 0;$customerid = "";$couponvalue =0;foreach($orderdata as $s){$customerid = $s->customerid;$customer = $this->db->query("SELECT * FROM t_customer WHERE id ='".$customerid."'")->result(); 
					
						
						
						foreach($details as $d)
						{
							$productid = $d->productid;$price = $d->price; //unitprice
							$discount = $d->discount;$vat = $d->vat;$qty = $d->qty; //$size = $d->size;
							$color = $d->color;
							$product = $this->db->query("SELECT * FROM product WHERE id ='".$productid."'")->result(); 
							foreach($product as $p)
							{
								$code = $p->code; // code
								$title = $p->title; // title
								$stock = $p->stock;
								$imagename = $p->picture;
							
$message .= '<table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table class="m_-8065932370111914007m_-3734552349517890535row" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr class="m_-8065932370111914007m_-3734552349517890535order-list__item" style="width:100%"> <td class="m_-8065932370111914007m_-3734552349517890535order-list__item__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table style="border-spacing:0;border-collapse:collapse"> <tbody> <tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <img src="'.$siteurl.'img/product/'.$imagename.'" class="m_-8065932370111914007m_-3734552349517890535order-list__product-image CToWUd" style="margin-right:15px;border-radius:8px;border:1px solid #e5e5e5" width="60" height="60" align="left"> </td> <td class="m_-8065932370111914007m_-3734552349517890535order-list__product-description-cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;width:100%"> <span class="m_-8065932370111914007m_-3734552349517890535order-list__item-title" style="font-size:16px;font-weight:600;line-height:1.4;color:#555">'.$title.'</span><br> <span class="m_-8065932370111914007m_-3734552349517890535order-list__item-variant" style="font-size:14px;color:#999">'.$qty.'</span><br> </td> <td class="m_-8065932370111914007m_-3734552349517890535order-list__price-cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;white-space:nowrap"> <p class="m_-8065932370111914007m_-3734552349517890535order-list__item-price" style="color:#555;line-height:150%;font-size:16px;font-weight:600;margin:0 0 0 15px" align="right">'. number_format($price,2).'</p> </td> </tr></tbody></table> </td> </tr></tbody></table>';$totalprice += $price * $qty; 

			//total
			$totaldiscount += $discount; //total discount
			$totalvat += $vat; //total vat
			$subtotal += (($price * $qty)+ $vat) ; //grand total
						}
					}
			$couponvalue = $s->couponvalue; //coupon
			$deliverycharge = $s->deliverycharge; // delivery charge
			$paymethod = $s->paymethod;$paymentstatus = $s->paymentstatus;}
			$subtotal = $subtotal+$deliverycharge;
$message .=' <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535subtotal-lines" style="width:100%;border-spacing:0;border-collapse:collapse;margin-top:15px;border-top-width:1px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-spacer" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;width:40%"></td> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535subtotal-table" style="width:100%;border-spacing:0;border-collapse:collapse;margin-top:20px"> <tbody><tr class="m_-8065932370111914007m_-3734552349517890535subtotal-line"> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__title" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0"> <p style="color:#777;line-height:1.2em;font-size:16px;margin:0"> <span style="font-size:16px">Subtotal</span> </p> </td> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__value" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0" align="right"> <strong style="font-size:16px;color:#555">'.number_format($totalprice,2).'</strong> </td></tr> <tr class="m_-8065932370111914007m_-3734552349517890535subtotal-line"> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__title" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0"> <p style="color:#777;line-height:1.2em;font-size:16px;margin:0"> <span style="font-size:16px">Shipping</span> </p> </td> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__value" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0" align="right"> <strong style="font-size:16px;color:#555">'.number_format($deliverycharge,2).'</strong> </td></tr> <tr class="m_-8065932370111914007m_-3734552349517890535subtotal-line"> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__title" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0"> <p style="color:#777;line-height:1.2em;font-size:16px;margin:0"> <span style="font-size:16px">VAT</span> </p> </td> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__value" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:5px 0" align="right"> <strong style="font-size:16px;color:#555">'.number_format($totalvat,2).'</strong> </td></tr> </tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535subtotal-table m_-8065932370111914007m_-3734552349517890535subtotal-table--total" style="width:100%;border-spacing:0;border-collapse:collapse;margin-top:20px;border-top-width:2px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr class="m_-8065932370111914007m_-3734552349517890535subtotal-line"> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__title" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:20px 0 0"> <p style="color:#777;line-height:1.2em;font-size:16px;margin:0"> <span style="font-size:16px">Total</span> </p> </td> <td class="m_-8065932370111914007m_-3734552349517890535subtotal-line__value" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:20px 0 0" align="right"> <strong style="font-size:24px;color:#555">'.number_format($subtotal,2).'</strong> </td></tr> </tbody></table> </td> </tr></tbody></table> </td> </tr> </tbody></table> </center> </td> </tr></tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535section" style="width:100%;border-spacing:0;border-collapse:collapse;border-top-width:1px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535section__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:40px 0"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <h3 style="font-weight:normal;font-size:20px;margin:0 0 25px">Customer information</h3> </td> </tr> </tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <table class="m_-8065932370111914007m_-3734552349517890535row" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td colspan="2" class="m_-8065932370111914007m_-3734552349517890535customer-info__item" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px;width:50%"> <h4 style="font-weight:500;font-size:16px;color:#555;margin:0 0 5px">Shipping address</h4> <p style="color:#777;line-height:150%;font-size:16px;margin:0">'.$customername.'<br>'.$deliverylocation.'</p> </td> </tr> </tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td colspan="2" class="m_-8065932370111914007m_-3734552349517890535customer-info__item" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px;width:50%"> <h4 style="font-weight:500;font-size:16px;color:#555;margin:0 0 5px">Payment method</h4> <p class="m_-8065932370111914007m_-3734552349517890535customer-info__item-content" style="color:#777;line-height:150%;font-size:16px;margin:0"> '.$paymethod.' — <strong style="font-size:16px;color:#555">'.number_format($subtotal,2).'</strong> </p> </td> </tr> </tbody></table> </td> </tr> </tbody></table> </center> </td> </tr></tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535footer" style="width:100%;border-spacing:0;border-collapse:collapse;border-top-width:1px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535footer__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:35px 0"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <p class="m_-8065932370111914007m_-3734552349517890535disclaimer__subtext" style="color:#999;line-height:150%;font-size:14px;margin:0"><a href="'.$siteurl.'main/customer_login" style="font-size:14px;text-decoration:none;color:#1990c6" target="_blank">View your order</a> \ <a href="'.$siteurl.'">Visit our store</a> \ <a href="'.$contacturl.'" style="font-size:14px;text-decoration:none;color:#1990c6" target="_blank">contact us</a></p><br><br> </td></tr><tr><td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"><p class="m_-8065932370111914007m_-3734552349517890535disclaimer__subtext" style="color:#999;line-height:150%;font-size:14px;margin: -20px 0 0 0;">'.$companyname.', Address: '.$companyaddress.', Email: '.$companyemail.', Phone: '.$companyphone.'</p></td> </tr></tbody></table> </td> </tr> </tbody></table>';
			
			
				$to = $cusemail;
				$subject = "Order #".$orderno." delivered";
				$sendresult="";
				if($mailsendvia == "smtp")
				{
					$sendresult = $this->mm->send_mail($smtp_host,$smtp_port,$smtp_user,$smtp_pass,$to,$storename,$subject,$message);
				}
				else
				{ 
					// To send HTML mail
					$headers[] = 'MIME-Version: 1.0';
					$headers[] = 'Content-type: text/html; charset=iso-8859-1';
					// Additional headers
					$headers[] = 'To:'.$servermail;
					$headers[] = 'From:'.$servermail;
					
					$sendresult = mail($to,$subject,$message,implode("\r\n", $headers)); 
				} 
			
			
			////////////////  Mail End///////////////////////////////////////////////////
			
			redirect("admincontroller/order_complete_form?sk=Order Completed");
		}
		else{
		redirect("admincontroller/order_complete_form?sk=Invalid Order Number");}
	}
	
	public function checkmailmessage()
	{
		$data = array();
		$data['container'] = $this->load->view('product_sales_confirm_message','',true);
		$this->load->view('masteradmin',$data);
	}
	public function confirm_order_cencel($orderno,$customerid,$salespersonid)//cencel order after confirm
	{
		$permission = $this->session->userdata('permission');
		$acl = 72;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			//purchase table info
			$order = $this->db->query("select * from  t_purchase where orderid = '".$orderno."'")->result();
			$details = "";
			foreach($order as $o)
			{
				$details = json_decode($o->details);
			}
			$quartproductid = "";
			$quartproductqty = 0;
			foreach($details as $de)
			{
				$quartproductid = $de->productid;
				$quartproductqty = $de->qty; 
				$stock = 0;
				$stock = $this->db->query("select stock  from  product where id = '".$quartproductid."'")->row()->stock;
				$stock = $stock + $quartproductqty;
				$data = array();
				$data['stock'] = $stock;
				//update product table to increase product stock
				$this->mm->update_info('product',$data,array('id'=>$quartproductid));
			}
			//purchase table
			$data = array();
			$data['orderstatus'] = "Cencel";
			$data['salespersonid'] = $salespersonid;
			if($this->mm->update_info('t_purchase',$data,array('orderid'=>$orderno)))
			{
				$this->mm->SaveLogs('Cencel',"Complete Order '".$orderno."' Cencel by ".$salespersonid);
				
				///////notification///////////////////////////
				$notify = array();
				$notify['date'] = date('Y-m-d h:i:s');
				$notify['notification'] = 'Order: '.$orderno.' is cenceled';
				$this->mm->insert_data('t_notification',$notify);
			
				//customer table
				$ordercencelstatus = $this->db->query("select ordercencelstatus from  t_customer where id = '".$customerid."'")->row()->ordercencelstatus;
				$data = array();
				$data['ordercencelstatus'] = $ordercencelstatus + 1;
				$this->mm->update_info('t_customer',$data,array('id'=>$customerid));
				redirect("admincontroller/order_confirm_show?sk=Order Cencel");
			}
			else{
			redirect("admincontroller/order_confirm_show?esk=Order Cencel Problem, Try later");}
		}
		else
		{
			redirect(base_url() . "admincontroller/order_confirm_show?esk=No Access");
		}
	}
	public function news_form()
	{
		$data = array();
		$data['menu'] = 'news';
		$data['container'] = $this->load->view('single_order_sales_complete',$data,true);
		$this->load->view('masteradmin',$data);
	}
	
	////// Theme color ///////////
	public function theme_color_change()
	{
		$data = array();
		$data['menu'] = "theme";
        $data['container'] = $this->load->view('theme_color', '', true);
        $this->load->view('masteradmin', $data);
	}
	public function theme_color_modify($cssname)
	{
		$data = array();
		$data['value']=$cssname;
		$result = $this->mm->update_info('t_settings', $data,array('name'=>'theme_color'));
		if ($result == true) {
			redirect(base_url() . "admincontroller/theme_color_change?sk= Theme color changed");
		}
		else{
			redirect(base_url() . "admincontroller/theme_color_change?esk= Database too busy, try later");
		}
	}
	public function home_category()
	{
		$data = array();
		$data['menu'] = 'homecat';
		$data['category'] = $this->db->query("SELECT *  FROM category ")->result();
		$data['container'] = $this->load->view('home_page_category_select_form',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function set_home_category()
	{
		$catid = $this->input->post('cid');
		
		if(!empty($catid)){$data = array();$data['value'] = trim($catid);
			$this->mm->update_info('t_settings',$data,array('name'=>'home page category'));}
		redirect("admincontroller/home_category?sk=Saved");
	}
	public function customer_add()
	{
		$data = array();
		$data['menu'] = 'customer_add';
		$data['container'] = $this->load->view('customer_reg',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function customer_info_save()
	{
		$permission = $this->session->userdata('permission');
		$acl = 55;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			if($_POST)
			{
				$username = trim($this->input->post('username'));
				$password = trim($this->input->post('password'));
				$name = trim($this->input->post('name'));
				$email = trim($this->input->post('email'));
				$phone = trim($this->input->post('phone'));
				$address = trim($this->input->post('address'));
				$city = trim($this->input->post('city'));
				$country = trim($this->input->post('country'));
				$err=0;
				$msg="";
				if($email =="")
				{
					$msg .=++$err.". email required<br>";
				}
				else if(strlen($email) >150 )
				{
					$msg .=++$err.". email maximum length exceed<br>";
				}
				if($email =="")
				{
					if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
					{
					  $msg .=++$err.". Invalid email format";
					}
					else
					{
						$isemailexist = 0;
						$allemail = $this->db->query("select email from t_customer")->result();
						foreach($allemail as $ae)
						{
							if($ae->email == $email){$isemailexist = 1;}
						}
						if($isemailexist == 1){$err++; $msg .= "Email already exit<br>";}
					}
				}
				if($password =="")
				{
					$msg .=++$err.". password required<br>";
				}
				else if(strlen($password) <6 || strlen($password) >12)
				{
					$msg .=++$err.". password maximum length exceed<br>";
				}
				if($name =="")
				{
					$msg .=++$err.". name required<br>";
				}
				else if(strlen($name) >100 )
				{
					$msg .=++$err.". name maximum length exceed<br>";
				}
				
				if($phone =="")
				{
					$msg .=++$err.". phone required<br>";
				}
				else if(strlen($phone) >12 )
				{
					$msg .=++$err.". phone maximum length exceed<br>";
				}
				else if(is_nan($phone) )
				{
					$msg .=++$err.". phone number invalid<br>";
				}
				if($address =="")
				{
					$msg .=++$err.". address required<br>";
				}
				else if(strlen($address) >200 )
				{
					$msg .=++$err.". shipping address maximum length exceed<br>";
				}
				if($city =="")
				{
					$msg .=++$err.". city required<br>";
				}
				else if(strlen($city) >20 )
				{
					$msg .=++$err.". city maximum length exceed<br>";
				}
				if($country =="")
				{
					$msg .=++$err.". country required<br>";
				}
				
				if($err==0)
				{
					$data = array();
					$data['username'] = $username;
					$data['password'] = $password;
					$data['name'] = $name;
					$data['email'] = $email;
					$data['phone'] = $phone;
					$data['address'] = $address;
					$data['country'] = $country;
					$data['citylocation'] = $city;
					$insertedid = $this->mm->insert_data('t_customer',$data);
					if($insertedid !="")
					{
						$this->mm->SaveLogs('Saved',"New Customer '".$name."' Info Saved");
						redirect("admincontroller/customer_add?sk=Saved Successfully");
					}
					else
					{
						redirect("admincontroller/customer_add?esk=Error!!!, Not Saved");
					}
					
				}
				else
				{
					redirect("admincontroller/customer_add?esk=".$msg);
				}
			}
			else
			{
				redirect("main");
			}
		}
		else
		{
			redirect(base_url() . "admincontroller/customer_add?esk=No Access");
		}
	}
	public function view_customers()
	{
		$data = array();
		$data['menu'] = 'view_customers';
		$data['info'] = $this->db->query("SELECT *  FROM t_customer ")->result();
		$data['container'] = $this->load->view('view_customer_info_admin',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function view_customer_email_list()
	{
		$data = array();
		$data['menu'] = 'view_customer_email_list';
		$data['info'] = $this->db->query("SELECT *  FROM t_customer ")->result();
		$data['container'] = $this->load->view('view_customer_email_list_admin',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function edit_customer($id,$rp)
	{
		$data = array();
		$data['menu'] = 'view_customers';
		$data['rp'] = $rp;
		$data['info'] = $this->db->query("SELECT *  FROM t_customer where id='".$id."' ")->result();
		$data['container'] = $this->load->view('customer_reg_edit',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function edit_customers($id)
	{
		$data = array();
		$data['menu'] = 'view_customers';
		$data['delivery'] = $this->db->query("SELECT *  FROM delivery ")->result();
		$data['info'] = $this->db->query("SELECT *  FROM t_customer where id='".$id."' ")->result();
		$data['container'] = $this->load->view('customer_reg_edit',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function customer_info_update()
	{
		//firstname lastname citylocation city country
		$permission = $this->session->userdata('permission');
		$acl = 56;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			if($_POST)
			{
				$id = $this->input->post('id');
				$email = trim($this->input->post('email'));
				$password = trim($this->input->post('password'));
				$firstname = trim($this->input->post('firstname'));
				$lastname = trim($this->input->post('lastname'));
				$phone = trim($this->input->post('phone'));
				$address = trim($this->input->post('address'));
				$citylocation = trim($this->input->post('citylocation'));
				$city = trim($this->input->post('city'));
				$country = trim($this->input->post('country'));
				$status = trim($this->input->post('status'));
				$err=0;
				$msg="";
				
				if($password =="")
				{
					$msg .=++$err.". password required<br>";
				}
				else if(strlen($password) <6 || strlen($password) >12)
				{
					$msg .=++$err.". password maximum length exceed<br>";
				}
				if($firstname =="")
				{
					$msg .=++$err.". firstname required<br>";
				}
				else if(strlen($firstname) >150 )
				{
					$msg .=++$err.". firstname maximum length exceed<br>";
				}
				if($lastname !="")
				{
					if(strlen($lastname) >150 )
					{
						$msg .=++$err.". lastname maximum length exceed<br>";
					}
				}
				
				if($phone =="")
				{
					$msg .=++$err.". phone required<br>";
				}
				else if(strlen($phone) >12 )
				{
					$msg .=++$err.". phone maximum length exceed<br>";
				}
				else if(is_nan($phone) )
				{
					$msg .=++$err.". phone number invalid<br>";
				}
				if($address =="")
				{
					$msg .=++$err.". address required<br>";
				}
				else if(strlen($address) >200 )
				{
					$msg .=++$err.". shipping address maximum length exceed<br>";
				}
				if($city =="")
				{
					$msg .=++$err.". city required<br>";
				}
				else if(strlen($city) >100 )
				{
					$msg .=++$err.". city maximum length exceed<br>";
				}
				if($country =="")
				{
					$msg .=++$err.". country required<br>";
				}
				
				if($err==0)
				{
					$data = array();
					$data['password'] = $password;
					$data['firstname'] = $firstname;
					if($lastname !=""){
					$data['lastname'] = $lastname;}
					$data['phone'] = $phone;
					$data['address'] = $address;
					$data['country'] = $country;
					$data['citylocation'] = $citylocation;
					$data['city'] = $city;
					$data['status'] = $status;
					if($this->mm->update_info('t_customer',$data,array("id"=>$id)))
					{
						$this->mm->SaveLogs('Update',"Customer id:'".$id."' Info Update");
						redirect("admincontroller/edit_customers/".$id."?sk=Update Successfully");
					}
					else
					{
						redirect("admincontroller/edit_customers/".$id."?esk=Error!!!, Not Updated");
					}
					
				}
				else
				{
					redirect("admincontroller/edit_customers/".$id."?esk=".$msg);
				}
			}
			else
			{
				redirect("main");
			}
		}
		else
		{
			redirect(base_url() . "admincontroller/edit_customers/".$id."?esk=No Access");
		}
	}
	public function view_active_customers()
	{
		$data = array();
		$data['menu'] = 'view_customers';
		$data['info'] = $this->db->query("SELECT *  FROM t_customer where  status=0 ")->result(); //status=0 active, status=1 inactive, status=2 bad customer
		$data['container'] = $this->load->view('view_active_customer_info_admin',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function view_inactive_customers()
	{
		$data = array();
		$data['menu'] = 'view_customers';
		$data['info'] = $this->db->query("SELECT *  FROM t_customer where  status=1 ")->result(); //status=0 active, status=1 inactive, status=2 bad customer
		$data['container'] = $this->load->view('view_inactive_customer_info_admin',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function view_bad_customers()
	{
		$data = array();
		$data['menu'] = 'view_customers';
		$data['info'] = $this->db->query("SELECT *  FROM t_customer where  status=2 ")->result(); //status=0 active, status=1 inactive, status=2 bad customer
		$data['container'] = $this->load->view('view_bad_customer_info_admin',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function exchange_order()
	{
		$data = array();
		$data['menu'] = 'exchange_order';
		$data['container'] = $this->load->view('exchange_order_admin',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function single_order_sales_exchange()
	{
		$orderno = strtolower(trim($this->input->post('orno')));
		$orderdata = $this->db->query("select * from  t_purchase where orderid = '".$orderno."'")->result();
		if(count($orderdata)>0)
		{
			$data = array();
			$data['menu'] = 'orderreqreport';
			$data['singleorderreq'] = $orderdata;
			$data['orderno'] = $orderno;
			$data['container'] = $this->load->view('single_order_sales_exchange',$data,true);
			$this->load->view('masteradmin',$data);
		}
		else
		{
			redirect('admincontroller/exchange_order?esk=order number invalid');
		}
		
	}
	public function editordereditem()
	{
		$orid = $this->input->post("orid");
		$proid = $this->input->post("proid");
		$size = $this->input->post("size");
		$color = $this->input->post("color");
		
		$jsondata = $this->db->query("select details from t_purchase where orderid='".$orid."' ")->row()->details; 
		$jsondata = json_decode($jsondata); 
		
		 for($i=0;$i<count($jsondata);$i++)
		 {
			 if($jsondata[$i]->productid == $proid)
			 {
				 $jsondata[$i]->color = $color;
				 $jsondata[$i]->size = $size;
			 }
		 }
		
		$jsondata = json_encode($jsondata);
		if($this->db->query("update t_purchase set details='".$jsondata."' where orderid='".$orid."' "))
		{
			$this->mm->SaveLogs('Exchange',"Order '".$orid."' Exchange");
			echo 'Update Successfully';
		}
		else
		{
			echo 'Error!!!, Try again';
		}
	}
	public function single_order_sale_exchange($orderno)
	{
		$orderdata = $this->db->query("select * from  t_purchase where orderid = '".$orderno."'")->result();
		if(count($orderdata)>0)
		{
			$data = array();
			$data['menu'] = 'orderreqreport';
			$data['singleorderreq'] = $orderdata;
			$data['orderno'] = $orderno;
			$data['container'] = $this->load->view('single_order_sales_exchange',$data,true);
			$this->load->view('masteradmin',$data);
		}
		else
		{
			redirect('admincontroller/exchange_order?esk=order number invalid');
		}
		
	}
	public function view_product_review()
	{
		$data = array();
		$data['menu'] = 'view_customers';
		$data['info'] = $this->db->query("SELECT DISTINCT code  FROM t_customerreview ")->result();
		$data['product'] = $this->db->query("SELECT title, code,avg_review  FROM product ")->result();
		$data['container'] = $this->load->view('view_product_review_admin',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function view_product_review_details($code)
	{
		$data = array();
		$data['menu'] = 'view_customers';
		$data['info'] = $this->db->query("SELECT *  FROM t_customerreview where code='".$code."' ")->result();
		$data['product'] = $this->db->query("SELECT *  FROM product where code='".$code."' ")->result();
		$data['customer'] = $this->db->query("SELECT *  FROM t_customer ")->result();
		$data['container'] = $this->load->view('view_product_review_details_admin',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function set_review_type($code)
	{
		$data = array();
		$data['code']=$code;
		$data['container'] = $this->load->view('set_review_type_admin',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function save_review_type()
	{
		$code = $this->input->post('code');
		$reviewstatus = $this->input->post('reviewstatus');
		$data = array();
		if($reviewstatus=='no review'){$reviewstatus="";}
		$data['avg_review']=$reviewstatus;
		$this->mm->update_info('product',$data,array('code'=>$code));
		redirect('admincontroller/set_review_type/'.$code.'?sk=Updated');
	}
	public function delete_comment($id,$code)
	{
		if($this->mm->delete_info('t_customerreview',array('id'=>$id)))
		{
			$this->mm->SaveLogs('Comment',"Comment id:'".$id."' Deleted");
			redirect('admincontroller/view_product_review_details/'.$code.'?sk=Deleted');
		}
		else
		{
			redirect('admincontroller/view_product_review_details/'.$code.'?esk=Error!!!, Not Deleted');
		}
	}
	public function message_template()
	{
		$data = array();
		$data['menu'] = 'message_template';
		$data['info'] = $this->db->query("SELECT *  FROM t_message_template ")->result(); //status=0 active, status=1 inactive, status=2 bad customer
		$data['container'] = $this->load->view('message_template_admin',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function edit_message_template($id)
	{
		$data = array();
		$data['menu'] = 'edit_message_template ';
		$data['info'] = $this->db->query("SELECT * FROM t_message_template where id='".$id."' ")->result(); //status=0 active, status=1 inactive, status=2 bad customer
		$data['container'] = $this->load->view('message_template_edit_admin',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function message_template_update()
	{
		$permission = $this->session->userdata('permission');
		$acl = 61;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			if($_POST)
			{
				//id name subject message isactive
				$id = $this->input->post('id');
				$name = trim($this->input->post('name'));
				$subject = trim($this->input->post('subject'));
				$message = trim($this->input->post('message'));
				$isactive = trim($this->input->post('isactive'));
				$err=0;
				$msg = "";
				if($name ==""){ $msg .=++$err.". Name required<br>";}
				if($subject ==""){ $msg .=++$err.". Subject required<br>";}
				if($message ==""){ $msg .=++$err.". Message required<br>";}
				if($err ==0)
				{
					$data = array();
					$data['name'] = $name;
					$data['subject'] = $subject;
					$data['message'] = $message;
					if($isactive == 'on'){
					$data['isactive'] = 1;}
					else{
					$data['isactive'] = 0;
					}
					if($this->mm->update_info('t_message_template',$data,array('id'=>$id)))
					{
						$this->mm->SaveLogs('message_template',"message_template name:'".$name."' Updated");
						redirect('admincontroller/edit_message_template/'.$id.'?sk=Updated successfully');
					}
					else
					{
						redirect('admincontroller/edit_message_template/'.$id.'?esk=Error!!!');
					}
				}
				else
				{
					redirect('admincontroller/edit_message_template/'.$id.'?esk='.$msg);
				}
			}
			else
			{
				redirect("main");
			}
		}
		else
		{
			redirect(base_url() . "admincontroller/edit_message_template/".$id."?esk=No Access");
		}
	}
	public function testmessagetemplate($id)
	{
		$data = array();
		$data['info'] = $this->db->query("SELECT * FROM t_message_template where id='".$id."' ")->result();
		$data['container'] = $this->load->view('message_template_view_admin',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function email_to_customer_page()
	{
		$data = array();
		$data['customerlist'] = $this->db->query("SELECT * FROM t_customer ")->result();
		$data['container'] = $this->load->view('customer_message_page_admin',$data,true); //customer_message_page_admin
		$this->load->view('masteradmin',$data);
	}
	public function save_mail()  
	{ 
		if($_POST)
		{
			$to = trim($this->input->post('to'));
			$customer_group = trim($this->input->post('customer_group'));
			$customerid = trim($this->input->post('customer'));
			$subject = trim($this->input->post('subject'));
			$message = trim($this->input->post('message')); 
			$err=0;
			$msg="";
			
			if($subject == "")
			{
				$msg .=++$err.". Subject required<br>";
			}
			if($message == "")
			{
				$msg .=++$err.". Message required<br>";
			}
			$customerdata="";
			if($to == 'customer_all')
			{
				$customerdata = $this->db->query("select * from t_customer")->result();
			}
			else if($to == 'customer_group')
			{
				$customerdata = $this->db->query("select * from t_customer where status='".$customer_group."' ")->result();
			}
			else if($to == 'customer')
			{
				$customerdata = $this->db->query("select * from t_customer where id='".$customerid."' ")->result();
			}
			if(count($customerdata)>0)
			{
				$settingdata = $this->db->query("SELECT *  FROM t_settings ")->result();
				$storename="";
				$smtp_host = "";$smtp_port = "";$smtp_user = "";$smtp_pass = "";$mailsendvia =""; $servermail ="";$companyname="";$companyaddress = "";$companyemail = "";$companyphone = "";
				$siteurl = "";$brandorlogo="brand";
				foreach($settingdata as $d)
				{
					if($d->name == 'smtp_host'){$smtp_host = $d->value;}
					if($d->name == 'smtp_port'){$smtp_port = $d->value;}
					if($d->name == 'smtp_user'){$smtp_user = $d->value;}
					if($d->name == 'smtp_pass'){$smtp_pass = $d->value;}
					if($d->name == 'mailsendvia'){$mailsendvia = $d->value;}
					if($d->name == 'servermail'){$servermail = $d->value;}
					if($d->name == 'Company Name'){$companyname = $d->value;}
					if($d->name == 'Address'){$companyaddress = $d->value;}
					if($d->name == 'servermail'){$companyemail = $d->value;}
					if($d->name == 'phone'){$companyphone = $d->value;}
					if($d->name == 'brand or logo'){$brandorlogo = $d->value;}
				}
				foreach($customerdata as $cus)
				{
					$to = $cus->email;
					$sendresult="";
					if($mailsendvia == "smtp")
					{
						$sendresult = $this->mm->send_mail($smtp_host,$smtp_port,$smtp_user,$smtp_pass,$to,$companyname,$subject,$message);
					}
					else
					{ 
						// To send HTML mail
						$headers[] = 'MIME-Version: 1.0';
						$headers[] = 'Content-type: text/html; charset=iso-8859-1';
						// Additional headers
						$headers[] = 'To:'.$to;
						$headers[] = 'From:'.$servermail;
						
						$sendresult = mail($to,$subject,$message,implode("\r\n", $headers)); 
					} 
				}
				$this->mm->SaveLogs('Customer Mail',"Customer Mail Subject:'".$subject."', Send");
				redirect("admincontroller/email_to_customer_page?sk=Mail sent..");
			}
			else
			{
				redirect("admincontroller/email_to_customer_page?esk=Error!!!,mail sending problem");
			}
		}
		else
		{
			redirect("main");
		}
	}
	public function send_mail($limit)  
	{ 
		$customermail = $this->db->query("select top '".$limit."' * from t_mail where sendmailstatus='no' ")->result();
		if(count($customermail)>0)
		{
			$storename = "";
			$settingdata = $this->db->query("SELECT *  FROM t_settings ")->result();
			$smtp_host = "";$smtp_port = "";$smtp_user = "";$smtp_pass = "";$mailsendvia =""; $servermail ="";
			foreach($settingdata as $d)
			{
				if($d->name == 'smtp_host'){$smtp_host = $d->value;}
				if($d->name == 'smtp_port'){$smtp_port = $d->value;}
				if($d->name == 'smtp_user'){$smtp_user = $d->value;}
				if($d->name == 'smtp_pass'){$smtp_pass = $d->value;}
				if($d->name == 'mailsendvia'){$mailsendvia = $d->value;}
				if($d->name == 'servermail'){$servermail = $d->value;}
				if($d->name == 'Company Name'){$storename = $d->value;}
			}
			foreach($customermail as $cm)
			{
				//////////////////////////
				$to=""; $to = $cm->mailto;
				$subject=""; $subject = $cm->subject;
				$message .= $cm->message;
				$sendresult="";
				if($mailsendvia == "smtp")
				{
					$sendresult = $this->mm->send_mail($smtp_host,$smtp_port,$smtp_user,$smtp_pass,$to,$storename,$subject,$message);
				}
				else
				{ 
					// To send HTML mail
					$headers[] = 'MIME-Version: 1.0';
					$headers[] = 'Content-type: text/html; charset=iso-8859-1';
					// Additional headers
					$headers[] = 'To:'.$servermail;
					$headers[] = 'From:'.$servermail;
					
					$sendresult = mail($to,$subject,$message,implode("\r\n", $headers)); 
				}
				// 
				if($sendresult)
				{
					$data = array();
					$data['sendmailstatus'] = 'Yes';
					$this->mm->update_info("t_mail",$data,array('id'=>$cm->id));
				}
			}
		}
	}
	public function sms_to_customer_page()
	{
		$data = array();
		$data['customerlist'] = $this->db->query("SELECT * FROM t_customer ")->result();
		$data['container'] = $this->load->view('customer_sms_page_admin',$data,true); //customer_message_page_admin
		$this->load->view('masteradmin',$data);
	}
	public function save_sms()  
	{ 
		if($_POST)
		{
			$to = trim($this->input->post('to'));
			$customer_group = trim($this->input->post('customer_group'));
			$customerid = trim($this->input->post('customer'));
			$subject = trim($this->input->post('subject'));
			$message = trim($this->input->post('message')); 
			$err=0;
			$msg="";
			
			if($subject == "")
			{
				$msg .=++$err.". Subject required<br>";
			}
			if($message == "")
			{
				$msg .=++$err.". Message required<br>";
			}
			$customerdata="";
			if($to == 'customer_all')
			{
				$customerdata = $this->db->query("select * from t_customer")->result();
			}
			else if($to == 'customer_group')
			{
				$customerdata = $this->db->query("select * from t_customer where status='".$customer_group."' ")->result();
			}
			else if($to == 'customer')
			{
				$customerdata = $this->db->query("select * from t_customer where id='".$customerid."' ")->result();
			}
			if(count($customerdata)>0)
			{
				$storename = $this->mm->getSet("Company Name");
				foreach($customerdata as $cus)
				{
					$data = array();
					$data['smsto'] = $cus->phone;
					$data['subject'] = $subject;
					$data['message'] = $message;
					$data['sendsmsstatus'] = 'no';
					$this->mm->insert_data('t_sms',$data);
				}
				$this->mm->SaveLogs('Customer SMS',"Customer SMS Subject:'".$subject."', Send");
				redirect("admincontroller/sms_to_customer_page?sk=Sms sent..");
			}
			else
			{
				redirect("admincontroller/sms_to_customer_page?esk=Error!!!,sms sending problem");
			}
		}
		else
		{
			redirect("main");
		}
	}
	public function send_sms($limit)  
	{ 
		$customersms = $this->db->query("select top '".$limit."' * from t_sms where sendsmsstatus='no' ")->result();
		if(count($customersms)>0)
		{
			$storename = $this->mm->getSet('Company Name');
			foreach($customersms as $cm)
			{
				//sms api
				if(sms_api())
				{
					$data = array();
					$data['sendsmsstatus'] = 'Yes';
					$this->mm->update_info("t_sms",$data,array('id'=>$cm->id));
				}
			}
		}
	}
	public function access_logs($filename=null)
	{
		if($filename ==""){$filename=date('Ymd').'.txt';}
		$this->load->helper('directory');
		$tdate = explode('.txt',$filename);
		$tdate =$tdate[0]; 
		$tdate = date("d F, Y", strtotime($tdate));
		$data = array();
		$data['sdate'] = $tdate; 
		$logsfile = read_file('./logs/'.$filename);
		$filedata = array();
		
		if($logsfile !="")
		 { 
			if(strpos($logsfile,'===='))
			{
				$logsfile = explode('====',$logsfile);
				for($i=0;$i<count($logsfile)-1;$i++)
				{
					//echo $logsfile[$i]."<br>";
					if(strpos($logsfile[$i],';'))
					{
						$logs = explode(';',$logsfile[$i]);
					}
					array_push($filedata,$logs);
				}
			}
		 }
		$data['logsfile'] = $filedata;
		$data['container'] = $this->load->view('active_logs_file_list',$data,true); //customer_message_page_admin
		$this->load->view('masteradmin',$data);
	}
	public function access_logs_search()
	{
		$filename ="";
		$sdate = trim($this->input->post('sdate')); 
		$sip = trim($this->input->post('sip'));
		$suser = trim($this->input->post('suser'));
		$sevent = trim($this->input->post('sevent'));
		if($sdate !="")
		{
			//$sdate = date_format($sdate, 'Ymd');
			$ssdate = date("Ymd", strtotime($sdate)); //echo $ssdate;exit;
			$filename=$ssdate.'.txt';
		}
		
		if($filename =="")
		{
			$filename=date('Ymd').'.txt';
			$sdate = date('dd/mm/yyyy');
		}
		$this->load->helper('directory');
		$tdate = explode('.txt',$filename);
		$tdate =$tdate[0]; 
		$tdate = date("d F, Y", strtotime($tdate));
		$data = array();
		$data['sdate'] = $sdate;
		$data['logdate'] = $tdate; 
		$logsfile = read_file('./logs/'.$filename);
		$filedata = array();
		$filedata2 = array();
		if($logsfile !="")
		 { 
			if(strpos($logsfile,'===='))
			{
				$logsfile = explode('====',$logsfile);
				for($i=0;$i<count($logsfile)-1;$i++)
				{
					if(strpos($logsfile[$i],';'))
					{
						$logs = explode(';',$logsfile[$i]);
					}
					array_push($filedata,$logs);
				}
			}
		 }
		 
		 
		 
		 if($sip !="" && $suser !="" && $sevent !="")
		 {
			 $filedata2 = array();
			 if(count($filedata)>0)
			 {
				 foreach($filedata as $fd)
				 {
					 if($fd[1]==$sip && $fd[2]==$suser && $fd[3]==$sevent)
					 {
						 array_push($filedata2,$fd);
					 }
				 }
				 if(count($filedata2)>0)
				 {
					 $data['logsfile'] = $filedata2;
				 }
			 }
			 $data['sip'] = $sip;
			 $data['suser'] = $suser;
			 $data['sevent'] = $sevent;
		 }
		 else if($sip !="" && $suser !="")
		 {
			 $filedata2 = array();
			 if(count($filedata)>0)
			 {
				 foreach($filedata as $fd)
				 {
					 if($fd[1]==$sip && $fd[2]==$suser)
					 {
						 array_push($filedata2,$fd);
					 }
				 }
				 if(count($filedata2)>0)
				 {
					 $data['logsfile'] = $filedata2;
				 }
			 }
			 $data['sip'] = $sip;
			 $data['suser'] = $suser;
		 }
		 else if($sip !="" && $sevent !="")
		 {
			 $filedata2 = array();
			 if(count($filedata)>0)
			 {
				 foreach($filedata as $fd)
				 {
					 if($fd[1]==$sip && $fd[3]==$sevent)
					 {
						 array_push($filedata2,$fd);
					 }
				 }
				 if(count($filedata2)>0)
				 {
					 $data['logsfile'] = $filedata2;
				 }
			 }
			 $data['sip'] = $sip;
			 $data['sevent'] = $sevent;
		 }
		 else if($suser !="" && $sevent !="")
		 {
			 $filedata2 = array();
			 if(count($filedata)>0)
			 {
				 foreach($filedata as $fd)
				 {
					 if($fd[2]==$suser && $fd[3]==$sevent)
					 {
						 array_push($filedata2,$fd);
					 }
				 }
				 if(count($filedata2)>0)
				 {
					 $data['logsfile'] = $filedata2;
				 }
			 }
			 $data['suser'] = $suser;
			 $data['sevent'] = $sevent;
		 }
		 else if($sip !="")
		 {
			 $filedata2 = array();
			 if(count($filedata)>0)
			 {
				 foreach($filedata as $fd)
				 {
					 if($fd[1]==$sip)
					 {
						 array_push($filedata2,$fd);
					 }
				 }
				 if(count($filedata2)>0)
				 {
					 $data['logsfile'] = $filedata2;
				 }
			 }
			 $data['sip'] = $sip;
			 
		 }
		 else if($suser !="")
		 {
			 $filedata2 = array();
			 if(count($filedata)>0)
			 {
				 foreach($filedata as $fd)
				 {
					 if($fd[2]==$suser)
					 {
						 array_push($filedata2,$fd);
					 }
				 }
				 if(count($filedata2)>0)
				 {
					 $data['logsfile'] = $filedata2;
				 }
			 }
			 $data['suser'] = $suser;
			 
		 }
		 else if($sevent !="")
		 {
			 $filedata2 = array();
			 if(count($filedata)>0)
			 {
				 foreach($filedata as $fd)
				 {
					 if($fd[3]==$sevent)
					 {
						 array_push($filedata2,$fd);
					 }
				 }
				 if(count($filedata2)>0)
				 {
					 $data['logsfile'] = $filedata2;
				 }
			 }
			 $data['sevent'] = $sevent;
		 }
		 if(count($filedata2)==0)
		 {
			$data['logsfile'] = $filedata;
		 }
		$data['container'] = $this->load->view('active_logs_file_list',$data,true); //customer_message_page_admin
		$this->load->view('masteradmin',$data);
	}
	//reports
	public function stock_report()
	{
		$desition = $this->input->post('desition');
		$stock = $this->input->post('stock');
		$data = array();
		if($desition =="" || $stock=="")
		{
			$data['productlist'] = $this->db->query("SELECT * FROM product order by code asc ")->result();
		}
		else
		{
			if($desition == 'greater_then')
			{
				$data['productlist'] = $this->db->query("SELECT * FROM product where stock > '".$stock."' order by code asc ")->result();
			}
			else if($desition == 'smaller_then')
			{
				$data['productlist'] = $this->db->query("SELECT * FROM product where stock < '".$stock."' order by code asc ")->result();
			}
			else if($desition == 'equal_to')
			{
				$data['productlist'] = $this->db->query("SELECT * FROM product where stock = '".$stock."' order by code asc ")->result();
			}
		}
		$data['container'] = $this->load->view('stock_show_admin',$data,true); //customer_message_page_admin
		$this->load->view('masteradmin',$data);
	}
	public function best_categories_report()
	{
		$data = array();
		$data['category'] = $this->db->query("SELECT * FROM category ")->result();
		$data['product'] = $this->db->query("SELECT * FROM product ")->result();
		$data['sale'] = $this->db->query("SELECT details FROM t_purchase where orderstatus ='Complete' ")->result();
		$data['container'] = $this->load->view('best_categories_admin',$data,true); //customer_message_page_admin
		$this->load->view('masteradmin',$data);
	}
	public function best_seller_report()
	{
		$data = array();
		$data['brand'] = $this->db->query("SELECT * FROM brand ")->result();
		$data['product'] = $this->db->query("SELECT * FROM product ")->result();
		$data['sale'] = $this->db->query("SELECT details FROM t_purchase where orderstatus ='Complete' ")->result();
		$data['container'] = $this->load->view('best_seller_admin',$data,true); //customer_message_page_admin
		$this->load->view('masteradmin',$data);
	}
	public function best_brand_report()
	{
		$data = array();
		$data['brand'] = $this->db->query("SELECT * FROM brand ")->result();
		$data['product'] = $this->db->query("SELECT * FROM product ")->result();
		$data['sale'] = $this->db->query("SELECT details FROM t_purchase where orderstatus ='Complete' ")->result();
		$data['container'] = $this->load->view('best_brand_admin',$data,true); //customer_message_page_admin
		$this->load->view('masteradmin',$data);
	}
	public function best_customer_report()
	{
		$data = array();
		$data['customer'] = $this->db->query("SELECT * FROM t_customer ")->result();
		$data['customerids'] = $this->db->query("SELECT customerid FROM(
SELECT distinct customerid, count(customerid)as cus_count FROM t_purchase WHERE orderstatus='Complete' group by customerid order by cus_count desc)tbl1")->result();
		$data['container'] = $this->load->view('best_customer_report_admin',$data,true); //customer_message_page_admin
		$this->load->view('masteradmin',$data);
	}
	public function complete_orders_report()
	{
		$data = array();
		$data['menu'] = 'confirm_orders_report';
		$data['pageheaders'] = 'Complete Orders';
		$data['returnpage'] = 1;
		$data['allorders'] = $this->db->query("select * from  t_purchase WHERE orderstatus='Complete' order by dateoforder desc ")->result();
		//$data['allorderreqlist'] = $this->db->query("select * from  t_purchase WHERE orderstatus='Complete' order by dateoforder desc")->result();
		$data['container'] = $this->load->view('total_order_show',$data,true);
		
		$this->load->view('masteradmin',$data);
	}
	public function pending_orders_report()
	{
		$data = array();
		$data['menu'] = 'pending_orders_report';
		$data['pageheaders'] = 'Pending Orders';
		$data['returnpage'] = 2;
		$data['allorders'] = $this->db->query("select * from  t_purchase WHERE orderstatus='' and paymentstatus='Pending' order by id desc ")->result();
		//$data['allorderreqlist'] = $this->db->query("select * from  t_purchase WHERE orderstatus='' and paymentstatus='Pending' order by id desc")->result();
		$data['container'] = $this->load->view('total_order_show',$data,true);
		
		$this->load->view('masteradmin',$data);
	}
	public function cancel_orders_report()
	{
		$data = array();
		$data['menu'] = 'cancel_orders_report';
		$data['pageheaders'] = 'Cancel Orders';
		$data['returnpage'] = 3;
		$data['allorders'] = $this->db->query("select * from  t_purchase WHERE orderstatus='Cencel' order by id desc ")->result();
		//$data['allorderreqlist'] = $this->db->query("select * from  t_purchase WHERE orderstatus='Cencel' order by id desc")->result();
		$data['container'] = $this->load->view('total_order_show',$data,true);
		
		$this->load->view('masteradmin',$data);
	}
	public function orders_locations_report()
	{
		$data = array();
		$data['orderedlocations'] = $this->db->query("SELECT DISTINCT deliveryloc FROM `t_purchase`")->result();
		$data['container'] = $this->load->view('most_customer_location_report_admin',$data,true); //customer_message_page_admin
		$this->load->view('masteradmin',$data);
	}
	public function bad_review_product_report()
	{
		$data = array();
		$data['Category'] = $this->db->query("SELECT * FROM category ")->result();
		$data['subCategory'] = $this->db->query("SELECT * FROM sub_category ")->result();
		$data['brand'] = $this->db->query("SELECT * FROM brand ")->result();
		$data['color'] = $this->db->query("SELECT * FROM color ")->result();
		$data['product'] = $this->db->query("SELECT * FROM product where avg_review ='bad' ")->result();
		$data['container'] = $this->load->view('bad_review_product_admin',$data,true); 
		$this->load->view('masteradmin',$data);
	}
	public function most_review_product_report()
	{
		$data = array();
		$data['brand'] = $this->db->query("SELECT * FROM brand ")->result();
		$data['product'] = $this->db->query("SELECT * FROM product order by cus_review desc ")->result();
		$data['container'] = $this->load->view('review_product_admin',$data,true); //customer_message_page_admin
		$this->load->view('masteradmin',$data);
	}
	public function most_cancel_product_report()
	{
		$data = array();
		$data['brand'] = $this->db->query("SELECT * FROM brand ")->result();
		$data['product'] = $this->db->query("SELECT * FROM product ")->result();
		$data['sale'] = $this->db->query("SELECT details FROM t_purchase where orderstatus ='Cencel' ")->result();
		$data['container'] = $this->load->view('most_cancel_product_admin',$data,true); //customer_message_page_admin
		$this->load->view('masteradmin',$data);
	}
	public function top_search_word_report()
	{
		$data = array();
		$data['searchword'] = $this->db->query("SELECT value FROM t_settings where name ='search word' ")->result();
		$data['container'] = $this->load->view('top_search_word',$data,true); //customer_message_page_admin
		$this->load->view('masteradmin',$data);
	}
	public function bestseller_searchdata()
	{
		$searchword = trim($this->input->post("searchword"));
		$searchoption = trim($this->input->post("searchoption"));
		
		$data = array();
		$data['per_page'] = 50;
		$limit_start = 0;
		$limit_end = 0;
        if (isset($_GET['page'])&&!empty($_GET['page']) ) 
		{
            $data['start'] = ($_GET['page'] - 1) * $data['per_page'] + 1;  //$data['start'] = ($_GET['page'] - 1) * $data['per_page'] + 1; 
			$limit_start = $data['start']-1; 
        } 
		else 
		{
            $data['start'] = 1;
			$limit_start = 0;
        }
		if (isset($_GET['page'])&&!empty($_GET['page']) ) 
		{
        	$data['end'] = $data['start'] + $data['per_page'];
			$limit_end = $data['per_page']; 
		}
		else
		{
			$data['end'] = $data['start'] + $data['per_page'];
			$limit_end = $data['per_page']; 
		}
		$data['Category'] = $this->mm->getinfo('category');
		$data['brand'] = $this->mm->getinfo('brand');
        $data['subCategory'] = $this->mm->getinfo('sub_category');
        $data['unit'] = $this->mm->getinfo('unit');
		$data['color'] = $this->mm->getinfo('color');
		if($searchoption == 'all')
		{
        	$data['model'] = $this->db->query("SELECT * FROM product order by code LIMIT $limit_start , $limit_end ")->result();
			$data['productlist'] = $this->db->query("SELECT * FROM product order by code ")->result();
		}
		else if($searchoption == 'code')
		{
        	$data['model'] = $this->db->query("SELECT * FROM product where code='".$searchword."' LIMIT $limit_start , $limit_end ")->result();
			$data['productlist'] = $this->db->query("SELECT * FROM product where code='".$searchword."' ")->result();
		}
		else if($searchoption == 'modelno')
		{
        	$data['model'] = $this->db->query("SELECT * FROM product where  modelno='".$searchword."' order by code LIMIT $limit_start , $limit_end ")->result();
			$data['productlist'] = $this->db->query("SELECT * FROM product where modelno='".$searchword."' order by code ")->result();
		}
		else if($searchoption == 'title')
		{
        	$data['model'] = $this->db->query("SELECT * FROM product where  title='".$searchword."'  LIMIT $limit_start , $limit_end ")->result();
			$data['productlist'] = $this->db->query("SELECT * FROM product where title='".$searchword."' ")->result();
		}
		else if($searchoption == 'buyprice')
		{
        	$data['model'] = $this->db->query("SELECT * FROM product where  buyprice='".$searchword."' order by code LIMIT $limit_start , $limit_end ")->result();
			$data['productlist'] = $this->db->query("SELECT * FROM product where buyprice='".$searchword."' order by code ")->result();
		}
		else if($searchoption == 'saleprice')
		{
        	$data['model'] = $this->db->query("SELECT * FROM product where  saleprice='".$searchword."' order by code LIMIT $limit_start , $limit_end ")->result();
			$data['productlist'] = $this->db->query("SELECT * FROM product where saleprice='".$searchword."' order by code ")->result();
		}
		else if($searchoption == 'category')
		{
			if($searchword !="")
			{
				$catid = $this->mm->getCatid($searchword);
				if($catid !="")
				{
        			$data['model'] = $this->db->query("SELECT * FROM product where  categoryid='".$catid."' order by code LIMIT $limit_start , $limit_end ")->result();
					$data['productlist'] = $this->db->query("SELECT * FROM product where categoryid='".$catid."' order by code ")->result();
				}
				else
				{
					$data['model'] = $this->db->query("SELECT * FROM product order by code  LIMIT $limit_start , $limit_end ")->result();
					$data['productlist'] = $this->db->query("SELECT * FROM product order by code")->result();
				}
			}
			else
			{
				$data['model'] = $this->db->query("SELECT * FROM product order by code  LIMIT $limit_start , $limit_end ")->result();
				$data['productlist'] = $this->db->query("SELECT * FROM product order by code")->result();
			}
		}
		else if($searchoption == 'stock')
		{
        	$data['model'] = $this->db->query("SELECT * FROM product where  stock='".$searchword."' order by code LIMIT $limit_start , $limit_end ")->result();
			$data['productlist'] = $this->db->query("SELECT * FROM product where stock='".$searchword."' order by code ")->result();
		}
		else if($searchoption == 'showofferproduct')
		{
        	$data['model'] = $this->db->query("SELECT * FROM product where  offerid !='' order by code LIMIT $limit_start , $limit_end ")->result();
			$data['productlist'] = $this->db->query("SELECT * FROM product where offerid !='' order by code ")->result();
		}
		else if($searchoption == 'bestseller')
		{
        	$data['model'] = $this->db->query("SELECT * FROM product where  bestseller=1 order by code LIMIT $limit_start , $limit_end ")->result();
			$data['productlist'] = $this->db->query("SELECT * FROM product where bestseller=1 order by code ")->result();
		}
		else
		{
        	$data['model'] = $this->db->query("SELECT * FROM product order by code  LIMIT $limit_start , $limit_end ")->result();
			$data['productlist'] = $this->db->query("SELECT * FROM product order by code")->result();
		}
		
        $data['container'] = $this->load->view('bestseller', $data, true);
        //$data['container'] = $this->load->view('product/product_page', $data, true);
        $this->load->view('masteradmin', $data);
	}
	public function bestseller() 
	{
        $data = array();
		$data['per_page'] = 10;
		$limit_start = 0;
		$limit_end = 0;
        if (isset($_GET['page'])&&!empty($_GET['page']) ) 
		{
            $data['start'] = ($_GET['page'] - 1) * $data['per_page'] + 1;  //$data['start'] = ($_GET['page'] - 1) * $data['per_page'] + 1; 
			$limit_start = $data['start']-1; 
        } 
		else 
		{
            $data['start'] = 1;
			$limit_start = 0;
        }
		if (isset($_GET['page'])&&!empty($_GET['page']) ) 
		{
        	$data['end'] = $data['start'] + $data['per_page'];
			$limit_end = $data['per_page']; 
		}
		else
		{
			$data['end'] = $data['start'] + $data['per_page'];
			$limit_end = $data['per_page']; 
		}
		$data['Category'] = $this->mm->getinfo('category');
		$data['brand'] = $this->mm->getinfo('brand');
        $data['subCategory'] = $this->mm->getinfo('sub_category');
        $data['unit'] = $this->mm->getinfo('unit');
		$data['color'] = $this->mm->getinfo('color');
        $data['model'] = $this->db->query("SELECT * FROM product order by code LIMIT $limit_start , $limit_end ")->result();
		$data['productlist'] = $this->db->query("SELECT * FROM product order by code")->result();
        $data['container'] = $this->load->view('bestseller', $data, true);
        $this->load->view('masteradmin', $data);
    }
	public function add_in_bestseller() {
        $id = array("id" => $this->uri->segment(3));

        $data = array();
      	$data['Category'] = $this->mm->getinfo('category');
		$data['brand'] = $this->mm->getinfo('brand');
        $data['subCategory'] = $this->mm->getinfo('sub_category');
        $data['unit'] = $this->mm->getinfo('unit');
		$data['color'] = $this->mm->getinfo('color');
        $data['model'] = $this->mm->get_all_info_by_id('product', $id);
        $data['container'] = $this->load->view('bestseller_add_individualy', $data, true);
        //$data['container'] = $this->load->view('product/product_page', $data, true);
        $this->load->view('masteradmin', $data);
    }
	public function update_product_bestseller()
	{
		$permission = $this->session->userdata('permission');
		$acl = 54;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$id = trim($this->input->post("id"));
			$bestseller = trim($this->input->post("bestseller"));
			
			$err=0;
			$msg="";
			if($bestseller =="" || $bestseller ==0){$msg .=++$err.". Select Bestseller<br>";}
			
			if($err==0)
			{
				$data = array();
				if($bestseller == '-1')
				{
					$data['bestseller']= "";
				}
				else
				{
					$data['bestseller']= 1;
				}
				
				if($this->mm->update_info('product', $data, array('id'=>$id)))
				{
					$msg="";
					if($bestseller !="-1"){$msg="Product Added In Bestseller";}else{$msg="Product Removed From Bestseller";}
					redirect("admincontroller/bestseller?sk=".$msg, "refresh");
				}
				else 
				{
					redirect("admincontroller/bestseller?esk=Database too busy, Can't add", "refresh");
				}
			}
			else 
			{
				redirect("admincontroller/bestseller?esk=".$msg, "refresh");
			}
		}
		else
		{
			redirect(base_url() . "admincontroller/bestseller?esk=No Access");
		}
		
	}
	public function newsletter()
	{
		$data = array();
		$data['menu'] = 'newsletter';
		$data['container'] = $this->load->view('newsletter_form',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function update_newsletter()
	{
		$permission = $this->session->userdata('permission');
		$acl = 83;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$newsletterdata = $this->input->post('newsletterdata');
			
			if(!empty($newsletterdata)){$data = array();$data['value'] = trim($newsletterdata);
				$this->mm->update_info('t_settings',$data,array('name'=>'newsletter'));}	
			$this->mm->SaveLogs('update','update_newsletter');
			redirect("admincontroller/newsletter?sk=Updated");
		}
		else
		{
			redirect(base_url() . "admincontroller/newsletter?esk=No Access");
		}
	}
	public function visitors_online_report()
	{
		$data = array();
		$data['menu'] = 'visitors_online';
		$data['container'] = $this->load->view('user_online_admin',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function coupon()
	{
		$data = array();
		$data['menu'] = 'coupon';
		$data['container'] = $this->load->view('coupon',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function selected_coupon_product()
	{
		$idlist = $this->input->post('idlist');
		if(count($idlist)==0){redirect('admincontroller/coupon');}
		$data = array();
		$data['menu'] = 'coupon';
		$data['selectedproductidlist'] = implode(',',$idlist);
		$data['container'] = $this->load->view('coupon',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function create_new_coupon()
	{
		if($_POST)
		{
			$couponcode = $this->input->post('couponcode');
			$couponamount = $this->input->post('couponamount');
			$issuedate = $this->input->post('issuedate');
			$expiredate = $this->input->post('expiredate');
			$minimumpurchase = $this->input->post('minimum_purchase');
			$selectedproduct = $this->input->post('selectedproduct');
			$firstpurchase = $this->input->post('firstpurchase');
			$allproducts = $this->input->post('allproducts');
			$selectedcategory = $this->input->post('selectedcategory');
			
			$minimumpurchaseamount="";$selectedproductidlist="";$fp="";$ap="";$sc="";
			if($minimumpurchase == "on"){$minimumpurchaseamount = $this->input->post('minimumpurchaseamount');}
			if($selectedproduct == "on"){$selectedproductidlist = $this->input->post('selectedproductidlist');}
			else if($firstpurchase == "on"){$fp=1;}
			else if($allproducts == "on"){$ap=1;}
			else if($selectedcategory == "on"){$sc = $this->input->post('sc');}  
			
			$err=0;
			$msg="";
			if($couponcode == "")
			{
				$msg .=++$err.". Coupon code required<br>";
			}
			if($couponamount == "")
			{
				$msg .=++$err.". Coupon value required<br>";
			}
			if($issuedate == "")
			{
				$msg .=++$err.". Coupon issue date required<br>";
			}
			if($expiredate == "")
			{
				$msg .=++$err.". Coupon expiredate date required<br>";
			}
			if($minimumpurchase == "on")
			{
				if($minimumpurchaseamount == ""){
				$msg .=++$err.". Minimum purchase amount required<br>";}
			}
			if($selectedproduct == "on")
			{
				if($selectedproductidlist == ""){
				$msg .=++$err.". selected productid list required<br>";}
			}
			if($selectedcategory == "on")
			{
				if($sc == ""){
				$msg .=++$err.". selected category required<br>";}
			}
			
			if($err==0)
			{
				$data = array();
				$data['code'] = $couponcode;
				$data['amount'] = $couponamount;
				$data['issue'] = date("Y-m-d", strtotime($issuedate));
				$data['expire'] = date("Y-m-d", strtotime($expiredate));
				if($minimumpurchase == "on")
				{
					$data['mpurchase'] = $minimumpurchaseamount;
				}
				if($selectedproduct == "on")
				{
					$data['sp'] = $selectedproductidlist;
				}
				if($firstpurchase == "on")
				{
					$data['fp'] = $fp;
				}
				if($allproducts == "on")
				{
					$data['ap'] = $ap;
				}
				if($selectedcategory == "on")
				{
					$data['sc'] = $sc;
				}
				$insertresult = $this->mm->insert_data('t_coupon',$data);
				if($insertresult == true)
				{
					$this->mm->SaveLogs('Coupon',"Coupon code:'".$couponcode."' Created");
					redirect("admincontroller/coupon?sk=Saved successfully");
				}
				else
				{
					redirect("admincontroller/coupon?esk=error!!!, try again");
				}
			}
			else
			{
				redirect("admincontroller/coupon?esk=".$msg);
			}
		}
		else
		{
			redirect("main");
		}
	}
	public function view_coupon()
	{
		$data = array();
		$data['menu'] = 'view_coupon';
		$data['category'] = $this->db->query("select * from category")->result();
		$data['couponlist'] = $this->db->query("select * from t_coupon order by uses asc")->result();
		$data['container'] = $this->load->view('coupon_view',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function edit_coupon($id)
	{
		$data = array();
		$data['menu'] = 'coupon';
		$data['acoupon'] = $this->db->query("select * from t_coupon where id='".$id."'")->result();
		$data['container'] = $this->load->view('coupon_edit',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function update_coupon()
	{
		if($_POST)
		{
			$id = $this->input->post('id');
			$couponcode = $this->input->post('couponcode');
			$couponamount = $this->input->post('couponamount');
			$issuedate = $this->input->post('issuedate');
			$expiredate = $this->input->post('expiredate');
			$minimumpurchase = $this->input->post('minimum_purchase');
			$selectedproduct = $this->input->post('selectedproduct');
			$firstpurchase = $this->input->post('firstpurchase');
			$allproducts = $this->input->post('allproducts');
			$selectedcategory = $this->input->post('selectedcategory');
			
			$minimumpurchaseamount="";$selectedproductidlist="";$fp="";$ap="";$sc="";
			if($minimumpurchase == "on"){$minimumpurchaseamount = $this->input->post('minimumpurchaseamount');}
			if($selectedproduct == "on"){$selectedproductidlist = $this->input->post('selectedproductidlist');}
			else if($firstpurchase == "on"){$fp=1;}
			else if($allproducts == "on"){$ap=1;}
			else if($selectedcategory == "on"){$sc = $this->input->post('sc');}  
			
			$err=0;
			$msg="";
			if($couponcode == "")
			{
				$msg .=++$err.". Coupon code required<br>";
			}
			if($couponamount == "")
			{
				$msg .=++$err.". Coupon value required<br>";
			}
			if($issuedate == "")
			{
				$msg .=++$err.". Coupon issue date required<br>";
			}
			if($expiredate == "")
			{
				$msg .=++$err.". Coupon expiredate date required<br>";
			}
			if($minimumpurchase == "on")
			{
				if($minimumpurchaseamount == ""){
				$msg .=++$err.". Minimum purchase amount required<br>";}
			}
			if($selectedproduct == "on")
			{
				if($selectedproductidlist == ""){
				$msg .=++$err.". selected productid list required<br>";}
			}
			if($selectedcategory == "on")
			{
				if($sc == ""){
				$msg .=++$err.". selected category required<br>";}
			}
			
			if($err==0)
			{
				$data = array();
				$data['code'] = $couponcode;
				$data['amount'] = $couponamount;
				$data['issue'] = date("Y-m-d", strtotime($issuedate));
				$data['expire'] = date("Y-m-d", strtotime($expiredate));
				
				if($minimumpurchase == "on"){ $data['mpurchase'] = $minimumpurchaseamount;} else{$data['mpurchase'] = 0;}
				if($selectedproduct == "on"){$data['sp'] = $selectedproductidlist;}else{$data['sp'] = "";}
				if($firstpurchase == "on"){$data['fp'] = $fp;}else{$data['fp'] = 0;}
				if($allproducts == "on"){$data['ap'] = $ap;}else{$data['ap'] = 0;}
				if($selectedcategory == "on"){$data['sc'] = $sc;}else{$data['sc'] = "";}
				//update
				if($this->mm->update_info('t_coupon', $data, array('id'=>$id)))
				{
					$this->mm->SaveLogs('Coupon',"Coupon id:'".$id."' Updated");
					redirect("admincontroller/view_coupon?sk=Update successfully");
				}
				else
				{
					redirect("admincontroller/view_coupon?esk=error!!!, Update problem");
				}
			}
			else
			{
				redirect("admincontroller/edit_coupon/".$id."?esk=".$msg);
			}
		}
		else
		{
			redirect("main");
		}
	}
	public function delete_coupon($id)
	{
		if($this->mm->delete_info('t_coupon',array('id'=>$id)))
		{
			$this->mm->SaveLogs('Coupon',"Coupon id:'".$id."' Deleted");
			redirect('admincontroller/view_coupon?sk=Deleted');
		}
		else
		{
			redirect('admincontroller/view_coupon?esk=Error!!!, Not Deleted');
		}
	}
	public function theme()
	{
		$data = array();
		$data['menu'] = 'theme';
		$data['container'] = $this->load->view('theme_change_form',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function change_theme()
	{
		$permission = $this->session->userdata('permission');
		$acl = 113;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$themeid = $this->input->post('themeid');
			
			if(!empty($themeid)){$data = array();$data['value'] = trim($themeid);
				$this->mm->update_info('t_settings',$data,array('name'=>'themeid'));}	
			$this->mm->SaveLogs('update','change_theme');
			redirect("admincontroller/theme?sk=Theme changed");
		}
		else
		{
			redirect(base_url() . "admincontroller/theme?esk=No Access");
		}
	}
	public function sms_api()
	{
		$data = array();
		$data['menu'] = 'smsapi';
		$data['container'] = $this->load->view('sms_api_form',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function sms_api_setting()
	{
		//user apikey url enable
		$user = trim($this->input->post('user'));
		$apikey = trim($this->input->post('apikey'));
		$url = trim($this->input->post('url'));
		$enable = trim($this->input->post('enable'));
		$data = array();
		$data['user'] = $user;
		$data['apikey'] = $apikey;
		$data['url'] = $url;
		$data['enable'] = $enable;
		$smsdata = $this->db->query("SELECT * FROM sms_apis")->result();
		if(empty($smsdata)){$this->mm->insert_data('sms_apis',$data);}
		else{$this->mm->update_info('sms_apis',$data,array('id'=>1));}
		redirect(base_url() . "admincontroller/sms_api?sk=Saved Successfully");
	}
	public function notificationshow()
	{
		$reader = $this->session->userdata('username');
		$message ="";
		$count=0;		
				$notification = $this->db->query("SELECT * FROM t_notification")->result();
				if(count($notification)>0)
				{
					foreach($notification as $notify)
					{
						$readers = $notify->reader;
						$readers = explode(',',$readers); 
						if(!in_array($reader,$readers))
						{
							$count++;
							$message .=  "<li><a href='admincontroller/allnotificationshow' ><i class='fa fa-shopping-cart text-aqua'></i>".$notify->notification."</a></li>";
						}
					}
				}
     		
			$data =array();
			$data['notification'] = $message;
			$data['count'] = $count;
			$json = json_encode($data);
			echo $json;exit;
	}
	public function notificationhide()
	{
		$id = $this->input->post('id');
		$notificationdata = $this->db->query("SELECT * FROM t_notification WHERE id='".$id."'")->result();
		$reader = $this->session->userdata('username');
		$readers = "";
		if(count($notificationdata)>0)
		{
			$readers = $notificationdata[0]->reader;
			$abc = explode(',',$readers); 
			if(!in_array($reader,$abc))
			{
				$readers = $notificationdata[0]->reader.','.$reader;
			}
		}
		else
		{
			$readers = $reader;
		}
		$data = array();
		$data['reader'] = $readers;
		if($readers !="")
		{
			$this->mm->update_info('t_notification',$data,array('id'=>$id));
		}
		
		$message ="";
				$notification = $this->db->query("SELECT * FROM t_notification")->result();
				if(count($notification)>0)
				{
					foreach($notification as $notify)
					{
              			$readers = $notify->reader;
						if(!in_array($reader,$readers))
						{
							$message .=  "<li><a href='javascript:' onClick='notificationhide($notify->id)'><i class='fa fa-shopping-cart text-aqua'></i>".$notify->notification."</a></li>";
						}
					}
				}
     		
			$data =array();
			$data['notification'] = $message;
			$data['count'] = $count;
			$json = json_encode($data);
			echo $json;exit;
	}
	public function allnotificationshow()
	{
		$data = array();
		$data['menu'] = 'notification';
		$data['notification'] = $this->db->query("SELECT * FROM t_notification")->result();
		$data['container'] = $this->load->view('notification_show',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function notification_status()
	{
		$reader = $this->session->userdata('username');
		$chk = $this->input->post('chk');
		$decision = $this->input->post('decision');
		$err=0;
		$emsg="";
		if($reader == ""){$err++;$emsg .="Session problem";}
		if(count($chk)==0){
			$err++;$emsg .="Select notification<br>";
		}
		if($decision == 0){
			$err++;$err++;$emsg .="Select any action<br>";
		}
		if($err==0)
		{
			$notification = $this->db->query("SELECT * FROM t_notification")->result();
			if($decision == 1)//read
			{
				if(count($notification)>0)
				{
					foreach($notification as $notify)
					{
              			$readers = $notify->reader;
						$readers = explode(',',$readers); 
						$nid = $notify->id;
						if(in_array($nid,$chk))
						{
							if(!in_array($reader,$readers))
							{
								array_push($readers,$reader);
								$readers = implode(',',$readers);
								$this->db->query("Update t_notification SET reader='".$readers."' where id='".$nid."' ");
							}
						}
					}
					
				}
				redirect('admincontroller/allnotificationshow');
			}
			if($decision == 2)//unread
			{
				if(count($notification)>0)
				{
					foreach($notification as $notify)
					{
              			$readers = $notify->reader;
						$readers = explode(',',$readers); 
						$nid = $notify->id;
						if(in_array($nid,$chk))
						{
							if(in_array($reader,$readers))
							{
								$i = array_search($reader,$readers);
								unset($readers[$i]);
								$readers = implode(',',$readers); 
								$this->db->query("Update t_notification SET reader='".$readers."' where id='".$nid."' ");
							}
						}
					}
				}
				redirect('admincontroller/allnotificationshow');
			}
			if($decision == 3)//delete
			{
				for($i=0;$i<count($chk);$i++)
				{
					$this->db->query("delete from t_notification where id='".$chk[$i]."' ");
				}
				redirect('admincontroller/allnotificationshow');
			}
		}
		else
		{
			redirect('admincontroller/allnotificationshow?esk='.$emsg);
		}
	}
	public function notification_status_change($status,$id)
	{
		$reader = $this->session->userdata('username');
		$notification = $this->db->query("SELECT * FROM t_notification WHERE id='".$id."'")->result();
		if($status == 'read')
		{
			if(count($notification)>0)
			{
				foreach($notification as $notify)
				{
					$readers = $notify->reader;
					$readers = explode(',',$readers); 
					if(!in_array($reader,$readers))
					{
						array_push($readers,$reader);
						$readers = implode(',',$readers);
						$this->db->query("Update t_notification SET reader='".$readers."' where id='".$id."' ");
					}
				}
			}
		}
		else if($status == 'unread')
		{
			if(count($notification)>0)
			{
				foreach($notification as $notify)
				{
					$readers = $notify->reader;
					$readers = explode(',',$readers); 
					if(in_array($reader,$readers))
					{
						$i = array_search($reader,$readers);
						unset($readers[$i]);
						$readers = implode(',',$readers); 
						$this->db->query("Update t_notification SET reader='".$readers."' where id='".$id."' ");
					}
				}
			}
		}
		redirect('admincontroller/allnotificationshow');
	}
	public function notification_search()
	{
		$notif = trim($this->input->post('notif'));
		$datep = trim($this->input->post('datep'));
		$notification ="";
		if($notif !="" && $datep !="")
		{
			$notification = $this->db->query("SELECT * FROM t_notification WHERE notification like '%".$notif."%' and date like'".$datep."%' ")->result();
		}
		else if($notif !="" && $datep =="")
		{
			$notification = $this->db->query("SELECT * FROM t_notification WHERE notification like '%".$notif."%' ")->result();
		}
		else if($notif =="" && $datep !="")
		{
			$notification = $this->db->query("SELECT * FROM t_notification WHERE date like '".$datep."%' ")->result();
		}
		if($notification =="")
		{
			
			$notification = $this->db->query("SELECT * FROM t_notification")->result();
		}
		
		$data = array();
		$data['menu'] = 'notification';
		$data['notification'] = $notification;
		$data['container'] = $this->load->view('notification_show',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function mailshow()
	{
		$reader = $this->session->userdata('username');
		$useremail = $this->db->query("SELECT email FROM t_user where username='".$reader."' ")->row()->email;
		$subject ="";
		$count=0;		
				$allmail = $this->db->query("SELECT * FROM t_mail where mailto='".$useremail."'")->result();
				if(count($notification)>0)
				{
					foreach($allmail as $m)
					{ 
						$count++;
						$subject .=  "<li><a href='admincontroller/allmailshow' ><i class='fa fa-envelope text-aqua'></i>".$m->subject."</a></li>";
					}
				}
     		
			$data =array();
			$data['subject'] = $subject;
			$data['count'] = $count;
			$json = json_encode($data);
			echo $json;exit;
	}
	
}//close controller
