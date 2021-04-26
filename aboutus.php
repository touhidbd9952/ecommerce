
<style>
.about_header{text-align:center;font-size: 32px;line-height: 1.25;font-weight: 300;margin-bottom: 2rem;}
.addresss{text-align:left;font-size:20px;color:#73B6E1 !important;text-decoration:underline}
</style>

<?php 
$companyname="Company Name";
$bout="About Company";
$companyaddress ="Company Address";
$phone = "Company Phone";
$email="Company Email";
$settingdata = $this->db->query("select * from t_settings")->result();
foreach($settingdata as $d)
{
	if($d->name =="Company Name" && $d->name !=""){$companyname =$d->value; }
	if($d->name =="About Company work" && $d->name !=""){$bout =$d->value; }
	if($d->name =="Address" && $d->name !=""){$companyaddress =$d->value; }
	if($d->name =="phone" && $d->name !=""){$phone =$d->value; }
	if($d->name =="servermail" && $d->name !=""){$email =$d->value; }
}

?>

<!--start-breadcrumbs-->
	<div class="breadcrumbs">
		<div class="container">
			<div class="breadcrumbs-main">
				<ol class="breadcrumb">
					<li><a href="home">Home</a></li>
					<li class="active">Aboutus</li>
				</ol>
			</div>
		</div>
	</div>
	<!--end-breadcrumbs-->
	<!--contact-start-->
	<div class="aboutus">
		<div class="container">
			<!--<div class="contact-top heading">
				
			</div>-->
				<div class="contact-text">
				
					<div class="col-md-12 contact-right">
                    <h2 class="about_header">About</h2> 
                    	<div style="text-align:justify;">
                        <?php if($bout !="") echo $bout;?>

                        </div>
						<div class="address" style="margin-bottom:5em">
							<div>
                            	<a class="addresss">Address:</a><br />
								<?php echo $companyname;?>,&nbsp;<?php echo $companyaddress;?>,&nbsp;<?php if($phone !="") echo 'Tel: '.$phone;?>,&nbsp;<?php if($email !="") echo 'Email: '.$email;?>
                            </div>
						</div>
					
					</div>	
                    
                    
                    
					<div class="clearfix"></div>
				</div>
		</div>
	</div>
	<!--contact-end-->