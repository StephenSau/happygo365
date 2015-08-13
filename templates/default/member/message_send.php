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
				echo '<li style="width:110px"><a  class="'.$classname.'" href="'.$val['menu_url'].'">'.$val['menu_name'].'(<span style="color: red;">'.$output['newcommon'].'</span>)</a></li>';
				}elseif ($val['menu_key'] == 'system'){
				echo '<li style="width:110px"><a  class="'.$classname.'" href="'.$val['menu_url'].'">'.$val['menu_name'].'(<span style="color: red;">'.$output['newsystem'].'</span>)</a></li>';
				}elseif ($val['menu_key'] == 'close'){
				echo '<li style="width:110px"><a  class="'.$classname.'" href="'.$val['menu_url'].'">'.$val['menu_name'].'(<span style="color: red;">'.$output['newpersonal'].'</span>)</a></li>';
				}else{
				echo '<li style="width:110px"><a  class="'.$classname.'" href="'.$val['menu_url'].'">'.$val['menu_name'].'</a></li>';
				}
				}
				}?>
			</ul>
		</div>
	</div>
	<div class="buychangebox">
		<div class="snetmessfriend">
		<?php if ($output['member_name'] == ''){?>
			<div class="friendlist">
				<?php if(intval(count($output['friend_list']))>0){?>
				<h3><?php echo $lang['home_message_friend'];?>(<?php echo intval(count($output['friend_list'])); ?>)</h3>
				<div class="friendlistbox">
					<ul class="ul-friendlist">
						<?php if ($output['friend_list'] != '') { ?>
						<?php foreach ($output['friend_list'] as $val) { ?>
						<li><a class="a-friendlist" href="javascript:void(0);" id="<?php echo $val['friend_tomname']; ?>" nc_type="to_member_name"><img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/img/img29.jpg">
						<?php echo $val['friend_tomname']; ?></a></li>
						<?php } ?>
						<?php } else { ?>
						<li>&nbsp;<?php echo $lang['home_message_no_friends'];?></li>
						<?php } ?>
					</ul>
				</div>
				<?php }?>
			</div>
			<?php }?>
			<div class="snetmessfriendin">
				<form method="post" id="send_form" action="index.php?act=home&op=savemsg">
				<input type="hidden" name="form_submit" value="ok" />
				<table class="table-snetmessfriendin">
					<tbody>
						<tr>
							<th><?php echo $lang['home_message_reveiver'].$lang['nc_colon'];?></th>
							<td>
								<input type="text" class="inputtxt11 font-mic" name="to_member_name" value="<?php echo $output['member_name']; ?>" <?php if (!empty($output['member_name'])){echo "readonly";}?>/>
								<p class="p-topup"><?php echo $lang['home_message_separate'];?></p>
							</td>
						</tr>
						<tr>
							<th> </th>
							<td>
								<label class="label1"><input type="radio"value="2" name="msg_type" checked="checked" ><?php echo $lang['home_message_open'];?></label>
								<label class="label1"><input type="radio" name="msg_type" value="0"><?php echo $lang['home_message_close'];?></label>
							</td>
						</tr>
						<tr>
							<th><?php echo $lang['home_message_content'].$lang['nc_colon'];?></th>
							<td><textarea class="textarea4" name="msg_content" rows="3" ></textarea></td>
						</tr>
						<tr>
							<th> </th>
							<td><input type="submit" value="<?php echo $lang['home_message_ensure_send'];?>" class="inputsub5"></td>
						</tr>
					</tbody>
				</table>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(function(){
    $('a[nc_type="to_member_name"]').click(function (){
        var str = $('input[name="to_member_name"]').val();
        var id = $(this).attr('id');
        if(str.indexOf(id+',') < 0){
            doFriend(id+',', 'add');
        }else{
            doFriend(id, 'delete');
        }
    });
});
$(function(){
  $('#send_form').validate({
        errorPlacement: function(error, element){
            $(element).next('p').html(error);
        },
        submitHandler:function(form){
            ajaxpost('send_form', '', '', 'onerror') 
        },   
        rules : {
            to_member_name : {
                required   : true
            },
            msg_content : {
                required   : false
            }
        },
        messages : {
            to_member_name : {
                required : '<?php echo $lang['home_message_receiver_null'];?>.'
            },
            msg_content : {
                required   : '<?php echo $lang['home_message_content_null'];?>.'
            }
        }
    });
});
function doFriend(user_name, action){
    var input_name = $("input[name='to_member_name']").val();
    var key, i = 0;
    var exist = false;
    var arrOld = new Array();
    var arrNew = new Array();
    input_name = input_name.replace(/\uff0c/g,',');
    arrOld     = input_name.split(',');
    for(key in arrOld){
        arrOld[key] = $.trim(arrOld[key]);
        if(arrOld[key].length > 0){
            arrOld[key] == user_name &&  action == 'delete' ? null : arrNew[i++] = arrOld[key]; 
            arrOld[key] == user_name ? exist = true : null;
        }
    }
    if(action == 'delete' && arrNew !=''){
    	arrNew = arrNew+',';
    }
    if(!exist && action == 'add'){
        arrNew[i] = user_name;
    }
    $("input[name='to_member_name']").val(arrNew);
}
</script> 
