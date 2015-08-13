<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
	<form action=""  method="post">
	  <table class="ncu-table-style">
		<tr>
			<td>申报地海关</td>
			<td><input class="check" type="text" name="apply_custom" /><span style="color:red;"></span></td>		
			<td>单据类型</td>
			<td><input class="check" type="text" name="according_type" /><span style="color:red;"></span></td>
		</tr>		
		<tr>
			<td>单证编号</td>
			<td><input class="check" type="text" name="according_num" /><span style="color:red;"></span></td>		
			<td>账册编号</td>
			<td><input class="check" type="text" name="zhangce_num" /><span style="color:red;"></span></td>
		</tr>	
		<tr>
			<td colspan="3">
				<input id="submit" type="submit" value="查询" />
				<input type="hidden" name="is_seller" value="<?php echo $_SESSION["is_seller"]?>" />
				<input type="hidden" name="store_id" value="<?php echo $_SESSION["store_id"]?>" />
			</td>
		</tr>
	  </table>
	</form>
	<table  class="ncu-table-style">
	<?php if(!empty($row)):?>
		<tr>
			<td>申报地海关</td>
			<td>单证编号</td>
			<td>账册编号</td>
			<td>进出口岸</td>
			<td>运输方式</td>
			<td>到达日期</td>
			<td>操作</td>
		</tr>
		<?php foreach($row as $k=>$v):?>		
		<tr>
			<td><?php echo $v['apply_custom'] ?></td>
			<td><?php echo $v['according_num'] ?></td>
			<td><?php echo $v['zhangce_num'] ?></td>
			<td><?php echo $v['inout_port'] ?></td>
			<td><?php echo $v['oversight'] ?></td>
			<td><?php echo $v['inout_date'] ?></td>
			<td><a href="index.php?act=store&op=store_warehouse_detail&id=<?php echo $v['jincang_input'] ?>">查看</a></td>
		</tr>
		<?php endforeach;?>
		<?php endif;?>
		<?php if(!empty($row)):?>
		<tr>
			<td colspan="7"><div class="pagination"><?php echo $page;?></div></td>
		</tr>
		<?php endif;?>
	</table>
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
		}else
		{
			$(this).next("span").removeClass('error').text('');
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