

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
	   for(var i=1;i<=36;i++){
	   document.getElementById('catalogvar'+i).checked=true;}
   }
   else if(catalog.checked == false)
   {
	   for(var i=1;i<=36;i++){
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

<style>
.tbl{border:1px solid #FFF;margin-bottom:20px;}
.tbl .row {
    margin-right: -15px;
    margin-left: -15px;
    padding: 5px;
}
</style>
<div class="container-fluid">
	
  <div class="row">
    <div class="col-md-12 col-sm-12">
    <a href="<?php echo 'acl_management/view_roll';?>" style="float:right;margin-right:20px;background:#00A65A;margin-top:10px;color:#fff;font-weight:bold;padding:5px 10px;">View Role</a>
     
    <h2>Role Create Form</h2>
    <form action="<?php echo 'acl_management/create_role'?>" method="post">
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
										<div class="col-lg-2 col-md-3 col-sm-4 col-xs-12"><input type="checkbox" name="permissionlist[]" id="adminvar<?php echo $a;?>" value="<?php echo $p->id?>" />&nbsp;<?php echo '<span style="color:#000">'.$p->name.'</span>'?></div>
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
										<input type="checkbox" name="permissionlist[]" id="catalogvar<?php echo $b;?>" value="<?php echo $p->id?>" />&nbsp;<?php echo '<span style="color:#000">'.$p->name.'</span>'?>
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
										<input type="checkbox" name="permissionlist[]" id="customervar<?php echo $c;?>" value="<?php echo $p->id?>" />&nbsp;<?php echo '<span style="color:#000">'.$p->name.'</span>'?>
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
										<input type="checkbox" name="permissionlist[]" id="salesvar<?php echo $d;?>" value="<?php echo $p->id?>" />&nbsp;<?php echo '<span style="color:#000">'.$p->name.'</span>'?>
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
										<input type="checkbox" name="permissionlist[]" id="configurationvar<?php echo $e;?>" value="<?php echo $p->id?>" />&nbsp;<?php echo '<span style="color:#000">'.$p->name.'</span>'?>
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
										<input type="checkbox" name="permissionlist[]" id="reportvar<?php echo $f;?>" value="<?php echo $p->id?>" />&nbsp;<?php echo '<span style="color:#000">'.$p->name.'</span>'?>
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
            
            <tr>
                <td>
                    <br><br>
                    Enter Role Group Name:<br>
                    <input type="text" name="rolegroup" id="rolegroup" style="width:270px;" class="form-control">
                </td>
            </tr>
            
            <tr>
                <td>
                    <br>
                    <input type="submit" value="Save" style="width:80px;background:#00A65A;color:#fff;padding:5px;font-weight:bold;" >
                </td>
            </tr>
        </table>
    </form>
    </div>
   </div>
</div>    