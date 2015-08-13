<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
  <form action="" method="post">
	  <table class="ncu-table-style">
		<tr>
			<td>进境清单文件</td>
			<td>
				<a href="javascript:void(0);" onclick="window.upload.document.upload.file.click()">点击上传</a>
				<iframe name="upload" src="index.php?act=store&op=store_declaration_add_file" style="display:none;" ></iframe>
				<input id="apply_file" type="hidden" name="apply_file" value="" />
			</td>
		</tr>	
		<tr>
			<td colspan="2"><img id="declaration" src="" style="width:500px;height:400px;display:none;" /></td>
		</tr>	
		<tr>
			<td>进境清单明细</td>
			<td>
				<a href="javascript:void(0);" onclick="window.upload2.document.upload.file.click()">点击上传</a>
				<iframe name="upload2" src="index.php?act=store&op=store_declaration_add_file2" style="display:none;" ></iframe>
				<input id="apply_detail" type="hidden" name="apply_detail" value="" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><img id="declaration2" src="" style="width:500px;height:400px;display:none;" /></td>
		</tr>		
		<tr>
			<td colspan="2"><input type="submit" value="提交" /></td>
		</tr>
	  </table>
	</form>
</div>
<script>
function success(n,arr)
{
	if( n == 1)
	{
		$("#declaration").css({"display":"block"});
		$("#declaration").attr('src',arr);
		$("#apply_file").val(arr);
		
	}else if(n == 0)
	{
		alert('上传失败！');
	}
}
function success_one(n,arr)
{
	if( n == 1)
	{
		$("#declaration2").css({"display":"block"});
		$("#declaration2").attr('src',arr);
		$("#apply_detail").val(arr);
		
	}else if(n == 0)
	{
		alert('上传失败！');
	}
}
$("#submit").click(function()
{
	var numError = $('form .error').length;
	if(numError)
	{
		return false;
	}
});

</script>