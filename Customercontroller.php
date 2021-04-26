<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customercontroller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Dhaka");
		//$this->mm->isLogin();  ///////// Call isLogin() for all function ////////////////////
	}
	public function index()
	{
		$cusemail = $this->session->userdata('cusemail');
		$customerdata = $this->db->query("SELECT *  FROM t_customer where email ='".$cusemail."' ")->result();
		$data = array();
		$data['menu'] = 'account_controlpanel';
		$data['customerdata'] = $customerdata;
		$data['category'] = $this->db->query("SELECT *  FROM category ")->result();
		$data['content'] = $this->load->view('cus_account_controlpanel_client',$data,true);
		$this->load->view('mastercustomer',$data);
	}
	public function account_controlpanel()
	{
		$cusemail ="";
		$cusemail = $this->session->userdata('cusemail');
		if($cusemail !="")
		{
		$customerdata = $this->db->query("SELECT *  FROM t_customer where email ='".$cusemail."' ")->result();
		$data = array();
		$data['menu'] = 'account_controlpanel';
		$data['customerdata'] = $customerdata;
		$data['category'] = $this->db->query("SELECT *  FROM category ")->result();
		$data['content'] = $this->load->view('cus_account_controlpanel_client',$data,true);
		$this->load->view('mastercustomer',$data);
		}
		else
		{
			redirect('main/customer_login');
		}
	}
	public function customer_profile()
	{
		$cusemail = $this->session->userdata('cusemail');
		if($cusemail !="")
		{
			$customerdata = $this->db->query("SELECT *  FROM t_customer where email ='".$cusemail."' ")->result();
			if(count($customerdata >0))
			{
				$data = array();
				$data['menu'] = 'profile';
				$data['customerdata'] = $customerdata;
				$data['category'] = $this->db->query("SELECT *  FROM category ")->result();
				$data['content'] = $this->load->view('customer_reg_info',$data,true);
				$this->load->view('mastercustomer',$data);
			}
			else{redirect('main/customer_login');}
		}
		else{redirect('main/customer_login');}
	}
	//customer name update
	public function customer_name_update()
	{
		$id = $this->input->post('id');
		$firstname = trim($this->input->post('firstname')); 
		$lastname = trim($this->input->post('lastname'));
		$err=0;
		$msg="";
		if($firstname =="")
		{
			$msg .=++$err.". First name required<br>";
		}
		else if(strlen($firstname) >100 )
		{
			$msg .=++$err.".First name maximum length exceed<br>";
		}
		if($lastname =="")
		{
			$msg .=++$err.". First name required<br>";
		}
		else if(strlen($lastname) >100 )
		{
			$msg .=++$err.".First name maximum length exceed<br>";
		}
		if($err==0)
		{
			$data = array();
			$data['firstname'] = $firstname;
			if($lastname !=""){
			$data['lastname'] = $lastname;}
			if($this->mm->update_info('t_customer',$data,array("id"=>$id)))
			{
				$this->mm->SaveLogs('Update',"Customer id:".$id. "name Info Update");
				$this->session->set_userdata('cusfirstname',$firstname);
				$this->session->set_userdata('cuslastname',$lastname);
				redirect("customercontroller/customer_profile?sk=Update Successfully");
			}
			else
			{
				redirect("customercontroller/customer_profile?esk=Error!!!, Not Updated");
			}
		}
		else
		{
			redirect("customercontroller/customer_profile?esk=".$msg);
		}
	}
	//customer email update
	public function customer_email_update()
	{
		$id = $this->input->post('id'); 
		$useremail = $this->input->post('useremail');
		$email = trim($this->input->post('email')); 
		$userdata = $this->db->query("select * from t_customer where email='".$email."'")->result();
		$err=0;
		$msg="";
		if($email =="")
		{
			$msg .=++$err.". Email required<br>";
		}
		else if(strlen($email) >150 )
		{
			$msg .=++$err.".Email maximum length exceed<br>";
		}
		else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$msg .=++$err.". Email invalid<br>";
		}
		if($useremail != $email)
		{
			if(count($userdata)>0)
			{
				$msg .=++$err.". Email already exist<br>";
			}
		}
		
		if($err==0)
		{
			$data = array();
			$data['email'] = $email;
			if($this->mm->update_info('t_customer',$data,array("id"=>$id)))
			{
				$this->mm->SaveLogs('Update',"Customer id:".$id. "email Info Update");
				$this->session->set_userdata('cusemail',$email);
				redirect("customercontroller/customer_profile?sk=Update Successfully");
			}
			else
			{
				redirect("customercontroller/customer_profile?esk=Error!!!, Not Updated");
			}
		}
		else
		{
			redirect("customercontroller/customer_profile?esk=".$msg);
		}
	}
	//customer phone update
	public function customer_phone_update()
	{
		$id = $this->input->post('id');
		$userephone = $this->input->post('userephone');
		$phone = trim($this->input->post('phone')); 
		$userdata = $this->db->query("select * from t_customer where phone='".$phone."'")->result();
		$err=0;
		$msg="";
		if($phone =="")
		{
			$msg .=++$err.". Phone required<br>";
		}
		else if(strlen($phone) >20 )
		{
			$msg .=++$err.".phone length exceed<br>";
		}
		else if($userephone != $phone)
		{
			if(count($userdata)>0)
			{
				$msg .=++$err.". Phone already exist<br>";
			}
		}
		else if(!is_nan($phone)){$msg .=++$err.". Phone invalid<br>";}
		
		if($err==0)
		{
			$data = array();
			$data['phone'] = $phone;
			if($this->mm->update_info('t_customer',$data,array("id"=>$id)))
			{
				$this->mm->SaveLogs('Update',"Customer id:".$id. "phone: Info Update");
				$this->session->set_userdata('phone',$phone);
				redirect("customercontroller/customer_profile?sk=Update Successfully");
			}
			else
			{
				redirect("customercontroller/customer_profile?esk=Error!!!, Not Updated");
			}
		}
		else
		{
			redirect("customercontroller/customer_profile?esk=".$msg);
		}
	}
	//customer password update
	public function customer_password_update()
	{
		$id = $this->input->post('id');
		$userecurpass = $this->input->post('userecurpass');
		$cpass = trim($this->input->post('cpass')); 
		$npass = trim($this->input->post('npass')); 
		$confirmpass = trim($this->input->post('confirmpass'));
		
		$userdata = $this->db->query("select * from t_customer where id='".$id."'")->result();
		$err=0;
		$msg="";
		if($userecurpass =="")
		{
			$msg .=++$err.". Current password required<br>";
		}
		if($npass =="")
		{
			$msg .=++$err.". New password required<br>";
		}
		else if(strlen($npass) >12 )
		{
			$msg .=++$err.".New password length exceed<br>";
		}
		if($confirmpass =="")
		{
			$msg .=++$err.". confirm password required<br>";
		}
		else if($confirmpass != $npass)
		{
			$msg .=++$err.". password not match<br>";
		}
		if($err==0)
		{
			$data = array();
			$data['password'] = $npass;
			if($this->mm->update_info('t_customer',$data,array("id"=>$id)))
			{
				$this->mm->SaveLogs('Update',"Customer id:".$id. "Password: changed");
				redirect("customercontroller/customer_profile?sk=Update Successfully");
			}
			else
			{
				redirect("customercontroller/customer_profile?esk=Error!!!, Not Updated");
			}
		}
		else
		{
			redirect("customercontroller/customer_profile?esk=".$msg);
		}
	}
	//customer address update
	public function customer_address_update()
	{
		$id = $this->input->post('id');
		$address = $this->input->post('address'); 
		$err=0;
		$msg="";
		if($address =="")
		{
			$msg .=++$err.". Address required<br>";
		}
		else if(strlen($address) >200 )
		{
			$msg .=++$err.".Address length exceed<br>";
		}
		if($err==0)
		{
			$data = array();
			$data['address'] = $address;
			if($this->mm->update_info('t_customer',$data,array("id"=>$id)))
			{
				$this->mm->SaveLogs('Update',"Customer id:".$id.". address Info Update");
				$this->session->set_userdata('cusaddress',$address);
				redirect("customercontroller/customer_profile?sk=Update Successfully");
			}
			else
			{
				redirect("customercontroller/customer_profile?esk=Error!!!, Not Updated");
			}
		}
		else
		{
			redirect("customercontroller/customer_profile?esk=".$msg);
		}
	}
	//customer city update
	public function customer_city_update()
	{
		$id = $this->input->post('id');
		$city = $this->input->post('city'); 
		$err=0;
		$msg="";
		if($city =="")
		{
			$msg .=++$err.". city required<br>";
		}
		else if(strlen($city) >100 )
		{
			$msg .=++$err.".city length exceed<br>";
		}
		if($err==0)
		{
			$data = array();
			$data['city'] = $city;
			if($this->mm->update_info('t_customer',$data,array("id"=>$id)))
			{
				$this->mm->SaveLogs('Update',"Customer id:".$id.". city Info Update");
				$this->session->set_userdata('city',$city);
				redirect("customercontroller/customer_profile?sk=Update Successfully");
			}
			else
			{
				redirect("customercontroller/customer_profile?esk=Error!!!, Not Updated");
			}
		}
		else
		{
			redirect("customercontroller/customer_profile?esk=".$msg);
		}
	}
	//customer countrty update
	public function customer_country_update()
	{
		$id = $this->input->post('id');
		$country = $this->input->post('country'); 
		$data = array();
		$data['country'] = $country;
		if($this->mm->update_info('t_customer',$data,array("id"=>$id)))
		{
			$this->mm->SaveLogs('Update',"Customer id:".$id.". country Info Update");
			$this->session->set_userdata('country',$country);
			redirect("customercontroller/customer_profile?sk=Update Successfully");
		}
		else
		{
			redirect("customercontroller/customer_profile?esk=Error!!!, Not Updated");
		}
		
		
	}
	
	
	
	public function logout()
	{
		$this->session->unset_userdata('cusemail');
		$this->session->unset_userdata('cusfirstname');
		$this->session->unset_userdata('cuslastname');
		$this->session->unset_userdata('cusid');
		redirect("main/home");
	}
	public function address_book()
	{
		$cusemail = $this->session->userdata('cusemail');
		if($cusemail !="")
		{
			$customerdata = $this->db->query("SELECT *  FROM t_customer where email ='".$cusemail."' ")->result();
			$data = array();
			$data['menu'] = 'address book';
			$data['customerdata'] = $customerdata;
			$data['category'] = $this->db->query("SELECT *  FROM category ")->result();
			$data['content'] = $this->load->view('cus_account_adress_client',$data,true);
			$this->load->view('mastercustomer',$data);
		}
		else
		{
			redirect("main/customer_login");
		}
	}
	public function my_orders()
	{
		$cusemail = $this->session->userdata('cusemail');
		if($cusemail !="")
		{
			$customerdata = $this->db->query("SELECT *  FROM t_customer where email ='".$cusemail."' ")->result();
			$purchasedata = $this->db->query("SELECT *  FROM t_purchase ")->result();
			$data = array();
			$data['menu'] = 'myorders';
			$data['customerdata'] = $customerdata;
			$data['purchasedata'] = $purchasedata;
			$data['category'] = $this->db->query("SELECT *  FROM category ")->result();
			$data['content'] = $this->load->view('cus_orders_client',$data,true);
			$this->load->view('mastercustomer',$data);
		}
		else{
		redirect("main/customer_login");}
	}
	public function order_details($orderno)
	{
		$singleorderreq = $this->db->query("select * from  t_purchase where orderid = '".$orderno."' ")->result();
		if(count($singleorderreq)>0)
		{
			$data = array();
			$data['menu'] = 'myorders';
			$data['singleorderreq'] = $singleorderreq;
			$data['customer'] = $this->db->query("SELECT * FROM t_customer")->result();
			$data['orderno'] = $orderno;
			$data['category'] = $this->db->query("SELECT *  FROM category ")->result();
			$data['content'] = $this->load->view('single_order_details_for_client',$data,true);
			$this->load->view('mastercustomer',$data);
		}
		else
		{
			$this->load->view('404error');
		}
	}
	public function my_review()
	{
		$cusid = $this->session->userdata('cusid');
		$customerdata = $this->db->query("SELECT *  FROM  t_customerreview where cusid ='".$cusid."' ")->result();
		$product = $this->db->query("SELECT *  FROM product ")->result();
		$data = array();
		$data['menu'] = 'myreview';
		$data['customerdata'] = $customerdata;
		$data['product'] = $product;
		$data['category'] = $this->db->query("SELECT *  FROM category ")->result();
		$data['content'] = $this->load->view('cus_review_client',$data,true);
		$this->load->view('mastercustomer',$data);
	}
	public function delete_review($pcode)
	{
		$cusid = $this->session->userdata('cusid');
		if($this->mm->delete_info('t_customerreview',array('cusid'=>$cusid,'code'=>$pcode)))
		{
			redirect("customercontroller/my_review?sk=Deleted");
		}
		else
		{
			redirect("customercontroller/my_review?esk=Error!!! Not Deleted");
		}
		
	}
	public function my_wishlist() //view single product
	{
		$cusid = $this->session->userdata('cusid');
		
		$data = array();
		$data['menu'] = 'mywishlist';
		$data['product'] = $this->db->query("select * from product ")->result();
		$data['offerdata'] = $this->db->query("select * from t_offer ")->result();
		$data['category'] = $this->db->query("SELECT *  FROM category ")->result();
		$data['customerdata'] = $this->db->query("SELECT *  FROM t_customer where id='".$cusid."' ")->result();
		$data['content'] = $this->load->view('show_wishlist_client',$data,true);
		$this->load->view('mastercustomer',$data);
	}
	public function delete_wishlistitem()
	{
		$procode = $this->input->post('procode'); 
		$cusid = $this->session->userdata('cusid');
		$customerdata = $this->db->query("SELECT *  FROM t_customer where id='".$cusid."' ")->result();
		$wishlist="";
		if(count($customerdata)>0)
		{
			$wishlist=$customerdata[0]->wish_list;
		}
		if($wishlist !="")
		{
			if(strpos($wishlist, ','))
			{
				$wishlistarray = explode(',',$wishlist);
				$key = array_search($procode, $wishlistarray);
				unset($wishlistarray[$key]);
				$wishlist = implode(',',$wishlistarray);
				$this->db->query("update t_customer set wish_list='".$wishlist."' where id='".$cusid."' ");
				redirect('customercontroller/my_wishlist');
			}
			else 
			{
				$this->db->query("update t_customer set wish_list='' where id='".$cusid."' ");
				redirect('customercontroller/my_wishlist');
			}
		}else{redirect('customercontroller/my_wishlist');}
	}
	public function delete_all_wish_list()
	{
		$cusid = $this->session->userdata('cusid');
		$this->db->query("update t_customer set wish_list='' where id='".$cusid."' ");
		redirect('customercontroller/my_wishlist');
	}
	public function current_news()
	{
		$cusemail = $this->session->userdata('cusemail');
		$customerdata = $this->db->query("SELECT *  FROM t_customer where email ='".$cusemail."' ")->result();

		$viewnews = 1;
		if(count($customerdata)>0){$viewnews = $customerdata[0]->viewnewsletter;}
		$data = array();
		$data['menu'] = 'current_news';
		if($viewnews == 0)
		{
			$data['viewnews'] = $viewnews;
			$data['newsdata'] = $this->db->query("SELECT *  FROM  t_settings where name='newsletter' ")->result();
			$data['category'] = $this->db->query("SELECT *  FROM category ")->result();
			$data['content'] = $this->load->view('cus_newsletter_client',$data,true);
		}
		else
		{
			$data['viewnews'] = $viewnews;
			$data['category'] = $this->db->query("SELECT *  FROM category ")->result();
			$data['content'] = $this->load->view('cus_newsletter_client',$data,true);
		}
		$this->load->view('mastercustomer',$data);
	}
	public function emailverifypage($id)
	{
		$emailverifycode = $this->mm->randomCode(8);
		$cusemail = $this->session->userdata('cusemail');
		//////// mail //////////////
		
		$settingdata = $this->db->query("SELECT *  FROM t_settings ")->result(); 
			$smtp_host = "";$smtp_port = "";$smtp_user = "";$smtp_pass = "";$mailsendvia =""; $servermail ="";$companyname="";$companyaddress="";$companyphone="";
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
				if($d->name == 'phone'){$companyphone = $d->value;}
			}
			$to = $cusemail;
			$from = $companyname;
			$subject="Email varification";
			$message = "Dear Customer,<br>your email varification code: ".$emailverifycode;
			$message .= "\r\n\r\n\r\n\r\nBest Regards,\r\n\r\n".$companyname."\r\n".$companyaddress."\r\n".$companyphone;
			if($mailsendvia == "smtp")
			{
				$sendresult = $this->mm->send_mail($smtp_host,$smtp_port,$smtp_user,$smtp_pass,$to,$from,$subject,$message);
			}
			else
			{ 
				// To send HTML mail
				$headers[] = 'MIME-Version: 1.0';
				$headers[] = 'Content-type: text/html; charset=iso-8859-1';
				// Additional headers
				$headers[] = 'To:'.$cusemail;
				$headers[] = 'From: '.$companyname.' <'.$servermail.'>';
				
				$sendresult = mail($to,$subject,$message,implode("\r\n", $headers)); 
			}
		/////////////////////
		$cusdata = $this->db->query("select data from t_customer where id='".$id."' ")->result(); 
		$cdata = array();
		if(isset($cusdata[0]->data)&& $cusdata[0]->data=="")
		{
			$cdata['email_verify_code']= $emailverifycode;//new verify code
			$cdata['email_verify']= 'no';
			$cdata['phone_verify_code']= '';
			$cdata['phone_verify']= '';
			$jscode = json_encode($cdata);
			$this->db->query("update t_customer set data='".$jscode."' where id='".$id."' ");
		}
		else
		{
			$cussettingdata = $cusdata[0]->data;
			$cussettingdata = json_decode($cussettingdata);
			
			$email_verify = $cussettingdata->email_verify;
			$phone_verify_code = $cussettingdata->phone_verify_code;
			$phone_verify = $cussettingdata->phone_verify;
			
			$cdata['email_verify_code']= $emailverifycode;//new verify code
			$cdata['email_verify']= $email_verify;
			$cdata['phone_verify_code']= $phone_verify_code;
			$cdata['phone_verify']= $phone_verify;
			$jscode = json_encode($cdata);
			$this->db->query("update t_customer set data='".$jscode."' where id='".$id."' ");
		}
		
		$data = array();
		$data['cusid'] = $id;
		$this->load->view('emailverify',$data);
	}
	public function emailverify($customerid)
	{
		$code = trim($this->input->post("code"));
		$cusdata = $this->db->query("select data from t_customer where id='".$customerid."' ")->result();
		$cussettingdata = $cusdata[0]->data;
		$cussettingdata = json_decode($cussettingdata);
		
		$emailverifycode = $cussettingdata->email_verify_code;
		$email_verify = $cussettingdata->email_verify;
		$phone_verify_code = $cussettingdata->phone_verify_code;
		$phone_verify = $cussettingdata->phone_verify;
		
		if($emailverifycode == $code){$email_verify="yes";}else{$email_verify="no";}
		$cdata['email_verify_code']= $emailverifycode;//new verify code
		$cdata['email_verify']= $email_verify;
		$cdata['phone_verify_code']= $phone_verify_code;
		$cdata['phone_verify']= $phone_verify;
		$jscode = json_encode($cdata);
		$this->db->query("update t_customer set data='".$jscode."' where id='".$customerid."' ");
		redirect("customercontroller/customer_profile");
	}
	///phone verify/////
	public function phoneverifypage($id)
	{
		$phoneverifycode = $this->mm->randomCode(8);
		$cusphone = $this->session->userdata('cusphone');
		//////// sms //////////////
		$user="";$apikey="";$url="";$enable="";$companyname="";$companyphone="";
		$settingdata = $this->db->query("SELECT *  FROM t_settings ")->result();
		foreach($settingdata as $d)
		{
			if($d->name == 'Company Name'){$companyname = $d->value;}
			if($d->name == 'phone'){$companyphone = $d->value;}
		}
		
		$message = "'".$companyname."',<br>Phone varification code: ".$phoneverifycode;
		
		$phoneapidata = $this->db->query("SELECT * FROM `sms_apis` WHERE id=1")->result();
		if(!empty($phoneapidata))
		{
			foreach($phoneapidata as $apidata)
			{
				$user = $apidata->user;
				$apikey = $apidata->apikey;
				$url = $apidata->url;
				$enable = $apidata->enable;
			}
		}
		if($enable ==1)
		{
			$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL, "'".$url."'?username=!'".$user."'!&password=!'".$apikey."'!&sender=!'".$companyphone."'!&mobile=!'".$cusphone."'!&message=!'".$message."'!");
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_exec($ch);
			curl_close($ch);
		}
		/////////////////////
		$cusdata = $this->db->query("select data from t_customer where id='".$id."' ")->result(); 
		$cdata = array();
		if(isset($cusdata[0]->data)&& $cusdata[0]->data=="")
		{
			$cdata['email_verify_code']= "";
			$cdata['email_verify']= 'no';
			$cdata['phone_verify_code']= $phoneverifycode;//new verify code
			$cdata['phone_verify']= 'no';
			$jscode = json_encode($cdata);
			$this->db->query("update t_customer set data='".$jscode."' where id='".$id."' ");
		}
		else
		{
			$cussettingdata = $cusdata[0]->data;
			$cussettingdata = json_decode($cussettingdata);
			
			$email_verify_code = $cussettingdata->email_verify_code;
			$email_verify = $cussettingdata->email_verify;
			$phone_verify = 'no';
			
			$cdata['email_verify_code']= $email_verify_code;
			$cdata['email_verify']= $email_verify;
			$cdata['phone_verify_code']= $phoneverifycode;//new verify code
			$cdata['phone_verify']= $phone_verify;
			$jscode = json_encode($cdata);
			$this->db->query("update t_customer set data='".$jscode."' where id='".$id."' ");
		}
		
		$data = array();
		$data['cusid'] = $id;
		$this->load->view('phoneverify',$data);
	}
	public function phoneverify($customerid)
	{
		$code = trim($this->input->post("code"));
		$cusdata = $this->db->query("select data from t_customer where id='".$customerid."' ")->result();
		$cussettingdata = $cusdata[0]->data;
		$cussettingdata = json_decode($cussettingdata);
		
		$emailverifycode = $cussettingdata->email_verify_code;
		$email_verify = $cussettingdata->email_verify;
		$phone_verify_code = $cussettingdata->phone_verify_code;
		$phone_verify = $cussettingdata->phone_verify;
		
		if($phone_verify_code == $code){$phone_verify="yes";}else{$phone_verify="no";}
		$cdata['email_verify_code']= $emailverifycode;//new verify code
		$cdata['email_verify']= $email_verify;
		$cdata['phone_verify_code']= $phone_verify_code;
		$cdata['phone_verify']= $phone_verify;
		$jscode = json_encode($cdata);
		$this->db->query("update t_customer set data='".$jscode."' where id='".$customerid."' ");
		redirect("customercontroller/customer_profile");
	}
}










//close controller
