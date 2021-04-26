<style>
.style1 {
	border: 1px solid #D3D3D3;
	min-height: 120px;
	margin: 5px;
	font-family: "Roboto Condensed", sans-serif;
}
.divstyle1 {
	padding: 5px;
	font-family: Roboto, Helvetica, Arial, sans-serif;
	font-weight: bold;
}
.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
	font-family: inherit;
	font-weight: 500;
	line-height: 1.1;
	color: inherit;
}
.pu {
	padding-bottom: 5px !important;
	border-bottom: 1px solid #4b4b4b !important;
	margin-top: -1px;
	font-size: 14px;
}
.chemail {
	color: #000;
}
.chemail:hover {
	color: #73B6E1;
	text-decoration: none;
	font-size: 12px;
}
.i-edit {
	background: url(img/edit.png) no-repeat;
}
.i-address {
	background: url(img/address.png) no-repeat;
}
.i-phone {
	background: url(img/phone.png) no-repeat;
}
.editstyle {
	position: absolute;
	bottom: 10px;
	right: 40px;
	text-decoration: none;
	color: #000;
	font-size: 12px;
}
.editstyle:hover {
	color: #73B6E1;
	text-decoration: none;
	font-size: 12px;
}
.addressstyle {
	font-family: "Roboto Condensed", sans-serif;
	font-size: 12px;
	font-weight: normal;
	margin: 0px;
}
.phonestyle {
	font-family: "Roboto Condensed", sans-serif;
	font-size: 12px;
	font-weight: normal;
	margin-top: 10px;
}
.adm {
	float: right;
	margin-top: 5px;
	color: #000;
}
.adm:hover {
	color: #73B6E1;
	text-decoration: none;
}
</style>

<div class="w3l_banner_nav_right" style="margin-bottom:30px; 0">
  <div class="w3ls_w3l_banner_nav_right_grid">
    <h4 style="margin:15px 15px;text-align:left;font-size: 2em;">NEWSLETTER</h4>
    <hr>
    <div class="col-md-12 col-sm-12 col-xs-12" style="margin:0 -15px 50px -15px;">
      <?php 
			if($viewnews == 1)
			{
		?>
      <h4>You are currently not subscribed to any of our newsletters.</h4>
      <?php 
			}
			else
			{
				if(count($newsdata)>0)
				{
					foreach($newsdata as $n)
					{
		?>
      <table>
        <tr>
          <td><div style="margin-top:10px;text-align:justify;">
              <?php if(isset($n->value)&& $n->value !="") echo $n->value;?>
            </div></td>
        </tr>
      </table>
      <?php
					}
				}
			}
		?>
    </div>
  </div>
</div>
