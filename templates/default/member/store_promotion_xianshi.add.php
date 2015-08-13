<?php defined('haipinlegou') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/haipin2/base.css">
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_PATH;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
   <div class="popup" id="a-popupcloses">
        <div class="popupblack"></div>
        <div class="popupin font-mic">
            <a class="a-popupclose" id="a-popupclose" href="javascript:void(0);"></a>
            <div class="newsite">
			<form id="add_form" action="index.php?act=store_promotion_xianshi&op=xianshi_save" method="post">
                <p class="p-newactivity">添加活动</p>
                <table class="table-newsite">
                    <tbody>
                        <tr>
                            <th><i>*</i><?php echo $lang['xianshi_name'];?><?php echo $lang['nc_colon'];?></th>
                            <td>
                                <p><input id="xianshi_name" name="xianshi_name" class="inputtxt8" type="text"><span></span></p>
                                <p><?php echo $lang['xianshi_name_explain'];?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><i>*</i><?php echo $lang['start_time'];?><?php echo $lang['nc_colon'];?></th>
                            <td>
                                <p><input id="start_time" name="start_time" type="text" class="select3"  maxlength="25" /><span></span></p>
                                <p><?php echo sprintf($lang['xianshi_add_start_time_explain'],date('Y-m-d',$output['current_xianshi_quota']['start_time']));?></p>
                            </td>
                        </tr>                       
                        <tr>
                            <th><i>*</i><?php echo $lang['end_time'];?><?php echo $lang['nc_colon'];?></th>
                            <td>
                                <p><input id="end_time" name="end_time" type="text" class="select3"/><span></span></p>
                                <p><?php echo sprintf($lang['xianshi_add_end_time_explain'],date('Y-m-d',$output['current_xianshi_quota']['end_time']));?></p>
                            </td>
                        </tr>                       
                        <tr>
                            <th><i>*</i><?php echo $lang['xianshi_discount'];?><?php echo $lang['nc_colon'];?></th>
                            <td>
                               <p><input id="discount" name="discount" type="text" class="inputtxt8"><span></span></p>
                                <p><?php echo $lang['xianshi_discount_explain'];?></p>
                            </td>
                        </tr>                     
                        <tr>
                            <th> </th>
                            <td><input class="inputsub8 font-mic" id="submit_button" type="submit" value="确定"></td> 
                    </tbody>
                </table>
			</form>
            </div>
        </div>
    </div>
<!--<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/haipin2/jquery-1.9.1.min.js" charset="utf-8"></script>-->
<script type="text/javascript">
	iframe:parent.jQuery("#a-popupclose").click(function(){
		parent.jQuery("#a-popupcloses").hide();
		parent.window.location.href = "index.php?act=store_promotion_xianshi&op=xianshi_list";
	});
	jQuery("input.select3").css({height:'28px'});
	//轮播 
	/*
	$(".banner").goban({
		runbox:'.banner',
		runmbox:'.banimg',
		runmain:'.ul-banimg',
		runbtnclass:'span-banbtn',
		runalltime:5000,
		runtime:1000,
		resizee:1
	});
	
	// 浮动搜索
	var fixedsearch=$(".fixedsearch");
	$(window).scroll(function(){
		var scrolltop=$(window).scrollTop();
		if (scrolltop>101&&scrolltop<400) {
			fixedsearch.stop().animate({top:0},'slow');
		}
		if (scrolltop<103) {
			fixedsearch.stop().animate({top:-115+'px'},'slow');
		}
		// console.log(scrolltop);
	});
	// 轮播右tab
	var bantjtitle=$(".bantjtitle"),
		bantjmain=$(".bantjmain");
	bantjtitle.children().on({
		click:function(){
			var index=$(this).index();
			$(this).addClass("span-bantjtitle").siblings().removeClass("span-bantjtitle");
			bantjmain.children().eq(index).css('display','block').siblings().css('display','none');
		}
	})

	// 左导航菜单
	function getByClass(oParent,sClass,tagName){
		var aEle=document.getElementsByTagName(tagName),
			aResult=[],
			re=new RegExp('\\b'+sClass+'\\b','i'),
			i=0;
		for(i=0;i<aEle.length;i++){
			if (re.test(aEle[i].className)) {
				aResult.push(aEle[i]);
			}
		}
		return aResult;
	}
	var navleftmain=document.getElementById('navleftmain');
	var	navleftlist=getByClass(navleftmain,'navleftlist','div');
	var snavleft=getByClass(navleftmain,'snavleft','div');
	for (var i = 0; i < navleftlist.length; i++) {
		var _thistop=navleftlist[i].offsetTop;
		snavleft[i].style.top=_thistop-2*81*i-40;
	};
	// 回到顶部
	var agotop=$(".a-gotop");
	agotop.on({
		click:function(){
			$('html,body').animate({scrollTop:0},'slow');
		}
	});
	*/
	//卖家中心导航折叠
	$(".sn-indicator").click(function(){
		if($(this).parent().hasClass("unfold")){
			$(this).parent().removeClass("unfold").addClass("fold");
		}else{
			$(this).parent().removeClass("fold").addClass("unfold");
		}
	});
	$(document).ready(function(e) {
		$(".order-content").each(function(index, element) {
			$(".shouhou", this).height($(this).height() - 20);
			$(".all-price", this).height($(this).height() - 20);
            console.log($(this).height());
        });
    });
	
