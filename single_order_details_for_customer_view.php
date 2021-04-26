
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


<style type="text/css">
table{border-collapse:collapse;}
.tableHeader{font-size:24px;color:#000;padding:5px 0px;}
.tdStyle{text-align:center;}
th{text-align:center}
.btnStyle{padding:0px 20px;}
#message1{color:green; font-size:24px;}
#message2{color:red; font-size:24px;}
.print-btn{width: 60px;height: 40px;padding: 10px;float: right;position: absolute;top: 0;right: 0;background: #4D4D4D;text-align: center;color: #FFF;}
@media print
{  
	@page { margin: 0; }
  	body { margin: 1.6cm; }  
    .no-print, .no-print *
    {
        display: none !important;
    }
	.print-size{width:700px;}
	a:link:after, a:visited:after /*Stop print url*/
	{
    	content: "";
	}
}
</style>



<script>
$(document).ready(function(e) {
    $('#hideme').show();
	$('#hideme').click(function(){ 
		$('#hideme').hide();
	})
});




function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}


</script>

			
				
          <?php 
		    $name = "";
			$email = "";
			$phone = "";
			$country = "";
			$deliverylocation = "";
			$customerid ="";
		  	foreach($singleorderreq as $so)
			{
				$customerid = $so->customerid;
				$paystatus = $so->paymentstatus;
				$paysmethod = $so->paymethod;
				$invoiceno = $so->invoiceno;
				$deliverylocation = $so->deliveryloc;
			}
			foreach($customer as $c)
			{
				if($c->id == $customerid)
				{
					$name = $c->firstname;
					if($c->lastname !=""){$name .= " ".$c->lastname;}
					$email = $c->email;
					$phone = $c->phone;
					$country = $c->country;
				}
			}
		  ?>   
         <div class="container">   
         <div id="printableArea">        
         <div class="row">
         	<div class="col-md-10 col-sm-10" style="margin-left:40px;" > 
            	
                <div class="row">
                	<div class="col-md-12 col-sm-12 " style="min-width:550px;">
                        <div style="width:100%;min-height:150px;">
                        
                        <h3><?php echo $this->mm->getSet('Company Name');?></h3>
                        <h5><?php echo $this->mm->getSet('Address');?></h5>
                        <h5><?php echo $this->mm->getSet('servermail');?></h5>
                        <h5><?php echo $this->mm->getSet('phone');?></h5>
                        </div>      
                        
                        <div style="position:absolute;top:50px;right:20px;">
                            <table>
                                <tr><th colspan="3" style="text-align:left;text-transform:uppercase;color:#800000;">Customer</th></tr>
                                <tr><th style="text-align:right">Name</th> <th>&nbsp;:&nbsp;</th> <td><?php echo $name;?></td></tr>
                                <tr><th style="text-align:right">Email</th> <th>&nbsp;:&nbsp;</th> <td><?php echo $email;?></td></tr>
                                <tr><th style="text-align:right">Phone</th> <th>&nbsp;:&nbsp;</th> <td><?php echo $phone;?></td></tr>
                                <tr><th style="text-align:right">Country</th> <th>&nbsp;:&nbsp;</th> <td><?php echo $country;?></td></tr>
                                <tr><th style="text-align:right">Delivery Location</th> <th>&nbsp;:&nbsp;</th> <td><?php echo $deliverylocation;?></td></tr>
                            </table>
                        </div>
                     </div> 
                   </div>
                   
                  <div class="row">  
                     <div class="col-md-6 col-sm-6 ">
                        <div>
                            <table>
                                <tr><th colspan="3" style="text-align:left;text-transform:uppercase;color:#800000;">Payment</th></tr>
                                <tr><th style="text-align:right">Method</th> <th>&nbsp;:&nbsp;</th> <td><?php echo $paysmethod;?></td></tr>
                            </table>
                        </div>
                     </div>   
                 </div>
        
        <br /><br />
        <h5 class="tableHeader" >Orders No: &nbsp;<?php echo $orderno;?></h5>
        <br />
        <h5 style="text-transform:uppercase">Order details:</h5>
                        
        <table class="table confirmtable table-condensed" style="background:#FFF;z-index:10000;margin-bottom:0px; width:100%;">
                              <tr style="background:#CCC">
                              	<th></th>
                                <th style="text-align:left">Code</th>
                                <th width="30%" style="text-align:left">Title</th>
                                <th style="text-align:left">UnitPrice</th>
                                <th width="10%" style="text-align:left">Qty</th>
                                <th width="10%" style="text-align:left">Size&color</th>
                                <th style="text-align:right">Total</th>
                                <th></th>
                              </tr>
                              <?php 
									$mdata = array();
									$grandtotal = 0;
									$subtotal = 0;
									$totaldiscount = 0;
									$totalvat = 0;
									$totalprice = 0;
										$discountonorder = 0;
										$grandgrandtotal = 0;
									$couponvalue =0;
									
									foreach($singleorderreq as $s)
									{
										$customerid = $s->customerid;
										$discountonorder = $s->discount_on_order;
										$customer = $this->db->query("SELECT * FROM t_customer WHERE id ='".$customerid."'")->result(); //print_r($product);
										foreach($customer as $c)
										{
											$email = $c->email;
											$phone = $c->phone;
											$country = $c->country;
											$deliverylocation = $c->address;
										}
										$details = json_decode($s->details);  //print_r($details);exit;
										foreach($details as $d)
										{
											//print_r($d);
											$productid = $d->productid;
												$price = $d->price; //unitprice
												$discount = $d->discount;
												$vat = $d->vat;
												$qty = $d->qty; //
												$size = $d->size;
												$color = $d->color;
											$product = $this->db->query("SELECT * FROM product WHERE id ='".$productid."'")->result(); //print_r($product);
											foreach($product as $p)
											{
												$code = $p->code; // code
												$title = $p->title; // title
												$imagename = $p->picture;
								
									
								?>
                                
                              <li style="list-style:none;">
                                <tr>
                                <td><?php if(isset($imagename)&& !empty($imagename)){?>
                                    <img src="<?php echo base_url().'img/product/'.$imagename?>" style="width:100px;height:80px;">
                                    <?php }?></td>
                                  <td align="left"><?php if(isset($code)&& !empty($code))echo $code?></td>
                                  <td align="left"><?php if(isset($title)&& !empty($title))echo $title?></td>
                                  <td align="left"><div style="margin-right:0px;"><?php echo $price?></div></td>
                                  <td align="left"><?php echo $qty?></td>
                                  <td align="left"><?php echo $size?><p><?php echo $color?></p></td>
                                  <td align="left"><div style="float:right;margin-right:0px;"><?php echo  $price * $qty.'.00'?></div></td>
                                  <td></td>
                                  
                                </tr>
                              </li>
                              <?php 
							  					$totalprice += $price * $qty;  //total
												$totaldiscount += $discount; //total discount
												$totalvat += $vat; //total vat
												$subtotal += (($price * $qty)+ $vat) ; //grand total
							  					}
										}

										
										
										$couponvalue = $s->couponvalue; //coupon
										$deliverycharge = $s->deliverycharge; // delivery charge
										$paymethod = $s->paymethod;
										$paymentstatus = $s->paymentstatus;
									}

									$subtotal = $subtotal+$deliverycharge;
									
								?>
                                <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><span style="font-weight:bold;float:right;">Total:</span></td>
                                <td colspan="3"><div style="float:right;margin-right:0px;"><?php echo  $totalprice.'.00'?></div>    
                              </tr>
                              <?php 
							  if($couponvalue !=0)
							  {
							  ?>
                                <tr class="noborder">
                                <td style="border:none;"></td>
                                <td style="border:none;"></td>
                                <td style="border:none;"></td>
                                <td><span style="font-weight:bold;float:right;">Coupon:</span></td>
                                <td colspan="3"><div style="float:right;margin-right:0px;"><?php echo  '- '.$couponvalue?></div>    
                              </tr>
                              <?php 
							  }
							  ?>
                              <tr class="noborder">
                                <td style="border:none;"></td>
                                <td style="border:none;"></td>
                                <td style="border:none;"></td>
                                <td><span style="font-weight:bold;float:right;">Vat:</span></td>
                                <td colspan="3"><div style="float:right;margin-right:0px;"><?php echo  '+ '.$totalvat.'.00'?></div>   
                              </tr>
                              <tr class="noborder">
                                <td style="border:none;"></td>
                                <td style="border:none;"></td>
                                <td style="border:none;"></td>
                                <td><span style="font-weight:bold;float:right;font-size:12px;">Flat Shipping Rate:</span></td>
                                <td colspan="3"><div style="float:right;margin-right:0px;"><?php echo  '+ '.$deliverycharge?></div>   
                              </tr>
                              
                              <tr class="noborder">
                                <td style="border:none;"></td>
                                <td style="border:none;"></td>
                                <td style="border:none;"></td>
                                <td><span style="font-weight:bold;float:right;">GrandTotal:</span><br>
                                  <br></td>
                                <td colspan="3"><div style="float:right;margin-right:0px;">
                                <?php 
									  if($couponvalue !=0)
									  {
									  ?>
										<?php echo  '<span style="font-weight:bold;">'.number_format(($subtotal - $couponvalue),2).'</span>'?>
                                    <?php 
									  }
									  else
									  {
									?>
									<?php echo  '<span style="font-weight:bold;">'.number_format($subtotal,2).'</span>'?>
                                <?php
									  }
									?>
                                </div>  
                              </tr>
                            </table>
                                  

				
	</div>       
    </div>
 </div>
 </div>