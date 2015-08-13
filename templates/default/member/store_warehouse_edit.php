<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
	<form id="apply" action=""  method="post" style="position:absolute;width:600px;height:280px;background-color:#ffffff;border:1px #ccc solid;left:50%;margin-left:-300px;z-index:1000;display:none;">
	  <table class="ncu-table-style">
		<tr>
			<td colspan="4"><span id="close" style="float:right;margin-right:15px;font-size:18px;cursor:pointer;">X</span></td>
		</tr>
		<tr>
			<td>企业内部编号</td>
			<td><input class="check" type="text" name="company_inside_num" /><span style="color:red;"></span></td>		
			<td>申报数量</td>
			<td><input class="check" type="text" name="goods_number" /><span style="color:red;"></span></td>
		</tr>		
		<tr>
			<td>申报地海关</td>
			<td><input class="check" type="text" name="apply_custom" /><span style="color:red;"></span></td>		
			<td>对应单据类型</td>
			<td><input class="check" type="text" name="according_type" /><span style="color:red;"></span></td>
		</tr>		
		<tr>
			<td>对应单证编号</td>
			<td><input class="check" type="text" name="according_num" /><span style="color:red;"></span></td>		
			<td>账册编号</td>
			<td><input class="check" type="text" name="zhangce_num" /><span style="color:red;"></span></td>
		</tr>		
		<tr>
			<td>进出口岸</td>
			<td><input class="check" type="text" name="inout_port" /><span style="color:red;"></span></td>		
			<td>监管方式</td>
			<td><input class="check" type="text" name="oversight" /><span style="color:red;"></span></td>
		</tr>
		<tr>
			<td colspan="3">
				<input id="submit" type="submit" value="提交申请" />
				<input type="hidden" name="is_seller" value="<?php echo $_SESSION["is_seller"]?>" />
				<input type="hidden" name="store_id" value="<?php echo $_SESSION["store_id"]?>" />
				<input id="goods_id" type="hidden" name="id" value="" />
			</td>
		</tr>
	  </table>
	  <img id="warehouse" src="" style="width:800px; height:500px;display:none;" />
	</form>
	<table class="ncu-table-style">
	<?php if(!empty($goods_records_list)):?>
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
			<td>状态</td>
			<td>编辑</td>
		</tr>
		<?php foreach($goods_records_list as $k=>$v):?>
			<?php if($v['is_show']==0):?>
				<tr>
					<td><?php echo $v['operation_type'];?></td>
					<td><?php echo $v['goods_apply_num'];?></td>
					<td><?php echo $v['goods_commodity_code'];?></td>
					<td><?php echo $v['goods_name'];?></td>
					<td><?php echo $v['goods_article_num'];?>
					<td><?php echo $v['declaration_unit'];?></td>
					<td><?php echo $v['declaration_price'];?></td>
					<td><?php echo $v['goods_note'];?></td>
					<td><?php echo $v['gross_weight'];?></td>
					<td><?php echo $v['net_weight'];?></td>
					<td><?php echo $v['goods_not'];?></td>
					<?php if($v['warehouse'] == 0):?>
					<td>
						<a href="javascript:void(0);" onclick="eject(this)"><?php echo $lang['store_goods_apply'];?></a>
						<input type="hidden" name="id" value="<?php echo $v['id']?>" />
					</td>
					<?php elseif($v['warehouse'] == 1):?>
					<td><a href="javascript:void(0);"><?php echo $lang['store_goods_examine'];?></a></td>
					<?php elseif($v['warehouse'] == 2):?>
					<td><a href="javascript:void(0);"><?php echo $lang['store_goods_warehouse_done'];?></a></td>
					<?php elseif($v['warehouse'] == 3):?>
					<td>
						<a href="javascript:void(0);" onclick="check(this);" style="color:red;"><?php echo $lang['store_goods_examine_failed'];?><br/><?php echo $lang['store_goods_examine_check'];?></a>
						<p id="info" style="position:absolute;left:50%;width:400px;height:auto;padding:10px 0 50px 10px;text-align:left;margin:-100px 0 0 -200px;line-height:15px;color:#000; background-color:#ffffff;border:1px #ccc solid;z-indx:1000;display:none;">
							<span class="close" style="float:right;margin:3px 10px 0 0;cursor:pointer;">X</span>
							<?php echo $v['warehouse_receipt_info'];?>
						</p>
					</td>
					<?php else:?>
					<td><a href="javascript:void(0);"><?php echo $lang['store_goods_examine'];?></a></td>
					<?php endif;?>
					<td><a href="index.php?act=store&op=store_warehouse_edit&id=<?php echo $v['id'];?>"><?php echo $lang['store_goods_examine_edit'];?></td>
				</tr>
			<?php endif;?>
		<?php endforeach;?>
	<?php endif;?>
		<?php if(!empty($goods_records_list)):?>
		<tr>
			<td>
				<a href="javascript:void(0);" onclick="window.upload.document.upload.file.click()">批量上传</a>&nbsp;&nbsp;&nbsp;<span id="location"></span>
				<iframe name="upload" src="index.php?act=store&op=store_jincang_csv" style="display:none;" ></iframe>
			</td>
			<td colspan="13"><div class="pagination"><?php echo $page; ?></div></td>
		</tr>
		<?php endif;?>
		
	</table>
</div>
<script>
$("#close").click(function()
{
	$("#apply").hide();
})
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
	var n = 0;
	$(".check").each(function()
	{
		if($(this).val() == "")
		{
			++n;
		}
	});
	
	if(n > 0)return false;
	
	var numError = $('form .error').length;
	if(numError)
	{
		return false;
	}
});

function success()
{
	window.location.href="index.php?act=store&op=store_warehouse_add";
}

function eject(e)
{
	id = $(e).next().val();
	$("#goods_id").val(id)
	$("#apply").toggle();
}
function check(e)
{
	$(e).next('p').toggle();
}

$(".close").click(function()
{
	$("#info").hide();
})
</script>