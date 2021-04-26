

<link rel="stylesheet" href="css/mystyle.css" />
<script src="js/myjs.js"></script>

<script>
$(document).ready(function(e) {
    var cusid = document.getElementById('cusid').value;
	if(cusid !="")
	{
		document.getElementById('reviewbox').style.display="block";
	}
});
</script>
<style>
.deliveryinfo1 ul, .rating-bars ul{list-style-type:none;}
@media(min-width:992px)
{
	.product-main {height: 437px;}
	.product-main img{height: 260px;}
}
.ms{color:#FF8000;}
.ems{color:#F00;}
.flex-control-thumbs li img {
    padding: 0.5em;
    width:60px;
	mim-height:60px !important;
}
.flex-control-thumbs li {
	width: 24% !important;
    float: left;
    margin: 0 1% 0 0;
}
.flex-direction-nav{display:none;}
.contact {padding: 3em 0px !important;}

.deliveryinfo1{display:block;}
.deliveryinfo2{display:none;}
.img-responsive, .thumbnail a > img, .thumbnail > img {display: block;max-width: 100%;height: auto;padding-left: 5px;}
@media(max-width:460px)
{
	.deliveryinfo1{display:none;}
	.deliveryinfo2{display:block;}
	.product-left{margin-bottom:10px;}
	.bestseller {background-color: #ffffff;border-color: #e1e1e1;margin-bottom: 20px;border-style: solid;border-width: 1px;border-radius: 5px;display: block;margin: 0 -15px;}
	.img-responsive, .thumbnail a > img, .thumbnail > img {display: block;max-width: 100%;height: auto;padding-left: 5px;}
}

.register, .btn {color: #ffffff;padding: 0 22px;font-size:12px;text-align: center;text-decoration: none;height: 27px;display: inline-block;line-height: 0px;font-family: 'Lato', sans-serif;font-weight: bold;border-radius: 3px;background: #3c8dbc;background: -moz-linear-gradient(top, #3c8dbc 0%, #3c8dbc 100%);background: -webkit-linear-gradient(top, #3c8dbc 0%,#3c8dbc 100%);background: linear-gradient(to bottom, #3c8dbc 0%,#3c8dbc 100%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3c8dbc', endColorstr='#3c8dbc',GradientType=0 );box-shadow: inset 0px 1px 0px 0px #AAAEB1;margin: 0px;border:none !important;}
</style>


<?php 
$catname ="";
$images = "";
$title ="";
$price ="";
$shortdes="";
$customer_review=0;
$color = "";
$unit = "";
$description = "";
$stock = "";
$productid = "";
$procode ="";
$price ="";
$discount="";
if(isset($singleproduct)&&$singleproduct != "")
{
	foreach($singleproduct as $sp)
	{
		$catname = $this->db->query("select name from category where id='".$sp->categoryid."' ")->row()->name;
		if($sp->picture !=""){$images = $sp->picture;}
		if($sp->picture2 !=""){$images .= ','.$sp->picture2;}
		if($sp->picture3 !=""){$images .= ','.$sp->picture3;}
		if($sp->picture4 !=""){$images .= ','.$sp->picture4;}
		if($sp->picture5 !=""){$images .= ','.$sp->picture5;}
		if($sp->picture6 !=""){$images .= ','.$sp->picture6;}
		$mainimage = $sp->picture;
		$images = explode(",",$images);
		$title = $sp->title;
		///
		$startdate="";$enddata="";
		if(count($offerdata)>0)
		{
			foreach($offerdata as $od)
			{
				if($od->id == $sp->offerid)
				{
					$startdate = $od->startdate;
					$enddata = $od->enddate;
				}
			}
			$curdate = date('Y-m-d');
			if($curdate >= $startdate && $curdate <= $enddata)
			{
				$discount =$sp->offerdiscount;
				$price = ($sp->regularprice -(($sp->regularprice*$sp->offerdiscount)/100));
			}
			else
			{
				$discount =$sp->discount;
				$price = ($sp->regularprice -(($sp->regularprice*$sp->discount)/100));
			}
		}
		else
		{
			$discount =$sp->discount;
			$price = ($sp->regularprice -(($sp->regularprice*$sp->discount)/100));
		}
		///
		$regularprice = $sp->regularprice;
		//$discount = $sp->discount;
		//$price = $sp->saleprice;
		//$price = $regularprice - (($regularprice * $discount)/100);
		$discountplusprice = $regularprice;
		$shortdes=$sp->shortdes;
		$color = $sp->colorid;
		$brand = "";
		$metatag = $sp->metatag;
		$stock = $sp->stock;
		$productid = $sp->id;
		$procode = $sp->code;
		$customer_review = $this->db->query("select count(*)as totalcomment from  t_customerreview where code='".$procode."' ")->row()->totalcomment;
		
		if($color !=0)
		{
			$color = $this->db->query("select name from  color where id='".$color."' ")->row()->name;
		}
		$unit = $sp->unitid;
		if($unit !=0)
		{
			$unit = $this->db->query("select name from  unit where id='".$unit."' ")->row()->name;
		}
		$description = $sp->description;
		$brand = "";
		if($sp->brandid !=0)
		{
			$brand = $this->db->query("select name from  brand where id='".$sp->brandid."' ")->row()->name;
		}
	}
	//print($brand);
}
?>

<!--start-breadcrumbs-->
	<div class="breadcrumbs">
		<div class="container">
			<div class="breadcrumbs-main">
				<ol class="breadcrumb">
					<li><a href="<?php echo 'main/home'?>">Home</a></li>
					<li class="active">Store&nbsp;/&nbsp;<?php echo $catname?></li>
				</ol>
                <div style="float:right;">
                <?php 
                	if(isset($_GET['sk'])&& !empty($_GET['sk']))echo '<label class="ms mymsg fl-r">'.$_GET['sk'].'</label>';
					if(isset($_GET['esk'])&& !empty($_GET['esk']))echo '<label class="ems mymsg fl-r">'.$_GET['esk'].'</label>';
				?>		
                </div>
			</div>
		</div>
	</div>
	<!--end-breadcrumbs-->
	<!--start-single-->
	<div class="single contact">
		<div class="container">
			<div class="single-main">
				<div class="col-md-9 single-main-left">
				<div class="sngl-top">
					<div class="col-md-5 single-top-left">	
						<div class="flexslider">
							  
						<div class="flex-viewport" style="overflow:hidden; position: relative;">
                        	<ul class="slides" style="width:1000%">
                            <?php 
								for($i=0;$i<count($images);$i++)
								{
							?>
                        		<li data-thumb="img/product/<?php echo $images[$i]?>" class="" aria-hidden="true" style="float: left; display: block;">
								   <div class="thumb-image"> <img src="img/product/<?php echo $images[$i]?>" data-imagezoom="true" class="img-responsive" alt="<?php if($metatag !=""){echo $metatag;}else{echo $title;}?>" draggable="false"> </div>
								</li>
                             <?php }?>  
								
                                
                                
                             </ul>
                             
                             <!--- Product view from different angle ------->
                        </div>
                                
                          </div>
                          
                          
						<!-- FlexSlider -->
						<script src="js/imagezoom.js"></script>
						<script defer="" src="js/jquery.flexslider.js"></script>
						<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen">

						<script>
						// Can also be used with $(document).ready()
						$(window).load(function() {
						  $('.flexslider').flexslider({
							animation: "slide",
							controlNav: "thumbnails"
						  });
						});
						</script>
					</div>	
					<div class="col-md-7 single-top-right">
						<div class="single-para simpleCart_shelfItem">
						<h2><?php echo $title;?></h2>
							<div class="star-on">
									<ul class="star-footer">
										<li><a href="#"><i> </i></a></li>
										<li><a href="#"><i> </i></a></li>
										<li><a href="#"><i> </i></a></li>
										<li><a href="#"><i> </i></a></li>
										<li><a href="#"><i> </i></a></li>
									</ul>
								<div class="review">
                                <?php 
								$cusname = $this->session->userdata('cusname');
								if($cusname =="")
								{
								?>
									<a href="javascript:" data-toggle="modal" data-target="#myModal"> <?php echo $customer_review;?> customer review </a>
                                 <?php
								}
								else
								{
									 echo $customer_review.' customer review'; 
								}
								 ?>   
								</div>
							
							</div>
							<style>
							.discountbox{padding:2px 4px;border: 2px solid #F68B1E;color:#F68B1E;font-size:12px;font-weight:700;}
							.add-cart{text-decoration: none;color: #fff;background: #000;padding: 0.4em 0.8em;font-size: 1.15em;text-transform: uppercase; margin-top: 2em;display: inline-block;}
							</style>
							<h5 class="item_price"><br /><?php echo number_format(($price),2);?>
                            <?php
                            if($discount!='0.00')
							{
							?>
                                <br />
                                <span style="text-decoration: line-through;color:#F00;font-size: 17px;">
                                <?php echo number_format($discountplusprice,2);?>
                                </span>&nbsp;
                                <span class="discountbox"><?php if($discount=='0.00'){echo '0%';}else{ echo $discount.'%';}?></span>
                            <?php 
							}
							?>
                            </h5>
                            <p><?php if($brand !=""){echo 'Brand&nbsp;:&nbsp;'.$brand;}?></p>
							<p><?php echo $shortdes;?></p>
							
                            
                            <script>
								function addtocart()
								{
									pid="";pname="";pimg="";pcolor="";psize="";pqty="";
									var pid = document.getElementById('pid').value;
									var pname = document.getElementById('pname').value;
									var pimg = document.getElementById('pimg').value;
									var pcolor = document.getElementById('pcolor').value;
									var psize = document.getElementById('psize').value;
									var pqty = document.getElementById('numofpro').value;
									//alert(pid+', '+pname+', '+pimg+', '+pcolor+', '+psize+', '+pqty);
										$.ajax({
										type: "POST",
										data: "pid=" + pid+ "&pname=" + pname+ "&pimg=" + pimg+ "&pcolor=" + pcolor+ "&psize=" + psize+ "&pqty=" + pqty,
										url: "<?php echo 'main/addtocart';?>",
										success: function(data) {
											
										   location.reload(); 
										   
										}
									});
									
								}
							</script>
                            
                            	<form action="<?php echo 'main/addtocart_offer'?>" method="post">
                                	<input type="hidden" name="pid" id="pid" value="<?php echo $productid;?>" />
                                   <!-- <input type="hidden" name="pname" id="pname" value="<?php echo $title;?>" />-->
                                    <!--<input type="hidden" name="pimg" id="pimg" value="<?php echo $mainimage;?>" />-->
								<table>
									<tr>
                                    <td>
										<?php 
                                        if(count($color)>0)
                                        {
                                            if (strpos($color, ',') !== false) 
                                            {
                                                $color = explode(",",$color);
                                        ?>
                                                Color
                                                <select name="selectedcolor" id="pcolor" class="form-control" style="display:inline !important;min-width:150px;width:70%">
                                                    <?php
                                                    for($i=0;$i<count($color);$i++)
                                                    { 
                                                    ?>
                                                    <option value="<?php echo $color[$i];?>"><?php echo $color[$i];?></option>
                                                    <?php 
                                                    }
                                                    ?>
                                                </select>
                                        <?php 
                                            }
                                            else
                                            {
                                        ?>
                                                Color
                                                <select name="selectedcolor" id="pcolor" class="form-control" style="display:inline !important;min-width:150px;width:70%">
                                                    <option value="<?php echo $color;?>"><?php echo $color;?></option>
                                                </select>
                                        <?php 
                                            }
                                        }
                                        ?>
                                    </td>
                                    </tr>
                                    
                                    
									<tr>
                                    <td><br />
										<?php
                                        if(count($color)>0)
                                        {
                                            if (strpos($unit, ',') !== false) 
                                            {
                                                $unit = explode(",",$unit);
                                        ?>
                                            Size&nbsp;&nbsp;&nbsp;<select name="selectedsize" id="psize" class="form-control" style="display:inline !important;min-width:150px;width:70%">
                                            <?php
                                                for($i=0;$i<count($unit);$i++)
                                                { 
                                                ?>
                                                <option value="<?php echo $unit[$i];?>"><?php echo $unit[$i];?></option>
                                            <?php 
                                                }
                                                ?>
                                            </select>
                                            <?php 
                                            }
                                            else
                                            {
                                        ?>
                                            Size&nbsp;&nbsp;&nbsp;<select name="selectedsize" id="psize" class="form-control" style="display:inline !important;min-width:150px;width:70%">
                                                <option value="<?php echo $unit;?>"><?php echo $unit;?></option>
                                            </select>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
								
							</table>
                            
                           
						
                        	
                            
                        	<script>
							$(document).ready(function(e) {
								var value = 1;
								document.getElementById('numofpro').value;
								$('#minusnum').click(function(){
								//var value = document.getElementById('numofpro').value; 
								if(value >1){value = Number(value) - 1;document.getElementById('numofpro').value = value;}	
								});
								$('#plusnum').click(function(){
									//var value = document.getElementById('numofpro').value;
									var stock = document.getElementById('stock').value; 
									if(value < stock){value = Number(value) + 1;document.getElementById('numofpro').value = value;}	
								});
                                
                            });
							
							</script>
                            	<input type="hidden" name="stock" id="stock" value="<?php echo $stock;?>" />
								<span id="minusnum" style="width:35px;height:35px;background:url(img/minus2.png) no-repeat;color:#FFF;line-height:35px;padding:10px 17px;margin-top:-2px;cursor:pointer"></span>
								<input type="text" name="numofpro" id="numofpro" style="width:40px;height:35px; font-size:18px;text-align:center;margin-right:0px;" readonly="readonly" value="1" />
                                <span id="plusnum" style="width:35px;height:35px;background:url(img/plus2.png) no-repeat;color:#FFF;line-height:35px;padding:10px 17px;margin-top:-2px;margin-right:10px;cursor:pointer;margin-left: -4px;"></span>
                                <input type="submit" value="ADD TO CART" class="add-cart"  /> <!---   onclick="addtocart();" ---->
							</form>
                          
                          <!----------- Wish List Start ------------------------------------------------------------------------------------------>
                          <style>
                            	.wishlist{color:#CCC;text-align:right;font-weight:400;line-height:inherit;vertical-align:initial;width:230px;}
                            </style>
                            <div style="margin-top:25px;" class="wishlist">
                            	<?php 
									$cusid="";
									$cusid = $this->session->userdata('cusid');
								?>
                            	<a href="<?php if($cusid !=""){echo 'main/customer_add_wishlist/'.$productid.'/'.$procode;}else{echo 'javascript:';}?>" <?php if($cusid ==""){echo 'data-toggle="modal" data-target="#myWishlist"';}?> style="color:#73B6E1;text-decoration:none;text-transform:none;"><i class="fa fa-heart"></i> <span>Save for later</span></a>
                            </div>
                             <!----------- Wish List End ------------------------------------------------------------------------------------------>
                            
						</div>
					</div>
                    
				</div>
                
                
                
                
                
                
                
                <style>
				.des{width: 100%;height: 3.2em;line-height: 3.5em;text-indent: 1.2em;display: block;position: relative;color: #fff;text-transform: uppercase;font-size: 14px;text-decoration: none;background: rgba(0, 0, 0, 0.78);margin-bottom: 15px;
				}
				.reviewtextarea{min-width:300px;min-height:150px;}
				</style>
                
                <div class="row">
				<div class="tabs col-md-12 col-sm-12 col-xs-12" style="margin:50px 0 0 0;padding:0;">
						
						
						<style>

						.osh-tabs.-center {text-align: center;}
						 .osh-tabs.-center > .tabs-list {font-size: 0;color: #909090;font-weight: 500;}
						.osh-tabs > .tabs-list {list-style: none;margin: 0;padding: 0;line-height: 17px;}
						.osh-tabs.-default > .tab-content, .osh-tabs > .tabs-list {border-bottom: 1px solid #ddd;}
						.osh-tabs > .tabs-list {font-family: Roboto,Helvetica,Arial,sans-serif;}
						.osh-tabs.-center > .tabs-list > .tab.-active {border-top: 1px solid #ddd;border-right: 1px solid #ddd;border-left: 1px solid #ddd;}
						.osh-tabs.-center > .tabs-list > .tab {font-size: 18px;display: inline-block;border-top: 1px solid #F8F8F8;border-right: 1px solid #F8F8F8;border-left: 1px solid #F8F8F8;}
						.osh-tabs.-center > .tabs-list > .tab.-active > a {color: #000;background-color: #fff;}
						.osh-tabs.-center > .tabs-list > .tab > a {height: 40px;padding: 10px 20px 6px;color: #AAA;background-color: #F8F8F8;margin-left: 1px;}
						.osh-tabs > .tabs-list > .tab > a {display: block;text-decoration: none;outline: 0;}
						.osh-tabs > .tab-content {display: none;}
						.osh-tabs > .tab-content.-active {display: block;}
						@media(max-width:460px)
						{
							.osh-tabs.-center > .tabs-list > .tab {font-size: 10px;}
							.osh-tabs > .tabs-list {line-height: 9px;}
							.osh-tabs.-center > .tabs-list li{height:25px;}
							.osh-tabs.-center > .tabs-list > .tab > a {height: 26px;}
							#rbar{width:70%;margin:auto;}
						}
						#productDescriptionTab {margin: auto 15px;text-align: center;}
						#productSizeGuide {max-width: 710px;margin: 40px auto;}
						/*#ratingReviews {width: 875px;margin: 0 auto;}*/
						</style>
						<script>
						$(document).ready(function(e) {
							$("#descriptionTab").addClass("-active");
							$("#productDescriptionTab").addClass("-active");
								
                            $("#descriptionTab").click(function(){
								$("#descriptionTab,#productDescriptionTab").addClass("-active");
								$("#returnPolicy,#productReturnPolicy,#ratingReviewsTab,#ratingReviews").removeClass("-active");
							});
							$("#returnPolicy").click(function(){
								$("#returnPolicy, #productReturnPolicy").addClass("-active");
								$("#descriptionTab,#productDescriptionTab,#ratingReviewsTab,#ratingReviews").removeClass("-active");
							});
							$("#ratingReviewsTab").click(function(){
								$("#ratingReviewsTab, #ratingReviews").addClass("-active");
								$("#descriptionTab,#productDescriptionTab,#returnPolicy,#productReturnPolicy").removeClass("-active");
							});
                        });
						</script>
						<div class="osh-tabs -center">
                    	<ul class="tabs-list">
                            <li class="tab" id="descriptionTab"><a href="javascript:">Description</a></li>
                            <!--<li class="tab"><a href="#product-details">Specifications</a></li>-->
                            <li class="tab" id="ratingReviewsTab"><a href="javascript:">Reviews</a></li>
                            <li class="tab" id="returnPolicy"><a href="javascript:">Return Policy</a></li>
                        </ul>
                    	  <div id="productDescriptionTab" class="tab-content">
                            <div class="product-description" style="text-align:justify">
                            	<br />
                            	<?php echo $description;?>
                        	<br>
                      		</div>
                          </div>
                        
                          
                          
                    <div id="product-details" class="tab-content">
                            <div class="list -features">
                        <div class="title">Key Features</div>
                        <ul>
                                <li>OS: iOS 11</li>
                                <li>Chipset: Apple A11 Bionic</li>
                                <li>CPU: Hexa-core (2x Monsoon + 4x Mistral)</li>
                                <li>GPU: Apple GPU (three-coregraphics)</li>
                                <li>3GB RAM and 256GB ROM</li>
                                <li>Network: GSM, LTE</li>
                                <li>Display Size: 4.7"</li>
                                <li>Display: LED-backlit IPS LCD</li>
                                <li>Glass Ion-strengthened glass, oleophobic coating</li>
                                <li>Resolution: 750 x 1334 pixels (~326 ppi pixel density)</li>
                                <li>Back Camera: 12MP</li>
                                <li>Front Camera: 7MP</li>
                                <li>Battery: Non-removable Li-Ion 1821 mAh battery</li>
                              </ul>
                      </div>
                            <div class="osh-table -no-border">
                        <div class="caption">Specifications of iPhone 8 - 4.7" - 2GB RAM – 256GB ROM - 12MP Camera - Space Grey</div>
                        <div class="osh-row ">
                                <div class="osh-col -head">SKU</div>
                                <div class="osh-col ">AP113EL0V6S28NAFAMZ</div>
                              </div>
                        <div class="osh-row ">
                                <div class="osh-col -head">Model</div>
                                <div class="osh-col ">iPhone 8</div>
                              </div>
                        <div class="osh-row ">
                                <div class="osh-col -head">Weight (kg)</div>
                                <div class="osh-col ">0.17</div>
                              </div>
                        <div class="osh-row ">
                                <div class="osh-col -head">Type</div>
                                <div class="osh-col ">Smartphone</div>
                              </div>
                        <div class="osh-row ">
                                <div class="osh-col -head">Display Size (inches)</div>
                                <div class="osh-col ">4.7</div>
                              </div>
                        <div class="osh-row ">
                                <div class="osh-col -head">Internal Memory (GB)</div>
                                <div class="osh-col ">256</div>
                              </div>
                        <div class="osh-row ">
                                <div class="osh-col -head">Product warranty</div>
                                <div class="osh-col ">1 Year Brand Warranty</div>
                              </div>
                        <div class="osh-row ">
                                <div class="osh-col -head">RAM (GB)</div>
                                <div class="osh-col ">2 GB</div>
                              </div>
                        <div class="osh-row ">
                                <div class="osh-col -head">Camera(s)</div>
                                <div class="osh-col ">12MP Rear and 7MP Front</div>
                              </div>
                        <div class="osh-row ">
                                <div class="osh-col -head">Battery</div>
                                <div class="osh-col ">1821mAh</div>
                              </div>
                        <div class="osh-row ">
                                <div class="osh-col -head">SIM card</div>
                                <div class="osh-col ">Single</div>
                              </div>
                        <div class="osh-row ">
                                <div class="osh-col -head">Display Resolution</div>
                                <div class="osh-col ">750x1334</div>
                              </div>
                        <div class="osh-row ">
                                <div class="osh-col -head">Display Type</div>
                                <div class="osh-col ">LED-backlit IPS LCD</div>
                              </div>
                        <div class="osh-row ">
                                <div class="osh-col -head">Processor</div>
                                <div class="osh-col ">Hexa-core</div>
                              </div>
                      </div>
                          </div>
                     <style>
					 	/*#ratingReviews > .osh-rating-review {padding-left: 45px;text-align: left;}*/
						/*#ratingReviews {width: 775px;margin: 0 auto;}*/
					 	.percent {position: relative;text-align: left;display: inline-block;width: 100px;height: 8px;background-color: #EEE;}
						.percent > span { position: absolute;display: inline-block; height: 8px;background-color: #FFE358;}
					 	.rating {unicode-bidi: bidi-override;direction: rtl;}
						.rating > span {display: inline-block;position: relative;width: 1.1em;font-size:28px;color:#EEEEEE;}
						.rating > span:hover:before,
						.rating > span:hover ~ span:before {content: "\2605";position: absolute;color:#FFE358;}
						@media(min-width:768px){.ratingborder{border-right:1px solid #CCC;}}
						.ratingbtn{display: inline;padding: 5px 15px;font-size: 75%;font-weight: 700;line-height: 1;color: #fff;text-align: center;white-space: nowrap;vertical-align: baseline;border-radius: .25em;background: #73B6E1;text-transform:uppercase;}
					 </style>  
                     <?php 
					 	$productreview = $this->db->query("select * from t_customerreview where code='".$procode."' ")->result();
						$fivestar=0;$fourstar=0;$threestar=0;$twostar=0;$onestar=0;
						$fivestarpercent=0;$fourstarpercent=0;$threestarpercent=0;$twostarpercent=0;$onestarpercent=0;
						if(count($productreview)>0)
						{
							foreach($productreview as $pv)
							{
								if($pv->rating == '5'){$fivestar++;}
								if($pv->rating == '4'){$fourstar++;}
								if($pv->rating == '3'){$threestar++;}
								if($pv->rating == '2'){$twostar++;}
								if($pv->rating == '1'){$onestar++;}
							}
							
							$fivestarpercent = ($fivestar*100)/100;
							$fourstarpercent = ($fourstar*100)/100;
							$threestarpercent = ($threestar*100)/100;
							$twostarpercent = ($twostar*100)/100;
							$onestarpercent = ($onestar*100)/100;
						}
						$averagerating=0;
						if(count($productreview)>0){$averagerating = ($fivestar+$fourstar+$threestar+$twostar+$onestar)/5;}
					 ?>   
                    <div id="ratingReviews" class="tab-content" style="height:auto;">
                            
                            <br />
                            <div class="col-md-2">&nbsp;</div>
                            <div class="avg col-md-2 col-sm-4 col-xs-12" style="margin-bottom:20px;">
                            
                                <header>Average Rating</header>
                                <div style="height:120px;">
                                <div class="-active" style="font-size:110px;color:#FFE358;margin-top:-30px;z-index:-1;">&#9734;</div> <div style="font-size:12px;margin-top:-82px;color:#000"><?php echo $averagerating;?></div>
                                </div>
                                <?php if($averagerating==0){?>
                                <footer>No rating yet.</footer>
                                <?php }?>
                              </div>
                              
                        <div class="rating-bars col-md-4 col-sm-4 col-xs-12 ratingborder" style="margin-bottom:20px;">
                        	
                                <ul id="rbar">
                            <li class="clearfix prd-ratingBarListItem">
                            		<div class="star" style="float:left;">5 stars&nbsp;</div>
                                    <div class="percent-rating" style="float:left;"><span class="percent"><span style="width: <?php echo $fivestarpercent;?>%"></span></span> <span class="rating">(<?php echo $fivestar;?>)</span></div>
                                  </li>
                            <li class="clearfix prd-ratingBarListItem">
                            		<div class="star" style="float:left;">4 stars&nbsp;</div>
                                    <div class="percent-rating" style="float:left;"><span class="percent"><span style="width: <?php echo $fourstarpercent;?>%"></span></span> <span class="rating">(<?php echo $fourstar;?>)</span></div>
                                  </li>
                            <li class="clearfix prd-ratingBarListItem">
                            		<div class="star" style="float:left;">3 stars&nbsp;</div>
                                    <div class="percent-rating" style="float:left;"><span class="percent"><span style="width: <?php echo $threestarpercent;?>%"></span></span> <span class="rating">(<?php echo $threestar;?>)</span></div>
                                  </li>
                            <li class="clearfix prd-ratingBarListItem">
                            		<div class="star" style="float:left;">2 stars&nbsp;</div>
                                    <div class="percent-rating" style="float:left;"><span class="percent"><span style="width: <?php echo $twostarpercent;?>%"></span></span> <span class="rating">(<?php echo $twostar;?>)</span></div>
                                  </li>
                            <li class="clearfix prd-ratingBarListItem">
                            		<div class="star" style="float:left;">&nbsp;&nbsp;1 star&nbsp;</div>
                                    <div class="percent-rating" style="float:left;"><span class="percent"><span style="width: <?php echo $onestarpercent;?>%"></span></span> <span class="rating">(<?php echo $onestar;?>)</span></div>
                                  </li>
                                  
                          </ul>
                          
                              </div>
                              
                              
                        
                     
                            	<div class="osh-rating-review col-md-3 col-sm-3 col-xs-12">
                            	<div class="rating">
									<span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span>
								</div>
                                <?php 
								$cusname = $this->session->userdata('cusname');
								if($cusname =="")
								{
								?>
                                <a href="javascript:" data-toggle="modal" data-target="#myModal" class="osh-btn -blue js-link-to-reviews" style="text-decoration:none"> <span class="ratingbtn ">Write a Review</span> </a>
                                <?php
								}
								else
								{
								?>
                                <a href="javascript:"  class="osh-btn -blue js-link-to-reviews" style="text-decoration:none"> <span class="ratingbtn ">Write a Review</span> </a>
                                <?php
								}
								?>
                        		</div>
                          </div>
                          
                          
                          
                          
                          
                    <div id="productReturnPolicy" class="tab-content">
                            <style>
						a:link
						{
						color:#FFA500;
						}
						
						a:visited 
						{
						color:#FFA500
						}
						
						a:hover
						{
						color:#FFA500
						}
						
						a:active
						{
						color:#FFA500
						}
						p {
							margin: 0 0 8.5px;
						}
						
						</style>
                            <font face="roboto">
                      <div style="text-align: left;">
                      		<br />
                              <?php echo $this->mm->getSet('return policy');?>
                            </div>
                      </font></div>
                  </div>
                        <!--<span class="des"><img src="addtocart_files/arrow.png" alt="">Description</span>-->
                                <!--<div class="subitem1" style="text-align:justify;padding:0 15px;">
                                    <?php echo $description;?>
                                </div>-->
                        
                        
                        
                        <?php 
							$cusid = $this->session->userdata('cusid');
						?>
	 					<div class="col-md-5 col-sm-5 col-xs-12" id="reviewbox">
                        	<h3 style="color:#FF9900;">Comment Section</h3>
                        	<form action="<?php echo 'main/add_comment'?>" method="post" >
                            	<input type="hidden" name="procode" value="<?php echo $procode?>" />
                                <input type="hidden" name="proid" value="<?php echo $productid?>" />
                                <input type="hidden" name="cusid" id="cusid" value="<?php if(isset($cusid)&& $cusid !=""){echo $cusid;}?>" />
                            	<table>
                                	<tr>
                                        <td><span style="color:#FF9900;">Do you like it?</span><br />
                                        	<select name="ratingpoint" style="min-width:300px;height:35px;line-height:35px;">
                                            	<option value="4"> I like it</option>
                                                <option value="2"> I don't like it</option>
                                            	<option value="1"> I hate it</option>
												<option value="3"> It's ok</option>
												<option value="5"> It's perfect!</option>
                                            </select><br /><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span style="color:#FF9900;">Give you comment here:</span><br />
                                        	<textarea name="comment"  class="reviewtextarea"></textarea>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                        	<br />
                                            <input type="submit" value="Send" style="float:right"/>
                                        </td>
                                    </tr>
                                 </table>   
                            </form>
                        </div>
				</div>
                </div>
                
                
                <div class="row">
                	<div class="latestproducts col-md-12 col-sm-12 col-xs-12" style="padding:0px;">
                    	<!--------------------------------------------------------------------------------------------------->
							<?php 
                            $deleiveryinfo = $this->db->query("select * from delivery")->result();
                            if(count($deleiveryinfo)>0)
                            {	$local="";$localaddress="";$nationaladdress="";$internationaladdress="";
                                foreach($deleiveryinfo as $delivery)
                                {
                                    if($delivery->name == 'Local'){$local=$delivery->address;$localaddress = "Inside ".$delivery->address." ".$delivery->period;}
                                    if($delivery->name == 'National')
                                    {
                                        if($local !="")
                                        {
                                            $nationaladdress = "Outside ".$local." ".$delivery->period;
                                        }
                                        else
                                        {
                                            $nationaladdress = "Outside Dhaka ".$delivery->period;
                                        }
                                    }
                                    if($delivery->name == 'International'){$internationaladdress = $delivery->address." ".$delivery->period;}
                                }
                            
                        ?>
                        <div class="deliveryinfo2" style="width:100%;background:#F8F8F8;padding:10px 10px 0px 10px;margin-bottom:0px;border:1px solid #D3D3D3;">
                        <center>
                        <h4 style="text-transform:uppercase;font-weight:bold;margin-top:-5px;">Delivery Information</h4>
                        
                        <ul>
                            <li style="margin-bottom:15px;"><?php echo $localaddress;?> days</li>
                            <li style="margin-bottom:15px;"><?php echo $nationaladdress;?> days</li>
                            <li style="margin-bottom:15px;"><?php echo $internationaladdress;?> days</li>
                        </ul>
                        </center>
                        </div>
                        <?php }?>
                        <!--------------------------------------------------------------------------------------------------->
                    </div>
                </div>
                
                
                
                <div class="row">
                <style>
					.otherpro {width: 100%;height: 3.2em;line-height: 3.5em;text-indent: 1.2em;display: block;position: relative;color: #fff;text-transform: uppercase;font-size: 14px;text-decoration: none;background: rgba(0, 0, 0, 0.78);margin-bottom:15px;}
				</style>
                
                
				<div class="latestproducts col-md-12 col-sm-12 col-xs-12" style="padding:0px;">
					<div class="product-one">
                    	
                    	<?php 
							$samotherpro = $this->db->query("SELECT *  FROM product where categoryid='".$sp->categoryid."' and id !='".$sp->id."' and offerid !='' and stock !=0 limit 12 ")->result();
							if(count($samotherpro)>0)
							{
						?>
                        <div class="otherpro">Other Related Products</div>
                        <?php		
								$image = "";
								foreach($samotherpro as $sop)
								{
									$image = $sop->picture;
									$imagefile = 'img/product/'.$image;
									if(file_exists($imagefile))
									{
						?>
						<div class="col-md-4 product-left"> 
							<div class="product-main simpleCart_shelfItem">
								<a href="<?php echo 'main/view_offer/'.$sop->id?>" class="mask"><?php if($image !=""){?><img class="img-responsive zoom-img" src="img/product/<?php echo $image;?>" alt="<?php if($sop->metatag !=""){echo $sop->metatag;}else{echo $sop->title;}?>" style="max-width:260px;max-height:260px;"><?php }?></a>
								<div class="product-bottom" style="text-align:center;">
                                
                                	<div style="height:90px;overflow:hidden;margin-top:10px;">
									<h3><?php if(isset($sop->title)&&$sop->title!="") echo $sop->title?></h3>
									<p>Explore Now</p>
                                    </div>
                                    
									<h4><a class="item_add" href="<?php echo 'main/view_offer/'.$sop->id?>"><i></i></a> <span class=" item_price"><?php if(isset($sop->saleprice)&&$sop->saleprice!="") echo number_format($sop->saleprice,2)?>
                                    	<?php 
										if($sop->saleprice < $sop->regularprice)
										{
										?>
										<span style="text-decoration:line-through;color:#F00;font-size:17px;font-family:'BreeSerif-Regular';padding-left:5px;"><?php echo number_format($sop->regularprice,2);?></span>  
										<?php
										}
										?>
                                    </span></h4>
								</div>
								<!--<div class="srch">
                                    <span><?php if($sop->offerdiscount=='0.00'){echo '0%';}else{ echo number_format($sop->offerdiscount,2).'%';}?></span>
								</div>-->
							</div>
						</div>
                        
                        <?php 
									}
								}
							}
						?>
						<div class="clearfix" style="margin-bottom:50px;"></div>
					</div>
				</div>
                </div>
			</div>
				
                <!-------=================== Right Side Start =================================----------->
            	<div class="col-md-3 single-main-left">
                
                <div class="row">
				<div class="sngl-top col-md-12 col-sm-12">
                	<?php 
						$deleiveryinfo = $this->db->query("select * from delivery")->result();
						if(count($deleiveryinfo)>0)
						{	$local="";$localaddress="";$nationaladdress="";$internationaladdress="";
							foreach($deleiveryinfo as $delivery)
							{
								if($delivery->name == 'Local'){$local=$delivery->address;$localaddress = "Inside ".$delivery->address." ".$delivery->period;}
								if($delivery->name == 'National')
								{
									if($local !="")
									{
										$nationaladdress = "Outside ".$local." ".$delivery->period;
									}
									else
									{
										$nationaladdress = "Outside Dhaka ".$delivery->period;
									}
								}
								if($delivery->name == 'International'){$internationaladdress = $delivery->address." ".$delivery->period;}
							}
						
					?>
                	<div class="deliveryinfo1" style="width:100%;background:#F8F8F8;padding:10px 10px 0px 10px;margin-bottom:25px;border:1px solid #D3D3D3;">
                    <center>
                    <h4 style="text-transform:uppercase;font-weight:bold;margin-top:-5px;">Delivery Information</h4>
                    
                	<ul>
                    	<li style="margin-bottom:15px;"><?php echo $localaddress;?> days</li>
                        <li style="margin-bottom:15px;"><?php echo $nationaladdress;?> days</li>
                        <li style="margin-bottom:15px;"><?php echo $internationaladdress;?> days</li>
                    </ul>
                    </center>
                    </div>
                    <?php }?>
                	
                    <style>
						.bestseller{background-color:#ffffff;border-color:#e1e1e1;margin-bottom:20px;border-style:solid;border-width:1px;border-radius:5px;display:block;}
						.bestseller-title {color: #434343;border-color: #e1e1e1;background-color: #f1f1f1;margin: 0 0 7px;padding:.1rem 2rem;border-bottom-width:0px;margin-bottom: 0;}
						.bestseller-title h3{margin-top:0px;margin-bottom:0px;}
						.bestseller .products-list .item {margin-bottom:0;padding:2rem 0 1rem;border-width:0px;}
						.products-list .product-image {float:left;margin:0 20px 10px 0;margin-bottom:10px;}
						.products-list .product-shop {overflow: hidden;text-align: left;}
						.f-fix {float: left;}
						.product-name{font-size: 1em;font-weight: normal;}
					</style>
                    <div class="bestseller">
                    	
                        <?php 
							$bestsellerdata ="";
							$pronum = 0; $pronum = count($samotherpro); 
							if($pronum >=9){$bestsellerdata = $this->db->query("select * from product where bestseller='1' limit 12 ")->result();}
							else if($pronum >=6){$bestsellerdata = $this->db->query("select * from product where bestseller='1' limit 6 ")->result();}
							else if($pronum >=3){$bestsellerdata = $this->db->query("select * from product where bestseller='1' limit 3 ")->result();}
							else{$bestsellerdata = $this->db->query("select * from product where bestseller='1' limit $pronum ")->result();}
							
							if(count($bestsellerdata)>0)
							{
						?>
                        <div class="bestseller-title">
                        	<h4 style="text-transform:uppercase;font-weight:bold;">Best Sellers</h4>
                        </div>
                        <?php		
							foreach($bestsellerdata as $bd)
							{
								///
								$startdate="";$enddata="";
								if(count($offerdata)>0)
								{
									foreach($offerdata as $od)
									{
										if($od->id == $bd->offerid)
										{
											$startdate = $od->startdate;
											$enddata = $od->enddate;
										}
									}
									$curdate = date('Y-m-d');
									if($curdate >= $startdate && $curdate <= $enddata)
									{
										$discount =$bd->offerdiscount;
										$price = ($bd->regularprice -(($bd->regularprice*$bd->offerdiscount)/100));
									}
									else
									{
										$discount =$bd->discount;
										$price = ($bd->regularprice -(($bd->regularprice*$bd->discount)/100));
									}
								}
								else
								{
									$discount =$bd->discount;
									$price = ($bd->regularprice -(($bd->regularprice*$bd->discount)/100));
								}
								///
						?>
                         <div class="products-list" style="min-height:140px;">
                        	<div class="item" style="width:50%;">
                            	<a href="<?php echo 'main/view/'.$bd->id;?>" title="Xiaomi Mi In-Ear Headphones Basic" class="product-image ">                           
                                        <img class="em-alt-hover img-responsive" src="<?php echo 'img/product/'.$bd->picture;?>" alt="<?php if($bd->metatag !=""){echo $bd->metatag;}else{echo $bd->title;}?>" width="100" height="100">
                						                						                        
                					</a>
                            </div>
                            <div class="f-fix" style="width:50%;">
                            <div style="height:60px;overflow:hidden;"><a href="<?php echo 'main/view/'.$bd->id;?>" style="color:#73B6E1"><?php echo $bd->title;?></a></div>
                            
                            <h4>
                            <a class="item_add" href="<?php echo 'main/view/'.$bd->id;?>"><i></i></a> 
                            <span class="item_price"><?php echo number_format($price,2);?>	
                            <span style="text-decoration:line-through;color:#F00;font-size:17px;font-family:'BreeSerif-Regular';padding-left:5px;">660.00</span> </span>
                            </h4>
                            </div>
                        </div>
                        
                        	<hr style="margin:0;border:1px dotted #eee" />
                            
                       
                        <?php }}?>
                        
                    </div>
                    
                </div>
                </div>
                </div>
				<!-------=================== Right Side End =================================----------->
                
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
    
	<!--end-single-->
    
 
    <!-- Modal -->
    <style>
		.modal-dialog {width: 300px !important;margin: 30px auto;position:absolute;top:50%;left:50%;margin:-100px 0 0 -150px;}
	</style>
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!---------- Modal content Start ----------------------------------------------------------------------------->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Login Your Account</h4>
        </div>
        <div class="modal-body">
          <form action="<?php echo 'main/check_customer'?>" method="post">
          	<input type="hidden" name="procode" value="<?php echo $procode?>" />
            <input type="hidden" name="proid" value="<?php echo $productid?>" />
          	<table>
            	<tr>
                	<td>Username:<br /><input type="text" name="username" id="username" style="min-width:270px;height:35px;" tabindex="1" autocomplete="off"  /></td>
                </tr>
                <tr>
                	<td>Password:<br /><input type="password" name="password" id="password" style="min-width:270px;height:35px;" tabindex="2" autocomplete="off"/></td>
                </tr>
                <tr>
                	<td><br /><input type="submit" value="Login" class="btn" tabindex="3" /></td>
                </tr>
            </table>
          </form>
          <br />
          <a href="<?php echo 'main/create_new_account/'.$productid?>">Create an account</a>
        </div>
      </div>
      
    </div>
  </div>
  <!---------- Modal content End ----------------------------------------------------------------------------->
  
  
  <!-------------- Wish List Start ----------------------------------------------------------------------------------->
  
  <div class="modal fade" id="myWishlist" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Login Your Account</h4>
        </div>
        <div class="modal-body">
          <form action="<?php echo 'main/check_customer_for_add_wishlist'?>" method="post">
          	<input type="hidden" name="procode" value="<?php echo $procode?>" />
            <input type="hidden" name="proid" value="<?php echo $productid?>" />
          	<table>
            	<tr>
                	<td>Email:<br /><input type="text" name="useremail" id="useremail" style="min-width:270px;height:35px;" tabindex="1" autocomplete="off"  /></td>
                </tr>
                <tr>
                	<td>Password:<br /><input type="password" name="password" id="password" style="min-width:270px;height:35px;" tabindex="2" autocomplete="off"/></td>
                </tr>
                <tr>
                	<td><br /><input type="submit" value="Login" class="btn" tabindex="3" style="height:30px;line-height:20px;" /></td>
                </tr>
            </table>
          </form>
          <br />
          <a href="<?php echo 'main/create_new_account/'.$productid?>">Create an account</a>
        </div>
      </div>
      
    </div>
  </div>
  
  <!---------- Wish List End ------------------------------------------------------------------------------------>