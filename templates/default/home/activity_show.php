<div class="content">
		<div class="w1210">
			<div class="activityall pdt30">
				<div class="activityallright fr">
					<div class="activityalltj font-mic">
						<h3>热门推荐</h3>
						<ul class="ul-activityalltj">
							<?php foreach($output['asort'] as $k=>$v){?>
							<li id="li-activityalltj" <?php if($k == 0){ echo "class='li-activityalltj'";}?> >
								<a class="a-activityalltj" href="index.php?act=activity&op=detailed&det_id=<?php echo $v['activity_id']?>"><?php echo $v['activity_title'];?></a>
								<p><a href="index.php?act=activity&op=detailed&det_id=<?php echo $v['activity_id']?>"><img src="<?php echo SiteUrl."/".ATTACH_ACTIVITY."/".$v['activity_banner']?>" width="390px" height="160px"></a></p>
							</li>
							<?php }?>
						</ul>
					</div>
				</div>
				<div class="activityallleft">
					<ul class="ul-activityallleft font-mic">
					<?php if(is_array($output['activity']) && !empty($output['activity'])){$act = 1;?>
						<?php foreach($output['activity'] as $actey => $acvalue){if($act < 3){?>
						<li>
							<a href="index.php?act=activity&op=detailed&det_id=<?php echo $acvalue['activity_id']?>"><img src="<?php if(!empty($acvalue['activity_banner'])){echo SiteUrl."/".ATTACH_ACTIVITY."/".$acvalue['activity_banner'];}else{echo TEMPLATES_PATH."/images/sale_banner.jpg";}?>" width="980" height="330" /></a>
							<p><?php echo $acvalue['activity_title'];?><span>有效期： <?php echo date('Y-m-d',$acvalue['activity_start_date']);?> 至 <?php echo date('Y-m-d',$acvalue['activity_end_date']);?></span></p>
						</li>
						<?php }$act++;}?>
					<?php }?>
					</ul>
					
				</div>
				<div class="pages">
					<div class="pagination" style="margin:0 auto"><?php echo $output['show_page'];?></div>
				</div>
			</div>
			
		</div>
	</div>
	<script type="text/javascript">
	$('.ul-activityalltj li').mouseover(function(){
	$(this).find('p').css("display","block");
	$(this).siblings().find('p').css("display","none");
	})
	</script>
