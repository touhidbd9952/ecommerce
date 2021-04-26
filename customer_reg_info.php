<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="js/myjs.js"></script>
<style>
.hand_up{background:url(img/hand_up.png) no-repeat;width:25px;height:25px;}
.btn{padding:0 !important}
.profile {
	width: 550px;
}
.modalsize1 {
	padding-right: 17px !important;
	width: 600px;
	height: 327px;
	overflow: hidden !important;
	margin: 0 auto;
}
.modalsize2 {
	padding-right: 17px !important;
	width: 600px;
	height: 255px;
	overflow: hidden !important;
	margin: 0 auto;
}
.modalsize3 {
	padding-right: 17px !important;
	width: 600px;
	height: 400px;
	overflow: hidden !important;
	margin: 0 auto;
}
.modal-dialog {
	width: 600px;
	margin: 30px auto;
}
.inputsize {
	width: 100%;
}

@media(max-width:767px) {
	.inputsize {
		width: 240px;
	}
	.modal-footer {
		padding: 15px;
		text-align: right;
		border-top: 1px solid #e5e5e5;
		width: 270px;
	}	
	 .profile {
	width: 320px;
	}
 
	 .modalsize1 {
	display: block;
	padding-right: 17px !important;
	width: 320px;
	height:327px;
	overflow: hidden !important;
	margin: 0 auto;
	}
	 .modalsize2 {
	display: block;
	padding-right: 17px !important;
	width: 320px;
	height:255px;
	overflow: hidden !important;
	margin: 0 auto;
	}
	 .modal-dialog {
	width: 320px;
	margin: 30px auto;
	}
}


.profile tr th, .profile tr td {
	width: 90px;
	padding: 24px;
	background: #f9f8f8;
	border-top: 1px solid rgba(0,0,0,0.12);
}
.mymsg {
	color: #0F0;
}
.modal-header {
	padding: 15px;
	border-bottom: 1px solid #e5e5e5;
	background: #e9e9e9;
}
input, select {
	height: 35px;
	font-size: 18px;
}
textarea {
	font-size: 18px;
}
.mybtn {
	padding: 6px 22px;
	border-radius: 3px;
	border: none;
	text-transform: uppercase;
	outline: none;
	font-family: 'Lato-Regular';
	text-transform: none;
}
.modal-footer a {
	text-decoration: none;
	padding-top:12px;
}
.ems {
	color: #F00;
}
</style>
<script>

$(document).ready(function() {
    $("#myName").click(function(){
		$("#myName").css("visibility","visible");
		$("#myEmail,#myPhone,#myPass,#myAddress,#myCity,#myCountry").css("visibility","hidden");
	});
	$("#myEmail").click(function(){
		$("#myEmail").css("visibility","visible");
		$("#myName,#myPhone,#myPass,#myAddress,#myCity,#myCountry").css("visibility","hidden");
	});
	$("#myPass").click(function(){
		$("#myPass").css("visibility","visible");
		$("#myName,#myPhone,#myEmail,#myAddress,#myCity,#myCountry").css("visibility","hidden");
	});
	$("#myPhone").click(function(){
		$("#myPhone").css("visibility","visible");
		$("#myName,#myEmail,#myPass,#myAddress,#myCity,#myCountry").css("visibility","hidden");
	});
	$("#myAddress").click(function(){
		$("#myAddress").css("visibility","visible");
		$("#myName,#myEmail,#myPhone,#myPass,#myCity,#myCountry").css("visibility","hidden");
	});
	$("#myCity").click(function(){
		$("#myCity").css("visibility","visible");
		$("#myName,#myEmail,#myPhone,#myPass,#myAddress,#myCountry").css("visibility","hidden");
	});
	$("#myCountry").click(function(){
		$("#myCountry").css("visibility","visible");
		$("#myName,#myEmail,#myPhone,#myPass,#myAddress,#myCity").css("visibility","hidden");
	});
});
function closemodal()
{
	location.reload();
}

