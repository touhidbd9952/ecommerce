<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Offer_management extends CI_Controller {

    public function __construct()
	 {
        parent::__construct();
		date_default_timezone_set('Asia/Dhaka');
    }

    public function index() {
        $data = array();
		$data['menu'] = 'tab1';
        $data['content'] = $this->load->view('offer/offer_form_entry', $data, true);
        $data['container'] = $this->load->view('offer/offer_page', $data, true);
        $this->load->view('masteradmin', $data);
    }
    public function view_info() {
        $data = array();
		$data['menu'] = 'tab2';
        $data['offers'] = $this->db->query("select * from  t_offer")->result();
        $data['content'] = $this->load->view('offer/view_offer_info', $data, true);
        $data['container'] = $this->load->view('offer/offer_page', $data, true);
        $this->load->view('masteradmin', $data);
    }

    public function insert() 
	{
		$permission = $this->session->userdata('permission');
		$acl = 44;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$offertitle = $this->input->post('offertitle');
			$sdate = $this->input->post('sdate');
			$edate = $this->input->post('edate');
			
			$p="";
			$p = pathinfo($_FILES['pic']['name']);
			$err=0;
			$msg="";
			if($offertitle ==""){$msg +=++$err."Title Required<br>";}
			if($sdate ==""){$msg +=++$err."Select Start Date<br>";}
			if($edate ==""){$msg +=++$err."Select End Date<br>";}
			if(count($p)<3){$msg +=++$err."Upload Banner<br>";}
			if($err==0)
			{
				$picdata ="";
				$pnum = 0;
				$picname = "";
				$data = array();
				
				//insert
				$data["title"] = $offertitle;
				$data["startdate"] = $sdate;
				$data["enddate"] = $edate;
				
				$insertedid = $this->mm->Insert_data_getid('t_offer', $data);
				if ($insertedid !="") 
				{
					if(count($p)>2)
					{
						$imgfilename = "offer".$insertedid.".png";
						$this->mm->image_upload('./img/offer/' , '15000000', '5000', '3000', $imgfilename ,'350','250','pic');
						$this->db->query("update t_offer set  pic='".$imgfilename."' where id='".$insertedid."' ");
					}
					redirect("offer_management/index?sk=Saved Successfully");
				} 
				else 
				{
					redirect("offer_management/index?esk=Database too busy");
				}
				
			}
			else 
			{
				redirect("offer_management/index?esk=".$msg);
			}
		}
		else
		{
			redirect(base_url() . "offer_management/index?esk=No Access");
		}
        
    }

    public function edit_data() 
	{
        $id = $this->uri->segment(3);
        $data = array();
		$data['menu'] = 'tab2';
		$data['offers'] = $this->db->query("select * from  t_offer where id='".$id."' ")->result();
        $data['content'] = $this->load->view('offer/offer_form_edit', $data, true);
        $data['container'] = $this->load->view('offer/offer_page', $data, true);
        $this->load->view('masteradmin', $data);
    }

    public function update_offer() 
	{
		$permission = $this->session->userdata('permission');
		$acl = 45;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$id = $this->input->post('id');
			$offertitle = $this->input->post('offertitle');
			$sdate = $this->input->post('sdate');
			$edate = $this->input->post('edate'); 
			$indexno = $this->input->post('indexno');
			
			$p = pathinfo($_FILES['pic']['name']);
	
				$data = array();
				$data["title"] = $offertitle;
				$data["startdate"] = $sdate;
				$data["enddate"] = $edate;
				$data["indexno"] = $indexno;
				if(count($p)>2)
				{
					$data["pic"] = "offer".$id.".png";
				}
			
			if ($this->mm->update_info('t_offer', $data, array('id'=>$id))) 
			{
				//print_r($data);exit;
				if(count($p)>2)
				{
					$imgfilename = "offer".$id.".png";
					$oldfile = "offer".$id.".png";
					$this->mm->image_upload2('./img/offer/' , '15000000', '5000', '3000', $imgfilename ,'350','250','pic',$oldfile);
				}
				redirect(base_url() . "offer_management/view_info?sk=Update Successfully", "refresh");
			} 
			else 
			{
				redirect(base_url() . "offer_management/view_info?esk=Database too busy","refresh" );
			}
		}
		else
		{
			redirect(base_url() . "offer_management/view_info?esk=No Access");
		}
    }

    public function delete_data($id,$pic) 
	{
		$permission = $this->session->userdata('permission');
		$acl = 46;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			if ($this->mm->delete_info('t_offer', array('id'=>$id))) 
			{
				unlink('img/offer/'.$pic);
				$msg = "Deleted Successfully";
				redirect(base_url() . "offer_management/view_info?sk=".$msg, "refresh");
			} else {
				$emsg = "Database too busy";
				redirect(base_url() . "offer_management/view_info?esk=".$emsg, "refresh");
			}
		}
		else
		{
			redirect(base_url() . "offer_management/view_info?esk=No Access");
		}
    }
	public function category_wise_add_offer() 
	{
        $data = array();
		$data['offers'] = $this->db->query("select * from  t_offer ")->result();
        $data['category'] = $this->db->query("select * from  category ")->result();
        $data['container'] = $this->load->view('category_wise_add_offer', $data, true);
        $this->load->view('masteradmin', $data);
    }
	public function offer_add_by_category()
	{
		$permission = $this->session->userdata('permission');
		$acl = 47;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$categoryid = $this->input->post('categoryid');
			$offerid = $this->input->post('offerid');
			$discount = trim($this->input->post('offerdiscount'));  //echo $categoryid.",".$offerid.", ".$discount;exit;
			$err=0;
			$msg="";
			if($categoryid == "" || $categoryid == 0){$err++;$msg .="Category required<br>";}
			if($offerid == "" || $offerid == 0){$err++;$msg .="Offer required<br>";}
			if($offerid !="-1")
			{
				if($discount == ""){$err++;$msg .="Offer discount required<br>";}
				else if(is_nan($discount)){$err++;$msg .="Offer discount invalid<br>";}
			}
			if($err==0)
			{
				$data=array();
				if($offerid =="-1")
				{
					$data['offerid'] = "";
					$data['offerdiscount'] = "";
				}
				else
				{
					$data['offerid'] = $offerid;
					$data['offerdiscount'] = $discount; //print_r($data);exit;
				}
				if($this->mm->update_info('product', $data, array('categoryid'=>$categoryid)))
				{
					$msg="";
					if($offerid !="-1"){$msg="Offer Updated Successfully";}else{$msg="Offer Removed Successfully";}
					redirect("offer_management/category_wise_add_offer?sk=".$msg, "refresh");
				}
				else 
				{
					redirect("offer_management/category_wise_add_offer?esk=Database too busy, Can't add", "refresh");
				}
			}
			else 
			{
				redirect("offer_management/category_wise_add_offer?esk=".$msg, "refresh");
			}
		}
		else
		{
			redirect(base_url() . "offer_management/category_wise_add_offer?esk=No Access");
		}
	}
	public function individualy_wise_add_offer() 
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
        $data['container'] = $this->load->view('individualy_add_offer', $data, true);
        $this->load->view('masteradmin', $data);
    }
	public function searchdata()
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
		
        $data['container'] = $this->load->view('individualy_add_offer', $data, true);
        //$data['container'] = $this->load->view('product/product_page', $data, true);
        $this->load->view('masteradmin', $data);
	}
	public function add_in_offer() {
        $id = array("id" => $this->uri->segment(3));

        $data = array();
      	$data['Category'] = $this->mm->getinfo('category');
		$data['brand'] = $this->mm->getinfo('brand');
        $data['subCategory'] = $this->mm->getinfo('sub_category');
        $data['unit'] = $this->mm->getinfo('unit');
		$data['color'] = $this->mm->getinfo('color');
        $data['model'] = $this->mm->get_all_info_by_id('product', $id);
        $data['container'] = $this->load->view('offer_add_individualy', $data, true);
        //$data['container'] = $this->load->view('product/product_page', $data, true);
        $this->load->view('masteradmin', $data);
    }
	public function update_product_offer()
	{
		$permission = $this->session->userdata('permission');
		$acl = 50;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$id = trim($this->input->post("id"));
			$offerid = trim($this->input->post("offerid"));
			$discount = trim($this->input->post("offerdiscount")); 
			$err=0;
			$msg="";
			if($offerid =="" || $offerid ==0){$msg .=++$err.". Select Offer<br>";}
			if($discount ==""){$msg .=++$err.". Discount Required<br>";}
			if(is_nan($discount)){$msg .=++$err.". Discount Invalid<br>";}
			if($err==0)
			{
				$data = array();
				if($offerid == '-1')
				{
					$data['offerid']= "";
					$data['offerdiscount']= "";
				}
				else
				{
					$data['offerid']= $offerid;
					$data['offerdiscount']= $discount;
				}
				
				if($this->mm->update_info('product', $data, array('id'=>$id)))
				{
					$msg="";
					if($offerid !="-1"){$msg="Offer Updated Successfully";}else{$msg="Offer Removed Successfully";}
					redirect("offer_management/individualy_wise_add_offer?sk=".$msg, "refresh");
				}
				else 
				{
					redirect("offer_management/individualy_wise_add_offer?esk=Database too busy, Can't add", "refresh");
				}
			}
			else 
			{
				redirect("offer_management/individualy_wise_add_offer?esk=".$msg, "refresh");
			}
		}
		else
		{
			redirect(base_url() . "offer_management/individualy_wise_add_offer?esk=No Access");
		}
		
	}
	public function remove_all_offer($decision)
	{
		$permission = $this->session->userdata('permission');
		$acl = 53;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			if($decision == 'yes'){
			if($this->db->query('update product set offerid="",offerdiscount="" '))
			{
				$msg="";
				$msg="Offer Removed Successfully";
				redirect("offer_management/individualy_wise_add_offer?sk=".$msg, "refresh");
			}
			else 
			{
				redirect("offer_management/individualy_wise_add_offer?esk=Database too busy, Can't remove offer", "refresh");
			}
			}
		}
		else
		{
			redirect(base_url() . "offer_management/individualy_wise_add_offer?esk=No Access");
		}
	}

}
