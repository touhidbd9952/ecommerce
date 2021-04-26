
<style>
.about_header{text-align:center;font-size: 32px;line-height: 1.25;font-weight: 300;margin-bottom: 2rem;}
.addresss{text-align:left;font-size:20px;color:#73B6E1 !important;text-decoration:underline}
</style>

<?php 
$terms="Help center";
$settingdata = $this->db->query("select * from t_settings")->result();
foreach($settingdata as $d)
{
	if($d->name =="helpcenter" && $d->name !=""){$helpcenter =$d->value; }
}

?>

<!--start-breadcrumbs-->
	<div class="breadcrumbs">
		<div class="container">
			<div class="breadcrumbs-main">
				<ol class="breadcrumb">
					<li><a href="home">Home</a></li>
					<li class="active"> Help center</li>
				</ol>
			</div>
		</div>
	</div>
	<!--end-breadcrumbs-->
	<!--contact-start-->
	<div class="aboutus" style="margin-bottom:80px">
		<div class="container">
			<!--<div class="contact-top heading">
				
			</div>-->
				<div class="contact-text">
				
					<div class="col-md-12 contact-right">
                    
                    	<div style="text-align:justify;">
                        <?php if($helpcenter !="") echo $helpcenter;?>

                        </div>
					</div>	
                    
                    
                    
					<div class="clearfix"></div>
				</div>
		</div>
	</div>
	<!--contact-end-->