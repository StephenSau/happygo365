<?php defined('haipinlegou') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_PATH;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="buyercenterright font-mic">
	<div class="buyerdoneintop mb30">
		<div class="buyerdongtitle pr">
			<?php include template('member/member_submenu3');?>
			<p class="p-integraldetail"><?php echo $lang['points_log_pointscount']; ?><span><?php echo $output['member_info']['member_points']; ?></span>分</p>
		</div>
		<form method="get" action="index.php">
		<div class="buyerdongsearch">
		 <input type="hidden" name="act" value="member_points" />
			<ul class="ul-buyerdongsearch">
				<li class="li-ovflow fl">
					<p><?php echo $lang['points_addtime'].$lang['nc_colon']; ?></p>
					<span><input type="text" class="inputtxt7" id="stime" name="stime" value="<?php echo $_GET['stime'];?>"></span>
					<p>-</p>
					<span><input type="text" class="inputtxt7" id="etime" name="etime" value="<?php echo $_GET['etime'];?>"></span>
				</li>
				<li class="li-ovflow fl">
					<p><?php echo $lang['points_pointsdesc'].$lang['nc_colon']; ?></p>
					<span><input type="text" class="inputtxt6" id="description" name="description" value="<?php echo $_GET['description'];?>"></span>
				</li>									
				<li class="fr">
					<p><?php echo $lang['points_stage'].$lang['nc_colon']; ?></p>
						<span><select class="select1 font-mic" name="stage">
							<option value="" <?php if (!$_GET['stage']){echo 'selected=selected';}?>><?php echo $lang['nc_please_choose'];?></option>
							<option value="regist" <?php if ($_GET['stage'] == 'regist'){echo 'selected=selected';}?>><?php echo $lang['points_stage_regist']; ?></option>
							<option value="login" <?php if ($_GET['stage'] == 'login'){echo 'selected=selected';}?>><?php echo $lang['points_stage_login']; ?></option>
							<option value="comments" <?php if ($_GET['stage'] == 'comments'){echo 'selected=selected';}?>><?php echo $lang['points_stage_comments']; ?></option>
							<option value="order" <?php if ($_GET['stage'] == 'order'){echo 'selected=selected';}?>><?php echo $lang['points_stage_order']; ?></option>
							<option value="system" <?php if ($_GET['stage'] == 'system'){echo 'selected=selected';}?>><?php echo $lang['points_stage_system']; ?></option>
							<option value="pointorder" <?php if ($_GET['stage'] == 'pointorder'){echo 'selected=selected';}?>><?php echo $lang['points_stage_pointorder']; ?></option>
							<option value="app" <?php if ($_GET['stage'] == 'app'){echo 'selected=selected';}?>><?php echo $lang['points_stage_app']; ?></option>
						</select></span>
					<span><input type="submit" value="<?php echo $lang['nc_search'];?>" class="inputsub4"></span>
				</li>
			</ul>
		</div>
		</form>
	</div>
	<div class="integraldetail">
		<table class="table-integraldetail">
			<thead>
				<tr>
					<th width="20%"><?php echo $lang['points_addtime']; ?></th>
					<th width="15%"><?php echo $lang['points_pointsnum']; ?></th>
					<th width="25%"><?php echo $lang['points_stage']; ?></th>
					<th width="35%"><?php echo $lang['points_pointsdesc']; ?></th>
				</tr>
			</thead>
			<tbody>
				<?php  if (count($output['list_log'])>0) { ?>
				<?php foreach($output['list_log'] as $val) { ?>
				<tr>
					<td><?php echo @date('Y-m-d',$val['pl_addtime']);?></td>
					<td><span class="span-integraldetailadd"><?php echo ($val['pl_points'] > 0 ? '+' : '').$val['pl_points']; ?></span></td>
					<td><?php 
	              	switch ($val['pl_stage']){
	              		case 'regist':
	              			echo $lang['points_stage_regist'];
	              			break;
	              		case 'login':
	              			echo $lang['points_stage_login'];
	              			break;
	              		case 'comments':
	              			echo $lang['points_stage_comments'];
	              			break;
	              		case 'order':
	              			echo $lang['points_stage_order'];
	              			break;
	              		case 'system':
	              			echo $lang['points_stage_system'];
	              			break;
	              		case 'pointorder':
	              			echo $lang['points_stage_pointorder'];
	              			break;
	              		case 'app':
	              			echo $lang['points_stage_app'];
	              			break;
	              	}
	              ?>
				  </td>
					<td><?php echo $val['pl_desc'];?></td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr>
					<td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record']; ?></span></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<?php  if (count($output['list_log'])>0) { ?>
     
       <div class="pagination"><?php echo $output['show_page']; ?></div>
     
      <?php } ?>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script> 
<script language="javascript">
$(function(){
	$('#stime').datepicker({dateFormat: 'yy-mm-dd'});
	$('#etime').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>