<?php 
		$companyname="";
		$companyaddress ="Company Address";
		$phone = "Company Phone";
		$email="Company Email";
		$footerCopyrighttext="";
		$logo = "";
		$favicon ="";
		$band="";
		$logo_or_brand="";
		$facebook="";
		$twitter="";
		$googleplus="";
		$settingdata = $this->db->query("select * from t_settings")->result();
		foreach($settingdata as $d)
		{
			if($d->name =="Company Name" && $d->name !=""){$companyname =$d->value; }
			if($d->name =="Address" && $d->name !=""){$companyaddress =$d->value; }
			if($d->name =="phone" && $d->name !=""){$phone =$d->value; }
			if($d->name =="servermail" && $d->name !=""){$email =$d->value; }
			if($d->name=='Footer Copyright text'){$footerCopyrighttext=$d->value;}
			if($d->name=='brand or logo'){$logo_or_brand=$d->value;}
			
			if($d->name=='facebook'){$facebook=$d->value;}
			if($d->name=='twitter'){$twitter=$d->value;}
			if($d->name=='google plus'){$googleplus=$d->value;}
		}
	
?>
<!DOCTYPE html>
<html><head>
<base href="<?php echo base_url();?>">
<title><?php if($companyname !=""){echo $companyname;}else{echo "Ecom";}?> | <?php if($menu !=""){echo $menu;}?> </title>

<link rel="icon" href="<?php echo 'img/favicon.png'?>" type="image/gif">

