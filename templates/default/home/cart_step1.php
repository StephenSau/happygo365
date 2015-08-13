<link href="<?php echo TEMPLATES_PATH;?>/css/home_cart.css" rel="stylesheet" type="text/css">
<style type="text/css">#navBar { display: none !important;}</style>
<ul class="flow-chart flow-ch">
  <li class="step a1" title="<?php echo $lang['cart_index_ensure_order'];?>"></li>
  <li class="step b1" title="<?php echo $lang['cart_index_ensure_info'];?>"></li>
  <li class="step c2" title="<?php echo $lang['cart_index_buy_finish'];?>"></li>
</ul>
<?php if($output['buynow'] == '1'){?>
<form method="post" id="order_form" name="order_form" action="index.php?act=buynow&op=step2">
<input type="hidden" name="buynow_spec_id" value="<?php echo intval($_GET['buynow_spec_id']);?>">
<input type="hidden" name="buynow_quantity" value="<?php echo intval($_GET['buynow_quantity']);?>">
<?php }elseif($output['bundling'] == '1'){?>
<form method="post" id="order_form" name="order_form" action="index.php?act=buynow&op=bundling_step2">
<input type="hidden" name="bundling_id" value="<?php echo $output['bundling_id'];?>" />
<input type="hidden" name="spec" value='<?php echo $output['spec'];?>' />
<input type="hidden" name="quantity" value="<?php echo $output['quantity'];?>" />
<?php }else{?>
<form method="post" id="order_form" name="order_form" action="index.php?act=cart&op=step2">
<input type="hidden" name="goodsId" value="<?php echo $goodsId; ?>" />
  <?php }?>
  <input type="hidden" name="store_id" value="<?php echo intval($_GET['store_id']); ?>" />
  <div class="content margin1" style="width:1210px;">
    <?php include template('home/cart_shipping');?>
    <?php if($output['xianshi_flag'] == false && $output['mansong_flag'] == false && $output['bundling_flag'] == false) { ?>
    <?php include template('home/cart_voucher');?>
    <?php } elseif($output['promotion_explain']) { ?>
    <dl class="cart-discount">
      <dt><?php echo $lang['cart_step1_youhui'];?><i></i></dt>
      <dd><?php echo $output['promotion_explain'];?></dd>
    </dl>
    <?php } ?>
    <?php include template('home/cart_amount');?>
    <div class="contad pdt40">
		<a href="">
			<img src="<?php echo TEMPLATES_PATH;?>/images/haipin2/img/img4.jpg">
		</a>
	</div>
    <div class="clear"></div>
  </div>
</form>
<!--
<div class="gotop">
    <a class="a-gotop" href="javascript:void(0);"></a>
</div>
-->
<style type="text/css">
table.gridtable 
{
	width:500px;
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
}
</style>

<script>
function stopSend(str){
 $("#upload1").css({"display":"block"});
 $("#upload1").attr('src',str);
 $("#idcard").val(str);
}

function stop(str){
 $("#upload2").css({"display":"block"});
 $("#upload2").attr('src',str);
 $("#idcard2").val(str);
}

function error(str)
{


	alert(str);
}

function showval(obj)
{
   getallprice();
   var amount = $("#order_amount").html();
   var atoatl= parseFloat(amount-obj);
    $("#order_amount").html(atoatl);
   //var voucherValue = obj.value;
   //alert(voucherValue);
   
  // if(radioValue==0)
  // {
 //     document.all.showMessage.innerHTML = "公司：<input type=\"text\" name=\"company\">";
 //  }else if(radioValue==1){
  //    document.all.showMessage.innerHTML = "";
 //  }
}
/*
// 回到顶部
  var agotop=$(".a-gotop");
  agotop.on({
    click:function(){
      $('html,body').animate({scrollTop:0},'slow');
    }
  });
/*
$("#submit").click(function()
{
	var error = $("form .error").length;
	if(error > 0)
	{
		return false;
	}
	
	var id_card = $("#id_card").val();
	var idcard = $("#idcard").val();
	var idcard2 = $("#idcard2").val();
	if(id_card == "" || idcard=="" || idcard2=="")
	{
		alert('请填入完整信息！');
		return false;
	}else
	{
		$(".status").hide();
	}
})*/
</script>
