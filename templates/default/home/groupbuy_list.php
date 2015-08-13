<link href="<?php echo TEMPLATES_PATH;?>/css/home_group.css" rel="stylesheet" type="text/css">
<script src="<?php echo RESOURCE_PATH; ?>/js/jquery.cookie.js" type="text/javascript"></script>
<script language="JavaScript">
         var tms = [];
         var day = [];
         var hour = [];
         var minute = [];
         var second = [];
         function takeCount() {
             setTimeout("takeCount()", 1000);
             for (var i = 0, j = tms.length; i < j; i++) {
                 tms[i] -= 1;
                 var days = Math.floor(tms[i] / (1* 60 * 60 * 24));
                 var hours = Math.floor(tms[i] / (1* 60 * 60)) % 24;
                 var minutes = Math.floor(tms[i] / (1* 60)) % 60;
                 var seconds = Math.floor(tms[i] / 1) % 60;
                 if (days < 0)
                     days = 0;
                 if (hours < 0)
                     hours = 0;
                 if (minutes < 0)
                     minutes = 0;
                 if (seconds < 0)
                     seconds = 0;
                 document.getElementById(day[i]).innerHTML = days;
                 document.getElementById(hour[i]).innerHTML = hours;
                 document.getElementById(minute[i]).innerHTML = minutes;
                 document.getElementById(second[i]).innerHTML = seconds;
             }
         }
         setTimeout("takeCount()", 1000);
</script>
<script language="JavaScript">
$(document).ready(function(){
    $("#list").hide();
    $("#button_show").click(function(){
        $("#list").toggle();
    });
    $("#button_close").click(function(){
        $("#list").hide();
    });
    $('.group-list').children('ul').children('li').bind('mouseenter',function(){
        $('.group-list').children('ul').children('li').attr('class','c1');
        $(this).attr('class','c2');
    });
    $('.group-list').children('ul').children('li').bind('mouseleave',function(){
        $('.group-list').children('ul').children('li').attr('class','c1');
    });
    var area = $.cookie('<?php echo COOKIE_PRE;?>groupbuy_area');
   if(area == null) {
        $("#show_area_name").html("<?php echo $lang['text_country'];?>");
        $("#groupbuy_area").val('');
    }
   else {
        area_array = area.split(",");
        $("#show_area_name").html(area_array[1]);
        $("#groupbuy_area").val(area);
    }
});

function submit_search() {
        $('#search_form').attr('method','get');
        $('#search_form').submit();
}
function set_groupbuy_area(area) {
    if(area == '') { 
        $("#groupbuy_area").val('');
        $.cookie('<?php echo COOKIE_PRE;?>groupbuy_area',null);
    }
    else {
        area_array = area.split(",");
        $("#groupbuy_area").val(area_array[0]);
        $.cookie('<?php echo COOKIE_PRE;?>groupbuy_area',area);
    }
    submit_search();
}
function set_groupbuy_class(class_id) {
    $("#groupbuy_class").val(class_id);
    submit_search();
}
function set_groupbuy_price(price_range) {
    $("#groupbuy_price").val(price_range);
    submit_search();
} 
function set_groupbuy_order(order_key) {
    if(order_key == $("#groupbuy_order_key").val()) {
        change_groupbuy_order();
    }
    else {
        $("#groupbuy_order").val('asc');
    }
    $("#groupbuy_order_key").val(order_key);
    submit_search();
} 
function change_groupbuy_order() {
    if($("#groupbuy_order").val() == 'asc') {
        $("#groupbuy_order").val('desc');
    }
    else {
        $("#groupbuy_order").val('asc');
    }
}
</script>
<form id="search_form">
  <input name="act" type="hidden" value="show_groupbuy" />
  <input name="op" type="hidden" value="groupbuy_list" />
  <input id="groupbuy_area" name="groupbuy_area" type="hidden" value="<?php echo $_GET['groupbuy_area'];?>"/>
  <input id="groupbuy_class" name="groupbuy_class" type="hidden" value="<?php echo $_GET['groupbuy_class'];?>"/>
  <input id="groupbuy_price" name="groupbuy_price" type="hidden" value="<?php echo $_GET['groupbuy_price'];?>"/>
  <input id="groupbuy_order_key" name="groupbuy_order_key" type="hidden" value="<?php echo $_GET['groupbuy_order_key'];?>"/>
  <input id="groupbuy_order" name="groupbuy_order" type="hidden" value="<?php echo $_GET['groupbuy_order'];?>"/>
