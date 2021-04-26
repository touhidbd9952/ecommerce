



<style>
input[type="text"],input[type="password"],input[type="email"],select{border:1px solid #ddd;padding:8px;-webkit-border-radius:3px 0 0 3px;-moz-border-radius:3px 0 0 3px border-radius:3px 0 0 3px;border-style:solid;border-width:1px;color:#888;font-size:14px;margin-bottom:10px;height:36px}
.checkout-heading{background:#f8f8f8;border:1px solid #dbdee1;color:#555;font-size:13px;font-weight:700;margin-bottom:15px;padding:13px 8px}
.checkout a.button,.checkout input.button,a.button,.continuebtn{-moz-user-select:none;background-color:#fe5757;background-image:none;border:medium none;border-radius:4px;color:#fff;cursor:pointer;display:inline-block;font-size:12px;font-weight:700;line-height:10px;margin-bottom:0;padding:12px 20px;text-align:center;transition:background .25s ease 0;-webkit-transition:background .25s ease 0;vertical-align:middle;white-space:nowrap;box-shadow:0 -2px 0 rgba(0,0,0,.15) inset}
.memenu{display:none !important;}
.ckeckout {padding: 0em 0px;}
.em{color:#F00;}
@media(max-width:767px)
{
	ul.cart-header li span {
    margin: 2.3em 0 0;
    display: inline;
    color: #000;
    text-align: left;
	font-size: 11px;
	}
	ul.unit li{font-size: 11px;}
	ul.cart-header li{font-size: 11px;}
	.tar{text-align:right;}
}
@media(min-width:768px)
{
	.tdd{margin: 2.3em 0 0;}
	input[type="text"]{width:120px;}
	.td4{text-align:center;}
}
</style>



<div class="breadcrumbs">
		<div class="container" style="padding-right:0px;">
			<div class="breadcrumbs-main">
				<ol class="breadcrumb" style="padding: 8px 0px 8px 10px;">
					<li><a href="main/home">Home</a></li>
					<li class="active">Checkout&nbsp;/&nbsp;Confirm Order</li>
				</ol>
			</div>
		</div>
	</div>


<?php
					
	$jsondata = $this->session->userdata('cartdata'); 
	$jsondata = json_decode($jsondata);
	$spid ="";$spcolor ="";$spsize ="";$spqty ="";
	$spid = $jsondata->pid; 
	$spimg = $jsondata->pimg;
	$spcolor = $jsondata->pcolor; 
	$spsize = $jsondata->psize; 
	$spqty = $jsondata->pqty;
	
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
	
	$mdata = array();
	$data = array();
	//purchase table data start
	$deliveryloc = $this->session->userdata('deliveryloc');
	$deliverycharge =0;
	if($deliveryloc ==0)
	{
		$deliverycharge = $this->session->userdata('deliverycharge');
	}
	else
	{
		$deliverycharge = $this->session->userdata('pickuppointdeliverycharge');
	}
	$paymethod = $this->session->userdata('paymethod');
	$paymentstatus = $this->session->userdata('paymentstatus');
	$orno = $this->db->query("select max(id)as orno from t_purchase")->row()->orno;
	 if(count($spid)>1)  
		{
			for($i=0;$i<count($spid);$i++)
			{
				$product = $this->db->query("select * from product where id='".$spid[$i]."' ")->result();
				$data['ordernumber'] ='or'.$orno+1;
				
				$data['productid'] = $spid[$i];
				if(($product[0]->offerid =="")||($product[0]->offerid =="0"))
				{
					$data['price'] = ($product[0]->regularprice -(($product[0]->regularprice*$product[0]->discount)/100));
					$data['discount'] = $product[0]->discount;
				}
				else
				{
					$offerdata = $this->db->query("select * from t_offer where id='".$product[0]->offerid."' ")->result();
					$startdate="";$enddata="";
					foreach($offerdata as $od)
					{
						$startdate = $od->startdate;
						$enddata = $od->enddate;
					}
					$curdate = date('Y-m-d'); 
					if($curdate >= $startdate && $curdate <= $enddata)
					{
						$data['price'] = ($product[0]->regularprice -(($product[0]->regularprice*$product[0]->offerdiscount)/100));
						$data['discount'] = $product[0]->offerdiscount;
					}
				}
				$data['qty'] = $spqty[$i];
				$data['size'] = $spsize[$i];
				$data['color'] = $spcolor[$i];
				
				$data['vat'] = $product[0]->vat;
				$data['deliverycharge'] = $deliverycharge;
				$data['paymethod'] = $paymethod;
				$data['paymentstatus'] = $paymentstatus;
				
				$data['customer']= $this->session->userdata('customer');  //customer name
				$data['email']= $this->session->userdata('email');
				$data['phone']= $this->session->userdata('phone');
				$data['address']= $this->session->userdata('address');
				$data['country']= $this->session->userdata('country');
				$data['citylocation']= $this->session->userdata('citylocation');
				
				$mdata[] = $data;
			}
		}
		else
		{
				$product = $this->db->query("select * from product where id='".$spid."' ")->result();
				$data['ordernumber'] ='or'.$orno+1;
				
				$data['productid'] = $spid;
				if(($product[0]->offerid =="")||($product[0]->offerid =="0"))
				{
					$data['price'] = ($product[0]->regularprice -(($product[0]->regularprice*$product[0]->discount)/100));
					$data['discount'] = $product[0]->discount;
				}
				else
				{
					$offerdata = $this->db->query("select * from t_offer where id='".$product[0]->offerid."' ")->result();
					$startdate="";$enddata="";
					foreach($offerdata as $od)
					{
						$startdate = $od->startdate;
						$enddata = $od->enddate;
					}
					$curdate = date('Y-m-d'); 
					if($curdate >= $startdate && $curdate <= $enddata)
					{
						$data['price'] = ($product[0]->regularprice -(($product[0]->regularprice*$product[0]->offerdiscount)/100));
						$data['discount'] = $product[0]->offerdiscount;
					}
				}
				
				$data['qty'] = $spqty;
				$data['size'] = $spsize;
				$data['color'] = $spcolor;
				
				$data['vat'] = $product[0]->vat;
				$data['deliverycharge'] = $deliverycharge;
				$data['paymethod'] = $paymethod;
				$data['paymentstatus'] = $paymentstatus;
				
				$data['customer']= $this->session->userdata('customer'); //customer name
				$data['email']= $this->session->userdata('email');
				$data['phone']= $this->session->userdata('phone');
				$data['address']= $this->session->userdata('address');
				$data['country']= $this->session->userdata('country');
				$data['citylocation']= $this->session->userdata('citylocation');
				$mdata[] = $data;
		}
		$mdata = json_encode($mdata);
		$sdata = array();
		$sdata['sa'] =  $mdata;
		$this->session->set_userdata($sdata);
									
?>		

<div class="single contact">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="checkout">
            <!--<div id="shipping-address">
              <div class="checkout-heading">Step 1: Delivery Details</div>
            </div>-->
            <!--<div id="shipping-method">
              <div class="checkout-heading">Step 2: Delivery Method</div>
            </div>-->
            <!--<div id="payment-method">
              <div class="checkout-heading">Step 3: Payment Method</div>
            </div>-->
            <div id="confirm">
              <!--<div class="checkout-heading">Step 4: Confirm Order</div>-->
              <div class="checkout-content" style="display: block;">
                <div id="shipping-new" style="">
                  
                  <div class="ckeckout">
		<div class="container" style="padding-left:0px;padding-right:0px;">
			<div class="ckeckout-top">
			<div class="cart-items" style="padding:0 !important;">
			 <h3 style="color:#66AAD0">My Shopping Bag (<?php echo count($spid)?>)</h3>
				<script>
					function deleteitem(arrid)
					{
							$.ajax({
							type: "POST",
							data: "arrid=" + arrid,
							url: "<?php echo 'main/deletecartitem';?>",
							success: function(data) {
							   location.reload();   
							}
						});
						
					}
			   </script>
			<style>
				.st1{width:120px !important;text-align:left}
			</style>
				
			<div class="in-check">
				<ul class="unit">
					<li class="td1"><span>Item</span></li>
					<li class="td2">Name</li>		
					<li class="td3">Unit</li>
                    <li class="td4">Qty</li>
					<li class="td5">Size</li>
                    <li class="td6">color</li>
					<div class="clearfix"> </div>
				</ul>
                <?php
					//coupon
					$couponvalue=0;
					$mpurchase=0;
					$sp="";$fp=0;$ap=0;$sc="";
					$selectedproduct = false;
					$firstpurchase = false;
					$allproduct = false;
					$selectedcategory = false;
					$applycoupon = false;
					if(isset($coupondata) && count($coupondata)>0)
					{
						foreach($coupondata as $coupon)
						{
							$mpurchase = $coupon->mpurchase; //minimum purchase
							$sp=$coupon->sp; //selected product
							$fp=$coupon->fp; //first purchase
							$ap=$coupon->ap; //all product
							$sc=$coupon->sc; //selected category
						}
					}
					//coupon
					
					
					
					$discount=0;
					$vat=0;
					$pprice="";
					$totalprice =0;
					 if(count($spid)>1)  
					{
						for($i=0;$i<count($spid);$i++)
						{
							$product = $this->db->query("select * from product where id='".$spid[$i]."' ")->result();
							foreach($product as $p)
							{
								////coupon////
								if($sp !="")
								{
									if(strpos($sp,',')){$sp = explode(',',$sp);}
									if(in_array($p->id,$sp)){$couponvalue = $coupon->amount;$selectedproduct = true;}
								}
								else if($fp ==1)
								{
									if(isset($cusdata)&& count($cusdata)>0)
									{
										$productpurchase = 0;
										foreach($cusdata as $cus)
										{
											$productpurchase = $cus->product_purchase;
										}
										if($productpurchase ==0)
										{
											$couponvalue = $coupon->amount;
											$firstpurchase = true;
										}
									}
								}
								else if($ap ==1)
								{
									$couponvalue = $coupon->amount;
									$allproduct = true;
								}
								else if($sc != "")
								{
									if($p->categoryid ==$sc ){$couponvalue = $coupon->amount;$selectedcategory = true;}
								}
								////coupon////
								
								
								
								if(($p->offerid =="")||($p->offerid =="0"))
								{
									$discount +=$p->discount;
									$vat +=$p->vat;
									$pprice = ($p->regularprice -(($p->regularprice*$p->discount)/100));
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
										$vat +=$p->vat;
										$pprice = ($p->regularprice -(($p->regularprice*$p->offerdiscount)/100));
										$totalprice += ($p->regularprice -(($p->regularprice*$p->offerdiscount)/100)) * $spqty[$i];
									}
								}
								
							}
				?>
				<ul class="cart-header">
						<li class="ring-in td1"><a href="home/single"><img src="img/product/<?php echo $p->picture;?>" class="img-responsive"  alt="" style="max-width:100px;width:100%;"></a></li>
						<li class="td2 tdd"><?php if(isset($p->title)&&$p->title!=""){ echo $p->title;}?></li>
						<li class="td3 tdd"><?php if(isset($pprice)&&$pprice!=""){ echo number_format($pprice,2);}?></li>
                        <li class="td4 tdd" style="text-align:center"><?php if(isset($spqty[$i])&&$spqty[$i]!=""){ echo $spqty[$i];}?></li>
                        <li class="td5 tdd"><?php if(isset($spsize[$i])&&$spsize[$i]!=""){ echo $spsize[$i];}?></li>
                        <li class="td6 tdd"><?php if(isset($spcolor[$i])&&$spcolor[$i]!=""){ echo $spcolor[$i];}?></li>
						
					<div class="clearfix"> </div>
				</ul>
				<?php 
					}
				}
				else
				{
					$product = $this->db->query("select * from product where id='".$spid."' ")->result();
					foreach($product as $p)
					{
						////coupon////
								if($sp !="")
								{
									$sp = explode(',',$sp);
									if(in_array($p->id,$sp)){$couponvalue = $coupon->amount;$selectedproduct = true;}
								}
								else if($fp ==1)
								{
									if(isset($cusdata)&& count($cusdata)>0)
									{
										$productpurchase = 0;
										foreach($cusdata as $cus)
										{
											$productpurchase = $cus->product_purchase;
										}
										if($productpurchase ==0)
										{
											$couponvalue = $coupon->amount;
											$firstpurchase = true;
										}
									}
								}
								else if($ap ==1)
								{
									$couponvalue = $coupon->amount;
									$allproduct = true;
								}
								else if($sc != "")
								{
									if($p->categoryid ==$sc ){$couponvalue = $coupon->amount;$selectedcategory = true;}
								}
								////coupon////
								
								
						if(($p->offerid =="")||($p->offerid =="0"))
						{
							$vat +=$p->vat;
							$pprice = ($p->regularprice -(($p->regularprice*$p->discount)/100));
							$totalprice = ($p->regularprice -(($p->regularprice*$p->discount)/100)) * $spqty;
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
								$discount = $p->offerdiscount;
								$vat = $p->vat;
								$pprice = ($p->regularprice -(($p->regularprice*$p->offerdiscount)/100));
								$totalprice = ($p->regularprice -(($p->regularprice*$p->offerdiscount)/100)) * $spqty;
							}
						}
					}
				?>
                <ul class="cart-header">
						<li class="ring-in td1"><a href="home/single"><img src="img/product/<?php echo $p->picture;?>" class="img-responsive" style="max-width:100px;width:100%;"></a></li>
						<li class="td2 tdd"><?php if(isset($p->title)&&$p->title!=""){ echo $p->title;}?></li>
						<li class="td3 tdd"><?php if(isset($pprice)&&$pprice!=""){ echo number_format($pprice,2);}?></li>
                        <li class="td4 tdd" style="text-align:center"><?php if(isset($spqty)&&$spqty!=""){ echo $spqty;}?></li>
                        <li class="td5 tdd"><?php if(isset($spsize)&&$spsize!=""){ echo $spsize;}?></li>
                        <li class="td6 tdd"><?php if(isset($spcolor)&&$spcolor!=""){ echo $spcolor;}?></li>
					<div class="clearfix"> </div>
				</ul>
                <?php 
				}
				?>
				
			</div>   
            <div class="col-md-7 col-sm-7 col-xs-12"><span style="text-align:right;">
            	<!--<p><h4>Discount:&nbsp;<?php echo number_format($discount,2);?></h4></p>-->
                <p><h4>(+)Vat:&nbsp;<?php echo number_format($vat,2);?></h4></p>
                <p><h4>(+)Delivery charge:&nbsp;<?php echo number_format($deliverycharge,2);?></h4></p>
                <p>.....................................................</p>
                <?php 
				//coupon maximum purchase
				
				if(isset($couponcode)&& ($couponcode !="") && ($mpurchase <= $totalprice) && ($sp == "") && ($fp == 0) && ($ap == 0) && ($sc==""))
				{
					$couponvalue = $coupon->amount;
					$applycoupon = true;
				?>	
                <p><h4>(-)Coupon:&nbsp;<?php echo $couponvalue;?></h4></p>
                <p>.....................................................</p>
                
                <p><h4 style="font-weight:bold;">Payable Total:&nbsp;<?php echo number_format((($totalprice+$vat + $deliverycharge) - $couponvalue),2)?> </h4></p></span>
				<?php
					
				}
				else if(isset($couponcode)&& ($couponcode !="") &&  ($mpurchase <= $totalprice) && (($selectedproduct == true) || ($firstpurchase == true) || ($allproduct == true) || ($selectedcategory == true)))
				{
					$applycoupon = true;
				?>	
                <p><h4>(-)Coupon:&nbsp;<?php echo $couponvalue;?></h4></p>
                <p>.....................................................</p>
				<p><h4 style="font-weight:bold;">Payable Total:&nbsp;<?php echo number_format((($totalprice+$vat + $deliverycharge) - $couponvalue),2)?> </h4></p></span>
				<?php	
				}
				//coupon selected product
				else if(isset($couponcode)&& ($couponcode !="") && ($mpurchase ==0) && ($selectedproduct == true) && ($couponvalue !=0))
				{
					$applycoupon = true;
				?>
                <?php
				}
				//coupon first purchase
				else if(isset($couponcode)&& ($couponcode !="") && ($mpurchase ==0) && ($firstpurchase == true) && ($couponvalue !=0))
				{
					$applycoupon = true;
				?>	
                <p><h4>(-)Coupon:&nbsp;<?php echo $couponvalue;?></h4></p>
                <p>.....................................................</p>
				<p><h4 style="font-weight:bold;">Payable Total:&nbsp;<?php echo number_format((($totalprice+$vat + $deliverycharge) - $couponvalue),2)?> </h4></p></span>
                <?php 	
				}
				//coupon all product
				else if(isset($couponcode)&& ($couponcode !="") && ($mpurchase ==0) && ($allproduct == true) && ($couponvalue !=0))
				{
					$applycoupon = true;
				?>	
                <p><h4>(-)Coupon:&nbsp;<?php echo $couponvalue;?></h4></p>
                <p>.....................................................</p>
				<p><h4 style="font-weight:bold;">Payable Total:&nbsp;<?php echo number_format((($totalprice+$vat + $deliverycharge) - $couponvalue),2)?> </h4></p></span>
				<?php	
				}
				//coupon selected category
				else if(isset($couponcode)&& ($couponcode !="") && ($mpurchase ==0) && ($selectedcategory == true) && ($couponvalue !=0))
				{
					$applycoupon = true;
				?>	
                <p><h4>(-)Coupon:&nbsp;<?php echo $couponvalue;?></h4></p>
                <p>.....................................................</p>
				<p><h4 style="font-weight:bold;">Payable Total:&nbsp;<?php echo number_format((($totalprice+$vat + $deliverycharge) - $couponvalue),2)?> </h4></p></span>
                <?php
				}
				else
				{
				?>
                <p><h4 style="font-weight:bold;">Payable Total:&nbsp;<?php echo number_format((($totalprice)+$vat + $deliverycharge),2)?> </h4></p></span>
                <?php 
				}
				?>
            </div>
            <div class="col-md-5 col-sm-5"></div>
                
			</div>  
		 </div>
		</div>
	</div>
    
                  
                  <!----------------------------------------->
                  
                  
                </div>
                <div class="buttons"> 
                  <!--<div class="right"><a id="button-address" class="button"><span>Continue</span></a></div>--> 
                </div>
                <br>
              </div>
            </div>
          </div>
        </div>
        <!--<div class="col-lg-3 col-md-3 col-sm-12 margin-top-20-sm"> 
 </div>--> 
      </div>
      <!--/.row--> 
      <script>
	  	function checkcouponcode()
		{
			var couponcode = document.getElementById('couponcode').value; 
			document.getElementById('ecouponcode').value="";
			var err=0;
			var isvalid='false';
			if(couponcode == "")
			{
				err++; document.getElementById('ecouponcode').innerHTML="Required";
			}
			else if(couponcode != "")
			{
				<?php 
					if(isset($coupondata)&& count($coupondata)>0)
					{
						foreach($coupondata as $cd)
						{
				?>
							if(couponcode == '<?php echo $cd->code;?>')
							{
								var isvalid='true';
							}
				<?php 
						}
					}
				?>
				if(isvalid =='false')
				{
					err++; document.getElementById('ecouponcode').innerHTML="Invalid Code";
				}			
			}
			
			if(err == 0)
			{
				return true;
			}
			return false;
		}
	  </script>
      <div class="row">
                		<div class="col-md-5 col-sm-5 col-xs-12"></div>
                        <div class="col-md-5 col-sm-5 col-xs-12 tar">
                        <?php 
							if(!isset($couponid))
							{
						?>
                        Apply Gift Voucher or Promo Code<br />
                        <form action="<?php echo 'main/check_couponcode'?>" method="post" onsubmit="return checkcouponcode()">
                            <input type="text" name="couponcode" id="couponcode" />
                            <input type="submit" value="Apply" id="button-apply" style="background:#818A90;">
                            <br /><span id="ecouponcode" class="em"></span>
                            <?php 
								if(isset($_GET['eck']) && $_GET['eck'] !="") echo '<br><label class="em">'.$_GET['eck'].'</label>';
							?>
                        </form>
                        <?php }?>
                        </div>
                    </div>
                    <style>
					/*.continuebtn{display:block;padding:10px 25px;background:#000;color:#FFF;border-radius:5px;text-align:center;text-decoration:none;font-weight:bold;}*/

					/*.continuebtn : hover{background:#FF8000;color:#FFF;text-decoration:none;}*/
					body a{text-decoration:none !important;}
					</style>
                    <div class="row">
                    <div class="col-md-10 col-sm-10 col-xs-12">&nbsp;</div>
                	<div class="col-md-2 col-sm-2">
                    	<form action="<?php echo 'main/confirm_order'?>" method="post"> 
                        
                        	<input type="hidden" name="couponcode" value="<?php if(($applycoupon == true) && isset($couponcode)) echo $couponcode;?>" />
                            <input type="hidden" name="couponvalue" value="<?php if(($applycoupon == true) && isset($couponvalue)) echo $couponvalue;?>" />
                          	<input type="submit" value="Confirm Order" id="button-confirm"  style="float:right;"> <!--- class="button"-->
                          </form>
                    </div>
                </div>
      
    </div>
</div>