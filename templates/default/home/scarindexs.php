<?php defined('haipinlegou') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATES_PATH;?>/css/1.css">
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/car_style.js" charset="utf-8"></script>
<!--图片轮播的背景start-->    
        <div id="bd" >     
            <div class="layout ">
            			
                       <div class="tshop-pbsm-shop-self-defined">
                           
                                     <div style="height:740px;">
                                         <div class="footer-more-trigger" style="left:50%;top:auto;border:none;padding:0;"> 
                                             <div class="footer-more-trigger" style="left:-1000px;top:auto;border:none;padding:0;">
                                               
                                             </div> 
                                         </div> 
                                     </div>
                          
                    	</div>                 
             </div>
                <!-- 测试start  -->
                   <div class="containner_carousel">
						<div class="prev"><a href="javascript:void(0)"><</a></div>
                 
                            <div class="show">
                                    <div class="v_cont">
                                        <ul>
                                         <?php if(isset($output['goods_class']) && !empty($output['goods_class']) ) {?>
												<?php foreach ($output['goods_class'] as $key=>$val) {?>
                                        
                                            <li onclick="selectbrand('<?php echo $val['gc_id']?>','xilie');"  index="<?php echo $key?>">	
                                                <p id="<?php echo $key?>" <?php if($key % 2 !=0 && $key !=0){?>class="font_img2"<?php }?>><img src="<?php if(!empty($val['gc_image'])){ echo SiteUrl.'/'.ATTACH_GC.'/'.$val['gc_image'];}else{ echo SiteUrl.'/templates/'.TPL_NAME.'/images/default_brand_image.gif';}?>">
                                                </p>  
                                            </li>
                                            	<?php }?>
										<?php }?>
                                      </ul>
                                    </div>
                             </div>                        
						<div class="next"><a href="javascript:void(0)">></a></div>
				<div class="sel-car">
					<div class="sel-car_l">
						<div class="sesd">
								<div class="sele-car">选择你想的车型</div>
								<div class="sele-car-r">
									 <?php if(isset($output['goods_class']) && !empty($output['goods_class']) ) {?>
												<?php foreach ($output['goods_class'] as $key=>$val) {?>
									<a ><?php echo $val['gc_name']?>&nbsp;></a>
                                        	<?php }?>
										<?php }?>
								</div>
						</div>
						<select id="xilie" onChange="selectbrand(this.value,'chexing')">
						 <option  value=''>请选择..</option>
						</select>
					</div>
					<div class="sel-car_r">
						<select id="chexing" onChange="selectbrand(this.value,'jump')"> 
						  <option  value=''>请选择..</option>
						</select>
					</div>
				</div>
                   </div>   
                <!-- 测试end--> 
				
        </div>

<!--图片轮播的背景end-->

<script language="javascript">
	function selectbrand(id,type){
	
		if(type=='jump'){
			if(id>0){
				window.location='index.php?act=search&cate_id='+id;
			}
			return false;
		}
	
		if(type=='xilie'){
			$("#chexing").html('<option  value="">请选择..</option>');
		}
		var str=' <option  value="">请选择..</option>';
		$.getJSON('index.php?act=car&op=ajax_goods_class',{
		
				'gc_id':id
		},function(data){
				for (i=0; i<data.length; i++) {
					str+='<option  value="'+data[i].gc_id+'">'+data[i].gc_name+'</option>';
				}
				$("#"+type).html(str);
			}
		
		);
	}
</script>