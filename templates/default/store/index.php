<?php defined('haipinlegou') or exit('Access Invalid!');?>

<?php include template('store/header');?>
<?php include template('store/top');?>
<div class="sellerbanner">
  <div class="sellerbanimg pr">
          <a class="sellerbanimgaa" href="<?php echo ncUrl(array('act'=>'show_store','id'=>$output['store_info']['store_id']), 'store', $output['store_info']['store_domain']);?>">
            <?php if(!empty($output['store_info']['store_banner'])){?>
             <img src="<?php echo ATTACH_STORE.'/'.$output['store_info']['store_banner'];?>" alt="<?php echo $output['store_info']['store_name']; ?>" title="<?php echo $output['store_info']['store_name']; ?>">
            <?php }else{?>

					<?php if(!empty($output['store_slide']) && is_array($output['store_slide'])){?>
					<?php for($i=0;$i<5;$i++){?>
					<?php if($output['store_slide'][$i] != ''){?>
					<a <?php if($output['store_slide_url'][$i] != '' && $output['store_slide_url'][$i] != 'http://'){?>href="<?php echo $output['store_slide_url'][$i];?>"<?php }?>><img src="<?php echo ATTACH_SLIDE.DS.$output['store_slide'][$i];?>"></a>
					<?php }?>
					<?php }?>
					<?php }else{?><img src="<?php echo ATTACH_SLIDE.DS;?>f01.jpg">
					<?php }?>

            <?php }?>
          </a>
  </div>

</div>

<div class="content">
  <div class="sellercnt pdt40">
    <div class="w1210">
      <div class="sllercontin font-mic">
        
        <div class="sellertleft fl">
           
           <?php include template('store/info');?>
           <?php include template('store/callcenter');?>
           <?php include template('store/left');?>
        
        </div>          
          
        <div class="sellertright">
          <div class="sellergoodslist">
            <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
            <ul class="ul-sellergoodslist">            
              <?php foreach($output['goods_list'] as $key=>$value){?>
              <li <?php  if((intval($key)%4)==0 || $key==0){?>class="nomargin-l"<?php }?>>
                <div class="sellergoodslistblck">
                  <!--<a class="a-sellergoodslistimg" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$value['goods_id']), 'goods');?>"><img src="<?php echo thumb($value,'small');?>" onload="javascript:DrawImage(this,218,218);"></a>-->
                  <a class="a-sellergoodslistimg" href="index.php?act=goods&goods_id=<?php echo $value['goods_id'];?>"><img src="<?php echo thumb($value,'mid');?>" onload="javascript:DrawImage(this,218,218);"></a>
                  <div class="sellergoodslistmess">
                    <a class="a-sellergoodslistname" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$value['goods_id']), 'goods');?>"><?php echo $value['goods_name'];?></a>
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
                  <div class="pagination-store"><?php echo $output['show_page'];?></div>
              </div>
            
            <?php }else{?>
                  <div class="sellergoodslist">
                     <p><?php echo $lang['show_store_index_no_record'];?></p>
                  </div>
            <?php }?>
          </div>
        </div>
       
      
    </div>
  </div>
</div>
</div>
<!--
<div class="gotop">
    <a class="a-gotop" href="javascript:void(0);"></a>
  </div>
  -->
<?php include template('footer');?>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.flexslider-min.js"></script> 
<script type="text/javascript">
	<?php echo $output['style_js'];?>
	$(window).load(function() {
		$('.flexslider').flexslider();
	});

  // 回到顶部
  var agotop=$(".a-gotop");
  agotop.click(function(){
      $('html,body').animate({scrollTop:0},'slow');
    }
  );
 
</script>
</body>
</html>
