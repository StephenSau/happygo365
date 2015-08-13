<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
  <?php if(empty($_GET['id'])):?>
  <form action="" method="post">
	 <table class="ncu-table-style">
		<tr>
			<td>企业名称</td>
			<td><input class="check" type="text" name="company_name" /><span style="color:red;"></span></td>
		</tr>		
		<tr>
			<td>法人代表</td>
			<td><input class="check" type="text" name="company_host" /><span style="color:red;"></span></td>
		</tr>		
		<tr>
			<td>企业分类</td>
			<td>
				<select class="check" name="company_category">
					<option select="selected">请选择企业分类</option>
					<option>快递</option>
					<option>归类</option>
					<option>平台</option>
					<option>配送</option>
				</select>
				<span style="color:red;"></span>
			</td>
		</tr>		
		<tr>
			<td>开户银行</td>
			<td><input class="check" type="text" name="openning_bank" /><span style="color:red;"></span></td>
		</tr>		
		<tr>
			<td>银行账号</td>
			<td><input class="check" type="text" name="bank_account" /><span style="color:red;"></span></td>
		</tr>		
		<tr>
			<td>仓库名称</td>
			<td><input class="check" type="text" name="warehouse_name" /><span style="color:red;"></span></td>
		</tr>		
		<tr>
			<td>仓库标识</td>
			<td>
				<select class="check" name="warehouse_id">
					<option select="selected">请选择仓库标志</option>
					<option>报税仓</option>
					<option>监管仓</option>
				</select>
				<span style="color:red;"></span>
			</td>
		</tr>		
		<tr>
			<td colspan="2"><input type="submit" value="提交"></td>
		</tr>
	</table>
  </form>
  <?php elseif(!empty($_GET['id'])):?>
  	<p style="display:block;margin:0 auto;width:500px;height:500px;font-size:28px;text-align:center;line-height:500px;">
		<span>备案正在审核中...</span>
	</p>
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
		}else
		{
			$(this).next("span").removeClass('error').text("");
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
});

</script>