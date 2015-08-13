<div class="content">
    	<div class="w1210 font-mic">
        	<?php if ($_SESSION['store_id']){?>
            <div  style="height:50px; line-height:45px; class="crumbs clear">
                <span>您当前位置：</span>
                <a href="index.php?act=store"><?php echo $lang['nc_seller'];?></a> <span>></span>
                <?php if($output['menu_sign_url'] != '' and $lang['nc_member_path_'.$output['menu_sign1']] != ''){?>
                    <a href="<?php echo $output['menu_sign_url'];?>"/>
                <?php }?>
                    <?php echo $lang['nc_member_path_'.$output['menu_sign']];?>
                <?php if($output['menu_sign_url'] != '' and $lang['nc_member_path_'.$output['menu_sign1']] != ''){?>
                    </a><span>></span><?php echo $lang['nc_member_path_'.$output['menu_sign1']];?>
                <?php }?>
            </div>
             <?php }?>
            <div class="goods-release-nav">
            	<ul class="grn-step">
                	<li>
                        <div class="grn-icon active step-one fl">
                            <span>STEP 1</span>
                            <p>选择商品分类</p>
                        </div>
                        <i class="active"></i>
                    </li>
                    <li>
                        <div class="grn-icon active step-two fl">
                            <span>STEP 2</span>
                            <p>填写商品信息</p>
                        </div>
                        <i class="active"></i>
                    </li>
                    <li>
                        <div class="grn-icon active step-three fl">
                            <span>STEP 3</span>
                            <p>商品发布成功</p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="goods-release-success01">
            	<div class="grs-content">
                    <p class="nice-big-icon"><?php echo $lang['store_goods_step3_goods_release_success'];?></p>
                    <a class="fl" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$output['goods_id']))?>"><?php echo $lang['store_goods_step3_viewed_product'];?></a>
                    <a class="fr" href="index.php?act=store_goods&op=edit_goods&goods_id=<?php echo $output['goods_id']; ?>"><?php echo $lang['store_goods_step3_edit_product'];?></a>
                </div>
            </div>
        </div>
    </div>
