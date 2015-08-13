<?php defined('haipinlegou') or exit('Access Invalid!');?>

<?php include template('store/header');?>



  <?php include template('store/top');?>

  

  <div class="content">

  <div class="sellercnt pdt40">

    <div class="w1210">

      <div class="sllercontin font-mic">

        <div class="sellertleft fl">

          <?php include template('store/info');?>

          <?php include template('store/left');?>

        </div>

        <div class="sellertright">

            <div class="sellergoodslist">

              <div style="height:40px; color:#ff6c00; font-weight:bold;">

                <h4>

                  <?php if(!empty($_GET['stc_id'])){echo $output['stc_name'];}elseif(!empty($_GET['keyword'])){echo $lang['show_store_index_include'].$_GET['keyword'].$lang['show_store_index_goods'];}else{ echo $lang['nc_whole_goods']; }?>

                </h4>

              </div>

              <?php if(!empty($output['recommended_goods_list']) && is_array($output['recommended_goods_list'])){?>

              <ul class="ul-sellergoodslist">

               <?php foreach($output['recommended_goods_list'] as $key=>$value){?>

               <li <?php if(($key%4)==0 || $key==0){?>class="nomargin-l"<?php }?>>

                  <div class="sellergoodslistblck">



                    <a class="a-sellergoodslistimg" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$value['goods_id']), 'goods');?>"><img src="<?php echo thumb($value,'small');?>" onload="javascript:DrawImage(this,160,160);" title="<?php echo $value['goods_name'];?>" alt="<?php echo $value['goods_name'];?>" /></a>

                    

                    <div class="sellergoodslistmess">

                      

                      <a class="a-sellergoodslistname" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$value['goods_id']), 'goods');?>"><?php echo mb_strcut($value['goods_name'],0,30,'utf-8');?></a>

                      <p class="p-sellergoodslistprice">￥

                       <b>

                        <?php if(intval($value['group_flag']) === 1) { ?>

                        <?php echo $value['group_price']?>

                        <?php } elseif(intval($value['xianshi_flag']) === 1) { ?>

                        <?php echo ncPriceFormat($value['goods_store_price'] * $value['xianshi_discount'] / 10);?>

                        <?php } else { ?>

                        <?php echo $value['goods_store_price']?>

                        <?php } ?>

                       </b>

                      </p>

                      <p class="p-saleedandpj">

                        <span>销量<b><?php echo $value['salenum'];?></b></span>

                        <span class="span-saleedandpjright">评价<b><?php echo $value['commentnum']?></b></span>

                      </p>



                    </div>



                  </div>

                </li>

                <?php }?>

              </ul>

                <div class="store">

                  <div class="pagination-store"><?php echo $output['show_page']; ?></div>

                </div>

              <?php }else{?>

                 <div class="sellergoodslist nothing">

                   <p><?php echo $lang['show_store_index_no_record'];?></p>

                 </div>

              <?php }?>

            </div>

          </div>

      </div>

    </div>

  </div>

</div>

<div class="gotop">

    <a class="a-gotop" href="javascript:void(0);"></a>

  </div>



<?php include template('footer');?>

<script type="text/javascript">

function set_form(set){

	if($('input[name="key"]').val() == set){

		if($('input[name="order"]').val() == 'asc'){

			$('input[name="order"]').val('desc');

		}else{

			$('input[name="order"]').val('asc');

		}

	}else{

		$('input[name="order"]').val('desc');

	}

	$('input[name="key"]').val(set);

	$('#search_form').submit();

}



// 回到顶部

  var agotop=$(".a-gotop");

  agotop.click(function(){

      $('html,body').animate({scrollTop:0},'slow');

    }

  );

</script>

</body>

</html>

