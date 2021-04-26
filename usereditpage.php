<?php 
	$roleid="";
	$perm="";
	if(isset($selectedrole)&& count($selectedrole)>0)
	{
		foreach($selectedrole as $srr)
		{
			$roleid = $srr->id;
			$perm = $srr->permissionids;
			if(strpos($perm,','))
			{
				$perm = explode(',',$perm);
			}
		}
	}
	
?>

<script>
function select_permission()
{
	var roleid = document.getElementById('rolegroup').value;
	//alert(roleid);
	window.location.href = "<?php echo site_url('acl_management/usercreate_form_with_permission?id='.roleid);?>";
}

</script>


<script>

function checkall1()
{
	var admin = document.getElementById('admin');
   if(admin.checked == true)
   {
	   for(var i=1;i<=18;i++){
	   document.getElementById('adminvar'+i).checked=true;}
   }
   else if(admin.checked == false)
   {
	   for(var i=1;i<=18;i++){
	    document.getElementById('adminvar'+i).checked=false;}
   }
} 
function checkall2()
{
	var catalog = document.getElementById('catalog');
   if(catalog.checked == true)
   {
	   for(var i=1;i<=33;i++){
	   document.getElementById('catalogvar'+i).checked=true;}
   }
   else if(catalog.checked == false)
   {
	   for(var i=1;i<=33;i++){
	    document.getElementById('catalogvar'+i).checked=false;}
   }
} 
function checkall3()
{
	var customer = document.getElementById('customer');
   if(customer.checked == true)
   {
	   for(var i=1;i<=13;i++){
	   document.getElementById('customervar'+i).checked=true;}
   }
   else if(customer.checked == false)
   {
	   for(var i=1;i<=13;i++){
	    document.getElementById('customervar'+i).checked=false;}
   }
} 
function checkall4()
{
	var sales = document.getElementById('sales');
   if(sales.checked == true)
   {
	   for(var i=1;i<=9;i++){
	   document.getElementById('salesvar'+i).checked=true;}
   }
   else if(sales.checked == false)
   {
	   for(var i=1;i<=9;i++){
	    document.getElementById('salesvar'+i).checked=false;}
   }
} 
function checkall5()
{
	var configuration = document.getElementById('configuration');
   if(configuration.checked == true)
   {
	   for(var i=1;i<=15;i++){
	   document.getElementById('configurationvar'+i).checked=true;}
   }
   else if(configuration.checked == false)
   {
	   for(var i=1;i<=15;i++){
	    document.getElementById('configurationvar'+i).checked=false;}
   }
} 
function checkall6()
{
	var report = document.getElementById('report');
   if(report.checked == true)
   {
	   for(var i=1;i<=15;i++){
	   document.getElementById('reportvar'+i).checked=true;}
   }
   else if(report.checked == false)
   {
	   for(var i=1;i<=15;i++){
	    document.getElementById('reportvar'+i).checked=false;}
   }
} 


</script>

