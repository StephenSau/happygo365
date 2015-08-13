<?php defined('haipinlegou') or exit('Access Invalid!');?>
<?php if(!empty($output['bundling_array']) && is_array($output['bundling_array'])){$i=0;?>
<h4 class="h4-combination">组合销售</h4><?php foreach($output['bundling_array'] as $val){?><?php if(!empty($output['b_goods_array'][$val['id']]) && is_array($output['b_goods_array'][$val['id']])){$i++;?><div class="combinationmain">	<ul class="ul-combinationmain">	    <?php ksort($output['b_goods_array'][$val['id']]);foreach($output['b_goods_array'][$val['id']] as $v){?>		<li class="li-combinationmain">			<a class="a-combinationmain-img" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$v['id']), 'goods', $output['store_info']['store_domain']);?>" target="block"><img src="<?php echo cthumb($v['image'], 'small', $v['store_id'])?>" title="<?php echo $v['name'];?>" alt="<?php echo $v['name'];?>" onload="javascript:DrawImage(this,100,100);" /></a>			<a class="a-combinationmain-name" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$v['id']), 'goods', $output['store_info']['store_domain']);?>" target="block"><?php echo $v['name'];?></a>			<b class="b-combinationmain-price"><?php echo $lang['goods_index_goods_cost_price'].$lang['nc_colon'].$lang['currency'].$v['price'];?></b>		</li>				<li class="li-addition">			<i class="i-addition"></i>		</li>		<?php }?>	</ul>	<div class="combination-totalprice" style="float:right;">		<p class="p-infacttotalprice"><?php echo $lang['bundling_price'];?><b class="c-yellow"><i>￥</i><?php echo $val['price'];?></b></p>		<p class="p-totalpricebefore"><?php echo $lang['bundling_save'];?><b>￥<?php echo ncPriceFormat(floatval($val['cost_price'])-floatval($val['price']));?></b></p>		<a href="index.php?act=bundling&bundling_id=<?php echo $val['id']?>&id=<?php echo $v['store_id'];?>" target="block" class="btn a-combination-buy"><?php echo $lang['bundling_buy'];?></a>	</div></div><?php }?><?php }?>
<script>
$(function(){
	$('.nc-promotion').show();
	$('#nc-bundling').show();
	$('.nc-bundling-container').F_slider({len:<?php echo $i;?>});
});
</script>
<?php }?>
