<?php
class _install extends CI_Controller{

	public function __construct()
	{
	parent::__construct();
	$this->load->model('mm'); //call model
	}

	public function index()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_message('required','Please enter %s');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');
		
		$this->form_validation->set_rules('companyName', 'Company name', 'required'); //companyName aboutCompanywork caddress cphone cemail
		$this->form_validation->set_rules('aboutCompanywork', 'About company work', 'required');
		$this->form_validation->set_rules('caddress', 'Company address', 'required');
		$this->form_validation->set_rules('cphone', 'Company phone', 'required');
		$this->form_validation->set_rules('cemail', 'Company email', 'required|valid_email');
	
	
		if ($this->form_validation->run() == FALSE)
		{
			$data['newpass']=$this->randomPassword();
			$this->load->view('install',$data);
		}
		else
		{
			$email=$this->input->post("email",TRUE);
			$mobile=$this->input->post("mobile",TRUE);
			$username=$this->input->post("username",TRUE);
			$password=$this->input->post("password",TRUE);
			$createdate = date('Y-m-d H:i:s');
			
			$companyName=$this->input->post("companyName",TRUE);
			$aboutCompanywork=$this->input->post("aboutCompanywork",TRUE);
			$caddress=$this->input->post("caddress",TRUE);
			$cphone=$this->input->post("cphone",TRUE);
			$cemail=$this->input->post("cemail",TRUE);
			$footerCopyrighttext=$this->input->post("footerCopyrighttext",TRUE);
	
				if(!$this->db->table_exists('t_admin') )
				{
					$this->db->query("CREATE TABLE  IF NOT EXISTS `brand` (
						 `id` int(11) NOT NULL auto_increment,
						 `name` varchar(100) NOT NULL,
						 `pic` varchar(150) NOT NULL,
						 PRIMARY KEY  (`id`),
						 UNIQUE KEY `name` (`name`)
						) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `category` (
						 `id` int(11) NOT NULL auto_increment,
						 `name` varchar(50) NOT NULL,
						 `iconname` varchar(150) NOT NULL,
						 `type` varchar(10) NOT NULL default 'general',
						 `categoryads` varchar(50) NOT NULL,
						 `showstatus` varchar(3) NOT NULL default 'yes',
						 `indexnumber` int(5) NOT NULL,
						 `ads_index` varchar(2) NOT NULL,
						 PRIMARY KEY  (`id`),
						 UNIQUE KEY `name` (`name`)
						) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1");
						
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `country` (
						 `id` int(11) NOT NULL auto_increment,
						 `name` varchar(30) NOT NULL,
						 PRIMARY KEY  (`id`),
						 UNIQUE KEY `name` (`name`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `city` (
						 `id` int(11) NOT NULL auto_increment,
						 `name` varchar(30) NOT NULL,
						 `countryid` int(11) NOT NULL,
						 PRIMARY KEY  (`id`),
						 UNIQUE KEY `name` (`name`,`countryid`),
						 KEY `countryid` (`countryid`),
						 CONSTRAINT `city_ibfk_1` FOREIGN KEY (`countryid`) REFERENCES `country` (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `color` (
						 `id` int(11) NOT NULL auto_increment,
						 `name` varchar(200) NOT NULL,
						 PRIMARY KEY  (`id`),
						 UNIQUE KEY `name` (`name`)
						) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `customer` (
						 `id` int(11) NOT NULL auto_increment,
						 `name` varchar(100) NOT NULL,
						 `email` varchar(100) NOT NULL,
						 `password` varchar(40) NOT NULL,
						 `gender` varchar(1) NOT NULL,
						 `contact` varchar(200) NOT NULL,
						 `cityid` int(11) NOT NULL,
						 `citylocation` varchar(50) NOT NULL,
						 `country` varchar(100) NOT NULL,
						 `phone` varchar(20) NOT NULL,
						 `picture` varchar(100) NOT NULL,
						 `status` varchar(30) NOT NULL,
						 `type` varchar(1) NOT NULL,
						 PRIMARY KEY  (`id`),
						 UNIQUE KEY `email` (`email`),
						 UNIQUE KEY `email_2` (`email`),
						 KEY `cityid` (`cityid`),
						 CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`cityid`) REFERENCES `city` (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `product` (
						 `id` int(11) NOT NULL auto_increment,
						 `code` varchar(10) NOT NULL,
						 `modelno` varchar(50) NOT NULL,
						 `title` varchar(300) NOT NULL,
						 `brandid` int(11) NOT NULL,
						 `description` text NOT NULL,
						 `shortdes` varchar(70) NOT NULL,
						 `buyprice` float(8,2) NOT NULL,
						 `saleprice` float(8,2) NOT NULL,
						 `categoryid` int(11) NOT NULL,
						 `subcategoryid` int(11) NOT NULL,
						 `unitid` int(11) NOT NULL,
						 `colorid` int(11) NOT NULL,
						 `discount` float(5,2) NOT NULL,
						 `vat` float(5,2) NOT NULL,
						 `stock` int(11) NOT NULL,
						 `picture` varchar(20) NOT NULL,
						 `picture2` varchar(20) NOT NULL,
						 `picture3` varchar(20) NOT NULL,
						 `picture4` varchar(20) NOT NULL,
						 `picture5` varchar(20) NOT NULL,
						 `picture6` varchar(20) NOT NULL,
						 `slider` varchar(1) NOT NULL,
						 `date` date NOT NULL,
						 `updatestatus` varchar(1) NOT NULL,
						 `salespersonid` varchar(10) NOT NULL,
						 `cus_review` varchar(50) NOT NULL default '0',
						 PRIMARY KEY  (`id`),
						 KEY `subcategoryid` (`subcategoryid`),
						 KEY `unitid` (`unitid`),
						 CONSTRAINT `product_ibfk_1` FOREIGN KEY (`subcategoryid`) REFERENCES `sub_category` (`id`),
						 CONSTRAINT `product_ibfk_2` FOREIGN KEY (`unitid`) REFERENCES `unit` (`id`)
						) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `comment` (
						 `id` int(11) NOT NULL auto_increment,
						 `description` varchar(100) NOT NULL,
						 `rating_point` int(11) NOT NULL,
						 `customerid` int(11) NOT NULL,
						 `productid` int(11) NOT NULL,
						 PRIMARY KEY  (`id`),
						 UNIQUE KEY `customerid` (`customerid`,`productid`),
						 KEY `productid` (`productid`),
						 CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`customerid`) REFERENCES `customer` (`id`),
						 CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`productid`) REFERENCES `product` (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1");
						
						
						
						
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `delivery` (
						 `id` int(11) NOT NULL auto_increment,
						 `name` varchar(100) NOT NULL,
						 `amount` float(6,2) NOT NULL,
						 PRIMARY KEY  (`id`),
						 UNIQUE KEY `name` (`name`)
						) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `ecom_sessions` (
						 `id` varchar(128) NOT NULL,
						 `ip_address` varchar(45) NOT NULL,
						 `timestamp` int(10) unsigned NOT NULL default '0',
						 `data` blob NOT NULL,
						 PRIMARY KEY  (`id`,`ip_address`),
						 KEY `ci_sessions_timestamp` (`timestamp`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `order` (
						 `id` int(11) NOT NULL auto_increment,
						 `orderid` varchar(11) NOT NULL,
						 `customer` varchar(100) NOT NULL,
						 `productid` int(11) NOT NULL,
						 `price` varchar(10) NOT NULL,
						 `imagename` varchar(100) NOT NULL,
						 `quantity` int(5) NOT NULL,
						 `dateoforder` date NOT NULL,
						 PRIMARY KEY  (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1");
						
						
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `sub_category` (
						 `id` int(11) NOT NULL auto_increment,
						 `name` varchar(50) NOT NULL,
						 `categoryid` int(11) NOT NULL,
						 PRIMARY KEY  (`id`),
						 UNIQUE KEY `name` (`name`,`categoryid`),
						 KEY `categoryid` (`categoryid`),
						 CONSTRAINT `sub_category_ibfk_1` FOREIGN KEY (`categoryid`) REFERENCES `category` (`id`)
						) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `t_admin` (
						 `id` int(11) NOT NULL auto_increment,
						 `username` varchar(100) NOT NULL,
						 `password` varchar(255) NOT NULL,
						 `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
						 PRIMARY KEY  (`id`)
						) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `t_ads` (
						 `id` int(11) NOT NULL auto_increment,
						 `catid` int(11) NOT NULL,
						 `subcatid` int(11) NOT NULL,
						 `pic` varchar(20) NOT NULL,
						 PRIMARY KEY  (`id`)
						) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `t_ads_under_menu` (
						 `id` int(11) NOT NULL auto_increment,
						 `catid` int(11) NOT NULL,
						 `subcatid` int(11) NOT NULL,
						 `pic` varchar(20) NOT NULL,
						 PRIMARY KEY  (`id`)
						) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `t_banner` (
						 `id` int(11) NOT NULL auto_increment,
						 `catid` int(11) NOT NULL,
						 `pic` varchar(500) NOT NULL,
						 PRIMARY KEY  (`id`)
						) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `t_companyprofile` (
						 `id` int(1) NOT NULL,
						 `profile` text NOT NULL,
						 PRIMARY KEY  (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `t_customer` (
						 `id` int(11) NOT NULL auto_increment,
						 `name` varchar(100) NOT NULL,
						 `email` varchar(100) NOT NULL,
						 `phone` varchar(20) NOT NULL,
						 `address` varchar(200) NOT NULL,
						 `country` varchar(100) NOT NULL,
						 `citylocation` varchar(20) NOT NULL,
						 `ordercencelstatus` int(4) NOT NULL default '0',
						 PRIMARY KEY  (`id`)
						) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `t_news` (
						 `id` int(11) NOT NULL auto_increment,
						 `title` varchar(200) NOT NULL,
						 `desc` text NOT NULL,
						 `publishdate` date NOT NULL,
						 `pic` varchar(10) NOT NULL,
						 PRIMARY KEY  (`id`)
						) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `t_orge` (
						 `id` int(11) NOT NULL auto_increment,
						 `orno` int(11) NOT NULL,
						 PRIMARY KEY  (`id`),
						 UNIQUE KEY `orno` (`orno`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `t_purchase` (
						 `id` int(11) NOT NULL auto_increment,
						 `orderid` varchar(15) NOT NULL,
						 `details` text NOT NULL,
						 `deliverycharge` float(7,2) NOT NULL,
						 `paymethod` varchar(30) NOT NULL,
						 `paymentstatus` varchar(10) NOT NULL,
						 `orderstatus` varchar(10) NOT NULL,
						 `customerid` int(11) NOT NULL,
						 `dateoforder` datetime NOT NULL,
						 `order_total_price` decimal(12,2) NOT NULL,
						 `receive_amt` decimal(12,2) NOT NULL,
						 `discount_on_order` varchar(5) NOT NULL,
						 `paiddate` datetime NOT NULL,
						 `salespersonid` varchar(10) NOT NULL,
						 `invoiceno` varchar(10) NOT NULL,
						 `comment` varchar(10) NOT NULL,
						 PRIMARY KEY  (`id`),
						 KEY `customerid` (`customerid`),
						 CONSTRAINT `t_purchase_ibfk_1` FOREIGN KEY (`customerid`) REFERENCES `t_customer` (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `t_purchase_details` (
						 `id` int(11) NOT NULL auto_increment,
						 `purchaseid` int(11) NOT NULL,
						 `productid` int(11) NOT NULL,
						 `qty` int(11) NOT NULL,
						 PRIMARY KEY  (`id`),
						 KEY `purchaseid` (`purchaseid`),
						 KEY `productid` (`productid`),
						 CONSTRAINT `t_purchase_details_ibfk_1` FOREIGN KEY (`purchaseid`) REFERENCES `t_purchase` (`id`),
						 CONSTRAINT `t_purchase_details_ibfk_2` FOREIGN KEY (`productid`) REFERENCES `product` (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `t_salesperson` (
						 `id` int(11) NOT NULL auto_increment,
						 `code` varchar(10) NOT NULL,
						 `username` varchar(10) NOT NULL,
						 `password` varchar(10) NOT NULL,
						 `name` varchar(100) NOT NULL,
						 `email` varchar(100) NOT NULL,
						 `phone` varchar(15) NOT NULL,
						 `address` varchar(200) NOT NULL,
						 `shift` varchar(1) NOT NULL,
						 `status` varchar(10) NOT NULL,
						 `category` varchar(20) NOT NULL,
						 `joindate` date NOT NULL,
						 `picture` varchar(10) NOT NULL,
						 PRIMARY KEY  (`id`)
						) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `t_settings` (
						 `id` int(11) NOT NULL auto_increment,
						 `name` varchar(100) NOT NULL,
						 `value` text NOT NULL,
						 `subject` varchar(50) NOT NULL,
						 PRIMARY KEY  (`id`)
						) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `t_user_online` (
						 `sid` char(100) NOT NULL,
						 `username` varchar(255) NOT NULL,
						 `userid` varchar(50) NOT NULL,
						 `time` int(11) NOT NULL,
						 `login_time` datetime NOT NULL,
						 `ip` varchar(255) NOT NULL,
						 `loged_in` varchar(6) NOT NULL
						) ENGINE=MyISAM DEFAULT CHARSET=latin1");
						
						$this->db->query("CREATE TABLE  IF NOT EXISTS `unit` (
						 `id` int(11) NOT NULL auto_increment,
						 `name` varchar(50) NOT NULL,
						 PRIMARY KEY  (`id`),
						 UNIQUE KEY `name` (`name`)
						) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1");
				
		
	
	//t_setting data//
		
					if(!empty($companyName)){$data = array();$data['name'] = 'Company Name';$data['value'] = trim($companyName);
						$this->db->insert('t_settings',$data);}	
					if(!empty($aboutCompanywork)){$data = array();$data['name'] = 'About Company work';$data['value'] = trim($aboutCompanywork);
						$this->db->insert('t_settings',$data);}	
					if(!empty($caddress)){$data = array();$data['name'] = 'Address';$data['value'] = trim($caddress);
						$this->db->insert('t_settings',$data);}	
					if(!empty($cphone)){$data = array();$data['name'] = 'phone';$data['value'] = trim($cphone);
						$this->db->insert('t_settings',$data);}	
					if(!empty($cemail)){$data = array();$data['name'] = 'servermail';$data['value'] = trim($cemail);
						$this->db->insert('t_settings',$data);}
					if(!empty($cemail)){$data = array();$data['name'] = 'Footer Copyright text';$data['value'] = trim($footerCopyrighttext);
					$this->db->insert('t_settings',$data);}
					
					$data = array();$data['name'] = 'Login Link';$this->db->insert('t_settings',$data);
					$data = array();$data['name'] = 'Register Link';$this->db->insert('t_settings',$data);
					$data = array();$data['name'] = 'E-mail us link';$this->db->insert('t_settings',$data);
					$data = array();$data['name'] = 'serverkey';$this->db->insert('t_settings',$data);
					
				$criPass=$this->mm->insert_rc4_pass($username,$password);
				$data=array(
				'id'=>1,
				'username '=>$username,
				'password'=>$criPass,
				'mobile'=>$mobile,
				'email'=>$email,
				'status'=>1,
				'date'=>$createdate
				);
				$this->db->insert('t_admin',$data);
							
				//unlink("application/controllers/_install.php");
				redirect('Admincontroller?sk=Admin panel setup completed, Please login','location');
			}
			else{redirect("Admincontroller?esk=Admin panel already exist, Don't try to install again",'location');}
		}
	}


	function randomCode() 
	{
		$alphabet = "0123456789";
		$Key = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) 
		{
			$n = rand(0, $alphaLength);
			$Key[] = $alphabet[$n];
		}
		return implode($Key); //turn the array into a string
	}

	function randomPassword() 
	{
		$alphabet = "abcdefghijklmnopqrstuwxyz123456789ABCDEFGHIJKLMNOPQRSTUWXYZ";
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 11; $i++) 
		{
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}

}
?>