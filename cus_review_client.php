
<link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap.min.css" />
<script type="text/javascript" src="js/jquery-1.12.4.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/dataTables.bootstrap.min.js"></script>


<script>
function reviewDelete(anchor)
{
   var conf = confirm('Are you sure want to delete this review?');
   if(conf)
      window.location=anchor.attr("href");
}
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>

<div class="w3l_banner_nav_right" style="margin-bottom:30px; 0">
  <div class="w3ls_w3l_banner_nav_right_grid">
    <div class="col-md-12 col-sm-12 col-xs-12" style="margin:0 -15px;">
     <h4 style="margin:15px 15px;text-align:left;font-size: 2em;">My Review</h4>
	<hr>
    	<table id="example"  class="table table-bordered table-striped table-hover table-responsive">
        	<thead>
        	<tr>
            	<th style="width:50px;">#</th>
                <th style="width:80px;">img</th>
                <th>Title</th>
                <th style="max-width:80px;">Date</th>
                <th>Review</th>
                <th>Rating</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            	<?php 
					$sl=1;
					$imgurl="";
					$title="";
					$date="";
					$review="";
					$rating="";
					$pcode="";
					
					if($customerdata>0)
					{
						foreach($customerdata as $cd)
						{
							if($product>0)
							{
								foreach($product as $p)
								{
									if($p->code == $cd->code)
									{
										$imgurl = $p->picture;
										$title = $p->title;
										$date = $cd->reviewdate;
										$review = $cd->review;
										$pcode = $p->code;
										if($cd->rating !="")
										{ 
											if($cd->rating=='1'){$rating="1";}
											if($cd->rating=='2'){$rating="2";}
											if($cd->rating=='3'){$rating="3";}
											if($cd->rating=='4'){$rating="4";}
											if($cd->rating=='5'){$rating="5";}
										}
									}
								}
							}
				?>
                <tr>
                    <td><?php echo $sl++;?></td>
                    <td><?php if($imgurl !=""){?><img src="<?php echo 'img/product/'.$imgurl?>" width="50px;" /><?php }?></td>
                    <td style="font-size:10px;max-height:80px;"><?php echo $title;?></td>
                    <td><?php echo $date;?></td>
                    <td style="font-size:10px;max-height:80px;"><?php echo $review;?></td>
                    <td><?php echo $rating;?></td>
                    <td><a href="<?php echo 'customercontroller/delete_review/'.$pcode;?>" onclick="javascript:reviewDelete($(this));return false;" style="text-decoration:none;"><span class="label label-danger">Delete</span></a></td>
            	</tr>
                <?php 
						}
					}
				?>
            </tbody>
    	</table>
        
    </div>
</div>
</div>