</form>
<div class="content">
	<div class="w1210 pdt30">
		<div class="teambuyallsearch">
			
			<div class="teambuyallsearchtop pr">
				<div class="teambuyallsearchtopmain">
					<ul class="ul-teambuyallsearchtop font-mic">
					  <li><a href="javascript:void(0)" onClick="set_groupbuy_class('')"><?php echo $lang['text_no_limit'];?></a></li>
					  <?php if(is_array($output['class_list'])) { ?>
					  <?php foreach($output['class_list'] as $groupbuy_class) { ?>
					  <?php if(intval($groupbuy_class['deep']) === 0) { ?>
						<li <?php echo $_GET['groupbuy_class'] == $groupbuy_class['class_id']?"class='selected'":'';?>>
						  <a href="javascript:void(0)" onClick="set_groupbuy_class('<?php echo $groupbuy_class['class_id'];?>')"><?php echo $groupbuy_class['class_name'];?></a>
						</li>
					  <?php }?>
					  <?php }?>
					  <?php }?>
					</ul>
				</div>
				<a class="a-teambuyallsearchpre" href=""><</a>
				<a class="a-teambuyallsearchnet" href="">></a>
			</div>
			
			<div class="teambuyallsearchbtm pr">
				<ul class="ul-teambuyallsearchbtm font-mic">
					<li><a class="<?php echo empty($_GET['groupbuy_order_key'])?'a-teambuyallsearchbtm-up':''?>" href="JavaScript:void(0);" onClick="set_groupbuy_order('')"><?php echo $lang['text_default'];?></a></li>
					<li>
					<a <?php if(!empty($_GET['groupbuy_order_key']) && $_GET['groupbuy_order_key'] == 'sale' ){?>
						class="a-teambuyallsearchbtm-up";
					<?php }else{?>
						class="";
					<?php }?>
					href="javascript:void(0)" onClick="set_groupbuy_order('sale')"><?php echo $lang['text_sale'];?></a></li>
					<li>
					<a <?php if(!empty($_GET['groupbuy_order_key']) && $_GET['groupbuy_order_key'] == 'price'){?>
						class="a-teambuyallsearchbtm-up";
					<?php }else{?>
						class="";
					<?php }?>
					href="javascript:void(0)" onClick="set_groupbuy_order('price')"><?php echo $lang['text_price'];?></a></li>
				</ul>
				<div class="teambuyallsearchselect">
				  <ul>
				    <li><a href="index.php?act=show_groupbuy&op=groupbuy_soon" target="_blank"><?php echo $lang['groupbuy_soon'];?></a></li>
					<li><a href="index.php?act=show_groupbuy&op=groupbuy_history" target="_blank"><?php echo $lang['groupbuy_history'];?></a></li>
				  </ul>
				</div>
			</div>
		</div>
		<?php if(is_array($output['groupbuy_template'])) { ?>
		<div class="teambuy pdt20">
			<?php if(is_array($output['groupbuy_list'])) {$num=1; ?>
			<div class="teambuyin teambuyall font-mic">
				<ul class="ul-teambuyin">
					<?php foreach($output['groupbuy_list'] as $key=>$groupbuy) { ?>
					<li <?php if(($key%3)==0 || $key0){?>class="nomargin-l"<?php }?> <?php if($num>3){?>style="display:none;"<?php }?>>
						<div class="teambuyinborder">
							<a title="" href="<?php echo ncUrl(array('act'=>'show_groupbuy','op'=>'groupbuy_detail','group_id'=>$groupbuy['group_id'],'id'=>$groupbuy['store_id']), 'groupbuy');?>" target="_blank"><img style="width:390px; height:260px;" src="<?php echo gthumb($groupbuy['group_pic'],'mid');?>" alt="" onload="javascript:DrawImage(this,296,216);"></a>
							<div class="teambuymess pr">
								<h4>
									<a class="a-teambuyinimg" title="<?php echo $groupbuy['group_name'];?>" href="<?php echo ncUrl(array('act'=>'show_groupbuy','op'=>'groupbuy_detail','group_id'=>$groupbuy['group_id'],'id'=>$groupbuy['store_id']), 'groupbuy');?>" target="_blank"><?php echo $groupbuy['group_name'];?></a>
								</h4>
								<p class="p-goodsprice">乐购价:<span><b><?php echo $lang['currency'].$groupbuy['goods_price'];?></b></span></p>
								<p class="p-goodsnumber">已有<span><?php echo $groupbuy['def_quantity']+$groupbuy['virtual_quantity'];?>人</span>团购</p>
								<?php if($groupbuy['state'] == '3') { ?>
								<a class="a-teambuybtn" href="<?php echo ncUrl(array('act'=>'show_groupbuy','op'=>'groupbuy_detail','group_id'=>$groupbuy['group_id'],'id'=>$groupbuy['store_id']), 'groupbuy');?>" style="cursor:pointer" target="_blank">立即团购</a>
							    <?php }else{?>
								 <a class="a-teambuybtn" href="<?php echo ncUrl(array('act'=>'show_groupbuy','op'=>'groupbuy_detail','group_id'=>$groupbuy['group_id'],'id'=>$groupbuy['store_id']), 'groupbuy');?>" style="cursor:pointer" target="_blank">团购结束</a>
								<?php }?>
							</div>
						</div>
					</li>
					<?php }?>
				</ul>
				<?php $num++;}else{?>
				<div class="teambuyin teambuyall font-mic">
				<ul class="ul-teambuyin">
				  <p class="no_info"><?php echo $lang['no_groupbuy_info'];?></p>
				</ul>
				</div>
				<?php }?>
			</div>
		</div>
		<?php }else{?>
			<div class="teambuyin teambuyall font-mic">
			 <ul class="ul-teambuyin">
			  <p class="no_info"><?php echo $lang['no_groupbuy_info'];?></p>
			 </ul>
			</div>
		<?php }?>
		
		<?php if(is_array($output['groupbuy_template'])) { ?>
        <?php if(is_array($output['groupbuy_list'])) { ?>
		<div class="store">
				<div class="pagination"><?php echo $output['show_page'];?></div>
		</div>
		<?php }?>
		<?php }?>
	</div>
</div>
