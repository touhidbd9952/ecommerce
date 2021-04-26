<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mymodel_model extends CI_Model
{


	public function checklogin($table,$username,$password)
	{
		$this->db->where("username",$username);
		$this->db->where("password",$password);
		$this->db->select("*");
		$this->db->from($table);
		return $this->db->get()->result(); 
	}
	public function check_customer_login($table,$email)
	{
		$this->db->where("email",$email);
		$this->db->select("*");
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function insert_data($table,$data) //insert
	{
		if($this->db->insert($table,$data))
		{
			return true;
		}
		return false;
	}
	public function view_data($table)
	{
		$this->db->select("*");
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function get_last_record_id($table)
	{
		$this->db->select_max('id');
		$this->db->from($table);
		$result = $this->db->get()->result();
		$id = 0;
		foreach($result as $r)
		{
			$id = $r->id;
		}
		return $id;
	}
	public function get_last_record($table)
	{
		$this->db->select_max('id');
		$this->db->from($table);
		$result = $this->db->get()->result();
		foreach($result as $r)
		{
			$id = $r->id;
		}
		$this->db->where(array('id'=>$id));
		$this->db->select('*');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	function getLastInserted($table, $id) 
	{
		$this->db->select_max($id);
		$Q = $this->db->get($table);
		$row = $Q->row_array();
		return $row[$id];
 	}
	public function getinfo($table) //getinfo * 
	{
		$this->db->select('*');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function Get_data($table,$fields,$id)
	{
		$this->db->where($id);
		$this->db->select($fields);
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function view_data_two_table($table1,$table2,$select,$relation)  //view data two table
	{
		$this->db->select($select);
		$this->db->from($table1);
		$this->db->join($table2,$relation);
		return $this->db->get()->result();
	}
	public function getinf_unread_mail($table,$unread)
	{
		$this->db->where($unread);
		$this->db->select('*');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	

	public function getinfo_name($table,$name)
	{
		$this->db->where('name',$name);
		$this->db->select('*');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function getinfo_email($table,$email)
	{
		$this->db->where('email',$email);
		$this->db->select('*');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function update_info($table,$data,$id) //update
	{
		$this->db->where($id);
		if($this->db->update($table,$data))
		{
			return true;
		}
		return false;
	}
	public function update_by_user_pass($table,$username,$newpass)
	{
		$this->db->where($username);
		if($this->db->update($table,$newpass))
		{
			return true;
		}
		return false;
	}
	
	///////////////////////////////////////////////////////////////////////////////////
	
	
	/* Security Fuctions */
	
	public function randomCode($length) {
    $alphabet = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789abcdefghijklmnpqrstuvwxyz$.-#";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
	}
	
	public function set_key_Server(){
	$domain=$_SERVER['SERVER_ADDR'];
	$domain=str_replace(".","",$domain);
	$domain ='8080';//remove when live
	$domain=base64_encode($domain);
	$code= $this->mm->randomCode(50);
	$codeKey=$code.$domain;
	$update=$this->db->query("UPDATE t_settings SET value='$codeKey' WHERE name='serverkey' LIMIT 1");
	return $code;
	}
	
	public function key_Split($str, $length) {
	$str1 = substr($str, 20) ;
	$str2 = substr($str, 15) ;
	$str3 = substr($str, 40) ;
	$str4 = substr($str, 35) ;
	$str5 = substr($str, 0, 10) ;
	$str6 = substr($str, 25, 13) ;
	$fstr =$str1.$str2.$str3.$str4.$str5.$str6;
	return substr($fstr, 7, $length);
	}
	
	public function key_Server(){
	$domain=$_SERVER['SERVER_ADDR'];
	$domain=str_replace(".","",$domain);
	$domain ='8080';//remove when live
	$domain=base64_encode($domain);
	$code=$this->mm->getSet("serverkey");
	if ($code){
	$preCode=substr($code, 0, 50);
	$preCode2=str_replace($preCode,'',$code);
	if ($preCode2==$domain) {$codeKey=$preCode;}
	else {
	//$code=$this->mm->set_key_Server();
	$codeKey=NULL;
	}} else {
	$code=$this->mm->set_key_Server();
	$codeKey=$code;
	}
	return $codeKey;
	}

	public function enc_Key(){
	$key=$this->mm->key_Server();
	$code=$this->mm->key_Split($key, 30);
	return $code;
	}
	
	public function rc4($key, $str) {
	$s = array();
	for ($i = 0; $i < 256; $i++)
	{ $s[$i] = $i; }
	$j = 0;
	for ($i = 0; $i < 256; $i++) {
	$j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
	$x = $s[$i];
	$s[$i] = $s[$j];
	$s[$j] = $x; }
	$i = 0; $j = 0; $res = '';
	for ($y = 0; $y < strlen($str); $y++) {
	$i = ($i + 1) % 256;
	$j = ($j + $s[$i]) % 256;
	$x = $s[$i];
	$s[$i] = $s[$j];
	$s[$j] = $x;
	$res .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
	}
	return $res;
	}

	public function insert_rc4_pass($username,$password){  /////use in registration
	$username =  strtolower($username);
	$key = $this->mm->enc_Key().$username;	
	//$stp = base64_encode($this->mm->rc4($key, $password));
	$stp = base64_encode($this->mm->rc4($key, $password));
	$stp=$this->mm->randomCode(59).$stp;
	return $stp;
	}

	public function read_rc4_pass($username,$db_pass){  //////use in password checking
	$username =  strtolower($username);
	$db_pass=substr($db_pass, 59) ;
	//echo $db_pass;exit;
	$pass = base64_decode($db_pass);
	
	$key = $this->mm->enc_Key().$username;
	$ori_pass = $this->mm->rc4($key, $pass);
	return $ori_pass;
	}
	
	
	
	
	
	//////////////////////////////////////////////////////////////////////////////////
	
	public function delete_info($table,$id) //delete
	{
		if($this->db->delete($table,$id))
		{
			return true;
		}
		return false;
	}
	public function get_all_info($table)
	{
		$this->db->select('*');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function get_all_info_by_cid($table,$id)
	{
		$this->db->where('clientid',$id);
		$this->db->select('*');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function get_info_by_id($table,$id)
	{
		$this->db->where($id);
		$this->db->select('pic');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function get_all_info_by_id($table,$id) //get info by id
	{
		$this->db->where($id);
		$this->db->select('*');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function get_all_info_by_id_desc($table,$id,$order)
	{
		$this->db->where($id);
		$this->db->select('*');
		$this->db->from($table);
		$this->db->order_by($order,"desc");
		return $this->db->get()->result();
	}
	public function getinfo_by_two_col($table,$user,$email)
	{
		$this->db->where('name',$user);
		$this->db->where('email',$email);
		$this->db->select('*');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function getinfo_by_user_pass($table,$user,$pass)
	{
		$this->db->where('username',$user);
		$this->db->where('password',$pass);
		$this->db->select('*');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function get_all_info_by_date($table,$startdate,$enddate)
	{
		$this->db->where("date between '".$startdate."' and '".$enddate."'");
		$this->db->select('SUM(receiptamount) AS receiptamt, SUM(sendamount) AS sendamt');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function get_all_info_by_date1($table,$startdate,$enddate)
	{
		$this->db->where("date between '".$startdate."' and '".$enddate."'");
		$this->db->select('SUM(amount) AS amount');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function getinfo_date($table,$startdate,$enddate)
	{
		$this->db->order_by('date','asc');
		$this->db->where("date between '".$startdate."' and '".$enddate."'");
		$this->db->select('*');
		$this->db->from($table);
		return $this->db->get()->result();
	}
	public function InsertWithImage($table,$data)
	{
		$this->db->insert($table,$data);
		$insert_id = $this->db->insert_id();
		return $insert_id ;
	}
	public function Insert_data_getid($table,$data) //Insert_data_getid
	{
		$this->db->insert($table,$data);
		$insert_id = $this->db->insert_id();
		return $insert_id ;
	}
	public function UpdateWithImage($table, $data,$id ) //update with image
	{
		$this->db->where($id);
		if ($this->db->update($table ,$data))
 			{
 				return true ;
 			}
 			return false ;
	}
	
	
	//////isLogin/////////////////////////////////////////////////////
	public function isLogin()
	{
		$sid=$this->session->userdata('sid');
		$username=$this->session->userdata('username');
		$userid=$this->session->userdata('userid');
		//$email=$this->session->userdata('email');
		//$phone=$this->session->userdata('phone');
		$loged_in=$this->session->userdata('loged_in'); 
		
		//$system = $this->agent->agent_string();
		//$ip = $_SERVER['REMOTE_ADDR'];
		///////////
		if (empty($sid) && $loged_in!=TRUE)
		{ 
			$this->session->sess_destroy(); //////////////
			redirect('main/index');
		}
		
		$q=$this->db->query("SELECT * FROM t_user_online WHERE username='".$username."' AND  userid='".$userid."' AND loged_in='true' LIMIT 1");
		if ($q->num_rows()==0){ redirect('main/index');}
		
		
		$this->mm->onlineUser();
	}
	/////online User//////////////////////////////////////////////// 	
	public function onlineUser(){
	$sid=$this->session->userdata('sid');
	$username=$this->session->userdata('username');
	$userid=$this->session->userdata('userid');
	$email=$this->session->userdata('email');
	$phone=$this->session->userdata('phone');
	$loged_in=$this->session->userdata('loged_in');
	$system = $this->agent->agent_string();
	$ip = $_SERVER['REMOTE_ADDR'];
	
	$time=time();
	$time_check=time()-3600;  ////1 hour= 3600
	//New User
	//$q=$this->db->query("select * from t_user_online where username='".$username."'")->result(); 
	//$userpass=$this->mm->read_rc4_pass($username,$q[0]->sid); 
	$currentusersid = md5($userid.$username.$email.$phone.$system.$ip); 
	
	//$currentusersid = $this->mm->insert_rc4_pass($username,$currentusersid); echo $currentusersid;exit;
	
	if($currentusersid != $sid){ 
	redirect('admincontroller/admin_logout');
	}else{ 
	$uData=array('time'=>$time);
	$this->db->where('sid',$sid);
	$this->db->update('t_user_online',$uData);
	}
	
	//Logout inactive user
	$this->db->delete('t_user_online', array('time <' => $time_check)); /////delete all user who loged in over 1 hour
	}
	
	
	public function getSet($place)
	{
		$value = $this->db->query("Select value from t_settings where name='".$place."'")->row()->value;
		return $value;
			
	}
	public function getSets($table,$reqcol ,$wherecol) //table, required column, where condition
	{
		$value = $this->db->query("Select $reqcol from $table where $wherecol")->row()->value;  //name='".$place."'
		return $value;
			
	}
	public function getPic($id)
	{
		$picname = $this->db->query("Select * from t_pic where id='".$id."'")->row()->pic;
		return $picname;
			
	}
	public function getCatid($name)
	{
		$catid=0;
		$catdata = $this->db->query("Select id from category where name='".$name."'")->result();
		if(count($catdata)>0){$catid = $catdata[0]->id;}
		return $catid;
			
	}
	public function getApiInfo($place)
	{
		$value = $this->db->query("Select value from t_settings where name='".$place."'")->row()->value;
		return $value;
	}
	
	//////sms system ////////
	
	public function curl_get($url, array $get = NULL, array $options = array()) 
	{  
		$defaults = array(
			CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get), 
			CURLOPT_HEADER => 0, 
			CURLOPT_RETURNTRANSFER => TRUE, 
			CURLOPT_TIMEOUT => 5 
		); 
		 
		//print_r($defaults);
    
		$ch = curl_init(); 
		curl_setopt_array($ch, ($options + $defaults)); 
		$result = curl_exec($ch);
		curl_close($ch); 
		return $result; 
		
	}
	
	
	public function curl_get_ip($url, array $get = NULL, array $options = array()) 
	{  
		$defaults = array(
			CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get), 
			CURLOPT_HEADER => 0, 
			CURLOPT_RETURNTRANSFER => TRUE, 
			CURLOPT_TIMEOUT => 5 
		); 
		//print_r($defaults);
    
		$ch = curl_init(); 
		curl_setopt_array($ch, ($options + $defaults)); 
		$result = curl_exec($ch);
		curl_close($ch); 
		return $result; 
		
	}
	public function startWith($haystack, $needle)
	{
		$var=!strncmp($haystack, $needle, strlen($needle));
		if ($var==1) return true;
		else return false;
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function image_upload ($destinationFolder , $maxSize , $maxWidth , $maxHeight , $fileName,$image1_width,$image1_height,$pic) 
	{
 		$config = array ();
		$config['upload_path'] = $destinationFolder ; 
		$config['allowed_types'] = 'gif|jpg|png|jpeg|pjpeg|x-png' ; 
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
		return true;
 	}
	function image_upload2($destinationFolder , $maxSize , $maxWidth , $maxHeight , $fileName,$image1_width,$image1_height,$pic,$oldfile) 
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
		$config['allowed_types'] = 'gif|jpg|png|jpeg|pjpeg|x-png' ; 
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
		
		return true;
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
		$config['allowed_types'] = 'gif|jpg|png|jpeg|pjpeg|x-png' ; 
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
		return true;
 	}
	
	
	
	/////////News Image Insert////////
	function image_news_upload ($destinationFolder ,$fileName, $pic) 
	{
 		$config = array ();
		$config['upload_path'] = $destinationFolder ; 
		$config['allowed_types'] = 'gif|jpg|png|jpeg' ; 
		$config['max_size'] = 2048 ; 
		$config['max_width'] = 0 ;  
		$config['max_height'] = 0 ; 
		$config['file_name'] = $fileName ; 

 		$this->upload->initialize($config ); 
 		$this->upload->do_upload($pic); 
		
		//Image 640x359 
		$config = array();
		$config['source_image'] = $destinationFolder.$fileName;
		$config['new_image'] = $destinationFolder.'/640x359/';
		$config['admintain_ratio'] = FALSE;
		$config['width'] = 640;
		$config['height'] = 359;
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		//Image 320x179 
		$config = array();
		$config['source_image'] = $destinationFolder.$fileName;
		$config['new_image'] = $destinationFolder.'/320x179/';
		$config['admintain_ratio'] = FALSE;
		$config['width'] = 320;
		$config['height'] = 179;
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		//Image 320x179 
		$config = array();
		$config['source_image'] = $destinationFolder.$fileName;
		$config['new_image'] = $destinationFolder.'/200x112/';
		$config['admintain_ratio'] = FALSE;
		$config['width'] = 200;
		$config['height'] = 112;
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		//Image 320x179 
		$config = array();
		$config['source_image'] = $destinationFolder.$fileName;
		$config['new_image'] = $destinationFolder.'/120x67/';
		$config['admintain_ratio'] = FALSE;
		$config['width'] = 120;
		$config['height'] = 67;
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		////At the end /////
		$this->image_lib->clear(); 
		$oldfile = $destinationFolder.$fileName;
		if(file_exists($oldfile))
		{
			unlink($oldfile);
		} 
		return true;
		
 	}
	
	/////////News Image Update ////////
	function image_news_upload2($destinationFolder ,$fileName, $pic) 
	{
		
		$oldfile1 = $destinationFolder.'/640x359/'.$fileName;
		if(file_exists($oldfile1))
		{
			unlink($oldfile1);
		}
		$oldfile2 = $destinationFolder.'/320x179/'.$fileName;
		if(file_exists($oldfile2))
		{
			unlink($oldfile2);
		}
		$oldfile3 = $destinationFolder.'/200x112/'.$fileName;
		if(file_exists($oldfile3))
		{
			unlink($oldfile3);
		}
		$oldfile4 = $destinationFolder.'/120x67/'.$fileName;
		if(file_exists($oldfile4))
		{
			unlink($oldfile4);
		}
		
 		$config = array ();
		$config['upload_path'] = $destinationFolder ; 
		$config['allowed_types'] = 'gif|jpg|png|jpeg' ; 
		$config['max_size'] = 2048 ; 
		$config['max_width'] = 0 ;  
		$config['max_height'] = 0 ; 
		$config['file_name'] = $fileName ; 

 		$this->upload->initialize($config ); 
 		$this->upload->do_upload($pic); 
		
		//Image 640x359 
		$config = array();
		$config['source_image'] = $destinationFolder.$fileName;
		$config['new_image'] = $destinationFolder.'/640x359/';
		$config['admintain_ratio'] = FALSE;
		$config['width'] = 640;
		$config['height'] = 359;
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		//Image 320x179 
		$config = array();
		$config['source_image'] = $destinationFolder.$fileName;
		$config['new_image'] = $destinationFolder.'/320x179/';
		$config['admintain_ratio'] = FALSE;
		$config['width'] = 320;
		$config['height'] = 179;
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		//Image 320x179 
		$config = array();
		$config['source_image'] = $destinationFolder.$fileName;
		$config['new_image'] = $destinationFolder.'/200x112/';
		$config['admintain_ratio'] = FALSE;
		$config['width'] = 200;
		$config['height'] = 112;
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		//Image 320x179 
		$config = array();
		$config['source_image'] = $destinationFolder.$fileName;
		$config['new_image'] = $destinationFolder.'/120x67/';
		$config['admintain_ratio'] = FALSE;
		$config['width'] = 120;
		$config['height'] = 67;
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		////At the end /////
		$this->image_lib->clear(); 
		$oldfile = $destinationFolder.$fileName;
		if(file_exists($oldfile))
		{
			unlink($oldfile);
		} 
		return true;
		
 	}
	////
	function image_newsbanner_upload($destinationFolder ,$fileName, $pic) 
	{
		$oldfile1 = $destinationFolder.'banner/'.$fileName; 
		if(file_exists($oldfile1))
		{
			unlink($oldfile1);
		}

 		$config = array ();
		$config['upload_path'] = $destinationFolder ; 
		$config['allowed_types'] = 'gif|jpg|png|jpeg' ; 
		$config['max_size'] = 0;  
		$config['max_height'] = 0 ; 
		$config['file_name'] = $fileName ; 

 		$this->upload->initialize($config ); 
 		$this->upload->do_upload($pic); 
		
		//Image 1440x115 
		$config = array();
		$config['source_image'] = $destinationFolder.$fileName;
		$config['new_image'] = $destinationFolder.'/banner/';
		$config['admintain_ratio'] = FALSE;
		$config['width'] = 1440;
		$config['height'] = 115;
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		////At the end /////
		$this->image_lib->clear();
		 
		$oldfile = $destinationFolder.$fileName;
		if(file_exists($oldfile))
		{
			unlink($oldfile);
		} 
		return true;
		
 	}
	////
	function delete_image($image_location_with_image_name) // 'img/customer/'.$picturename)
	{
		unlink($image_location_with_image_name);
		return true;
	}
	function dateconverttobangla($dateofgiven)
	{
		$currentDate = date_format(date_create($dateofgiven), "j F Y"); //date("j F Y");
						   
		$engDATE = array(1,2,3,4,5,6,7,8,9,0,'January','February','March','April','May','June','July','August','September','October','November','December');
		$bangDATE = array('১','২','৩','৪','৫','৬','৭','৮','৯','০','জানুয়ারী','ফেব্রুয়ারী','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর' );
		$convertedDATE = str_replace($engDATE, $bangDATE, $currentDate);
		return $convertedDATE;
	}
	function converttobangla($num)
	{
		$engMum = array(1,2,3,4,5,6,7,8,9,0);
		$bangNum = array('১','২','৩','৪','৫','৬','৭','৮','৯','০' );
		$convertedNum = str_replace($engMum, $bangNum, $num);
		return $convertedNum;
	}
	function currentdaydatetobangladay()
	{
		$currentdatdate = date('l, d F Y');
		$engDATE = array(1,2,3,4,5,6,7,8,9,0,'Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','January','February','March','April','May','June','July','August','September','October','November','December');
		$bangDATE = array('১','২','৩','৪','৫','৬','৭','৮','৯','০','শনিবার','রবিবার','সোমবার','মঙ্গলবার','বুধবার ','বৃহস্পতিবার','শুক্রবার','জানুয়ারী','ফেব্রুয়ারী','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর' );
		$convertedDATE = str_replace($engDATE, $bangDATE, $currentdatdate);
		return $convertedDATE;
	}
	function englishnumbertobanglanumber($num)
	{
		$engNum = array(1,2,3,4,5,6,7,8,9,0);
		$bangNum = array('১','২','৩','৪','৫','৬','৭','৮','৯','০' );
		$convertedNum = str_replace($engNum, $bangNum,$num);
		return $convertedNum;
	}
	
	function publishdatetime($dateandtime)
	{
		$dt = "";
		$date1 = $dateandtime;
		if($date1 == '0000-00-00 00:00:00'){$date1 = date('Y-m-d h:i:s');}
		$today = date('Y-m-d h:i:s');
		$date2 = $today;
		
		//if(strtotime($date2)> strtotime($date1)){
		//$diff = abs((strtotime($date2) - strtotime($date1))) ;}
		
		$diff = abs((strtotime($date2) - strtotime($date1))) ;
		
		if(($diff /(60 * 60 * 24))<1)
		{
			if(($diff /(60 * 60))<1)
			{
				if(($diff /(60))<1)
				{
					$dt = round($diff);
					$dt = $this->converttobangla($dt).' সেকেন্ট আগে';
				}
				else
				{
					$dt = round(abs(($diff /60)));
					$dt = $this->converttobangla($dt).' মিনিট আগে';
				}
			}
			else
			{
				$dt = round(abs(($diff /(60 * 60))));
				$dt = $this->converttobangla($dt).' ঘন্টা আগে';
			}
		}
		else
		{
			$dt = round(abs(($diff /(60 * 60 * 24))));
			$dt = $this->converttobangla($dt).' দিন আগে';
		}
		return $dt;
	}
	
	////Word count ////
	function limit_text($text, $limit) {
      if (str_word_count($text, 0) > $limit) {
          $words = str_word_count($text, 2);
          $pos = array_keys($words);
          $text = substr($text, 0, $pos[$limit]) . '...';
      }
      return $text;
    }
	
	function clean_string($string) {
   		$string = str_replace('%', '', $string); 
		$string = str_replace('!', '', $string); 
		$string = str_replace(@"‘", '', $string); 
		$string = str_replace(@"’", '', $string); 
		$string = str_replace(@",", '', $string); 
		$string = str_replace(@"?", '', $string);
		$string = str_replace(@":", '', $string);
		$string = str_replace(@"@", @"", $string);
		$string = str_replace(@"/", @"", $string); 
		$string = str_replace(@"//", @"/", $string); 

   		return preg_replace('/-+/', '-', $string); 
	}
	function calculatepercentage($salesprice, $discount)
	{
		return (($discount/$salesprice)*100);
		
	}
	
	function convert_message_subject($messagesubject) //admin message template
	{
		$logourl="";
		$companyname="";
		$companyemail="";
		$messagename="";          
		$customername = "Customer";  
		$message="Customer Message Customer Message Customer Message";
		$orderurlforcustomer = "orderurl";   
		
		$baseurl = base_url();
		$settingdata = $this->db->query("select * from t_settings")->result();
		foreach($settingdata as $s)
		{
			if($s->name == "logourl"){$logourl = $s->value;}
			if($s->name == "Company Name"){$companyname = $s->value;}
			if($s->name == "servermail"){$companyemail = $s->value;}
		}	
		if($messagesubject != "")
		{	
			if((strpos($messagesubject,'%StoreURL%'))||(strpos($messagesubject,'%StoreURL%' )=='true')){ $messagesubject = str_replace('%StoreURL%',$logourl,$messagesubject);}
			if((strpos($messagesubject,'%StoreName%')) || (strpos($messagesubject,'%StoreName%')=='true')){ $messagesubject = str_replace('%StoreName%',$companyname,$messagesubject);}
			if((strpos($messagesubject,'%StoreEmail%'))||(strpos($messagesubject,'%StoreEmail%')=='true')){ $messagesubject = str_replace('%StoreEmail%',$companyemail,$messagesubject);}
			if((strpos($messagesubject,'%CustomerName%'))|| (strpos($messagesubject,'%CustomerName%')=='true')){ $messagesubject = str_replace('%CustomerName%',$customername,$messagesubject);} 
			if((strpos($messagesubject,'%OrderURLForCustomer%'))||(strpos($messagesubject,'%OrderURLForCustomer%')=='true')){$messagesubject = str_replace('%OrderURLForCustomer%',$orderurlforcustomer,$messagesubject);}
		}
		return $messagesubject;
	}
	function convert_message_text($messagetext) //admin message template
	{
		$logourl="";
		$companyname="";
		$companyemail="";
		$messagename="";          
		$customername = "Customer";  
		$message="Customer Message Customer Message Customer Message";
		$orderurlforcustomer = "orderurl";   
		
		$baseurl = base_url();
		$settingdata = $this->db->query("select * from t_settings")->result();
		foreach($settingdata as $s)
		{
			if($s->name == "logourl"){$logourl = $s->value;}
			if($s->name == "Company Name"){$companyname = $s->value;}
			if($s->name == "servermail"){$companyemail = $s->value;}
		}
		if($messagetext !="")
		{
			if((strpos($messagetext,'%StoreURL%'))||(strpos($messagetext,'%StoreURL%')=='true')){ $messagetext = str_replace('%StoreURL%',$logourl,$messagetext);}
			if((strpos($messagetext,'%StoreName%'))||(strpos($messagetext,'%StoreName%')=='true')){ $messagetext = str_replace('%StoreName%',$companyname,$messagetext);}
			if((strpos($messagetext,'%StoreEmail%'))||(strpos($messagetext,'%StoreEmail%')=='true')){ $messagetext = str_replace('%StoreEmail%',$companyemail,$messagetext);}
			if((strpos($messagetext,'%CustomerName%'))||(strpos($messagetext,'%CustomerName%')=='true')){ $messagetext = str_replace('%CustomerName%',$customername,$messagetext);} 
			if((strpos($messagetext, '%OrderURLForCustomer%'))||(strpos($messagetext, '%OrderURLForCustomer%')=='true')){$messagetext = str_replace('%OrderURLForCustomer%',$orderurlforcustomer,$messagetext);}
			if((strpos($messagetext, '%CustomerMessage%'))||(strpos($messagetext, '%CustomerMessage%')=='true')){$messagetext = str_replace('%CustomerMessage%',$message,$messagetext);}
		}
		return $messagetext;
	}
	public function convert_mail_text1($orderurl,$message) //client mail with order url, only for message
	{
	  $settingdata = $this->db->query("select * from t_settings where subject='Company' ")->result();
	
	  $logourl="";
	  $company="";
	  $companyemail="";
	  if(count($settingdata)>0)
	  {
		foreach($settingdata as $sd)
		{
		  if($sd->name == 'logourl'){$logourl=$sd->value;}
		  if($sd->name == 'Company Name'){$company=$sd->value;}
		  if($sd->name == 'servermail'){$companyemail=$sd->value;}
		}
	  }
	  $customer = $this->session->userdata("customer");
	  if(strpos($message,'%StoreURL%')){$message = str_replace("%StoreURL%", $logourl, $message);}
	  if(strpos($message,'%StoreName%')){$message = str_replace("%StoreName%", $company, $message);}
	  if(strpos($message,'%StoreEmail%')){$message = str_replace("%StoreEmail%", $companyemail, $message);}
	  if(strpos($message,'%CustomerName%')){$message = str_replace("%CustomerName%", $customer, $message);}
	  if(strpos($message,'%OrderURLForCustomer%')){$message = str_replace("%OrderURLForCustomer%", $orderurl, $message);}
	  return $message;
	}
	public function convert_mail_text2($string) //client mail, no order url ,for subject, general message
	{
	  $settingdata = $this->db->query("select * from t_settings where subject='Company' ")->result();
	
	  $logourl="";
	  $company="";
	  $companyemail="";
	  if(count($settingdata)>0)
	  {
		foreach($settingdata as $sd)
		{
		  if($sd->name == 'logourl'){$logourl=$sd->value;}
		  if($sd->name == 'Company Name'){$company=$sd->value;}
		  if($sd->name == 'servermail'){$companyemail=$sd->value;}
		}
	  }
	  $customer = $this->session->userdata("customer");
	  if(strpos($string,'%StoreURL%')){$string = str_replace("%StoreURL%", $logourl, $string);}
	  if(strpos($string,'%StoreName%')){$string = str_replace("%StoreName%", $company, $string);}
	  if(strpos($string,'%StoreEmail%')){$string = str_replace("%StoreEmail%", $companyemail, $string);}
	  if(strpos($string,'%CustomerName%')){$string = str_replace("%CustomerName%", $customer, $string);}
	  return $string;
	}
	
	//// Activity Logs //////////////////////////////////////////////
	
	public function SaveLogs($type,$info) //type of log, change info
	{
		 $user_id=$this->session->userdata('username'); 
		 //$level=$this->session->userdata('user_type');
		 $ip=$this->getUserIP();
		 //$url=$_SERVER['SERVER_NAME'];
		 $date_time=date("Y-m-d H:i:s");
		 $browser=$this->thisBrowser();
		 $platform=$this->sysInfo();
		 $ipData = $this->ip_info();
		 $cCode="";$ctCode="";
		 if(!empty($ipData))
		 {
		 	$cCode=$ipData->countryCode;
		 	$ctCode=$ipData->city;
		 }
		 $filename=date('Ymd');
		 $filepath="logs/$filename.txt";
		 $data=$date_time.";".$ip.";".$user_id.";".$type.";".$info.";".$browser.";".$platform.";".$cCode.";".$ctCode.'====';
		 file_put_contents($filepath, $data."\n", FILE_APPEND);
	} 
	public function ip_info($user_ip=null) 
	{
		 //$this->db = $this->load->database('t_ip_info', TRUE);
			if ($user_ip ==""){$user_ip = $this->getUserIP();}  
			$user_ip = '103.232.101.27';
		 $rowData=null;$spl=null;
		 $spl=explode(".",$user_ip);
		 $ip2=$spl[0].".".$spl[1].".".$spl[2];
		 
		 $rowData=$this->db->query("SELECT * from t_ip_info where ip = '".$ip2."'")->result();
		 foreach($rowData as $rdata){$rowData = $rdata->data;}
		 //echo $this->db->last_query();
		 //print_r($rowData); exit;
		 if (count($rowData)==0)
		 {
			 $xml = $this->curl_get_ip("https://easyrecharge.ws/ipinfo/ipinfo/info/$user_ip",array()); 
			 //echo $xml;exit;
			 if (!strlen($xml)){ $xml = $this->curl_get_ip("https://pro.ip-api.com/json/".$user_ip."?key=o85nrcMiyTMo7jV",array());}
			  //echo $xml;exit;
			 if ($xml){$this->db->insert('t_ip_info', array('ip'=>$ip2, 'data'=>$xml));}
			 $xmlData = json_decode($xml);
			 //$xml = json_decode($this->mm->curl_get_ip("https://pro.ip-api.com/json/".$user_ip."?key=o85nrcMiyTMo7jV"));
			 return $xmlData;
		 } 
		 else { $xmlData = json_decode($rowData); return $xmlData;}
	 }
	 public function sysInfo()
	 {
		 $this->load->library('user_agent');
		 $platfo=null;
		 $platform=$this->agent->agent_string(); 
		 $plat=explode(" (",$platform);
		 if ($this->agent->is_mobile())
		 {
		  $plat = explode(" Build/",$plat[1]);
		  $platfo1 = explode(";",$plat[0]);
		  $platfo = $platfo1[1]." ".$platfo1[2];
		  $platfo = explode(")",$platfo);
		  $platfo = $platfo[0];
		 } 
		 else 
		 {
			 $platfo=explode(";",$plat[1]); 
			 $platfo=$platfo[0];
		  
		  	$platfo=explode(")",$platfo); 
		  	$platfo=$platfo[0];
		 }
		 return $platfo;
	 }
	 public function getUserIP() 
	 {
		 $client  = @$_SERVER['HTTP_CLIENT_IP'];
		 $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		 $remote  = getenv('REMOTE_ADDR');
		
		 if(filter_var($client, FILTER_VALIDATE_IP)) { $ip = $client; }
		 elseif(filter_var($forward, FILTER_VALIDATE_IP)) { $ip = $forward; }
		 else { $ip = $remote; }
		 return $ip;
	 }
	 public function thisBrowser()
	 {
		 $this->load->library('user_agent');
		 if ($this->agent->is_browser())
		 {
		 $agent = $this->agent->browser().' '.$this->agent->version();
		 }
		 elseif ($this->agent->is_robot())
		 {
		 $agent = $this->agent->robot();
		 }
		 elseif ($this->agent->is_mobile())
		 {
		 $agent = $this->agent->mobile();
		 }
		 else
		 {
		 $agent = 'Undefined';
		 }
		 return $agent;
	 }
	public function send_mail($smtp_host,$smtp_port,$smtp_user,$smtp_pass,$to,$from,$subject,$message)
	{
		//Load email library
		$this->load->library('encrypt');
		$this->load->library('email');
		//SMTP & mail configuration
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => 'ssl://'.$smtp_host,
			'smtp_port' => $smtp_port,
			'smtp_user' => $smtp_user,
			'smtp_pass' => $smtp_pass,
			'mailtype'  => 'html',
			'charset'   => 'utf-8'
		);
		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");
		
		//Email content
		$this->email->to($to);
		$this->email->from($from);
		$this->email->subject($subject);
		$this->email->message($message);
		//Send email
		if($this->email->send()){return true;}else{return false;}	
	}
	public function checkpermission($acl,$permission)
	{
		$access="no";
		$admin = $this->session->userdata("username");
		if($acl !="" && $permission !="")
		{
			if(strpos($permission,','))
			{
				$permission = explode(',',$permission);
				if(in_array($acl,$permission)){ $access='yes';}else{$access='no';}
			}
			else
			{
				if($acl == $permission){ $access='yes';}else{$access='no';}
			}
			return $access;
		}
		else if($admin=='admin'){ $access='yes';return $access;}
		return $access;
	}
	
	
	
	//////////
	//echo $this->db->last_query();
	
}
