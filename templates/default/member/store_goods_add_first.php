<div class="content">

    	<div class="w1210 font-mic">

        	<div style="height:50px; line-height:45px; color:#888888">

            <?php if ($_SESSION['store_id']){?>

                <span>您当前位置：</span>

                <a href="index.php?act=store" style="color:#888888"><?php echo $lang['nc_seller'];?></a> <span>></span>

				<?php if($output['menu_sign_url'] != '' and $lang['nc_member_path_'.$output['menu_sign1']] != ''){?>

				<a href="<?php echo $output['menu_sign_url'];?>"/>

				<?php }?>

				<?php echo $lang['nc_member_path_'.$output['menu_sign']];?>

				<?php if($output['menu_sign_url'] != '' and $lang['nc_member_path_'.$output['menu_sign1']] != ''){?>

				</a><span>></span><?php echo $lang['nc_member_path_'.$output['menu_sign1']];?>

				<?php }?>

				

			<?php }?>

            </div>

            <div class="head-search">

            	<div class="hs-content">

                    <p>商品搜索</p>
					
					<form class="head-search-form" action="index.php?act=store_goods&op=add_goods&step=first" method="get">
						<input type="hidden" value="store_goods" name="act">
						<input type="hidden" value="add_goods" name="op">
						<input type="hidden" value="first" name="step">
						<span class="fl"><input id="tijiao" value="<?php if(!empty($_GET['goods_name'])){echo $_GET['goods_name'];}else{echo '请输入商品名称';}?>" name="goods_name" maxlength="22" type="text" class="search-text" /></span>
						<!--<span><a class="submit aaa" href="JavaScript:void(0);" id="searchBtn"><?php echo $lang['store_goods_step1_search'];?></a></span>-->
						<span><input id="look" style="width:50px; height:35px;" type="submit" value="<?php echo $lang['store_goods_step1_search'];?>"></span> 

					</form>
					
                </div>

            </div>
			<script>
				$("#tijiao").focus(function()
				{
					//$(this).val('');
					if($(this).val()=='请输入商品名称'){
						$(this).val('');
					}
				});
			</script>
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

            <div class="search-result-list01" style="position:relative;">

				

            	<table class="search-result-table table">

				<?php if(!empty($goods_records_done_list)):?>

                	<thead>

                    	<tr>

                        	<th width="40%">商品信息</th>

                            <th width="20%">价格</th>

                            <th width="20%">库存</th>

                            <th width="20%">操作</th>

                        </tr>

                    </thead>

					

                    <tbody>
					<?php foreach($goods_records_done_list as $k=>$v):
								
					?>

                    	<tr>

                        	<td class="ta-l">

                                <p><?php echo $v['goods_name'];?></p>

                            </td>

                            <td>

                            	<span class="s-value"><?php echo $v['declaration_price'];?></span>

                                <input type="text" value="<?php echo $v['declaration_price'];?>" readonly="true" />

                            </td>

                            <td>

                            	<span class="s-value"><?php echo $v['goods_number'];?></span>

                                <input type="text" value="<?php echo $v['goods_number'];?>" readonly="true" />

                            </td>

                            <td><a href="index.php?act=store_goods&op=add_goods&step=two&id=<?php echo $v['id'];?>&class_id=<?php echo $v['second_category'];?>"><?php echo $lang['store_goods_show'];?></a></td>

                        </tr>

						<?php 
							
						endforeach;
						?>

                    </tbody>

					<tr>

						<td colspan="13">
							<div class="pagination"><?php echo $page;?></div>
						</td>
					</tr>
					<?php else:?>
					<tr>
						<td colspan="13" style="height:220px">暂时还木有备案通过的商品</td>

					</tr>
					<?php endif;?>

                </table>

            </div>

        </div>

    </div>

<script type="text/javascript">

SEARCHKEY = '<?php echo $lang['store_goods_step1_search_input_text'];?>';

</script> 

<script type="text/javascript">

// ajax查询分类TAG

$('#searchKey').css('color','rgb(153,153,153)');

$('#searchKey').unbind().focus(function(){

    if($(this).val() == SEARCHKEY){

        $(this).val('');

    }

}).blur(function(){

    if($(this).val() == ''){

        $(this).val(SEARCHKEY);

    }

});



// 返回分类选择

$('a[nc_type="return_choose_sort"]').unbind().click(function(){

    $('.wp_search_result').hide();

    $('.search-result-list01').show();

});





$('#searchBtn').unbind().click(function(){

    $('#searchNone').hide();

    $('#searchSome').hide();

    if($('#searchKey').val() != SEARCHKEY && $('#searchKey').val() != ''){

        $('.wp_search_result').show();

        $('#searchLoad').show();

        $('.search-result-list01').hide();

        $('.goods-classify').css({height:'120px'});

        $.getJSON('index.php?act=store_goods&op=ajax_class_search&column=ok',{value:$('#searchKey').val()}, function(data){

            if (data == false){

                $('#searchLoad').hide();

                $('#searchNone').show();

            }else{

                if (data.length > 0){

                    var tag = '';

                    for (i = 0; i < data.length; i++){

                        tag +=('<li nc_type="searchList_name" id="'+data[i].gc_id+'|'+data[i].type_id+'">'+data[i].gc_tag_name+'</li>');

                    }

                    

                }

                $('#searchLoad').hide();

                $('#searchSome').show();

                $('#searchList > ul').html(tag);

                $.getScript("./resource/js/jquery.goods_add_step1.js");

            }

        });

    }else{

        alert(SEARCHKEY);

    }

});



</script>