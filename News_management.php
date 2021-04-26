<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News_management extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
 
    }
	public function index()
	{
		$data = array();
		$data['menu'] = 'news';
		$data['content'] = $this->load->view('news/news_form_entry',$data,true);
		$data['container'] = $this->load->view('news/news_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	
	
	public function insert()
	{
		$title = $this->input->post('title');
		$desc = $this->input->post('desc');
		$err=0;
		$msg="";
		if(empty($title)){$msg .=++$err.'. Title required<br>';}
		if(empty($desc)){$msg .=++$err.'. Description required<br>';}
		
		$p = pathinfo($_FILES['pic']['name']);
			$picname = "";
			if(count($p)>2)
			{
				$numrow = $this->db->query("select count(*)as numrow from t_news")->row()->numrow;
				$numrow = $numrow+1;
				$picname = $numrow.'.png';
			}
		if($err==0)
		{
			$data['title']= $title;
			$data['desc']= $desc;
			$data['publishdate']= date('Y-m-d');
			if($picname != "")
			{
				$data['pic']= $picname;
			}
			if($this->mm->insert_data('t_news',$data))
			{
				if(count($p)>2)
				{
					$imgfilename = $picname;
					//print_r($picname);exit;
					$this->mm->image_upload('./img/news/' , '15000000', '5000', '3000', $imgfilename ,'400','300','pic');
				}
				redirect(base_url()."news_management/index?sk=Saved Successfully");
			}
			else
			{
				redirect(base_url()."news_management/index?esk=Database too busy");
			}
		}
		else{
			redirect(base_url()."news_management/index?esk=".$msg);
		}
	}
	
	public function view_info()
	{
		$data = array();
		$data['per_page'] = 5;
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
		$data['menu'] = 'news';
		$data['model'] = $this->db->query("select * from t_news Limit $limit_start , $limit_end")->result();
		$data['newslist'] = $this->db->query(" select * from t_news")->result();
		$data['content'] = $this->load->view('news/view_news_info',$data,true);
		$data['container'] = $this->load->view('news/news_page',$data,true);
		$this->load->view('masteradmin',$data);
	}
	
	public function edit_data()
	{
		$id = array("id" => $this->uri->segment(3));
		
		$data = array();
		$data['news'] = $this->mm->get_all_info_by_id('t_news',$id);
		$data['content'] = $this->load->view('news/news_form_edit',$data,true);
		$data['container'] = $this->load->view('news/news_page',$data,true);
		$this->load->view('masteradmin',$data);
		
	}
	public function update_category()
	{
		$tid = $this->input->post('id');
		$id = array("id" => $this->input->post('id'));
		$title = $this->input->post('title');
		$desc = $this->input->post('desc');
		$err=0;
		$msg="";
		if(empty($title)){$msg .=++$err.'. Title required<br>';}
		if(empty($desc)){$msg .=++$err.'. Description required<br>';}
		
			$p = pathinfo($_FILES['pic']['name']);
			$picname = "";
			if(count($p)>2)
			{
				$picname = $this->db->query("Select pic from t_news where id='".$tid."'")->row()->pic; 
				if(count($picname)==0)
				{
					$picname = $tid.'.png';
				}
			}
		if($err==0)
		{
			if(count($p)>2)
			{
				$imgfilename = $picname;
				$oldfile = $picname;
				$this->mm->image_upload2('./img/news/' , '15000000', '5000', '3000', $imgfilename ,'400','300','pic',$oldfile);
			}
			$data = array();
			$data['title']= $title;
			$data['desc']= $desc;
			if($picname != "")
			{
				$data['pic']= $picname;
			}
			if($this->mm->update_info('t_news',$data,$id))
			{
				redirect(base_url()."news_management/view_info?sk=Update Successfully", "refresh");
			}
			else
			{
				redirect(base_url()."news_management/view_info?esk=Database too busy" , "refresh");
			}
		}
		else{
			redirect(base_url()."news_management/view_info?esk=".$msg);
		}
	}
	public function delete_data($id)
	{
		if($this->mm->delete_info('t_news',array("id" =>$id)))
		{
			$oldfile = './img/news/'.$id.'.png';
			if(file_exists($oldfile))
			{
				unlink($oldfile);
			} 
			$msg = "Deleted Successfully";
			redirect(base_url()."news_management/view_info?sk=".$msg , "refresh");
		}
		else
		{
			$emsg = "Database too busy";
			redirect(base_url()."news_management/view_info?esk=".$emsg , "refresh");
		}	
	}
	
	
	
}
