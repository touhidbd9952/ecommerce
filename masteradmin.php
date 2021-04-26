
<?php
	$user ="";
	$user = $this->session->userdata("username");
	$permission = $this->session->userdata("permission");
	if(strpos($permission,','))
	{
		$permission = explode(',',$permission);
	}
	$sap="";
	if($user == 'admin'){$sap="-1";}
	if($user ==""){redirect('main/index');}
    $brand = $this->mm->getSet('Company Name');
	$footertext = $this->mm->getSet('Footer Copyright text');
?>

<!DOCTYPE html>
<html style="height: auto; min-height: 100%;"><head>
<base href="<?php echo base_url();?>">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php if($brand !=""){echo $brand;}else{echo "Ecom";}?> | Admin Panel</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/adminmaster/bootstrap-datepicker.css">
  <link rel="stylesheet" href="css/adminmaster/dataTables.bootstrap.min.css">
  
  <link rel="stylesheet" href="css/adminmaster/table-style.css" />
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/font-awesome.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="css/adminmaster/ionicons.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="css/adminmaster/jquery-jvectormap.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/adminmaster/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="css/adminmaster/_all-skins.css">
  <link rel="stylesheet" href="css/mystyle.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
  

  <!-- Google Font -->
  <link rel="stylesheet" href="css/css.css">
