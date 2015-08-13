
<div class="content" style="width:100%;">

	<div class="w1210" style="width:100%;overflow:hidden;">

		<div class="serviceWrap">
			<div class="bannerLogo">
				<div style="position:relative;">
					<i class="qulityWrap_mao"  name="001" id="001"></i>
					<img src="templates/default/images/service/service_guarantee02.jpg" alt="" width="1920" height="549" />
				</div>
			</div>
		<div id="fixedRight">
			<div class="navWrap_guarantee">
				<ul>
					<li class="navWrap_gua_li_a">
						<a href="#001">
							<p ></p>
							<a>正品保障</a>
						</a>
					</li>
					<li class="navWrap_gua_li_b">
						<a href="#002">
							<p></p>
							<a>资源丰富</a>
						</a>
					</li>
					<li class="navWrap_gua_li_c">
						<a href="#003">
							<p></p>
							<a>低价保证</a>
						</a>
					</li>
					<li class="navWrap_gua_li_d">
						<a href="#004">
							<p></p>
							<a>阳关绿色通关</a>
						</a>
					</li>
					<li class="navWrap_gua_li_e">
						<a href="#005">
							<p></p>
							<a>急速配送</a>
						</a>
					</li>
					<li class="navWrap_gua_li_f"  href="#006">
						<a href="#006"> 
							<p></p>
							<a>购物流程</a>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<!-- 2楼 -->
		<div class="qulityWrap_di">
			<i class="qulityWrap_mao"  name="002" id="002"></i>
			<div class="qulityWrap_box">
			</div>
		</div>
		<div class="qulityWrap_di_a"><img width="1123" height="552" src="templates/default/images/service/service_guarantee03.jpg"></div>
		<!-- 3楼 -->
		<div class="qulityWrap_di_2">
			<i class="qulityWrap_mao"  name="003" id="003"></i>
			<div class="qulityWrap_box_2">
			</div>
		</div>
		<div class="qulityWrap_di_b"><img width="974" height="618" src="templates/default/images/service/service_guarantee04.jpg"></div>
		<!-- 4楼 -->
		<div class="qulityWrap_di_3">
			<i class="qulityWrap_mao"  name="004" id="004"></i>
			<div class="qulityWrap_box_3">
			</div>
		</div>
		<div class="qulityWrap_di_c"><img width="1126" height="338" src="templates/default/images/service/service_guarantee05.jpg"></div>
		<!-- 5楼 -->
		<div class="qulityWrap_di_4">
			<i class="qulityWrap_mao"  name="005" id="005"></i>
			<div class="qulityWrap_box_4">
			</div>
		</div>
		<div class="qulityWrap_di_d"><img width="956" height="626" src="templates/default/images/service/service_guarantee06.jpg"></div>
		<!-- 6楼 -->
		<div class="qulityWrap_di_5">
			<i class="qulityWrap_mao"  name="006" id="006"></i>
			<div class="qulityWrap_box_5" >
			</div>
		</div>
		<div class="qulityWrap_di_e"><img width="1061" height="820" src="templates/default/images/service/service_guarantee07.jpg"></div>
	
			
		</div>
		

	</div>

</div>
<script>
// 一定位置固定

 

/*旋转木马*/

var serviceGuaranteeObj = serviceGuaranteeObj || {};

serviceGuaranteeObj = {

	clickScroll : function() {
		var aA = $(".nav a");
		var oDiv = $(".nav").nextAll("div");
		var iScrollTop = 0;

		$.each(aA,function(index){
			$(this).bind("click",function(){
				/*点击增加效果*/
				$(this).parent().addClass("active");
				if(index == 0){
					iScrollTop = 500;
				}else if(index == 1){
					iScrollTop = 1060;
				}else if(index == 2){
					iScrollTop = 1660;
				}else if(index == 3){
					iScrollTop = 2160;
				}

				serviceGuaranteeObj.scrollRun(iScrollTop);

			})

		});

		
	},

	scroll : function(){
		var iScrollTop = $(window).scrollTop();
		var oNav =  $(".serviceWrap .navWrap");
		if(iScrollTop >= 520){
			oNav.addClass("fixedNav");
			fixedsearch.css("top","0");
		}else{
			oNav.removeClass("fixedNav");
			fixedsearch.css("top","-115");
		};
	},

	external : function(){
		var oUrl = window.location.hash;
		var iScrollTop = 0;

		$(".navWrap li").removeClass("active");
		if(oUrl == "#quality"){
			$(".navWrap .nav01").addClass("active");
			iScrollTop = 500;
		}else if(oUrl == "#logistics"){
			$(".navWrap .nav02").addClass("active");
			iScrollTop = 1060;
		}else if(oUrl == "#price"){
			$(".navWrap .nav03").addClass("active");
			iScrollTop = 1660;
		}else if(oUrl == "#service"){
			$(".navWrap .nav04").addClass("active");
			iScrollTop = 2160;
		}

		serviceGuaranteeObj.scrollRun(iScrollTop);
		
	},

	scrollRun :function(iScrollTop){

		var oTimer = setInterval(function(){
					$(window).scrollTop(iScrollTop);
					if($(window).scrollTop() == iScrollTop ){
						clearInterval(oTimer);
					}
				},100)
	}
}
/*jQuery插件*/
;(function($){
	$.fn.extend({
		carousel : function(options){
			var defaults = {
				carouselClass : ".myCarrousel",
				prevClass : ".prev",
				nextClass : ".next"
			};

			var defaults = $.extend(defaults,options);
			

			return this.each(function(){
				var timer= null;
				var iNow = 0;
				var iLen = 0;
				var oCarousel = $(defaults.	carouselClass);
				var iWidth = oCarousel.find("li").eq(0).outerWidth();
				var oNewEle = oCarousel.find("li").eq(0).clone();
				var oPrev = $(defaults.prevClass);
				var oNext = $(defaults.nextClass);

				/*复制多一份*/
				oCarousel.append(oNewEle);
				iLen = oCarousel.find("li").length;
				oCarousel.css("width",iLen * iWidth);

				autoRun();

				/*向点击*/
				oNext.bind("click",function(){
					if(!oCarousel.is(":animated")){
						iNow ++;
						
						slide(iNow);
					}
				})

				/*左点击*/
				oPrev.bind("click",function(){
					if(!oCarousel.is(":animated")){

						iNow --;
						
						slide(iNow);
					}
				})

				function slide(index){
					
					//拉到第一个  
					//运动到第二

					if(index > iLen-1){	
						oCarousel.css("left",0); //拉到第一个  
						index=1;
						iNow=index;
					}
					//拉到最后一个
					//运动到倒数第二
					if(index <0){	
						oCarousel.css("left",-(iLen-1)*iWidth);	//拉到最后一个
						index=iLen-2;
						iNow=index;
					}
						if(!oCarousel.is(":animated")){

							oCarousel.animate({
								left : -iWidth * index
							});
						}
					
				}
				/*自动播放代码*/
				function autoRun(){
					
					timer = setInterval(function(){
						iNow ++;
						slide(iNow);
					},3000)
				}	
				/*自动播放*/
				$(this).hover(function(){
					clearInterval(timer);
				},function(){
					autoRun();
				})


			})
		}
	})
})(jQuery);

	$(function(){

		serviceGuaranteeObj.clickScroll();

		$(window).bind("scroll",serviceGuaranteeObj.scroll);

		// $(".carrouselCon").carousel();

		/*外部链接*/
		serviceGuaranteeObj.external();
	})
	

</script>