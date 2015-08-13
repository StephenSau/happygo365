<?php defined('haipinlegou') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_PATH;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="buyercenterright font-mic">

<div class="buyerdoneintop mb30">

  <div class="buyerdongtitle pr">

    <?php include template('member/member_submenu3');?>
     <div class="text-intro"><?php echo $lang['predeposit_pricetype_available'].$lang['nc_colon']; ?><?php echo $output['member_info']['available_predeposit']; ?>&nbsp;<?php echo $lang['currency_zh'];?></div>

  </div>

  <form method="get" action="index.php">

  <div class="buyerdongsearch">

      <input type="hidden" name="act" value="predeposit" />

      <input type="hidden" name="op" value="predepositlog" />

    <ul class="ul-buyerdongsearch">

      <li class="li-ovflow fl">

        <p>时间</p>

        <span><input class="inputtxt7" type="text" id="stime" name="stime" value="<?php echo $_GET['stime'];?>"></span>

        <p>-</p>

        <span><input class="inputtxt7" type="text" id="etime" name="etime" value="<?php echo $_GET['etime'];?>"></span>

      </li>

      <li class="li-ovflow fl">

        <p>描述</p>

        <span><input class="inputtxt6" type="text" id="description" name="description" value="<?php echo $_GET['description'];?>"></span>

      </li>                 

      <li class="fr">

        <p>操作</p>

        <span>

          <select class="select1 font-mic" name="stage">

            <option value="" <?php if (!$_GET['stage']){echo 'selected=selected';}?>><?php echo $lang['nc_please_choose'];?></option>

            <option value="recharge" <?php if ($_GET['stage'] == 'recharge'){echo 'selected=selected';}?>><?php echo $lang['predeposit_log_stage_recharge'];?></option>

            <option value="cash" <?php if ($_GET['stage'] == 'cash'){echo 'selected=selected';}?>><?php echo $lang['predeposit_log_stage_cash'];?></option>

            <option value="order" <?php if ($_GET['stage'] == 'order'){echo 'selected=selected';}?>><?php echo $lang['predeposit_log_stage_order'];?></option>

            <option value="admin" <?php if ($_GET['stage'] == 'admin'){echo 'selected=selected';}?>><?php echo $lang['predeposit_log_stage_artificial'];?></option>

            <option value="system" <?php if ($_GET['stage'] == 'system'){echo 'selected=selected';}?>><?php echo $lang['predeposit_log_stage_system'];?></option>

            <option value="income" <?php if ($_GET['stage'] == 'income'){echo 'selected=selected';}?>><?php echo $lang['predeposit_log_stage_income'];?></option>

          </select>

        </span>

        <span><input class="inputsub4" type="submit" value="<?php echo $lang['nc_search'];?>"></span>

      </li>

    </ul>

  </div>

  </form>

<div class="integraldetail">

  <table class="table-integraldetail">

    <thead>

      <tr>

        <th width="15%"><?php echo $lang['predeposit_price'];?>(<?php echo $lang['currency_zh'];?>)</th>

        <th width="15%"><?php echo $lang['predeposit_addtime']; ?></th>

        <th width="25%"><?php echo $lang['predeposit_log_stage']?></th>

        <th width="45%"><?php echo $lang['predeposit_log_desc'];?></th>

      </tr>

    </thead>

    <tbody>

    <?php  if (count($output['list_log'])>0) { ?>

    <?php foreach($output['list_log'] as $v) { ?>

      <tr>

        <td><span class="span-integraldetailadd"><?php echo $v['pdlog_price'];?></span></td>

        <td><?php echo @date('Y-m-d H:i:s',$v['pdlog_addtime']);?></td>

        <td>

          <?php 

          switch ($v['pdlog_stage']){

                    case 'recharge':

                      echo $lang['predeposit_log_stage_recharge'];

                      break;

                    case 'cash':

                      echo $lang['predeposit_log_stage_cash'];

                      break;

                    case 'order':

                      echo $lang['predeposit_log_stage_order'];

                      break;

                    case 'admin':

                      echo $lang['predeposit_log_stage_artificial'];

                      break;

                    case 'system':

                      echo $lang['predeposit_log_stage_system'];

                      break;

                    case 'income':

                      echo $lang['predeposit_log_stage_income'];

                      break;

              }?>

        </td>

        <td><?php echo $v['pdlog_desc'];?></td>

      </tr>

    <?php }?>

    <?php }else{?>

      <tr>

        <td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>

      </tr>

    <?php }?>

    </tbody>

  </table>

</div>

<?php  if (count($output['list_log'])>0) { ?>

  <div class="store">

      <div class="pagination-store"><?php echo $output['show_page'];?></div>

  </div>

<?php }?>

</div>

</div>

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script> 

<script language="javascript">

$(function(){

	$('#stime').datepicker({dateFormat: 'yy-mm-dd'});

	$('#etime').datepicker({dateFormat: 'yy-mm-dd'});

});

</script>