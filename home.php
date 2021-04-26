
<?php 
	include("visitors.php");
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
.about {
    padding: 1em 0px 1em 0px;
}
</style>

<!--banner-starts-->
	<div class="bnr" id="home">
		<div id="top" class="callbacks_container">
			<ul class="rslides callbacks callbacks1" id="slider4">
            	<?php 
					//$selectedcatid = $this->mm->getSet('home page category');
					$selectedcatid = -1;
					if($selectedcatid == ""){$this->db->query("Select id from category where indexnumber=1")->result();}
					$allbanner = $this->db->query("Select * from t_banner where catid='".$selectedcatid."' ")->result();
				   if(isset($allbanner))
				   {
					   foreach($allbanner as $ad)
					   {
			    ?>
			    <li id="callbacks1_s0" style="display: list-item; transition: opacity 500ms ease-in-out 0s; float: none; position: absolute; opacity: 0; z-index: 1;" class="">
                
					<img src="<?php echo 'img/banner/'.$ad->pic?>" alt="">
                 
				</li>
                <?php
					   }
				   }
				  ?>
			</ul>
            <a href="#" class="callbacks_nav callbacks1_nav prev">Previous</a>
            <a href="#" class="callbacks_nav callbacks1_nav next">Next</a>
            <ul class="callbacks_tabs callbacks1_tabs"><li class="callbacks1_s1">
            	<a href="#" class="callbacks1_s1">1</a></li>
                	<li class="callbacks1_s2 callbacks_here">
                <a href="#" class="callbacks1_s2">2</a></li>
                <li class="callbacks1_s3"><a href="#" class="callbacks1_s3">3</a></li>
           </ul>
		</div>
		<div class="clearfix"> </div>
        <br />
	</div>
	<!--banner-ends--> 
	<!--Slider-Starts-Here-->
				<script src="js/theme1/responsiveslides.js"></script>
			 <script>
			    // You can also use "$(window).load(function() {"
			    $(function () {
			      // Slideshow 4
			      $("#slider4").responsiveSlides({
			        auto: true,
			        pager: true,
			        nav: true,
			        speed: 500,
			        namespace: "callbacks",
			        before: function () {
			          $('.events').append("<li>before event fired.</li>");
			        },
			        after: function () {
			          $('.events').append("<li>after event fired.</li>");
			        }
			      });
			
			    });
			  </script>
			<!--End-slider-script-->
	<!--about-starts-->
    <?php 
		if(count($specialads)>0)
		{
	?>
	<div class="about"> 
		<div class="container">
        	
			<div class="about-top grid-1">
            
            <?php	
				$c=1;
				$curdate = date('Y-m-d'); 	
					foreach($specialads as $sads)
					{
						$title =$sads->title;
						if(strpos($sads->title,'%')){$title = str_replace('%',' Percent',$sads->title);} //codeconversion
						if(isset($sads->id)&&$sads->id !="")
						{
							$startdate="";$enddata="";
							$startdate = $sads->startdate;
							$enddata = $sads->enddate;
							if($curdate >= $startdate && $curdate <= $enddata)
							{
							if($c<4)
							{
			?>
                            <div class="col-md-4 about-left">
                                
                                <a href="<?php echo 'main/offer/'.$sads->id.'/'.urlencode($title)?>"><img class="img-responsive" src="img/offer/<?php echo $sads->pic;?>" alt="<?php echo $title;?>" style="max-height:200px;"></a>
                                    
                            </div>
			<?php
							}
						$c++;
							}
						}
					}
			?>
            
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
    <?php			
		}
	?>
	<!--about-end-->
    
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
					$price ="";
					if(isset($allproduct) && (count($allproduct)>0))
					{
						$count = count($allproduct); 
						foreach($allproduct as $apro)
						{
							//$offerdata = $this->db->query("select * from t_offer where id='".$apro->offerid."' ")->result();
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
                                        <a href="<?php echo 'main/view/'.$apro->id?>" class="mask"><img class="zoom-img" src="img/product/<?php echo $apro->picture;?>" alt="<?php echo $apro->title;?>" style="max-width:260px;max-height:260px;"></a>
                                        <div class="product-bottom">
                                        	<div style="height:80px;overflow:hidden">
                                            <h3><?php echo $apro->title;?></h3>
                                            <p>Explore Now</p>
                                            </div>
                                            <h4>
                                            	<a class="item_add" href="<?php echo 'main/view/'.$apro->id?>"><i></i></a> 
                                            	<span class=" item_price"><?php echo $price;?> 
                                                <?php 
												if($apro->saleprice < $apro->regularprice)
												{
												?>
                                            	<span style="text-decoration:line-through;color:#F00;font-size:17px;font-family:'BreeSerif-Regular';padding-left:5px;"><?php echo $apro->regularprice;?></span>  
                                                <?php
												}
												?>
                                                </span>
                                			</h4>
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
                        echo "<a href='".base_url()."main/home?page=" . ($start-1)/$per_page . "' class='page'>PREV</a>";    // href='".  base_url(). "?page=" . ($start-1)/$per_page . "'
                    }
                    for($c =0;  $c < $productlist;$c++) 
                    {
                        if($count <= 30)
                        {
                            if($c%$per_page==0)
                            {
                                if(($end-1)/$per_page == $p)
                                {
                                    echo "<a href='".base_url()."main/home?page={$p}' class='pgactive'>{$p}</a>";
                                }
                                else
                                {
                                    echo "<a href='".base_url()."main/home?page={$p}' class='page'>{$p}</a>";
                                }
                                $p++;
                            }
                            $count++;
                        }
                        
                    }
                    if($end < $productlist)
                    {
                        echo '...'."<a href='".base_url()."main/home?page=" . ((($end-1)/$per_page)+1) . "' class='page'>NEXT</a>";
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
    
    
    
    
    
    <!------------- Best Seller Start ------------------------------------------------------------------------------------------------------->
    <?php
    if(isset($bestseller) && (count($bestseller)>0))
	{
	?>	
    <div class="product"> 
		<div class="container">
        	<div style="width:100%;border:1px solid #ECEAEA;margin-bottom:20px;">
        		<center><h2 style="text-transform:uppercase">Best Seller</h2></center>
            </div>
                
			<div class="product-top">
            	<div class="product-one">
                	
            	<?php 
					$c=0;
					$j=1;
					$count=0;
					$price ="";
						$count = count($bestseller); 
						foreach($bestseller as $p)
						{
							$startdate="";$enddata="";
							if(count($offerdata)>0)
							{
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
									$discount =$p->offerdiscount;
									$price = ($p->regularprice -(($p->regularprice*$p->offerdiscount)/100));
								}
								else
								{
									$discount =$p->discount;
									$price = ($p->regularprice -(($p->regularprice*$p->discount)/100));
								}
							}
							else
							{
								$discount =$p->discount;
								$price = ($p->regularprice -(($p->regularprice*$p->discount)/100));
							}
							if($c==0)
							{
								echo '<div class="row">';
							}
							
				?>
                                <div class="col-md-3 product-left" style="margin-bottom:15px;">
                                    <div class="product-main simpleCart_shelfItem">
                                        <a href="<?php echo 'main/view/'.$p->id?>" class="mask"><img class="zoom-img" src="img/product/<?php echo $p->picture;?>" alt="<?php echo $p->title;?>" style="max-width:260px;max-height:260px;"></a>
                                        <div class="product-bottom">
                                        	<div style="height:80px;overflow:hidden">
                                            <h3><?php echo $p->title;?></h3>
                                            <p>Explore Now</p>
                                            </div>
                                            <h4><a class="item_add" href="<?php echo 'main/view/'.$p->id?>"><i></i></a> <span class=" item_price"><?php echo $price?>
                                            <?php 
											if($p->saleprice < $p->regularprice)
											{
											?>
											<span style="text-decoration:line-through;color:#F00;font-size:17px;font-family:'BreeSerif-Regular';padding-left:5px;"><?php echo $p->regularprice;?></span>  
											<?php
											}
											?>
                                            </span></h4>
                                        </div>
                                        <!--<div class="srch">
                                            <span><?php echo $p->discount.'%'?></span>
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
				?>

                    <br /><br />
                    <!------------------------------------------------------------------------------>
               </div>			
									
			</div> 
		</div>
	</div>
    <?php }?>
    <!-------------- Best Seller End -------------------------------------------------------------------------------------------------------->
    
    
    
    