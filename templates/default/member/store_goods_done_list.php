<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
  <table class="ncu-table-style">
	<?php if(!empty($goods_records_done_list)):?>
		<tr>
			<td>操作类型</td>
			<td>商品申请编号</td>
			<td>商品编码</td>
			<td>商品名称</td>
			<td>商品货号</td>
			<td>商品计量单位</td>
			<td>申报单价</td>
			<td>商品描述</td>
			<td>毛重</td>
			<td>净重</td>
			<td>备注</td>
			<td>操作</td>
		</tr>
		<?php foreach($goods_records_done_list as $k=>$v):?>
			<?php if($v['examine'] == 1 && $v['is_show']==0):?>
				<tr>
					<td><?php echo $v['operation_type'];?></td>
					<td><?php echo $v['goods_apply_num'];?></td>
					<td><?php echo $v['goods_commodity_code'];?></td>
					<td><?php echo $v['goods_name'];?></td>
					<td><?php echo $v['goods_article_num'];?></td>
					<td><?php echo $v['declaration_unit'];?></td>
					<td><?php echo $v['declaration_price'];?></td>
					<td><?php echo $v['goods_note'];?></td>
					<td><?php echo $v['gross_weight'];?></td>
					<td><?php echo $v['net_weight'];?></td>
					<td><?php echo $v['goods_not'];?></td>
					<?php if($v['examine'] == 1 && $v['warehouse'] == 2):?>
					<td><a target="_blank" href="index.php?act=store_goods&op=add_goods&step=one&id=<?php echo $v['id']; ?>"><?php echo $lang['store_goods_show'];?></a></td>
					<?php elseif($v['examine'] == 1):?>
					<td><a href="javascript:void(0);"><?php echo $lang['store_goods_examine_done'];?></a></td>
					<?php else:?>
					<td><a href="javascript:void(0);"><?php echo $lang['store_goods_examine'];?></a></td>
					<?php endif;?>
				</tr>
			<?php endif;?>
		<?php endforeach;?>
	<?php endif;?>
	<tr>
		<td colspan="13"><div class="pagination"><?php echo $page;?></div></td>
	</tr>
  </table>
</div>
<script>
$(".check").each(function()
{
	$(this).blur(function()
	{
		if($(this).val() == "")
		{
			$(this).next("span").addClass('error').text("内容不能为空！");
		}
	});
});
$("#submit").click(function()
{
	var numError = $('form .error').length;
	if(numError)
	{
		return false;
	}
})
</script>