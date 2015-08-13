<?php defined('haipinlegou') or exit('Access Invalid!');?>
<?php include template('store/header');?>

<?php include template('store/top');?>
<div class="content">
	<div class="sellercnt pdt40">
		<div class="w1210 font-mic">
			<div class="shopsappraise mb20">
				<h3 class="sellertlefttitle"><?php echo $lang['show_store_credit_good_rate'];?> ( <?php echo $output['store_info']['praise_rate'];?> % )</h3>
				<div class="shopsappraisein">
					<table class="table-shopsappraisein">
						<thead>
							<tr>
								<th width="16%"></th>
								<th width="21%"><p class="p-shopsappraisein1"><?php echo $lang['nc_credit_good'];?>（<b>+1</b>）</p></th>
								<th width="21%"><p class="p-shopsappraisein2"><?php echo $lang['nc_credit_normal'];?>（<b>+0</b>）</p></th>
								<th width="21%"><p class="p-shopsappraisein3"><?php echo $lang['nc_credit_bad'];?>（<b>-1</b>）</p></th>
								<th width="21%"><p><?php echo $lang['show_store_credit_sum'];?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="td-textl"><?php echo $lang['show_store_credit_week'];?></td>
								<td><?php echo intval($output['goodsstat_list'][0]['gevalstat_level1num']);?></td>
								<td><?php echo intval($output['goodsstat_list'][0]['gevalstat_level2num']);?></td>
								<td><?php echo intval($output['goodsstat_list'][0]['gevalstat_level3num']);?></td>
								<td><?php echo intval($output['goodsstat_list'][0]['gevalstat_level1num'])+intval($output['goodsstat_list'][0]['gevalstat_level2num'])+intval($output['goodsstat_list'][0]['gevalstat_level3num']);?></td>
							</tr>								
							<tr>
								<td class="td-textl"><?php echo $lang['show_store_credit_month'];?></td>
								<td><?php echo intval($output['goodsstat_list'][1]['gevalstat_level1num']);?></td>
								<td><?php echo intval($output['goodsstat_list'][1]['gevalstat_level2num']);?></td>
								<td><?php echo intval($output['goodsstat_list'][1]['gevalstat_level3num']);?></td>
								<td><?php echo intval($output['goodsstat_list'][1]['gevalstat_level1num'])+intval($output['goodsstat_list'][1]['gevalstat_level2num'])+intval($output['goodsstat_list'][1]['gevalstat_level3num']);?></td>
							</tr>								
							<tr>
								<td class="td-textl"><?php echo $lang['show_store_credit_six_month'];?></td>
								<td><?php echo intval($output['goodsstat_list'][2]['gevalstat_level1num']);?></td>
								<td><?php echo intval($output['goodsstat_list'][2]['gevalstat_level2num']);?></td>
								<td><?php echo intval($output['goodsstat_list'][2]['gevalstat_level3num']);?></td>
								<td><?php echo intval($output['goodsstat_list'][2]['gevalstat_level1num'])+intval($output['goodsstat_list'][2]['gevalstat_level2num'])+intval($output['goodsstat_list'][2]['gevalstat_level3num']);?></td>
							</tr>								
							<tr>
								<td class="td-textl"><?php echo $lang['show_store_credit_before_six'];?></td>
								<td><?php echo intval($output['goodsstat_list'][3]['gevalstat_level1num']);?></td>
								<td><?php echo intval($output['goodsstat_list'][3]['gevalstat_level2num']);?></td>
								<td><?php echo intval($output['goodsstat_list'][3]['gevalstat_level3num']);?></td>
								<td><?php echo intval($output['goodsstat_list'][3]['gevalstat_level1num'])+intval($output['goodsstat_list'][3]['gevalstat_level2num'])+intval($output['goodsstat_list'][3]['gevalstat_level3num']);?></td>
							</tr>								
							<tr>
								<td class="td-textl"><?php echo $lang['show_store_credit_sum'];?></td>
								<td><?php echo intval($output['goodsstat_list'][4]['gevalstat_level1num']);?></td>
								<td><?php echo intval($output['goodsstat_list'][4]['gevalstat_level2num']);?></td>
								<td><?php echo intval($output['goodsstat_list'][4]['gevalstat_level3num']);?></td>
								<td><?php echo intval($output['goodsstat_list'][4]['gevalstat_level1num'])+intval($output['goodsstat_list'][4]['gevalstat_level2num'])+intval($output['goodsstat_list'][4]['gevalstat_level3num']);?></td>
							</tr>								
						</tbody>
					</table>
				</div>
			</div>
			<div class="shopsappraisesix mb20">
				<h3 class="sellertlefttitle"><?php echo $lang['show_store_credit_storestat_title'];?></h3>
				<div class="shopsappraisesixin">
					<div class="shopsappraisesixinleft fl">
						<ul class="ul-shopsappraisesixinleft">
							<li><?php echo $lang['nc_credit_evalstore_type_1'].$lang['nc_colon'];?><b><?php echo $output['storestat_list'][1]['evalstat_average'];?><?php echo $lang['nc_grade'];?></b></li>
							<li class="li-shopsappraisesixinleft"><?php echo $lang['nc_credit_evalstore_type_2'].$lang['nc_colon'];?><b><?php echo $output['storestat_list'][2]['evalstat_average'];?><?php echo $lang['nc_grade'];?></b></li>
							<li><?php echo $lang['nc_credit_evalstore_type_3'].$lang['nc_colon'];?><b><?php echo $output['storestat_list'][3]['evalstat_average'];?><?php echo $lang['nc_grade'];?></b></li>
						</ul>
					</div>
					<div class="shopsappraisesixinright">					
					<?php foreach ($output['storestat_list'] as $k=>$v){?>
						<ul class="ul-shopsappraisesixinright <?php echo $k == 1?'':'hidden'?>" id="pingfen">
                            <?php foreach ($v as $sonk=>$sonv){?>
              		        <?php if(in_array("$sonk",array('evalstat_onenum_rate','evalstat_twonum_rate','evalstat_threenum_rate','evalstat_fournum_rate','evalstat_fivenum_rate'))){?>	       
							<li>
								<span class="stars"></span><span class="span-grade"><b></b><?php echo $lang['nc_grade'];?></span>
								<span class="span-percentage">
								<?php if ($sonv>0){?>
								<i style="width:<?php echo $sonv;?>px;"></i><b><?php echo $sonv;?>%</b>
								<?php }else { echo $lang['show_store_credit_storeevalnull'];}?>
								</span>
							</li>
							<?php }?>
							<?php }?>						
						</ul>
					<?php }?>	
					</div>
				</div>
			</div>

			<div class="shopsappraisepj">
				<h3 class="sellertlefttitle"><?php echo $lang['show_store_credit_credit'];?></h3>
				<div class="shopsappraisepjin" id="goodseval">
				  <script type="text/javascript">						$("#goodseval").load('index.php?act=show_store&op=comments&id=<?php echo $_GET['id'];?>');				  </script> 
				</div>
			</div>
		</div>
	</div>
