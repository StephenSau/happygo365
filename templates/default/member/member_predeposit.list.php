<?php defined('haipinlegou') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_PATH;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="buyercenterright font-mic">
  <div class="buyerdoneintop mb30">
    
    <div class="buyerdongtitle pr">
     <?php include template('member/member_submenu3');?>
    </div>

    <form method="get" action="index.php">
    <div class="buyerdongsearch">
      <ul class="ul-buyerdongsearch">

      <input type="hidden" name="act" value="predeposit" />
      <input type="hidden" name="op" value="rechargelist" />

        <li class="li-ovflow fl">
          <p>编号</p>
          <span><input class="inputtxt6" type="text" name="sn_search" value="<?php echo $_GET['sn_search'];?>"></span>
        </li>                 
        <li class="fr">
          <p>支付方式</p>
            <span>
              <select class="select1 font-mic" name="payment_search" id="payment_search">
              <option value=""><?php echo $lang['nc_please_choose'];?></option>
              <?php if (is_array($output['payment_array']) && count($output['payment_array'])>0){?>
              <?php foreach ($output['payment_array'] as $k=>$v){?>
              <option value="<?php echo $k;?>" <?php if($_GET['payment_search'] == $k) { ?>selected="selected"<?php } ?> title="<?php echo $v['payment_info'];?>"><?php echo $v['payment_name'];?></option>
              <?php }?>
              <?php }?>
              </select>
            </span>
          <span><input class="inputsub4" type="submit" value="<?php echo $lang['nc_search'];?>"></span>
        </li>
      </ul>
    </div>
    </form>

  </div>
  <div class="buyrecharge">
    <table class="table-buyrecharge">
      <thead>
        <tr>
          <th width="20%"><?php echo $lang['predeposit_rechargesn']; ?></th>
          <th width="20%"><?php echo $lang['predeposit_addtime']; ?></th>
          <th width="15%"><?php echo $lang['predeposit_payment']; ?></th>
          <th width="20%"><?php echo $lang['predeposit_recharge_price']; ?>(<?php echo $lang['currency_zh'];?>)</th>
          <th width="10%"><?php echo $lang['predeposit_paystate']; ?></th>
          <th width="15%"><?php echo $lang['nc_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($output['recharge_list'])>0) { $n = 0; ?>
        <?php foreach($output['recharge_list'] as $val) { ?>
        <tr>
          <td><?php echo $val['pdr_sn'];?></td>
          <td><?php echo @date('Y-m-d',$val['pdr_addtime']);?></td>
          <td><?php echo $output['payment_array'][$val['pdr_payment']]['payment_name'];?></td>
          <td><?php echo $val['pdr_price'];?></td>
          <td><?php echo $output['rechargepaystate'][$val['pdr_paystate']]; ?></td>
          <td>
            <a id="check<?php echo ++$n;?>" class="a-buyrechargedone" href="javascript:void(0)">查看</a>|
           <!--弹出框begin-->
            <div class="popup" style="display:none;">
              <div class="popupblack"></div>
              <div class="popupin font-mic">
                <a class="a-popupclose" href="javascript:void(0);" onclick="close(this);"></a>
                <div class="refundpup">
                  <dl style="font-size:16px;text-indent:3em">
                    <dt><?php echo $lang['predeposit_rechargesn'].$lang['nc_colon'];?></dt>
                    <dd style="float:left;margin-left:-30px;"><?php echo $val['pdr_sn']; ?></dd>
                  </dl>
                  <dl style="font-size:16px;text-indent:1em">
                    <dt><?php echo $lang['predeposit_payment'].$lang['nc_colon'];?></dt>
                    <dd style="float:left;"><?php echo $output['payment_array'][$val['pdr_payment']]['payment_name']; ?></dd>
                  </dl>
                  <dl style="font-size:16px;text-indent:1em">
                    <dt><?php echo $lang['predeposit_recharge_price'].$lang['nc_colon'];?></dt>
                    <dd style="float:left;"><?php echo $val['pdr_price']; ?><?php echo $lang['currency_zh']; ?></dd>
                  </dl>
                  <?php if ($val['pdr_payment'] == 'offline'){?>
                  <dl style="font-size:16px;">
                    <dt><?php echo $lang['predeposit_recharge_huikuanname'].$lang['nc_colon'];?></dt>
                    <dd style="float:left;margin-left:16px;"><?php echo $val['pdr_remittancename']; ?></dd>
                  </dl >
                  <dl style="font-size:16px;text-indent:1em">
                    <dt><?php echo $lang['predeposit_recharge_huikuanbank'].$lang['nc_colon'];?></dt>
                    <dd style="float:left;"><?php echo $val['pdr_remittancebank']; ?></dd>
                  </dl>
                  <dl style="font-size:16px;text-indent:1em">
                    <dt><?php echo $lang['predeposit_recharge_huikuandate'].$lang['nc_colon'];?></dt>
                    <dd style="float:left;"><?php echo @date('Y-m-d',$val['pdr_remittancedate']); ?></dd>
                  </dl>
                  <?php }?>
                  <dl style="font-size:16px;text-indent:1em">
                    <dt><?php echo $lang['predeposit_addtime'].$lang['nc_colon'];?></dt>
                    <dd style="float:left;"><?php echo @date('Y-m-d',$val['pdr_addtime']); ?></dd>
                  </dl>
                  <dl style="font-size:16px;text-indent:1em">
                    <dt><?php echo $lang['predeposit_memberremark'].$lang['nc_colon'];?></dt>
                    <dd style="float:left;"><?php echo $val['pdr_memberremark']; ?></dd>
                  </dl>
                  <dl style="font-size:16px;text-indent:1em">
                    <dt><?php echo $lang['predeposit_paystate'].$lang['nc_colon'];?></dt>
                    <dd style="float:left;"><?php echo $output['rechargepaystate'][$val['pdr_paystate']]; ?></dd>
                  </dl>
                </div>
              </div>
            </div>
            <!--弹出框end-->
            <?php if ($val['pdr_paystate'] == 0){?>
            <a class="a-buyrechargedone" href="javascript:drop_confirm('<?php echo $lang['nc_ensure_del'];?>', 'index.php?act=predeposit&op=rechargedel&id=<?php echo $val['pdr_id']; ?>');">删除</a>
            <a class="a-buyrecharge" uri="index.php?act=predeposit&op=rechargepay&id=<?php echo $val['pdr_id'];?>" dialog_id="rechargepay_div" dialog_title="<?php echo $lang['predeposit_recharge_pay']; ?>" dialog_width="480" nc_type="dialog" href="javascript:void(0)">
              <?php if ($val['pdr_payment'] == 'offline') { ?>
              <?php echo $lang['predeposit_recharge_pay_offline'];?>
              <?php } else { ?>
              <?php echo $lang['predeposit_recharge_pay']; ?>
              <?php } ?>
            </a>
            <?php }?>
          </td>
        </tr>
        <?php }?>
        <?php }else{?>
          <tr>
            <td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>
          </tr>
        <?php } ?> 
      </tbody>
    </table>
	<?php  if (count($output['cash_list'])>0) { ?>
    <div class="store">
        <div class="pagination-store"><?php echo $output['show_page'];?></div>
    </div>
	<?php } ?>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script> 
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<script>

$("a[id^='check']").click(function()
{
    $(this).next('.popup').show();
})
$(document).ready(function(){
  $(".popup").click(function(){
  $(this).hide();
  });
});

</script>