<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
	
	  <table class="ncu-table-style">
	  <?php if(!empty($row)):?>
		<tr>
			<td>总包号</td>
			<td>毛重</td>
			<td>出仓时间</td>
			<td>申报海关</td>
			<td>操作</td>
		</tr>
		<?php foreach($row as $k=>$v):?>
		<tr>
			<td><?php echo rand(00000000,99999999)?></td>
			<td><?php echo $v['gross_weight']?></td>
			<td>2014-10-13</td>
			<td>***</td>
			<td><a href="index.php?act=store&op=loading_list_detail&id=<?php echo $v['id'];?>">查看详情</a></td>
		</tr>
		<?php endforeach;?>
		<?php else:?>
		<tr>
			<td colspan="5">暂时还木有数据</td>
		</tr>
		<?php endif;?>
		<?php if(!empty($row)):?>
		<tr>
			<td colspan="4"><div class="pagination"><?php echo $page;?></div></td>
		</tr>
		<?php endif;?>
	  </table>


	
</div>