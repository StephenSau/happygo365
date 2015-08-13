<?php defined('haipinlegou') or exit('Access Invalid!');?>
<link rel="stylesheet" href="<?php echo TEMPLATES_PATH;?>/css/1.css"/>

<script language="javascript">
$(function(){
	/*======next======*/
				$(".next a").click(function(){ nextscroll() });

				function nextscroll(){

						var vcon = $(".v_cont ");
						var offset = ($(".v_cont li").width())*-1;
						
						vcon.animate({left:offset},"slow",function(){

							var firstItem = $(".v_cont ul li").first();
						vcon.find("ul").append(firstItem);
							 $(this).css("left","0px");
							
			
							
						});  
					
				};



				//setInterval(nextscroll,2000)
				 
				/*======prev======*/
				$(".prev a").click(function(){
				
										var vcon = $(".v_cont ");
										var offset = ($(".v_cont li").width()*-1);
				
										var lastItem = $(".v_cont ul li").last();
										vcon.find("ul").prepend(lastItem);
										vcon.css("left",offset);
										vcon.animate({left:"0px"},"slow",function(){
											 circle()
										})

				 });
				 
				 /*======0======*/ 
				   <?php foreach($brandlist as $key=>$val){?>
				$("#<?php echo $key?> img").mouseover(function(){
								
								$(this).attr("src","<?php echo  SiteUrl.'/'.ATTACH_BRANDSORT.'/'.$val['sort_pic']?>");
								<?php if ($key==0){?>
								$(".panel").fadeTo("slow",0.5);
								<?php }?>
				});
				
				$("#<?php echo $key?> img").mouseout(function(){
								$(this).attr("src","<?php echo  SiteUrl.'/'.ATTACH_BRAND.'/'.$val['brand_pic']?>");
								
				});
				
			<?php }?>
								
				
				$("a").mouseover(function(){
						$(this).parent().siblings().css("color","#F00");
					});
				$("a").mouseout(function(){
						$(this).parent().siblings().css("color","#FFF");
					});
		 })

</script>

<div style="width:600px; height:4px; background:#fff; " ></div>
<!--图片轮播的背景start-->     
        <div id="bd"  >     
            <div class="layout "  >
            			
                       <div class="tshop-pbsm-shop-self-defined"  >
                           
                                     <div style="height:570px;" >
                                         <div class="footer-more-trigger" style="left:50%;top:auto;border:none;padding:0;"> 
                                             <div class="footer-more-trigger" style="left:-1500px;top:auto;border:none;padding:0;" >
                                               
                                             </div> 
                                         </div> 
                                     </div>
                          
                    	</div>
                  
             </div>
                <!-- 测试start  -->
                   <div class="containner_carousel" >
						<div class="prev"><a href="javascript:void(0)"><</a></div>
                 
                            <div class="show">
                                    <div class="v_cont">
                                        <ul>
                                        
                                        <?php foreach($brandlist as $key=>$val){?>
										<li index="<?php echo $key?>">
                                                <p <?php if($key % 2 !=0 && $key !=0){?>class="font_img2"<?php }?> id="<?php echo $key?>" ><a href="index.php?act=search&cate_id=390&b_id=<?php echo $val['brand_id']?>" target="_blank"><img src="<?php echo SiteUrl.'/'.ATTACH_BRAND.'/'.$val['brand_pic'];?>"></a>
                                                </p>
                                              <p <?php if($key % 2 !=0 && $key !=0){?>class="font_zh2"<?php }else{?>class="font_zh"<?php }?> class="font_zh"><a><?php echo $val['brand_name']?></a></p>
                                              <!--  <p class="font_en"><a>FORO</a></p>-->
                                            </li>
                                         <?php }?>  

                                      </ul>
                                    </div>
                                </div>
                         
						<div class="next"><a href="javascript:void(0)">></a></div>
                   </div>   
                <!-- 测试end--> 
        </div>

<!--图片轮播的背景end-->
