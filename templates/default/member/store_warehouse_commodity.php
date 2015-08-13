<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
	<?php if(!empty($row)):?>
	<table  class="ncu-table-style">
		<tr>
			<td>商品名称</td>
			<td>单证编码</td>
			<td>申报价值</td>
			<td>出库情况</td>
		</tr>
		<?php foreach($row as $k=>$v):?>		
		<tr>
			<td><?php echo $v['goods_name'] ?></td>
			<td><?php echo $v['according_num'] ?></td>
			<td><?php echo '￥'.$v['declaration_price'] ?></td>
			<td><span style="color:red;"><?php if($v['warehouse']==0){echo "未出库";}elseif($v['warehouse']==1){echo "待审核";}elseif($v['warehouse']==2){echo "已出库";} ?></span></td>
		</tr>
		<?php endforeach;?>
		<tr>
			<td colspan="7"><div class="pagination"><?php echo $page;?></div></td>
		</tr>
	</table>
	<?php else:?>
	<p style="display:block;width:500px;height:500px;margin:0 auto;text-align:center;line-height:500px;font-size:28px;">暂时还木有数据...</p>
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