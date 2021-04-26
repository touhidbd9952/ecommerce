
<?php 
	$specialads = $this->db->query("select * from t_offer order by indexno asc")->result(); 
?>

<style>
@media(min-width:992px)
{
	.product-main {height: 437px;}
	.product-main img{height: 260px;}
}
.product-bottom h4 i {
    background: url(img/cart-2.png) no-repeat;
    width: 20px;
    height: 15px;
    display: inline-block;
    margin-right: 4px;
    vertical-align: middle;
}
.product-left:hover .product-bottom h4 i{
	background: url(img/cart-3.png) no-repeat;
}
</style>

<?php 
if(count($allproduct)>0)
{
?>
	<!--product-starts-->
	<div class="product"> 
		<div class="container">
			<div class="product-top">
            	<div class="product-one">
                	
            	<?php 
					$c=0;
					$j=1;
					$count=0;
					$totalproduct = 20;
					$price = "";
					if(isset($allproduct) && (count($allproduct)>0))
					{
						$count = count($allproduct); 
						foreach($allproduct as $apro)
						{
							//$price = $apro->regularprice - (($apro->regularprice * $apro->discount)/100);
							$startdate="";$enddata="";
							if(count($offerdata)>0)
							{
								foreach($offerdata as $od)
								{
									if($od->id == $apro->offerid)
									{
										$startdate = $od->startdate;
										$enddata = $od->enddate;
									}
								}
								$curdate = date('Y-m-d');
								if($curdate >= $startdate && $curdate <= $enddata)
								{
									$discount =$apro->offerdiscount;
									$price = ($apro->regularprice -(($apro->regularprice*$apro->offerdiscount)/100));
								}
								else
								{
									$discount =$apro->discount;
									$price = ($apro->regularprice -(($apro->regularprice*$apro->discount)/100));
								}
							}
							else
							{
								$discount =$apro->discount;
								$price = ($apro->regularprice -(($apro->regularprice*$apro->discount)/100));
							}
							if($c==0)
							{
								echo '<div class="row">';
							}
				?>
						
                                <div class="col-md-3 product-left" style="margin-bottom:15px;">
                                    <div class="product-main simpleCart_shelfItem">
                                        <a href="<?php echo 'main/view_offer/'.$apro->id?>" class="mask"><img class="zoom-img" src="img/product/<?php echo $apro->picture;?>" alt="<?php echo $apro->title;?>" style="max-width:260px;max-height:260px;"></a>
                                        <div class="product-bottom">
                                            <h3><?php echo $apro->title;?></h3>
                                            <p>Explore Now</p>
                                            <h4>
                                            <a class="item_add" href="<?php echo 'main/view_offer/'.$apro->id?>"><i></i></a> 
                                            <span class=" item_price"><?php echo $price?>
                                            <?php 
												if($apro->saleprice < $apro->regularprice)
												{
												?>
                                            	<span style="text-decoration:line-through;color:#F00;font-size:17px;font-family:'BreeSerif-Regular';padding-left:5px;"><?php echo $apro->regularprice;?></span>  
                                                <?php
												}
												?>
                                            </span></h4>
                                        </div>
                                        <!--<div class="srch">
                                            <span><?php echo $apro->discount.'%'?></span>
                                        </div>-->
                                    </div>
                                </div>   
               <?php             
							$c++;
							$j++;
							if($c==4 )
							{
								echo '</div>'; $c=0;
								echo '<div class="clearfix"></div>';
							}
											
						}
					}
				?>
                
				<div class="clearfix"></div>
                
                <br />
                        <!------------------------------------------------------------------------------>
                    
                    <style>
                    a {-webkit-transition: color .24s ease-in-out;-moz-transition: color .24s ease-in-out;-o-transition: color .24s ease-in-out;-ms-transition: color .24s ease-in-out;transition: color .24s ease-in-out;color: #2777BB;}
                    a.page, a.pgactive {padding: 5px 10px;border: 1px solid #ccc;margin: 0 10px;}
                    a.pgactive { color:#FF8000;}
                </style>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m10">
                	<center>
                    <?php
                    $c = 0;
                    $p = 1; 
                    $count=1;                                   
                    if($start > 1)
                    {
                        echo "<a href='".base_url()."main/offer/".$offerid."/".$offertitle."?page=" . ($start-1)/$per_page . "' class='page'>PREV</a>";    // href='".  base_url(). "?page=" . ($start-1)/$per_page . "'
                    }
                    for($c =0;  $c < $productlist;$c++) 
                    {
                        if($count <= 30)
                        {
                            if($c%$per_page==0)
                            {
                                if(($end-1)/$per_page == $p)
                                {
                                    echo "<a href='".base_url()."main/offer/".$offerid."/".$offertitle."?page={$p}' class='pgactive'>{$p}</a>";
                                }
                                else
                                {
                                    echo "<a href='".base_url()."main/offer/".$offerid."/".$offertitle."?page={$p}' class='page'>{$p}</a>";
                                }
                                $p++;
                            }
                            $count++;
                        }
                        
                    }
                    if($end < $productlist)
                    {
                        echo '...'."<a href='".base_url()."main/offer/".$offerid."/".$offertitle."?page=" . ((($end-1)/$per_page)+1) . "' class='page'>NEXT</a>";
                    }
                    ?>
                    </center>
              </div>
                    <br /><br />
                    <!------------------------------------------------------------------------------>
            
               </div>			
									
			</div> 
		</div>
	</div>
	<!--product-end-->
 <?php 
}
?>
    
    