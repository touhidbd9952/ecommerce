<link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap.min.css" />
<script type="text/javascript" src="js/jquery-1.12.4.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/dataTables.bootstrap.min.js"></script>
<style>
.style1 {
	border: 1px solid #D3D3D3;
	min-height: 120px;
	margin: 5px;
	font-family: "Roboto Condensed", sans-serif;
}
.divstyle1 {
	padding: 5px;
	font-family: Roboto, Helvetica, Arial, sans-serif;
	font-weight: bold;
}
.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
	font-family: inherit;
	font-weight: 500;
	line-height: 1.1;
	color: inherit;
}
.pu {
	padding-bottom: 5px !important;
	border-bottom: 1px solid #4b4b4b !important;
	margin-top: -1px;
	font-size: 14px;
}
.chemail {
	color: #000;
}
.chemail:hover {
	color: #73B6E1;
	text-decoration: none;
	font-size: 12px;
}
.i-edit {
	background: url(img/edit.png) no-repeat;
}
.i-address {
	background: url(img/address.png) no-repeat;
}
.i-phone {
	background: url(img/phone.png) no-repeat;
}
.editstyle {
	position: absolute;
	bottom: 10px;
	right: 40px;
	text-decoration: none;
	color: #000;
	font-size: 12px;
}
.editstyle:hover {
	color: #73B6E1;
	text-decoration: none;
	font-size: 12px;
}
.addressstyle {
	font-family: "Roboto Condensed", sans-serif;
	font-size: 12px;
	font-weight: normal;
	margin: 0px;
}
.phonestyle {
	font-family: "Roboto Condensed", sans-serif;
	font-size: 12px;
	font-weight: normal;
	margin-top: 10px;
}
.adm {
	float: right;
	margin-top: 5px;
	color: #000;
}
.adm:hover {
	color: #73B6E1;
	text-decoration: none;
}
.viewstyle:hover {
	color: #F90;
}
</style>
<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<div class="w3l_banner_nav_right" style="margin-bottom:30px; 0">
  <div class="w3ls_w3l_banner_nav_right_grid">
    <div class="col-md-12 col-sm-12 col-xs-12" style="margin:0 -15px;">
      <h4 style="margin:15px 0px;text-align:left;font-size: 2em;">My Orders</h4>
      <hr />
      <table id="example"  class="table table-bordered table-striped table-hover table-responsive">
        <thead>
          <tr>
            <th style="width:50px;">#</th>
            <th>Order</th>
            <th>Date</th>
            <th style="min-width:100px;">Order status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php 
				$sl=1;
				$orderdate ="";
				$orderstatus ="";
				if(count($customerdata)>0)
				{
					foreach($customerdata as $c)
					{
						$orderlist = $c->orderlist;
						if($orderlist !="")
						{
							if(strpos($orderlist,','))
							{
								$orderlist = explode(',',$orderlist);
								for($i=0; $i<count($orderlist);$i++)
								{
									if(count($purchasedata)>0)
									{
										foreach($purchasedata as $p)
										{
											if($p->orderid == $orderlist[$i])
											{
												$orderdate =$p->dateoforder;
												$orderstatus =$p->orderstatus; 
												if($orderstatus ==""){$orderstatus ="Pending";}
											}
										}
				?>
          <tr>
            <td><?php echo $sl++;?></td>
            <td><?php echo $orderlist[$i];?></td>
            <td><?php echo $orderdate;?></td>
            <td><?php echo $orderstatus;?></td>
            <td><a href="<?php echo 'customercontroller/order_details/'.$orderlist[$i]?>" style="text-decoration:none" class="viewstyle">[View]</a></td>
          </tr>
          <?php 
									}
								}
							}
							else
							{
								if(count($purchasedata)>0)
								{
										foreach($purchasedata as $p)
										{
											if($p->orderid == $orderlist)
											{
												$orderdate =$p->dateoforder;
												$orderstatus =$p->orderstatus;
												if($orderstatus ==""){$orderstatus ="Pending";}
											}
										}
				?>
          <tr>
            <td><?php echo $sl++;?></td>
            <td><?php echo $orderlist;?></td>
            <td><?php echo $orderdate;?></td>
            <td><?php echo $orderstatus;?></td>
            <td><a href="<?php echo 'customercontroller/order_details/'.$orderlist?>" style="text-decoration:none" class="viewstyle">[View]</a></td>
          </tr>
          <?php	
								}
							}
							
						}
					}
				}
			?>
        </tbody>
      </table>
    </div>
  </div>
</div>
