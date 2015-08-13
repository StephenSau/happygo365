
<!--浮动客服-->
    <div class="floatService pf">
        <div class="weixin pr">
            <span class="pa"></span>
        </div>
        <ul class="bc_black">
            
            <li class="consult pr">
                <a href="tencent://message/?uin=3047450986" class="pa"></a>
            </li>
            <li class="sale pr">
                <a href="tencent://message/?uin=2711212465" class="pa"></a>
            </li>
            <li class="telphone pr">
                <a href="javascript:;" class="pa"></a>
                <span class="pa"></span>
            </li>
            <li class="cart pr">
                <a href="tencent://message/?uin=2711212465" class="pa"></a>
            </li>
            <li class="back"></li>
        </ul>
    </div>
    <!--浮动客服-->
<!--页脚区域-->
<div id="footer" class="bc_white">
    <div class="infoContent">
        <div class="guaranteeWrap">
            <ul class="black fw_b font16">
                <li class="fl ta_c"><span class="revamp_icon g1"></span>正品保障</li>
                <li class="fl ta_c"><span class="revamp_icon g2"></span>货源丰富</li>
                <li class="fl ta_c"><span class="revamp_icon g3"></span>低价保证</li>
                <li class="fl ta_c"><span class="revamp_icon g4"></span>阳光绿色通道</li>
                <li class="fl ta_c"><span class="revamp_icon g5"></span>极速配送</li>
                <li class="fl ta_c hiddenM"><span class="revamp_icon g6"></span>购物流程</li>
            </ul>
        </div>

        <div class="footerNav color_808080 lh24 of_h pr">
            <ul class="fl footerNavL a_808080">
                <?php if(!empty($output['article_list']) & is_array($output['article_list'])) {?>

                    <?php foreach($output['article_list'] as $key2=>$val2){ ?>
                        <?php if($key2 == 6) break; ?>
                        <li class="fl">
                            <dl>
                                <dt class="fw_b"><?php echo $val2['ac_name'] ?></dt>
                                <?php  ?>
                                <?php if(!empty($val2['list']) && is_array($val2['list'])) { ?>
                                    <?php foreach($val2['list'] as $key3=>$val3) { ?>
                                        <dd><a href="<?php echo SiteUrl.'/index.php?act=article&article_id='.$val3['article_id'] ?>" target="_blank"><?php echo $val3['article_title'] ?></a></dd>
                                    <?php } } ?>

                            </dl>
                        </li>
                    <?php } } ?>

            </ul>
            <div class="phone revamp_icon"></div>
            <div class="ercode pa">
                <ul class="of_h ta_c lh20">
                    <li class="fl weixin">

                        <p class="revamp_icon ps"></p>
                        <p class="pr"><span class="revamp_icon"></span>官方微信</p>
                    </li>
                    <li class="fl weibo">

                        <p class="revamp_icon ps" style="  background-position: -564px -78px;"></p>
                        <p class="pr"><span class="revamp_icon"></span>官方微博</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="copyContent w100 bc_black">
        <div class="copyright of_h">
            <div class="copyNav fl a_white white">
                <a class="ml_0" href="infoContent" target="blank">首页</a>|
                <a href="index.php?act=article&article_id=47" target="blank">招聘英才</a>|
                <a href="index.php?act=article&article_id=48" target="blank">广告合作</a>|
                <a href="index.php?act=article&article_id=49" target="blank">联系我们</a>
            </div>
            <div class="copyInof fr white">粤ICP备15053546 广东广物信息技术有限公司 版权所有</div>
        </div>
    </div>
</div>
<!--页脚区域-->