<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
<!--jQuery(necessary for Bootstrap's JavaScript plugins)-->
<script src="js/theme1/jquery-1.js"></script>
<!--Custom-Theme-files-->
<!--theme-style-->
<link href="css/theme1/style.css" rel="stylesheet" type="text/css" media="all">	
<!--//theme-style-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="keywords" content="Luxury Watches Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--start-menu-->
<script src="js/theme1/simpleCart.js"> </script>
<link href="css/theme1/memenu.css" rel="stylesheet" type="text/css" media="all">
<script type="text/javascript" src="js/theme1/memenu.js"></script>
<script src="js/theme1/global-shortcut.js"></script>
<script>$(document).ready(function(){$(".memenu").memenu();});</script>	

<!--<script src="js/jquery"></script>-->
<!--dropdown-->
<script src="js/theme1/easydropdown.js"></script>	

</head>
<style>
/*#mhide{display:none !important}*/
.callbacks1_tabs{display:none !important;}
#reviewbox{display:none;padding-top:80px;}
.header-text{font-size: 1.7em;color: #73b6e1;margin: 0 0 0 0;font-family: 'BreeSerif-Regular';font-weight:500;}
input[type="submit"] {
    background: #73B6E1;
    color: #FFF;
    font-size: 1em;
    padding: 0.5em 1.2em;
    transition: 0.5s all;
    -webkit-transition: 0.5s all;
    -moz-transition: 0.5s all;
    -o-transition: 0.5s all;
    display: inline-block;
    text-transform: uppercase;
    border: none;
    outline: none;
    font-family: 'Lato-Regular';
}
.nhelp{
    display: block;
font-size: 12px;
overflow: hidden;
white-space: nowrap;
text-transform: uppercase;
font-weight: 400;
color: #fff;
}
.dropdown.open div{min-width:100px;margin-left:-20px;}
.dropdown.open{min-width:125px;}
.dropdown .selected{overflow:visible !important;}
.dropdown .carat{display:none !important;}
#trackorder{width:300px;height:200px;position:fixed;top:65px;left:10px;z-index:10000;display:none;background: #FFF;}
.em{color:#F00;}
input[type="submit"],.button {
    background: #73B6E1;
    color: #FFF;
    font-size: 1em;
    padding: 0.5em 1.2em;
    transition: 0.5s all;
    -webkit-transition: 0.5s all;
    -moz-transition: 0.5s all;
    -o-transition: 0.5s all;
    display: inline-block;
    text-transform: uppercase;
    border: none;
    outline: none;
    font-family: 'Lato-Regular';
	text-decoration:none;
}
.information{padding-top:48px;}
.information h3{padding-top:20px;}
.latestproducts {margin-top: 1em;}
.top-header{height:51px;}
@media(max-width:460px)
{
	h3{font-size:18px;}
	.information{padding-top:15px;}
	.information h3{padding-top:0px;margin-top:10px;margin-bottom:10px;}
	.latestproducts {margin-top: 2em;}
	.product-left:nth-child(3), .product-left:nth-child(4) {margin-top: 0px;}
	.infor ul li a p span {width: 20px;height: 20px;background-size: 300%;}
	.infor ul li a p span.twit {background-position: -20px 0px;}
	.infor ul li a p span.google {background-position: -40px 0px;}
	.box{margin-right:15px;}
	.drop {margin-top: 1px;}
	.sidemenumob{position: absolute !important;top:20px !important;}
}
</style>
<script>
function frackorder()
{
	document.getElementById('trackorder').style.display="block";
}

function closetraceorder()
{
	document.getElementById('trackorder').style.display="none";
}


</script>


  
<style>
.dropdown.open {  }
.dropdown .btn {padding:0px;background:#000;color:#FFF;box-shadow:none !important;}
.dropdown .btn .dropdown-menu a{color:#000;}
.dropdown.open div{min-width:100px !important;min-height:67px !important;top:30px !important;background:#000 !important;border-radius:0px;margin-left:-10px;}
.dropdown.open div a{line-height:30px;color:#FFF;text-decoration:none;margin-bottom:10px;padding-left:5px;}
.dropdown .dropdown-item{width:70px;}
.register, .btn {color: #ffffff;padding: 0 22px;font-size:12px;text-align: center;text-decoration: none;height: 27px;display: inline-block;line-height: 0px;font-family: 'Lato', sans-serif;font-weight: bold;border-radius: 3px;background: #3c8dbc;background: -moz-linear-gradient(top, #3c8dbc 0%, #3c8dbc 100%);background: -webkit-linear-gradient(top, #3c8dbc 0%,#3c8dbc 100%);background: linear-gradient(to bottom, #3c8dbc 0%,#3c8dbc 100%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3c8dbc', endColorstr='#3c8dbc',GradientType=0 );box-shadow: inset 0px 1px 0px 0px #AAAEB1;margin:0px;}
.dropdown, .dropup { position: relative;top: -5px;}
.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
    font-family: inherit;
    font-weight: 500;
    line-height: 1.1;
    color: inherit;
}
.box1 {float: left;width: 45.5% !important;}
@media(min-width:678px){
.top-header {height: 51px;line-height: 35px;}
}
@media(max-width:460px)
{
  .box1 {float: left;width: 250px !important;}
}
</style>
<body cz-shortcut-listen="true"> 
	<!--top-header-->
	<div class="top-header">
		<div class="container">
			<div class="top-header-main">
				<div class="col-md-6 top-header-left" style="padding-left:0px;padding-right:0px;">
                	<div class="box" style="margin-right:15px;width:80px;">
							<div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:8px;color:#FFF;">
                                Need Help&nbsp;&nabla; 
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="<?php echo 'main/helpcenter'?>" style="padding-bottom:5px;">Help Center</a>
                                <a class="dropdown-item" href="javascript:" style="border-bottom:none;" onClick="frackorder()">Track Order</a>
                              </div>
                    		</div>
						</div>
                        
                        <div class="box1">
                	<?php 
						$cusfirstname = $this->session->userdata('cusfirstname');
						$cuslastname = $this->session->userdata('cuslastname');
						$name="";
						if($cusfirstname !="")
						{
							if($cuslastname !=""){$name=$cusfirstname.' '.$cuslastname;}else{$name=$cusfirstname;}
							echo '<a href="customercontroller/account_controlpanel"><span style="color:#FFF">'.$name.'</span></a>';
					?>
                    		<a href="<?php echo 'customercontroller/logout'?>" style="margin-left:30px;width:60px;height:30px;display:inline;text-decoration:none;color:#F90;">Logout</a>
                    <?php		
						}
					?>
                    </div>
                    
				</div>
                
                
               <!-------////////Wishlish/////////---------->
                
                <?php 
					$cusid = $this->session->userdata('cusid');
					$totalwish="";
					if($cusid !="")
					{
						$customerdata = $this->db->query("select * from t_customer where id ='".$cusid."' ")->result();
						$cuswishlist = "";
						if(count($customerdata)>0)
						{
							$cuswishlist = $customerdata[0]->wish_list;
							if($cuswishlist !="")
							{
								if(strpos($cuswishlist,','))
								{
									$cuswishlist = explode(',',$cuswishlist);
									$totalwish=count($cuswishlist);
								}
								else{$totalwish=1;}
							}
						}
					}
				?>
                
				<div class="col-md-4 top-header-left">
					<div class="cart box_1">
                    	<style>
						.Cart_total {
							font-size: 13px;box-sizing: border-box;color: #fff;font-family: 'Lato-Regular';line-height: 1.42857143; text-align:right;
						}
						</style>
                        <?php if($totalwish !="")
						{ 
						?>
						<a href="<?php if($totalwish !=""){ echo 'main/show_wishlist';}else{echo 'javascript:';}?>">
							 <div class="total">
								<span class="Cart_total"><?php echo '&nbsp;<i class="fa fa-heart"></i> Wishlist: '.$totalwish;?></span>
                             </div>
						</a>
                        <?php 
						}
						?>
						<div class="clearfix"> </div>
					</div>
				</div>
                
                
                
                
                <!-------/////////////////---------->
                
                
                
                <?php 
					$jsondata = $this->session->userdata('cartdata'); 
					
					$spid ="";$spcolor ="";$spsize ="";$spqty ="";$discount=0;$totalprice=0;
					if($jsondata !="")
					{
						$jsondata = json_decode($jsondata);
						$spid = $jsondata->pid; 
						$spimg = $jsondata->pimg;
						$spcolor = $jsondata->pcolor; 
						$spsize = $jsondata->psize; 
						$spqty = $jsondata->pqty; //echo $spqty;
						
						if (strpos($spid, ',') !== false) 
						{
								$spid = explode(',',$spid);
						}
						if (strpos($spimg, ',') !== false) 
						{
								$spimg = explode(',',$spimg);
						}
						if (strpos($spcolor, ',') !== false) 
						{
								$spcolor = explode(',',$spcolor);
						}	
						if (strpos($spsize, ',') !== false) 
						{
								$spsize = explode(',',$spsize);
						}
						if (strpos($spqty, ',') !== false) 
						{
								$spqty = explode(',',$spqty);
						}
						if(count($spid)>1)
						{
							for($i=0;$i<count($spid);$i++)
							{
								$product = $this->db->query("select * from product where id='".$spid[$i]."' ")->result();
								foreach($product as $p)
								{
									if(($p->offerid =="")||($p->offerid =="0"))
									{
										$discount +=$p->discount;
										$totalprice += ($p->regularprice -(($p->regularprice*$p->discount)/100)) * $spqty[$i];
									}
									else
									{
										$offerdata = $this->db->query("select * from t_offer where id='".$p->offerid."' ")->result();
										$startdate="";$enddata="";
										foreach($offerdata as $od)
										{
											$startdate = $od->startdate;
											$enddata = $od->enddate;
										}
										$curdate = date('Y-m-d'); 
										if($curdate >= $startdate && $curdate <= $enddata)
										{
											$discount +=$p->offerdiscount;
											$totalprice += ($p->regularprice -(($p->regularprice*$p->offerdiscount)/100)) * $spqty[$i];
										}
										else
										{
											$discount +=$p->discount;
											$totalprice += ($p->regularprice -(($p->regularprice*$p->discount)/100)) * $spqty[$i];
										}
									}
								}
							}
						}
						else
						{
							$product = $this->db->query("select * from product where id='".$spid."' ")->result(); 
							foreach($product as $p)
							{
								if(($p->offerid =="")||($p->offerid =="0"))
								{
									$totalprice += ($p->regularprice -(($p->regularprice*$p->discount)/100)) * $spqty;
								}
								else
								{
									$offerdata = $this->db->query("select * from t_offer where id='".$p->offerid."' ")->result();
									$startdate="";$enddata="";
									foreach($offerdata as $od)
									{
										$startdate = $od->startdate;
										$enddata = $od->enddate;
									}
									$curdate = date('Y-m-d'); 
									if($curdate >= $startdate && $curdate <= $enddata)
									{
										$totalprice += ($p->regularprice -(($p->regularprice*$p->offerdiscount)/100)) * $spqty;
									}
									else
									{
										$totalprice += ($p->regularprice -(($p->regularprice*$p->discount)/100)) * $spqty;
									}
								}
							}
						}
					}
				?>
                
				<div class="col-md-2 top-header-left">
					<div class="cart box_1">
                    	<style>
						.Cart_total {
							font-size: 13px;box-sizing: border-box;color: #fff;font-family: 'Lato-Regular';line-height: 1.42857143; text-align:right;
						}
						</style>
						<a href="<?php if($jsondata !=""){ echo 'main/checkout';}?>">
							 <div class="total">
								<span class="Cart_total"><?php echo number_format(($totalprice),2);?></span></div>
								<img src="img/cart-1.png" alt="" class="shopping-cart">
						</a>
						
						<div class="clearfix"> </div>
					</div>
				</div>
                
                
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
    
    <div id="trackorder">
    	<style>
			.trackouderform{padding:20px;border: 1px solid #888888;padding: 10px;box-shadow: 1px 1px 10px #888888;}
			.trackorderinput{width:250px;height:30px;line-height:30px;border:1px solid #CCC;border-radius:5px;text-indent:10px;margin-bottom:10px;}
			.closetraceorder{float:right;margin-top:-5px;}
		</style>
        <script>
		function checkvalid()
		{
			var cusorder = document.getElementById('cusorder').value;
			var cusemail = document.getElementById('cusemail').value;
			document.getElementById('ecusorder').innerHTML="";
			document.getElementById('ecusemail').innerHTML="";
			var err=0;
			if(cusorder ==""){err++;document.getElementById('ecusorder').innerHTML="required";}
			else if(cusorder !="")
			{
				var isorderexist = 0;
				<?php
					$allorder = $this->db->query("select orderid from t_purchase where orderstatus !='Complete'")->result();
					if(count($allorder)>0) 
					{
						foreach($allorder as $ao)
						{
				?>
							if(cusorder == '<?php echo $ao->orderid?>')
							{
								isorderexist = 1;
							}
				<?php			
						}
					}	
				?>
				if(isorderexist == 0)
				{
					++err;
					document.getElementById('ecusorder').innerHTML="invalid";
				}	
			}
			if(cusemail ==""){err++;document.getElementById('ecusemail').innerHTML="required";}
			else if(cusemail !="")
			{
				var atpos = cusemail.indexOf("@");
				var dotpos = cusemail.lastIndexOf(".");
				if (atpos<1 || dotpos<atpos+2 || dotpos+2>=cusemail.length) 
				{
					++err;
					document.getElementById('ecusemail').innerHTML="invalid";
					
				}
				var isemailexist = 0;
				var cusid ="";
				<?php
					$allemail = $this->db->query("select id,email from t_customer")->result();
					if(count($allemail)>0) 
					{
						foreach($allemail as $ae)
						{
				?>
							if(cusemail == '<?php echo $ae->email?>')
							{
								isemailexist = 1;
								cusid = '<?php echo $ae->id?>';
							}
				<?php			
						}
					}
				?>
				if(cusid != ""){ document.getElementById('cid').value = cusid;}	
				if(isemailexist == 0)
				{
					++err;
					document.getElementById('ecusemail').innerHTML="not match";
				}	
			}
			if(err==0)
			{
				return true;	
			}
			return false;
		}
		</script>
    	<form action="<?php echo 'main/ordertrack'?>" method="post" class="trackouderform" onSubmit="return checkvalid()">
        	<input type="hidden" name="cusid" id="cid">
        	<a href="javascript:"  class="closetraceorder" onClick="closetraceorder()">[close]</a>
        	<table>
            <tr>
                <td>Your order number&nbsp;<span id="ecusorder" class="em"></span><br>
                    <input type="text" name="cusorder" id="cusorder" class="trackorderinput">
                </td>
            </tr>
            <tr>
                <td>Your email&nbsp;<span id="ecusemail" class="em"></span><br>
                    <input type="text" name="cusemail" id="cusemail" class="trackorderinput">
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit"  value="Track" style="float:right;">
                </td>
            </tr>
            </table>
        </form>
    </div>
	<!--top-header-->
	<!--start-logo-->
	<div class="logo">
    	<?php
			if($logo_or_brand =="logo")
			{
		?>
				<a href="<?php echo 'javascript:'?>"><img src="img/logo.png" height="50" style="margin: -5px 0;"></a> 
        <?php 
			}
			else
			{
		?>
        		<a href="<?php echo 'javascript:'?>"><h1><?php echo $companyname?></h1></a> 
        <?php 
			}
		?>
	</div>
	<!--start-logo-->
				<style>
                    .sidemenu{width:100%;height:auto;}
					.sidemenu ul{list-style-type:none;}
					.sidemenu ul li {float:none;}
					.sidemenu ul li a{width:100%;height:30px;float:left;color:#000;text-transform:uppercase;}
					@media(max-width:1279px)
					{
						.sidemenu{display:none;}
						.sidemenumob{display:block;}
					}
					@media(min-width:1279px)
					{
						.sidemenu{display:block;}
						.sidemenumob{display:none;}
					}
					.dropdown.open div {min-width: 100%;min-height: 100%;top: 30px;background: #FFF;border-radius: 0px;margin-left: -10px;}
					.dropdown ul {position: absolute;left: 0;top: 25px;height: 300px;width: 100%;list-style: none;overflow: hidden;padding: 0;background: #000;}
					.dropdown-menu > li > a {display: block;padding: 3px 2px;clear: both;font-size:10px;font-weight:400;line-height: 1.22857143 !important;color: #333;white-space: nowrap;}
					.menuactive{color:#73B6E1 !important;}
                </style>
                <style>
                    .me-one ul li {
                        list-style: none;
                        text-align: left ;
                        float:left !important;
						margin:0 25px;
						width: 350px;
                    }
                    .me-one ul li a {
                        color: #999;
                        font-size: 13px;
                        height:25px;
                        text-align:left !important;
						/*width:200px !important;*/
                    }
					
                </style>
	<!--bottom-header-->
	<div class="header-bottom">
    	<div class="container">
			<div class="header" style="margin-bottom:30px;">
				<div class="col-md-9 header-left">
				<div class="top-nav">
					<ul class="memenu skyblue">
                        
                        <li class="active" style="">
                    		<a href="<?php echo 'main/home'?>">Home</a>
                        </li>
                    
						<li class="grid" style=""><a href="javascript:">Store</a>
							<div class="mepanel"  style="display:none;"> <!----   class="mepanel" ----->
								<div class="row">
									<ul class="col-md-6 me-one">
										
                                        	<?php
											$c=0;
												if($category)
												{
													foreach($category as $cat)
													{
														if($c==0){echo '<ul class="col-md-6">';}
											?>			
													<li style="min-width:350px;"><a href="<?php echo 'main/product/'.$cat->id.'/'.$cat->name?>"><span style="font-size:16px;"><?php echo $cat->name?></span>&nbsp;&nbsp;</a></li>
											<?php 
														$c++;
														if($c==7){echo "</ul>";$c=0;}
													}
												}
											?>	
											
										
									</ul>
								</div>
							</div>
						</li>
                        <?php 
							$totalbestseller = $this->db->query("SELECT count(*)as total  FROM product where bestseller = '1' and picture !=''  ")->row()->total;
						?>
						<li class="grid" style=""><a href="<?php if($totalbestseller >0){ echo 'main/bestseller';}else{echo 'javascript:';}?>">Best Seller</a></li>
                        <style>
							.menu_mepanel{display:none !important;}
						</style>
                        <?php $alloffer = $this->db->query("select * from t_offer")->result(); ?>
                        <li class="grid" style=""><a href="javascript:">Current Offer</a>
							<div class="mepanel <?php if(count($alloffer)==0){echo 'menu_mepanel';}?>" > <!----   class="mepanel" ----->
								<div class="row">
									<ul class="col-md-6 me-one">
										
                                        	<?php
											$c=0;
											$startdate="";$enddata="";
												if(count($alloffer)>0)
												{
													$curdate = date('Y-m-d');
													foreach($alloffer as $of)
													{
														if($c==0){echo '<ul class="col-md-6">';}
														$startdate = $of->startdate;
														$enddata = $of->enddate;
														if($curdate >= $startdate && $curdate <= $enddata)
														{
															$title =$of->title;
															if(strpos($title,'%')){$title = str_replace('%',' Percent',$title);} //codeconversion
											?>			
													<li style="min-width:350px;"><a href="<?php echo 'main/offer/'.$of->id.'/'.urlencode($title)?>"><span style="font-size:16px;"><?php echo $of->title?></span>&nbsp;&nbsp;</a></li>
											<?php 
															$c++;
														}
														if($c==7){echo "</ul>";$c=0;}
													}
												}
											?>	
											
										
									</ul>
								</div>
							</div>
						</li>
						<li class="grid" style=""><a href="<?php echo 'main/contact'?>">Contact</a>
						</li>
					</ul>
				</div>
				<div class="clearfix"> </div>
			</div>
			<div class="col-md-3 header-right"> 
            	<script>
					function searchvalidation()
					{
						var searchword = document.getElementById('searchword').value;
						if(searchword != 'Search')
						{
							return true;
						}
						return false;
					}
				</script>
				<div class="search-bar">
                	<form action="<?php echo 'main/search_product'?>" method="post" onSubmit="return searchvalidation()">
						<input type="text" name="searchword" id="searchword" value="Search" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search';}" >
						<input value="" type="submit">
                    </form>
				</div>
			</div>
			<div class="clearfix"> </div>
			</div>
		</div>
		<div class="container">
        	<div class="row">
            	<div class="col-lg-3 col-md-3 col-sm-3">
                	<div class="sidemenu">
                    	<ul>
                        	<li><a href="<?php echo 'customercontroller/account_controlpanel';?>" <?php if($menu=='account_controlpanel'){ echo 'class="menuactive"';}?>>Account Control Panel</a></li>
                            <li><a href="<?php echo 'customercontroller/customer_profile';?>" <?php if($menu=='profile'){ echo 'class="menuactive"';}?>>Personal Information</a></li>
                            <li><a href="<?php echo 'customercontroller/address_book';?>" <?php if($menu=='address book'){ echo 'class="menuactive"';}?>>Address Book</a></li>
                            <li><a href="<?php echo 'customercontroller/my_orders';?>" <?php if($menu=='myorders'){ echo 'class="menuactive"';}?>>My Orders</a></li>
                            <li><a href="<?php echo 'customercontroller/my_review';?>" <?php if($menu=='myreview'){ echo 'class="menuactive"';}?>>My Review & Ratings</a></li>
                            <li><a href="<?php echo 'customercontroller/my_wishlist'?>" <?php if($menu=='mywishlist'){ echo 'class="menuactive"';}?>>My Saved Items</a></li>
                            <li><a href="<?php echo 'javascript:'?>">My Coupons</a></li>
                            <li><a href="<?php echo 'customercontroller/current_news'?>">Newsletters</a></li>
                        </ul>
                    </div>
                </div>
      			<div class="col-lg-9 col-md-9 col-sm-9" style="border-left:1px solid #999;min-height:400px;">
                		
  
                		<div class="dropdown sidemenumob">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><img src="img/dropdown_icon.png">
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu" style="background:#FFF;">
                          	<li><a href="<?php echo 'customercontroller/account_controlpanel';?>" <?php if($menu=='account_controlpanel'){ echo 'class="menuactive"';}?>>Account Control Panel</a></li>
                            <li><a href="<?php echo 'customercontroller/customer_profile';?>" <?php if($menu=='profile'){ echo 'class="menuactive"';}?>>Personal Information</a></li>
                            <li><a href="<?php echo 'customercontroller/address_book';?>" <?php if($menu=='address book') echo 'class="menuactive"'?>>Address Book</a></li>
                            <li><a href="<?php echo 'customercontroller/my_orders';?>" <?php if($menu=='myorders'){ echo 'class="menuactive"';}?>>My Orders</a></li>
                            <li><a href="<?php echo 'customercontroller/my_review';?>" <?php if($menu=='myreview'){ echo 'class="menuactive"';}?>>My Review & Ratings</a></li>
                            <li><a href="<?php echo 'customercontroller/my_wishlist'?>" <?php if($menu=='mywishlist'){ echo 'class="menuactive"';}?>>My Saved Items</a></li>
                            <li><a href="<?php echo 'javascript'?>">My Coupons</a></li>
                            <li><a href="<?php echo 'customercontroller/current_news'?>">Newsletters</a></li>
                        </ul>
                      </div>
                        
                	<?php echo $content?>
                </div>
             </div>   
		</div>
	</div>
	<!--bottom-header-->
    
    
    
    
	
    
    
    
   
    <style>
    	.infor ul li a, .info p h4{color: #999;font-size:13px;font-family: 'BreeSerif-Regular';margin: 0 0 0 10px;display: inline-block;vertical-align: middle;}
    </style>
	<!--Footer information-starts-->
	<div class="information" style="background:#202340;color:#FFF;">
		<div class="container">
			<div class="infor-top">
				<div class="col-md-3 col-sm-3 col-xs-6 infor"> <!---- infor-left ---->
					<h3 style="color:#FFF">Follow Us</h3>
					<ul>
						<li><a href="<?php if($facebook !=""){echo $facebook;}else{echo 'javascript:';}?>"><p><span class="fb"></span>Facebook</p></a></li>
						<li><a href="<?php if($twitter !=""){echo $twitter;}else{echo 'javascript:';}?>"><p><span class="twit"></span>Twitter</p></a></li>
						<li><a href="<?php if($googleplus !=""){echo $googleplus;}else{echo 'javascript:';}?>"><p><span class="google"></span>Google+</p></a></li>
					</ul>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-6 infor">
					<h3 style="color:#FFF">Information</h3>
					<ul>
						<li><a href="<?php echo 'main/aboutus'?>"><p>About Us</p></a></li>
						<li><a href="<?php echo 'main/policy'?>"><p>Privacy & Policy</p></a></li>
						<li><a href="<?php echo 'main/termsandcondition'?>"><p> Terms & Conditions</p></a></li>
						<li><a href="<?php echo 'main/contact'?>"><p>Contact Us</p></a></li>
					</ul>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-6 infor">
					<h3 style="color:#FFF">My Account</h3>
					<ul>
						<li><a href="<?php echo 'customercontroller/account_controlpanel';?>"><p>My Account</p></a></li>
						
						<!--<li><a href="<?php echo 'javascript:'?>"><p>My Merchandise returns</p></a></li>-->
						<li><a href="<?php echo 'customercontroller/customer_profile'?>"><p>My Personal info</p></a></li>
						<li><a href="<?php echo 'customercontroller/address_book'?>"><p>My Addresses</p></a></li>
                        <li><a href="<?php echo 'customercontroller/my_orders'?>"><p>My Orders</p></a></li>
					</ul>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-6 infor">
                	
					<h3 style="color:#FFF">Store Information</h3>
					<p><h6 style="color: #999;">The <?php echo $companyname?>,
						<span><?php echo $companyaddress?></span>
					</h6></p>
					<p><h6 style="color:#999;"><?php echo $phone?></h6>	</p>
					<p><a href="mailto:<?php echo $email?>" style="color:#999;"><?php echo $email?></a></p>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<!--information-end-->
	<!--footer-starts-->
	<div class="footer" style="background:#202340;color:#FFF;">
		<div class="container">
			<div class="footer-top">
				<div class="col-md-4 footer-left">
					<form>
						<input value="Enter Your Email" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Enter Your Email';}" type="text">
						<input value="Subscribe" type="submit">
					</form>
				</div>
                <div class="col-md-4 footer-right">
                	<img src="img/paymethodimg.png" style="height:31px">
                </div>
				<div class="col-md-4 footer-right">					
					<p><?php if($footerCopyrighttext !="") echo $footerCopyrighttext?></p>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<!--footer-end-->	



 <script src="js/theme1/bootstrap.min.js"></script>
</body></html>
