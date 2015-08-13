<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
	<?php if($row['examine'] == 1):?>
	 <table class="ncu-table-style">
		<tr>
			<td>企业名称:</td>
			<td><?php echo $row['company_name']?></td>
		</tr>		
		<tr>
			<td>法人代表:</td>
			<td><?php echo $row['company_host']?></td>
		</tr>		
		<tr>
			<td>企业分类:</td>
			<td><?php echo $row['company_category']?></td>
		</tr>		
		<tr>
			<td>开户银行:</td>
			<td><?php echo $row['openning_bank']?></td>
		</tr>		
		<tr>
			<td>银行账号:</td>
			<td><?php echo $row['bank_account']?></td>
		</tr>		
		<tr>
			<td>仓库名称:</td>
			<td><?php echo $row['warehouse_name']?></td>
		</tr>		
		<tr>
			<td>仓库标识:</td>
			<td><?php echo $row['warehouse_id']?></td>
		</tr>		
	</table>
	<?php elseif($row['examine'] == 0):?>
		
	<p style="display:block;margin:0 auto;width:500px;font-size:28px;height:500px;text-align:center;line-height:500px;">
		<a href="index.php?act=store&op=company_apply">还没申请或者等待管理员审核...</a>
	</p>
	
	<?php endif;?>
</div>