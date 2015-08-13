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
							echo '<li style="width:110px"><a class="'.$classname.'" href="'.$val['menu_url'].'">'.$val['menu_name'].'(<span style="color: red;">'.$output['newcommon'].'</span>)</a></li>';
						}elseif ($val['menu_key'] == 'system'){
							echo '<li style="width:110px"><a class="'.$classname.'" href="'.$val['menu_url'].'">'.$val['menu_name'].'(<span style="color: red;">'.$output['newsystem'].'</span>)</a></li>';
						}elseif ($val['menu_key'] == 'close'){
							echo '<li style="width:110px"><a class="'.$classname.'" href="'.$val['menu_url'].'">'.$val['menu_name'].'(<span style="color: red;">'.$output['newpersonal'].'</span>)</a></li>';
						}else{
							echo '<li style="width:110px"><a class="'.$classname.'" href="'.$val['menu_url'].'">'.$val['menu_name'].'</a></li>';
						}
					}
				}?>
			</ul>
			<?php if ($output['isallowsend']){?>
			<a class="a-buynewsite" href="index.php?act=home&op=sendmsg" class="ncu-btn3" title="<?php echo $lang['home_message_send_message'];?>"><?php echo $lang['home_message_send_message'];?></a>
			<?php }?>
		</div>
		<div class="buyerdongsearch">
			<div class="collectallchoice">
			<?php if (!empty($output['message_array'])) { ?>
			<tr>
				<p><input type="checkbox" id="all" class="checkall"/>
				<?php echo $lang['home_message_select_all'];?></p>
				  <?php if ($output['drop_type'] == 'msg_list'){?>
				  <p>
					  <a href="javascript:void(0)" class="ncu-btn1" uri="index.php?act=home&op=dropcommonmsg&drop_type=<?php echo $output['drop_type']; ?>" name="message_id" confirm="<?php echo $lang['home_message_delete_confirm'];?>?" nc_type="batchbutton"><span><?php echo $lang['home_message_delete'];?></span>
					  </a>
				  </p>
				<?php }?>
				<?php if ($output['drop_type'] == 'msg_system' || $output['drop_type'] == 'msg_seller'){?>
				  <p>
					  <a href="javascript:void(0)" class="ncu-btn1" uri="index.php?act=home&op=dropbatchmsg&drop_type=<?php echo $output['drop_type']; ?>" name="message_id" confirm="<?php echo $lang['home_message_delete_confirm'];?>?" nc_type="batchbutton"><?php echo $lang['home_message_delete'];?>
					  </a>
				  </p>
				<?php }?>
				<?php }?>
			</tr>
			</div>
		</div>
	</div>
	<div class="buyrecharge">
		<div class="buymessshow">
		<?php if(!empty($output['message_list'])) { ?>
		<?php foreach ($output['message_list'] as $k=>$v){?>
			<div class="othermess">
				<a href="javascript:void(0);" class="a-othermess"><!--<img src="img/img24.jpg">--><b><?php echo $v['from_member_name']?></b></a>
				<div class="othermessmain">
					<p><?php echo $v['message_body']?></p>
					<span><?php echo date("Y-m-d H:i",$v['message_time']); ?></span>
				</div>
			</div>
			<!--
			<div class="usermess">
				<a href="javascript:void(0);" class="a-usermess"><img src="img/img24.jpg"><b><?php echo $v['to_member_name']?></b></a>
				<div class="usermessmain">
					<p><?php echo  $output['message_list'][++$k]['message_body'] ?></p>
					<span>回复：<?php echo date("Y-m-d H:i",$v['message_update_time']); ?></span>
				</div>									
			</div>-->
			<?php }} ?>
		</div>
		
	<?php if($_GET['drop_type'] == 'msg_list' && $output['isallowsend']){?>
    <form id="replyform" method="post" action="index.php?act=home&op=savereply">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="message_id" value="<?php echo $output['message_id']; ?>" />
      <div class="buymessshowreturn">
        
          <p>
            <textarea name="msg_content" rows="3"  class="textarea3"></textarea>
          </p>
          <p>
            <input type="submit" class="inputsub3" value="<?php echo $lang['home_message_submit'];?>" />
          </p>
        
      </div>
    </form>
    <script type="text/javascript">
    $(function(){
    	  $('#replyform').validate({
    	        errorPlacement: function(error, element){
    	            $(element).parent().next('p').html(error);
    	        },
    	        rules : {
    	        	msg_content : {
    	                required   : true
    	            }
    	        },
    	        messages : {
    	            msg_content : {
    	                required   : '<?php echo $lang['home_message_reply_content_null'];?>.'
    	            }
    	        }
    	    });
    });
    </script>
    <?php }?>
		
		
		
		
	</div>
</div>
