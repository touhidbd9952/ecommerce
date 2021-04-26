

<style>
input[type="text"],input[type="password"],input[type="email"],select{border:1px solid #ddd;padding:8px;-webkit-border-radius:3px 0 0 3px;-moz-border-radius:3px 0 0 3px border-radius:3px 0 0 3px;border-style:solid;border-width:1px;color:#888;font-size:14px;margin-bottom:10px;height:36px}
.checkout-heading{background:#f8f8f8;border:1px solid #dbdee1;color:#555;font-size:13px;font-weight:700;margin-bottom:15px;padding:13px 8px}
.checkout a.button,.checkout input.button,a.button{-moz-user-select:none;background-color:#fe5757;background-image:none;border:medium none;border-radius:4px;color:#fff;cursor:pointer;display:inline-block;font-size:12px;font-weight:700;line-height:10px;margin-bottom:0;padding:12px 20px;text-align:center;transition:background .25s ease 0;-webkit-transition:background .25s ease 0;vertical-align:middle;white-space:nowrap;box-shadow:0 -2px 0 rgba(0,0,0,.15) inset}
.memenu{display:none !important;}
.newcus{font-size:18px;padding-bottom:10px;color:#300;font-family: 'Lora-Regular';}
</style>

<script>
function validation()
{
	var paymethod = document.getElementsByClassName('paymethod');
	if(paymethod != "")
	{
		return true;
	}
	return false;
}

</script>

<div class="breadcrumbs">
		<div class="container">
			<div class="breadcrumbs-main">
				<ol class="breadcrumb">
					<li><a href="main/home">Home</a></li>
					<li class="active">Checkout&nbsp;/&nbsp;Delivery Details</li>
				</ol>
			</div>
		</div>
	</div>

<div class="single contact" style="padding: 3em 0px;">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="checkout">
            <!--<div id="shipping-address">
              <div class="checkout-heading">Step 1: Delivery Details</div>
            </div>-->
            <!--<div id="shipping-method">
              <div class="checkout-heading">Step 2: Delivery Method</div>
            </div>-->
            <div id="payment-method">
              <!--<div class="checkout-heading">Step 3: Payment Method</div>-->
              <div class="checkout-content" style="display: block;">
                <div id="shipping-new" style="">
                  <div class="row" style="margin:0">
                    <div class="userInfo col-lg-6" style="color:#701818;">
                      <?php 
					  
					  $deliveryloc = $this->session->userdata('deliveryloc');
					  if($deliveryloc == 0)
					  {
						$deliverycharge = $this->session->userdata('deliverycharge'); 
					  }
					  else
					  {
						$deliverycharge = $this->session->userdata('pickuppointdeliverycharge'); 
					  }
					  ?>
                      <div class="checkout-content" style="display: block;">
                        <form action="<?php echo 'main/checkout_payment_method_save'?>" method="post" onsubmit="return validation()">
                          <span class="newcus"><h3 class="header-text">Please select the preferred shipping method to use on this order.</h3></span><br />
                          <table class="radio">
                            <tbody>
                              <tr class="highlight">
                                <td>
                                <?php 
								if($deliveryloc == 1)
					  			{
								?>
                                  <input type="radio" name="paymethod" value="s"  checked="checked" style="margin:4px 20px 0 0;cursor:pointer">
                                  &nbsp; &nbsp; &nbsp;Cash On Show Room Delivery<br>
                                  <br>
                                <?php 
								}
								else
								{
								?> 
                                  <input type="radio" name="paymethod" value="h" checked="checked" style="margin:4px 20px 0 0;cursor:pointer">
                                  &nbsp; &nbsp; &nbsp; Cash On Home Delivery<br>
                                  <br>
                                <?php 
								}
								?>
                                  
                                  
                                  <input type="radio" name="paymethod" value="" style="margin:4px 20px 0 0;cursor:none;" onclick="this.checked = false;">
                                  &nbsp; &nbsp; &nbsp; PayPal<br>
                                  <br>
                                  <!-- value="p" ----->
                                  
                                  <input type="radio" name="paymethod" value="" style="margin:4px 20px 0 0;cursor:none" onclick="this.checked = false;">
                                  &nbsp; &nbsp; &nbsp; Credit Card <!-- value="c" -----></td>
                              </tr>
                            </tbody>
                          </table>
                          <br>
                          <br>
                          <div class="buttons">
                            <div class="right">
                              <input type="submit" value="Continue" id="button-shipping-method"  style="float: right;">
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="buttons"> 
                  <!--<div class="right"><a id="button-address" class="button"><span>Continue</span></a></div>--> 
                </div>
                <br>
              </div>
            </div>
            <!--<div id="confirm">
              <div class="checkout-heading">Step 4: Confirm Order</div>
              <div class="checkout-content"></div>
            </div>-->
          </div>
        </div>
        <!--<div class="col-lg-3 col-md-3 col-sm-12 margin-top-20-sm"> 
 </div>--> 
      </div>
      <!--/.row--> 
      
    </div>
</div>