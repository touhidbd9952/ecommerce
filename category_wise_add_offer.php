<link rel="stylesheet" href="css/mystyle.css" />
<script src="js/myjs.js"></script>





<style type="text/css">
table{border-collapse:collapse;}
.tableHeader{font-size:24px;color:#000;padding:5px 0px;}
.tdStyle{text-align:center;}
.btnStyle{padding:0px 20px;}
#message1{color:green; font-size:24px;}
#message2{color:red; font-size:24px;}

</style>

<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>

<script>
function confirmationDelete(anchor)
{
   var conf = confirm('Are you sure want to delete this record?');
   if(conf)
      window.location=anchor.attr("href");
}
function valid()
{
	var categoryid = document.getElementById('categoryid').value;
	var offerid = document.getElementById('offerid').value;
	var offerdiscount = document.getElementById('offerdiscount').value;
	document.getElementById('ecategoryid').style.cssText="color:black;font-size:12px";
	document.getElementById('eofferid').style.cssText="color:black;font-size:12px";
	document.getElementById('eofferdiscount').style.cssText="color:black;font-size:12px";
	var err=0;
	if(categoryid == 0){err++;document.getElementById('ecategoryid').style.cssText="color:red;font-size:20px";}
	if(offerid == 0){err++;document.getElementById('eofferid').style.cssText="color:red;font-size:20px";}
	if( offerid != "-1")
	{
		if(offerdiscount == ""){err++;document.getElementById('eofferdiscount').style.cssText="color:red;font-size:20px";}
		else if(offerdiscount != ""){if(isNaN(offerdiscount)){err++; document.getElementById('eofferdiscount').style.cssText="color:red;font-size:20px";}}
	}
	if(err==0)
	{
		return true;
	}
	return false;
}
</script>

<div class="container-fluid">
	<div class="row">
    	<div class="col-md-8 col-sm-8">	
                <h3>Catalog &raquo; Promotion &raquo; Add/Remove Campain &raquo; Category wise</h3>
                
		
		<form name="myform" action="<?php echo 'offer_management/offer_add_by_category'?>" id="register-form" method="post" enctype="multipart/form-data" onsubmit="return valid()">
			
			
            	<?php
                	if(isset($_GET['sk'])&& !empty($_GET['sk']))echo '<label class="ms mymsg fl-r">'.$_GET['sk'].'</label>';
					if(isset($_GET['esk'])&& !empty($_GET['esk']))echo '<label class="ems mymsg fl-r">'.$_GET['esk'].'</label>';
				?>	
            <table>
            <tr>
            	<td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Category<span id="ecategoryid">*</span></span></span>
                		<select name="categoryid" id="categoryid" class="form-control input-lg">
                        	<option value="0">Select Category</option>
                        	<?php 
							if(count($category)>0)
							{
								foreach($category as $c)
								{
							?>
                        	<option value="<?php echo $c->id;?>"><?php echo $c->name;?></option>
                            <?php
								}
							}
							?>
                        </select>
                    </div>
                    <!-- End .input-group --><br>
                </td>
            </tr>
            <tr>
            	<td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Offer<span id="eofferid">*</span></span></span>
                		<select name="offerid" id="offerid" class="form-control input-lg">
                        	<option value="0">Select Offer</option>
                        	<?php 
							if(count($offers)>0)
							{
								foreach($offers as $d)
								{
							?>
                        		<option value="<?php echo $d->id;?>"><?php echo $d->title;?></option>
                            <?php
								}
							}
							?>
                            <option value="-1" style="color:#F00">Remove from offer</option>
                        </select>
                    </div>
                    <!-- End .input-group --><br>
                </td>
            </tr>
            <tr>
            	<td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Discount<span id="eofferdiscount">*</span></span></span>
                		<input type="text" name="offerdiscount" id="offerdiscount" class="form-control input-lg"/>	
                    </div>
                    <!-- End .input-group --><br>
                </td>
            </tr>
           
            <tr>
                <td>						
                    &nbsp;<input type="submit" name="sub" value="Update Offer" class="btn btn-custom-2 btn-lg md-margin" >
                </td>
            </tr>
            </table>
		</form>
        </div>
 	</div>             
</div>