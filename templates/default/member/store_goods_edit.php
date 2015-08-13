<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
  </div>
  <?php if(!empty($row)):?>
  <form action="" method="post">
	  <table class="ncu-table-style">
		<tr>
			<td>商品申请编号</td>
			<td><input class="check" type="text" name="goods_apply_num" value="<?php echo $row['goods_apply_num'];?>" /><span style="color:red;"></span></td>		
			<td>操作类型</td>
			<td>
				<select name="operation_type">
					<option value="M">变更</option>
				</select>
				<span style="color:red;"></span>
			</td>
		</tr>		
		<tr>
			<td>商品货号</td>
			<td><input class="check" type="text" name="goods_article_num" value="<?php echo $row['goods_article_num'];?>" /><span style="color:red;"></span></td>		
			<td>商品名称</td>
			<td><input class="check" type="text" name="goods_name" value="<?php echo $row['goods_name'];?>" /><span style="color:red;"></span></td>
		</tr>		
		<tr>
			<td>规格型号</td>
			<td><input class="check" type="text" name="goods_format" value="<?php echo $row['goods_format'];?>" /><span style="color:red;"></span></td>		
			<td>商品编码</td>
			<td><input class="check" type="text" name="goods_commodity_code" value="<?php echo $row['goods_commodity_code'];?>" /><span style="color:red;"></span></td>
		</tr>		
		<tr>
			<td>申报计量单位</td>
			<td><input class="check" type="text" name="declaration_unit" value="<?php echo $row['declaration_unit'];?>" /><span style="color:red;"></span></td>		
			<td>申报单价</td>
			<td><input class="check" type="text" name="declaration_price" value="<?php echo $row['declaration_price'];?>" /><span style="color:red;"></span></td>
		</tr>		
		<tr>
			<td>行邮税号</td>
			<td><input class="check" type="text" name="tax_num" value="<?php echo $row['tax_num'];?>" /><span style="color:red;"></span></td>		
			<td>毛重</td>
			<td><input class="check" type="text" name="gross_weight" value="<?php echo $row['gross_weight'];?>" /><span style="color:red;"></span></td>
		</tr>		
		<tr>
			<td>净重</td>
			<td><input class="check" type="text" name="net_weight" value="<?php echo $row['net_weight'];?>" /><span style="color:red;"></span></td>		
			<td>备注</td>
			<td><input  type="text" name="goods_not" value="<?php echo $row['goods_not'];?>" /><span style="color:red;"></span></td>
		</tr>		
		<tr>
			<td>进出口标志</td>
			<td>
				<select name="ieflag">
					<option value="E" <?php if($row['ieflag']=='E'):?>selected="selected"<?php endif;?>>出口</option>
					<option value="I" <?php if($row['ieflag']=='I'):?>selected="selected"<?php endif;?>>进口</option>
				</select>
				<span style="color:red;"></span>
			</td>		
			<td>行邮税名称</td>
			<td><input class="check" type="text" name="tax_name" value="<?php echo $row['tax_name'];?>" /><span style="color:red;"></span></td>
		</tr>		
		<tr>
			<td>商品描述</td>
			<td><input class="check" type="text" name="goods_note" value="<?php echo $row['goods_note'];?>" /><span style="color:red;"></span></td>		
			<td>商品条形码</td>
			<td><input type="text" name="bar_code" value="<?php echo $row['bar_code'];?>" /><span style="color:red;"></span></td>
		</tr>
		<tr>
			<td colspan="3">
				<input id="submit" type="submit" value="提交申请" />
				<input type="hidden" name="is_seller" value="<?php echo $_SESSION["is_seller"];?>" />
				<input type="hidden" name="store_id" value="<?php echo $_SESSION["store_id"];?>" />
			</td>
		</tr>
	  </table>
	</form>
	<?php endif;?>
</div>
<script>
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
	window.location.href="index.php?act=store&op=store_goods_records";
}
</script>