function valid1()
{
	var firstname = document.getElementById('firstname').value;
	var lastname = document.getElementById('lastname').value;
	document.getElementById('efirstname').innerHTML="";
	document.getElementById('elastname').innerHTML="";
	var err=0;
	var emsg="";
	if(firstname ==""){err++; document.getElementById('efirstname').innerHTML="Required";}
	else if(!isNaN(firstname)){err++; document.getElementById('efirstname').innerHTML="Invalid";}
	if(lastname !="")
	{ 
		if(!isNaN(lastname)){err++; document.getElementById('elastname').innerHTML="Invalid";}
	}
	if(err==0)
	{
		return true;
	}
	return false;
}
function valid2()
{
	var email = document.getElementById('email').value;
	document.getElementById('eemail').innerHTML="";
	var err=0;
	var emsg="";
	if(email ==""){err++; document.getElementById('eemail').innerHTML="Required";}
	else if(email.length >150){err++; document.getElementById('eemail').innerHTML="Email maximum length exceed";}
	else if(email !="")
	{
		var atpos = email.indexOf("@");
		var dotpos = email.lastIndexOf(".");
		if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length) {
			err++; document.getElementById('eemail').innerHTML="Email invalid";
		}
	}
	if(err==0)
	{
		return true;
	}
	return false;
}
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function valid3()
{
	var phone = document.getElementById('phone').value;
	document.getElementById('ephone').innerHTML="";
	var err=0;
	var emsg="";
	if(phone ==""){err++; document.getElementById('ephone').innerHTML="Required";}
	else if(phone.length >20){err++; document.getElementById('ephone').innerHTML="Phone length exceed";}
	
	if(err==0)
	{
		return true;
	}
	return false;
}
function valid4()
{
	var address = document.getElementById('address').value;
	document.getElementById('eaddress').innerHTML="";
	var err=0;
	var emsg="";
	if(address ==""){err++; document.getElementById('eaddress').innerHTML="Required";}
	else if(address.length >200){err++; document.getElementById('eaddress').innerHTML="Address length exceed";}
	
	if(err==0)
	{
		return true;
	}
	return false;
}
function valid5()
{
	var city = document.getElementById('city').value;
	document.getElementById('ecity').innerHTML="";
	var err=0;
	var emsg="";
	if(city ==""){err++; document.getElementById('ecity').innerHTML="Required";}
	else if(city.length >100){err++; document.getElementById('ecity').innerHTML="City length exceed";}
	
	if(err==0)
	{
		return true;
	}
	return false;
}
function valid7()
{
	var userecurpass = document.getElementById('userecurpass').value;
	var cpass = document.getElementById('cpass').value;
	var npass = document.getElementById('npass').value;
	var confirmpass = document.getElementById('confirmpass').value;
	document.getElementById('ecpass').innerHTML="";
	document.getElementById('enpass').innerHTML="";
	document.getElementById('econfirmpass').innerHTML="";
	var err=0;
	var emsg="";
	if(cpass == ""){err++; document.getElementById('ecpass').innerHTML="Required";}
	else if(cpass != userecurpass){err++; document.getElementById('ecpass').innerHTML="Wrong pass";}
	if(npass == ""){err++; document.getElementById('enpass').innerHTML="Required";}
	else if(confirmpass == ""){err++; document.getElementById('econfirmpass').innerHTML="Required";}
	else if(npass != confirmpass){err++; document.getElementById('enpass').innerHTML="Not match";}
	
	if(err==0)
	{
		return true;
	}
	return false;
}
</script>

<div class="w3l_banner_nav_right" style="margin-bottom:30px; 0">
  <div class="w3ls_w3l_banner_nav_right_grid">
    <h4 style="margin:15px 15px;text-align:left;font-size: 2em;">Personal Information</h4>
    
    <?php
	if($customerdata)
	{
		foreach($customerdata as $d)
		{
			
		}
		$customersettingdata = $d->data;
		$customersettingdata = json_decode($customersettingdata);
		$email_verify = $customersettingdata->email_verify;
		$phone_verify = $customersettingdata->phone_verify;
	}
