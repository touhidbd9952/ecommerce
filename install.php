<!DOCTYPE html>
<html>
  <head>
   <base href="<?php echo base_url();?>">
	<title> Schedule Managment | Reset Configuration</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="SYSnet dot NET">
	<meta name="copyright" content="Copyright &copy; 2008 sysnet.net.bd" />
	<meta name="description" content="Accounts">
	<link href='http://fonts.googleapis.com/css?family=Raleway:400,200' rel='stylesheet' type='text/css'>
    <link href="css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
	<div id="wrap">
	<!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="main">Ecommerce Management Installation</a>
        </div>
      </div>
    </div>
	<div class="clrGap">&nbsp;</div>
	<div class="container mybody">
		<div class="install">
		<h4><b>Schedule Managment</b> Installation.</h4>
		<?php echo form_open("_install",'role="form" class="inform" style="width:300px;background:#fff;padding:0px;"');?>
			  <div class="form-group <?php if (form_error('username')) echo "has-error";?>">
				<label class="control-label" for="username">Admin Username</label>
				<input type="text" name="username" id="username" class="form-control input-sm" value="<?php echo set_value('username','Server-Admin')?>">
			  </div>
			  <div class="form-group <?php if (form_error('password')) echo "has-error";?>">
				<label class="control-label" for="password">Password</label>
				<input type="text" name="password" id="password" class="form-control input-sm" value="<?php echo set_value('password',$newpass)?>">
			  </div>
			  <div class="form-group <?php if (form_error('mobile')) echo "has-error";?>">
				<label class="control-label" for="mobile">Mobile No</label>
				<input type="text" name="mobile" id="mobile" class="form-control input-sm" value="<?php echo set_value('mobile','')?>">
			  </div>
			  <div class="form-group <?php if (form_error('email')) echo "has-error";?>">
				<label class="control-label" for="email">Email</label>
				<input type="text" name="email" id="email" class="form-control input-sm" value="<?php echo set_value('email','support@sysnet.net.bd')?>">
			  </div>
              
              
              <!-----------> 
              <div class="form-group <?php if (form_error('companyName')) echo "has-error";?>">
				<label class="control-label" for="companyName">Company Name</label>
				<input type="text" name="companyName" id="companyName" class="form-control input-sm" >
			  </div>
              <div class="form-group <?php if (form_error('aboutCompanywork')) echo "has-error";?>">
				<label class="control-label" for="aboutCompanywork">About Company Work</label>
				<input type="text" name="aboutCompanywork" id="aboutCompanywork" class="form-control input-sm" >
			  </div>
              <div class="form-group <?php if (form_error('address')) echo "has-error";?>">
				<label class="control-label" for="caddress">Company Address</label>
				<input type="text" name="caddress" id="caddress" class="form-control input-sm" >
			  </div>
              <div class="form-group <?php if (form_error('cphone')) echo "has-error";?>">
				<label class="control-label" for="companyphone">Company phone</label>
				<input type="text" name="cphone" id="cphone" class="form-control input-sm" >
			  </div>
              <div class="form-group <?php if (form_error('email')) echo "has-error";?>">
				<label class="control-label" for="companyemail">Company email</label>
				<input type="text" name="cemail" id="cemail" class="form-control input-sm" >
			  </div>
              <div class="form-group <?php if (form_error('footerCopyrighttext')) echo "has-error";?>">
				<label class="control-label" for="footerCopyrighttext">Footer Copyright text</label>
				<input type="text" name="footerCopyrighttext" id="footerCopyrighttext" class="form-control input-sm" >
			  </div>
              
              
              
              <!----------->
			  <button type="submit" class="btn btn-primary btn-sm" ><span class="glyphicon glyphicon-ok"></span> &nbsp;Install</button>
			<?php echo form_close();?>
		</div>
	</div>
	</div> <!--/end Wrap-->
	<div style="padding:20px 115px">
	<p>Powered By SYSnet dot NET</p>
	</div>
  </body>
</html>