<?php defined('haipinlegou') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_PATH;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<style type="text/css">

.store-name {

  width: 130px;

  display: inline-block;

  overflow: hidden;

  white-space: nowrap;

  text-overflow: ellipsis;

}

</style>


<div class="buyercenterright font-mic">
  <div class="memberInfo">
      <p class="title">您好<span><a href="index.php?act=home&op=member" title="<?php echo $lang['nc_edituserinfo'];?>"><?php echo $output['member_info']['member_name'];?></a>,</span>欢迎进入会员中心！</p>
      <ul>
        <li>您目前是：<span class="s">[注册会员]</span><span>您有<em><?php echo $output['vouchertotal']?></em>张优惠券未使用，<strong><a href="index.php?act=member_voucher" target="_blank">点击查看</a></strong></span></li>
        <li>账户总积分：<span><strong><em class="s"><?php echo $output['member_info']['member_points'];?></em>分</span><a href="index.php?act=member_points">查看积分记录>></strong></a></li>
        <li>预存款余额：￥<i class="s"><?php echo $output['member_info']['available_predeposit']; ?></i><strong><a href="index.php?act=predeposit">预存款充值>></a></strong></li>
        <li class="ercode">
          <a href="javascript:;" data-flag>邀请链接(二维码)</a>
          <div class="shareBox" data-flag>
            <div class="shareTitle" data-flag>分享到:<span>x</span></div>
            <div class="bdsharebuttonbox memberShare " data-flag>
              <a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间" data-flag></a>
              <a data-flag href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
              <a data-flag href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a>
              <a data-flag href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a>
              <a data-flag href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a>
              <a data-flag href="#" class="bds_more" data-cmd="more"></a>
            </div>
          </div>
        </li>
      </ul>
      <p class="transaction">交易信息：
      <!-- <a href="javascript:;" target="_blank">待付款订单（<span>1<span>）</a>
      <a href="javascript:;" target="_blank">待付款订单（<span>1<span>）</a>
      <a href="javascript:;" target="_blank">待付款订单（<span>1<span>）</a> -->
        <a href="index.php?act=member&op=order&state_type=order_pay"><?php echo $lang['nc_order_waitpay'];?><span <?php if($output['member_info']['order_nopay'] > 0){ echo "class='yes'";}else{ echo "class='no'";}?>>(<?php echo $output['member_info']['order_nopay'];?>)</span></a>

        <a href="index.php?act=member&op=order&state_type=order_shipping"><?php echo $lang['nc_order_receiving'];?><span <?php if($output['member_info']['order_noreceiving'] > 0){ echo "class='yes'";}else{ echo "class='no'";}?>>(<?php echo $output['member_info']['order_noreceiving'];?>)</span></a>

        <a href="index.php?act=member&op=order&state_type=noeval"><?php echo $lang['nc_order_waitevaluate'];?><span <?php if($output['member_info']['order_noeval'] > 0){ echo "class='yes'";}else{ echo "class='no'";}?>>(<?php echo $output['member_info']['order_noeval'];?>)</span></a>
      </p>
    </div>
  <div class="buyerdoneintop mb30">
    <div class="buyerdongtitle pr">

      
        
         <?php include template('member/member_submenu3');?>

      

    </div>

    <form method="get" action="index.php" target="_self">

    <input type="hidden" name="act" value="member" />

    <input type="hidden" name="op" value="order" />

    <div class="buyerdongsearch">

      <ul class="ul-buyerdongsearch">

        <li class="li-ovflow fl">

          <p><?php echo $lang['member_order_time'].$lang['nc_colon'];?></p>

          <span><input type="text" class="inputtxt7" name="add_time_from" id="add_time_from" value="<?php echo $_GET['add_time_from']; ?>"></span>

          <p>-</p>

          <span><input type="text" class="inputtxt7" name="add_time_to" id="add_time_to" value="<?php echo $_GET['add_time_to']; ?>"></span>

        </li>

        <li class="li-ovflow fl">

          <p><?php echo $lang['member_order_sn'].$lang['nc_colon'];?></p>

          <span><input type="text" class="inputtxt6" name="order_sn" value="<?php echo $_GET['order_sn']; ?>"></span>

        </li>                 

        <li class="fr">

          <p>订单状态</p>

          <span><select class="select1 font-mic" name="state_type">

          <option value="all" <?php echo $_GET['state_type']=='all'?'selected':''; ?>><?php echo$lang['member_order_all'];?></option>

          <option value="order_pay" <?php echo $_GET['state_type']=='order_pay'?'selected':''; ?>><?php echo $lang['member_order_wait_pay'];?></option>

          <option value="order_pay_confirm" <?php echo $_GET['state_type']=='order_pay_confirm'?'selected':''; ?>><?php echo $lang['member_order_wait_confirm'];?></option>

          <option value="order_no_shipping" <?php echo $_GET['state_type']=='order_no_shipping'?'selected':''; ?>><?php echo $lang['member_order_wait_ship'];?></option>

          <option value="order_shipping" <?php echo $_GET['state_type']=='order_shipping'?'selected':''; ?>><?php echo $lang['member_order_shipped'];?></option>

          <option value="order_finish" <?php echo $_GET['state_type']=='order_finish'?'selected':''; ?>><?php echo $lang['member_order_finished'];?></option>

          <option value="order_cancal" <?php echo $_GET['state_type']=='order_cancal'?'selected':''; ?>><?php echo $lang['member_order_canceled'];?></option>

          <option value="order_refer" <?php echo $_GET['state_type']=='order_refer'?'selected':''; ?>><?php echo $lang['member_order_refer']; ?></option>

          <option value="order_confirm" <?php echo $_GET['state_type']=='order_confirm'?'selected':''; ?>><?php echo $lang['member_order_confirm']; ?></option>

          </select></span>

          <span><input type="submit" value="<?php echo $lang['member_order_search'];?>" class="inputsub4"></span>

        </li>

      </ul>

    </div>


    </form>
  </div>

  <div class="buyerorderlist">

    <div class="buyerordertop mb14">

      <p>

        <span style="width:38%">商品信息</span>

        <span style="width:10%">单价</span>

        <span style="width:7%">数量</span>

        <span style="width:10%">售后</span>

        <span style="width:20%">订单总价</span>

        <span style="width:15%">状态与操作</span>

      </p>

    </div> 

    <?php if($output['order_array']) { ?>

    <div class="buyerorderlistin">

    

      <ul class="ul-buyerorderlistin">

       <?php foreach ($output['order_array'] as $val) { ?>

        <li>

          <div class="buyerordermess">

            <p class="buyerordertime">

              <span><?php echo $lang['member_order_sn'].$lang['nc_colon'];?><?php echo $val[0]['order_sn']; ?>

                <?php if ($val[0]['order_from'] == 2){?>

                  <img src="<?php echo TEMPLATES_PATH;?>/images/mobile.png">

                  <?php }?>

                  <?php if(!empty($val[0]['group_id'])){ ?>

                  <i class="red" title="<?php echo $lang['nc_groupbuy'];?>"><?php echo $lang['nc_groupbuy_flag'];?></i>

                  <?php } ?>

                  <?php if(!empty($val[0]['xianshi_id'])){ ?>

                  <i class="green" title="<?php echo $lang['nc_xianshi'];?>"><?php echo $lang['nc_xianshi_flag'];?></i>

                  <?php } ?>

                  <?php if(!empty($val[0]['mansong_id'])){ ?>

                  <i class="orange" title="<?php echo $lang['nc_mansong'];?>"><?php echo $lang['nc_mansong_flag'];?></i>

                  <?php } ?>

                  <?php if(!empty($val[0]['bundling_id'])){?>

                  <i class="blue" title="<?php echo $lang['nc_bundling'];?>"><?php echo $lang['nc_bundling_flag'];?></i>

                  <?php }?>

              </span> 

                 

                 <!--下单时间-->

              <span><?php echo $lang['member_order_time'].$lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$val[0]['add_time']); ?>

              </span>

            </p>

            <span class="fl ml20"><a href="index.php?act=member&op=show_order&order_id=<?php echo $val[0]['order_id']; ?>" target="_blank" class="nc-show-order"><i></i><?php echo $lang['member_order_view_order'];?></a>

              <?php if ($val[0]['shipping_express_id']>0){?>

              <a href='index.php?act=member&op=show_express&order_id=<?php echo $val[0]['order_id']; ?>' class="nc-show-deliver"><i></i><?php echo $lang['member_order_show_deliver']?></a>

              <?php }?>

            </span>
            <a class="buyergoodsshopname" href="<?php 
            switch($val[0]['store_id']){     
                 case 19:
                  echo "index.php?act=national";
                  break;
                case 16:
                  echo "index.php?act=dubai";
                  break;
                case 17:
                  echo "index.php?act=japan";
                  break;
                case 20:
                  echo "index.php?act=car&op=sindex";
                  break;
                default:
                  echo "index.php?act=national";
                  break;
            }

             ?>"><?php echo $val[0]['store_name']; ?></a>
            <!--没修改连接前
            <a class="buyergoodsshopname" href="index.php?act=show_store&id=<?php echo $val[0]['store_id']; ?>"><?php echo $val[0]['store_name']; ?></a>
            没修改连接前end-->
            <!--<a class="buyerserviceits" href="index.php?act=member_snshome&mid=<?php echo $val[0]['member_id'];?>" title="<?php echo $lang['member_evaluation_storekeeper'];?>"><img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/icon13_1.png"><?php echo $val[0]['member_name'];?></a>-->

			<a  target="_blank" class="buyerserviceits" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $val[0]['store_qq'];?>&site=qq&menu=yes" title="<?php echo $lang['member_evaluation_storekeeper'];?>"><img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/icon13_1.png"><?php echo $val[0]['member_name'];?></a>

		  </div>

          <table>

            <tbody>

              <?php foreach($val as $k=>$v) { ?>

              <!--商品详情--> 

              <tr>

                <td style="width:39%" class="td-buyerordername">

                  <a class="a-buyerordername" href="index.php?act=goods&goods_id=<?php echo $v['goods_id'];?>" target="_blank">

                    <img src="<?php echo thumb($v,'tiny'); ?>" onload="javascript:DrawImage(this,60,60);"/>

                    <b><?php echo $v['goods_name']; ?></b>

                    <em><?php echo $v['spec_info'];?></em>

                  </a>

                </td>

                <td style="width:7%" class="td-font16"><?php echo $v['goods_price']; ?></td>

                <td style="width:7%" class="td-font16"><?php echo $v['goods_num']; ?></td>

                

                <?php if ((count($val) > 1 && $k ==0) || (count($val) == 1)){?>

                <!--退款--> 

                <td rowspan="<?php echo count($val);?>" style="width:10%" class="td-bl">

                <?php if(($v['order_state'] >= 20 && $v['order_state'] < 40 && $v['payment_code'] != 'cod') && ($v['payment_code'] == 'predeposit' || C('payment') == 0)) { ?>
                      
                  <?php if ($v['refund_state'] == 0){?>
                    
                  <p><a href="javascript:void(0)" nc_type="dialog" dialog_title="<?php echo $lang['member_order_refund'];?>" dialog_id="member_order_refund" dialog_width="400" uri="index.php?act=member_refund&op=add&order_id=<?php echo $v['order_id']; ?>" id="order<?php echo $v['order_id']; ?>_action_refund"><?php echo $lang['member_order_refund'];?></a></p>

                  <?php } elseif ($v['refund']['refund_state'] == 1){?>

                  <p><a href="javascript:void(0)" nc_type="dialog" dialog_title="<?php echo $lang['member_order_refund'];?>" dialog_id="member_order_refund" dialog_width="400" uri="index.php?act=member_refund&op=add&order_id=<?php echo $v['order_id']; ?>" id="order<?php echo $v['order_id']; ?>_action_refund"><?php echo $lang['member_refund_confirm'];?></a></p>

                  <?php }?>

                  <?php if ($v['order_state'] == 30 && $v['return_state'] == 0){?>

                  <p><a href="javascript:void(0)" nc_type="dialog" dialog_title="<?php echo $lang['member_order_return'];?>" dialog_id="member_order_return" dialog_width="400" uri="index.php?act=member_return&op=add&order_id=<?php echo $v['order_id']; ?>" id="order<?php echo $v['order_id']; ?>_action_return"><?php echo $lang['member_order_return'];?></a></p>

                  <?php }?>

                  <?php }

                   elseif ($v['order_state'] >= 20 && $v['order_state'] < 40 && $v['payment_code'] != 'cod'){?>
                  
                  <p>

                  <?php if ($v['refund_state'] == 0){?>
         
                  <a href="javascript:void(0)" nc_type="dialog" dialog_title="<?php echo $lang['member_order_refund'];?>" dialog_id="member_order_refund" dialog_width="400" uri="index.php?act=member_refund&op=offline_add&order_id=<?php echo $v['order_id']; ?>" id="order<?php echo $v['order_id']; ?>_action_refund"><?php echo $lang['member_order_refund'];?></a>

                  <?php } elseif ($v['refund']['refund_state'] == 1){?>
                  
                  <a href="javascript:void(0)" nc_type="dialog" dialog_title="<?php echo $lang['member_order_refund'];?>" dialog_id="member_order_refund" dialog_width="400" uri="index.php?act=member_refund&op=offline_add&order_id=<?php echo $v['order_id']; ?>" id="order<?php echo $v['order_id']; ?>_action_refund"><?php echo $lang['member_refund_confirm'];?></a>

                  <?php } elseif ($v['refund']['refund_state'] == 2 && $v['refund']['buyer_confirm'] == 1){?>
                  
                  <a href="javascript:void(0)" nc_type="dialog" dialog_title="<?php echo $lang['member_buyer_confirm'];?>" dialog_id="member_buyer_confirm" dialog_width="400" uri="index.php?act=member_refund&op=buyer_confirm&log_id=<?php echo $v['refund']['log_id']; ?>" id="order<?php echo $v['order_id']; ?>_buyer_confirm"><?php echo $lang['member_buyer_confirm'];?></a>

                  <?php }?>

                  </p>

                  <?php if ($v['order_state'] == 30 && $v['return_state'] == 0){?>
                    
                  <p><a href="javascript:void(0)" nc_type="dialog" dialog_title="<?php echo $lang['member_order_return'];?>" dialog_id="member_order_return" dialog_width="400" uri="index.php?act=member_return&op=add&order_id=<?php echo $v['order_id']; ?>" id="order<?php echo $v['order_id']; ?>_action_return"><?php echo $lang['member_order_return'];?></a> </p>

                  <?php } elseif ($v['order_state'] == 30 && $v['return']['return_state'] == 1){?>
            
                  <p><a href="javascript:void(0)" nc_type="dialog" dialog_title="<?php echo $lang['member_order_return'];?>" dialog_id="member_order_return" dialog_width="400" uri="index.php?act=member_return&op=view&return_id=<?php echo $v['return']['return_id']; ?>" id="order<?php echo $v['order_id']; ?>_action_return"><?php echo $lang['member_return_confirm'];?></a> </p>

                  <?php }?>

                  <?php }?>

                  <?php if($v['refund_state']>0 && $v['refund_amount']>0) { ?>
             
                  <p><a style="line-height:22px" href="javascript:void(0)" nc_type="dialog" dialog_title="<?php echo $lang['nc_view'].$lang['member_order_refund'];?>" dialog_id="member_order_refund" dialog_width="400" uri="index.php?act=member_refund&op=view&log_id=<?php echo $v['refund']['log_id']; ?>">
                    <?php echo substr($lang['member_order_refund'].$lang['nc_colon'],0,-3);?><?php echo $lang['currency'];?><?php echo $v['refund_amount']; ?></a></p>

                  <?php }?>

                  <?php 

                  if(empty($val[0]['finnshed_time'])) {

                    $time_limit = true;

                    }

                  else {

                    $time_limit = (intval($val[0]['finnshed_time'])+intval($output['complain_time_limit']))>time();

                    }

                  if(intval($val[0]['order_state']) >= 20 && $time_limit) {

                    ?>
                 
                  <p><a href="index.php?act=member_complain&op=complain_submit&order_id=<?php echo $val[0]['order_id']; ?>" target="_blank" class="ncu-btn2"><?php echo $lang['member_order_complain'];?></a></p>

                  <?php } ?>

                </td>

                

                <!--银联在线即时到账--> 

              <td rowspan="<?php echo count($val);?>" style="width:20%" class="td-bl">

                <p class="goods-price" id="order<?php echo $val[0]['order_id']; ?>_order_amount"><strong><?php echo $val[0]['order_amount']; ?></strong></p>

                  <?php if ($val[0]['payment_name']) { ?>

                  <p class="goods-pay" title="<?php echo $lang['member_order_pay_method'].$lang['nc_colon'];?><?php echo $val[0]['payment_name']; ?>"><?php echo $val[0]['payment_name']; ?></p>

                  <?php } ?>

                  <p class="goods-freight">

                  <?php if ($v['shipping_fee'] > 0){?>

                  (<?php echo $lang['member_order_shipping_han'].$v['shipping_name'];?> <?php echo $v['shipping_fee'];?>)

                  <?php }else{?>

                  <?php echo $lang['nc_common_shipping_free'];?>

                  <?php }?>

                  </p>    

              </td>

              <!--等待付款--> 

              <td  class="td-bl" style="width:15%"  rowspan="<?php echo count($val);?>"><p><?php echo $val[0]['state_info']; ?><br/>

                <?php if($val[0]['evaluation_status']==1) { ?>

                <br/>

                <?php echo $lang['member_order_evaluated'];?>

                <?php } ?>

                </p>

                <?php if ($val[0]['refund_state'] == 0 && $val[0]['order_state'] == 30) { ?>

                <?php if ($val[0]['payment_direct'] == '1'){?>

                <p><a href="javascript:void(0)" class="ncu-btn7 mt5" nc_type="dialog" dialog_id="buyer_order_confirm_order" dialog_width="400" dialog_title="<?php echo $lang['member_order_ensure_order'];?>" uri="index.php?act=member&op=change_state&state_type=confirm_order&order_sn=<?php echo $val[0]['order_sn']; ?>&order_id=<?php echo $val[0]['order_id']; ?>" id="order<?php echo $val[0]['order_id']; ?>_action_confirm"><?php echo $lang['member_order_ensure_order'];?></a></p>

                <?php }else{?>

                <p><a class="span-buyerstate" href="javascript:void(0)" id="order<?php echo $val[0]['order_id']; ?>_action_confirm" onclick="window.open('index.php?act=payment&op=receive&order_id=<?php echo $val[0]['order_id']; ?>');"><?php echo $lang['member_order_ensure_order'];?></a></p>

                <?php }?>

                <?php } ?>

                <?php if ($val[0]['order_state'] == 10) { ?>

                <p><a class="a-buyerofforder" href="javascript:void(0)" style="color:#F30; text-decoration:underline;" nc_type="dialog" dialog_width="400" dialog_title="<?php echo $lang['member_order_cancel_order'];?>" dialog_id="buyer_order_cancel_order" uri="index.php?act=member&op=change_state&state_type=cancel_order&order_sn=<?php echo $val[0]['order_sn']; ?>&order_id=<?php echo $val[0]['order_id']; ?>"  id="order<?php echo $val[0]['order_id']; ?>_action_cancel"><?php echo $lang['member_order_cancel_order'];?></a></p>

                <?php } ?>

                <?php if ($val[0]['order_state'] == 10) { ?>

                <a class="a-buyerding a-buyerpad" href="index.php?act=cart&op=order_pay&order_id=<?php echo $val[0]['order_id']; ?>" id="order<?php echo $val[0]['order_id']; ?>_action_pay"><?php echo $lang['member_order_pay'];?></a>

                <?php } ?>

                <?php if ($val[0]['order_state'] == 40 && $val[0]['refund_state']<2 && $val[0]['able_evaluate']) { ?>

                <p><a class="ncu-btn2 mt5" href="index.php?act=member_evaluate&op=add&order_id=<?php echo $val[0]['order_id']; ?>"><?php echo $lang['member_order_want_evaluate'];?></a><!--我要评价--></p>

                <?php } ?>

              </td>

                <?php }?>

              </tr>

              <?php } ?>

            </tbody>

          </table>

        </li>

<?php }?>       

      </ul>

      <?php  } else { ?>

        <tr>

          <td colspan="19" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>

        </tr>

      <?php } ?>

    </div>

    <?php if($output['order_array']) { ?>

    

  <div class="store">

    <div class="pagination-store"><?php echo $output['show_page'];?></div>

  </div>

    

    <?php }?>

  </div>

