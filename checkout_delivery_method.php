<?php 
	$pickuppointdata = $this->db->query("select DISTINCT area from t_pickuppoint")->result();
	$pickuppointalldata = $this->db->query("select id,area, pickuppoint from t_pickuppoint")->result();
	$pickuppointdeliverycharge=0;
?>

<style>
input[type="text"],input[type="password"],input[type="email"],select{border:1px solid #ddd;padding:8px;-webkit-border-radius:3px 0 0 3px;-moz-border-radius:3px 0 0 3px border-radius:3px 0 0 3px;border-style:solid;border-width:1px;color:#888;font-size:14px;margin-bottom:10px;height:36px}
.checkout-heading{background:#f8f8f8;border:1px solid #dbdee1;color:#555;font-size:13px;font-weight:700;margin-bottom:15px;padding:13px 8px}
.checkout a.button,.checkout input.button,a.button{-moz-user-select:none;background-color:#fe5757;background-image:none;border:medium none;border-radius:4px;color:#fff;cursor:pointer;display:inline-block;font-size:12px;font-weight:700;line-height:10px;margin-bottom:0;padding:12px 20px;text-align:center;transition:background .25s ease 0;-webkit-transition:background .25s ease 0;vertical-align:middle;white-space:nowrap;box-shadow:0 -2px 0 rgba(0,0,0,.15) inset}
.memenu{display:none !important;}
.newcus{font-size:18px;padding-bottom:10px;color:#300;font-family: 'Lora-Regular';}
</style>

<script>
function get_deliverypoint(data){
				var arealoc = document.getElementById('arealoc').value; 
				if(arealoc =="" || arealoc =="Select")
				{
					var x = document.getElementById("dp");
					x.remove(x.selectedIndex);
					document.getElementById('pickuppointdeliverycharge').value="";
					document.getElementById('pickuppointdeliverytime').value="";
					//document.getElementById('picupaddress').innerHTML = "";
				}
                data.dp.options.length = 0;
                var x = data.arealoc.options[data.arealoc.selectedIndex].value; 
                if(x == 0){
                    data.dp.options[data.dp.options.length] = new Option("Select Area First", 0);
                }
             <?php
                foreach ($pickuppointalldata as $ppd){                 
             ?>
                else if(x == '<?php echo $ppd->id ?>'){
					data.dp.options[data.dp.options.length] = new Option("Select Delivery Point", 0);
                    <?php
             foreach ($pickuppointalldata as $sc){
                 if($sc->area == $ppd->area) {
                    ?>
                    data.dp.options[data.dp.options.length] = new Option("<?php  echo $sc->pickuppoint;?>", <?php echo $sc->id; ?>);
					
             <?php } } ?>
                }             
                <?php } ?>     
            }
			
function get_pickuppointdeliverycharge()
			{
				var dpid = document.getElementById('dp').value; 
				var del_id = $(this).attr('id');
			   $.ajax({
				  type:'POST',
				  data:'id='+dpid,
				  url:'<?php echo 'main/get_delivery_charge'?>',
				  success:function(data) 
				  {
					if(data) 
					{ // Sucess
						var data = JSON.parse(data); //convert json data to javascript object
						document.getElementById('pickuppointdeliverycharge').value = data.amount;
						document.getElementById('pickuppointdeliverytime').value = data.period;
						document.getElementById('picupaddress').innerHTML = data.pickupaddress;
						document.getElementById('picupaddressloc').value = data.pickupaddress;
					}
				  }
			   });
			}
function valid()
			{
				var shipping_method = document.getElementById('shipping_method').checked; 
				if(shipping_method == true)
				{
					var arealoc = document.getElementById('arealoc').value;
					var err=0;
					if(arealoc =="" || arealoc =="Select")
					{
						err++;
					}
					else
					{
						var dp = document.getElementById('dp').value;
						if(dp == "" || dp == "Select Delivery Point")
						{
							err++;
						}
					}
					if(err==0)
					{
						return true;
					}
					else
					{
						alert("Please select pickup point");
						return false;
					}
				}
				else
				{
					return true;
				}
				
			}
			
 </script>
<div class="breadcrumbs">
		<div class="container">
			<div class="breadcrumbs-main">
				<ol class="breadcrumb">
					<li><a href="main/home">Home</a></li>
					<li class="active">Checkout&nbsp;/&nbsp;Delivery Method</li>
				</ol>
			</div>
		</div>
	</div>

<div class="single contact" style="padding: 3em 0px;">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="checkout">
            <div id="shipping-method">
              <div class="checkout-content" style="display:block;">
                <div id="shipping-new" style="">
                  <div class="row" style="margin:0">
                    <div class="userInfo col-lg-6" style="color:#701818;">
                      <?php 
						$deliverycharge = $this->session->userdata('deliverycharge');
						$deliveryperiod = $this->session->userdata('deliveryperiod');
					  ?>
                      <div class="checkout-content" style="display: block;">
                        <form action="<?php echo 'main/checkout_delivery_method_save'?>" method="post" name="myform" onsubmit="return valid()">
                          <table class="radio">
                            <tbody>
                              <tr>
                                <td colspan="3"><span class="newcus"><h3 class="header-text">Select product delivery address</h3></span><br /><br /></td>
                              </tr>
                              <tr class="highlight">
                                <td colspan="3">
                                	<div col-md-3 col-sm-3 col-xs-12> 
                                	<input name="shipping_method"  value="homeaddress"  type="radio" checked="checked" style="margin:0 !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Home address<br />
                                    <?php 
									$cusfirstname = $this->session->userdata('cusfirstname');
									$cuslastname = $this->session->userdata('cuslastname');
									$cusname = $cusfirstname; 
									if($cuslastname !=""){$cusname .=" ".$cuslastname;}
									$cusaddress = $this->session->userdata('cusaddress');
									$cusemail = $this->session->userdata('cusemail');
									$cusemail = $this->session->userdata('cusemail');
									$cusphone = $this->session->userdata('phone');
									$deliveryaddress ="";
									if($cusname !=""){$deliveryaddress ='<b>Name:</b> '.$cusname;}
									if($cusaddress !=""){$deliveryaddress .='<br><b>Address:</b> '.$cusaddress;}
									if($cusemail !=""){$deliveryaddress .='<br><b>Email:</b> '.$cusemail;}
									if($cusphone !=""){$deliveryaddress .='<br><b>Phone:</b> '.$cusphone;}
									?>
                                    <div style="max-width:360px;min-height:100px;border:1px solid #600;padding:10px;"><?php if($deliveryaddress !="")echo $deliveryaddress;?></div>
                                    <br />
                                	<label for="flat.flat" style="margin-top: 12px;">Flat Shipping Rate:&nbsp;</label>
                                    <label for="flat.flat" style="margin-top: 12px;"><?php if(isset($deliverycharge))echo $deliverycharge ?></label>
                                    <label for="flat.flat" style="margin-top: 0px;">Delivery Time(in days):&nbsp;</label>
                                    <label for="flat.flat" style="margin-top: 12px;"><?php if(isset($deliveryperiod))echo $deliveryperiod ?></label>
                                    </div>
                                    
                                    <?php 
									if(count($pickuppointalldata)>0)
									{
									?>
                                    <div col-md-3 col-sm-3 col-xs-12>
                                    <br /><br />
                                	<input name="shipping_method" id="shipping_method"  value="pickuppoint"   type="radio" style="margin:0 !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pickup Point<br />
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    Select Area
                                    <select name="arealoc" id="arealoc" style="min-width:250px;color:#701818;margin-bottom:10px;" onchange ="get_deliverypoint(document.myform)">
                                    	<option value="Select">Select</option>
                                      <?php 
									  	$areaarr = array();
										
									  	foreach($pickuppointalldata as $pd)
										{
											if(!in_array($pd->area,$areaarr))
											{
												array_push($areaarr,$pd->area);
									  ?>	
                                    	<option value="<?php echo $pd->id;?>"><?php echo $pd->area;?></option>
                                       <?php 
											}
											
										}
									   ?> 
                                    </select>
                                   <br />
                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    Delivery Point
                                    <select name="dp" id="dp" style="min-width:250px;color:#701818" onchange="get_pickuppointdeliverycharge()">
                                    	
                                    </select>
                                    <br />
                                    <div  style="max-width:360px;min-height:100px;border:1px solid #600;padding:10px;">
                                    	<span id="picupaddress"></span>
                                        <input type="hidden" name="picupaddressloc" id="picupaddressloc">
                                    </div>
                                    
                                	<label for="flat.flat" style="margin-top: 12px;">Flat Shipping Rate:&nbsp;</label>
                                    <input type="text" name="pickuppointdeliverycharge" id="pickuppointdeliverycharge"  style="margin-top: 12px;cursor:pointer;border-color:#FFF !important;color:#600;width:80px;margin-bottom:0px;" readonly="readonly">
                                   
                                   <label for="flat.flat" style="margin-top: 0px;">Delivery Time(in days):&nbsp;</label>
                                    <input type="text" name="pickuppointdeliverytime" id="pickuppointdeliverytime"  style="margin-top: 12px;cursor:pointer;border-color:#FFF !important;color:#600;width:50px;" readonly="readonly">
                                    </div>
                                    <?php 
									}
									?>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                          <br>
                          <b>Add Comments About Your Order</b> <br>
                          <textarea name="comment" rows="8" cols="60"></textarea>
                          <br>
                          <br>
                          <div class="buttons">
                            <div class="right">
                              <input value="Continue" id="button-shipping-method"  style="float: right;" type="submit">
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="buttons"> 
                </div>
                <br>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--/.row--> 
      
    </div>
</div>