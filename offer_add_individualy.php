<?php 
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="css/mystyle.css" />
<script src="js/myjs.js"></script>
<style type="text/css">
#message1 {
	font-size: 24px;
	color: green;
}
#message2 {
	font-size: 24px;
	color: red;
}
.input-text-style {
	text-align: right;
}

</style>
<script>
            function CatScat(data){
                data.scatid.options.length = 0;
                var x = data.catid.options[data.catid.selectedIndex].value;
                if(x == 0){
                    data.scatid.options[data.scatid.options.length] = new Option("Select Category First", 0);
                }
             <?php
                foreach ($Category as $cat){                 
             ?>
                else if(x == <?php echo $cat->id ?>){
                    <?php
             foreach ($subCategory as $scat){
                 if($scat->categoryid == $cat->id) {
                    ?>
                    data.scatid.options[data.scatid.options.length] = new Option("<?php  echo $scat->name;?>", <?php echo $scat->id; ?>);
             <?php } } ?>
                }               
                <?php } ?>     
            }
			
			
			function valid()
			{
				var offerid = document.getElementById('offerid').value;
				var offerdiscount = document.getElementById('offerdiscount').value;
				document.getElementById('eofferid').style.cssText="color:black;font-size:12px";
				document.getElementById('eofferdiscount').style.cssText="color:black;font-size:12px";
				var err=0;
				if(offerid == "" || offerid == 0){err++; document.getElementById('eofferid').style.cssText="color:red;font-size:20px";}
				if( offerdiscount !="-1")
				{
				if(offerdiscount == ""){err++; document.getElementById('eofferdiscount').style.cssText="color:red;font-size:20px";}
				else if(offerdiscount != ""){if(!isNaN(discount)){err++; document.getElementById('eofferdiscount').style.cssText="color:red;font-size:20px";}}
				}
				if(err==0){return true;}else{return false;}
			}
 </script>
<script>
$( document ).ready(function() {

	$("#scatid").change(function(){
	   var scatid = $('#scatid').val();
	   $.ajax({
		  type:'POST',
		  data:'subcatid='+scatid,
		  url:'<?php echo "product_management/get_maxcode"?>',
		  success:function(data) 
		  {
			  document.getElementById('code').value = data;
		  }
	   });
	 });
	
});

