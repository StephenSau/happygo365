<?php defined('haipinlegou') or exit('Access Invalid!');?>
<div class="eject_con">

  <div class="adds"style="height:400px; overflow:auto">

    <div id="warning"></div>

   <?php foreach ($output['goods_records_list'] as $key => $val){?>
    
      <dl>
 			<input class="text w300 goods_records_name" readonly="readonly" type="text"  name="area_info"   value="<?php echo $val['goods_name'];?>"/>
        
 		  <input class="text w300 goods_article_num" type="hidden" name="area_info" value="<?php echo $val['goods_article_num'];?>"/>
        <dd>

          <p>

          </p>

        </dd>

      </dl>
<?php 
}

?>

  </div>

</div>
<script type="text/javascript">
var SITE_URL = "<?php echo SiteUrl; ?>";

$(document).ready(function(){



	$(".goods_records_name").bind('click',function(i){
			
		var huonum=$(".goods_article_num").eq($(this).index(".goods_records_name")).val();
		
		var name='<?php echo $name; ?>';
		if(name){
		$("input[name='"+name+"']").val(huonum);
			
				DialogManager.close(name);
		}
	

	});

	

	

	

	



});


</script>

