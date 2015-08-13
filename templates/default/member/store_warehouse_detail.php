<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
	<?php if(!empty($row)):?>
	<table class="ncu-table-style">
		<tr>
			<td>单证编号:<span style="color:#4384b7;"><?php echo $row[0]['according_num'];?></span></td>
			<td>企业名称:<span style="color:#4384b7;"><?php echo $row[0]['store_name'];?></span></td>
		</tr>		
		<tr>
			<td>单据类型:<span style="color:#4384b7;"><?php echo $row[0]['according_type'];?></span></td>
			<td>进关口岸:<span style="color:#4384b7;"><?php echo $row[0]['inout_port'];?></span></td>
		</tr>			
		<tr>
			<td>账册编号:<span style="color:#4384b7;"><?php echo $row[0]['zhangce_num'];?></span></td>
			<td>进仓日期:<span style="color:#4384b7;"><?php echo $row[0]['inout_date'];?></span></td>
		</tr>		
		<tr>
			<td>申报海关:<span style="color:#4384b7;"><?php echo $row[0]['apply_custom'];?></span></td>
			<td>运输方式:<span style="color:#4384b7;"><?php echo $row[0]['oversight'];?></span></td>
		</tr>			
	</table>
	<br/>
	<table class="ncu-table-style">
		<tr>
			<td>序号</td>
			<td>商品名称</td>
			<td>商品编号</td>
			<td>申报数量</td>
			<td>计量单位</td>
			<td>申报总价</td>
			<td>毛重</td>
			<td>净重</td>
		</tr>
		<?php foreach($row as $k=>$v):?>
		<tr>
			<td><?php echo $v['id'];?></td>
			<td><?php echo $v['goods_name'];?></td>
			<td><?php echo $v['goods_commodity_code'];?></td>
			<td><?php echo $v['goods_number'];?></td>
			<td><?php echo $v['declaration_unit'];?></td>
			<td><?php echo floatval($v['goods_number']*$v['declaration_price']);?></td>
			<td><?php echo $v['gross_weight'];?></td>
			<td><?php echo $v['net_weight'];?></td>
		</tr>	
		<?php endforeach;?>
	</table>
	<?php else:?>
	暂时还没有数据
	<?php endif;?>
</div>
<script>
function success(n,arr)
{
	if( n == 1)
	{
		$("#warehouse").css({"display":"block"});
		$("#warehouse").attr('src',arr);
		
	}else if(n == 0)
	{
		alert('上传失败！');
	}
}
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
	var value = $("#file_list").val();
	if(value == "")
	{
		return false;
	}
	var numError = $('form .error').length;
	if(numError)
	{
		return false;
	}
});

</script>