</div>

          

          

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>

<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-ui/i18n/zh-CN.js" ></script> 

<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/sns.js" ></script> 

<script type="text/javascript">

/*邀请链接*/

var snsIndexObj = snsIndexObj || {};

    snsIndexObj = {
      oErcode : ".ercode a",
      oClose : ".shareTitle span",
      oShareBox : ".shareBox",
      showBox : function(){
        $(snsIndexObj.oShareBox).show(200);
      },
      hideBox : function(){
        $(snsIndexObj.oShareBox).hide(200);
      }
    }

$(function(){

    $('#add_time_from').datepicker({dateFormat: 'yy-mm-dd'});

    $('#add_time_to').datepicker({dateFormat: 'yy-mm-dd'});
/*邀请链接显示与隐藏*/
    $(snsIndexObj.oErcode).bind("click",snsIndexObj.showBox);
    $(document).bind("click",function(event){
      var oTarget = $(event.target).attr("data-flag");
      if(oTarget == undefined){
        snsIndexObj.hideBox();  
      }
    })

});

</script>
<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdUrl":"<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/index.php?act=login&op=register&popularize=brnAjhS","bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"16"},"share":{},"image":{"viewList":["qzone","tsina","tqq","renren","weixin"],"viewText":"分享到：","viewSize":"16"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["qzone","tsina","tqq","renren","weixin"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>