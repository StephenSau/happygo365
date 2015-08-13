<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
	  <table class="ncu-table-style">
		<tr>
			<td>总包号:&nbsp;<?php echo $row['package_num']; ?></td>
			<td>毛重:&nbsp;<?php echo $row['gross_weight']; ?></td>
		</tr>
		<tr>
			<td>出仓时间:&nbsp;<?php echo $row['warehouse_time']; ?></td>
			<td>申报海关:&nbsp;<?php echo $row['custom_declaration']; ?></td>
		</tr>
		<tr>
			<td><a href="javascript:void(0);">打印</a></td>
		</tr>
	  </table>	

</div>