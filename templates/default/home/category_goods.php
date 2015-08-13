<div class="content">
	<div class="w1210">
		<div class="goodsclassifyall pdt30 font-mic">
		<?php if(!empty($output['aa']) && is_array($output['aa'])){?>
			<div class="goodsclassifyallleft fl">			
				<?php foreach($output['aa'] as $key=>$gc_list){?>
				<?php if ($gc_list['gc_parent_id'] != '0') break;?>					
				<div class="goodsclassifyalllist">
					<div class="goodsclassifyalllist-t">
						<h3><?php echo $gc_list['gc_name'];?></h3>
					</div>
					<div class="goodsclassifyalllist-m">
						<?php if($gc_list['child'] != ''){?>
						<?php foreach(explode(',',$gc_list['child']) as $chikey=>$chivalue){?>
						<dl class="dl-nobt">
							<dt><?php echo $output['gc_list'][$chivalue]['gc_name'];?></dt>
							<dd>
							<?php 

								if($output['show_goods_class'][$chivalue]['child'] != ''){$num=1;

								foreach(explode(',',$output['show_goods_class'][$chivalue]['child']) as $key){

								?>
								<em><a href="index.php?act=search&cate_id=<?php echo $key;?>" title="<?php echo $output['show_goods_class'][$key]['gc_name']?>" ><?php echo $output['show_goods_class'][$key]['gc_name']?></a></em>
							<?php }?>
							<?php }?>
							</dd>
						</dl>
						<?php }?>
						<?php }?>
					</div>
				</div>
				<?php }?>
			</div>
			<?php }?>
			
			
		    <?php if(!empty($output['bb']) && is_array($output['bb'])){?>
			<div class="goodsclassifyallleft fr">			
				<?php foreach($output['bb'] as $key=>$gc_list){?>
				<?php if ($gc_list['gc_parent_id'] != '0') break;?>					
				<div class="goodsclassifyalllist">
					<div class="goodsclassifyalllist-t">
						<h3><?php echo $gc_list['gc_name'];?></h3>
					</div>
					<div class="goodsclassifyalllist-m">
						<?php if($gc_list['child'] != ''){?>
						<?php foreach(explode(',',$gc_list['child']) as $chikey=>$chivalue){?>
						<dl class="dl-nobt">
							<dt><?php echo $output['gc_list'][$chivalue]['gc_name'];?></dt>
							<dd>
							<?php 

								if($output['show_goods_class'][$chivalue]['child'] != ''){$num=1;

								foreach(explode(',',$output['show_goods_class'][$chivalue]['child']) as $key){

								?>
								<em><a href="index.php?act=search&cate_id=<?php echo $key;?>" title="<?php echo $output['show_goods_class'][$key]['gc_name']?>" ><?php echo $output['show_goods_class'][$key]['gc_name']?></a></em>
							<?php }?>
							<?php }?>
							</dd>
						</dl>
						<?php }?>
						<?php }?>
					</div>
				</div>
				<?php }?>
			</div>
			<?php }?>
			
			
			
			<!--<div class="goodsclassifyallright fr">
				<div class="goodsclassifyalllist">
					<div class="goodsclassifyalllist-t">
						<h3>手机</h3>
					</div>
					<div class="goodsclassifyalllist-m">
						<dl class="dl-nobt">
							<dt>电子书刊</dt>
							<dd>
								<em><a href="">电子书</a></em>
								<em><a href="">网络原著</a></em>
								<em><a href="">数字杂志</a></em>
								<em><a href="">多媒体图书</a></em>
							</dd>
						</dl>
					</div>
				</div>
				</div>
			</div>p-->

		</div>
		
	</div>
</div>