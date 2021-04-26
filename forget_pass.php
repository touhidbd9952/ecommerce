
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
<style>
input[type="text"],input[type="password"],input[type="email"],select{border:1px solid #ddd;padding:8px;-webkit-border-radius:3px 0 0 3px;-moz-border-radius:3px 0 0 3px border-radius:3px 0 0 3px;border-style:solid;border-width:1px;color:#888;font-size:14px;margin-bottom:10px;height:36px}
.checkout-heading{background:#f8f8f8;border:1px solid #dbdee1;color:#555;font-size:13px;font-weight:700;margin-bottom:15px;padding:13px 8px}
.checkout a.button,.checkout input.button,a.button{-moz-user-select:none;background-color:#fe5757;background-image:none;border:medium none;border-radius:4px;color:#fff;cursor:pointer;display:inline-block;font-size:12px;font-weight:700;line-height:10px;margin-bottom:0;padding:12px 20px;text-align:center;transition:background .25s ease 0;-webkit-transition:background .25s ease 0;vertical-align:middle;white-space:nowrap;box-shadow:0 -2px 0 rgba(0,0,0,.15) inset}
.memenu{display:none !important;}
.required{color:#F00;}
.em{color:#F00;}
#emailexist{margin-top:-10px;margin-bottom:10px;}
</style>
<script>

function valid()
{
	var useremail = document.getElementById('useremail').value; 
	document.getElementById('euseremail').innerHTML="";
	err=0;
	if(useremail ==""){err++;document.getElementById('euseremail').innerHTML="Enter Email";}
	if(err==0)
	{
	   return true;
	}
	return false;
}
</script>
<div class="breadcrumbs">
		<div class="container">
			<div class="breadcrumbs-main">
				<ol class="breadcrumb" style="margin-bottom:0px !important">
					<li><a href="main/home">Home</a></li>
					<li class="active">Forget Password</li>
				</ol>
			</div>
		</div>
	</div>
<style>
.newcus{font-size:18px;padding-bottom:10px;color:#300;font-family: 'Lora-Regular';}
.contact {padding: 2em 0px;}
.search-bar{display:none;}
.control-label{text-align:right;}
.ck input:checked + i {
	border-color: #73B6E1;	
}

</style>
<div class="single contact">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="checkout">
            <div id="shipping-address">
              
              <div class="checkout-content" style="display: block;">
                <div id="shipping-new" style="">
                  <div class="row" style="margin:0">
                  	<div class="userInfo mgb col-lg-5" style="margin-bottom:300px;">
                    <span class="newcus"><h5 class="header-text">Enter Your Email:</h5> &nbsp;&nbsp;&nbsp;&nbsp;
                            
                              <form action="<?php echo 'main/get_customer_pass'?>" method="post" onsubmit="return valid()">
                                    <span class="em" id="euseremail"></span><br />
                                    <input type="text" name="useremail" id="useremail" style="width:100%;" autocomplete="off"/>
                                    
                                    <input type="submit" value="Send" />
                             </form>
                             
                             
                    </span>
                    </div>
                    
                    
                    
                  </div>
                </div>
                
                <br>
              </div>
            </div>
            
      </div>
      <!--/.row--> 
      
    </div>
</div>