</script>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script> 
<script type="text/javascript">
$(document).ready(function(){
    $('#start_time').datepicker();
    $('#end_time').datepicker();

    jQuery.validator.methods.greaterThanDate = function(value, element, param) {
        var date1 = new Date(Date.parse(param.replace(/-/g, "/")));
        var date2 = new Date(Date.parse(value.replace(/-/g, "/")));
        return date1 < date2;
    };
    jQuery.validator.methods.lessThanDate = function(value, element, param) {
        var date1 = new Date(Date.parse(param.replace(/-/g, "/")));
        var date2 = new Date(Date.parse(value.replace(/-/g, "/")));
        return date1 > date2;
    };
    jQuery.validator.methods.greaterThanStartDate = function(value, element) {
        var start_date = $("#start_time").val();
        var date1 = new Date(Date.parse(start_date.replace(/-/g, "/")));
        var date2 = new Date(Date.parse(value.replace(/-/g, "/")));
        return date1 < date2;
    };


    //页面输入内容验证
    $("#add_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('p').children('span');
            error_td.find('.field_notice').hide();
            error_td.append(error);
        },
    	submitHandler:function(form){
    		ajaxpost('add_form', '', '', 'onerror');
    	},
            rules : {
                xianshi_name : {
                    required : true
                },
                start_time : {
                    required : true,
                    dateISO : true,
                    greaterThanDate : '<?php echo date('Y-m-d',$output['current_xianshi_quota']['start_time']);?>'
                },
                end_time : {
                    required : true,
                    dateISO : true,
                    lessThanDate : '<?php echo date('Y-m-d',$output['current_xianshi_quota']['end_time']);?>',
                    greaterThanStartDate : true 
                },
                discount : {
                    required : true,
                    number : true,
                    max : 9.9,
                    min : 0.1
                }
            },
                messages : {
                    xianshi_name : {
                        required : '<?php echo $lang['xianshi_name_error'];?>'
                    },
                    start_time : {
                        required : '<?php echo sprintf($lang['xianshi_add_start_time_explain'],date('Y-m-d',$output['current_xianshi_quota']['start_time']));?>',
                        dateISO : '<?php echo $lang['time_error'];?>',
                        greaterThanDate : '<?php echo sprintf($lang['xianshi_add_start_time_explain'],date('Y-m-d',$output['current_xianshi_quota']['start_time']));?>'
                    },
                    end_time : {
                        required : '<?php echo sprintf($lang['xianshi_add_end_time_explain'],date('Y-m-d',$output['current_xianshi_quota']['end_time']));?>',
                        dateISO : '<?php echo $lang['time_error'];?>',
                        lessThanDate : '<?php echo sprintf($lang['xianshi_add_end_time_explain'],date('Y-m-d',$output['current_xianshi_quota']['end_time']));?>',
                        greaterThanStartDate : '<?php echo $lang['greater_than_start_time'];?>'
                    },
                    discount : {
                        required : '<?php echo $lang['xianshi_discount_explain'];?>',
                        number : '<?php echo $lang['xianshi_discount_explain'];?>',
                        max : '<?php echo $lang['xianshi_discount_explain'];?>',
                        min : '<?php echo $lang['xianshi_discount_explain'];?>'
                    }
                }
    });
});
</script>