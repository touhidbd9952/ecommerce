<?php 
	$allemail = $this->db->query("select email from t_customer")->result();
?>
<link rel="stylesheet" href="css/mystyle.css" />
<script src="js/myjs.js"></script>
  
<style>
input[type="text"],input[type="password"],input[type="email"],select{border:1px solid #ddd;padding:8px;-webkit-border-radius:3px 0 0 3px;-moz-border-radius:3px 0 0 3px border-radius:3px 0 0 3px;border-style:solid;border-width:1px;color:#888;font-size:14px;height:36px}
.checkout-heading{background:#f8f8f8;border:1px solid #dbdee1;color:#555;font-size:13px;font-weight:700;margin-bottom:15px;padding:13px 8px}
.checkout a.button,.checkout input.button,a.button{-moz-user-select:none;background-image:none;border:medium none;border-radius:4px;color:#000;cursor:pointer;display:inline-block;font-size:12px;font-weight:700;line-height:10px;margin-bottom:0;padding:12px 20px;text-align:center;transition:background .25s ease 0;-webkit-transition:background .25s ease 0;vertical-align:middle;white-space:nowrap;box-shadow:0 -2px 0 rgba(0,0,0,.15) inset}
/*.memenu{display:none !important;}*/
.required{color:#F00;}
.em{color:#F00;}
#searchword,.search-bar{display:none;}
.dropdown, .dropup { position: relative;top: -18px;}

.register, .btn {color: #ffffff;padding: 0 22px;font-size:12px;text-align: center;text-decoration: none;height: 27px;display: inline-block;line-height: 0px;font-family: 'Lato', sans-serif;font-weight: bold;border-radius: 3px;background: #3c8dbc;background: -moz-linear-gradient(top, #3c8dbc 0%, #3c8dbc 100%);background: -webkit-linear-gradient(top, #3c8dbc 0%,#3c8dbc 100%);background: linear-gradient(to bottom, #3c8dbc 0%,#3c8dbc 100%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3c8dbc', endColorstr='#3c8dbc',GradientType=0 );box-shadow: inset 0px 1px 0px 0px #AAAEB1;margin: 0px;border:none !important;margin-top:15px;}
.dropdown.open div {min-width: 100px;min-height: 67px;top: 50px;background: #000;border-radius: 0px;margin-left: -10px;}
</style>
<script>

function countphonedegit()
{
	var phone = document.getElementById('phone').value;
	document.getElementById('countph').innerHTML="";
	if(phone == "")
	{
		document.getElementById('countph').innerHTML="";
	}
	else
	{
		var count = phone.length;
		document.getElementById('countph').innerHTML=count;
	}
}
function validation()
{
	var email = document.getElementById('email').value;
	var password = document.getElementById('password').value;
	var firstname = document.getElementById('firstname').value;
	var lastname = document.getElementById('lastname').value;
	var phone = document.getElementById('phone').value;
	var address = document.getElementById('address').value;
	var city = document.getElementById('city').value;
	var country = document.getElementById('country').value
	
	document.getElementById('eemail').innerHTML = "";
	document.getElementById('epassword').innerHTML='';
	document.getElementById('efirstname').innerHTML='';
	document.getElementById('elastname').innerHTML='';
	document.getElementById('ephone').innerHTML = "";
	document.getElementById('eaddress').innerHTML = "";
	document.getElementById('ecity').innerHTML = "";
	document.getElementById('ecountry').innerHTML = "";
	var err = 0;
	
	if(email == "")
	{
		++err;
		document.getElementById('eemail').innerHTML='required';
	}
	else if(email != "")
	{
		var atpos = email.indexOf("@");
    	var dotpos = email.lastIndexOf(".");
    	if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length) 
		{
        	++err;
			document.getElementById('eemail').innerHTML ="invalid";
    	}
		var isemailexist = 0;
		<?php
			if(count($allemail)>0) 
			{
				foreach($allemail as $ae)
				{
		?>
					if(email == '<?php echo $ae->email?>')
					{
						isemailexist = 1;
					}
		<?php			
				}
			}	
		?>
		if(isemailexist == 1)
		{
			++err; document.getElementById('eemail').innerHTML = " already exit";
		}		
	}
	if(password == "")
	{
		++err;
		document.getElementById('epassword').innerHTML='required';
	}
	else if(password.length <6 || password.length >12)
	{
		++err;
		document.getElementById('epassword').innerHTML='6-12 length';
	}
	if(firstname == "")
	{
		++err;
		document.getElementById('efirstname').innerHTML='required';
	}
	else if(!isNaN(firstname))
	{
		++err;
		document.getElementById('efirstname').innerHTML='invalid';
	}
	if(lastname != "")
	{
		if(!isNaN(lastname))
		{
			++err;
			document.getElementById('elastname').innerHTML='invalid';
		}
	}
	
	if(phone == "")
	{
		++err;
		document.getElementById('ephone').innerHTML='required';
	}
	else if( phone.length > 12)
	{
		++err;
		document.getElementById('ephone').innerHTML ="invalid";
	}
	else if( isNaN(phone))
	{
		++err;
		document.getElementById('ephone').innerHTML ="invalid";
	}
	if(address == "")
	{
		++err;
		document.getElementById('eaddress').innerHTML='required';
	}
	else if( address.length > 200)
	{
		++err;
		document.getElementById('eaddress').innerHTML ="maximum length exceed";
	}
	if(city == "")
	{
		++err;
		document.getElementById('ecity').innerHTML='required';
	}
	if(country == "")
	{
		++err;
		document.getElementById('ecountry').innerHTML='required';
	}
	if(err == 0)
	{
		return true;
	}
	return false;
}
</script>

<style>
.newcus{font-size:18px;padding-bottom:10px;color:#300;}

</style>

    <div class="container" style="margin-top:50px;">
      <div class="row">
      	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">&nbsp;</div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin-bottom:50px;border-right:1px solid #CCC;">
          <div class="checkout">
            <div id="shipping-address">
            <?php
                	if(isset($_GET['sk'])&& !empty($_GET['sk']))echo '<label class="ms mymsg fl-r">'.$_GET['sk'].'</label>';
				?>
              <h3 class="header-text">New Customer Signup</h3><br />
              <div class="checkout-content" style="display: block;">
                <div id="shipping-new" style="">
                  <div class="row" style="margin:0">
                  	
                    
                    <div class="userInfo col-lg-6"> 
                      <form action="<?php echo 'main/customer_info_save';?>" method="post" onsubmit="return validation()">
                      <div class="form-group">
                          Email&nbsp;<span class="required" id="eemail">*</span><br />
                            <input name="email" id="email" value="" style="font-weight:bold;width:300px;" class="large-field" placeholder="your email" autocomplete="off" type="text">
                        </div>
                        <div class="form-group">
                          Password&nbsp;<span class="required" id="epassword">*</span><br />
                            <input name="password" id="password" value="" class="large-field" style="width:300px;" autocomplete="off" type="password" placeholder="password 6-12 char">
                        </div>
                        <div class="form-group"> 
                           First Name&nbsp;<span class="required" id="efirstname">*</span><br />
                            <input name="firstname" id="firstname" value="" class="large-field" style="width:300px;" autocomplete="off" type="text">
                        </div>
                        <div class="form-group">
                           Last Name&nbsp;<span class="required" id="elastname"></span><br />
                            <input name="lastname" id="lastname" value="" class="large-field" style="width:300px;" autocomplete="off" type="text">
                        </div>
                        
                        <div class="form-group">
                          Phone(<span id="countph">0</span>)&nbsp;<span class="required" id="ephone">*</span><br />
                            <input name="phone" id="phone" value="" style="font-weight:bold;width:300px;" class="large-field" placeholder="88017XXXXXXXX" autocomplete="off" onkeyup="return countphonedegit();" type="text">
                        </div>
                        <div class="form-group">
                          Address&nbsp;<span class="required" id="eaddress">*</span><br />
                            <input name="address" id="address" value="" class="large-field" style="width:300px;" autocomplete="off" type="text">
                        </div>
                        
                        <div class="form-group">
                        <?php 
							$citylocation="";
							$deliverydata = $this->db->query("select * from delivery")->result();
							if(count($deliverydata)>0)
							{
								$citylocation = $deliverydata[0]->address;
							}
						?>
                          City Location&nbsp;<span class="required" id="ecitylocation">*</span><br />
                            <select name="citylocation" class="large-field" style="width:300px;">
                              <option value="Local"><?php if($citylocation !=""){echo $citylocation;}else{echo 'Dhaka';}?></option>
                              <option value="National"><?php if($citylocation !=""){echo 'Outsite '.$citylocation;}else{echo 'Outsite Dhaka';}?></option>
                              <option value="International"><?php if($deliverydata[2]->address !=""){echo $deliverydata[2]->address;}else{echo 'Outsite Bangladesh';}?></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                          City&nbsp;<span class="required" id="ecity">*</span><br />
                            <input type="text" name="city" id="city" class="large-field" style="width:300px;">
                        </div>
                        
                        <div class="form-group">
                          Country&nbsp;<span class="required" id="ecountry">*</span><br />
                            <select name="country" id="country" class="large-field" style="width:300px;">
                              <option value=""> --- Please Select --- </option>
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
                              <option value="Bangladesh" selected="selected">Bangladesh</option>
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
                        </div>
                        <div class="form-group" style="min-width:300px;">
                        <input id="button-address" class="btn btn-box-tool" value="Save" style="float:right;height:40px;line-height:15px;padding:0 20px;margin:20px 0px;" type="submit">
                        </div>
                      </form>
                    </div>
                    <?php
						if(isset($_GET['esk'])&& !empty($_GET['esk']))echo '<label class="ems mymsg fl-r">'.$_GET['esk'].'</label>';
					?>
                  </div>
                </div>
                <br>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-lg-4 col-md-4 col-sm-4" style="margin-bottom:50px;">
        	<style>
			.loginform{width:290px;padding:0px 20px;}
			.logininput{width:250px;height:30px;line-height:30px;border:1px solid #CCC;border-radius:5px;text-indent:10px;margin-bottom:10px;}
		</style>
        	<script>
			function loginvalid()
			{
				var loginemail = document.getElementById('loginemail').value;
				var loginpass = document.getElementById('loginpass').value;
				document.getElementById('eloginemail').innerHTML="";
				document.getElementById('eloginpass').innerHTML="";
				err=0;
				if(loginemail =="")
				{
					err++; document.getElementById('eloginemail').innerHTML="required";
				}
				else if(loginemail != "")
				{
					var atpos = loginemail.indexOf("@");
					var dotpos = loginemail.lastIndexOf(".");
					if (atpos<1 || dotpos<atpos+2 || dotpos+2>=loginemail.length) 
					{
						++err;
						document.getElementById('eloginemail').innerHTML ="invalid";
					}
				}
				if(loginpass =="")
				{
					err++; document.getElementById('eloginpass').innerHTML="required";
				}
				if(err==0)
				{
					return true;
				}
				return false;
			}
			</script>
        	<h3 class="header-text">Customer Login</h3><br />
        	<form action="<?php echo 'main/customer_panel'?>" method="post" class="loginform"  onsubmit="return loginvalid()">
        	<input type="hidden" name="cusid" id="cid">
        	<table>
            
            <tr>
                <td>Email&nbsp;<span id="eloginemail" class="em"></span><br>
                    <input type="text" name="loginemail" id="loginemail" class="logininput" autocomplete="off">
                </td>
            </tr>
            <tr>
                <td>Password&nbsp;<span id="eloginpass" class="em"></span><br>
                    <input type="password" name="loginpass" id="loginpass" class="logininput">
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit"  value="Login" style="float:right;">
                </td>
            </tr>
            </table>
        </form>
        </div>
        
      </div>
      <!--/.row--> 
      
    </div>
