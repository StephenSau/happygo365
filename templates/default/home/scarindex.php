<link href="<?php echo TEMPLATES_PATH;?>/css/haipin2/seller-center.css" rel="stylesheet" type="text/css">
<link href="<?php echo TEMPLATES_PATH;?>/css/member_store.css" rel="stylesheet" type="text/css">
<style type="text/css">
body {
    background: none repeat scroll 0 0 #fff;
    color: #333333;
    font: 12px/1 "Microsoft YaHei","Lucida Grande","Lucida Sans Unicode",Tahoma,Helvetica,Arial,sans-serif;
}
.tijiao{
	background: url("../images/member/ncus_public.png") no-repeat scroll 0 -110px rgba(0, 0, 0, 0);
    border: 0 none;
    border-radius: 4px;
    box-shadow: 1px 1px 0 rgba(0, 0, 0, 0.1);
    color: #555;
    cursor: pointer;
    font-weight: 700;
    height: 34px;
    width: 120px;
	 background-color: #0570c2;
    color: #fff;
    display: block;
    height: 40px;
    line-height: 40px;
    margin: 40px auto;
    text-align: center;
    width: 280px;
}
.content .classify-item{ height:320px; }
.content .margin_0_auto { margin:0 auto; height:300px; width:1000px; padding-left:110px;}
.content .goods-classify { height:490px;}
</style>
<div class="content">

    	<div class="w1210 font-mic">
        	<div class="w1210 font-mic">
            <div  style="height:20px; line-height:20px;" class="crumbs01">
                <span>您当前位置：</span>
                <a href="index.php?act=car&op=sindex">汽车馆</a> <span>></span>选择配置
            </div>
			<div class="goods-release-nav">

            	<ul class="grn-step">

                	<li>

                        <div class="grn-icon active step-one fl">

                            <span>STEP 1</span>

                            <p>选择品牌</p>

                        </div>

                        <i class="active"></i>

                    </li>

                    <li>

                        <div class="grn-icon step-two fl">

                            <span>STEP 2</span>

                            <p>选择系列</p>

                        </div>

                        <i class="active"></i>

                    </li>

                    <li>

                        <div class="grn-icon step-three fl">

                            <span>STEP 3</span>

                            <p>选择车型</p>

                        </div>

                    </li>

                </ul>

            </div>

		

			

            



            <div class="goods-classify" id="class_div">


<div class="margin_0_auto">
            	<div class="classify-item">

                    <dl class="goods-list">

                        <dd class="unfold">

                        	<div id="class_div_1" class="title category_list">

							<ul>

							  <?php if(isset($output['goods_class']) && !empty($output['goods_class']) ) {?>

							  <?php foreach ($output['goods_class'] as $val) {?>

							  <li class="" onclick="selClass(this);" id="<?php echo $val['gc_id'];?>|1|<?php echo $val['type_id'];?>"> <a class="" href="javascript:void(0)"><span class="has_leaf"><?php echo $val['gc_name'];?></span></a> </li>

							  <?php }?>

							  <?php }?>

							</ul>			

							</div>

                        </dd>

                    </dl>

                </div>

                <div class="classify-item">

                    <dl class="goods-list">

                    	<dd class="unfold ss">

						<div id="class_div_2" class="title category_list">

                            <ul>

                            </ul>

						</div>

                        </dd>

                    </dl>

                </div>

                <div class="classify-item">

                    <dl class="goods-list">

                    	<dd class="unfold ss">

						<div id="class_div_3" class="title category_list">

                            <ul>

                            </ul>

						</div>

                        </dd>

                    </dl>

                </div>

                <!--<div class="classify-item mr-0">

                    <dl class="goods-list">

                    	<dd class="unfold ss">

						<div  id="class_div_4" class="title category_list">

                            <ul>

                            </ul>

						</div>

                        </dd>

                    </dl>

                </div>-->
                
							<!--显示选择的商品-->
</div>
                <div class="selected-goods"   style="display: block; clear:both;">

                	<p id="commodityspan"  style="color:#F00;">请选择品牌->系列->车型</p>

                	<p id="commoditydt" style="display: none;">您选择的是：</p>

                    <div  id="commoditydd" class="selected"; style="display:inline;float:left"></div>

                </div>

				<!--显示选择的商品-->

                <div>
				  <input name="class_id" id="class_id" value="" type="hidden" />
				<input  type="button" onclick="goclass()" class="tijiao" value="提交" />
				</div>

            </div>

        </div>

    </div>

<script type="text/javascript">
function goclass(){
	var classid=$("#class_id").val();
	if(classid){
		window.location='index.php?act=search&cate_id='+classid;
	}
	
}

</script> 

<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.select.car.js"></script>