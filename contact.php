<?php
	$sociallink = $this->db->query("select * from t_settings")->result();
	$companyName='';
	$AboutCompanywork='';
	$address='';
	$email='';
	$loginLink='';
	$registerLink='';
	$phone='';
	$fax='';
	$emailuslink='';
	$footerCopyrighttext='';
	
	foreach($sociallink as $s)
	{
		if($s->name=='Company Name')
		{
			$companyName=$s->value;
		}
		
		if($s->name=='Address')
		{
			$address=$s->value;
		}
		if($s->name=='phone')
		{
			$phone=$s->value;
		}
		if($s->name=='fax')
		{
			$fax=$s->value;
		}
		if($s->name=='servermail')
		{
			$email=$s->value;
		}
		if($s->name=='Login Link')
		{
			$loginLink=$s->value;
		}
		if($s->name=='Register Link')
		{
			$registerLink=$s->value;
		}
		if($s->name=='E-mail us link')
		{
			$emailuslink=$s->value;
		}
		if($s->name=='Footer Copyright text')
		{
			$footerCopyrighttext=$s->value;
		}
		
	}
?>


<script>
function valid()
{
	var name = document.getElementById('name').value;
	var phone = document.getElementById('phone').value;
	var email = document.getElementById('email').value;
	var subject = document.getElementById('subject').value;
	var message = document.getElementById('message').value;
	var err=0;
	if(name==""){++err; document.getElementById('name').style.borderColor="red";}
	else{document.getElementById('name').style.borderColor="black";}
	if(phone==""){++err; document.getElementById('phone').style.borderColor="red";}
	else{document.getElementById('phone').style.borderColor="black";}
	if(email==""){++err; document.getElementById('email').style.borderColor="red";}
	else{document.getElementById('email').style.borderColor="black";}
	if(subject==""){++err; document.getElementById('subject').style.borderColor="red";}
	else{document.getElementById('subject').style.borderColor="black";}
	if(message==""){++err; document.getElementById('message').style.borderColor="red";}
	else{document.getElementById('message').style.borderColor="black";}
	if(err==0)
	{
		return true;
	}
	return false;
}

</script>
<script src="js/myjs.js"></script>
<link rel="stylesheet" href="css/mystyle.css" />


<style>
.contact-right input[type="text"]:nth-child(4) {
    margin: 10px 0 5px 0;width: 100%;
}
.btn {
    color: #ffffff;
    padding: 0 22px;
    font-size: 12px;
    text-align: center;
    text-decoration: none;
    height: 12px;
    display: inline-block;
    line-height: 12px;
    font-family: 'Lato', sans-serif;
    font-weight: bold;
    border-radius: 3px;
    background: #3c8dbc;
    background: -moz-linear-gradient(top, #3c8dbc 0%, #3c8dbc 100%);
    background: -webkit-linear-gradient(top, #3c8dbc 0%,#3c8dbc 100%);
    background: linear-gradient(to bottom, #3c8dbc 0%,#3c8dbc 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3c8dbc', endColorstr='#3c8dbc',GradientType=0 );
    box-shadow: inset 0px 1px 0px 0px #AAAEB1;
    margin: 0px;
    border: none;
}
</style>
<!--start-breadcrumbs-->
	<div class="breadcrumbs">
		<div class="container">
			<div class="breadcrumbs-main">
				<ol class="breadcrumb">
					<li><a href="<?php echo 'main/home'?>">Home</a></li>
					<li class="active">Contact</li>
				</ol>
			</div>
		</div>
	</div>
	<!--end-breadcrumbs-->
	<!--contact-start-->
	<div class="contact">
		<div class="container">
			<!--<div class="contact-top heading">
				
			</div>-->
				<div class="contact-text">
				
					<div class="col-md-9 contact-right">
                    <h2>CONTACT</h2> 
                    	<?php
                			if(isset($_GET['sk'])&& !empty($_GET['sk']))echo '<label class="ms mymsg fl-r">'.$_GET['sk'].'</label>';
						?>
						<form action="<?php echo 'main/contactmail';?>" method="post" onsubmit="return valid()">
							<input type="text" name="name" id="name"  tabindex="0" placeholder="Name" style="margin-bottom:10px;">
							<input type="text" name="phone" id="phone"  tabindex="1" placeholder="Phone">
							<input type="text" name="email" id="email"  tabindex="2"  placeholder="Email">
                            <input type="text" name="subject" id="subject"  tabindex="3"  placeholder="Subject">
							<textarea name="message" id="message"  tabindex="4" placeholder="Message"></textarea>
							<div class="submit-btn">
								<input type="submit" value="SUBMIT" tabindex="5">
							</div>
						</form>
                        <?php
							if(isset($_GET['esk'])&& !empty($_GET['esk']))echo '<label class="ems mymsg fl-r">'.$_GET['esk'].'</label>';
						?>
					</div>	
                    
                    <div class="col-md-3 contact-left">
						<div class="address">
							<h5>Address</h5>
							<p><?php if(isset($companyName)&&$companyName!=""){echo $companyName;}else{echo 'The company name';}?>, 
							<span><?php if(isset($address)&&$address!=""){echo $address;}else{echo 'address address';}?>.</span></p>
						</div>
						<div class="address">
							<h5>Address1</h5>
							<p>Tel:<?php if(isset($phone)&&$phone!=""){echo $phone;}else{echo '123456789';}?>, 
							<span>Fax:<?php if(isset($fax)&&$fax!=""){echo $fax;}else{echo '123456789';}?></span>
							Email: <a href="mailto:<?php if(isset($email)&&$email!=""){echo $email;}?>"><?php if(isset($email)&&$email!=""){echo $email;}else{echo 'contact@example.com';}?></a></p>
						</div>
					</div>
                    
					<div class="clearfix"></div>
				</div>
		</div>
	</div>
	<!--contact-end-->