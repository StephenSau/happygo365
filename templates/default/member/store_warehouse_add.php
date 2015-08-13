<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
	
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
		</tr>
		<?php foreach($goods_records_list as $k=>$v):?>
			<?php if($v['is_show']==0 && !empty($v['goods_custom_num'])):?>
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
					<?php if($v['warehouse'] == 1):?>
					<td>
						已提交申请
						
					</td>
					<?php elseif($v['warehouse'] == 2):?>
					<td><span style="color:#599dda;"><?php echo $lang['store_goods_warehouse_done'];?></span></td>
					<?php elseif($v['warehouse'] == 3):?>
					<td>
						<a href="javascript:void(0);" onclick="check(this);" style="color:red;"><?php echo $lang['store_goods_examine_failed'];?><br/><?php echo $lang['store_goods_examine_check'];?></a>
						<p class="info" style="position:absolute;left:50%;width:400px;height:auto;padding:10px 0 50px 10px;text-align:left;margin:-100px 0 0 -200px;line-height:15px;color:#000; background-color:#ffffff;border:1px #ccc solid;z-indx:1000;display:none;">
							<span class="close" style="float:right;margin:3px 10px 0 0;cursor:pointer;">X</span>
							<?php echo $v['warehouse_receipt_info'];?>
						</p>
					</td>
					<?php else:?>
					<td><a href="javascript:void(0);"><?php echo $lang['store_goods_examine'];?></a></td>
					<?php endif;?>
				</tr>
			<?php endif;?>
		<?php endforeach;?>
	<?php else:?>
	<tr>
		<td colspan="12">
			<p style="color:#4384b7;">
				暂时还没有数据
			</p>
		</td>
	</tr>
	<?php endif;?>
		<?php if(!empty($goods_records_list)):?>
		
	</table>
</div>
<script>
$("#close").click(function()
{
	$("#apply").hide();
});

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
	$(".info").hide();
});

$("#checkallBottom").click(function()
{
	if($(this).is(":checked"))
	{
		$("input.choese").each(function()
		{
			$(this).attr("checked","checked");
		});
		
	}else
	{
		$("input.choese").each(function()
		{
			$(this).attr("checked",false);
		});		
	}
})

function export_all()
{
	var id = '';
	var store_id = '';
	var arr=[];
	var n = 0;
	$("[name^=id]:checkbox").each(function()
	{
		if($(this).is(":checked"))
		{
			store_id += $(this).val()+',';
			id+=this.value+',';
			n+=1;
		}
	});
	if(n==0)
	{
		alert('请选择要导出的数据！');return false;
	}
	
	store_id = store_id.substring(0,store_id.length-1);
	
	$("#goods_id_all").val(store_id);
	
	$("#apply").show();
	
}
</script>