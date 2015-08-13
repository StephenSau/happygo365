<?php defined('haipinlegou') or exit('Access Invalid!');?>
	<div class="mt-20">	
		<form class="goods_record_search" action="index.php?act=store&op=store_goods_records" method="get">
			<input type="hidden" value="store" name="act">
			<input type="hidden" value="store_goods_records" name="op">
			<span class="fl"><input id="tijiao" value="<?php if(!empty($_GET['goods_name'])){echo $_GET['goods_name'];}else{echo '请输入商品名称';}?>" name="goods_name" maxlength="22" type="text" class="search-text" /></span>
			<span><input id="look"  class="submit aaa" type="submit" value="提交"></span> 
		</form>	
		<script>
			$("#tijiao").focus(function()
			{
				$(this).val('');
			});
		</script>
		<br/>
		<table class="sell-table table">	
		<?php if(!empty($goods_records_list)):?>		
			<thead>			
				<tr>				
					<th width="15%">商品信息</th>	
					<th width="10%">商品货号</th>
					<th width="20%">商品编码</th>	
					<th width="20%">商品申请编号</th>	
					<th width="10%">申报价格</th>		
					<th width="10%">备注</th>			
					<th width="15%">编辑</th>		
				</tr>	
			</thead>	
		<tbody>	
			<?php foreach($goods_records_list as $k=>$v):?>	
				<?php if($v['is_show']==0 && $v['examine'] == 1):?>		
					<tr>			
						<td class="t-div"><?php echo $v['goods_name'];?></td>			
						<td><?php echo $v['goods_article_num'];?></td>			
						<td><?php echo $v['goods_commodity_code'];?></td>			
						<td><?php echo $v['goods_apply_num'];?></td>			
						<td><?php echo $v['declaration_price'];?></td>			
						<td><?php echo $v['gross_weight'];?></td>			
						<?php if($v['examine'] == 1):?>	
						<td>					
							<p class="t-p">		
								<a target="_blank" href="index.php?act=store_goods&op=add_goods&step=one&id=<?php echo $v['id']; ?>" class="delete-btn"><?php echo $lang['store_goods_show'];?></a>					
							</p>				
						</td>
					</tr>					
					<?php endif;?>				
				<?php endif;?>	
			<?php endforeach;?>		
		</tbody>	
		<?php endif;?>	
	</table>	
	<div class="store">	 
	<div class="pagination-store">
	<?php echo $output['page'];?></div>	
	</div>
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

function error(m)
{
	alert(m);
	location.reload();
}

function check(e)
{
	$(e).next('p').toggle();
}

$(".close").click(function()
{
	$(".info").hide();
})
</script>