?>
    <table class="profile">
      <tr>
        <th>Name</th>
        <td><?php if($d->firstname !=""){echo ': '.$d->firstname;}?>
          <?php if($d->lastname !=""){echo ' '.$d->lastname;}?></td>
        <td><a href="javascript:"><span data-toggle="modal" data-target="#myName" class="glyphicon glyphicon-pencil" style="float:right;"></span></a></td>
      </tr>
      <tr>
        <th>Email</th>
        <td><?php echo ': '.$d->email?>
        <span onclick="javascript:PopUp('<?php echo "customercontroller/emailverifypage/".$d->id?>', 400, 350); return false;" class="hand_up btn  btn-xs" aria-hidden="true" title="Valid Email"></span>
        <?php if($email_verify =="yes"){?> <span style="color:#84C639;margin-left:10px;">verified</span><?php }?>
        </td>
        <td><a href="javascript:"><span data-toggle="modal" data-target="#myEmail" class="glyphicon glyphicon-pencil" style="float:right;"></span></a></td>
      </tr>
      <tr>
        <th>Phone</th>
        <td>
		<?php echo ': '.$d->phone?>
        <span onclick="javascript:PopUp('<?php echo "customercontroller/phoneverifypage/".$d->id?>', 400, 350); return false;" class="hand_up btn  btn-xs" aria-hidden="true" title="Valid Email"></span>
        <?php if($phone_verify =="yes"){?> <span style="color:#84C639;margin-left:10px;">verified</span><?php }?>
        </td>
        <td><a href="javascript:"><span data-toggle="modal" data-target="#myPhone" class="glyphicon glyphicon-pencil" style="float:right;"></span></a></td>
      </tr>
      <tr>
        <th>Password</th>
        <td><?php echo ': ******'?></td>
        <td><a href="javascript:"><span data-toggle="modal" data-target="#myPass" class="glyphicon glyphicon-pencil" style="float:right;"></span></a></td>
      </tr>
      <tr>
        <th>Address</th>
        <td><?php echo ': '.$d->address?></td>
        <td><a href="javascript:"><span data-toggle="modal" data-target="#myAddress" class="glyphicon glyphicon-pencil" style="float:right;"></span></a></td>
      </tr>
      <tr>
        <th>City</th>
        <td><?php echo ': '.$d->city?></td>
        <td><a href="javascript:"><span data-toggle="modal" data-target="#myCity" class="glyphicon glyphicon-pencil" style="float:right;"></span></a></td>
      </tr>
      <tr>
        <th>Country</th>
        <td><?php echo ': '.$d->country?></td>
        <td><a href="javascript:"><span data-toggle="modal" data-target="#myCountry" class="glyphicon glyphicon-pencil" style="float:right;"></span></a></td>
      </tr>
    </table>
    <div style="margin-top:30px;margin-left:200px;">
      <?php if(isset($_GET['sk'])&& !empty($_GET['sk'])){echo '<br><br><label class="ms mymsg fl-r">'.$_GET['sk'].'</label>';}
     else if(isset($_GET['esk'])&& !empty($_GET['esk'])){echo '<br><br><label class="ems mymsg fl-r">'.$_GET['esk'].'</label>';}?>
    </div>
    
    <!--======= Modal for name change ========-->
    <div class="modal fade modalsize1" id="myName" role="dialog">
      <div class="modal-dialog"> 
        
        <!-- Modal content-->
        <form action="<?php echo 'customercontroller/customer_name_update';?>" method="post" onsubmit="return valid1()">
          <input type="hidden" name="id" value="<?php echo $d->id;?>" />
          <div class="modal-content">
            <div class="modal-header"> <a href="javascript:"  onclick="closemodal()" style="float:right;text-decoration:none;">&times;</a>
              <h4 class="modal-title">My Name</h4>
            </div>
            <div class="modal-body">
              <div class="form-group"> <span style="width:25%">First Name&nbsp;<span id="efirstname"></span></span> <span style="width:75%">
                <input type="text" name="firstname" id="firstname" value="<?php if($d->firstname !=""){echo $d->firstname;}?>" required="required" class="form-control inputsize" />
                </span> </div>
              <div class="form-group"> <span style="width:25%">Last Name&nbsp;<span id="elastname"></span></span> <span>
                <input type="text" name="lastname" id="lastname" value="<?php if($d->lastname !=""){echo $d->lastname;}?>"  class="form-control inputsize"/>
                </span> </div>
            </div>
            <div class="modal-footer">
              <input type="submit" name="namesubmit" value="Submit" class="btn btn-default" />
              <a href="javascript:" class="btn btn-danger"  onclick="closemodal()">Close</a> </div>
          </div>
        </form>
      </div>
    </div>
    
    <!--======= Modal for email change ========-->
    <div class="modal fade modalsize2" id="myEmail" role="dialog">
      <div class="modal-dialog"> 
        
        <!-- Modal content-->
        <form action="<?php echo 'customercontroller/customer_email_update';?>" method="post" onsubmit="return valid2()">
          <input type="hidden" name="id" value="<?php echo $d->id;?>" />
          <input type="hidden" name="useremail" value="<?php echo $d->email;?>" />
          <div class="modal-content">
            <div class="modal-header"> <a href="javascript:"  onclick="closemodal()" style="float:right;text-decoration:none;">&times;</a>
              <h4 class="modal-title">My Email</h4>
            </div>
            <div class="modal-body">
              <div class="form-group"> <span style="width:25%">Email&nbsp;<span id="eemail"></span></span> <span style="width:75%">
                <input type="text" name="email" id="email" value="<?php if($d->email !=""){echo $d->email;}?>" required="required" class="form-control inputsize" />
                </span> </div>
            </div>
            <div class="modal-footer">
              <input type="submit" name="namesubmit" value="Submit" class="btn btn-default" />
              <a href="javascript:" class="btn btn-danger"  onclick="closemodal()">Close</a> </div>
          </div>
        </form>
      </div>
    </div>
    
    <!--======= Modal for phone change ========-->
    <div class="modal fade modalsize2" id="myPhone" role="dialog">
      <div class="modal-dialog"> 
        
        <!-- Modal content-->
        <form action="<?php echo 'customercontroller/customer_phone_update';?>" method="post" onsubmit="return valid3()">
          <input type="hidden" name="id" value="<?php echo $d->id;?>" />
          <input type="hidden" name="userephone" value="<?php echo $d->phone;?>" />
          <div class="modal-content">
            <div class="modal-header"> <a href="javascript:"  onclick="closemodal()" style="float:right;text-decoration:none;">&times;</a>
              <h4 class="modal-title">My Phone</h4>
            </div>
            <div class="modal-body">
              <div class="form-group"> <span style="width:25%">Phone&nbsp;<span id="ephone"></span></span> <span style="width:75%">
                <input type="text" name="phone" id="phone" value="<?php if($d->phone !=""){echo $d->phone;}?>" required="required" class="form-control inputsize"  onkeypress="return isNumber(event);" />
                </span> </div>
            </div>
            <div class="modal-footer">
              <input type="submit" name="namesubmit" value="Submit" class="btn btn-default" />
              <a href="javascript:" class="btn btn-danger"  onclick="closemodal()">Close</a> </div>
          </div>
        </form>
      </div>
    </div>
    
    <!--======= Modal for pass change ========-->
    <div class="modal fade modalsize3" id="myPass" role="dialog">
      <div class="modal-dialog"> 
        
        <!-- Modal content-->
        <form action="<?php echo 'customercontroller/customer_password_update';?>" method="post" onsubmit="return valid7()">
          <input type="hidden" name="id" value="<?php echo $d->id;?>" />
          <input type="hidden" name="userecurpass" id="userecurpass" value="<?php echo $d->password;?>" />
          
          <div class="modal-content">
          
            <div class="modal-header"> <a href="javascript:"  onclick="closemodal()" style="float:right;text-decoration:none;">&times;</a>
              <h4 class="modal-title">My Password</h4>
            </div>
            <div class="modal-body">
              <div class="form-group"> <span style="width:25%">Current Password&nbsp;<span id="ecpass"></span></span> <span style="width:100%">
                <input type="password" name="cpass" id="cpass"  required="required" class="form-control inputsize"  />
                </span> </div>
                
              <div class="form-group"> <span style="width:25%">New Password&nbsp;<span id="enpass"></span></span> <span style="width:100%">
                <input type="password" name="npass" id="npass"  required="required" class="form-control inputsize"  />
                </span> </div>
                
              <div class="form-group"> <span style="width:25%">Confirm Password&nbsp;<span id="econfirmpass"></span></span> <span style="width:100%">
                <input type="password" name="confirmpass" id="confirmpass"  required="required" class="form-control inputsize" />
                </span> </div>
              
            </div>
            
            <div class="modal-footer">
              <input type="submit" name="passsubmit" value="Submit" class="btn btn-default" />
              <a href="javascript:" class="btn btn-danger"  onclick="closemodal()">Close</a> </div>
          </div>
        </form>
      </div>
    </div>
    
    <!--======= Modal for address change ========-->
    <div class="modal fade modalsize1" id="myAddress" role="dialog">
      <div class="modal-dialog"> 
        
        <!-- Modal content-->
        <form action="<?php echo 'customercontroller/customer_address_update';?>" method="post" onsubmit="return valid4()">
          <input type="hidden" name="id" value="<?php echo $d->id;?>" />
          <div class="modal-content">
            <div class="modal-header"> <a href="javascript:"  onclick="closemodal()" style="float:right;text-decoration:none;">&times;</a>
              <h4 class="modal-title">My Address</h4>
            </div>
            <div class="modal-body">
              <div class="form-group"> <span style="width:25%;vertical-align:top;">Address&nbsp;<span id="eaddress"></span></span> <span style="width:75%">
                <textarea name="address" id="address" required="required" class="form-control inputsize" ><?php if($d->address !=""){echo $d->address;}?>
