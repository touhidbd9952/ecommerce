
<?php
	$wishlist = $customerdata[0]->wish_list;
	 if (strpos($wishlist, ',') !== false) 
	{
			$wishlist = explode(',',$wishlist);
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
					<li class="active">Wishlist</li>
				</ol>
			</div>
		</div>
	</div>

<!---   start-ckeckout --------->

<div class="ckeckout">
		<div class="container">
			<div class="ckeck-top heading">
				<h2>WISH LIST</h2>
			</div>
			<div class="ckeckout-top">
			<div class="cart-items">
				<script>
					function deleteitem(procode)
					{
							$.ajax({
							type: "POST",
							data: "procode=" + procode,
							url: "<?php echo 'main/delete_wishlistitem';?>",
							success: function(data) {
							   location.reload();   
							}
						});
						
					}
			   </script>
			
				
			<div class="in-check">
				<ul class="unit">
					<li class="td1"><span>Item</span></li>
					<li class="td2"><span>Name</span></li>		
					<li class="td3"><span>Price</span></li>
                    <li></li>
					<div class="clearfix"> </div>
				</ul>
                <?php
					
					$discount=0;
					$totalprice =0;
					$pprice="";
					 if(count($wishlist)>1)  
					{
						for($i=0;$i<count($wishlist);$i++)
						{
							$protitle ="";
							$proimg ="";
							$procode ="";
							$pid="";
							foreach($product as $p)
							{
								if($p->code == $wishlist[$i])
								{
									$pid = $p->id;
									$protitle =$p->title;
									$proimg =$p->picture;
									if(($p->offerid =="")||($p->offerid =="0"))
									{
										$discount +=$p->discount;
										$pprice = ($p->regularprice -(($p->regularprice*$p->discount)/100));
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
										}
										else
										{
											$discount +=$p->discount;
											$pprice = ($p->regularprice -(($p->regularprice*$p->discount)/100));
										}
									}
								}
							}
				?>
				<ul class="cart-header">
						<li class="ring-in td1"><a href="<?php echo 'main/view/'.$pid?>"><img src="img/product/<?php echo $proimg;?>" class="img-responsive" alt="" style="max-width:100px;width:100%;"></a></li>
						<li class="td2"><span class="name"><?php if(isset($protitle)&&$protitle!=""){ echo $protitle;}?></span></li>
						<li class="td3"><span><?php if(isset($pprice)&&$pprice!=""){ echo number_format($pprice,2);}?></span></li>
						<li style="width:30px !important;float:right;text-align:right;"><?php if($pid !="") {?><a href="" onclick="deleteitem(<?php echo $wishlist[$i];?>)" style="display:block;"><span class="fa fa-trash fa3x" aria-hidden="true"></span></a><?php }?></li>
					<div class="clearfix"> </div>
				</ul>
				<?php 
					}
				}
				else
				{
					$product = $this->db->query("select * from product where code='".$wishlist."' ")->result();
					$protitle ="";
					$proimg ="";
					$procode ="";
					$pid="";
					foreach($product as $p)
					{
						$pid = $p->id;
						$protitle =$p->title;
						$proimg =$p->picture;
						$procode =$p->code;
						if(($p->offerid =="")||($p->offerid =="0"))
						{
							$pprice = ($p->regularprice -(($p->regularprice*$p->discount)/100));
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
							}
							else
							{
								$pprice = ($p->regularprice -(($p->regularprice*$p->discount)/100));
							}
						}
					}
				?>
                <ul class="cart-header">
						<li class="ring-in td1"><a href="<?php echo 'main/view/'.$pid?>"><img src="img/product/<?php echo $proimg;?>" class="img-responsive" alt="" style="max-width:100px;width:100%;"></a></li>
						<li class="td2"><span class="name"><?php if(isset($protitle)&&$protitle!=""){ echo $protitle;}?></span></li>
						<li class="td3"><span class="name"><?php if(isset($pprice)&&$pprice!=""){ echo number_format(($pprice),2);}?></span></li>
						<li style="width:30px !important;float:right;text-align:right;"><?php if($pid !="") {?><a href="javascript:" onclick="deleteitem(<?php echo $wishlist;?>)" style="display:block;"><span class="fa fa-trash fa3x" aria-hidden="true"></span></a><?php }?></li>
					<div class="clearfix"> </div>
				</ul>
                <?php 
				}
				?>
				
			</div>
            
            <div class="col-md-5 col-sm-5"></div>
                <div class="row">
                	<div class="col-md-9 col-sm-9 col-xs-12"></div>
                	<div class="col-md-3 col-sm-3"><?php if($pid !="") {?><a href="<?php echo 'main/delete_all_wish_list'?>" class="button" style="float:right;margin:20px 0px 0 0;">Delete All Wish List</a><?php }?></div>
                </div>
			</div>  
		 </div>
		</div>
	</div>