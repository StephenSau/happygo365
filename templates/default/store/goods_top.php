<?php defined('haipinlegou') or exit('Access Invalid!');?>
<!--菜单区域-->
<div id="navWrap" class="bc_black w100 pr">
    <div id="navContent" class="a_white">
        <ul id="nav" class="fl font15">
            <li class="active"><a href="index.php" target="blank">首 页 </a></li>
            <?php if(!empty($output['head_menu']) && is_array($output['head_menu'])){ ;?>
                <?php foreach ($output['head_menu'] as $k1=>$v1) { ?>
                    <li><a href="<?php echo $v1['word_link'] ?>" target="blank"><?php echo $v1['word_value'] ?></a></li>
                <?php } } ?>
            <li class="subNav pr">
                <span class="revamp_icon"></span>
                <a href="javascript:;">主题馆</a>
                <dl class="w100 pa a_white a_bg_white dp_n">
                    <dd><a href="<?php echo SiteUrl.'/index.php?act=national' ?>" target="_blank">韩国馆</a></dd>
                    <dd><a href="<?php echo SiteUrl.'/index.php?act=dubai' ?>" target="_blank">迪拜馆</a></dd>
                    <dd><a href="<?php echo SiteUrl.'/index.php?act=japan' ?>" target="_blank">日本馆</a></dd>
                </dl>
            </li>
        </ul>
        <a id="shopCart" href="<?php echo SiteUrl.'/';?>index.php?act=cart" target="">
            <span class="revamp_icon"></span>购物车<span>(<?php echo intval($output['goods_num']); ?>)</span>
        </a>
    </div>
</div>
<!--菜单区域-->
</div>
<!--公共头部-->
