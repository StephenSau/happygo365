<?php defined('haipinlegou') or exit('Access Invalid!');?>

<div class="sellertleftitem mb20">
<form id="" name="searchShop" method="get" action="index.php">
  <input type="hidden" name="act" value="show_store" />
  <input type="hidden" name="op" value="goods_all" />
  <input type="hidden" name="id" value="<?php echo $output['store_info']['store_id'];?>" />
  <h3 class="sellertlefttitle">店内搜索</h3>
  <div class="shopsearch">
    <ul class="ul-shopsearch">
      <li><span>关键字</span>
        <input class="inputtxt3" type="text" name="keyword" value="">
      </li>
      <li><span>价格</span>
        <input class="inputtxt4" type="text" name="start_price" value="<?php $_GET['start_price']?>">
        <b>-</b>
        <input class="inputtxt4" type="text" name="end_price" value="<?php $_GET['end_price']?>">
      </li>
      <li><a class="inputsub3" style="display:block; text-align:center;" href="javascript:document.searchShop.submit();" >立即搜索</a></li>
    </ul>
  </div> 
</form>   
</div>
<div class="sellertleftitem mb20">
  <h3 class="sellertlefttitle"><?php echo $lang['nc_goods_rankings'];?></h3>
  <div class="sellersalerank">
    
    <div class="sellersalerankti">
      <span class="span-sellersalerankti change1">
       销售量
      </span>
      <span class="change2">
        收藏量
      </span>
    </div>

    <div class="sellersalerankmain mb20 change3">
      <div class="sellersalerankmainlist">
        <ul class="ul-sellersalerankmainlist">
          <?php if(is_array($output['hot_sales']) && !empty($output['hot_sales'])){$num=1;?>
          <?php foreach($output['hot_sales'] as $val){?>
		  <?php if($val['salenum'] != 0){?>
          <li <?php if($num>6){?>style="display:none;"<?php }?>>
            <a class="a-sellerrankimg" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$val['goods_id']));?>"><img src="<?php echo thumb($val,'tiny');?>"  onload="javascript:DrawImage(this,60,60);"></a>
            <div class="sellerrankimgmess">
              <a href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$val['goods_id']));?>"><?php echo $val['goods_name']?></a>
              <p><?php echo $val['goods_store_price']?></p>
              <span>已售出<b><?php echo $val['salenum'];?></b>笔</span>
            </div>
          </li>
		  <?php }?>
          <?php $num++;}?>
          <?php }?>
        </ul>
      </div>
    </div>

    <div class="sellersalerankmain mb20 change4"style="display:none">
      <div class="sellersalerankmainlist" >
        <ul class="ul-sellersalerankmainlist">
          <?php if(is_array($output['hot_collect']) && !empty($output['hot_collect'])){$num=1;?>
          <?php foreach($output['hot_collect'] as $val){?>
		  <?php if($val['goods_collect'] != 0){?>
          <li <?php if($num>6){?>style="display:none;"<?php }?>>
            <a class="a-sellerrankimg" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$val['goods_id']));?>"><img src="<?php echo thumb($val,'tiny');?>"  onload="javascript:DrawImage(this,60,60);"></a>
            <div class="sellerrankimgmess">
              <a href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$val['goods_id']));?>"><?php echo $val['goods_name']?></a>
              <p><?php echo $val['goods_store_price']?></p>
              <span>已收藏<b><?php echo $val['goods_collect'];?></b>次</span>
            </div>
          </li>
		  <?php }?>
          <?php $num++;}?>
          <?php }?>
        </ul>
      </div>
    </div>
    
    <a class="sellermoregoods" href="index.php?act=show_store&op=goods_all&id=<?php echo $_GET['id'];?>">查看更多商品</a>
  </div>
</div>
  
<?php if($output['page'] == 'index'){?>
<div class="sellertleftitem">
  <h3 class="sellertlefttitle">合作伙伴</h3>
  
  <div class="friendpartners pr">
    <div class="friendpartnersin">
      <ul class="ul-friendpartners">
      <?php if(!empty($output['store_partner_list']) && is_array($output['store_partner_list'])){?>
      <?php foreach($output['store_partner_list'] as $value){ if($value['sp_logo'] != ''){?>
        <li><a href="<?php echo $value['sp_link'];?>"><img src="<?php echo $value['sp_logo']; ?>" onerror="this.src='<?php echo TEMPLATES_PATH."/images/member/default.gif"?>'" onload="javascript:DrawImage(this,150,50);" alt="<?php echo $value['sp_title']; ?>" /></a></li>
      <?php }?>
      <?php }?>
      <?php foreach($output['store_partner_list'] as $value){ if($value['sp_logo'] == ''){?>
        <li><a href="<?php echo $value['sp_link'];?>"><?php echo $value['sp_title']; ?></a></li>
      <?php }?>
      <?php }?>
      <?php }?>
      </ul>
    </div>
  </div>
</div>
  <?php }?>
<script type="text/javascript">
   //商品排行切换
  $('.change1').click(function(){
    $(this).addClass("span-sellersalerankti")
    $('.change2').removeClass("span-sellersalerankti")
    $('.change4').css("display","none")
    $('.change3').css("display","block")
  });
  $('.change2').click(function(){
    $(this).addClass("span-sellersalerankti")
    $('.change1').removeClass("span-sellersalerankti")
    $('.change3').css("display","none")
    $('.change4').css("display","block")

  })
</script>