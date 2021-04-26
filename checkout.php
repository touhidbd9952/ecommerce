
<?php
	$jsondata ="";				
	$jsondata = $this->session->userdata('cartdata'); 
	$jsondata = json_decode($jsondata); 
	$spid ="";$spcolor ="";$spsize ="";$spqty ="";
	$spid = $jsondata->pid; 
	$spimg = $jsondata->pimg;
	$spcolor = $jsondata->pcolor; 
	$spsize = $jsondata->psize; 
	$spqty = $jsondata->pqty;
	
	 if (strpos($spid, ',')  !== false) 
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
?>					


<style>
.closesty{
    background: url('img/close.png') no-repeat 0px 0px;
    cursor: pointer;
    width: 28px;
    height: 28px;
    position: absolute;
    right: 5px;
    top: 10px;
    -webkit-transition: color 0.2s ease-in-out;
    -moz-transition: color 0.2s ease-in-out;
    -o-transition: color 0.2s ease-in-out;
    transition: color 0.2s ease-in-out;
}
.fa3x{color: #ea1010 !important;}
    
@media(min-width:768px)
{
	.fa3x{
    margin: 1.3em 0 0 !important;
    display: block !important;
    color: #ea1010 !important;
    text-align: left !important;
	font-size: 1.7em !important;}
}

</style>
<!-----   start-breadcrumbs ------------>
<div class="breadcrumbs">
		<div class="container">
			<div class="breadcrumbs-main">
				<ol class="breadcrumb">
					<li><a href="<?php echo 'main/home'?>">Home</a></li>
					<li class="active">Checkout</li>
				</ol>
			</div>
		</div>
	</div>

<!---   start-ckeckout --------->

<div class="ckeckout">
		<div class="container">
			<div class="ckeck-top heading">
				<h2>CHECKOUT</h2>
			</div>
			<div class="ckeckout-top">
			<div class="cart-items">
			 <h3>My Shopping Bag (<?php if($spid !="") {echo count($spid);}else {echo '0';}?>)</h3>
				<script>
					function deleteitem(arrid)
					{
							$.ajax({
							type: "POST",
							data: "arrid=" + arrid,
							url: "<?php echo 'main/deletecartitem';?>",
							success: function(data) {
								alert(data);
							   //location.reload();   
							   window.location.href = '<?php echo base_url();?>main/checkout';
							    
							}
						});
						
					}
			   </script>
			
				
			<div class="in-check">
				<ul class="unit">
					<li class="td1"><span>Item</span></li>
					<li class="td2"><span>Name</span></li>		
					<li class="td3"><span>Unit</span></li>
                    <li class="td4"><span>Qty</span></li>
					<li class="td5"><span>Size</span></li>
                    <li class="td6"><span>color</span></li>
					<li class="td7"> </li>
					<div class="clearfix"> </div>
				</ul>
                <?php
					
					$discount=0;
					$totalprice =0;
					$pprice="";
					$ptitle = "";
					$pimg = "";
					 if(count($spid)>1)  
					{
						for($i=0;$i<count($spid);$i++)
						{
							//$product = $this->db->query("select * from product where id='".$spid[$i]."' ")->result();
							foreach($product as $p)
							{
								if($p->id == $spid[$i])
								{
									if(($p->offerid =="")||($p->offerid =="0"))
									{
										$discount +=$p->discount;
										$pprice = ($p->regularprice -(($p->regularprice*$p->discount)/100));
										$totalprice += ($p->regularprice -(($p->regularprice*$p->discount)/100)) * $spqty[$i];
										$ptitle = $p->title;
										$pimg = $p->picture;
									}
									else
									{
										//$offerdata = $this->db->query("select * from t_offer where id='".$p->offerid."' ")->result();
										$startdate="";$enddata="";
										foreach($offerdata as $od)
										{
											if($od->id == $p->offerid)
											{
												$startdate = $od->startdate;
												$enddata = $od->enddate;
											}
										}
										$curdate = date('Y-m-d'); 
										if($curdate >= $startdate && $curdate <= $enddata)
										{
											$discount +=$p->offerdiscount;
											$pprice = ($p->regularprice -(($p->regularprice*$p->offerdiscount)/100));
											$totalprice += ($p->regularprice -(($p->regularprice*$p->offerdiscount)/100)) * $spqty[$i];
											$ptitle = $p->title;
											$pimg = $p->picture;
										}
										else
										{
											$discount +=$p->discount;
											$pprice = ($p->regularprice -(($p->regularprice*$p->discount)/100));
											$totalprice += ($p->regularprice -(($p->regularprice*$p->discount)/100)) * $spqty[$i];
											$ptitle = $p->title;
											$pimg = $p->picture;
										}
									}
								}
							}
				?>
				<ul class="cart-header">
					
						<li class="ring-in td1"><a href="home/single"><img src="img/product/<?php echo $pimg;?>" class="img-responsive" alt="" style="max-width:100px;width:100%;max-height:91px;"></a></li>
						<li class="td2"><span class="name"><?php if(isset($p->title)&&$p->title!=""){ echo $ptitle;}?></span></li>
						<li class="td3"><span><?php if(isset($pprice)&&$pprice!=""){ echo number_format($pprice,2);}?></span></li>
                        <li class="td4" style="text-align:center"><span><?php if(isset($spqty[$i])&&$spqty[$i]!=""){ echo $spqty[$i];}?></span></li>
                        <li class="td5"><span><?php if(isset($spsize[$i])&&$spsize[$i]!=""){ echo $spsize[$i];}?></span></li>
                        <li class="td6"><span><?php if(isset($spcolor[$i])&&$spcolor[$i]!=""){ echo $spcolor[$i];}?></span></li>
						<li class="td7" style="width:30px !important;float:right;text-align:right;"><?php if($p->id !="") {?><a href="<?php echo 'main/deletecartitem/'.$i?>"  style="display:block;"><span class="fa fa-trash fa3x" aria-hidden="true"></span></a><?php }?></li>
					<div class="clearfix"> </div>
				</ul>
				<?php 
					}
				}
				else
				{
					$ptitle = "";
					$pimg = "";
					$product = $this->db->query("select * from product where id='".$spid."' ")->result();
					foreach($product as $p)
					{
						$proid = $p->id;
						if(($p->offerid =="")||($p->offerid =="0"))
						{
							$pprice = ($p->regularprice -(($p->regularprice*$p->discount)/100));
							$totalprice = ($p->regularprice -(($p->regularprice*$p->discount)/100)) * $spqty;
							$ptitle = $p->title;
							$pimg = $p->picture;
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
								$pprice = ($p->regularprice -(($p->regularprice*$p->offerdiscount)/100));
								$totalprice += ($p->regularprice -(($p->regularprice*$p->offerdiscount)/100)) * $spqty;
								$ptitle = $p->title;
								$pimg = $p->picture;
							}
							else
							{
								$pprice = ($p->regularprice -(($p->regularprice*$p->discount)/100));
								$totalprice += ($p->regularprice -(($p->regularprice*$p->discount)/100)) * $spqty;
								$ptitle = $p->title;
								$pimg = $p->picture;
							}
						}
					}
				?>
                <ul class="cart-header">
						<li class="ring-in td1"><a href="home/single"><img src="img/product/<?php echo $pimg;?>" class="img-responsive" alt="" style="max-width:100px;width:100%;"></a></li>
						<li class="td2"><span class="name"><?php if(isset($p->title)&&$p->title!=""){ echo $ptitle;}?></span></li>
						<li class="td3"><span><?php if(isset($pprice)&&$pprice!=""){ echo number_format(($pprice),2);}?></span></li>
                        <li class="td4" style="text-align:center"><span><?php if(isset($spqty)&&$spqty!=""){ echo $spqty;}?></span></li>
                        <li class="td5"><span><?php if(isset($spsize)&&$spsize!=""){ echo $spsize;}?></span></li>
                        <li class="td6"><span><?php if(isset($spcolor)&&$spcolor!=""){ echo $spcolor;}?></span></li>
						<li class="td7" style="width:30px !important;float:right;text-align:right;"><?php if(isset($p->id)&&$p->id !="") {?><a href="<?php echo 'main/deletecartitem/'.$proid?>"  style="display:block;"><span class="fa fa-trash fa3x" aria-hidden="true"></span></a><?php }?></li>
					<div class="clearfix"> </div>
				</ul>
                <?php 
				}
				?>
				
			</div>
            <div class="col-md-7 col-sm-7 col-xs-12"><?php if($spid !=""){?><span style="text-align:right;"><p><h4 style="font-weight:bold;">Total:&nbsp;<?php echo number_format(($totalprice),2)?> </h4></p></span><?php }?></div>
            <div class="col-md-5 col-sm-5"></div>
                <div class="row">
                	<div class="col-md-10 col-sm-10 col-xs-12"></div>
                	<div class="col-md-2 col-sm-2"><?php if($spid !=""){?><a href="<?php echo 'main/checkout_delivery_details'?>" class="button" style="float:right;margin:20px 0px 0 0;">Continue</a><?php }?></div>
                </div>
			</div>  
		 </div>
		</div>
	</div>