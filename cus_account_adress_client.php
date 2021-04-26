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
</style>

<div class="w3l_banner_nav_right" style="margin-bottom:30px; 0">
  <div class="w3ls_w3l_banner_nav_right_grid">
    <h4 style="margin:15px 15px;text-align:left;font-size: 2em;">Address Book</h4>
    <hr>
    <div class="col-md-6 col-sm-6 col-xs-12" style="margin:0 -15px;">
      <div class="style1">
        <div class="divstyle1">
          <h4 class="pu">Your address</h4>
          <p style="font-size:12px;font-weight:normal;">
            <?php $cusname = $this->session->userdata('cusname'); if($cusname !=""){echo $cusname;}else{echo "Customer";} ?>
            <br>
            <?php 
						$cusemail = $this->session->userdata('cusemail'); 
						if(count($customerdata) >0)
						{
							$customeraddress =	$customerdata[0]->address;
							$customerphone =	$customerdata[0]->phone;
						}
					?>
          </p>
          <p class="i-address addressstyle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php if($customeraddress !="")echo $customeraddress;?>
          </p>
          <p class="i-phone phonestyle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php if($customerphone !="")echo $customerphone;?>
          </p>
          <a href="<?php echo 'customercontroller/customer_profile'?>" class="editstyle i-edit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit</a> </div>
      </div>
    </div>
  </div>
</div>