</script>

	  
 <?php
 		$brandname ="";
		$subcategory = "";
		$subcategoryid = "";
		$categoryid = "";
		$categoryname = "";
		$catid = "";
		$colorid = "";
		$colorname = "";
				
    	foreach($model as $d)
		{
			foreach($unit as $u)
			{
				if($d->unitid == $u->id)
				{
					$unitname = $u->name;
				}
			}
			foreach ($brand as $brn) 
			{
				
				if($d->brandid == $brn->id)
				{
					$brandname = $brn->name; 
				}
			}
			foreach ($color as $cl) 
			{
				
				if($d->colorid == $cl->id)
				{
					$colorname = $cl->name; 
					$colorid = $cl->id; 
				}
			}
			foreach($subCategory as $subc)
			{
				
				if($d->subcategoryid == $subc->id)
				{
					$subcategory = $subc->name;
					$subcategoryid = $subc->id;
					$categoryid = $subc->categoryid;
					foreach($Category as $cat)
					{
						if($categoryid ==$cat->id )
						{
							$categoryname = $cat->name;
							$catid = $categoryid;
							
						}
					}
				}
			}
	?>	    
     <div class="container-fluid">
  <div class="row">
    <div class="col-md-8 col-sm-12">
    	
      <form action="<?php echo base_url(); ?>offer_management/update_product_offer" id="register-form" method="post"  name="myform" onsubmit="return valid()">
      		<input type="hidden" name="id" value="<?php echo $d->id;?>" />
        <h2 class="sub-title">Product Add In Offer</h2>
        <?php
                	if(isset($_GET['sk'])&& !empty($_GET['sk']))echo '<label class="ms mymsg fl-r">'.$_GET['sk'].'</label>';
					if(isset($_GET['esk'])&& !empty($_GET['esk']))echo '<label class="ems mymsg fl-r">'.$_GET['esk'].'</label>';
				?>
        <div class="col-md-4 col-sm-4 col-xs-12">
          <table>
          <tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Category</span></span>
                  <input type="text"  class="form-control input-lg" readonly="readonly"  value="<?php echo $categoryname?>"/>   
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
          	<tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Sub Category</span></span>
              	<input type="text"  class="form-control input-lg" readonly="readonly"  value="<?php echo $subcategory?>"/>
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
          <tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Code</span></span>
                  <input type="text" name="code" id="code" readonly="readonly" class="form-control input-lg"   style="background:#FFF;" value="<?php echo $d->code;?>"/>
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            <tr>
              <td><div class="input-group"> <span class="input-group-addon" style="width:120px;"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Model No.</span></span>
                  <input type="text" name="modelno" id="modelno" readonly="readonly" class="form-control input-lg" placeholder="Model No"  style="background:#FFF;" value="<?php echo $d->modelno;?>">
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            <tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Title</span></span>
                  <input type="text" name="nm" readonly="readonly" class="form-control input-lg" placeholder="Product Title" value="<?php echo $d->title;?>">
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            <tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Brand</span></span>
              		<input type="text" name="brandid" readonly="readonly" class="form-control input-lg" placeholder="Product Title" value="<?php echo $brandname;?>">
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            <tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Description</span></span>
                  <textarea name="des" readonly="readonly" class="form-control input-lg" placeholder="Product Full Description" value="<?php echo $d->description;?>" style="font-size:11px;"><?php echo $d->description;?></textarea>
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            <tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Short Des</span></span>
                  <textarea name="shortdes" readonly="readonly" class="form-control input-lg" placeholder="Product Short Description" style="font-size:11px;" value="<?php echo $d->shortdes;?>"><?php echo $d->shortdes;?></textarea>
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            <tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Buy Price</span></span>
                  <input type="text" name="buyprice" readonly="readonly" class="form-control input-lg" placeholder="Product Price" value="<?php echo $d->buyprice;?>">
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            <tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Regular Price<span id="eregularprice">*</span></span></span>
                  <input type="text" name="regularprice" id="regularprice" readonly="readonly" class="form-control input-lg" placeholder="Product Price" value="<?php if($d->regularprice !="") echo $d->regularprice;?>">
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            <tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Discount(%)</span></span>
                  <input type="text" name="discount" id="discount" readonly="readonly" class="form-control input-lg"  value="<?php echo $d->discount;?>">
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            <tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Sale Price</span></span>
                  <input type="text" name="saleprice" id="saleprice" readonly="readonly" class="form-control input-lg"  value="<?php echo $d->saleprice;?>" onclick="calculate_saleprice()">
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            <tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Vat</span></span>
                  <input type="text" name="vat" readonly="readonly" class="form-control input-lg"  value="<?php echo $d->vat;?>">
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            
            <tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Size/Unit</span></span>
              		<input type="text" name="uid" readonly="readonly" class="form-control input-lg"  value="<?php echo $unitname;?>">
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            <tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Color</span></span>
              		<input type="text" name="colorid" readonly="readonly" class="form-control input-lg"  value="<?php echo $colorname;?>">
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            
            
            <tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Stock</span></span>
                  <input type="text" name="stock" readonly="readonly" class="form-control input-lg"  value="<?php echo $d->stock;?>">
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            
          </table>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
          <table>
          
            <tr>
              <td style="max-width:100px;overflow:hidden;"><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Picture*</span></span>
                  <input type="file" id="imgInp" name="pic" style="height:40px;" >
                </div></td>
            </tr>
            <tr>
              <td><img id="blah" src="<?php if(isset($d->picture)&& !empty($d->picture)){ echo 'img/product/'.$d->picture;} else {echo 'img/upload_img1.png';}?>"  style="border:5px solid #CCCCCC; width:300px;height:225px"><br>
                <br>
                <br></td>
            </tr>
            <tr>
              <td>
                <div class="input-group" id="otherpic"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Other Pictures</span></span>
                  <input type="file" id="imgInp2" name="pic2" style="height:40px;">
                  <img id="blah2" src="<?php if(isset($d->picture2)&& !empty($d->picture2)){ echo 'img/product/'.$d->picture2;} else {echo 'img/upload_img1.png';}?>"  style="border:5px solid #CCCCCC; width:100;height:120px">
                  <input type="file" id="imgInp3" name="pic3" style="height:40px;">
                  <img id="blah3" src="<?php if(isset($d->picture3)&& !empty($d->picture3)){ echo 'img/product/'.$d->picture3;} else {echo 'img/upload_img1.png';}?>"  style="border:5px solid #CCCCCC; width:100;height:120px">
                  <input type="file" id="imgInp4" name="pic4" style="height:40px;">
                  <img id="blah4" src="<?php if(isset($d->picture4)&& !empty($d->picture4)){ echo 'img/product/'.$d->picture4;} else {echo 'img/upload_img1.png';}?>"  style="border:5px solid #CCCCCC; width:100;height:120px">
                  <input type="file" id="imgInp5" name="pic5" style="height:40px;">
                  <img id="blah5" src="<?php if(isset($d->picture5)&& !empty($d->picture5)){ echo 'img/product/'.$d->picture5;} else {echo 'img/upload_img1.png';}?>"  style="border:5px solid #CCCCCC; width:100;height:120px">
                  <input type="file" id="imgInp6" name="pic6" style="height:40px;">
                  <img id="blah6" src="<?php if(isset($d->pictur6)&& !empty($d->picture6)){ echo 'img/product/'.$d->picture6;} else {echo 'img/upload_img1.png';}?>"  style="border:5px solid #CCCCCC; width:100;height:120px">
                </div>
              </td>
            </tr>
          </table>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
          <table>
            <tr>
              <td><br><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Add in Offer<span id="eofferid">*</span></span></span>
                  <select name="offerid" id="offerid" class="form-control input-lg" >
                  		<option value="0">Select offer</option>
                  <?php 
				  $specialads = $this->db->query("select * from t_offer")->result(); 
				  foreach ($specialads as $o) {
				  ?>
                    <option value="<?php if(isset($o->id)&& !empty($o->id)){ echo $o->id;}?>" <?php if(isset($d->offerid)&&($d->offerid==$o->id)){ echo 'selected="selected"';}?> style="color:#3C8DBC;"><?php if(isset($o->title)&& !empty($o->title)){ echo $o->title;}?></option>
                  <?php }?>
                    
                        <option value="-1" style="color:#F00">Remove from offer</option>
                  </select>
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            <tr>
              <td><div class="input-group"> <span class="input-group-addon"><span class="input-text-style">&nbsp;&nbsp;&nbsp;Discount<span id="eofferdiscount">*</span></span></span>
                  <input type="text" name="offerdiscount" id="offerdiscount"  class="form-control input-lg"  value="<?php if(isset($d->offerdiscount) && $d->offerdiscount!="") echo $d->offerdiscount;?>">
                </div>
                
                <!-- End .input-group --><br></td>
            </tr>
            <tr>
              <td><br /><br />
              <input type="submit" name="sub" value="Update Offer" class="btn btn-custom-2 btn-lg md-margin" style="height:80px;margin-bottom:50px;">
              </td>
            </tr>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>                        
<?php }?>