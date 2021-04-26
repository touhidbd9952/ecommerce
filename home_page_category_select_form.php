<link rel="stylesheet" href="css/mystyle.css" />
<script src="js/myjs.js"></script>
<style>
.form {
	min-width: 250px;
	min-height: 250px;
	padding: 50px 50px;
	margin-bottom: 50px;
}
.fl-r{float:right;m}
.ms{float:right;}
</style>
<?php
	$sociallink = $this->db->query("select * from t_settings where subject='social link'")->result();
	$facebook='';
	$googleplus='';
	$twitter='';
	$forum='';
	foreach($sociallink as $s)
	{
		if($s->name=='facebook')
		{
			$facebook=$s->value;
		}
		if($s->name=='google plus')
		{
			$googleplus=$s->value;
		}
		if($s->name=='twitter')
		{
			$twitter=$s->value;
		}
		if($s->name=='forum')
		{
			$forum=$s->value;
		}
	}
?>
<script>
function valid()
{
	var cid = document.getElementById('cid').value;
	document.getElementById('ecid').innerHTML="";
	var err=0;
	if(cid =="")
	{
		err++;
		document.getElementById('ecid').innerHTML="Required";
	}
	if(err==0)
	{
		return true;
	}
	return false;
}
</script>
<div class="container" style="margin-left:20px;">
  <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div>
      	<?php if(isset($_GET['sk'])&& !empty($_GET['sk']))echo '<label class="ms mymsg fl-r">'.$_GET['sk'].'</label>'; ?>
        <header class="content-title"><h1 class="title" style="color:#3C8DBC">Catalog &raquo; Home Category</h1></header>
      	<h3>Home Page Category</h3>
        		
        <form action="<?php echo 'admincontroller/set_home_category'?>" method="post" onsubmit="return valid()">
          <table style="width:100%;">
            <tr>
              <td>Category&nbsp;<span id="ecid"></span>
              <br> 
                <select name="cid" id="cid" style="width:250px;height:30px;">
                		<option value=""></option>
                	<?php 
						if(isset($category)&& count($category)>0)
						{
							foreach($category as $cat)
							{
					?>
                			<option value="<?php echo $cat->id?>"><?php echo $cat->name?></option>
                    <?php 
							}
							
						}
					?>
                </select>
                </td>
            </tr> 
            <tr>
              <td><input type="submit" value="Save" class="register" style="margin-top:30px;"/></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
