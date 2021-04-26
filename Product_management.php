<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product_management extends CI_Controller {
	
	public function __construct() 
	{
        parent::__construct();
		date_default_timezone_set("Asia/Dhaka");
    }
    public function index() {
        $data = array();
		$data['menu'] = 'tab1';
        $data['Category'] = $this->mm->getinfo('category');
		$data['brand'] = $this->mm->getinfo('brand');
        $data['subCategory'] = $this->mm->getinfo('sub_category');
        $data['unit'] = $this->mm->getinfo('unit');
		$data['color'] = $this->mm->getinfo('color');
		$data['content'] = $this->load->view('product/product_form_entry', $data, true);
        $data['container'] = $this->load->view('product/product_page', $data, true);
        $this->load->view('masteradmin', $data);
    }

    public function insert() 
	{
		$permission = $this->session->userdata('permission');
		$acl = 34;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			//pic2 pic3 pic4 pic5 pic6
			$code = trim($this->input->post('code'));
			$modelno=trim($this->input->post('modelno'));
			$title = trim($this->input->post('nm'));
			$brandid = trim($this->input->post('brandid'));
			$description = trim($this->input->post('des'));
			$shortdes = trim($this->input->post('shortdes'));
			$metatag = strtolower(trim($this->input->post('metatag')));
			$metades = strtolower(trim($this->input->post('metades')));
			$buyprice = trim($this->input->post('buyprice'));
			$regularprice = trim($this->input->post('regularprice'));
			$saleprice = trim($this->input->post('saleprice'));
			$categoryid = trim($this->input->post('catid'));
			$subcategoryid = trim($this->input->post('scatid'));
			$unitid = trim($this->input->post('uid'));
			$colorid = trim($this->input->post('colorid'));
			$discount = trim($this->input->post('discount'));
			$vat = trim($this->input->post('vat'));
			$stock = trim($this->input->post('stock'));
			$showinhome = $this->input->post('showinhome'); 
			
			if($showinhome == '1'){$showinhome=1;}else{$showinhome=0;}
			//echo $showinhome;exit;
			
			$picname="";
			if(isset($_FILES['pic']))
			{
				$p = pathinfo($_FILES['pic']['name']);
				if(count($p)>2)
				{
					$picname = $code.'.png';
				}
			}
			$picname2=""; if(isset($_FILES['pic2'])){ $p2 = pathinfo($_FILES['pic2']['name']); if(count($p2)>2){$picname2 = $code.'p2.png'; }}
			$picname3=""; if(isset($_FILES['pic3'])){ $p3 = pathinfo($_FILES['pic3']['name']); if(count($p3)>2){$picname3 = $code.'p3.png'; }}
			$picname4=""; if(isset($_FILES['pic4'])){ $p4 = pathinfo($_FILES['pic4']['name']); if(count($p4)>2){$picname4 = $code.'p4.png'; }}
			$picname5=""; if(isset($_FILES['pic5'])){ $p5 = pathinfo($_FILES['pic5']['name']); if(count($p5)>2){$picname5 = $code.'p5.png'; }}
			$picname6=""; if(isset($_FILES['pic6'])){ $p6 = pathinfo($_FILES['pic6']['name']); if(count($p6)>2){$picname6 = $code.'p6.png'; }}
			
			$data = array(
				"code" => $code,
				"modelno" => $modelno,
				"title" => $title,
				"brandid" => $brandid,
				"description" => $description,
				"shortdes" => $shortdes,
				"metatag" => $metatag,
				"metades" => $metades,
				"buyprice" => $buyprice,
				"regularprice" => $regularprice,
				"saleprice" => $saleprice,
				"categoryid" => $categoryid,
				"subcategoryid" => $subcategoryid,
				"unitid" => $unitid,
				"colorid" => $colorid,
				"discount" => $discount,
				"vat" => $vat,
				"stock" => $stock,
				"picture" => $picname,
				"date"=>date('Y-m-d'),
				"home"=> $showinhome
			);
			if($picname2 !=""){ $data['picture2'] = $picname2;}
			if($picname3 !=""){ $data['picture3'] = $picname3;}
			if($picname4 !=""){ $data['picture4'] = $picname4;}
			if($picname5 !=""){ $data['picture5'] = $picname5;}
			if($picname6 !=""){ $data['picture6'] = $picname6;}
			
			$insertId = $this->mm->InsertWithImage('product', $data);
			if ($insertId != "") { //InsertWithImage($table,$data)
				if(!empty($picname))
				{
					$imgfilename = $picname;
					$this->mm->image_upload('./img/product/' , '15000000', '5000', '3000', $imgfilename ,'400','300','pic');
				}
				if(!empty($picname2))
				{
					$imgfilename = $picname2;
					$this->mm->image_upload('./img/product/' , '15000000', '5000', '3000', $imgfilename ,'400','300','pic2');
				}
				if(!empty($picname3))
				{
					$imgfilename = $picname3;
					$this->mm->image_upload('./img/product/' , '15000000', '5000', '3000', $imgfilename ,'400','300','pic3');
				}
				if(!empty($picname4))
				{
					$imgfilename = $picname4;
					$this->mm->image_upload('./img/product/' , '15000000', '5000', '3000', $imgfilename ,'400','300','pic4');
				}
				if(!empty($picname5))
				{
					$imgfilename = $picname5;
					$this->mm->image_upload('./img/product/' , '15000000', '5000', '3000', $imgfilename ,'400','300','pic5');
				}
				if(!empty($picname6))
				{
					$imgfilename = $picname6;
					$this->mm->image_upload('./img/product/' , '15000000', '5000', '3000', $imgfilename ,'400','300','pic6');
				}
				$msg = "Saved Successfully";
				redirect(base_url() . "product_management/index?sk=".$msg);
			} 
			else 
			{
				$emsg = "Database too busy";
				redirect(base_url() . "product_management/index?esk=".$emsg);
			}
		}
		else
		{
			redirect(base_url() . "product_management/index?esk=No Access");
		}
        
    }

    public function view_info() 
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
		$data['menu'] = 'tab2';
		$data['Category'] = $this->mm->getinfo('category');
		$data['brand'] = $this->mm->getinfo('brand');
        $data['subCategory'] = $this->mm->getinfo('sub_category');
        $data['unit'] = $this->mm->getinfo('unit');
		$data['color'] = $this->mm->getinfo('color');
        $data['model'] = $this->db->query("SELECT * FROM product order by code LIMIT $limit_start , $limit_end ")->result();
		$data['productlist'] = $this->db->query("SELECT * FROM product order by code")->result();
		$data['content'] = $this->load->view('product/view_product_info', $data, true);
        $data['container'] = $this->load->view('product/product_page', $data, true);
        //$data['container'] = $this->load->view('product/product_page', $data, true);
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
		else if($searchoption == 'home')
		{
        	$data['model'] = $this->db->query("SELECT * FROM product where home=1 LIMIT $limit_start , $limit_end ")->result();
			$data['productlist'] = $this->db->query("SELECT * FROM product where home=1 ")->result();
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
		$data['menu'] = 'tab2';
		$data['content'] = $this->load->view('product/view_product_info', $data, true);
        $data['container'] = $this->load->view('product/product_page', $data, true);
        $this->load->view('masteradmin', $data);
	}

    public function edit_data() {
        $id = array("id" => $this->uri->segment(3));

        $data = array();
		$data['menu'] = 'tab2';
      	$data['Category'] = $this->mm->getinfo('category');
		$data['brand'] = $this->mm->getinfo('brand');
        $data['subCategory'] = $this->mm->getinfo('sub_category');
        $data['unit'] = $this->mm->getinfo('unit');
		$data['color'] = $this->mm->getinfo('color');
        $data['model'] = $this->mm->get_all_info_by_id('product', $id);
		$data['content'] = $this->load->view('product/product_form_edit', $data, true);
        $data['container'] = $this->load->view('product/product_page', $data, true);
        $this->load->view('masteradmin', $data);
    }

    public function update_product() 
	{
		$permission = $this->session->userdata('permission');
		$acl = 35;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
		
			$id = array("id" => $this->input->post('id'));
			$tid = $this->input->post('id');
			$code = trim($this->input->post('code'));
			$modelno=trim($this->input->post('modelno'));
			$picname = "";
			if(isset($_FILES['pic']))
			{
				$p = pathinfo($_FILES['pic']['name']);
				if(count($p)>2)
				{
					$picname = $code.'.png';
				}
			}
			$picname2=""; if(isset($_FILES['pic2'])){ $p2 = pathinfo($_FILES['pic2']['name']); if(count($p2)>2){$picname2 = $code.'p2.png'; }}
			$picname3=""; if(isset($_FILES['pic3'])){ $p3 = pathinfo($_FILES['pic3']['name']); if(count($p3)>2){$picname3 = $code.'p3.png'; }}
			$picname4=""; if(isset($_FILES['pic4'])){ $p4 = pathinfo($_FILES['pic4']['name']); if(count($p4)>2){$picname4 = $code.'p4.png'; }}
			$picname5=""; if(isset($_FILES['pic5'])){ $p5 = pathinfo($_FILES['pic5']['name']); if(count($p5)>2){$picname5 = $code.'p5.png'; }}
			$picname6=""; if(isset($_FILES['pic6'])){ $p6 = pathinfo($_FILES['pic6']['name']); if(count($p6)>2){$picname6 = $code.'p6.png'; }}	
			
			$title = trim($this->input->post('nm'));
			$brandid = trim($this->input->post('brandid'));
			$description = trim($this->input->post('des'));
			$shortdes = trim($this->input->post('shortdes'));
			$metatag = strtolower(trim($this->input->post('metatag')));
			$metades = strtolower(trim($this->input->post('metades')));
			$buyprice = trim($this->input->post('buyprice'));
			$regularprice = trim($this->input->post('regularprice'));
			$saleprice = trim($this->input->post('saleprice'));
			$categoryid = trim($this->input->post('catid'));
			$subcategoryid = trim($this->input->post('scatid'));
			$unitid = trim($this->input->post('uid'));
			$colorid = trim($this->input->post('colorid'));
			$discount = trim($this->input->post('discount'));
			$vat = trim($this->input->post('vat'));
			$stock = trim($this->input->post('stock'));
			$showinhome = $this->input->post('showinhome');
			if($showinhome == '1'){$showinhome=1;}else{$showinhome=0;}
			
			$data = array(); 
			$data["code"] = $code;
			$data["modelno"] = $modelno;
			$data["title"] = $title;
			$data["brandid"] = $brandid;
			$data["description"] = $description;
			$data["shortdes"] = $shortdes;
			$data["metatag"] = $metatag;
			$data["metades"] = $metades;
			$data["buyprice"] = $buyprice;
			$data["regularprice"] = $regularprice;
			$data["saleprice"] = $saleprice;
			$data["categoryid"] = $categoryid;
			$data["subcategoryid"] = $subcategoryid;
			$data["unitid"] = $unitid;
			$data["colorid"] = $colorid;
			$data["discount"] = $discount;
			$data["vat"] = $vat;
			$data["stock"] = $stock;
			if(!empty($picname)){
			$data["picture"] = $picname;}
			$data["date"]=date('Y-m-d');
			$data["updatestatus"] = 'u';
			$data["salespersonid"] = $this->session->userdata('username');
			$data["home"]= $showinhome;
			
			
			if($picname2 !=""){ $data['picture2'] = $picname2;}
			if($picname3 !=""){ $data['picture3'] = $picname3;}
			if($picname4 !=""){ $data['picture4'] = $picname4;}
			if($picname5 !=""){ $data['picture5'] = $picname5;}
			if($picname6 !=""){ $data['picture6'] = $picname6;}
			
			if ($this->mm->update_info('product', $data,$id)) { //InsertWithImage($table,$data)
				if($picname!="")
				{
					$imgfilename = $picname;
					$oldfile = $picname;
					$this->mm->image_upload2('./img/product/' , '15000000', '5000', '3000', $imgfilename ,'400','300','pic',$oldfile);
				}
				if(!empty($picname2))
				{
					$imgfilename = $picname2;
					$oldfile = $picname2;
					$this->mm->image_upload2('./img/product/' , '15000000', '5000', '3000', $imgfilename ,'400','300','pic2',$oldfile);
				}
				if(!empty($picname3))
				{
					$imgfilename = $picname3;
					$oldfile = $picname3;
					$this->mm->image_upload2('./img/product/' , '15000000', '5000', '3000', $imgfilename ,'400','300','pic3',$oldfile);
				}
				if(!empty($picname4))
				{
					$imgfilename = $picname4;
					$oldfile = $picname4;
					$this->mm->image_upload2('./img/product/' , '15000000', '5000', '3000', $imgfilename ,'400','300','pic4',$oldfile);
				}
				if(!empty($picname5))
				{
					$imgfilename = $picname5;
					$oldfile = $picname5;
					$this->mm->image_upload2('./img/product/' , '15000000', '5000', '3000', $imgfilename ,'400','300','pic5',$oldfile);
				}
				if(!empty($picname6))
				{
					$imgfilename = $picname6;
					$oldfile = $picname6;
					$this->mm->image_upload2('./img/product/' , '15000000', '5000', '3000', $imgfilename ,'400','300','pic6',$oldfile);
				}
				$msg = "Updated Successfully";
				redirect(base_url() . "product_management/view_info?sk=".$msg, "refresh");
			} 
			else 
			{
				$emsg = "Database too busy";
				redirect(base_url() . "product_management/view_info?esk=".$emsg , "refresh");
			}
		}
		else
		{
			redirect(base_url() . "product_management/view_info?esk=No Access");
		}
    }

    public function delete_data($tid, $picture) 
	{
		$permission = $this->session->userdata('permission');
		$acl = 36;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$id = array("id" => $tid);
			$picturename = $picture;
			//is customer exist in purchase table
			$isexist = "";
			$isexist = $this->db->query("Select * from t_purchase_details where productid='".$tid."'")->result(); 
			
			if(!empty($isexist)) //is exit record in purchase table don't delete customer record
			{
				redirect(base_url() . "product_management/view_info?esk=This product has record, Can't delete", "refresh");
			}
			else
			{
	
				if ($this->mm->delete_info('product', $id)) 
				{
					if(!empty($picturename))
					{
						$this->mm->delete_image('img/product/'.$picturename);
					}
					
					$msg = "Deleted Successfully";
					redirect(base_url() . "product_management/view_info?sk=".$msg, "refresh");
				} 
				else 
				{
					$emsg = "Database too busy";
					redirect(base_url() . "product_management/view_info?esk=".$emsg, "refresh");
				}
			}
		}
		else
		{
			redirect(base_url() . "product_management/view_info?esk=No Access");
		}

    }
	public function get_maxcode()
	{
		header('Content-Type: application/json');
		$subcategory = $this->input->post('subcatid'); 
		//$code = $subcategory * 1000;
		$code = $subcategory;
		if(!empty($code))
		{
			$maxcode = $this->db->query("SELECT max(code) as code FROM `product` WHERE code like '".$code."%'")->row()->code; 
			if(empty($maxcode))
			{
				$code = ($subcategory * 10000)+1;
				echo $code;exit;
			}
			else
			{
				$code = $maxcode+1;
				echo $code;exit;
			}
		}
		
		
	}
    

   

}
