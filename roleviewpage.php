<style>
.sm{ color:#008000;margin:15px 0;float:right;font-size:20px}
.em{ color:#F00;margin:15px 0;float:right;font-size:18px}
</style>

<script>
function confirmationDelete(anchor)
{
   var conf = confirm('Are you sure want to delete this record?');
   if(conf)
      window.location=anchor.attr("href");
}
</script>

<?php if(isset($_GET['msg'])&& $_GET['msg'] !="" )echo '<label class="sm">'.$_GET['msg'].'</label>';?>
<?php if(isset($_GET['emsg'])&& $_GET['emsg'] !="" )echo '<label class="em">'.$_GET['emsg'].'</label>';?>
<h2>Role Group List</h2>
<table class="table table-striped table-condensed table-bordered">
	<tr>
    	<th>SL</th>
        <th>Role-Group</th>
        <th>Access</th>
        <th></th>
    </tr>
    <?php 
		$sl=1; 
		$perm ="";
		if(isset($roles)&& count($roles)>0)
		{
			foreach($roles as $r)
			{
				$accessarray = array();
				$access="";
				if($r->permissionids != "")
				{
					$perm = explode(',',$r->permissionids);
				}
				foreach($permission as $p)
				{
					if(in_array($p->id,$perm))
					{
						array_push($accessarray,$p->name);
					}
				}
				
				if(count($accessarray)>0)
				{
					$access=implode(',',$accessarray);
				}
					
	?>
    <tr>
    	<td><?php echo $sl++;?></td>
        <td><?php if(isset($r->group)&& $r->group !="") echo $r->group; ?></td>
        <td><?php if(isset($access)&& count($access)>0)print_r($access)?></td>
        <td> 
        	<a href="<?php echo 'acl_management/roleedit/'.$r->id?>"><span class="label label-primary">Edit</span></a>
            <a href="<?php echo 'acl_management/delete_data/'.$r->id?>" onclick="javascript:confirmationDelete($(this));return false;"><span class="label label-danger">Delete</span></a>
        </td>
    </tr>
    <?php 
			}
		}
	?>
</table>




