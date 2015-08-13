	<?php defined('haipinlegou') or exit('Access Invalid!');?>



<div class="buyercenterright font-mic">

	<div class="buyerdoneintop mb20">

		<div class="buyerdongtitle pr">

			<ul class="ul-buyerdongtitle">

				<li><a href="" class="a-buyerdongtitle" style="width:135px;">地址列表</a></li>

			</ul>

			<a href="javascript:void(0)" class="a-buynewsite" nc_type="dialog" dialog_title="<?php echo $lang['member_address_new_address'];?>" dialog_id="my_address_add"  uri="index.php?act=member&op=address&type=add" dialog_width="740" title="<?php echo $lang['member_address_new_address'];?>"><?php echo $lang['member_address_new_address'];?></a>

		</div>

	</div>

	<div class="buyrecharge">

		<table class="table-buyrecharge">

			<thead>

				<tr>

					<th width="10%"><?php echo $lang['member_address_receiver_name'];?></th>

					<th width="20%"><?php echo $lang['member_address_location'];?></th>

					<th width="20%"><?php echo $lang['member_address_address'];?></th>

					<th width="10%"><?php echo $lang['member_address_zipcode'];?></th>

					<th width="25%"><?php echo $lang['member_address_phone'];?>/<?php echo $lang['member_address_mobile'];?></th>

					<th width="15%"><?php echo $lang['nc_handle'];?></th>

				</tr>

			</thead>

			<?php if(!empty($output['address_list']) && is_array($output['address_list'])){?>

			<tbody>

			<?php foreach($output['address_list'] as $key=>$address){?>

				<tr>

					<td><?php echo $address['true_name'];?></td>

					<td><?php echo $address['area_info'];?></td>

					<td><?php echo $address['address'];?></td>

					<td><?php echo $address['zip_code'];?></td>

					<td><?php echo $address['tel_phone'];?><br/>

						<?php echo $address['mob_phone']; ?></td>

					<td>

					<p>
						<a class="a-buyrechargedone" href="javascript:void(0);" dialog_id="my_address_edit" dialog_width="740" dialog_title="<?php echo $lang['member_address_edit_address'];?>" nc_type="dialog" uri="index.php?act=member&op=address&type=edit&id=<?php echo $address['address_id'];?>">

						<!--编辑地址

						<?php echo $lang['member_address_edit_address'];?>

						-->

						编 辑

						</a>

						|

						<a href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['nc_ensure_del'];?>', 'index.php?act=member&op=address&id=<?php echo $address['address_id'];?>');" class="a-buyrechargedone">

						<?php echo $lang['nc_del_&nbsp'];?>

						</a>
					</p>
					<p class="setDefault">
						<a href="javascript:;" data-id="<?php echo $address['address_id'];?>"><?php echo $key == 0 ? '默认地址':'设置默认地址';?></a>
					</p>
					</td>

				</tr>

				<?php }?>

				<?php }else{?>				

				<tr>

					<td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>

				</tr>

				<?php }?>

			</tbody>

		</table>

		<!-- <div class="page">

			<p class="font-mic">

				<a href="">上一页</a>

				<a href="" class="a-page">1</a>

				<a href="">2</a>

				<a href="">3</a>

				<a href="">4</a>

				<a href="">5</a>

				<a href="">...</a>

				<a href="">下一页</a>

			</p>

		</div> -->

		<?php if(!empty($output['address_list']) && is_array($output['address_list'])){?>

      <tr>

        <td colspan="20">&nbsp;</td>

      </tr>

      <?php }?>

	</div>

</div>



<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/address.js" charset="utf-8"></script>