</div><!--
<div class="gotop">
	<a class="a-gotop" href="javascript:void(0)"></a>
</div>-->
<script src="<?php echo RESOURCE_PATH;?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script>
<script type="text/javascript">


	$("#goodseval").load('index.php?act=show_store&op=comments&id=<?php echo $_GET['id'];?>');
	$(function(){
		$("#pingfen li").eq(0).find("span").eq(0).addClass("sa5");
		$("#pingfen li").eq(1).find("span").eq(0).addClass("sa4");
		$("#pingfen li").eq(2).find("span").eq(0).addClass("sa3");
		$("#pingfen li").eq(3).find("span").eq(0).addClass("sa2");
		$("#pingfen li").eq(4).find("span").eq(0).addClass("sa1");
		$("#pingfen li").eq(0).find("b").eq(0).html("5");
		$("#pingfen li").eq(1).find("b").eq(0).html("4");
		$("#pingfen li").eq(2).find("b").eq(0).html("3");
		$("#pingfen li").eq(3).find("b").eq(0).html("2");
		$("#pingfen li").eq(4).find("b").eq(0).html("1");
	});

  var agotop=$(".a-gotop");
  agotop.click(function(){
      $('html,body').animate({scrollTop:0},'slow');
    }
  );
	
</script> 
<?php include template('footer');?>


