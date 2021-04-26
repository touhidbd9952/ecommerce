

<style>
input[type="text"],input[type="password"],input[type="email"],select{border:1px solid #ddd;padding:8px;-webkit-border-radius:3px 0 0 3px;-moz-border-radius:3px 0 0 3px border-radius:3px 0 0 3px;border-style:solid;border-width:1px;color:#888;font-size:14px;margin-bottom:10px;height:36px}
.checkout-heading{background:#f8f8f8;border:1px solid #dbdee1;color:#555;font-size:13px;font-weight:700;margin-bottom:15px;padding:13px 8px}
.checkout a.button,.checkout input.button,a.button{-moz-user-select:none;background-color:#fe5757;background-image:none;border:medium none;border-radius:4px;color:#fff;cursor:pointer;display:inline-block;font-size:12px;font-weight:700;line-height:10px;margin-bottom:0;padding:12px 20px;text-align:center;transition:background .25s ease 0;-webkit-transition:background .25s ease 0;vertical-align:middle;white-space:nowrap;box-shadow:0 -2px 0 rgba(0,0,0,.15) inset}
</style>






    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-10 col-sm-10 col-xs-12">
          <div id="content" align="center" style="margin-top:10%;">
            <h1 style="color: #66AAD0;">Your Order Has Been Processed!</h1>
            <div class="wrapper underline">
              <p>Your order has been successfully processed!</p>
              <?php if(isset($orderno)&& !empty($orderno) ){?>
              <p>Your Order No: &nbsp;<?php echo $orderno?></p>
              <?php }?>
              <p>Thanks for shopping with us online!</p>
            </div>
            <div class="buttons">
              <div class="right"><a href="<?php echo base_url();?>" class="button" style="background-color: #66AAD0;text-decoration:none;">Continue</a></div>
            </div>
          </div>
        </div>
      </div>
      <!--/.row--> 
      
    </div>
