<?php defined('haipinlegou') or exit('Access Invalid!');?>
<div class="wrap">
  <div class="tabmenu"><?php include template('member/member_submenu');?></div>
  <div class="ncu-form-style">
    <dl>
      <dt><?php echo $lang['store_ztc_applytype'].$lang['nc_colon']; ?></dt>
      <dd>
        <?php if ($output['ztc_info']['ztc_type'] == 1){
                        		echo $lang['store_ztc_add_applytype_recharge'];
                        	}else {
                        		echo $lang['store_ztc_add_applytype_new'];
                        	}?>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['store_ztc_add_choose_goods'].$lang['nc_colon'];?></dt>
      <dd><?php echo $output['ztc_info']['ztc_goodsname'];?></dd>
      </p>
    </dl>
    <dl>
      <dt><?php echo $lang['store_ztc_add_usegold'].$lang['nc_colon']; ?></dt>
      <dd><?php echo $output['ztc_info']['ztc_gold'];?> <?php echo $lang['store_ztc_goldunit']; ?></dd>
    </dl>
    <dl id="starttime_div">
      <dt><?php echo $lang['store_ztc_starttime'].$lang['nc_colon']; ?></dt>
      <dd>
        <?php if ($output['ztc_info']['ztc_startdate']){echo date('Y-m-d',$output['ztc_info']['ztc_startdate']);}?>
      </dd>
      </p>
    </dl>
    <dl>
      <dt><?php echo $lang['store_ztc_add_remark'].$lang['nc_colon']; ?></dt>
      <dd><?php echo $output['ztc_info']['ztc_remark'];?></dd>
    </dl>
    <dl id="starttime_div">
      <dt><?php echo $lang['store_ztc_paystate'].$lang['nc_colon']; ?></dt>
      <dd>
        <?php 
                        	switch ($output['ztc_info']['ztc_paystate']){
                        		case 1:
                        			echo $lang['store_ztc_paysuccess'];
                        			break;
                        		default:
                        			echo $lang['store_ztc_waitpaying'];
                        			break;	
                        	}
                        ?>
      </dd>
    </dl>
    <dl id="starttime_div">
      <dt><?php echo $lang['store_ztc_auditstate'].$lang['nc_colon']; ?></dt>
      <dd>
        <?php 
                        	switch ($output['ztc_info']['ztc_state']){
                        		case 1:
                        			echo $lang['store_ztc_auditpass'];
                        			break;
                        		case 2:
                        			echo $lang['store_ztc_auditnopass'];
                        			break;
                        		default:
                        			echo $lang['store_ztc_auditing'];
                        			break;	
                        	}
                        ?>
      </dd>
    </dl>
    <dl>
      <dt>&nbsp;</dt>
      <dd>
        <input id="submit_group" type="submit"  class="submit" value="<?php echo $lang['store_ztc_index_backlist']; ?>" onclick="window.location='index.php?act=store_ztc&op=ztc_list'"/>
      </dd>
    </dl>
  </div>
</div>
<script>
function ztctype_change(){
	if(<?php echo $output['ztc_info']['ztc_type']; ?> == 1){
		$("#starttime_div").hide();		
	}else{
		$("#starttime_div").show();
	}
}
$(function(){
	ztctype_change();	
}); 
</script>