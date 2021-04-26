


  	<body onLoad="myFunc()">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="javascript:"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <?php
		$orpending=0; 
		$orconfirm=0; 
		$orcancel=0; 
		$orcomplete=0; 
		if(count($purchase)>0)
		{
			foreach($purchase as $p)
			{
			 if($p->orderstatus == ""){++$orpending;}
			 if($p->orderstatus == "Conform"){++$orconfirm;}
			 if($p->orderstatus == "Cencel"){++$orcancel;}
			 if($p->orderstatus == "Complete"){++$orcomplete;}
			}
			
		}
	?>
    <section class="content">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Order Pending</span>
              <span class="info-box-number"><?php echo$orpending; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-shopping-cart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Order Confirm</span>
              <span class="info-box-number"><?php echo$orconfirm; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon label-success"><i class="fa fa-shopping-cart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Order Completed</span>
              <span class="info-box-number"><?php echo$orcomplete; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-shopping-cart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Order Cancel</span>
              <span class="info-box-number"><?php echo$orcancel; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Monthly Sales Report</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a <?php echo 'javascript:';?>>Action</a></li>
                    <li><a <?php echo 'javascript:';?>>Another action</a></li>
                    <li><a <?php echo 'javascript:';?>>Something else here</a></li>
                    <li class="divider"></li>
                    <li><a <?php echo 'javascript:';?>>Separated link</a></li>
                  </ul>
                </div>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-8">
                  <p class="text-center">
                    <strong>Sales: 1 Jan, <?php echo date('Y')?> - 31 Dec, <?php echo date('Y')?></strong>
                  </p>

                  <div class="chart">
                    <!-- Sales Chart Canvas -->
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
					<script type="text/javascript">
						google.charts.load('current', {'packages':['corechart']});
                      	google.charts.setOnLoadCallback(drawChart);
                
                      function drawChart() {				   
                        var data = google.visualization.arrayToDataTable([
                          ['Month', 'Order pending', 'Order cancel', 'Order confirm', 'Order complete'],
						  <?php 
						  foreach($salesorder as $d)
						  {
							  if($d['month']=='jan')
							  {
						  ?>
						  
                          ['Jan',  <?php echo $d['orpending'];?>, <?php echo $d['orcancel'];?>,<?php echo $d['orconfirm'];?>,<?php echo $d['orcomplete'];?>],
						  <?php 
						  	 }
						  	 if($d['month']=='feb')
							 {
						  ?>
                          ['Feb', <?php echo $d['orpending'];?>, <?php echo $d['orcancel'];?>,<?php echo $d['orconfirm'];?>,<?php echo $d['orcomplete'];?>],
						  <?php 
						  	 }
						  	 if($d['month']=='mar')
							 {
						  ?>
                          ['Mar', <?php echo $d['orpending'];?>, <?php echo $d['orcancel'];?>,<?php echo $d['orconfirm'];?>,<?php echo $d['orcomplete'];?>],
						  <?php 
						  	 }
						  	 if($d['month']=='apr')
							 {
						  ?>
                          ['Apr',  <?php echo $d['orpending'];?>, <?php echo $d['orcancel'];?>,<?php echo $d['orconfirm'];?>,<?php echo $d['orcomplete'];?>],
						  <?php 
						  	 }
						  	 if($d['month']=='may')
							 {
						  ?>
                          ['May',  <?php echo $d['orpending'];?>, <?php echo $d['orcancel'];?>,<?php echo $d['orconfirm'];?>,<?php echo $d['orcomplete'];?>],
						  <?php 
						  	 }
						  	 if($d['month']=='jun')
							 {
						  ?>
                          ['Jun',  <?php echo $d['orpending'];?>, <?php echo $d['orcancel'];?>,<?php echo $d['orconfirm'];?>,<?php echo $d['orcomplete'];?>],
						  <?php 
						  	 }
						  	 if($d['month']=='jul')
							 {
						  ?>
                          ['Jul',  <?php echo $d['orpending'];?>, <?php echo $d['orcancel'];?>,<?php echo $d['orconfirm'];?>,<?php echo $d['orcomplete'];?>],
						  <?php 
						  	 }
						  	 if($d['month']=='aug')
							 {
						  ?>
                          ['Aug',  <?php echo $d['orpending'];?>, <?php echo $d['orcancel'];?>,<?php echo $d['orconfirm'];?>,<?php echo $d['orcomplete'];?>],
						  <?php 
						  	 }
						  	 if($d['month']=='sep')
							 {
						  ?>
                          ['Sep',  <?php echo $d['orpending'];?>, <?php echo $d['orcancel'];?>,<?php echo $d['orconfirm'];?>,<?php echo $d['orcomplete'];?>],
						  <?php 
						  	 }
						  	 if($d['month']=='oct')
							 {
						  ?>
                          ['Oct',  <?php echo $d['orpending'];?>, <?php echo $d['orcancel'];?>,<?php echo $d['orconfirm'];?>,<?php echo $d['orcomplete'];?>],
						  <?php 
						  	 }
						  	 if($d['month']=='nov')
							 {
						  ?>
                          ['Nov',  <?php echo $d['orpending'];?>, <?php echo $d['orcancel'];?>,<?php echo $d['orconfirm'];?>,<?php echo $d['orcomplete'];?>],
						  <?php 
						  	 }
						  	 if($d['month']=='dec')
							 {
						  ?>
                          ['Dec',  <?php echo $d['orpending'];?>, <?php echo $d['orcancel'];?>,<?php echo $d['orconfirm'];?>,<?php echo $d['orcomplete'];?>],
						  <?php 
						  	}
						  }
						  ?>
                        ]);
                
                        var options = {
                          title: 'Orders',
                          curveType: 'function',
                          legend: { position: 'bottom' }
                        };
                
                        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                
                        chart.draw(data, options);
                      }
                    </script>
                    
                    <div id="curve_chart" style="width: 1200px; height: 300px"></div>
                  </div>
                  <!-- /.chart-responsive -->
                </div>
                
                <?php
				$preorpending=0; 
				$preorconfirm=0; 
				$preorcancel=0; 
				$preorcomplete=0; 
				if(count($preyearsalesorder)>0)
				{
					foreach($preyearsalesorder as $pre)
					{
					 if($pre->orderstatus == ""){++$preorpending;}
					 if($pre->orderstatus == "Conform"){++$preorconfirm;}
					 if($pre->orderstatus == "Cencel"){++$preorcancel;}
					 if($pre->orderstatus == "Complete"){++$preorcomplete;}
					}
					
				}
			?>
                <!-- /.col -->
                <div class="col-md-4">
                  <p class="text-center">
                    <strong>Order Status <?php echo date('Y')-1?></strong>
                  </p>

                  <div class="progress-group">
                    <span class="progress-text">Order pending</span>
                    <span class="progress-number"><?php echo $preorpending;?></span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-aqua" style="width: 80%"></div>
                    </div>
                  </div>
                  
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Order confirm</span>
                    <span class="progress-number"><?php echo $preorconfirm;?></span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-yellow" style="width: 80%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  
                  
                  <div class="progress-group">
                    <span class="progress-text">Order complete</span>
                    <span class="progress-number"><?php echo $preorcomplete;?></span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-green" style="width: 80%"></div>
                    </div>
                  </div>
                  
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Order cancel</span>
                    <span class="progress-number"><?php echo $preorcancel;?></span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-red" style="width: 80%"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
            
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-md-8">
          <!-- MAP & BOX PANE -->
          
          <!-- /.box -->
          
          <!-- /.row -->

          <!-- TABLE: LATEST ORDERS -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Last 7 Orders</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Order No.</th>
                    <th>Date</th>
                    <th>Status</th>
                    
                  </tr>
                  </thead>
                  <tbody>
                  <?php
				  	$sl=1; 
				  	foreach($allorders as $d)
					{
						$selectedclass="";
						if($d->orderstatus == "Cencel"){$selectedclass='class="label label-danger"';}
						else if($d->orderstatus == "Conform"){$selectedclass='class="label bg-yellow"';}
						else if($d->orderstatus == "Complete"){$selectedclass='class="label label-success"';}
						else{$selectedclass='class="label bg-aqua"';}
				  ?>
                  <tr>
                    <td><a href="<?php echo 'admincontroller/single_order_details/'.$d->orderid?>"><?php if(isset($d->orderid)) echo $d->orderid;?></a></td>
                    <td><?php if(isset($d->dateoforder)) echo $d->dateoforder;?></td>
                    <td><div <?php echo $selectedclass;?>><?php if(isset($d->orderstatus)&&$d->orderstatus !=""){echo $d->orderstatus;}else{echo 'Pending';}?></div></td>
                  </tr>
                  <?php 
					}
				  ?>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="<?php echo 'admincontroller/orders'?>" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-4">
        <div style="background:#FFF;width:100%;padding:2px 10px;border-bottom:2px solid #00C0EF;margin-bottom:2px;">Last 4 Update Product</div>
          <?php 
		  if(isset($updatedproduct)&& count($updatedproduct)>0)
		  {
			  foreach($updatedproduct as $up)
			  {
		  ?>
          
          <div class="info-box">
            <span class="info-box-icon" style="overflow:hidden;background:#FFF !important;"><img src="img/product/<?php echo $up->code.'.png'?>"/></span>

            <div class="info-box-content">
              <span class="info-box-text"><?php echo $up->title;?></span>
              <span class="info-box-number">Code:&nbsp;<?php echo $up->code;?></span>

              <div class="progress">
                <div class="progress-bar" style="width: 40%"></div>
              </div>
              <span class="progress-description">
                    Updator: &nbsp;<?php echo $up->salespersonid;?>&nbsp;&nbsp;Date:&nbsp;<?php echo $up->date;?>
                  </span>
            </div>
            <!-- /.info-box-content -->
            
          </div>
          <?php 
			  }
		  }
		  ?>
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    <div>
</div></section>
</body>