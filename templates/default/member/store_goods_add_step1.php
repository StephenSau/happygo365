<div class="content">
    	<div class="w1210 font-mic">
        	<div class="w1210 font-mic">
        <?php if ($_SESSION['store_id']){?>
            <div  style="height:20px; line-height:20px;" class="crumbs01">
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
                        <div class="grn-icon step-two fl">
                            <span>STEP 2</span>
                            <p>填写商品信息</p>
                        </div>
                        <i class="active"></i>
                    </li>
                    <li>
                        <div class="grn-icon step-three fl">
                            <span>STEP 3</span>
                            <p>商品发布成功</p>
                        </div>
                    </li>
                </ul>
            </div>
			<!--S 搜索商品-->
            <div class="head-search">
            	<div class="hs-content">
                    <p>商品搜索</p>
                    <form class="head-search-form">
                        <span class="fl"><input value="<?php echo $lang['store_goods_step1_search_input_text'];?>" id="searchKey" maxlength="22" type="text" class="search-text" /></span>
                        <span class="fl"><a class="submit aaa" href="JavaScript:void(0);" id="searchBtn"><?php echo $lang['store_goods_step1_search'];?></a></span>
                    </form>
                </div>
            </div>
			<!--S 搜索结果-->
			<div class="wp_search_result" style="display:none;">
				<div class="back_to_sort"><a href="JavaScript:void(0);" nc_type="return_choose_sort">&lt;&lt;<?php echo $lang['store_goods_step1_return_choose_category'];?></a></div>
				<div class="no_result" id="searchNone" style="display:none;">
				  <div class="cont">
					<p><?php echo $lang['store_goods_step1_search_null'];?></p>
					<p><a href="JavaScript:void(0);" nc_type="return_choose_sort">
					  <button><?php echo $lang['store_goods_step1_return_choose_category'];?></button>
					  </a>
					<p> 
				  </div>
				</div>
				<div class="has_result" id="searchLoad" style="display:none;">
				  <div class="loading"><img src="<?php echo TEMPLATES_PATH;?>/images/loading.gif" alt="loading..." ><span class="txt_searching"><?php echo $lang['store_goods_step1_searching'];?></span></div>
				</div>
				<div class="has_result" id="searchSome" style="display:none;">
				  <div id="searchEnd"></div>
				  <div class="result_list" id="searchList">
					<ul>
					</ul>
				  </div>
				</div>
			  </div>
              <!--S 搜索结果-->

            <div class="goods-classify" id="class_div">

            	<div class="classify-item">
                    <dl class="goods-list">
                        <dd class="unfold">
                        	<div id="class_div_1" class="title category_list">
							<ul>
							  <?php if(isset($output['goods_class']) && !empty($output['goods_class']) ) {?>
							  <?php foreach ($output['goods_class'] as $val) {?>
							  <li class="" onclick="selClass(this);" id="<?php echo $val['gc_id'];?>|1|<?php echo $val['type_id'];?>"> <a class="" href="javascript:void(0)"><span class="has_leaf"><?php echo $val['gc_name'];?></span></a> </li>
							  <?php }?>
							  <?php }?>
							</ul>			
							</div>
                        </dd>
                    </dl>
                </div>
                <div class="classify-item">
                    <dl class="goods-list">
                    	<dd class="unfold ss">
						<div id="class_div_2" class="title category_list">
                            <ul>
                            </ul>
						</div>
                        </dd>
                    </dl>
                </div>
                <div class="classify-item">
                    <dl class="goods-list">
                    	<dd class="unfold ss">
						<div id="class_div_3" class="title category_list">
                            <ul>
                            </ul>
						</div>
                        </dd>
                    </dl>
                </div>
                <div class="classify-item mr-0">
                    <dl class="goods-list">
                    	<dd class="unfold ss">
						<div  id="class_div_4" class="title category_list">
                            <ul>
                            </ul>
						</div>
                        </dd>
                    </dl>
                </div>
				<!--显示选择的商品-->
                <div class="selected-goods"   style="display: block; clear:both;">
                	<p id="commodityspan"  style="color:#F00;"><?php echo $lang['store_goods_step1_please_choose_category'];?></p>
                	<p id="commoditydt" style="display: none;"><?php echo $lang['store_goods_step1_current_choose_category'];?><?php echo $lang['nc_colon'];?></p>
                    <div  id="commoditydd" class="selected"; style="display:inline;float:left"></div>
                    <div  id="commoditya" style="display: none;">&nbsp;&nbsp;<a href="JavaScript:void(0);"><?php echo $lang['store_goods_step1_add_common_category'];?></a></div>
                </div>
				<!--显示选择的商品-->
                <div>
				<form method="get">
				  <input name="act" value="store_goods" type="hidden" />
				  <?php if(isset($_GET['goods_id']) && intval($_GET['goods_id']) > 0){?>
				  <input name="op" value="edit_goods" type="hidden" />
				  <input name="goods_id" value="<?php echo $_GET['goods_id'];?>" type="hidden" />
				  <?php } else {?>
				  <input name="op" value="add_goods" type="hidden" />
				  <input name="step" value="two" type="hidden" />
				  <?php }?>
				  <input name="class_id" id="class_id" value="" type="hidden" />
				  <input name="t_id" id="t_id" value="" type="hidden" />
				  <input name="id" id="id" value="<?php echo $_GET['id'];?>" type="hidden" />
				  <input disabled="disabled" id="button_next_step" value="<?php echo $lang['store_goods_step1_next'];?>" type="submit"  class="next-btn" />
				</form>
				</div>
            </div>
        </div>
    </div>
<script type="text/javascript">
SEARCHKEY = '<?php echo $lang['store_goods_step1_search_input_text'];?>';
</script> 
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.goods_add_step1.js"></script>