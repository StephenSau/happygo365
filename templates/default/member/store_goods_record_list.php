<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
  <form action="" method="post">
	<table class="ncu-table-style">
		<tr>
			<td>商品名称:<input type="text" name="goods_name" /></td>
		</tr>
		<tr>
			<td ><input type="submit" value="查询" /></td>
		</tr>
	</table>
  </form>
  <table class="ncu-table-style">
	<?php if(!empty($goods_records_list)):?>
	<tr>
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
			<td>审核</td>
			<td>发布状态</td>
		</tr>
	</tr>
	<?php foreach($goods_records_list as $k=>$v):?>
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
			<?php if($v['examine']==1):?>
			<td><span style="color:red;">通过</span></td>
			<?php elseif($v['examine']==0):?>
			<td><span style="color:red;">未通过</span></td>
			<?php endif;?>			
			<?php if($v['is_show']==1):?>
			<td><span style="color:red;">已发布</span></td>
			<?php elseif($v['is_show']==0):?>
			<td><span style="color:red;">未发布</span></td>
			<?php endif;?>
		</tr>
	<?php endforeach;?>
	<?php endif;?>
	<tr>
		<td colspan="6"><div class="pagination"><?php echo $page;?></div></td>
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