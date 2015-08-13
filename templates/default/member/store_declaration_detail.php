<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
	  <table class="ncu-table-style">
		<tr>
			<td>序号:<?php echo $row['id'] ?></td>
			<td>申请时间:<?php echo $row['apply_date'] ?></td>
		</tr>
		<tr>
			<td width="10%">清单文件:<img src="<?php echo $row['apply_file'] ?>" style="width:500px; height:400px;" /></td>
		</tr>		
		<tr>
			<td width="10%">清单明细:<img src="<?php echo $row['apply_detail'] ?>" style="width:500px; height:400px;" /></td>
		</tr>		
		<tr>
			<td>状态：<?php if($row['state'] == 1){echo '审核中';}elseif($row['state'] == 2){echo '通过审核';};?></td>
		</tr>		
	  </table>
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