<script>
function validation()
{
	// username password name address email phone rolegroup
	var rolegroup = document.getElementById('rolegroup').value;
	var username = document.getElementById('username').value;
	var password = document.getElementById('password').value;
	var usercode = document.getElementById('usercode').value;
	var name = document.getElementById('name').value;
	var address = document.getElementById('address').value;
	var email = document.getElementById('email').value;
	var phone = document.getElementById('phone').value;
	var rolegroup = document.getElementById('rolegroup').value;
	var userid = document.getElementById('userid').value;
	
	
	document.getElementById('eusername').innerHTML="";
	document.getElementById('epassword').innerHTML="";
	document.getElementById('eusercode').innerHTML="";
	document.getElementById('ename').innerHTML="";
	document.getElementById('eaddress').innerHTML="";
	document.getElementById('eemail').innerHTML="";
	document.getElementById('ephone').innerHTML="";
	document.getElementById('erolegroup').innerHTML="";
	var err=0;
	
	if(rolegroup == ""){++err; document.getElementById('erolegroup').innerHTML="Required";}
	
	if(username == ""){++err; document.getElementById('eusername').innerHTML="Required";}
	else if(username.toLowerCase() == "admin"){++err; document.getElementById('eusername').innerHTML="Wrong, change it";}
	else if(username.length > 100){++err; document.getElementById('eusername').innerHTML="Maximum lenght exceed";}
	else
	{
		var userexist=0;
		<?php 
		if(isset($users)&& count($users)>0)
		{
			foreach($users as $u)
			{
		?>
			 if(username == '<?php echo $u->username; ?>' && userid !='<?php echo $u->id;?>')
			 {
				 userexist=1;
			 }
		<?php
			}
		}
		 ?>
		 if(userexist==1){++err; document.getElementById('eusername').innerHTML="already exist";}
	}
	
	if(password == ""){++err; document.getElementById('epassword').innerHTML="Required";}
	else{if(password.length > 20){++err; document.getElementById('epassword').innerHTML="Maximum lenght exceed";}}
	
	if(usercode == ""){++err; document.getElementById('eusercode').innerHTML="Required";}
	else if(usercode.length > 50){++err; document.getElementById('eusercode').innerHTML="Maximum lenght exceed";}
	else if(isNaN(usercode)){++err; document.getElementById('eusercode').innerHTML="Number only";}
	else
	{
		var usercodeexist=0;
		<?php 
		if(isset($users)&& count($users)>0)
		{
			foreach($users as $u)
			{
		?>
			 if(usercode == '<?php echo $u->access_code; ?>' && userid !='<?php echo $u->id;?>')
			 {
				 usercodeexist=1;
			 }
		<?php
			}
		}
		 ?>
		 if(usercodeexist==1){++err; document.getElementById('eusercode').innerHTML="already exist";}
	}
	
	if(name == ""){++err; document.getElementById('ename').innerHTML="Required";}
	else{if(name.length > 150){++err; document.getElementById('ename').innerHTML="Maximum lenght exceed";}}
	
	if(address == ""){++err; document.getElementById('eaddress').innerHTML="Required";}
	else{if(address.length > 255){++err; document.getElementById('eaddress').innerHTML="Maximum lenght exceed";}}
	
	if(email == ""){++err; document.getElementById('eemail').innerHTML="Required";}
	else{
			if(email.length > 150){++err; document.getElementById('eemail').innerHTML="Maximum lenght exceed";}
			else{
				var atpos = email.indexOf("@");
				var dotpos = email.lastIndexOf(".");
				if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length) 
				{
					err++; document.getElementById('eemail').innerHTML='Invalid email';
				}
			}
		}
	
	if(phone == ""){++err; document.getElementById('ephone').innerHTML="Required";}
	else{if(phone.length > 40){++err; document.getElementById('ephone').innerHTML="Maximum lenght exceed";}}
	
	if(err==0)
	{
		return true;
	}
	return false;
}
</script>

