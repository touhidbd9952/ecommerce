<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Dhaka");
		
	}
	public function index()
	{
		///////// visitor info /////////////////////
		$user_ip = $this->getUserIP();
		$todaydate = date('Y-m-d');
		$current_year = date('Y');
		$current_month = date('m');
		$visitorexist = $this->db->query("select * from t_visitorlist where t_date='".$todaydate."' and userip='".$user_ip."'")->result();
		if(count($visitorexist)==0)
		{
			$data=array();
			$data['t_date']=$todaydate;
			$data['userip']=$user_ip;
			$this->mm->insert_data('t_visitorlist',$data);
			$this->visitorcount();
		}
		/////////////////////////////
		
		$data = array();
		$data['menu'] = 'home';
		$data['per_page'] = 12;
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
		//$selectedcatid = $this->mm->getSet('home page category');
		$selectedcatid = -1;
		if($selectedcatid == "")
		{
			$selectedcatid = $this->db->query("Select id from category where indexnumber=1")->row()->id;
			$data['allproduct'] = $this->db->query("SELECT * FROM product where categoryid = '".$selectedcatid."' and picture !='' and stock !=0   ORDER BY id ASC  LIMIT $limit_start , $limit_end ")->result();
			$data['productlist'] = $this->db->query("SELECT count(*)as total  FROM product where categoryid = '".$selectedcatid."' and picture !='' and stock !=0   ")->row()->total;
		}
		else
		{
			$data['allproduct'] = $this->db->query("SELECT * FROM product where home = 1 and picture !='' and stock !=0   ORDER BY id ASC  LIMIT $limit_start , $limit_end ")->result();
			$data['productlist'] = $this->db->query("SELECT count(*)as total  FROM product where home = 1 and picture !='' and stock !=0  ")->row()->total;
		}
		$data['newarrivals'] = $this->db->query("SELECT * FROM product where  picture !=''  and stock !=0 ORDER BY id desc  LIMIT 4 ")->result();
		$data['allbestseller'] = $this->db->query("SELECT * FROM product where bestseller = '1' and picture !='' and stock !=0  ORDER BY id ASC  LIMIT $limit_start , $limit_end ")->result();
		
		$data['offerdata'] = $this->db->query("SELECT * FROM t_offer ")->result();
		$data['allofferproduct'] = $this->db->query("SELECT * FROM product where offerid !='' and picture !='' and stock !=0 group by id   LIMIT $limit_start , $limit_end  ")->result();
		$data['bestseller'] = $this->db->query("SELECT * FROM product where bestseller = '1' and picture !='' and stock !=0 and  offerid ='' and offerid !='0'   ORDER BY id ASC  LIMIT 4 ")->result();
		$data['content'] = $this->load->view('home',$data,true);
		$this->load->view('master',$data);
	}
	
	public function getUserIP()
	{
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];
	
		if(filter_var($client, FILTER_VALIDATE_IP))
		{
			$ip = $client;
		}
		elseif(filter_var($forward, FILTER_VALIDATE_IP))
		{
			$ip = $forward;
		}
		else
		{
			$ip = $remote;
		}
	
		return $ip;
	}
	public function visitorcount()
	{
		$todaydate = date('Y-m-d');
		$current_year = date('Y');
		$current_month = date('m');
		
		$visitor_info = $this->db->query("select * from t_visitor")->result();
			if(count($visitor_info)==1) //one recored, just need to update
			{
				foreach($visitor_info as $vi)
				{//one record
				
					$today_visitor = 0;
					$current_month_visitor = 0;
					
					$total_visitor = 0;
					if($todaydate == $vi->t_date)
					{
						$today_visitor = $vi->v_today + 1;
					}
					else
					{
						$today_visitor = 1;
					}
					if($current_month == $vi->c_month)
					{
						$current_month_visitor = $vi->v_month + 1;
					}
					else
					{
						$current_month_visitor = 1;
					}
					if($current_year != $vi->c_year)
					{
						$current_year = $vi->c_year;
					}
					$total_visitor=$vi->v_total + 1;
					$unique_visitor = $this->db->query("SELECT count(*)as uniquevisitor FROM ( SELECT DISTINCT userip FROM t_visitorlist)tbl")->row()->uniquevisitor;
						
					$data=array();
					$data['t_date']=$todaydate;
					$data['c_month']=$current_month;
					$data['c_year']=$current_year;
					
					$data['v_today']=$today_visitor;
					$data['v_month']=$current_month_visitor;
					if($unique_visitor != "")
					{
						$data['v_unique']=$unique_visitor;
					}
					$data['v_total']=$total_visitor;
					$this->mm->update_info('t_visitor',$data, array('id'=>1));
				} 
			}
			else
			{
				//echo $todaydate.','.$current_month.','.$current_year;
				$data=array();
				$data['t_date']=$todaydate;
				$data['c_month']=$current_month;
				$data['c_year']=$current_year;
				$this->mm->insert_data('t_visitor',$data);
				
			}
	}
	
	public function home()
	{
		$data = array();
		
		$data['menu'] = 'home';
		$data['per_page'] = 12;
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
		
		//$selectedcatid = $this->mm->getSet('home page category');
		$selectedcatid = -1;
		if($selectedcatid == "")
		{
			$selectedcatid = $this->db->query("Select id from category where indexnumber=1")->row()->id;
			$data['allproduct'] = $this->db->query("SELECT * FROM product where categoryid = '".$selectedcatid."' and picture !='' and stock !=0   ORDER BY id ASC  LIMIT $limit_start , $limit_end ")->result();
			$data['productlist'] = $this->db->query("SELECT count(*)as total  FROM product where categoryid = '".$selectedcatid."' and picture !='' and stock !=0   ")->row()->total;
		}
		else
		{
			$data['allproduct'] = $this->db->query("SELECT * FROM product where home = 1 and picture !='' and stock !=0   ORDER BY id ASC  LIMIT $limit_start , $limit_end ")->result();
			$data['productlist'] = $this->db->query("SELECT count(*)as total  FROM product where home = 1 and picture !='' and stock !=0   ")->row()->total;
		}
		$data['newarrivals'] = $this->db->query("SELECT * FROM product where  picture !=''  and stock !=0 ORDER BY id desc  LIMIT 4 ")->result();
		$data['allbestseller'] = $this->db->query("SELECT * FROM product where bestseller = '1' and picture !='' and stock !=0  ORDER BY id ASC  LIMIT $limit_start , $limit_end ")->result();
		
		$data['offerdata'] = $this->db->query("SELECT * FROM t_offer ")->result();
		$data['allofferproduct'] = $this->db->query("SELECT * FROM product where offerid !='' and picture !='' and stock !=0 group by id   LIMIT $limit_start , $limit_end  ")->result();
		$data['bestseller'] = $this->db->query("SELECT * FROM product where bestseller = '1' and picture !='' and stock !=0 and  offerid ='' and offerid !='0'  ORDER BY id ASC  LIMIT 4 ")->result();
		$data['content'] = $this->load->view('home',$data,true);
		$this->load->view('master',$data);
	}
	
	public function product($catid,$catname) //view single product
	{
		$data = array();
		$data['menu'] = 'product';
		$data['per_page'] = 12;
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
		$data['allproduct'] = $this->db->query("SELECT * FROM product where categoryid = '".$catid."' and picture !=''  and stock !=0 ORDER BY id ASC  LIMIT $limit_start , $limit_end ")->result();
		$data['productlist'] = $this->db->query("SELECT count(*)as total  FROM product where categoryid = '".$catid."' and picture !=''  and stock !=0 ")->row()->total;
		
		$data['offerdata'] = $this->db->query("SELECT * FROM t_offer ")->result();
		$data['selectedcatid'] = $catid;
		$data['shortcutmenu'] = 'Most Popular';
		$data['catname'] = $catname;
		$data['bestseller'] = $this->db->query("SELECT * FROM product where bestseller = '1' and picture !='' and  offerid ='' and offerid !='0'  ORDER BY id ASC  LIMIT 4 ")->result();
		$data['content'] = $this->load->view('productviewpage',$data,true);
		$this->load->view('master',$data);
	}
	public function products($catid,$catname) //view single product
	{
		$shortcutmenu = $this->uri->segment(5); 
		$orderby = "";
		if($shortcutmenu == ""){redirect('main/product/'.$catid.'/'.$catname);} 
		else
		{
			if(urldecode($shortcutmenu) == 'Most Popular'){$orderby = "id ASC";} 
			else if(urldecode($shortcutmenu) == 'New In'){$orderby = "date DESC";}
			else if(urldecode($shortcutmenu) == 'Lowest Price'){$orderby = "saleprice ASC";}
			else if(urldecode($shortcutmenu) == 'Highest Price'){$orderby = "saleprice DESC";}
			else if(urldecode($shortcutmenu) == 'Best Rating'){$orderby = "cus_review DESC";}
			else{redirect('main/product/'.$catid.'/'.$catname);}
		}
		$data = array();
		$data['menu'] = 'product';
		$data['per_page'] = 12;
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
		$data['allproduct'] = $this->db->query("SELECT * FROM product where categoryid = '".$catid."' and picture !=''  and stock !=0 ORDER BY ".$orderby."  LIMIT $limit_start , $limit_end ")->result();
		$data['productlist'] = $this->db->query("SELECT count(*)as total  FROM product where categoryid = '".$catid."' and picture !=''  and stock !=0 ")->row()->total;
		
		$data['offerdata'] = $this->db->query("SELECT * FROM t_offer ")->result();
		$data['selectedcatid'] = $catid;
		$data['shortcutmenu'] = urldecode($shortcutmenu);
		$data['catname'] = $catname;
		$data['bestseller'] = $this->db->query("SELECT * FROM product where bestseller = '1' and picture !='' and stock !=0 and  offerid ='' and offerid !='0'  ORDER BY id ASC  LIMIT 4 ")->result();
		
		$data['content'] = $this->load->view('productviewpage',$data,true);
		$this->load->view('master',$data);
	}
	public function bestseller()
	{
		$data = array();
		$data['catname'] = 'bestseller';
		$data['menu'] = 'bestseller';
		$data['per_page'] = 12;
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
		
		$data['allproduct'] = $this->db->query("SELECT * FROM product where bestseller = '1' and picture !='' and stock !=0  ORDER BY id ASC  LIMIT $limit_start , $limit_end ")->result();
		
		$data['productlist'] = $this->db->query("SELECT count(*)as total  FROM product where bestseller = '1' and picture !='' and stock !=0  ")->row()->total;
		
		$data['offerdata'] = $this->db->query("SELECT * FROM t_offer ")->result();
		
		$data['content'] = $this->load->view('bestseller_show',$data,true);
		$this->load->view('master',$data);
	}
	public function bestsellers()
	{
		$data = array();
		
		$data['menu'] = 'bestseller';
		$data['catname'] = 'bestseller';
		$data['per_page'] = 12;
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
		
		$data['allproduct'] = $this->db->query("SELECT * FROM product where bestseller = '1' and picture !='' and stock !=0  ORDER BY id ASC  LIMIT $limit_start , $limit_end ")->result();
		
		$data['productlist'] = $this->db->query("SELECT count(*)as total  FROM product where bestseller = '1' and picture !='' and stock !=0  ")->row()->total;
		
		
		$data['content'] = $this->load->view('bestseller_show',$data,true);
		$this->load->view('master',$data);
	}
	public function contact() //view single product
	{
		$data = array();
		$data['menu']='contact';
		$data['content'] = $this->load->view('contact',$data,true);
		$this->load->view('master',$data);
	}
	public function contactmail()
	{
		$name = trim($this->input->post('name'));
		$phone = trim($this->input->post('phone'));
		$from = trim($this->input->post('email'));
		$subject = trim($this->input->post('subject'));
		$message = trim($this->input->post('message'));
		$err=0;
		$msg="";
		if($name ==""){$msg .=++$err.'. Name Required';}
		if($phone ==""){$msg .=++$err.'. Phone Required';}
		if($from ==""){$msg .=++$err.'. Email Required';}
		else if(!filter_var($from, FILTER_VALIDATE_EMAIL)){$msg .=++$err.'. Email Invalid';}
		if($subject ==""){$msg .=++$err.'. Subject Required';}
		if($message ==""){$msg .=++$err.'. Message Required';}
		if($err ==0)
		{
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
			}
			$to = $this->mm->getSet("servermail");
			$message .= "\r\n\r\n\r\n\r\nBest Regards,\r\n\r\n".$name."\r\n".$from."\r\n".$phone;
			$sendresult="";
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
				$headers[] = 'To:'.$servermail;
				$headers[] = 'From: '.$name.' <'.$from.'>';
				
				$sendresult = mail($to,$subject,$message,implode("\r\n", $headers)); 
			}
			if($sendresult){
				redirect('main/contactmail?sk=Mail Sent');
			}
			else{
				redirect('main/contactmail?esk=Mail Sending Error !!!, Try later');
			}
		}
		else{
			redirect('main/contactmail?esk='.$msg);
		}
	}
	
	public function view($id) //view single product
	{
		$this->db->query("UPDATE product SET cus_review=cus_review +1 where  id='".$id."'");
		$data = array();
		$data['menu'] = 'view product';
		 
		$data['singleproduct'] = $this->db->query("SELECT *  FROM product where id='".$id."' ")->result();
		$data['offerdata'] = $this->db->query("SELECT * FROM t_offer ")->result(); 
		$data['content'] = $this->load->view('view_single_product',$data,true);
		$this->load->view('master',$data);
	}
	public function view_offer($id) //view single product
	{
		$this->db->query("UPDATE product SET cus_review=cus_review +1 where  id='".$id."'");
		$data = array();
		$data['menu'] = 'view product';
		$data['offerdata'] = $this->db->query("SELECT * FROM t_offer ")->result();
		$data['singleproduct'] = $this->db->query("SELECT *  FROM product where id='".$id."' ")->result();
		$data['content'] = $this->load->view('view_single_product_offer',$data,true);
		$this->load->view('master',$data);
	}
	public function checkout() //view single product
	{
		$data = array();
		$data['menu'] = 'checkout';
		$data['offerdata'] = $this->db->query("SELECT * FROM t_offer ")->result();
		$data['product'] = $this->db->query("select * from product ")->result();
		$data['content'] = $this->load->view('checkout',$data,true);
		$this->load->view('master',$data);
	}
	public function addtocart()
	{
		$pid = $this->input->post('pid'); 
		$productdata = $this->db->query("SELECT *  FROM product where id='".$pid."' ")->result();
		$pimg = $productdata[0]->picture;
		$pcolor = $this->input->post('selectedcolor');
		$psize = $this->input->post('selectedsize');
		$pqty = $this->input->post('numofpro');  
		
		$jsondata = $this->session->userdata('cartdata'); 
		if($jsondata !="")
		{
			$jsondata = json_decode($jsondata);
			$spid ="";$spcolor ="";$spsize ="";$spqty ="";
			$spid = $jsondata->pid; 
			$spimg = $jsondata->pimg;
			$spcolor = $jsondata->pcolor; 
			$spsize = $jsondata->psize; 
			$spqty = $jsondata->pqty;
		}
		 
		
		$data = array();
		if($spid == ""){$data['pid'] = $pid;}else{$data['pid'] = $spid.','.$pid;}
		if($spimg == ""){$data['pimg'] = $pimg;}else{$data['pimg'] = $spimg.','.$pimg;}
		if($spcolor == ""){$data['pcolor'] = $pcolor;}else{$data['pcolor'] = $spcolor.','.$pcolor;}
		if($spsize == ""){$data['psize'] = $psize;}else{$data['psize'] = $spsize.','.$psize;}
		if($spqty == ""){$data['pqty'] = $pqty;}else{$data['pqty'] = $spqty.','.$pqty;}
		$jsondata = json_encode($data);
		$this->session->set_userdata('cartdata',$jsondata);	
		redirect("main/view/".$pid);
		
	}
	public function addtocart_offer()
	{
		$pid = $this->input->post('pid'); 
		$productdata = $this->db->query("SELECT *  FROM product where id='".$pid."' ")->result();
		$pimg = $productdata[0]->picture;
		$pcolor = $this->input->post('selectedcolor');
		$psize = $this->input->post('selectedsize');
		$pqty = $this->input->post('numofpro');  
		
		$jsondata = $this->session->userdata('cartdata'); 
		if($jsondata !="")
		{
			$jsondata = json_decode($jsondata);
			$spid ="";$spcolor ="";$spsize ="";$spqty ="";
			$spid = $jsondata->pid; 
			$spimg = $jsondata->pimg;
			$spcolor = $jsondata->pcolor; 
			$spsize = $jsondata->psize; 
			$spqty = $jsondata->pqty;
		}
		 
		
		$data = array();
		if($spid == ""){$data['pid'] = $pid;}else{$data['pid'] = $spid.','.$pid;}
		if($spimg == ""){$data['pimg'] = $pimg;}else{$data['pimg'] = $spimg.','.$pimg;}
		if($spcolor == ""){$data['pcolor'] = $pcolor;}else{$data['pcolor'] = $spcolor.','.$pcolor;}
		if($spsize == ""){$data['psize'] = $psize;}else{$data['psize'] = $spsize.','.$psize;}
		if($spqty == ""){$data['pqty'] = $pqty;}else{$data['pqty'] = $spqty.','.$pqty;}
		$jsondata = json_encode($data);
		$this->session->set_userdata('cartdata',$jsondata);	
		redirect("main/view_offer/".$pid);
		
	}
	public function deletecartitem($arrid)
	{
		//$arrid = $this->input->post('arrid'); 
		
		$jsondata = $this->session->userdata('cartdata'); 
		$jsondata = json_decode($jsondata);
		$spid ="";$spcolor ="";$spsize ="";$spqty ="";
		$spid = $jsondata->pid;
		$spimg = $jsondata->pimg;
		$spcolor = $jsondata->pcolor; 
		$spsize = $jsondata->psize; 
		$spqty = $jsondata->pqty; 
		
		 if (strpos($spid, ',') !== false) 
		{
				$spid = explode(',',$spid);
				unset($spid[$arrid]);
				$spid = implode(',',$spid);
		}
		else{$spid="";}
		if (strpos($spimg, ',') !== false) 
		{
				$spimg = explode(',',$spimg);
				unset($spimg[$arrid]);
				$spimg = implode(',',$spimg);
		}
		else{$spimg="";}
		if (strpos($spcolor, ',') !== false) 
		{
				$spcolor = explode(',',$spcolor);
				unset($spcolor[$arrid]);
				$spcolor = implode(',',$spcolor);
		}	
		else{$spcolor="";}
		if (strpos($spsize, ',') !== false) 
		{
				$spsize = explode(',',$spsize);
				unset($spsize[$arrid]);
				$spsize = implode(',',$spsize);
		}
		else{$spsize="";}
		if (strpos($spqty, ',') !== false) 
		{
				$spqty = explode(',',$spqty);
				unset($spqty[$arrid]);
				$spqty = implode(',',$spqty);
		}
		else{$spqty="";}
		
		$data = array();
		$data['pid'] = $spid;
		$data['pimg'] = $spimg;
		$data['pcolor'] = $spcolor;
		$data['psize'] = $spsize;
		$data['pqty'] = $spqty;
		
		$jsondata = json_encode($data);
		$this->session->set_userdata('cartdata',$jsondata);
		redirect('main/checkout');
	}
	public function deletelastitem($permission)
	{
		if($permission == 'deletelastcartedproduct'){
			$this->session->unset_userdata('cartdata'); 
			redirect('main/checkout');
		}
		redirect('main/home');
	}
	public function cus_login()
	{ 
		$loginemail = trim($this->input->post('useremail'));
		$loginpass = trim($this->input->post('pass'));
		if($loginemail !="" && $loginpass !="")
		{
			$customerdata = $this->db->query("SELECT *  FROM t_customer where email ='".$loginemail."' and password = '".$loginpass."' ")->result(); 
			if(count($customerdata) >0)
			{
				$citylocation = $customerdata[0]->citylocation;
				$ses = array();
				$ses['cusid'] = $customerdata[0]->id;
				$ses['cusfirstname'] = $customerdata[0]->firstname;
				$ses['cuslastname'] = $customerdata[0]->lastname;
				$ses['password'] = $customerdata[0]->password;
				$ses['cusemail'] = $customerdata[0]->email;
				$ses['cusaddress'] = $customerdata[0]->address;
				$ses['citylocation']= $customerdata[0]->citylocation;
				$ses['city']= $customerdata[0]->city;
				$ses['country']= $customerdata[0]->country;
				$ses['phone']= $customerdata[0]->phone;
				
				if($citylocation == 'Local' || $citylocation == 'National' || $citylocation == 'International')
				{
					$deliverydata = $this->db->query("select amount,period from delivery where name = '".$citylocation."'")->result();
					$ses['deliverycharge']= $deliverydata[0]->amount;
					$ses['deliveryperiod']= $deliverydata[0]->period;
				}
				else
				{
					$deliverydata = $this->db->query("select * from t_pickuppoint where area='".$citylocation."' ")->result();
					if(count($deliverydata)>0)
					{
						$ses['deliverycharge']= $deliverydata[0]->amount;
						$ses['deliveryperiod']= $deliverydata[0]->period;
					}
				}
				$this->session->set_userdata($ses);
				
				redirect('main/checkout_delivery_method');
			}
			else{redirect('main/checkout_delivery_details?esk=wrong email or password');}
		}
		else{redirect('main/checkout_delivery_details?esk=Enter data properly');}
	}
	public function checkout_delivery_details()
	{
		$data = array();
		$data['menu'] = "checkout";
		$data['content'] = $this->load->view('checkout_delivery_details',$data,true);
		$this->load->view('master',$data);
	}
	public function checkout_delivery_info_save()
	{ 
		$loginpass = $this->input->post('loginpass');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$firstname = $this->input->post('firstname');
		$lastname = $this->input->post('lastname');
		$phone = $this->input->post('phone');
		$address = $this->input->post('address');
		$country = $this->input->post('country');
		$citylocation = $this->input->post('citylocation');
		$city = $this->input->post('city');
		$err = 0;
		$msg = "";
		if(empty($firstname)) { 
			$err++; $msg .= "First Name Required<br>"; 
		}
		if(empty($lastname)) { 
			$err++; $msg .= "Last Name Required<br>"; 
		}
		if(empty($email)){ 
			$err++; $msg .= "Email Required<br>";
		}
		else if(!empty($email))
		{
			if (filter_var($email, FILTER_VALIDATE_EMAIL) === false){
				$err++; $msg .= "Email Invalid<br>";
			}
			else
			{
				$selecteduserpass ="";
				$isemailexist = 0;
				$allemail = $this->db->query("select email,password from t_customer")->result();
				foreach($allemail as $ae)
				{
					if($ae->email == $email)
					{
						$selecteduserpass = $ae->password;
						if($selecteduserpass != $password){
						$isemailexist = 1;}
					}
				}
				if($isemailexist == 1){$err++; $msg .= "Email already exit<br>";}
			}
		}
		if(empty($phone)){
			$err++; $msg .= "Phone Required<br>";
		}
		if(empty($address)){
			$err++; $msg .= "Address Required<br>";
		}
		if(empty($country)){
			$err++; $msg .= "Country Required<br>";
		}
		if(empty($citylocation)){
			$err++; $msg .= "City location Required<br>";
		}
		
		if($err == 0)
		{
			$deliverydata = $this->db->query("select amount,period from delivery where name = '".$citylocation."'")->result();
			$ses = array();
			//$ses['orderid']= $orderid;
			$ses['cusemail']= $email;
			$ses['password']= $password;
			$ses['cusfirstname']= $firstname;
			$ses['cuslastname'] = $lastname;
			$ses['phone']= $phone;
			$ses['cusaddress']= $address;
			$ses['country']= $country;
			$ses['citylocation']= $citylocation;
			$ses['city']= $city;
			$ses['deliverycharge']= $deliverydata[0]->amount;
			$ses['deliveryperiod']= $deliverydata[0]->period;
			$this->session->set_userdata($ses); 
			redirect('main/checkout_delivery_method');
		}
		else{
			redirect('main/checkout_delivery_details?esk='.$msg);
		}
	}
	public function get_delivery_charge()
	{
		$id = $this->input->post('id');
		$deliverydata = $this->db->query("select * from t_pickuppoint where id='".$id."' ")->result();
		$amount = $deliverydata[0]->amount;
		$period = $deliverydata[0]->period;
		$pickupaddress = $deliverydata[0]->pickuppoint_address;
		$jdata = array();
		$jdata['amount'] = $amount;
		$jdata['period'] = $period;
		$jdata['pickupaddress'] = $pickupaddress;
		$jdata = json_encode($jdata);
		echo $jdata;
	}
	public function checkout_delivery_method()
	{
		$data = array();
		$data['menu'] = 'deliverymethod';
		$data['content'] = $this->load->view('checkout_delivery_method',$data,true);
		$this->load->view('master',$data);
	}
	public function checkout_delivery_method_save()
	{
		$email = $this->session->userdata('email');
		$password = $this->session->userdata('password');
		$firstname = $this->session->userdata('cusfirstname');
		$lastname = $this->session->userdata('cuslastname');
		$phone = $this->session->userdata('phone');
		$country = $this->session->userdata('country');
		
		$shipping_method = $this->input->post('shipping_method');
		$deliveryloc =0;
		if($shipping_method == 'homeaddress')
		{
			$deliveryloc = 0;
		}
			$address = $this->session->userdata('cusaddress'); ////
			$citylocation = $this->session->userdata('citylocation');
			$city = $this->session->userdata('city');
			$deliverycharge = $this->session->userdata('deliverycharge'); 
		
		$pickuppointaddress ="";
		$pickuppointarealocation = "";
		$pickuppointdeliverycharge = "";
		if($shipping_method == 'pickuppoint')
		{
			$deliveryloc = 1;
			$pickuppointaddress = $this->input->post('picupaddressloc');
			$pickuppointarealocation = $this->input->post('arealoc'); 
			if($pickuppointaddress =="")
			{
				redirect('main/checkout_delivery_method?esk=Pickup point address not found');
			}
			$pickuppointdeliverycharge = $this->input->post('pickuppointdeliverycharge'); 
		}
		$comment = $this->input->post('comment');
		$err=0;
		$msg="";
		if($shipping_method == 'homeaddress')
		{
			if($address ==""){$err++;$msg .="Delivery Address Missing<br>";}
			if($citylocation ==""){$err++;$msg .="Delivery City Missing<br>";}
			if($deliverycharge ==""){$err++;$msg .="Delivery Charge Missing<br>";}
		}
		if($shipping_method == 'pickuppoint')
		{
			if($pickuppointaddress ==""){$err++;$msg .="Pickup Point Address Missing<br>";}
			if($pickuppointarealocation ==""){$err++;$msg .="Pickup Point Location Missing<br>";}
			if($pickuppointdeliverycharge ==""){$err++;$msg .="Pickup Point Delivery Charge Missing<br>";}
		}
		
		if($err ==0)
		{
			$ses = array();
			$ses['email']= $email;
			$ses['password']= $password;
			$ses['cusfirstname']= $firstname;
			$ses['cuslastname']= $lastname;
			$ses['phone']= $phone;
			//normal delivery
			$ses['address']= $address;
			$ses['country']= $country;
			$ses['citylocation']= $citylocation;
			$ses['city']= $city;
			$ses['deliverycharge']= $deliverycharge;
			//pickuppoint
			$ses['deliveryloc']= $deliveryloc; //0 for normal delivery, 1 for pickup point delivery
			$ses['pickuppointaddress']= $pickuppointaddress;
			$ses['pickuppointarealocation']= $pickuppointarealocation;
			$ses['pickuppointdeliverycharge']= $pickuppointdeliverycharge;
			
			if(!empty($comment) && strlen($comment)<=200)
			{ $ses['deliverychargecomment']= $comment;}
			else{$ses['deliverychargecomment']= '';}
			$this->session->set_userdata($ses);
			redirect('main/checkout_payment_method');
		}
		else
		{
			redirect('main/checkout_delivery_method?esk='.$msg);
		}
		
	}
	public function checkout_payment_method()
	{
		$data = array();
		$data['menu'] = 'paymentmethod';
		$data['content'] = $this->load->view('checkout_payment_method',$data,true);
		$this->load->view('master',$data);
	}
	public function checkout_payment_method_save()
	{
		$paymethod = $this->input->post('paymethod');
		if($paymethod == 's' || $paymethod == 'h') //cash
		{
			if($paymethod == 's')
			{
				$paymethod = "Cash On Show Room Delivery";
			}
			if($paymethod == 'h')
			{
				$paymethod = "Cash On Home Delivery";
			}
			
			$paymentstatus = "Pending";
			
			//orderid = $this->session->userdata('orderid');
			$email = $this->session->userdata('email');
			$password = $this->session->userdata('password');
			$firstname = $this->session->userdata('cusfirstname');
			$lastname = $this->session->userdata('cuslastname');
			$phone = $this->session->userdata('phone');
			$address = $this->session->userdata('address');
			$country = $this->session->userdata('country');
			$citylocation = $this->session->userdata('citylocation');
			$city = $this->session->userdata('city');
			$deliverycharge = $this->session->userdata('deliverycharge');
			$deliverychargecomment = $this->session->userdata('deliverychargecomment');
			//pickuppoint
			$deliveryloc = $this->session->userdata('deliveryloc'); //0 for normal delivery, 1 for pickup point delivery
			$pickuppointaddress = $this->session->userdata('pickuppointaddress');
			$pickuppointarealocation = $this->session->userdata('pickuppointarealocation');
			$pickuppointdeliverycharge = $this->session->userdata('pickuppointdeliverycharge');
			
			$ses = array();
			//$ses['orderid']= $orderid;
			$ses['email']= $email;
			$ses['password']= $password;
			$ses['cusfirstname']= $firstname;
			$ses['cuslastname']= $lastname;
			$ses['phone']= $phone;
			
			//normal delivery
			$ses['address']= $address;
			$ses['country']= $country;
			$ses['citylocation']= $citylocation;
			$ses['city']= $city;
			$ses['deliverycharge']= $deliverycharge;
			//pickuppoint
			$ses['deliveryloc']= $deliveryloc; //0 for normal delivery, 1 for pickup point delivery
			$ses['pickuppointaddress']= $pickuppointaddress;
			$ses['pickuppointarealocation']= $pickuppointarealocation;
			$ses['pickuppointdeliverycharge']= $pickuppointdeliverycharge;
			
			$ses['deliverychargecomment']= $deliverychargecomment;
			$ses['paymethod']= $paymethod;
			$ses['paymentstatus']= $paymentstatus;
			
			$this->session->set_userdata($ses);
			redirect('main/checkout_confirm_order');
		}
		else if($paymethod == 'p') //paypal it will develop later
		{
			//redirect('');
		}
		else if($paymethod == 'c') // credit card it will develop later
		{
			//redirect('');
		}
	}
	public function check_couponcode()
	{
		$couponcode = trim($this->input->post('couponcode'));
		$err=0;
		$ems="";
		$isexist="false";
		$today = date('Y-m-d');
		$couponid ="";
		if($couponcode == "")
		{
			$ems .=++$err.". Promo Code Required<br>";
		}
		else
		{
			$coupondata = $this->db->query("select * from t_coupon where uses=0")->result();
			if(count($coupondata)>0)
			{
				foreach($coupondata as $cd)
				{
					if($couponcode == $cd->code)
					{
						if(($today >= $cd->issue) && ($today <= $cd->expire))
						{
							$isexist="true";
							$couponid = $cd->id;
						}
					}
				}
			}
			if($isexist == "false")
			{
				$ems .=++$err.". Promo Code Invalid or expire<br>";
			}
		}
		if($err == 0)
		{
			$cusemail = $this->session->userdata('cusemail');
			$user = $this->db->query("select * from t_customer where email='".$cusemail."'")->result();
			$cusid="";
			 if(count($user)==0)
			 {
				 $cus = array();
				 $cus['email'] = $this->session->userdata('cusemail');
				 $cus['password'] = $this->session->userdata('password');
				 $cus['firstname'] = $this->session->userdata('cusfirstname');
				 $cus['lastname'] = $this->session->userdata('cuslastname');
				 $cus['phone'] = $this->session->userdata('cusphone');
				 $cus['address'] = $this->session->userdata('address');
				 $cus['country'] = $this->session->userdata('country');
				 $cus['citylocation'] = $this->session->userdata('citylocation');
				 $cus['product_purchase'] = 0;
				 $cusid = $this->mm->Insert_data_getid('t_customer',$cus);
			 }
			 else
			 {
				 foreach($user as $cu)
				 {
					 $cusid = $cu->id;
				 }
			 }
			
			$data = array();
			$data['menu'] = 'confirm_order';
			$data['coupondata'] = $this->db->query("select * from t_coupon where uses=0 and id='".$couponid."' ")->result();
			$data['cusdata'] = $this->db->query("select * from t_customer where id='".$cusid."' ")->result();
			$data['couponid'] = $couponid;
			$data['couponcode'] = $couponcode;
			$data['content'] = $this->load->view('checkout_confirm_order',$data,true);
			$this->load->view('master',$data);
		}
		else
		{
			redirect("main/checkout_confirm_order?eck=".$ems);
		}
		
	}
	public function checkout_confirm_order()
	{
		$data = array();
		$data['menu'] = 'confirm_order';
		$data['coupondata'] = $this->db->query("select * from t_coupon where uses=0")->result();
		$data['content'] = $this->load->view('checkout_confirm_order',$data,true);
		$this->load->view('master',$data);
	}
	public function confirm_order()
	{
		$couponcode = $this->input->post("couponcode");
		$couponvalue = $this->input->post("couponvalue");
		
		$alldata = $this->session->userdata('sa'); 
		$mdata = json_decode($alldata); 
		$orderdate = date('Y-m-d h:i:s'); 
		
		/////////custimer table///////////
		$cusemail = $this->session->userdata('cusemail');	
		$cus = array();
		 $cus['email'] = $cusemail; 
		 $cus['password'] = $this->session->userdata('password'); 
		 $cus['firstname'] = $this->session->userdata('cusfirstname');
		 $cus['lastname'] = $this->session->userdata('cuslastname');
		 $cus['phone'] = $this->session->userdata('phone');
		 //normal delivery
		 $cus['address'] = $this->session->userdata('cusaddress'); 
		 $cus['country'] = $this->session->userdata('country'); 
		 $cus['citylocation'] = $this->session->userdata('citylocation');
		 $cus['city'] = $this->session->userdata('city');
		 //pickuppoint
		$deliveryloc= $this->session->userdata('deliveryloc'); //0 for normal delivery, 1 for pickup point delivery 
		
		 $customerid ="";
		 if($cusemail !="")
		 {
			 $user = $this->db->query("select * from t_customer where email='".$cusemail."'")->result();
			 if(count($user)>0)
			 {
				 $productpurchase =0;
				 foreach($user as $u)
				 {
					 $customerid = $u->id;
					 $productpurchase = $u->product_purchase;
				 }
				 $cus['product_purchase'] = $productpurchase + 1;
				 
				 $this->mm->update_info('t_customer',$cus,array('email'=>$cusemail)); //update customer info 
			 }
			 else
			 {
				 $cus['product_purchase'] = 0;
				 $customerid = $this->mm->Insert_data_getid('t_customer',$cus);
			 }
		 }
		 
		
		/////////purchase table ///////////
		$purchaseid = "";
		$orno="";
		if($customerid !="")
		{
			 $data = array();
			 $ps= array();
			 $js = array();
			 foreach($mdata as $m)
			 {
				 $dt = array();
				 $dt['productid'] = $m->productid;
				 $dt['price'] = $m->price;
				 $dt['qty'] = $m->qty;
				 $dt['color'] = $m->color;
				 $dt['size'] = $m->size;
				 $dt['discount'] = $m->discount;
				 $dt['vat'] = $m->vat;
				 $js[] =$dt;
			 }
			 
				$js = json_encode($js);
				 $orno = $this->db->query("select max(id)as orno from t_purchase")->row()->orno; 
				 $orno = $orno+1; 
				 $orno = 'or'.$orno; //echo $orno;exit;
				 $data['orderid'] = $orno;
				 $data['details'] = $js;
				  if($deliveryloc == 0)
				 {
				 	$data['deliveryloc'] = $this->session->userdata('cusaddress').','.$this->session->userdata('country'); //general location 
				 }
				 else
				 {
					 $data['deliveryloc'] = $this->session->userdata('pickuppointaddress').','.$this->session->userdata('pickuppointarealocation'); //pickuppoint location 
				 }
				 $data['deliverycharge'] = $m->deliverycharge;
				 $data['paymethod'] = $m->paymethod;
				 $data['paymentstatus'] = $m->paymentstatus;
				 $data['customerid'] = $customerid;
				 $data['dateoforder'] = $orderdate;
				 if($couponcode !="" && $couponvalue !="")
				 {
				 	$data['couponcode'] = $couponcode;
				 	$data['couponvalue'] = $couponvalue;
				 }
				
				 $purchaseid = $this->mm->Insert_data_getid('t_purchase',$data);
		}
		else{redirect('main/checkout_problem');}

		//////// purchase details table ///////////
		if($purchaseid !="")
		{
			//Coupon Update//
			if($couponvalue !="" && $couponvalue !=""){
				$this->db->query("update t_coupon set uses =1 where code='".$couponcode."' ");}
			////
			
			$pur = array();
			$pur['purchaseid'] = $purchaseid;
			$js = json_decode($js);
			foreach($js as $j)
			{
				$pur['productid'] = $j->productid;
				$pur['qty'] = $j->qty;
				$this->mm->insert_data('t_purchase_details',$pur);
			}
			////customer orderlist/////
			$cusorderlistdata = $this->db->query("select orderlist from t_customer where id='".$customerid."' ")->result();
			$cusorderlist="";
			if(count($cusorderlistdata)>0)
			{
				$cusorderlist= $cusorderlistdata[0]->orderlist;
				if($cusorderlist == ""){$cusorderlist = $orno;}else{$cusorderlist = $orno.','.$cusorderlist;}
				$this->db->query("update t_customer set orderlist='".$cusorderlist."' where id='".$customerid."' ");
			}
			
			///////notification///////////////////////////
			$notify = array();
			$notify['date'] = date('Y-m-d h:i:s');
			$notify['notification'] = 'New order: '.$orno.' is placed'; 
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
			$orderdata = $this->db->query("SELECT * FROM t_purchase WHERE orderid='".$orno."' ")->result();$customername =""; $cusemai="";$cusphone="";$cuscountry="";$deliverylocation="";$contacturl = base_url()."main/contact";$paymethod="";
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

$message .= '</a> </h1> </td> <td class="m_-8065932370111914007m_-3734552349517890535order-number__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;text-transform:uppercase;font-size:14px;color:#999" align="right"> <span class="m_-8065932370111914007m_-3734552349517890535order-number__text" style="font-size:16px"> Order #'.$orno.' </span> </td> </tr> </tbody></table> </td> </tr> </tbody></table> </center> </td> </tr></tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535content" style="width:100%;border-spacing:0;border-collapse:collapse"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535content__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding-bottom:40px"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <h2 style="font-weight:normal;font-size:24px;margin:0 0 10px">Thank you for your purchase! </h2> <p style="color:#777;line-height:150%;font-size:16px;margin:0"> Hi '.$customername.', we are getting your order ready to be shipped. We will notify you when it has been sent. </p> </td> </tr> </tbody></table> </center> </td> </tr></tbody></table> <table class="m_-8065932370111914007m_-3734552349517890535row m_-8065932370111914007m_-3734552349517890535section" style="width:100%;border-spacing:0;border-collapse:collapse;border-top-width:1px;border-top-color:#e5e5e5;border-top-style:solid"> <tbody><tr> <td class="m_-8065932370111914007m_-3734552349517890535section__cell" style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif;padding:40px 0"> <center> <table class="m_-8065932370111914007m_-3734552349517890535container" style="width:560px;text-align:left;border-spacing:0;border-collapse:collapse;margin:0 auto"> <tbody><tr> <td style="font-family:-apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,&quot;Roboto&quot;,&quot;Oxygen&quot;,&quot;Ubuntu&quot;,&quot;Cantarell&quot;,&quot;Fira Sans&quot;,&quot;Droid Sans&quot;,&quot;Helvetica Neue&quot;,sans-serif"> <h3 style="font-weight:normal;font-size:20px;margin:0 0 25px">Order summary</h3> </td> </tr> </tbody></table>'; 
					
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
				$subject = "Order #".$orno." confirmed";
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
			$ses = array();
			$ses['orderid'] = $orno;
			$this->session->set_userdata($ses);
			redirect('main/checkout_message');
		}
		else{redirect('main/checkout_problem');}
		
	}
	public function checkout_message()
	{
		$orderno = $this->session->userdata('orderid');
		/////cus mail start /////
		$maildata = $this->db->query("select * from t_message_template where name='Customer NewOrderNote' ")->result();
		$subject="";
		$message="";
		$orderurl = base_url().'general/order_details/'.$orderno;
		if(count($maildata)>0)
		{
			foreach($maildata as $md)
			{
				$subject = $this->mm->convert_mail_text2($md->subject);
				$message = $this->mm->convert_mail_text1($orderurl,$md->message);
			}
			$cusemail = $this->session->userdata('email');
			
			/////////////////////////////////////////
			$settingdata = $this->db->query("SELECT *  FROM t_settings ")->result();
			$storename="";
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

				$to=""; $to = $cusemail;
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
			
			//////////////////////////////////////////
		}
		/////cus mail end /////
		
		//distroy
		$this->session->unset_userdata('orderid'); //destroy previous user session
		$this->session->unset_userdata('sa'); 
		$this->session->unset_userdata('cartdata');
		
		redirect("main/checkoutmessage/".$orderno);
	}
	public function checkoutmessage($orderno)
	{
		$data = array();
		$data['orderno'] = $orderno;
		$this->load->view('checkout_message',$data);
	}
	public function checkout_problem()
	{
		$this->session->unset_userdata('orderid'); //destroy previous user session
		$data = array();
		$data['content'] = $this->load->view('checkout_problem',$data,true);
		$this->load->view('master',$data);
	}
	public function get_customerdata()
	{
		$useremail = trim($this->input->post('useremail'));
		$password = trim($this->input->post('pass'));
		$customerdata = $this->db->query("select * from t_customer where email='".$useremail."' and password='".$password."' ")->result(); 
		if(count($customerdata)>0)
		{
			$password = $customerdata[0]->password;
			$name = $customerdata[0]->name;
			$email = $customerdata[0]->email;
			$phone = $customerdata[0]->phone;
			$address = $customerdata[0]->address;
			$jdata = array();
			$jdata['email'] = $email;
			$jdata['password'] = $password;
			$jdata['name'] = $name;
			$jdata['phone'] = $phone;
			$jdata['address'] = $address;
			$jdata = json_encode($jdata);
			echo $jdata;
		}
	}
	public function aboutus()
	{
		$data = array();
		$data['menu'] = 'aboutus';
		$data['catname'] = 'About Us';
		$data['content'] = $this->load->view('aboutus',$data,true);
		$this->load->view('master',$data);
	}
	public function policy()
	{
		$data = array();
		$data['menu'] = 'policy';
		$data['content'] = $this->load->view('policy',$data,true); 
		$this->load->view('master',$data);
	}
	public function termsandcondition()
	{
		$data = array();
		$data['menu'] = 'termsandcondition';
		$data['content'] = $this->load->view('termsandcondition',$data,true); 
		$this->load->view('master',$data);
	}
	public function check_customer()
	{
		if($_POST)
		{
			$procode = $this->input->post('procode');
			$proid = $this->input->post('proid');
			$useremail = $this->input->post('useremail');
			$password = $this->input->post('password');
			$customerdata = $this->db->query("select * from t_customer where email = '".$useremail."' and password='".$password."' ")->result();
			if(count($customerdata)>0)
			{
				$cusid = $customerdata[0]->id;
				$todaydate = date("Y-m-d");
				$customerreview = $this->db->query("select * from t_customerreview where cusid = '".$cusid."' and code='".$procode."' and reviewdate='".$todaydate."' ")->result();
				if(count($customerreview)==0)
				{
					$this->session->set_userdata('cusid',$customerdata[0]->id);
					redirect("main/view/".$proid);
				}
				else
				{
					$this->session->unset_userdata('cusid');
					redirect("main/view/".$proid);
				}
			}
			else
			{
				$this->session->unset_userdata('cusid');
				redirect("main/view/".$proid);
			}
		}
	}
	public function add_comment()
	{
		if($_POST)
		{
			$procode = $this->input->post('procode');
			$proid = $this->input->post('proid');
			$cusid = $this->input->post('cusid');
			$ratingpoint = $this->input->post('ratingpoint');
			$comment = $this->input->post('comment');
			if($procode !="" && $cusid !="" && $comment !="")
			{
				$todaydate = date("Y-m-d");
				$customerreview = $this->db->query("select * from t_customerreview where cusid = '".$cusid."' and code='".$procode."' and reviewdate='".$todaydate."' ")->result();
				if(count($customerreview)==0)
				{
					$data = array();
					$data['code'] = $procode;
					$data['cusid'] = $cusid;
					$data['rating'] = $ratingpoint;
					$data['review'] = $comment;
					$data['reviewdate'] = $todaydate; //print_r($data);exit;
					if($this->mm->insert_data('t_customerreview',$data))
					{
						$this->session->unset_userdata('cusid');
						redirect("main/view/".$proid."?sk=Comment in process");
					}
					else
					{
						redirect("main/view/".$proid."?esk=Error!!!");
					}
				}
				else
				{
					$this->session->unset_userdata('cusid');
					redirect("main/view/".$proid);
				}
			}
			else
			{
				$this->session->unset_userdata('cusid');
				redirect("main/view/".$proid);
			}
		}
	}
	public function create_new_account($productid)
	{
		$data = array();
		$data['menu'] = 'new_account';
		$data['productid'] = $productid;
		$data['content'] = $this->load->view('create_new_customer_account',$data,true); 
		$this->load->view('master',$data);
	}
	public function add_new_customer()
	{
		if($_POST)
		{
			$productid = $this->input->post('productid');
			$username = trim($this->input->post('username'));
			$password = trim($this->input->post('password'));
			$name = trim($this->input->post('name'));
			$email = trim($this->input->post('email'));
			$phone = trim($this->input->post('phone'));
			$address = trim($this->input->post('address'));
			$city = trim($this->input->post('city'));
			$country = trim($this->input->post('country'));
			
			$err=0;
			$emsg="";
			if($username == "")
			{
				$emsg .= ++$err.". Username required";
			}
			else if(strlen($username)<6 || strlen($username)>12)
			{
				$emsg .= ++$err.". Username 6-12 char.";
			}
			if($password == "")
			{
				$emsg .= ++$err.". Password required";
			}
			else if(strlen($password)<6 || strlen($password)>12)
			{
				$emsg .= ++$err.". Password 6-12 char.";
			}
			if($name == "")
			{
				$emsg .= ++$err.". Name required";
			}
			else if(strlen($name)>100)
			{
				$emsg .= ++$err.". Name max length exceed.";
			}
			if($email == "")
			{
				$emsg .= ++$err.". Email required";
			}
			else if(strlen($email)>100)
			{
				$emsg .= ++$err.". Email max length exceed.";
			}
			if($phone == "")
			{
				$emsg .= ++$err.". Phone required";
			}
			else if(strlen($phone)>20)
			{
				$emsg .= ++$err.". Phone max length exceed.";
			}
			if($address == "")
			{
				$emsg .= ++$err.". Address required";
			}
			else if(strlen($address)>200)
			{
				$emsg .= ++$err.". Address max length exceed.";
			}
			if($city == "")
			{
				$emsg .= ++$err.". City required";
			}
			else if(strlen($city)>50)
			{
				$emsg .= ++$err.". City name max length exceed.";
			}
			if($country == "")
			{
				$emsg .= ++$err.". Country required";
			}
			if($err == 0)
			{
				$data = array();
				$data['username'] = $username;
				$data['password'] = $password;
				$data['name'] = $name;
				$data['email'] = $email;
				$data['phone'] = $phone;
				$data['address'] = $address;
				$data['citylocation'] = $city;
				$data['country'] = $country;
				if($this->mm->insert_data('t_customer',$data))
				{
					redirect("main/view/".$productid."?sk=Save successfully");
				}
				else
				{
					redirect("main/view/".$productid."?esk=Database too busy, try later");
				}
			}
			else
			{
				redirect("main/create_new_account?esk=".$emsg);
			}
		}
		else
		{
			redirect("main/home");
		}
	}
	public function OrderURLForCustomer()
	{
		
	}
	public function search_product()
	{
		///// start update search word //////
		$searchword = trim($this->input->post('searchword')); 
		if($searchword !="")
		{
			$predata = $this->db->query("Select value from t_settings where name ='search word' ")->result(); 
			if(count($predata)>0)
			{
				$proarray = array();
				$previoussearchdata = $predata[0]->value;
				if(strpos($previoussearchdata,','))
				{
					$proarray = explode(',',$previoussearchdata);
					if(!in_array($searchword,$proarray))
					{
						array_push($proarray,$searchword);
						$stringdata = implode(',',$proarray);
						//$this->mm->update_info('t_settings',$cus,array('email'=>$cusemail));
						$this->db->query("UPDATE t_settings SET value='".$stringdata."' where  name ='search word' ");
					}
				}
				else
				{
					if(!in_array($searchword,$proarray))
					{
						if($previoussearchdata !="")
						{
							$previoussearchdata = $previoussearchdata.','.$searchword;
						}
						else
						{
							$previoussearchdata = $searchword;
						}
						$this->db->query("UPDATE t_settings SET value='".$previoussearchdata."' where  name ='search word' ");
					}
				}
			}
			else
			{
				$this->db->query("UPDATE t_settings SET value='".$searchword."' where  name ='search word' ");
			}
		}
		///// end update search word //////
		$data = array();
		$data['menu'] = 'home';
		$data['per_page'] = 12;
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
		
		/////////////////
		$selectedcatid ="";
		$selecteddata="";
		
		$selecteddata = $this->db->query("Select id, categoryid from product where LOWER(title) like '%$searchword%' and stock !=0 ")->result(); 
		if(count($selecteddata)>0)
		{
			$selectedcatid = $selecteddata[0]->categoryid; 
			$selectedproductid = $selecteddata[0]->id; 
			
			$data['allproduct'] = $this->db->query("SELECT * FROM (SELECT * FROM product WHERE id='".$selectedproductid."'
union SELECT * FROM product where categoryid = '".$selectedcatid."' and picture !='' and id !='".$selectedproductid."'    LIMIT $limit_start , $limit_end)tbl ")->result();  

			$data['productlist'] = $this->db->query("SELECT count(*)as total FROM (SELECT * FROM product WHERE id='".$selectedproductid."'
union SELECT *  FROM product where categoryid = '".$selectedcatid."' and picture !='')as tbl2 ")->row()->total;
			
		}
		else
		{
			
			$keywords = explode(' ', strtolower($searchword)); 
			foreach($keywords as $words) 
			{
				$selecteddata = $this->db->query("Select id, categoryid from product where metatag like '%$words%' and stock !=0")->result(); 
				if(count($selecteddata)>0)
				{
					$selectedcatid = $selecteddata[0]->categoryid; 
					$selectedproductid = $selecteddata[0]->id; 
					
					$data['allproduct'] = $this->db->query("SELECT * FROM (SELECT * FROM product WHERE id='".$selectedproductid."'
	union SELECT * FROM product where categoryid = '".$selectedcatid."' and picture !='' and id !='".$selectedproductid."'   LIMIT $limit_start , $limit_end)tbl ")->result(); 
	 
					$data['productlist'] = $this->db->query("SELECT count(*)as total FROM (SELECT * FROM product WHERE id='".$selectedproductid."'
	union SELECT *  FROM product where categoryid = '".$selectedcatid."' and picture !='')as tbl2 ")->row()->total;
					
				}
				
			}
			
			
			if($selecteddata =="" || count($selecteddata)==0)
			{
				$selectedcatid = $this->db->query("Select id from category where indexnumber=1 LIMIT 1")->result();
				if(count($selectedcatid)>0)
				{
					$selectedcatid = $selectedcatid[0]->id;
					$data['allproduct'] = $this->db->query("SELECT * FROM product where categoryid = '".$selectedcatid."' and picture !='' and stock !=0  ORDER BY id ASC  LIMIT $limit_start , $limit_end ")->result();  
					$data['productlist'] = $this->db->query("SELECT count(*)as total  FROM product where categoryid = '".$selectedcatid."' and picture !='' and stock !=0  ")->row()->total;
				}
			} 
		}
		///////////////
		
		$data['offerdata'] = $this->db->query("SELECT * FROM t_offer ")->result();
		$data['bestseller'] = $this->db->query("SELECT * FROM product where bestseller = '1' and picture !='' and stock !=0 and  offerid ='' and offerid !='0'   ORDER BY id ASC  LIMIT 4 ")->result();
		$data['content'] = $this->load->view('home',$data,true);
		$this->load->view('master',$data);
	}
	public function forget_pass()
	{
		$data = array();
		$data['menu'] = 'forget_pass';
		$data['content'] = $this->load->view('forget_pass',$data,true); 
		$this->load->view('master',$data);
	}
	public function get_customer_pass()
	{
		$cusemail = trim($this->input->post('useremail'));
		
	}
	public function helpcenter()
	{
		$data = array();
		$data['menu'] = 'helpcenter';
		$data['catname'] = 'Help center';
		$data['content'] = $this->load->view('help_center',$data,true); 
		$this->load->view('master',$data);
	}
	public function ordertrack()
	{
		$cusid = trim($this->input->post('cusid'));
		$orderno = trim(strtolower($this->input->post('cusorder')));
		$cusemail = trim(strtolower($this->input->post('cusemail')));
		$customerdata = $this->db->query("select * from  t_customer where email ='".$cusemail."' ")->result();
		if(count($customerdata)>0 )
		{
			$orderlist = $customerdata[0]->orderlist;
			if(strpos($orderlist,','))
			{
				$orderlist = explode(',',$orderlist);
				if(in_array($orderno,$orderlist))
				{
					redirect('main/order_details/'.$orderno);
				}
				else{redirect('main/home');}
			}
			else if($orderlist == $orderno){redirect('main/order_details/'.$orderno);}
			else{redirect('main/home');}	
		}
		else{redirect('main/home');}
	}
	public function order_details($orderno)
	{
		$singleorderreq = $this->db->query("select * from  t_purchase where orderid = '".$orderno."' ")->result();
		if(count($singleorderreq)>0)
		{
			$data = array();
			$data['menu'] = 'order_details';
			$data['singleorderreq'] = $singleorderreq;
			$data['customer'] = $this->db->query("SELECT * FROM t_customer")->result();
			$data['orderno'] = $orderno;
			$data['content'] = $this->load->view('single_order_details_for_customer_view',$data,true);
			$this->load->view('master',$data);
		}
		else
		{
			$this->load->view('404error');
		}
	}
	public function customer_signup()
	{
		$data = array();
		$data['menu'] = 'customer_signup';
		$data['content'] = $this->load->view('customer_reg_user',$data,true);
		$this->load->view('master',$data);
	}
	public function customer_info_save()
	{ 
		if($_POST)
		{
			$username = trim($this->input->post('username'));
			$password = trim($this->input->post('password'));
			$firstname = trim($this->input->post('firstname'));
			$lastname = trim($this->input->post('lastname'));
			$email = trim($this->input->post('email'));
			$phone = trim($this->input->post('phone'));
			$address = trim($this->input->post('address'));
			$citylocation = $this->input->post('citylocation');
			$city = trim($this->input->post('city'));
			$country = trim($this->input->post('country'));
			$name="";
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
			if($firstname =="")
			{
				$msg .=++$err.". First name required<br>";
			}
			else if(strlen($firstname) >150 )
			{
				$msg .=++$err.". First name maximum length exceed<br>";
			}
			else{$name = $firstname;}
			if($lastname !="")
			{
				if(strlen($lastname) >150 )
				{
					$msg .=++$err.". Last name maximum length exceed<br>";
				}
				else{$name .= $lastname;}
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
				$data['firstname'] = $firstname;
				$data['lastname'] = $lastname;
				$data['email'] = $email;
				$data['phone'] = $phone;
				$data['address'] = $address;
				$data['country'] = $country;
				$data['citylocation'] = $citylocation;
				$data['city'] = $city;
				$insertedid = $this->mm->insert_data('t_customer',$data);
				if($insertedid !="")
				{
					$this->mm->SaveLogs('Saved',"New Customer '".$name."' Info Saved");
					
					///mail start////
					$settingdata = $this->db->query("SELECT *  FROM t_settings ")->result();
					$smtp_host = "";$smtp_port = "";$smtp_user = "";$smtp_pass = "";$mailsendvia =""; $servermail ="";$companyname ='';$siteurl ='';$sitephone='';$siteemail='';$brandorlogo="brand";
					foreach($settingdata as $d)
					{
						if($d->name == 'smtp_host'){$smtp_host = $d->value;}
						if($d->name == 'smtp_port'){$smtp_port = $d->value;}
						if($d->name == 'smtp_user'){$smtp_user = $d->value;}
						if($d->name == 'smtp_pass'){$smtp_pass = $d->value;}
						if($d->name == 'mailsendvia'){$mailsendvia = $d->value;}
						if($d->name == 'servermail'){$servermail = $d->value;}
						if($d->name == 'Company Name'){$companyname = $d->value;}
						if($d->name == 'logourl'){$siteurl = $d->value;}
						if($d->name == 'phone'){$sitephone = $d->value;}
						if($d->name == 'servermail'){$siteemail = $d->value;}
						if($d->name == 'brand or logo'){$brandorlogo = $d->value;}
					}
					$to = $email;
					$from = $servermail;
					$subject = 'Your new '.$companyname.' account!';
					$customername = $name;
					$siteimageurl=base_url().'img/logo.png';
					
$message = '<table class="m_4649490126487126744frc" width="600" cellspacing="0" cellpadding="0" border="0" align="center"><tbody><tr><td><br><span style="color:#333333;font-family:Arial,Helvetica,Sans-serif;text-decoration:none;font-size:14px;font-weight:bold;padding-bottom:30px">Dear '.$customername.';,</span><br><br>A <span class="il">'.$companyname.'</span> <span class="il">account</span> with your e-mail address has been created.<br>&nbsp;</td></tr><tr><td style="color:#333333;font-family:Arial,Helvetica,Sans-serif;text-decoration:none;font-size:14px" valign="middle" height="72" align="center"><a href="'.$siteurl.'" target="_blank">';
					
					
if($brandorlogo=='logo'){
$message .= '<img alt="Start Shopping Now" src="'.$siteimageurl.'" class="CToWUd" border="0">';
} else{ 
$message .= '<span style="color:#2777BB">'.$companyname.'</span>';}
					
$message .= '</a></td></tr><tr><td><br><span style="color:#333333;font-family:'.'Arial'.','.'Helvetica'.',Sans-serif;text-decoration:none;font-size:14px">For suggestion, feel free to contact our Customer Serivce. </span><ul><li><span style="color:#333333;font-family:'.'Arial,Helvetica,Sans-serif;text-decoration:none;font-size:14px">By Phone <strong span="" style="color:#3366ff">'.$sitephone.'</strong> </span></li><li><span style="color:#333333;font-family:'.'Arial'.','.'Helvetica'.',Sans-serif;text-decoration:none;font-size:14px">By Email <a href="'.$siteurl.'" style="text-decoration:none;color:#3366ff" target="_blank"><strong>'.$siteemail.'</strong></a></span></li></ul><br><span style="color:#333333;font-family:'.'Arial'.','.'Helvetica'.',Sans-serif;text-decoration:none;font-size:14px">We will be happy to guide you through your <span class="il">'.$companyname.'</span> experience.</span><br>&nbsp;</td></tr></tbody></table>';
					
					$sendresult="";
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
						$headers[] = 'To:'.$servermail;
						$headers[] = 'From: '.$companyname.' <'.$from.'>';
						
						$sendresult = mail($to,$subject,$message,implode("\r\n", $headers)); 
					}
					///mail start/////
					redirect("main/customer_signup?sk=Saved Successfully");
				}
				else
				{
					redirect("main/customer_signup?esk=Error!!!, Not Saved");
				}
				
			}
			else
			{
				redirect("main/customer_signup?esk=".$msg);
			}
		}
		else
		{
			redirect("main");
		}
	}
	public function customer_login()
	{
		$data = array();
		$data['menu'] = 'customer_signup';
		$data['content'] = $this->load->view('customer_reg_user',$data,true);
		$this->load->view('master',$data);
	}
	public function customer_panel()
	{
		$loginemail = trim($this->input->post('loginemail'));
		$loginpass = trim($this->input->post('loginpass'));
		if($loginemail !="" && $loginpass !="")
		{
			$customerdata = $this->db->query("SELECT *  FROM t_customer where email ='".$loginemail."' and password = '".$loginpass."' ")->result();
			if(count($customerdata >0))
			{
				$ses = array();
				$ses['cusid'] = $customerdata[0]->id;
				$ses['cusfirstname'] = $customerdata[0]->firstname;
				$ses['cuslastname'] = $customerdata[0]->lastname;
				$ses['cusemail'] = $customerdata[0]->email;
				$ses['cusphone'] = $customerdata[0]->phone;
				$this->session->set_userdata($ses);
				
				redirect('customercontroller/index');
			}
			else{redirect('main/home');}
		}
		else{redirect('main/home');}
	}
	
	
	public function offer($offer,$offertitle)
	{
		$data = array();
		
		$data['menu'] = 'offer';
		$data['offerid'] = $offer;
		$data['offertitle'] = $offertitle;
		$data['per_page'] = 12;
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
		
		$data['allproduct'] = $this->db->query("SELECT * FROM product where offerid = '".$offer."' and picture !='' and stock !=0 ORDER BY id ASC  LIMIT $limit_start , $limit_end ")->result();
		
		$data['productlist'] = $this->db->query("SELECT count(*)as total  FROM product where offerid = '".$offer."' and picture !='' and stock !=0 ")->row()->total;
		$data['offerdata'] = $this->db->query("SELECT * FROM t_offer ")->result();
		$data['content'] = $this->load->view('offer_page',$data,true);
		$this->load->view('master',$data);
	}
	public function adjustoffer()
	{
		$allofferinfo = $this->db->query("SELECT * FROM t_offer WHERE id IN (SELECT offerid FROM product WHERE offerid != '' OR offerid = '0' GROUP BY offerid)")->result();
		$curdate = date("Y-m-d");
		$offeridarray = array();
		foreach($allofferinfo as $d)
		{
			if($d->enddate < $curdate)
			{
				array_push($offeridarray,$d->id);
			}
		}
	}
	public function check_customer_for_add_wishlist()
	{
		if($_POST)
		{
			$procode = $this->input->post('procode');
			$proid = $this->input->post('proid');
			$useremail = $this->input->post('useremail');
			$password = $this->input->post('password');
			$customerdata = $this->db->query("select * from t_customer where email = '".$useremail."' and password='".$password."' ")->result();
			if(count($customerdata)>0)
			{
				$wishlist = $customerdata[0]->wish_list;
				if($wishlist !="")
				{
					if(strpos($wishlist, ','))
					{
						$wishlistarray = explode(',',$wishlist);
						if(!array_search($procode, $wishlistarray))
						{
							$wishlist = $wishlist.','.$procode;
						}
					}
					else if($wishlist != $procod)
					{
						$wishlist = $wishlist.','.$procode;
					}
				}else{$wishlist = $procode;}
				
				if($this->db->query("update t_customer set wish_list='".$wishlist."' where id='".$customerdata[0]->id."' "))
				{
					$this->session->set_userdata('cusid',$customerdata[0]->id);
					$this->session->set_userdata('cusemail',$customerdata[0]->email);
					redirect("main/view/".$proid);
				}
				else
				{
					redirect("main/view/".$proid);
				}
			}
			else
			{
				$this->session->unset_userdata('cusid');
				redirect("main/view/".$proid);
			}
		}
	}
	public function customer_add_wishlist($productid,$procode)
	{
		$cusid = $this->session->userdata('cusid');
		if($cusid !="")
		{
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
					if(!array_search($procode, $wishlistarray))
					{
						$wishlist = $wishlist.','.$procode;
					}
					else{redirect("main/view/".$productid);}
				}
				else if($wishlist != $procode)
				{
					$wishlist = $wishlist.','.$procode;
				}else{redirect("main/view/".$productid);}
				
			}
			else{$wishlist = $procode;}
			if($this->db->query("update t_customer set wish_list='".$wishlist."' where id='".$customerdata[0]->id."' "))
			{
				$this->session->set_userdata('cusid',$customerdata[0]->id);
				$this->session->set_userdata('cusemail',$customerdata[0]->email);
				redirect("main/view/".$productid);
			}
			else{redirect("main/view/".$productid);}
		}
		else{redirect("main/view/".$productid);}
		
	}
	public function show_wishlist() //view single product
	{
		$cusid = $this->session->userdata('cusid');
		
		$data = array();
		$data['menu'] = 'wishlist';
		$data['product'] = $this->db->query("select * from product ")->result();
		$data['customerdata'] = $this->db->query("SELECT *  FROM t_customer where id='".$cusid."' ")->result();
		$data['content'] = $this->load->view('show_wishlist',$data,true);
		$this->load->view('master',$data);
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
				redirect('main/show_wishlist');
			}
			else 
			{
				$this->db->query("update t_customer set wish_list='' where id='".$cusid."' ");
				redirect('main/show_wishlist');
			}
		}else{redirect('main/home');}
	}
	public function delete_all_wish_list()
	{
		$cusid = $this->session->userdata('cusid');
		$this->db->query("update t_customer set wish_list='' where id='".$cusid."' ");
		redirect('main/home');
	}
	public function news_subcriber()
	{
		$email = trim($this->input->post('email'));
		$err=0;
		$isemailexist=0;
		if($email == "")
		{
			$err++;
		}
		else
		{
			$allsubscriber = $this->db->query("SELECT * FROM t_newssubscriber")->result();
			if(count($allsubscriber)>0)
			{
				foreach($allsubscriber as $as)
				{
					if($as->email == $email)
					{
						$isemailexist =1;
					}
				}
			}
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
			  $err++;
			}	
		}
		if($err==0)
		{
			$data = array();
			$data['email'] = $email;
			$data['status'] = 1;
			if($isemailexist==0)
			{
				$this->mm->insert_data('t_newssubscriber',$data);
				redirect('main/customer_signup');
			}
			else
			{
				redirect('main/customer_signup');
			}
		}
		else
		{
			redirect('main/index');
		}
	}
	




}