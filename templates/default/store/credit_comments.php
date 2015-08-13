<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="shopsappraisepjinsearch">
	<div class="goodsspplmid">
		<ul class="ul-goodsspplmid">
			<li class="fl"><input name="evalscore" id="allRate" type="radio" value="0" <?php echo $_GET['evalscore']?'':'checked'; ?> nc_type="sform" href="index.php?act=show_store&op=comments&id=<?php echo $_GET['id'];?><?php if ($_GET['havecontent']){?>&havecontent=1<?php } ?>">
			<?php echo $lang['nc_credit_all'];?></li>
			<li class="fl"><input name="evalscore" id="goodRate" type="radio" value="1" <?php echo $_GET['evalscore'] == 1?'checked':''; ?> nc_type="sform" href="index.php?act=show_store&op=comments&id=<?php echo $_GET['id'];?>&evalscore=1<?php if ($_GET['havecontent']){?>&havecontent=1<?php } ?>">
			<?php echo $lang['nc_credit_good'];?>（<span><?php echo intval($output['goodsstat_list'][4]['gevalstat_level1num']);?></span>）</li>
			<li class="fl"><input name="evalscore" id="mediumRate" type="radio" value="2" <?php echo $_GET['evalscore'] == 2?'checked':''; ?> nc_type="sform" href="index.php?act=show_store&op=comments&id=<?php echo $_GET['id'];?>&evalscore=2<?php if ($_GET['havecontent']){?>&havecontent=1<?php } ?>">
			<?php echo $lang['nc_credit_normal'];?>（<span><?php echo intval($output['goodsstat_list'][4]['gevalstat_level2num']);?></span>）</li>
			<li class="fl"><input name="evalscore" id="worstRate" type="radio" value="3" <?php echo $_GET['evalscore'] == 3?'checked':''; ?> nc_type="sform" href="index.php?act=show_store&op=comments&id=<?php echo $_GET['id'];?>&evalscore=3<?php if ($_GET['havecontent']){?>&havecontent=1<?php } ?>">
			<?php echo $lang['nc_credit_bad'];?>（<span><?php echo intval($output['goodsstat_list'][4]['gevalstat_level3num']);?></span>）</li>
		</ul>
	</div>
</div>

<div class="shopsappraisepjinin">
<?php if(!empty($output['goodsevallist']) && is_array($output['goodsevallist'])){?>
	<table class="table-buyerrefund">
		<thead>
			<tr>
				<th width="40%">评价内容</th>
				<th width="25%"><?php echo $lang['show_store_credit_eval_member'];?></th>
				<th width="35%"><?php echo $lang['show_store_credit_eval_goodsinfo'];?></th>
			</tr>
		</thead>
        
		<tbody>
		<?php foreach($output['goodsevallist'] as $k=>$v){?>
			<tr>
				<td>
					<p class="<?php if($v['geval_scores']=='1'){echo 'p-evaluatenice';}elseif($v['geval_scores']=='0'){echo 'p-evaluateok';}else{echo 'p-evaluateunok';} ?>"><?php echo $v['geval_content'];?><?php echo $v['geval_scores']; ?></p>
				</td>
				<td class="td-buyercredit">
					<a class="a-buyershopsevaluatename" href=""><?php echo $v['geval_frommembername'];?></a>
					<?php if (empty($v['credit_arr'])){echo  $lang['nc_credit_buyer_credit'].$v['member_credit']; }else {?>
					<span class="credit credit2"></span>
					<?php }?>
				</td>
				<td>
					<a class="a-buyershopsevaluatename" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$v['geval_goodsid']), 'goods'); ?>"><?php echo $v['geval_goodsname']?></a>
					<span class="span-buyerrefundprice">￥<?php echo $v['geval_goodsprice'];?><?php echo $lang['currency_zh'];?></span>
				</td>
			</tr>
		<?php }?>
		</tbody>
	</table>
</div>
<div class="store">
	<div class="pagination">
	<?php echo $output['show_page'];?>
	</div>
</div>
<?php }else{?>
<div class="shopsappraisepjinin">
	<table class="table-buyerrefund">
		<thead>
			<tr>
            <td><?php echo $lang['no_record'];?></td>
			</tr>
		</thead>
	</table>
</div>
<?php }?>

<script type="text/javascript">
$(document).ready(function(){
	$('#goodseval').find('.demo').ajaxContent({
		event:'click', 
		loaderType:"img",
		loadingMsg:"<?php echo TEMPLATES_PATH;?>/images/transparent.gif",
		target:'#goodseval'
	});
	$('#goodseval').find('*[nc_type="sform"]').ajaxContent({
		event:'change', 
		loaderType:"img",
		loadingMsg:"<?php echo TEMPLATES_PATH;?>/images/transparent.gif",
		target:'#goodseval'
	});
});
</script>