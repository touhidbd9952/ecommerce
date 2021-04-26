
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style>
html,body, tr, textarea, input, select {
  min-width: 100%;
  background:#fff;
  font-family: "Lucida Grande", Verdana, Arial, sans-serif;
  font-size: 11px;
}

#form{
padding:10px;
}
#t1 h2{
padding:0 0 0 10px;
}
#form h2{font-size:14px;margin:0px; border-bottom:1px solid #ccc;font-weight:bold;padding-bottom:5px;}

#form table.pay td {
  padding: 3px 0px;
}

#form input,select{
padding:5px;
border:1px solid #ccc;
width:100%;
}
</style>

<script type="text/javascript">
function makeReload() 
{	
	window.close();
	window.opener.location.reload();
}

</script>

<table id="t1" height="40" cellspacing="10" width="100%" border="0">
<tr valign="top"><td height="25"><h2>Validate e-Mail</h2></td></tr>
<tr valign="top">
<td>
<form action="<?php echo 'customercontroller/emailverify/'.$cusid;?>" method='post' id='form' name='paymentform' accept-charset="utf-8">
<input type="hidden" name="csrf_test_name" value="75d528385c0f18993a1e103335abd94a" /> 
<table width="100%" cellspacing='0' class='pay'>
<tr><td>Virification Code : </td><td><input type="text" name="code" value="" style="width:250px;" /></td></tr>
<tr><td colspan=2 style="border-bottom:1px solid #ddd;">&nbsp;</td></tr>
</table>
<p style="border-bottom:2px solid #f3f3f3"></p>
<table style="float: right;"> <tr> <td>
<button type="submit" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span> Confirm </button>
<a href="javascript:void()"class="btn btn-danger btn-sm" onclick="javascript:makeReload()"><span class="glyphicon glyphicon-remove"></span> Close</a>
</td></tr></table>
<input type="hidden" name="type" value="validM"/>
</form></td>
</tr>            
</table>