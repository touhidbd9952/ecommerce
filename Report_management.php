<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_management extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
 		date_default_timezone_set("Asia/Dhaka");
    }
	public function index()
	{
		$data = array();
		$data['menu'] = 'report';
		$data['container'] = $this->load->view('report_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function show_report()
	{
		$requirement = $this->input->post('requireddata');
		$msg="";
		if($requirement == "")
		{
			$msg ='Select report category';
			redirect('report_management/index?sk='.$msg);
		}
		//Product Stock below 5
		if($requirement == "Product Stock below 5")
		{
			$today = date('Y-m-d');
			$report = $this->db->query("select * from product where stock <5")->result();
			if(!count($report)>0)
			{
				$msg ='Not Found';
				redirect('report_management/index?sk='.$msg);
			}
			else
			{
				$data = array();
				$data['menu'] = 'report';
				$data['Category'] = $this->mm->getinfo('category');
				$data['brand'] = $this->mm->getinfo('brand');
				$data['subCategory'] = $this->mm->getinfo('sub_category');
				$data['unit'] = $this->mm->getinfo('unit');
				$data['color'] = $this->mm->getinfo('color');
				$data['report']=$report;
				$data['content']= $this->load->view('report_page1',$data,true);
				$data['container'] = $this->load->view('report_page',$data,true);
				$this->load->view('masteradmin',$data);
			}
			
		}
		//All Product
		if($requirement == "All Product")
		{
			$today = date('Y-m-d');
			$report = $this->db->query("select * from product")->result();
			if(!count($report)>0)
			{
				$msg ='Not Found';
				redirect('report_management/index?sk='.$msg);
			}
			else
			{
				$data = array();
				$data['menu'] = 'report';
				$data['Category'] = $this->mm->getinfo('category');
				$data['brand'] = $this->mm->getinfo('brand');
				$data['subCategory'] = $this->mm->getinfo('sub_category');
				$data['unit'] = $this->mm->getinfo('unit');
				$data['color'] = $this->mm->getinfo('color');
				$data['report']=$report;
				$data['content']= $this->load->view('report_page1',$data,true);
				$data['container'] = $this->load->view('report_page',$data,true);
				$this->load->view('masteradmin',$data);
			}
			
		}
		if($requirement == "Product Category Subcategory")
		{
		
			
			$report = $this->mm->view_data_two_table('sub_category','category','sub_category.id, sub_category.name, category.name as cname','sub_category.categoryid = category.id');
			if(!count($report)>0)
			{
				$msg ='Not Found';
				redirect('report_management/index?sk='.$msg);
			}
			else
			{
				$data = array();
				$data['report']=$report;
				$data['content'] = $this->load->view('show_report_result2',$data,true);
				$data['container'] = $this->load->view('report_page',$data,true);
				$this->load->view('masteradmin',$data);
			}
		
		}
		//Today Entered product
		if($requirement == "Today Entered product")
		{
			$today = date('Y-m-d');
			$report = $this->db->query("select * from product where date='".$today."' and updatestatus=''")->result();
			if(!count($report)>0)
			{
				$msg ='Not Found';
				redirect('report_management/index?sk='.$msg);
			}
			else
			{
				$data = array();
				$data['menu'] = 'report';
				$data['Category'] = $this->mm->getinfo('category');
				$data['brand'] = $this->mm->getinfo('brand');
				$data['subCategory'] = $this->mm->getinfo('sub_category');
				$data['unit'] = $this->mm->getinfo('unit');
				$data['color'] = $this->mm->getinfo('color');
				$data['report']=$report;
				$data['content']= $this->load->view('report_page1',$data,true);
				$data['container'] = $this->load->view('report_page',$data,true);
				$this->load->view('masteradmin',$data);
			}
			
		}
		
		//Today Updated product
		if($requirement == "Today Updated product")
		{
			$today = date('Y-m-d');
			$report = $this->db->query("select * from product where date='".$today."' and updatestatus='u'")->result();
			if(!count($report)>0)
			{
				$msg ='Not Found';
				redirect('report_management/index?sk='.$msg);
			}
			else
			{
				$data = array();
				$data['menu'] = 'report';
				$data['Category'] = $this->mm->getinfo('category');
				$data['brand'] = $this->mm->getinfo('brand');
				$data['subCategory'] = $this->mm->getinfo('sub_category');
				$data['unit'] = $this->mm->getinfo('unit');
				$data['color'] = $this->mm->getinfo('color');
				$data['report']=$report;
				$data['content']= $this->load->view('report_page1',$data,true);
				$data['container'] = $this->load->view('report_page',$data,true);
				$this->load->view('masteradmin',$data);
			}
			
		}
		//Last 7 Days Entered product
		if($requirement == "Last 7 Days Entered product")
		{
			$report = $this->db->query("SELECT * FROM product WHERE date >= DATE(NOW()) - INTERVAL 7 DAY and updatestatus='' ")->result();
			if(!count($report)>0)
			{
				$msg ='Not Found';
				redirect('report_management/index?sk='.$msg);
			}
			else
			{
				$data = array();
				$data['menu'] = 'report';
				$data['Category'] = $this->mm->getinfo('category');
				$data['brand'] = $this->mm->getinfo('brand');
				$data['subCategory'] = $this->mm->getinfo('sub_category');
				$data['unit'] = $this->mm->getinfo('unit');
				$data['color'] = $this->mm->getinfo('color');
				$data['report']=$report;
				$data['content']= $this->load->view('report_page1',$data,true);
				$data['container'] = $this->load->view('report_page',$data,true);
				$this->load->view('masteradmin',$data);
			}
			
		}
		//Last 7 Days Updated product
		if($requirement == "Last 7 Days Updated product")
		{
			$report = $this->db->query("SELECT * FROM product WHERE date >= DATE(NOW()) - INTERVAL 7 DAY and updatestatus='u' ")->result();
			if(!count($report)>0)
			{
				$msg ='Not Found';
				redirect('report_management/index?sk='.$msg);
			}
			else
			{
				$data = array();
				$data['menu'] = 'report';
				$data['Category'] = $this->mm->getinfo('category');
				$data['brand'] = $this->mm->getinfo('brand');
				$data['subCategory'] = $this->mm->getinfo('sub_category');
				$data['unit'] = $this->mm->getinfo('unit');
				$data['color'] = $this->mm->getinfo('color');
				$data['report']=$report;
				$data['content']= $this->load->view('report_page1',$data,true);
				$data['container'] = $this->load->view('report_page',$data,true);
				$this->load->view('masteradmin',$data);
			}
			
		}
		//Confirm Order
		if($requirement == "Confirm Order")
		{
			$report = $this->db->query("SELECT * FROM t_purchase WHERE orderstatus = 'Conform' ")->result();
			if(!count($report)>0)
			{
				$msg ='Not Found';
				redirect('report_management/index?sk='.$msg);
			}
			else
			{
				$data = array();
				$data['menu'] = 'report';
				$data['subject']='All Confirm Order';
				$data['report']=$report;
				$data['content']= $this->load->view('report_page2',$data,true);
				$data['container'] = $this->load->view('report_page',$data,true);
				$this->load->view('masteradmin',$data);
			}
			
		}
		//Complete Order
		if($requirement == "Complete Order")
		{
			$report = $this->db->query("SELECT * FROM t_purchase WHERE orderstatus = 'Complete' ")->result();
			if(!count($report)>0)
			{
				$msg ='Not Found';
				redirect('report_management/index?sk='.$msg);
			}
			else
			{
				$data = array();
				$data['menu'] = 'report';
				$data['report']=$report;
				$data['subject']='All Complete Order';
				$data['content']= $this->load->view('report_page2',$data,true);
				$data['container'] = $this->load->view('report_page',$data,true);
				$this->load->view('masteradmin',$data);
			}
			
		}
		//Cencel Order
		if($requirement == "Cencel Order")
		{
			$report = $this->db->query("SELECT * FROM t_purchase WHERE orderstatus = 'Cencel' ")->result();
			if(!count($report)>0)
			{
				$msg ='Not Found';
				redirect('report_management/index?sk='.$msg);
			}
			else
			{
				$data = array();
				$data['menu'] = 'report';
				$data['report']=$report;
				$data['subject']='All Cencel Order';
				$data['content']= $this->load->view('report_page2',$data,true);
				$data['container'] = $this->load->view('report_page',$data,true);
				$this->load->view('masteradmin',$data);
			}
			
		}
		
	}
	public function show_report_result($orderno)
	{
		$data = array();
		$data['menu'] = 'orderreqreport';
		$data['singleorderreq'] = $this->db->query("select * from  t_purchase where orderid = '".$orderno."'")->result();
		$data['orderno'] = $orderno;
		$data['container'] = $this->load->view('show_report_result',$data,true);
		$this->load->view('masteradmin',$data);
	}
	public function show_subcategory_wise_data($subcategory)
	{
		$subcategory = str_replace('%20',' ',$subcategory);
		$subcategoryid = $this->db->query("select id from sub_category where name ='".$subcategory."'")->row()->id; 
		$report = $this->db->query("select * from product where subcategoryid ='".$subcategoryid."'")->result();
			if(!count($report)>0)
			{
				$msg ='Not Found';
				redirect('report_management/index?sk='.$msg);
			}
			else
			{
				$data = array();
				$data['menu'] = 'report';
				$data['Category'] = $this->mm->getinfo('category');
				$data['brand'] = $this->mm->getinfo('brand');
				$data['subCategory'] = $this->mm->getinfo('sub_category');
				$data['unit'] = $this->mm->getinfo('unit');
				$data['color'] = $this->mm->getinfo('color');
				$data['report']=$report;
				$data['content']= $this->load->view('report_page1',$data,true);
				$data['container'] = $this->load->view('report_page',$data,true);
				$this->load->view('masteradmin',$data);
			}
	}
	public function show_category_wise_data($category)
	{
		$category = str_replace('%20',' ',$category);
		$categoryid = $this->db->query("select id from category where name ='".$category."'")->row()->id; 
		$report = $this->db->query("select * from product where categoryid= '".$categoryid."'")->result(); 
			if(!count($report)>0)
			{
				$msg ='Not Found';
				redirect('report_management/index?sk='.$msg);
			}
			else
			{
				$data = array();
				$data['menu'] = 'report';
				$data['Category'] = $this->mm->getinfo('category');
				$data['brand'] = $this->mm->getinfo('brand');
				$data['subCategory'] = $this->mm->getinfo('sub_category');
				$data['unit'] = $this->mm->getinfo('unit');
				$data['color'] = $this->mm->getinfo('color');
				$data['report']=$report;
				$data['content']= $this->load->view('report_page1',$data,true);
				$data['container'] = $this->load->view('report_page',$data,true);
				$this->load->view('masteradmin',$data);
			}
	}
	
	
	
	
	
	
	
}