</textarea>
                </span> </div>
              
            </div>
            <div class="modal-footer">
              <input type="submit" name="namesubmit" value="Submit" class="btn btn-default" />
              <a href="javascript:" class="btn btn-danger"  onclick="closemodal()">Close</a> </div>
          </div>
        </form>
      </div>
    </div>
    
    <!--======= Modal for city change ========-->
    <div class="modal fade modalsize2" id="myCity" role="dialog">
      <div class="modal-dialog"> 
        
        <!-- Modal content-->
        <form action="<?php echo 'customercontroller/customer_city_update';?>" method="post" onsubmit="return valid5()">
          <input type="hidden" name="id" value="<?php echo $d->id;?>" />
          <div class="modal-content">
            <div class="modal-header"> <a href="javascript:"  onclick="closemodal()" style="float:right;text-decoration:none;">&times;</a>
              <h4 class="modal-title">My City</h4>
            </div>
            <div class="modal-body">
              <div class="form-group"> <span style="width:25%">City&nbsp;<span id="ecity"></span></span> <span style="width:75%">
                <input type="text" name="city" id="city" value="<?php if($d->city !=""){echo $d->city;}?>" required="required" class="form-control inputsize" />
                </span> </div>
            </div>
            <div class="modal-footer">
              <input type="submit" name="namesubmit" value="Submit" class="btn btn-default" />
              <a href="javascript:" class="btn btn-danger"  onclick="closemodal()">Close</a> </div>
          </div>
        </form>
      </div>
    </div>
    
    <!--======= Modal for city change ========-->
    <div class="modal fade modalsize2" id="myCountry" role="dialog">
      <div class="modal-dialog"> 
        
        <!-- Modal content-->
        <form action="<?php echo 'customercontroller/customer_country_update';?>" method="post" >
          <input type="hidden" name="id" value="<?php echo $d->id;?>" />
          <div class="modal-content">
            <div class="modal-header"> <a href="javascript:"   onclick="closemodal()" style="float:right;text-decoration:none;">&times;</a>
              <h4 class="modal-title">My Name</h4>
            </div>
            <div class="modal-body">
              <div class="form-group"> <span style="width:25%">Country</span> <span style="width:75%">
                <select name="country"  class="form-control inputsize">
                  <option value="<?php echo $d->country;?>"> <?php echo $d->country;?> </option>
                  <option value="Aaland Islands">Aaland Islands</option>
                  <option value="Afghanistan">Afghanistan</option>
                  <option value="Albania">Albania</option>
                  <option value="Algeria">Algeria</option>
                  <option value="American Samoa">American Samoa</option>
                  <option value="Andorra">Andorra</option>
                  <option value="Angola">Angola</option>
                  <option value="Anguilla">Anguilla</option>
                  <option value="Antarctica">Antarctica</option>
                  <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                  <option value="Argentina">Argentina</option>
                  <option value="Armenia">Armenia</option>
                  <option value="Aruba">Aruba</option>
                  <option value="Australia">Australia</option>
                  <option value="Austria">Austria</option>
                  <option value="Azerbaijan">Azerbaijan</option>
                  <option value="Bahamas">Bahamas</option>
                  <option value="Bahrain">Bahrain</option>
                  <option value="Bangladesh">Bangladesh</option>
                  <option value="Barbados">Barbados</option>
                  <option value="Belarus">Belarus</option>
                  <option value="Belgium">Belgium</option>
                  <option value="Belize">Belize</option>
                  <option value="Benin">Benin</option>
                  <option value="Bermuda">Bermuda</option>
                  <option value="Bhutan">Bhutan</option>
                  <option value="Bolivia">Bolivia</option>
                  <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
                  <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                  <option value="Botswana">Botswana</option>
                  <option value="Bouvet Island">Bouvet Island</option>
                  <option value="Brazil">Brazil</option>
                  <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                  <option value="Brunei Darussalam">Brunei Darussalam</option>
                  <option value="Bulgaria">Bulgaria</option>
                  <option value="Burkina Faso">Burkina Faso</option>
                  <option value="Burundi">Burundi</option>
                  <option value="Cambodia">Cambodia</option>
                  <option value="Cameroon">Cameroon</option>
                  <option value="Canada">Canada</option>
                  <option value="Canary Islands">Canary Islands</option>
                  <option value="Cape Verde">Cape Verde</option>
                  <option value="Cayman Islands">Cayman Islands</option>
                  <option value="Central African Republic">Central African Republic</option>
                  <option value="Chad">Chad</option>
                  <option value="Chile">Chile</option>
                  <option value="China">China</option>
                  <option value="Christmas Island">Christmas Island</option>
                  <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                  <option value="Colombia">Colombia</option>
                  <option value="Comoros">Comoros</option>
                  <option value="Congo">Congo</option>
                  <option value="Cook Islands">Cook Islands</option>
                  <option value="Costa Rica">Costa Rica</option>
                  <option value="Cote D Ivoire">Cote D Ivoire</option>
                  <option value="Croatia">Croatia</option>
                  <option value="Cuba">Cuba</option>
                  <option value="Curacao">Curacao</option>
                  <option value="Cyprus">Cyprus</option>
                  <option value="Czech Republic">Czech Republic</option>
                  <option value="Democratic Republic of Congo">Democratic Republic of Congo</option>
                  <option value="Denmark">Denmark</option>
                  <option value="Djibouti">Djibouti</option>
                  <option value="Dominica">Dominica</option>
                  <option value="Dominican Republic">Dominican Republic</option>
                  <option value="East Timor">East Timor</option>
                  <option value="Ecuador">Ecuador</option>
                  <option value="Egypt">Egypt</option>
                  <option value="El Salvador">El Salvador</option>
                  <option value="Equatorial Guinea">Equatorial Guinea</option>
                  <option value="Eritrea">Eritrea</option>
                  <option value="Estonia">Estonia</option>
                  <option value="Ethiopia">Ethiopia</option>
                  <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                  <option value="Faroe Islands">Faroe Islands</option>
                  <option value="Fiji">Fiji</option>
                  <option value="Finland">Finland</option>
                  <option value="France, Metropolitan">France, Metropolitan</option>
                  <option value="French Guiana">French Guiana</option>
                  <option value="French Polynesia">French Polynesia</option>
                  <option value="French Southern Territories">French Southern Territories</option>
                  <option value="FYROM">FYROM</option>
                  <option value="Gabon">Gabon</option>
                  <option value="Gambia">Gambia</option>
                  <option value="Georgia">Georgia</option>
                  <option value="Germany">Germany</option>
                  <option value="Ghana">Ghana</option>
                  <option value="Gibraltar">Gibraltar</option>
                  <option value="Greece">Greece</option>
                  <option value="Greenland">Greenland</option>
                  <option value="Grenada">Grenada</option>
                  <option value="Guadeloupe">Guadeloupe</option>
                  <option value="Guam">Guam</option>
                  <option value="Guatemala">Guatemala</option>
                  <option value="Guernsey">Guernsey</option>
                  <option value="Guinea">Guinea</option>
                  <option value="Guinea-Bissau">Guinea-Bissau</option>
                  <option value="Guyana">Guyana</option>
                  <option value="Haiti">Haiti</option>
                  <option value="Heard and Mc Donald Islands">Heard and Mc Donald Islands</option>
                  <option value="Honduras">Honduras</option>
                  <option value="Hong Kong">Hong Kong</option>
                  <option value="Hungary">Hungary</option>
                  <option value="Iceland">Iceland</option>
                  <option value="India">India</option>
                  <option value="Indonesia">Indonesia</option>
                  <option value="Iran (Islamic Republic of)">Iran (Islamic Republic of)</option>
                  <option value="Iraq">Iraq</option>
                  <option value="Ireland">Ireland</option>
                  <option value="Israel">Israel</option>
                  <option value="Italy">Italy</option>
                  <option value="Jamaica">Jamaica</option>
                  <option value="Japan">Japan</option>
                  <option value="Jersey">Jersey</option>
                  <option value="Jordan">Jordan</option>
                  <option value="Kazakhstan">Kazakhstan</option>
                  <option value="Kenya">Kenya</option>
                  <option value="Kiribati">Kiribati</option>
                  <option value="Korea, Republic of">Korea, Republic of</option>
                  <option value="Kuwait">Kuwait</option>
                  <option value="Kyrgyzstan">Kyrgyzstan</option>
                  <option value="Lao Peoples Democratic Republic">Lao Peoples Democratic Republic</option>
                  <option value="Latvia">Latvia</option>
                  <option value="Lebanon">Lebanon</option>
                  <option value="Lesotho">Lesotho</option>
                  <option value="Liberia">Liberia</option>
                  <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                  <option value="Liechtenstein">Liechtenstein</option>
                  <option value="Lithuania">Lithuania</option>
                  <option value="Luxembourg">Luxembourg</option>
                  <option value="Macau">Macau</option>
                  <option value="Madagascar">Madagascar</option>
                  <option value="Malawi">Malawi</option>
                  <option value="Malaysia">Malaysia</option>
                  <option value="Maldives">Maldives</option>
                  <option value="Mali">Mali</option>
                  <option value="Malta">Malta</option>
                  <option value="Marshall Islands">Marshall Islands</option>
                  <option value="Martinique">Martinique</option>
                  <option value="Mauritania">Mauritania</option>
                  <option value="Mauritius">Mauritius</option>
                  <option value="Mayotte">Mayotte</option>
                  <option value="Mexico">Mexico</option>
                  <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                  <option value="Moldova, Republic of">Moldova, Republic of</option>
                  <option value="Monaco">Monaco</option>
                  <option value="Mongolia">Mongolia</option>
                  <option value="Montenegro">Montenegro</option>
                  <option value="Montserrat">Montserrat</option>
                  <option value="Morocco">Morocco</option>
                  <option value="Mozambique">Mozambique</option>
                  <option value="Myanmar">Myanmar</option>
                  <option value="Namibia">Namibia</option>
                  <option value="Nauru">Nauru</option>
                  <option value="Nepal">Nepal</option>
                  <option value="Netherlands">Netherlands</option>
                  <option value="Netherlands Antilles">Netherlands Antilles</option>
                  <option value="New Caledonia">New Caledonia</option>
                  <option value="New Zealand">New Zealand</option>
                  <option value="Nicaragua">Nicaragua</option>
                  <option value="Niger">Niger</option>
                  <option value="Nigeria">Nigeria</option>
                  <option value="Niue">Niue</option>
                  <option value="Norfolk Island">Norfolk Island</option>
                  <option value="North Korea">North Korea</option>
                  <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                  <option value="Norway">Norway</option>
                  <option value="Oman">Oman</option>
                  <option value="Pakistan">Pakistan</option>
                  <option value="Palau">Palau</option>
                  <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                  <option value="Panama">Panama</option>
                  <option value="Papua New Guinea">Papua New Guinea</option>
                  <option value="Paraguay">Paraguay</option>
                  <option value="Peru">Peru</option>
                  <option value="Philippines">Philippines</option>
                  <option value="Pitcairn">Pitcairn</option>
                  <option value="Poland">Poland</option>
                  <option value="Portugal">Portugal</option>
                  <option value="Puerto Rico">Puerto Rico</option>
                  <option value="Qatar">Qatar</option>
                  <option value="Reunion">Reunion</option>
                  <option value="Romania">Romania</option>
                  <option value="Russian Federation">Russian Federation</option>
                  <option value="Rwanda">Rwanda</option>
                  <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                  <option value="Saint Lucia">Saint Lucia</option>
                  <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                  <option value="Samoa">Samoa</option>
                  <option value="San Marino">San Marino</option>
                  <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                  <option value="Saudi Arabia">Saudi Arabia</option>
                  <option value="Senegal">Senegal</option>
                  <option value="Serbia">Serbia</option>
                  <option value="Seychelles">Seychelles</option>
                  <option value="Sierra Leone">Sierra Leone</option>
                  <option value="Singapore">Singapore</option>
                  <option value="Slovak Republic">Slovak Republic</option>
                  <option value="Slovenia">Slovenia</option>
                  <option value="Solomon Islands">Solomon Islands</option>
                  <option value="Somalia">Somalia</option>
                  <option value="South Africa">South Africa</option>
                  <option value="South Georgia &amp; South Sandwich Islands">South Georgia &amp; South Sandwich Islands</option>
                  <option value="South Sudan">South Sudan</option>
                  <option value="Spain">Spain</option>
                  <option value="Sri Lanka">Sri Lanka</option>
                  <option value="St. Barthelemy">St. Barthelemy</option>
                  <option value="St. Helena">St. Helena</option>
                  <option value="St. Martin (French part)">St. Martin (French part)</option>
                  <option value="St. Pierre and Miquelon">St. Pierre and Miquelon</option>
                  <option value="Sudan">Sudan</option>
                  <option value="Suriname">Suriname</option>
                  <option value="Svalbard and Jan Mayen Islands">Svalbard and Jan Mayen Islands</option>
                  <option value="Swaziland">Swaziland</option>
                  <option value="Sweden">Sweden</option>
                  <option value="Switzerland">Switzerland</option>
                  <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                  <option value="Taiwan">Taiwan</option>
                  <option value="Tajikistan">Tajikistan</option>
                  <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                  <option value="Thailand">Thailand</option>
                  <option value="Togo">Togo</option>
                  <option value="Tokelau">Tokelau</option>
                  <option value="Tonga">Tonga</option>
                  <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                  <option value="Tunisia">Tunisia</option>
                  <option value="Turkey">Turkey</option>
                  <option value="Turkmenistan">Turkmenistan</option>
                  <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                  <option value="Tuvalu">Tuvalu</option>
                  <option value="Uganda">Uganda</option>
                  <option value="Ukraine">Ukraine</option>
                  <option value="United Arab Emirates">United Arab Emirates</option>
                  <option value="United Kingdom">United Kingdom</option>
                  <option value="United States">United States</option>
                  <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                  <option value="Uruguay">Uruguay</option>
                  <option value="Uzbekistan">Uzbekistan</option>
                  <option value="Vanuatu">Vanuatu</option>
                  <option value="Vatican City State (Holy See)">Vatican City State (Holy See)</option>
                  <option value="Venezuela">Venezuela</option>
                  <option value="Viet Nam">Viet Nam</option>
                  <option value="Virgin Islands (British)">Virgin Islands (British)</option>
                  <option value="Virgin Islands (U.S.)">Virgin Islands (U.S.)</option>
                  <option value="Wallis and Futuna Islands">Wallis and Futuna Islands</option>
                  <option value="Western Sahara">Western Sahara</option>
                  <option value="Yemen">Yemen</option>
                  <option value="Zambia">Zambia</option>
                  <option value="Zimbabwe">Zimbabwe</option>
                </select>
                </span> </div>
              
            </div>
            <div class="modal-footer">
              <input type="submit" name="namesubmit" value="Submit" class="btn btn-default" />
              <a href="javascript:" class="btn btn-danger"  onclick="closemodal()">Close</a> </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
