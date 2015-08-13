<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="buyercenterright font-mic">
	
	<div class="buyerdoneintop mb20">
		<div class="buyerdongtitle pr">
			<ul class="ul-buyerdongtitle">
				  <?php if(is_array($output['member_menu']) and !empty($output['member_menu'])) { 
					foreach ($output['member_menu'] as $key => $val) {
						$classname = 'normal';
						if($val['menu_key'] == $output['menu_key']) {
							$classname = 'a-buyerdongtitle';
						}
						if ($val['menu_key'] == 'message'){
							echo '<li style="width:120px;"><a class="'.$classname.'" href="'.$val['menu_url'].'">'.$val['menu_name'].'(<span style="color: red;">'.$output['newcommon'].'</span>)</a></li>';
						}elseif ($val['menu_key'] == 'system'){
							echo '<li style="width:120px;"><a class="'.$classname.'" href="'.$val['menu_url'].'">'.$val['menu_name'].'(<span style="color: red;">'.$output['newsystem'].'</span>)</a></li>';
						}elseif ($val['menu_key'] == 'close'){
							echo '<li style="width:120px;"><a class="'.$classname.'" href="'.$val['menu_url'].'">'.$val['menu_name'].'(<span style="color: red;">'.$output['newpersonal'].'</span>)</a></li>';
						}else{
							echo '<li style="width:120px;"><a class="'.$classname.'" href="'.$val['menu_url'].'">'.$val['menu_name'].'</a></li>';
						}
					}
				}?>
			</ul>
			<?php if ($output['isallowsend']){?>
			<a class="a-buynewsite" href="index.php?act=home&op=sendmsg" class="ncu-btn3" title="<?php echo $lang['home_message_send_message'];?>"><?php echo $lang['home_message_send_message'];?></a>
			<?php }?>
		</div>
		<div class="buyerdongsearch">
		   <?php if (!empty($output['message_array'])) { ?>
			<div class="collectallchoice">
				<p><input type="checkbox" id="all" class="checkall">全选</p>

				<p>
				  <?php if ($output['drop_type'] == 'msg_list'){?>
		          <a href="javascript:void(0)" class="ncu-btn1" uri="index.php?act=home&op=dropcommonmsg&drop_type=<?php echo $output['drop_type']; ?>" name="message_id" confirm="<?php echo $lang['home_message_delete_confirm'];?>?" nc_type="batchbutton"><span><?php echo $lang['home_message_delete'];?></span></a>
		          <?php }?>
		          <?php if ($output['drop_type'] == 'msg_system' || $output['drop_type'] == 'msg_seller'){?>
		          <a href="javascript:void(0)" class="ncu-btn1" uri="index.php?act=home&op=dropbatchmsg&drop_type=<?php echo $output['drop_type']; ?>" name="message_id" confirm="<?php echo $lang['home_message_delete_confirm'];?>?" nc_type="batchbutton"><span><?php echo $lang['home_message_delete'];?></span></a>
		          <?php }?>
				</p>
			</div>
		   <?php }?>
		</div>
	</div>

	<div class="buyrecharge">
		<table class="table-buyrecharge">
			<thead>
				<tr>
					<th width="5%"> </th>
					<th width="10%"><?php 
						if ($output['drop_type'] == 'msg_seller'){
							echo $lang['home_message_storename'];
						}else {
							echo $lang['home_message_sender'];
						}?>
					</th>
					<th width="50%"><?php echo $lang['home_message_content'];?></th>
					<th width="20%"><?php echo $lang['home_message_last_update'];?></th>
					<th width="15%"><?php echo $lang['home_message_command'];?></th>
				</tr>
			</thead>
			<tbody>
			<?php if (!empty($output['message_array'])) { ?>
			<?php foreach($output['message_array'] as $k => $v){ ?>
				<tr>
					<td><input type="checkbox" value="<?php echo $v['message_id']; ?>"></td>
					<td>
						<?php if ($output['drop_type'] == 'msg_seller'){?>
							  <a href="index.php?act=show_store&id=<?php echo $v['store_id'];?>" target="_blank"><?php echo $v['from_member_name']; ?>
							  </a>
						<?php }else { echo $v['from_member_name']; }?>
					</td>
					<td class="a-buyrechargedone <?php if($v['message_open'] == 0){?> font_bold<?php }?> tl"><?php echo parsesmiles($v['message_body']); ?></td>
					<td><?php echo @date("Y-m-d H:i:s",$v['message_update_time']); ?></td>
					<td><?php if ($output['drop_type'] == 'msg_list'){?>
							<a class="a-buyrechargedone" href="index.php?act=home&op=showmsgcommon&drop_type=<?php echo $output['drop_type']; ?>&message_id=<?php echo $v['message_id']; ?><?php if($v['message_parent_id']>0) echo '#'.$v['message_id']; ?>">查看</a> 
							| 
							<a class="a-buyrechargedone" href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['home_message_delete_confirm'];?>?', 'index.php?act=home&op=dropcommonmsg&drop_type=<?php echo $output['drop_type']; ?>&message_id=<?php echo $v['message_id']; ?>');" class="ncu-btn2 mt5"><?php echo $lang['home_message_delete'];?></a>
							<?php }?>
							<?php if ($output['drop_type'] == 'msg_system' || $output['drop_type'] == 'msg_seller'){?>
							<a href="index.php?act=home&op=showmsgbatch&drop_type=<?php echo $output['drop_type']; ?>&message_id=<?php echo $v['message_id']; ?><?php if($v['message_parent_id']>0) echo '#'.$v['message_id']; ?>"><?php echo $lang['home_message_view_detail'];?></a> <a href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['home_message_delete_confirm'];?>?', 'index.php?act=home&op=dropbatchmsg&drop_type=<?php echo $output['drop_type']; ?>&message_id=<?php echo $v['message_id']; ?>');" class="ncu-btn2 mt5"><?php echo $lang['home_message_delete'];?></a>
							<?php }?>
					</td>
				</tr>
				<?php } ?>
				<?php } else { ?>
					<tr>
						<td colspan="19" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>
					</tr>
				<?php } ?>				
			</tbody>
		</table>
		<?php if (!empty($output['message_array'])) { ?>
			<div class="pagination"><?php echo $output['show_page']; ?></div>
			<?php } ?>
		</div>

	</div>