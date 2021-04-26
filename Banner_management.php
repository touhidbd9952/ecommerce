<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Banner_management extends CI_Controller {

    public function __construct()
	 {
        parent::__construct();

    }

    public function index() {
        $data = array();
		$data['menu'] = 'tab1';
		$data['Category'] = $this->mm->getinfo('category');
        $data['content'] = $this->load->view('banner/banner_form_entry', $data, true);
        $data['container'] = $this->load->view('banner/banner_page', $data, true);
        $this->load->view('masteradmin', $data);
    }

    public function view_info() {
        $data = array();
		$data['menu'] = 'tab2';
		$data['per_page'] = 7;
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
        $data['banners'] = $this->db->query("select * from t_banner order by catid LIMIT $limit_start , $limit_end")->result();
		$data['bannerslist'] = $this->db->query("select * from t_banner order by catid")->result();
		$data['Category'] = $this->mm->getinfo('category');
        $data['content'] = $this->load->view('banner/view_banner_info', $data, true);
        $data['container'] = $this->load->view('banner/banner_page', $data, true);
        $this->load->view('masteradmin', $data);
    }

    public function insert() 
	{
		$permission = $this->session->userdata('permission');
		$acl = 37;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$catid ="";
			$catid = $this->input->post('catid');
			$p="";
			$p = pathinfo($_FILES['pic']['name']);
			$err=0;
			$msg="";
			if($catid ==""){$msg +=++$err."Select Category<br>";}
			if(count($p)<3){$msg +=++$err."Upload Banner<br>";}
			if($err==0)
			{
				$bannerimages = $this->db->query("select * from t_banner where catid='".$catid."' ")->result();
				$picdata ="";
				$pnum = 0;
				$picname = "";
				if(count($bannerimages)>0)
				{
					$pnum = count($bannerimages);
					$pnum = $pnum +1;
				}
				else
				{
					$pnum = $pnum +1;
				}
				if(count($p)>2)
				{
					$picname = 'banner'.$catid.'-'.$pnum.'.png';
				}
				
				$data = array();
				$data["catid"] = $catid;
				if(count($p)>2)
				{
					if($picdata !=""){
					$data["pic"] = $picname;}
					else{$data["pic"] = $picname;}
				}
				
				if ($this->mm->insert_data('t_banner', $data)) 
				{
					if(count($p)>2)
					{
						$imgfilename = $picname;
						$this->mm->image_upload('./img/banner/' , '15000000', '5000', '3000', $imgfilename ,'1600','650','pic');
					}
					redirect("banner_management/index?sk=Saved Successfully");
				} 
				else 
				{
					redirect("banner_management/index?esk=Database too busy");
				}
			}
			else 
			{
				redirect("banner_management/index?esk=".$msg);
			}
		}
		else
		{
			redirect(base_url() . "banner_management/index?esk=No Access");
		}
        
    }

    public function edit_data() {
        $id = $this->uri->segment(3);
		$pic = $this->uri->segment(4);

        $data = array();
		$data['menu'] = 'tab2';
        $data['bannerdata'] = $this->mm->get_all_info_by_id('t_banner', array("id"=>$id,"pic"=>$pic));
		$data['Category'] = $this->mm->getinfo('category');
        $data['content'] = $this->load->view('banner/banner_form_edit', $data, true);
        $data['container'] = $this->load->view('banner/banner_page', $data, true);
        $this->load->view('masteradmin', $data);
    }

    public function update_banner() 
	{
		$permission = $this->session->userdata('permission');
		$acl = 38;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
			$id = $this->input->post('id');
			$picname = $this->input->post('picname');
			$catid = $this->input->post('catid');
			
			$p = pathinfo($_FILES['pic']['name']);
	
				$data = array();
				$data["catid"] = $catid;
				if(count($p)>2)
				{
					$data["pic"] = $picname;
				}
			
			if ($this->mm->update_info('t_banner', $data, array('id'=>$id))) 
			{
				if(count($p)>2)
				{
					$imgfilename = $picname;
					$oldfile = $picname;
					$this->mm->image_upload2('./img/banner/' , '15000000', '5000', '3000', $imgfilename ,'1600','650','pic',$oldfile);
				}
				redirect(base_url() . "banner_management/view_info?sk=Update Successfully", "refresh");
			} 
			else 
			{
				redirect(base_url() . "banner_management/view_info?esk=Database too busy","refresh" );
			}
		}
		else
		{
			redirect(base_url() . "banner_management/view_info?esk=No Access");
		}
    }

    public function delete_data($id,$pic) 
	{
		$permission = $this->session->userdata('permission');
		$acl = 39;
		$access = 'no';
		$access = $this->mm->checkpermission($acl,$permission);
		if($access == 'yes')
		{
		
			if ($this->mm->delete_info('t_banner', array('id'=>$id))) 
			{
				unlink('img/banner/'.$pic);
				$msg = "Deleted Successfully";
				redirect(base_url() . "banner_management/view_info?sk=".$msg, "refresh");
			} else {
				$emsg = "Database too busy";
				redirect(base_url() . "banner_management/view_info?esk=".$emsg, "refresh");
			}
		}
		else
		{
			redirect(base_url() . "banner_management/view_info?esk=No Access");
		}
    }
	
	public function searchdata()
	{
		$searchcatid = trim($this->input->post("searchcatid"));
		
		$data = array();
		
		$data['menu'] = 'tab2';
		$data['per_page'] = 7;
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
		
		if($searchcatid == 'all')
		{
        	$data['banners'] = $this->db->query("select * from t_banner order by catid LIMIT $limit_start , $limit_end")->result();
			$data['bannerslist'] = $this->db->query("select * from t_banner order by catid")->result();
		}
		else if($searchcatid == 'home')
		{
        	$data['banners'] = $this->db->query("select * from t_banner where catid='-1'  LIMIT $limit_start , $limit_end")->result();
			$data['bannerslist'] = $this->db->query("select * from t_banner where catid='-1'")->result();
		}
		else
		{
        	$data['banners'] = $this->db->query("select * from t_banner where catid='".$searchcatid."'  LIMIT $limit_start , $limit_end")->result();
			$data['bannerslist'] = $this->db->query("select * from t_banner where catid='".$searchcatid."'")->result();
		}

		$data['Category'] = $this->mm->getinfo('category');
        $data['content'] = $this->load->view('banner/view_banner_info', $data, true);
        $data['container'] = $this->load->view('banner/banner_page', $data, true);
        $this->load->view('masteradmin', $data);
	}

}
