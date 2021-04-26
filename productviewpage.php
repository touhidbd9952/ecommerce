<body onLoad="defaultselectedshortitem()">

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

<!--banner-starts-->
	<div class="bnr" id="home">
		<div id="top" class="callbacks_container">
			<ul class="rslides callbacks callbacks1" id="slider4">
            	<?php 
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
            <a href="javascript:" class="callbacks_nav callbacks1_nav prev">Previous</a>
            <a href="javascript:" class="callbacks_nav callbacks1_nav next">Next</a>
            <ul class="callbacks_tabs callbacks1_tabs"><li class="callbacks1_s1">
            	<a href="javascript:" class="callbacks1_s1">1</a></li>
                	<li class="callbacks1_s2 callbacks_here">
                <a href="javascript:" class="callbacks1_s2">2</a></li>
                <li class="callbacks1_s3"><a href="javascript:" class="callbacks1_s3">3</a></li>
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
	
    
    <style>
		.dropdown1{position: relative;width: 200px;cursor:pointer;font-weight: 200;padding: 0;background: #fff;color:#000;float:right;}
		.dropdown1 .btn{padding:0 0 0 10px;background: #fff;color: #000;box-shadow: none !important;border:1px solid #CCC;width:200px;text-align:left;}
		.dropdown1.open div div{width: 200px;height:30px;line-height:30px;padding-left:10px;}
		.dropdown1.open div div a{text-decoration:none !important;text-align:left;color:#6c6969;}
		.dropdown1.open div div a:hover{text-decoration:none !important;text-align:left;color:#3CF;}
		.slectedshortname{font-weight:normal;}
		.btn-secondary{color:#000;}
		.dropdown1 .dropdown-toggle::after {display: inline-block;width: 0;height: 0;margin-left: .255em;vertical-align: .255em;content: "";border-top: .3em solid;border-right: .3em solid transparent;border-bottom: 0;border-left: .3em solid transparent;float:right;margin-right:5px;}
	</style>
	<!--product-starts-->
	<div class="product"> 
    <?php 
		if(isset($allproduct) && (count($allproduct)>0))
		{
	?>
    	<div class="container" style="margin-bottom:20px;">
        	<div class="dropdown1">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Sort by: <?php if(isset($shortcutmenu)&& $shortcutmenu !=""){ echo '<span style="color:#999;">'.$shortcutmenu.'</span>';}?>
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="width:200px;">
               <div> <a class="dropdown-item" href="<?php echo 'main/products/'.$selectedcatid.'/'.$catname.'/'.urlencode('Most Popular');?>" >Most Popular</a></div>
                <div><a class="dropdown-item" href="<?php echo 'main/products/'.$selectedcatid.'/'.$catname.'/'.urlencode('New In');?>" >New In</a></div>
                <div><a class="dropdown-item" href="<?php echo 'main/products/'.$selectedcatid.'/'.$catname.'/'.urlencode('Lowest Price');?>" >Lowest Price</a></div>
                <div><a class="dropdown-item" href="<?php echo 'main/products/'.$selectedcatid.'/'.$catname.'/'.urlencode('Highest Price');?>" >Highest Price</a></div>
                <div><a class="dropdown-item" href="<?php echo 'main/products/'.$selectedcatid.'/'.$catname.'/'.urlencode('Best Rating');?>" >Best Rating</a></div>
              </div>
            </div>
        </div>
     <?php }?>   
        
        
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
                                            <h4><a class="item_add" href="<?php echo 'main/view/'.$apro->id?>"><i></i></a> <span class=" item_price"><?php echo $price?>
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
                                            <span><?php if($apro->discount=='0.00'){echo '0%';}else{ echo $apro->discount.'%';}?></span>
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
					//product($catid,$catname)
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
									if(isset($shortcutmenu)&&$shortcutmenu !="")
									{
										echo "<a href='".base_url()."main/products/".$selectedcatid."/".$catname."/".urldecode($shortcutmenu)."?page={$p}' class='pgactive'>{$p}</a>";
									}
									else
									{
                                    	echo "<a href='".base_url()."main/product/".$selectedcatid."/".$catname."?page={$p}' class='pgactive'>{$p}</a>";
									}
                                }
                                else
                                {
									if(isset($shortcutmenu)&&$shortcutmenu !="")
									{
										echo "<a href='".base_url()."main/products/".$selectedcatid."/".$catname."/".urldecode($shortcutmenu)."?page={$p}' class='page'>{$p}</a>";
									}
									else
									{
                                    	echo "<a href='".base_url()."main/product/".$selectedcatid."/".$catname."?page={$p}' class='page'>{$p}</a>";
									}
                                }
                                $p++;
                            }
                            $count++;
                        }
                        
                    }
                    if($end < $productlist)
                    {
						if(isset($shortcutmenu)&&$shortcutmenu !="")
						{
							echo '...'."<a href='".base_url()."main/products/".$selectedcatid."/".$catname."/".urldecode($shortcutmenu)."?page=" . ((($end-1)/$per_page)+1) . "' class='page'>NEXT</a>";
						}
						else
						{
                        	echo '...'."<a href='".base_url()."main/product/".$selectedcatid."/".$catname."?page=" . ((($end-1)/$per_page)+1) . "' class='page'>NEXT</a>";
						}
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
    
  </body>  
    
    