<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="buyercenterright font-mic">
  <div class="buyerdongtitle pr" style="border:1px solid #e6e6e6">
    <?php include template('member/member_submenu3');?>
  </div>
  <div class="ncu-form-style" style="margin-top:20px;border:1px solid #e6e6e6">
    <dl style="margin-top:20px;margin-left:10px;font-size:16px;">
      <dt style="float:left;text-indent:3em"><?php echo $lang['predeposit_cashsn'].$lang['nc_colon']; ?></dt>
      <dd style="text-indent:3em"><?php echo $output['info']['pdcash_sn']; ?></dd>
    </dl>
    <dl style="margin-top:20px;margin-left:10px;font-size:16px;">
      <dt style="float:left;text-indent:1em"><?php echo $lang['predeposit_payment'].$lang['nc_colon']; ?></dt>
      <dd style="text-indent:3em"><?php echo $output['payment_info']['payment_name']; ?></dd>
    </dl>
    <dl style="margin-top:20px;margin-left:10px;font-size:16px;">
      <dt style="float:left;text-indent:1em"><?php echo $lang['predeposit_cash_price'].$lang['nc_colon']; ?></dt>
      <dd style="text-indent:3em"><?php echo $output['info']['pdcash_price']; ?><?php echo $lang['currency_zh']; ?></dd>
    </dl>
    <?php if ($output['info']['pdcash_payment'] == 'offline'){?>
    <dl style="margin-top:20px;margin-left:10px;font-size:16px;">
      <dt style="float:left;"><?php echo $lang['predeposit_cash_shoukuanname'].$lang['nc_colon'];?></dt>
      <dd style="text-indent:3em"><?php echo $output['info']['pdcash_toname']; ?></dd>
    </dl>
    <dl style="margin-top:20px;margin-left:10px;font-size:16px;">
      <dt style="float:left;text-indent:1em"><?php echo $lang['predeposit_cash_shoukuanbank'].$lang['nc_colon']; ?></dt>
      <dd style="text-indent:3em"><?php echo $output['info']['pdcash_tobank']; ?></dd>
    </dl>
    <?php }?>
    <dl style="margin-top:20px;margin-left:10px;font-size:16px;">
      <dt style="float:left;text-indent:1em"><?php echo $lang['predeposit_cash_shoukuanaccount'].$lang['nc_colon'];?></dt>
      <dd style="text-indent:3em"><?php echo $output['info']['pdcash_paymentaccount']; ?></dd>
    </dl>
    <dl style="margin-top:20px;margin-left:10px;font-size:16px;">
      <dt style="float:left;text-indent:1em"><?php echo $lang['predeposit_addtime'].$lang['nc_colon'];?></dt>
      <dd style="text-indent:3em"><?php echo @date('Y-m-d',$output['info']['pdcash_addtime']); ?></dd>
    </dl>
    <dl style="margin-top:20px;margin-left:10px;font-size:16px;">
      <dt style="float:left;text-indent:1em"><?php echo $lang['predeposit_memberremark'].$lang['nc_colon'];?></dt>
      <dd style="text-indent:3em"><?php echo $output['info']['pdcash_memberremark']; ?></dd>
    </dl>
    <dl style="margin-top:20px;margin-left:10px;font-size:16px;">
      <dt style="float:left;text-indent:1em"><?php echo $lang['predeposit_paystate'].$lang['nc_colon'];?></dt>
      <dd style="text-indent:3em"><?php echo $output['cashpaystate'][$output['info']['pdcash_paystate']]; ?></dd>
    </dl>
    <?php if ($output['info']['pdcash_remark'] != ''){?>
    <dl style="margin-top:20px;margin-left:10px;font-size:16px;">
      <dt style="float:left;text-indent:1em"><?php echo $lang['predeposit_adminremark'].$lang['nc_colon']; ?></dt>
      <dd style="text-indent:3em"><?php echo $output['info']['pdcash_remark']; ?></dd>
    </dl>
    <?php }?>
    <dl class="bottom" style="margin-top:20px;margin-left:10px;font-size:16px;">
      <dt>&nbsp;</dt>
      <dd style="margin-left:200px;">
        <input type="submit" class="submit" value="<?php echo $lang['predeposit_backlist']; ?>" onclick="window.location='index.php?act=predeposit&op=cashlist'" style="height:30px;width:100px;font-size:15px;font-weight:bold;margin-bottom:10px;"/>
      </dd>
    </dl>
  </div>
</div>