<style type="text/css">
.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;box-sizing: content-box;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}
.content-wrapper {
    min-height: 100%;
    background-color: #e3e5e9;
    z-index: 800;
	padding-bottom:50px;
}
#example_paginate{float:right;}
#example_length{float:left;}
#example_next,#example_previous,.paginate_button{
padding: 3px;
margin: 3px;
background: #FFF;
border-radius: 3px;
cursor:pointer;
}
.menuCategory{width:300px;50px;margin-top:50px;}
.menuCategory ul{list-style-type:none;margin:0px;padding:0px;}
.menuCategory ul li{float:left;display:inline;}
.menuCategory ul li a {line-height: 30px;text-decoration: none;padding: 0px 5px 0px 5px;display: block;color: #FFF;background-color:#3c8dbc;border-radius: 5px;margin-right: 5px;}
.menuCategory ul li a:hover{color:#FFF; background:#3c8dbc;}
table th{text-align:left;}
.fl-r{float:right;}
</style>
<script>
function divhide() 
{
    var abc = document.getElementsByTagName('div');
	if(abc.style.zIndex == '999999')
	{
		alert("Paisi");
	}
	
}
</script>
</head>

<body class="skin-blue sidebar-mini" style="height: auto; min-height: 100%;" cz-shortcut-listen="true" onLoad="divhide();">
<div class="wrapper" style="height: auto; min-height: 100%;">

  <header class="main-header">

    <!-- Logo -->
    <?php
	$logo =""; 
	$logo = $this->mm->getSet("Company Name");
	?>
    <a href="<?php echo 'javascript:'?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">Admin Panel</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?php if($logo !=""){echo $logo;}else{echo "Admin Panel";}?></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a <?php echo 'javascript:'?> class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a <?php echo 'javascript:'?> class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">1</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 1 messages</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a <?php echo 'javascript:'?>>
                      <div class="pull-left">
                        <img src="img/Icon-user.png" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Department Name
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Message</p>
                    </a>
                  </li>
                  <!-- end message -->
                </ul>
              </li>
              <li class="footer"><a <?php echo 'javascript:'?>>See All Messages</a></li>
            </ul>
          </li>
          
          
          <?php 
		  $notification = $this->db->query("SELECT * FROM t_notification")->result();
		  ?>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a <?php echo 'javascript:'?> class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">1</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 1 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                <?php 
				if(count($notification)>0)
				{
					foreach($notification as $notify)
					{
				?>
                  <li>
                    <a <?php echo 'javascript:'?>>
                      <i class="fa fa-shopping-cart text-aqua"></i> <?php echo $notify->notification;?>
                    </a>
                  </li>
              <?php 
					}
				}
			 ?>
                </ul>
              </li>
              <li class="footer"><a <?php echo 'javascript:'?>>View all</a></li>
            </ul>
          </li>
          
          
          
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu">
            <a <?php echo 'javascript:'?> class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">1</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 1 tasks</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a <?php echo 'javascript:'?>>
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a <?php echo 'javascript:'?>>View all tasks</a>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          
          <li class="dropdown user user-menu">
            <a href="<?php echo 'javascript:'?>" class="dropdown-toggle" data-toggle="dropdown">
              <img src="img/Icon-user.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php if($user !=""){echo $user;}else{echo "Admin";}?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="img/Icon-user.png" class="img-circle" alt="User Image">

                <p>
                  <?php if($user !=""){echo $user;}else{echo "Admin";}?>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="<?php echo 'javascript:'?>">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="<?php echo 'javascript:'?>">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="<?php echo 'javascript:'?>">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo 'javascript:'?>" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo 'admincontroller/admin_logout'?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!--<li>
            <a <?php echo 'javascript:'?> data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>-->
        </ul>
      </div>

    </nav>
  </header>
  
  
  
  <!----------------- Left side Menu ------------------------------------------>
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" style="height: auto;">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu tree" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
       
       
       
       
        
        
       
        <li><a href="<?php echo 'admincontroller/dashboard';?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        
        
        <!-------=========== Catalog ================-------->
        
        <?php if($sap=='-1'|| in_array(22,$permission) || in_array(23,$permission) || in_array(24,$permission) || in_array(25,$permission) || in_array(26,$permission) || in_array(27,$permission) || in_array(28,$permission) || in_array(29,$permission) || in_array(30,$permission)|| in_array(31,$permission)|| in_array(32,$permission) || in_array(33,$permission) || in_array(34,$permission) || in_array(35,$permission) || in_array(36,$permission) || in_array(37,$permission) || in_array(38,$permission) || in_array(39,$permission) || in_array(40,$permission) || in_array(41,$permission)  || in_array(42,$permission) || in_array(43,$permission) || in_array(44,$permission) || in_array(45,$permission) || in_array(46,$permission) || in_array(47,$permission) || in_array(48,$permission) || in_array(49,$permission) || in_array(50,$permission) || in_array(51,$permission) || in_array(52,$permission) || in_array(53,$permission) || in_array(54,$permission)){?>
        
        <li class="treeview">
          <a href="javascript:">
            <i class="fa fa-book"></i>
            <span>Catalog</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
          	<?php if($sap=='-1'|| in_array(22,$permission) || in_array(23,$permission)){?>
            <li><a href="<?php echo 'category_management/view_info';?>"><i class="fa fa-circle-o"></i> Categories</a></li><?php }?>
            <?php if($sap=='-1'|| in_array(25,$permission) || in_array(26,$permission)){?>
            <li><a href="<?php echo 'subcategory_management/view_info';?>"><i class="fa fa-circle-o"></i> Sub Categories</a></li><?php }?>
            <?php if($sap=='-1'|| in_array(28,$permission) || in_array(29,$permission)){?>
            <li><a href="<?php echo 'brand_management/view_info';?>"><i class="fa fa-circle-o"></i> Brand & Supplies</a></li><?php }?>
            <?php if($sap=='-1'|| in_array(108,$permission) || in_array(109,$permission)){?>
            <li><a href="<?php echo 'unit_management/view_info';?>"><i class="fa fa-circle-o"></i> Unit/Size</a></li><?php }?>
            <?php if($sap=='-1'|| in_array(31,$permission) || in_array(32,$permission)){?>
            <li><a href="<?php echo 'color_management/view_info';?>"><i class="fa fa-circle-o"></i> Color</a></li><?php }?>
            <?php if($sap=='-1'|| in_array(34,$permission) || in_array(35,$permission)){?>
            <li><a href="<?php echo 'product_management/view_info';?>"><i class="fa fa-circle-o"></i> Products</a></li><?php }?>
            
            
           <?php if($sap=='-1'|| in_array(37,$permission) || in_array(38,$permission)){?> 
            <li class="treeview">
                  <a href="javascript:">
                    <i class="fa fa-circle-o"></i>
                    <span>Ads</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu" style="display: none;">
                    <li><a href="<?php echo 'banner_management/view_info';?>"><i class="fa fa-minus"></i> Banner</a></li>
                  </ul>
            </li>
            <?php }?>
            
            <?php if($sap=='-1'|| in_array(40,$permission) || in_array(41,$permission) || in_array(42,$permission) || in_array(43,$permission)){?> 
            <li class="treeview">
                  <a href="javascript:">
                    <i class="fa fa-circle-o"></i>
                    <span>Delivery</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu" style="display: none;">
                  <?php if($sap=='-1'|| in_array(40,$permission)){?> 
                    <li><a href="<?php echo 'delivery_management/view_info';?>"><i class="fa fa-minus"></i> Delivery Information</a></li><?php }?>
                  <?php if($sap=='-1'|| in_array(41,$permission) || in_array(42,$permission)|| in_array(43,$permission)){?> 
                    <li><a href="<?php echo 'pickuppoint_management/view_info';?>"><i class="fa fa-minus"></i> Pickup Point</a></li><?php }?>
                  </ul>
            </li>
            <?php }?>
            <!------------------>
            <script>
				function confirmationRemove(anchor)
				{
				   var conf = confirm('Are you sure want to remove all offers?');
				   if(conf)
					  window.location=anchor.attr("href");
				}
			</script>
            <?php if($sap=='-1'|| in_array(44,$permission) || in_array(45,$permission) || in_array(46,$permission) || in_array(47,$permission) || in_array(48,$permission) || in_array(49,$permission) || in_array(50,$permission) || in_array(51,$permission) || in_array(52,$permission) || in_array(53,$permission) || in_array(54,$permission)){?> 	
            <li class="treeview">
              <a href="#">
                <i class="fa fa-circle-o"></i> <span>Promotion</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu" style="display:none;">
                <li><a href="<?php echo 'offer_management/view_info'?>"><i class="fa fa-circle-o"></i> Campain Offer</a></li>
                <li class="treeview">
                  <a href="javascript:"><i class="fa fa-circle-o"></i> Add/Remove Campain
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu" style="display:none;">
                  <?php if($sap=='-1'|| in_array(47,$permission)){?> 
                    <li><a href="<?php echo 'offer_management/category_wise_add_offer'?>"><i class="fa fa-minus"></i> Category wise</a></li><?php }?>
                  <?php if($sap=='-1'|| in_array(50,$permission)){?> 
                    <li><a href="<?php echo 'offer_management/individualy_wise_add_offer'?>"><i class="fa fa-minus"></i> Individual</a></li><?php }?>
                  <?php if($sap=='-1'|| in_array(53,$permission)){?> 
                    <li><a href="<?php echo 'offer_management/remove_all_offer/yes'?>" onclick='javascript:confirmationRemove($(this));return false;'><i class="fa fa-minus"></i> Remove all</a></li><?php }?>
                  </ul>
                </li>
              </ul>
            </li>
            <?php }?>
         	
            <?php if($sap=='-1'|| in_array(54,$permission)){?> 
            <li>
                <a <?php if(isset($menu)&& $menu=='bestseller') echo "class='active-menu'";?>  href="<?php echo "admincontroller/bestseller";?>"><i class="fa fa-circle-o"></i> Best Seller</a>
            </li>
            <?php }?>
            
          </ul>
        </li>
        <?php }?>
        
        
        
        
        
        
        
        
        
        
        <!-------===============  Sales ====================------------------->
        
        <?php if($sap=='-1'|| in_array(68,$permission) || in_array(69,$permission)  || in_array(70,$permission) || in_array(71,$permission) || in_array(72,$permission) || in_array(73,$permission) || in_array(74,$permission) || in_array(75,$permission) || in_array(76,$permission)){?>
        
        <li class="treeview">
          <a href="javascript:">
            <i class="fa fa-shopping-cart"></i>
            <span>Orders</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
          <?php if($sap=='-1'|| in_array(68,$permission)){?>
            <li><a href="<?php echo 'admincontroller/orders';?>"><i class="fa fa-circle-o"></i> Orders</a></li><?php }?>
            
				<?php 
                    $totalorder = 0;
                    $totalorder = $this->db->query("select count(*)as total from t_purchase where paymentstatus = 'Pending' and orderstatus !='Conform' and orderstatus !='Cencel' ")->row()->total;
                ?>
            
           <?php if($sap=='-1'|| in_array(68,$permission)){?>
            <li><a href="<?php echo 'admincontroller/order_request';?>"><i class="fa fa-circle-o"></i> Pending&nbsp;<?php echo '('.$totalorder.')';?></a></li><?php }?>
            
                <?php 
                    $totalconfirmorder = 0;
                    $totalconfirmorder = $this->db->query("select count(*)as total from t_purchase where paymentstatus = 'Pending' and orderstatus ='Conform' ")->row()->total;
					
					$totalcencel = 0;
                    $totalcencelorder = $this->db->query("select count(*)as total from t_purchase where paymentstatus = 'Pending' and orderstatus ='Cencel' ")->row()->total;
                ?>
            
            <?php if($sap=='-1'|| in_array(71,$permission)){?>    
            <li><a href="<?php echo 'admincontroller/order_confirm_show';?>"><i class="fa fa-circle-o"></i> Approved&nbsp;<?php echo '('.$totalconfirmorder.')';?></a></li><?php }?>
            <?php if($sap=='-1'|| in_array(73,$permission)){?>
            <li><a href="<?php echo 'admincontroller/order_cancelled';?>"><i class="fa fa-circle-o"></i> Cancelled&nbsp;<?php echo '('.$totalcencelorder.')';?></a></li><?php }?>
            <?php if($sap=='-1'|| in_array(74,$permission)){?>
            <li><a href="<?php echo 'admincontroller/order_shipped';?>"><i class="fa fa-circle-o"></i> Shipped</a></li><?php }?>
            <?php if($sap=='-1'|| in_array(75,$permission)){?>
            <li><a href="<?php echo 'admincontroller/exchange_order';?>"><i class="fa fa-circle-o"></i> Return/Exchange</a></li><?php }?>
            <?php if($sap=='-1'|| in_array(76,$permission)){?>
            <li><a href="<?php echo 'admincontroller/order_complete_form';?>"><i class="fa fa-circle-o"></i> Completed</a></li><?php }?>
          </ul>
        </li>
        <?php }?>
        
        
        <!-----=============== Reports ==================----->
        
        <?php if($sap=='-1'|| in_array(87,$permission) || in_array(88,$permission) || in_array(89,$permission)|| in_array(90,$permission)|| in_array(91,$permission)|| in_array(92,$permission)|| in_array(93,$permission)|| in_array(94,$permission)|| in_array(95,$permission)|| in_array(96,$permission)|| in_array(97,$permission)|| in_array(98,$permission)|| in_array(99,$permission)|| in_array(100,$permission)|| in_array(101,$permission)){?>
        <li class="treeview">
          <a href="javascript:">
            <i class="fa fa-pie-chart"></i>
            <span>Reports</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
          <?php if($sap=='-1'|| in_array(87,$permission)){?>
            <li><a href="<?php echo 'admincontroller/stock_report';?>"><i class="fa fa-circle-o"></i> Stock</a></li><?php }?>
          <?php if($sap=='-1'|| in_array(88,$permission)){?>
            <li><a href="<?php echo 'admincontroller/best_categories_report';?>"><i class="fa fa-circle-o"></i> Best Categories</a></li><?php }?>
          <?php if($sap=='-1'|| in_array(89,$permission)){?>
            <li><a href="<?php echo 'admincontroller/best_brand_report';?>"><i class="fa fa-circle-o"></i> Best Brand / Supplies</a></li><?php }?>
          <?php if($sap=='-1'|| in_array(90,$permission)){?>
            <li><a href="<?php echo 'admincontroller/best_customer_report';?>"><i class="fa fa-circle-o"></i> Best Customer</a></li><?php }?>
          <?php if($sap=='-1'|| in_array(91,$permission)){?>
            <li><a href="<?php echo 'admincontroller/best_seller_report';?>"><i class="fa fa-circle-o"></i> Best Seller</a></li><?php }?>
          <?php if($sap=='-1'|| in_array(92,$permission)){?> 
            <li><a href="<?php echo 'admincontroller/complete_orders_report';?>"><i class="fa fa-circle-o"></i> Complete Order</a></li><?php }?>
          <?php if($sap=='-1'|| in_array(93,$permission)){?>
            <li><a href="<?php echo 'admincontroller/pending_orders_report';?>"><i class="fa fa-circle-o"></i> pending Order</a></li><?php }?>
          <?php if($sap=='-1'|| in_array(94,$permission)){?>
            <li><a href="<?php echo 'admincontroller/cancel_orders_report';?>"><i class="fa fa-circle-o"></i> Cancel Order</a></li><?php }?>
          <?php if($sap=='-1'|| in_array(95,$permission)){?>  
            <li><a href="<?php echo 'admincontroller/orders_locations_report';?>"><i class="fa fa-circle-o"></i> Most Order Location</a></li><?php }?>
            
          <?php if($sap=='-1'|| in_array(96,$permission)){?>   
            <li><a href="<?php echo 'admincontroller/most_review_product_report';?>"><i class="fa fa-circle-o"></i> Most Viewed</a></li><?php }?>
          <?php if($sap=='-1'|| in_array(97,$permission)){?>
            <li><a href="<?php echo 'admincontroller/top_search_word_report';?>"><i class="fa fa-circle-o"></i> Top Search</a></li><?php }?>
          <?php if($sap=='-1'|| in_array(98,$permission)){?>
            <li><a href="<?php echo 'admincontroller/bad_review_product_report';?>"><i class="fa fa-circle-o"></i> Bad Review Products</a></li><?php }?>
          <?php if($sap=='-1'|| in_array(99,$permission)){?>
            <li><a href="<?php echo 'admincontroller/most_cancel_product_report';?>"><i class="fa fa-circle-o"></i> Most Cancel Products</a></li><?php }?>
           <?php if($sap=='-1'|| in_array(100,$permission)){?>
            <li><a href="<?php echo 'admincontroller/visitors_online_report';?>"><i class="fa fa-circle-o"></i> Visitors Online</a></li><?php }?>
           <?php if($sap=='-1'|| in_array(101,$permission)){?>
            <li><a href="<?php echo 'admincontroller/access_logs';?>"><i class="fa fa-circle-o"></i> Access Logs</a></li><?php }?>
            
          </ul>
        </li>
        <?php }?>
        
        <!-------====================  Store Setup  ======================------------------->
        
        
        <?php 
		    if($sap=='-1'  || in_array(77,$permission) || in_array(78,$permission)  || in_array(79,$permission)|| in_array(80,$permission)|| in_array(81,$permission)|| in_array(82,$permission)|| in_array(83,$permission) || in_array(84,$permission)|| in_array(85,$permission)|| in_array(111,$permission)){
		?>
        
        <li class="treeview">
          <a href="javascript:">
            <i class="fa fa-files-o"></i>
            <span>Configuration</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
          <?php if($sap=='-1'|| in_array(77,$permission)){?>
            <li><a href="<?php echo 'admincontroller/branding';?>"><i class="fa fa-circle-o"></i> Branding</a></li><?php }?>
           <?php if($sap=='-1'|| in_array(78,$permission)){?>
            <li><a href="<?php echo 'admincontroller/about_company';?>"><i class="fa fa-circle-o"></i> Company Profile</a></li><?php }?>
           <?php if($sap=='-1'|| in_array(79,$permission)){?>
            <li><a href="<?php echo 'admincontroller/sociallink';?>"><i class="fa fa-circle-o"></i> Social Link</a></li><?php }?>
           <?php if($sap=='-1'|| in_array(80,$permission)){?>
            <li><a href="<?php echo 'admincontroller/company_policy';?>"><i class="fa fa-circle-o"></i>Privacy & Policy</a></li><?php }?>
          <?php if($sap=='-1'|| in_array(84,$permission)){?> 
            <li><a href="<?php echo 'admincontroller/return_policy';?>"><i class="fa fa-circle-o"></i>Return Policy</a></li><?php }?>
          <?php if($sap=='-1'|| in_array(81,$permission)){?>  
            <li><a href="<?php echo 'admincontroller/company_termsandcondition';?>"><i class="fa fa-circle-o"></i>Terms & Conditions</a></li><?php }?>
          <?php if($sap=='-1'|| in_array(82,$permission)){?>   
            <li><a href="<?php echo 'admincontroller/mail_setting';?>"><i class="fa fa-circle-o"></i> Mail SMTP</a></li><?php }?>
          <?php if($sap=='-1'|| in_array(83,$permission)){?>  
            <li><a href="<?php echo 'admincontroller/newsletter';?>"><i class="fa fa-circle-o"></i> Newsletter</a></li><?php }?>
          
          <?php if($sap=='-1'|| in_array(111,$permission)){?>  
            <li><a href="<?php echo 'admincontroller/chatlink';?>"><i class="fa fa-circle-o"></i> Chat Link</a></li><?php }?>
          
            <!--<li><a href="<?php echo 'salesperson_management/index';?>"><i class="fa fa-circle-o"></i> Sales Person</a> </li>-->
           <?php if($sap=='-1'|| in_array(112,$permission)){?> 
           <li><a href="<?php echo 'admincontroller/coupon';?>"><i class="fa fa-circle-o"></i> Coupon</a> </li><?php }?>
             
          <?php if($sap=='-1'|| in_array(85,$permission)){?>
            <li><a href="<?php echo 'admincontroller/help_center';?>"><i class="fa fa-circle-o"></i> Help Center</a> </li><?php }?>
            
           <?php if($sap=='-1'|| in_array(113,$permission)){?>
            <li><a href="<?php echo 'admincontroller/theme';?>"><i class="fa fa-circle-o"></i> Theme</a> </li><?php }?>
             
          </ul>
        </li>
        <?php }?>
        <!-------------------------->
        
        
        
        
        <!---============ ACL ====================---->
            
        <?php if($sap=='-1'|| in_array(20,$permission) || in_array(21,$permission)){?>
       <li class="treeview">
          <a href="javascript:">
            <i class="fa fa-files-o"></i>
            <span>Acl</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
          <?php if($sap=='-1'|| in_array(20,$permission)){?>
            <li><a href="<?php echo 'acl_management/rolecreate_form';?>"><i class="fa fa-circle-o"></i>Create Role</a></li>
           <?php }?>
           <?php if($sap=='-1'|| in_array(21,$permission)){?>
            <li><a href="<?php echo 'acl_management/usercreate_form';?>"><i class="fa fa-circle-o"></i>Create User</a></li>
            <?php }?>
          </ul>
        </li>
        <?php }?>
          
          
          <!-------===================  Customers =====================------------------->
          
        <?php if($sap=='-1'|| in_array(55,$permission) || in_array(56,$permission) || in_array(57,$permission) || in_array(58,$permission) || in_array(59,$permission) || in_array(60,$permission) || in_array(61,$permission) || in_array(62,$permission) || in_array(63,$permission) || in_array(64,$permission) || in_array(65,$permission) || in_array(66,$permission) || in_array(67,$permission)){?>
        
        <li class="treeview">
          <a href="javascript:">
            <i class="fa fa-user"></i>
            <span>Customers</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
          	<?php if($sap=='-1'|| in_array(55,$permission) || in_array(56,$permission)){?>
            <li class="treeview">
                  <a href="javascript:">
                    <i class="fa fa-circle-o"></i>
                    <span>Registration</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu" style="display: none;">
                  <?php if($sap=='-1'|| in_array(55,$permission)){?>
                    <li><a href="<?php echo 'admincontroller/customer_add';?>"><i class="fa fa-minus"></i> Add Customer</a></li><?php }?>
                    <li><a href="<?php echo 'admincontroller/view_customers';?>"><i class="fa fa-minus"></i> View Customer</a></li>
                  </ul>
            </li>
            <?php }?>
            
            <?php if($sap=='-1'|| in_array(58,$permission) || in_array(59,$permission) || in_array(60,$permission)){?>
            <li class="treeview">
                  <a href="javascript:">
                    <i class="fa fa-circle-o"></i>
                    <span>Customer Group</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu" style="display: none;">
                 <?php if($sap=='-1'|| in_array(58,$permission)){?>
                    <li><a href="<?php echo 'admincontroller/view_active_customers';?>"><i class="fa fa-minus"></i> Active</a></li><?php }?>
                 <?php if($sap=='-1'|| in_array(59,$permission)){?> 																												
                    <li><a href="<?php echo 'admincontroller/view_inactive_customers';?>"><i class="fa fa-minus"></i> Inactive</a></li><?php }?>
                 <?php if($sap=='-1'|| in_array(60,$permission)){?>
                    <li><a href="<?php echo 'admincontroller/view_bad_customers';?>"><i class="fa fa-minus"></i> Bad Clients</a></li><?php }?>
                  </ul>
            </li>
            <?php }?>
            
            
            <li><a href="<?php echo 'admincontroller/message_template';?>"><i class="fa fa-circle-o"></i> Message Template</a></li>
            
            
            <?php if($sap=='-1'|| in_array(62,$permission) || in_array(63,$permission) || in_array(64,$permission) || in_array(65,$permission)){?>
            <li class="treeview">
                  <a href="javascript:">
                    <i class="fa fa-circle-o"></i>
                    <span>Communication</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu" style="display: none;">
                  <?php if($sap=='-1'|| in_array(62,$permission)){?>
                    <li><a href="<?php echo 'admincontroller/view_customer_email_list';?>"><i class="fa fa-minus"></i> Email List</a></li><?php }?>
                  <?php if($sap=='-1'|| in_array(63,$permission)){?>  
                    <li><a href="<?php echo 'admincontroller/email_to_customer_page';?>"><i class="fa fa-minus"></i> Email To Customer</a></li><?php }?>
                  <?php if($sap=='-1'|| in_array(64,$permission)){?>
                    <li><a href="<?php echo 'admincontroller/sms_to_customer_page';?>"><i class="fa fa-minus"></i> SMS Settings</a></li><?php }?>
                  </ul>
            </li>
            <?php }?>
            
            <?php if($sap=='-1'|| in_array(65,$permission)){?>
            <li><a href="<?php echo 'admincontroller/view_product_review';?>"><i class="fa fa-circle-o"></i> Review</a></li><?php }?>
            
          </ul>
        </li>
        <?php }?>
        <!----------- End Menu--------------->
          
          
          
            
        </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

	<style>.bg-green{display:none !important;}</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height: 902.8px;">
  <?php if(isset($container)) echo $container; ?>
      <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <a href="<?php echo 'javascript:';?>"><?php echo $footertext?></a>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-theme-demo-options-tab" data-toggle="tab"><i class="fa fa-wrench"></i></a></li><li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div><div id="control-sidebar-theme-demo-options-tab" class="tab-pane active"><div><h4 class="control-sidebar-heading">Layout Options</h4><div class="form-group"><label class="control-sidebar-subheading"><input data-layout="fixed" class="pull-right" type="checkbox"> Fixed layout</label><p>Activate the fixed layout. You can't use fixed and boxed layouts together</p></div><div class="form-group"><label class="control-sidebar-subheading"><input data-layout="layout-boxed" class="pull-right" type="checkbox"> Boxed Layout</label><p>Activate the boxed layout</p></div><div class="form-group"><label class="control-sidebar-subheading"><input data-layout="sidebar-collapse" class="pull-right" type="checkbox"> Toggle Sidebar</label><p>Toggle the left sidebar's state (open or collapse)</p></div><div class="form-group"><label class="control-sidebar-subheading"><input data-enable="expandOnHover" class="pull-right" type="checkbox"> Sidebar Expand on Hover</label><p>Let the sidebar mini expand on hover</p></div><div class="form-group"><label class="control-sidebar-subheading"><input data-controlsidebar="control-sidebar-open" class="pull-right" type="checkbox"> Toggle Right Sidebar Slide</label><p>Toggle between slide over content and push content effects</p></div><div class="form-group"><label class="control-sidebar-subheading"><input data-sidebarskin="toggle" class="pull-right" type="checkbox"> Toggle Right Sidebar Skin</label><p>Toggle between dark and light skins for the right sidebar</p></div><h4 class="control-sidebar-heading">Skins</h4><ul class="list-unstyled clearfix"><li style="float:left; width: 33.33333%; padding: 5px;"><a href="javascript:void(0)" data-skin="skin-blue" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9"></span><span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div></a><p class="text-center no-margin">Blue</p></li><li style="float:left; width: 33.33333%; padding: 5px;"><a href="javascript:void(0)" data-skin="skin-black" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover"><div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix"><span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe"></span><span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #222"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div></a><p class="text-center no-margin">Black</p></li><li style="float:left; width: 33.33333%; padding: 5px;"><a href="javascript:void(0)" data-skin="skin-purple" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span><span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div></a><p class="text-center no-margin">Purple</p></li><li style="float:left; width: 33.33333%; padding: 5px;"><a href="javascript:void(0)" data-skin="skin-green" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span><span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div></a><p class="text-center no-margin">Green</p></li><li style="float:left; width: 33.33333%; padding: 5px;"><a href="javascript:void(0)" data-skin="skin-red" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span><span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div></a><p class="text-center no-margin">Red</p></li><li style="float:left; width: 33.33333%; padding: 5px;"><a href="javascript:void(0)" data-skin="skin-yellow" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span><span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div></a><p class="text-center no-margin">Yellow</p></li><li style="float:left; width: 33.33333%; padding: 5px;"><a href="javascript:void(0)" data-skin="skin-blue-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9"></span><span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Blue Light</p></li><li style="float:left; width: 33.33333%; padding: 5px;"><a href="javascript:void(0)" data-skin="skin-black-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover"><div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix"><span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe"></span><span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Black Light</p></li><li style="float:left; width: 33.33333%; padding: 5px;"><a href="javascript:void(0)" data-skin="skin-purple-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span><span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Purple Light</p></li><li style="float:left; width: 33.33333%; padding: 5px;"><a href="javascript:void(0)" data-skin="skin-green-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span><span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Green Light</p></li><li style="float:left; width: 33.33333%; padding: 5px;"><a href="javascript:void(0)" data-skin="skin-red-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span><span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Red Light</p></li><li style="float:left; width: 33.33333%; padding: 5px;"><a href="javascript:void(0)" data-skin="skin-yellow-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span><span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Yellow Light</p></li></ul></div></div>
      <!-- /.tab-pane -->

      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input class="pull-right" checked="checked" type="checkbox">
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input class="pull-right" checked="checked" type="checkbox">
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input class="pull-right" checked="checked" type="checkbox">
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input class="pull-right" checked="checked" type="checkbox">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input class="pull-right" type="checkbox">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="js/adminmaster/jquery.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="js/adminmaster/bootstrap.js"></script>
<script src="js/adminmaster/bootstrap-datepicker.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<!-- FastClick -->
<script src="js/adminmaster/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="js/adminmaster/adminlte.js"></script>
<!-- Sparkline -->
<script src="js/adminmaster/jquery_002.js"></script>
<!-- jvectormap  -->
<script src="js/adminmaster/jquery-jvectormap-1.js"></script>
<script src="js/adminmaster/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll -->
<script src="js/adminmaster/jquery_003.js"></script>
<!-- ChartJS -->
<script src="js/adminmaster/Chart.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="js/adminmaster/dashboard2.js"></script>
<!-- AdminLTE for demo purposes -->
<!--<script src="js/adminmaster/demo.js"></script>-->

 

<div class="jvectormap-label" style="left: 529px; top: 843px; display: none;">Nigeria</div></body></html>
