

<link rel="stylesheet" href="css/mystyle.css" />
<script src="js/myjs.js"></script>


<style type="text/css">
table{border-collapse:collapse;}
.tableHeader{font-size:24px;color:#000;padding:5px 0px;}
.tdStyle{text-align:center;}
th{text-align:center}
.btnStyle{padding:0px 20px;}
#message1{color:green; font-size:24px;}
#message2{color:red; font-size:24px;}
.linktag{line-height: 30px;text-decoration: none;padding: 0px 5px 0px 5px;display: block;color: #FFF;background-color: #3c8dbc;border-radius: 5px;margin-right: 5px;float:right;margin-top: 10px;}
</style>



<script>
function confirmationDelete(anchor)
{
   var conf = confirm('Are you sure want to delete this record?');
   if(conf)
      window.location=anchor.attr("href");
}
$(document).ready(function(e) {
    document.getElementById('searchword').style.display="none";
	document.getElementById('searchbutton').style.display="none";
});
function searchvalid()
{
	var searchcontent = document.getElementById('searchoption').value;
	var searchword = document.getElementById('searchword').value;
	if(searchcontent =="selectsearchword")
	{
		return false;
	}
	else if(searchcontent =="all")
	{
		if(searchword == ""){return true;}
	}
	else if(searchcontent =="showofferproduct")
	{
		if(searchword == ""){return true;}
	}
	else if(searchcontent =="bestseller")
	{
		if(searchword == ""){return true;}
	}
	else if(searchcontent !="all")
	{
		if(searchword == ""){return false;}
	}
	
}
</script>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 col-sm-12">
			
				<?php
                	if(isset($_GET['sk'])&& !empty($_GET['sk']))echo '<label class="ms mymsg fl-r">'.$_GET['sk'].'</label>';
					if(isset($_GET['esk'])&& !empty($_GET['esk']))echo '<label class="ems mymsg fl-r">'.$_GET['esk'].'</label>';
				?>
                <h3>Catalog &raquo; Promotion &raquo; Add/Remove Campain &raquo; Individual</h3>
                
                <div style="float:right;">
                	<form action="<?php echo 'offer_management/searchdata'?>" method="post" onsubmit="return searchvalid()">
                	<input type="submit" value="Search" id="searchbutton" style="float:right"/>
                	<input type="text" name="searchword" id="searchword" style="float:right"/>
                    
                    <select name="searchoption" id="searchoption" style="text-align:center; float:right;height:26px;" >
                    	<option value="selectsearchword">Search</option>
                        <option value="all">All</option>
                        <option value="code">Code</option>
                        <option value="modelno">Model No.</option>
                        <option value="title">Title</option>
                        <option value="buyprice">Buy Price</option>
                        <option value="saleprice">Sale Price</option>
                        <option value="category">Category</option>
                        <option value="stock">Stock</option>
                        <option value="showofferproduct">show offer</option>
                        <option value="bestseller">Best Seller</option>
                    </select>
                    </form>
                    
				</div>
        
        <table  class="table table-bordered table-striped table-hover table-responsive">
            <thead>
                <tr>
               <th>Picture</th>
               <th>Code</th>
               <th>ModelNo.</th>
               <th>Title</th>
               <!--<th>Description</th>-->
               <!--<th>Short Des</th>-->
               <th>Buy Price</th>
               <th>Sale Price</th>
               <th>Category</th>
               <th>Sub category</th>
               <th>Unit</th>
               <th>Color</th>
               <th>Discount %</th>
               <th>Vat</th>
               <th>Stock</th>
               <th style="min-width:150px"></th>
            </tr>
            </thead>
            <tbody>
            	<?php 
					$serial = 0;
					foreach($model as $d)
					{							
							foreach($unit as $u)
							{
								if($d->unitid == $u->id)
								{
									$unitname = $u->name;
								}
							}
							
							foreach($color as $c)
							{
								if($d->colorid == $c->id)
								{
									$colorname = $c->name; 
								}
							}
							foreach($subCategory as $subc)
							{
								if($d->subcategoryid == $subc->id)
								{
									$subcategory = $subc->name;
									$categoryid = $subc->categoryid;
									foreach($Category as $cat)
									{
										if($categoryid ==$cat->id )
										{
											$category = $cat->name;
										}
									}
								}
							}
				?>
                <tr>
                	<td><?php if(isset($d->picture)&& !empty($d->picture)){?><img src="<?php echo 'img/product/'.$d->picture ?>" width='50'><?php }?></td>
                    <td><?php if(isset($d->code)) echo $d->code;?></td>
                    <td><?php if(isset($d->modelno)) echo $d->modelno;?></td>
                    <td><div style="max-width:120px;max-height:60px;overflow:hidden;"><?php if(isset($d->title)) echo $d->title;?></div></td>
                    <!--<td><div style="max-width:120px;max-height:60px;overflow:hidden;"><?php if(isset($d->description)) echo $d->description;?></div></td>-->
                    <!--<td><div style="max-width:120px;max-height:60px;overflow:hidden;"><?php if(isset($d->shortdes)) echo $d->shortdes;?></div></td>-->
                    <td><?php if(isset($d->buyprice)) echo $d->buyprice;?></td>
                    <td><?php if(isset($d->saleprice)) echo $d->saleprice;?></td>
                    <td><?php if(isset($category)) echo $category;?></td>
                    <td><?php if(isset($subcategory)) echo $subcategory;?></td>
                    <td><?php if(isset($unitname)) echo $unitname;?></td>
                    <td><?php if(isset($colorname)) echo $colorname;?></td>
                    <td><?php if(isset($d->discount)) echo $d->discount;?></td>
                    <td><?php if(isset($d->vat)) echo $d->vat;?></td>
                    <td><?php if(isset($d->stock)) echo $d->stock;?></td>
                    <td style="width:80px;">
                    	<a href="<?php echo 'offer_management/add_in_offer/'.$d->id?>"><span class="label label-primary">Edit</span></a>&nbsp;
                        
                        <?php if($d->offerid > 0){?><img src="img/Special-offer.png" style="width:50px;" /><?php }?>
                        <?php if($d->bestseller != ""){?><img src="img/bestseller.png" style="width:35px;" /><?php }?>
                    </td>
                </tr>
                <?php 
					}
					
				?>
            </tbody>
        </table>
        
        <style>
                    a {-webkit-transition: color .24s ease-in-out;-moz-transition: color .24s ease-in-out;-o-transition: color .24s ease-in-out;-ms-transition: color .24s ease-in-out;transition: color .24s ease-in-out;color: #2777BB;}
                    a.page, a.pgactive {padding: 5px 10px;border: 1px solid #ccc;margin: 0 10px;}
                    a.pgactive { color:#FF8000;}
                </style>
                <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12 m10">
                	<center>
                    <?php
					//product($catid,$catname)
                    $c = 0;
                    $p = 1; 
                    $count=1;                                   
                    if($start > 1)
                    {
                        echo "<a href='offer_management/individualy_wise_add_offer?page=" . ($start-1)/$per_page . "' class='page'>PREV</a>";    
                    }
                    for($c =0;  $c < count($productlist);$c++) 
                    {
                        if($count <= 30)
                        {
                            if($c%$per_page==0)
                            {
                                if(($end-1)/$per_page == $p)
                                {
                                    echo "<a href='offer_management/individualy_wise_add_offer?page={$p}' class='pgactive'>{$p}</a>";
                                }
                                else
                                {
                                    echo "<a href='offer_management/individualy_wise_add_offer?page={$p}' class='page'>{$p}</a>";
                                }
                                $p++;
                            }
                            $count++;
                        }
                        
                    }
                    if($end < count($productlist))
                    {
                        echo '...'."<a href='offer_management/individualy_wise_add_offer?page=" . ((($end-1)/$per_page)+1) . "' class='page'>NEXT</a>";
                    }
                    ?>
                    </center>
              </div>
              
        </div>
        </div>
        </div>      
                                  


