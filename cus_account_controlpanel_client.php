<style>
.style1{border:1px solid #D3D3D3;min-height:150px;margin:5px;font-family:"Roboto Condensed",sans-serif;}
.divstyle1{padding:5px;font-family:Roboto,Helvetica,Arial,sans-serif;font-weight:bold;}
.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {font-family: inherit;font-weight: 500;line-height: 1.1;color: inherit;}
.pu{padding-bottom:5px !important;border-bottom:1px solid #4b4b4b !important;margin-top:-1px;font-size:14px;}
.chemail{color:#000;}
.chemail:hover{color:#73B6E1;text-decoration:none;font-size:12px;}
.i-edit {background: url(img/edit.png) no-repeat;}
.i-address {background: url(img/address.png) no-repeat;}
.i-phone {background: url(img/phone.png) no-repeat;}
.editstyle, .readmore{position:absolute;bottom:10px;right:40px;text-decoration:none;color:#000;font-size:12px;}
.editstyle:hover{color:#73B6E1;text-decoration:none;font-size:12px;}
.addressstyle{font-family:"Roboto Condensed",sans-serif;font-size:12px;font-weight:normal;margin:0px;}
.phonestyle{font-family:"Roboto Condensed",sans-serif;font-size:12px;font-weight:normal;margin-top:10px;}
.adm{float:right;margin-top:5px;color:#000;}
.adm:hover{color:#73B6E1;text-decoration:none;}
.newsdata{max-height:80px;overflow:hidden;font-size:10px;}
.newsdata h1,.newsdata h2,.newsdata h3,.newsdata h4,.newsdata h5,.newsdata h6{font-size:12px;margin:0px;}
.newsdata p,.newsdata div,.newsdata span{font-size:12px;margin:0px;}
#tds{margin-top:0px !important}
</style>

<h3>Account Control Panel</h3>
<hr>
<span style="font-weight:bold; margin-bottom:10px;">Hello <?php $cusname = $this->session->userdata('cusname'); if($cusname !=""){echo $cusname;}else{echo "Customer";} ?></span>
<br>
Form your My Account Dashboard you have the ability to view a snapshot of your recent activity and update your account information. Select a link below to view or edit information.
<br><br>

	<div class="col-md-6 col-sm-6 col-xs-12" style="margin:0 -15px;">
    	<div class="style1">
        	<div class="divstyle1">
            	<h4 class="pu">Contact Information</h4>
                <p style="font-size:12px;font-weight:normal;">
                	<?php $cusname = $this->session->userdata('cusname'); if($cusname !=""){echo $cusname;}else{echo "Customer";} ?><br>
                    <?php $cusemail = $this->session->userdata('cusemail'); if($cusemail !=""){echo $cusemail;} ?> - <a href="<?php echo 'customercontroller/customer_profile_edit'?>" class="chemail">Change E-mail</a><br>
                </p>
                <p style="font-size:12px;font-weight:normal;">
                    <a href="<?php echo 'customercontroller/customer_profile'?>" class="chemail">Change password</a><br>
                </p>
                <a href="<?php echo 'customercontroller/customer_profile'?>" class="editstyle i-edit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit</a>
            </div>
        </div>
    </div>
    
    
    
    <div class="col-md-6 col-sm-6 col-xs-12" style="margin:0 -15px;">
    	<div class="style1">
        	<div class="divstyle1">
            	<h4 class="pu">Newsletter</h4>
                <?php 
				if(isset($customerdata))
				{
					if(count($customerdata) >0)
					{
						if(isset($customerdata[0]->viewnewsletter)&&$customerdata[0]->viewnewsletter != "")
						{
						$viewnewsletter = $customerdata[0]->viewnewsletter;
						if($viewnewsletter == 1)
						{
				?>	
                <span style="font-size:12px;font-weight:normal;"><?php echo 'You are currently not subscribed to any of our newsletters.';?></span>
                <a href="<?php echo 'javascript:'?>" class="editstyle i-edit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit</a>
                <?php
						}
						else
						{
				?>
                <span style="font-size:12px;font-weight:normal;">
				<?php 
					$newsdata = $this->db->query("select value from t_settings where name ='newsletter' ")->row()->value;
					echo '<div class="newsdata">'.$newsdata.'</div>';
				?>
                </span>
                <a href="<?php echo 'customercontroller/current_news'?>" class="readmore">Read More</a>				
				<?php			
						}
						}
					}
				}
				?>
            </div>
        </div>
    </div>
    
    
    
    <div class="col-md-6 col-sm-6 col-xs-12" style="margin:0 -15px;">
    	<div style="height:30px;margin:40px 5px 10px 5px;border-bottom:1px solid #000;">
    	<h3 style="float:left;margin:0;padding:0px;">Address book</h3>
        <a href="<?php echo 'customercontroller/customer_profile'?>" class="adm">Address management</a>
        </div>
    	<div class="style1">
        	<div class="divstyle1">
            	<h4 class="pu">Default delivery address</h4>
                <p style="font-size:12px;font-weight:normal;">
                	<?php $cusname = $this->session->userdata('cusname'); if($cusname !=""){echo $cusname;}else{echo "Customer";} ?><br>
                    
                    <?php 
						$cusemail = $this->session->userdata('cusemail'); 
						if(count($customerdata) >0)
						{
							$customeraddress =	$customerdata[0]->address;
							$customerphone =	$customerdata[0]->phone;
						}
					?> 
                </p>
                <p class="i-address addressstyle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($customeraddress !="")echo $customeraddress;?></p>
                <p class="i-phone phonestyle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($customerphone !="")echo $customerphone;?></p>
                <a href="<?php echo 'customercontroller/customer_profile'?>" class="editstyle i-edit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edit</a>
            </div>
        </div>
    </div>

