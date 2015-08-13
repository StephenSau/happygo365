<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
	  <table class="ncu-table-style">
	  <?php if(!empty($row)):?>
		<tr>
			<td>序号</td>
			<td>申请时间</td>
			<td>清单文件</td>
			<td>清单明细</td>
			<td>状态</td>
			<td>操作</td>
		</tr>
		<?php foreach($row as $k=>$v):?>
		<tr>
			<td><?php echo $v['id'];?></td>
			<td><?php echo $v['apply_date'];?></td>
			<td width="10%"><?php echo $v['apply_file'];?></td>
			<td width="10%"><?php echo $v['apply_detail'];?></td>
			<td><?php if($v['state'] == 1){echo '审核中';}elseif($v['state'] == 2){echo '通过审核';};?></td>
			<td><a href="index.php?act=store&op=store_declaration_detail&id=<?php echo $v['id']?>">查看详情</a></td>
		</tr>
		<?php endforeach;?>
		<?php else:?>
		<tr>
			<td colspan="6">暂时还木有数据</td>
		</tr>
		<?php endif;?>
		<?php if(!empty($row)):?>
		<tr>
			<td colspan="6"><div class="pagination"><?php echo $page; ?></div></td>
		</tr>
		<?php endif;?>
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