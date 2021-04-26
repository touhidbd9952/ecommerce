
<link rel="stylesheet" href="css/adminmaster/table-pagination.css" />
<link rel="stylesheet" href="css/adminmaster/mystyle.css" />
<script src="js/adminmaster/jquery.js"></script>
<script src="js/myjs.js"></script>


<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>


<script>
function confirmationDelete(anchor)
{
   var conf = confirm('Are you sure want to delete this user?');
   if(conf)
      window.location=anchor.attr("href");
}
</script>

<style>
.sm{ color:#008000;margin:15px 0;float:right;font-size:20px}
.em{ color:#F00;margin:15px 0;float:right;font-size:18px}
</style>
<div class="container-fluid">
	<div class="row">
     	<div class="col-lg-12">
<?php if(isset($_GET['msg'])&& $_GET['msg'] !="" )echo '<label class="sm">'.$_GET['msg'].'</label>';?>
<?php if(isset($_GET['emsg'])&& $_GET['emsg'] !="" )echo '<label class="em">'.$_GET['emsg'].'</label>';?>
<h2>User List</h2>
<table id="example" class="table table-striped table-condensed table-bordered">
	<thead>
	<tr>
    	<th>SL</th>
        <th>Username</th>
        <th>Code</th>
        <th>Name</th>
        <th>Address</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Role</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php 
		$sl=1; 
		if(isset($users)&& count($users)>0)
		{
			foreach($users as $u)
			{		
	?>
    <tr>
    	<td><?php echo $sl++;?></td>
        <td><?php if(isset($u->username)&& $u->username !="") echo $u->username; ?></td>
        <td><?php if(isset($u->access_code)&& $u->access_code !="") echo $u->access_code; ?></td>
        <td><?php if(isset($u->name)&& $u->name !="") echo $u->name; ?></td>
        <td><?php if(isset($u->address)&& $u->address !="") echo $u->address; ?></td>
        <td><?php if(isset($u->email)&& $u->email !="") echo $u->email; ?></td>
        <td><?php if(isset($u->phone)&& $u->phone !="") echo $u->phone; ?></td>
        <td><?php if(isset($u->rolegroup)&& $u->rolegroup !="") echo $u->rolegroup; ?></td>
        <td>
        <a href="<?php echo 'acl_management/useredit/'.$u->id?>"><span class="label label-primary">Edit</span></a>
        <a href="<?php echo 'acl_management/delete_user/'.$u->id?>" onclick="javascript:confirmationDelete($(this));return false;"><span class="label label-danger">Delete</span></a>
        </td>
    </tr>
    <?php 
			}
		}
	?>
    </tbody>
</table>

</div>
</div>
</div>




