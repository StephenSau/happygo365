<div class="content">
	<div class="w1210">

		<div class="brandallitem pdt40">
			<div class="brandallitemtitle pr">
				
				<h2>推荐品牌</h2>
				
				<p class="p-brandallitempage"><span class="font-mic"><b>1</b>/5</span><a class="a-brandallitempagepre" href=""><</a><a class="a-brandallitempagenet" href="">></a></p>
			</div>
			<div class="brandallitemin">
				
				<?php 
				$i = 0;
				foreach($output['recommend'] as $key=>$v){
					$i++;
					?>
					<div class="brandalliteminlist <?php echo $i==1?'nomargin-l':''?>">
						<h3 class="brandalliteminlisttitle"><?php echo $key;?></h3>
						<div class="brandalliteminlisttop pr">
							<a href="">
								<img src="<?php if(!empty($brand_r[$v['0']]['brand_pic'])){echo ATTACH_BRAND.'/'.$brand_r[$v['0']]['brand_pic'];}else{echo TEMPLATES_PATH.'/images/default_brand_image.gif';}?>" onload="javascript:DrawImage(this,230,145);" alt=""  width="230" height="145" />
							</a>
							<p><?php echo $brand_r[$v['0']]['brand_name']?></p>
						</div>
						<div class="brandalliteminlistbtm">
							<ul class="ul-brandalliteminlistbtm">
								<?php foreach($v as $key=>$vl){

									?>
									<li>
										<p>
											<img src="<?php if(!empty($brand_r[$vl]['sort_pic'])){echo ATTACH_BRANDSORT.'/'.$brand_r[$vl]['sort_pic'];}else{echo TEMPLATES_PATH.'/images/default_brand_image.gif';}?>" onload="javascript:DrawImage(this,115,56);" alt=""  width="115" height="56" />
										</p>
										<a href=""><?php echo $brand_r[$vl]['brand_name'];?></a>
									</li>	
									<?php }?>								
								</ul>
							</div>
						</div>	

						<?php }?>					
					</div>
				</div>	

				<?php foreach($output['brand_list'] as $key =>$v ){?>
				<div class="brandallitem pdt40">
					<div class="brandallitemtitle pr">

						<h2><?php echo $v['gc_name']?></h2>
						
						<p class="p-brandallitempage"><span class="font-mic"><b>1</b>/5</span><a class="a-brandallitempagepre" href=""><</a><a class="a-brandallitempagenet" href="">></a></p>
					</div>
					<div class="brandallitemin">


						<div class="brandalliteminlist nomargin-l">
							<h3 class="brandalliteminlisttitle"></h3>
							<div class="brandalliteminlisttop pr">
								<a href="">
									<img src="" onload="javascript:DrawImage(this,230,145);" alt=""  width="230" height="145" />
								</a>
								<p></p>
							</div>
							<div class="brandalliteminlistbtm">
								<ul class="ul-brandalliteminlistbtm">

									<li>
										<p>
											<img src="<?php //if(!empty($brand_r[$vl]['sort_pic'])){echo ATTACH_BRANDSORT.'/'.$brand_r[$vl]['sort_pic'];}else{echo TEMPLATES_PATH.'/images/default_brand_image.gif';}?>" onload="javascript:DrawImage(this,115,56);" alt=""  width="115" height="56" />
										</p>
										<a href=""><?php// echo $brand_r[$vl]['brand_name'];?></a>
									</li>	

								</ul>
							</div>
						</div>	


					</div>
				</div>	
				<?php }?>	
			</div>
		</div>




		







		