<style>
	.style-1{width:270px;height:30px;margin-bottom:10px;}
	.em{color:#F00;}
	.sm{color:#008000;font-size:20px;}
	.tbl{border:1px solid #FFF;margin-bottom:20px;}
.tbl .row {
    margin-right: -15px;
    margin-left: -15px;
    padding: 5px;
}


 /* Dropdown Button */
.dropbtn {
    background-color: #4CAF50;
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
}

/* The container <div> - needed to position the dropdown content */
.dropdown {
    position: relative;
    display: inline-block;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f1f1f1;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {background-color: #ddd}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
    display: block;
}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {
    background-color: #3e8e41;
} 
a.vu{float:right;margin-right: 20px;background: #00A65A;padding: 5px;color: #fff;border-radius: 15px;margin-top: 5px;}
a.vu:hover{float:right;margin-right: 20px;background: #F99706;padding: 5px;color: #fff;border-radius: 15px;margin-top: 5px;}
</style>


 <style>
		.dropdown1{position: relative;float:left;width:100%;cursor:pointer;font-weight: 200;padding: 0;background: #fff !important;color:#000;margin-bottom:10px;}
		.dropdown1 button{font-size:14px !important;background:#fff !important;color:000 !important;}
		.dropdown1 .btn{padding:0 0 0 10px;background: #fff;color: #000;box-shadow: none !important;border:1px solid #CCC;width:100%;text-align:left;}
		.dropdown1 .dropdown-toggle::after {display: inline-block;width: 0;height: 0;margin-left: .255em;vertical-align: .255em;content: "";border-top: .3em solid;border-right: .3em solid transparent;border-bottom: 0;border-left: .3em solid transparent;float: right;margin-right: 5px;position: absolute;top: 22px;right: 10px;}
		.dropdown1.open div div{width:100%;height:30px;line-height:30px;}
		.dropdown1.open div div a{text-decoration:none !important;text-align:left;color:#6c6969;width:100%;background:#fff;display:block;padding-left:10px;font-size:14px;}
		.dropdown1.open div div a:hover{text-decoration:none !important;text-align:left;color:#3CF;}
		.slectedshortname{font-weight:normal;}
		.btn-secondary{color:#000;}
		.dropdown1 .dropdown-toggle::after {display: inline-block;width: 0;height: 0;margin-left: .255em;vertical-align: .255em;content: "";border-top: .3em solid;border-right: .3em solid transparent;border-bottom: 0;border-left: .3em solid transparent;float:right;margin-right:5px;}
	</style>

<div class="container-fluid">
	<div class="row">
    	<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
<a href="<?php echo 'acl_management/view_user';?>" class="vu">View User</a>

<?php 
	if(isset($_GET['msg'])&& $_GET['msg'] !=""){echo '<label class="sm">'.$_GET['msg'].'</label>';} 
	else
	{
		if(isset($_GET['emsg'])&& $_GET['emsg'] !=""){echo '<label class="em">Errors:<br><br>'.$_GET['emsg'].'</label>';}
	}
?>


<h2>User Create Form</h2>
<?php 
	$uid="";
	if(!isset($selectedrole) && isset($selecteduser)&& count($selecteduser)>0 )
	{
		foreach($selecteduser as $su)
		{
			$uid = $su->id;
			$rolegroup = $su->rolegroup;
			$perm = $su->permission;
			if(strpos($perm,','))
			{
				$perm = explode(',',$perm);
			}
		}
	}
	else
	{
		foreach($selecteduser as $su)
		{
			$uid = $su->id;
			//$rolegroup = $su->rolegroup;
		}
	}
?>
<form action="<?php echo 'acl_management/update_user'?>" method="post" onsubmit="return validation()">
	<input type="hidden" name="rolegroup" id="rolegroup" value="<?php if(isset($rolegroup)&& $rolegroup !=""){ echo $rolegroup;}?>" />
    <input type="hidden" name="userid" id="userid" value="<?php echo $uid;?>" />
	<table>
    	<tr>
        	<td>
            	<b>Role Group</b>&nbsp;<span id="erolegroup" class="em"></span><br>
                
						<div class="dropdown1">
                        	<span id="erolegroup"></span>
						  <button class="btn  dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php if(isset($rolegroup)&& $rolegroup !=""){ echo '<span style="color:#999;">'.$rolegroup.'</span>';}?>
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="width:100%;">
						   <div> 
                           	<?php 
								if(isset($roles)&& count($roles)>0)
								{
									foreach($roles as $r)
									{
									
							?>
                           		<a class="dropdown-item" href="<?php echo 'acl_management/useredit_form_with_permission/'.$r->id.'/'.urlencode($r->group).'/'.$uid;?>" ><?php echo $r->group?></a>
                             <?php 
							
									}
								}
							?>
                            </div>
						  </div>
						</div>
            </td>
        </tr>
    	<tr>
        	<td>  
            	Username &nbsp;<span id="eusername" class="em"></span><br>
                <input type="text" name="username" id="username" class="style-1 form-control" value="<?php if($su->username) echo $su->username?>">
            </td>
        </tr>
        <tr>
        	<td>
            	Password&nbsp;<span id="epassword" class="em"></span><br />
                <input type="password" name="password" id="password" class="style-1 form-control">
            </td>
        </tr>
        <tr>
        	<td>  
            	User code &nbsp;<span id="eusercode" class="em"></span><br>
                <input type="password" name="usercode" id="usercode" value="<?php if($su->access_code) echo $su->access_code ?>" class="style-1 form-control">
            </td>
        </tr>
        <tr>
        	<td>
            	Name&nbsp;<span id="ename" class="em"></span><br />
                <input type="text" name="name" id="name" value="<?php if($su->name) echo $su->name ?>" class="style-1 form-control">
            </td>
        </tr>
        <tr>
        	<td>
            	Address&nbsp;<span id="eaddress" class="em"></span><br />
                <textarea name="address" id="address" style="width:270px;" class="form-control"><?php if($su->address) echo $su->address ?></textarea>
            </td>
        </tr>
        
        <tr>
        	<td>
            	Email&nbsp;<span id="eemail" class="em"></span><br />
                <input type="text" name="email" id="email" value="<?php if($su->email) echo $su->email ?>" class="style-1 form-control">
            </td>
        </tr>
        
        <tr>
        	<td>
            	Phone&nbsp;<span id="ephone" class="em"></span><br />
                <input type="text" name="phone" id="phone" value="<?php if($su->phone) echo $su->phone ?>" class="style-1 form-control">
            </td>
        </tr>
        
        <tr>
        	<td>
            	<br>
                <input type="submit" value="Update" style="width:80px;background:#00A65A;color:#fff;padding:5px;font-weight:bold;">
            </td>
        </tr>
    </table>


</div>

<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
	<table>
            
            <tr>
                <td>
                    
                    
                        <!-------  Admin ------------------------------------------->
                        <h3>Admin&nbsp;<input type="checkbox" id="admin" onclick="checkall1()" /></h3>
                        <table class="tbl col-lg-12">
                        	<tr>
                            	<td>
                                	
                                	<div class="row">
                                	<?php 
										$a=0;
										foreach($permission as $p)
										{
											if($p->subject == 'admin')
											{
												$a++;
											
									?>
										<div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                                        <input type="checkbox" name="permissionlist[]" id="adminvar<?php echo $a;?>" value="<?php echo $p->id?>" <?php if(is_array($perm)&& in_array($p->id,$perm)){echo 'checked="checked"';}else if($p->id == $perm){echo 'checked="checked"';}?> />&nbsp;<?php echo '<span style="color:#000">'.$p->name.'</span>'?>
                                        </div>
									<?php 
											}
										}
									?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <!-------  Catalog -------------------------------------------->
                        <h3>Catalog&nbsp;<input type="checkbox" id="catalog" onclick="checkall2()"/></h3>
                        <table class="tbl col-lg-12">
                        	<tr>
                            	<td>
                                	<div class="row">
                                	<?php 
										$b=0;
										foreach($permission as $p)
										{
											if($p->subject == 'catalog')
											{
												$b++;
									?>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
										<input type="checkbox" name="permissionlist[]" id="catalogvar<?php echo $b;?>" value="<?php echo $p->id?>" <?php if(is_array($perm)&& in_array($p->id,$perm)){echo 'checked="checked"';}else if($p->id == $perm){echo 'checked="checked"';}?> />&nbsp;<?php echo '<span style="color:#000">'.$p->name.'</span>'?>
                                    </div>
									<?php 
											}
										}
									?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        
                        <!-------  Customer ------------------------------------------->
                        <h3>Customers&nbsp;<input type="checkbox" id="customer" onclick="checkall3()" /></h3>
                         <table class="tbl col-lg-12">
                        	<tr>
                            	<td>
                                	<div class="row">
                                	<?php 
										$c=0;
										foreach($permission as $p)
										{
											if($p->subject == 'customer')
											{
												$c++;
									?>
                                    	<div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
										<input type="checkbox" name="permissionlist[]" id="customervar<?php echo $c;?>" value="<?php echo $p->id?>" <?php if(is_array($perm)&& in_array($p->id,$perm)){echo 'checked="checked"';}else if($p->id == $perm){echo 'checked="checked"';}?> />&nbsp;<?php echo '<span style="color:#000">'.$p->name.'</span>'?>
                                        </div>
									<?php 
											}
										}
									?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        
                        <!-------  Sale ----------------------------------------------->
                        <h3>Sales&nbsp;<input type="checkbox" id="sales" onclick="checkall4()" /></h3>
                         <table class="tbl col-lg-12">
                        	<tr>
                            	<td>
                                	<div class="row">
                                	<?php
										$d=0; 
										foreach($permission as $p)
										{
											if($p->subject == 'sales')
											{
												$d++;
									?>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
										<input type="checkbox" name="permissionlist[]" id="salesvar<?php echo $d;?>" value="<?php echo $p->id?>" <?php if(is_array($perm)&& in_array($p->id,$perm)){echo 'checked="checked"';}else if($p->id == $perm){echo 'checked="checked"';}?> />&nbsp;<?php echo '<span style="color:#000">'.$p->name.'</span>'?>
                                    </div>
									<?php 
											}
										}
									?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        
                        <!-------  Configuration -------------------------------------->
                        <h3>Configuration&nbsp;<input type="checkbox" id="configuration" onclick="checkall5()"/></h3>
                        <table class="tbl col-lg-12">
                        	<tr>
                            	<td>
                                	<div class="row">
                                	<?php 
										$e=0;
										foreach($permission as $p)
										{
											if($p->subject == 'configuration')
											{
												$e++;
									?>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
										<input type="checkbox" name="permissionlist[]" id="configurationvar<?php echo $e;?>" value="<?php echo $p->id?>" <?php if(is_array($perm)&& in_array($p->id,$perm)){echo 'checked="checked"';}else if($p->id == $perm){echo 'checked="checked"';}?> />&nbsp;<?php echo '<span style="color:#000">'.$p->name.'</span>'?>
                                    </div>
									<?php 
											}
										}
									?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        
                        <!-------  Reports -------------------------------------------->
                        <h3>Reports&nbsp;<input type="checkbox" id="report" onclick="checkall6()"/></h3>
                          <table class="tbl col-lg-12" >
                        	<tr>
                            	<td>
                                <div class="row">
                                	<?php 
										$f=0;
										foreach($permission as $p)
										{
											if($p->subject == 'report')
											{
												$f++;
									?>
                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
										<input type="checkbox" name="permissionlist[]" id="reportvar<?php echo $f;?>" value="<?php echo $p->id?>" <?php if(is_array($perm)&& in_array($p->id,$perm)){echo 'checked="checked"';}else if($p->id == $perm){echo 'checked="checked"';}?> />&nbsp;<?php echo '<span style="color:#000">'.$p->name.'</span>'?>
                                    </div>
									<?php 
											}
										}
									?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                </td>
            </tr>
        </table>
        
        
        </form>
</div>
</div>
</div>


