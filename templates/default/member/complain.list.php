<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="buyercenterright font-mic">
	<div class="buyerdoneintop mb30">
		<div class="buyerdongtitle pr">
			<?php include template('member/member_submenu3');?>
		</div>
		<div class="buyerdongsearch">
			<ul class="ul-buyerdongsearch">
				<!--<li class="li-ovflow fl">
					<p>投诉时间</p>
					<span><input type="text" class="inputtxt7" name="add_time_from" id="add_time_from" value="<?php echo $_GET['add_time_from']; ?>"></span>
					<p>-</p>
					<span><input type="text" class="inputtxt7" name="add_time_to" id="add_time_to" value="<?php echo $_GET['add_time_to']; ?>"></span>
				</li>
				<li class="li-ovflow fl">
					<p>被投诉人</p>
					<span><input type="text" class="inputtxt6" name="order_sn" value="<?php echo $_GET['order_sn']; ?>"></span>
				</li>-->									
				<li class="fr">
					<p>操作</p>
					<form id="list_form" method="get">
						<table>
						  <input type="hidden" id='act' name='act' value='' />
						  <input type="hidden" id='op' name='op' value='' />
						  <tr>
							<span>
								<select  class="select1 font-mic" name="select_complain_state">
									<option value="0" <?php if (empty($_GET['select_complain_state'])){echo 'selected=true';}?>> <?php echo $lang['complain_state_all'];?> </option>
									<option value="1" <?php if ($_GET['select_complain_state'] == '1'){echo 'selected=true';}?>> <?php echo $lang['complain_state_inprogress'];?> </option>
									<option value="2" <?php if ($_GET['select_complain_state'] == '2'){echo 'selected=true';}?>> <?php echo $lang['complain_state_finish'];?> </option>
								  </select>
							  </span>
							<span>
								<input type="submit" class="inputsub4" onclick="submit_search_form()" value="<?php echo $lang['nc_search'];?>" />
							</span>
						  </tr>
						</table>
					</form>
				</li>	
			</ul>
		</div>
	</div>
	<div class="buyerrefund">
		<table class="table-buyerrefund">
			<thead>
				<tr>
					<th width="10%"><?php echo $lang['complain_accuser'];?></th>
					<th width="20%"><?php echo $lang['complain_accused'];?></th>
					<th width="25%"><?php echo $lang['complain_subject_content'];?></th>
					<th width="25%"><?php echo $lang['complain_datetime'];?></th>
					<th width="10%"><?php echo $lang['complain_state'];?></th>
					<th width="10%"><?php echo $lang['nc_handle'];?></th>
				</tr>
			</thead>
			<tbody>
			<?php  if (count($output['port_list'])>0) { ?>
			<?php foreach($output['port_list'] as $val) { ?>
				<tr>
					<td><?php echo $val['accuser_name'];?></td>
					<td><?php echo $val['accused_name'];?></td>
					<td><?php echo $val['complain_subject_content'];?></td>
					<td><?php echo date("Y-m-d H:i:s",$val['complain_datetime']);?></td>
					<td>
						<span  class="span-buyerrefundcg"><?php 
							if(intval($val['complain_state'])===10) echo $lang['complain_state_new']; 
							if(intval($val['complain_state'])===20) echo $lang['complain_state_appeal'];
							if(intval($val['complain_state'])===30) echo $lang['complain_state_talk'];
							if(intval($val['complain_state'])===40) echo $lang['complain_state_handle'];
							if(intval($val['complain_state'])===99) echo $lang['complain_state_finish'];
							?>
						</span>
					</td>
					<td>
						<p>						<?php if (!empty($val['complain_id'])){?>
							<a class="a-buyerrefunddone" href="index.php?act=<?php echo $_GET['act'];?>&op=complain_submit&complain_id=<?php echo $val['complain_id'];?>" target="_blank"><?php echo $lang['complain_text_detail'];?>
							</a>						<?php }else{?>						    所投诉的产品不存在						<?php }?>
						</p>
					  <?php if(intval($val['complain_state'])==10) {?>
					  <p><a href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['complain_cancel_confirm'];?>','index.php?act=member_complain&op=complain_cancel&complain_id=<?php echo $val['complain_id']; ?>')" class="ncu-btn2 mt5"><?php echo $lang['nc_cancel']; ?></a></p>
					  <?php } ?></td>
				</tr>
				<?php }?>
					  <?php } else { ?>
					  <tr>
						<td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>
					  </tr>
				<?php } ?>	
			</tbody>
		</table>
	</div>
	<div class="page">
		<?php  if (count($output['port_list'])>0) { ?>
			<p class="font-mic">
				<?php echo $output['show_page'];?>
			</p>
		<?php } ?>
	</div>
</div>


<script type="text/javascript">
function submit_search_form(){
        $('#act').val('<?php echo $_GET['act'];?>');
        $('#op').val("<?php echo $output['op'];?>");
        $('#list_form').submit();
}
</script> 
