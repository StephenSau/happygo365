<?php defined('haipinlegou') or exit('Access Invalid!');?>


<div class="buyerdoneintop mb20">
  <div class="buyerdongtitle pr">
    <?php include template('member/member_submenu3');?>
  </div>
<form id="list_form" method="get">
<input type="hidden" id='act' name='act' value='store_voucher' />
<input type="hidden" id='op' name='op' value='voucher_template_inform_detail' />
  <div class="buyerdongsearch">
    <ul class="ul-buyerdongsearch">
    <!--
      <li class="li-ovflow fl">
        <p>举报时间</p>
        <span><input class="inputtxt7" type="text"></span>
        <p>-</p>
        <span><input class="inputtxt7" type="text"></span>
      </li>
      <li class="li-ovflow fl">
        <p>举报类型</p>
        <span><input class="inputtxt6" type="text"></span>
      </li>
    -->                 
      <li class="fr">
        <p>状态</p>
        <span>
          <select class="select1 font-mic" name="select_inform_state">
            <option value="0" <?php if (!$_GET['select_inform_state'] == '0'){echo 'selected=true';}?>> <?php echo $lang['inform_state_all']; ?> </option>
            <option value="1" <?php if ($_GET['select_inform_state'] == '1'){echo 'selected=true';}?>> <?php echo $lang['inform_state_unhandle'];?> </option>
            <option value="2" <?php if ($_GET['select_inform_state'] == '2'){echo 'selected=true';}?>> <?php echo $lang['inform_state_handled'];?> </option>
          </select>
        </span>
        <span><input class="inputsub4" type="submit" onclick="submit_search_form()" value="<?php echo $lang['nc_search'];?>" ></span>
      </li>
    </ul>
  </div>
</form>
</div>

<div class="buyerinform">
  <p class="p-buyerinformtitle mb20">
    <span class="span-buyerinform1">商店名称</span>
    <span class="span-buyerinform2">举报类型</span>
    <span class="span-buyerinform3">举报主题</span>
    <span class="span-buyerinform4">图片</span>
    <span class="span-buyerinform5">状态</span>
    <span class="span-buyerinform6">处理结果</span>
  </p>
  <?php if (count($output['report_list'])>0) { ?>
  <div class="buyerinformlist">
    <?php foreach($output['report_list'] as $val) { ?>
    <ul class="ul-buyerinformlist">
      <li>
        <p class="p-buyerinformtime">举报时间：<?php echo date("Y-m-d",$val['inform_datetime']);?></p>
        <div class="div-buyerinformgoods">
          <span class="span-buyerinform1">
            <a class="a-buyerinformimg" href="<?php echo ncUrl(array('act'=>'goods','goods_id'=>$val['inform_goods_id']), 'goods');?>" target="_blank"><?php echo $val['inform_goods_name']; ?></a>
          </span>
          <span class="span-buyerinform2"><?php echo $val['inform_subject_type_name'];?></span>
          <span class="span-buyerinform3"><?php echo $val['inform_subject_content'];?></span>
          <span class="span-buyerinform4">
           <?php 
            if(empty($val['inform_pic1'])&&empty($val['inform_pic2'])&&empty($val['inform_pic3'])) {
              echo $lang['inform_pic_none'];
            }
            else {
              $pic_link = SiteUrl.'/index.php?act=show_pics&type=inform&pics=';
              if(!empty($val['inform_pic1'])) {
                $pic_link .= $val['inform_pic1'].'|';
              }
              if(!empty($val['inform_pic2'])) {
                $pic_link .= $val['inform_pic2'].'|';
              }
              if(!empty($val['inform_pic3'])) {
                $pic_link .= $val['inform_pic3'].'|';
              }
              $pic_link = rtrim($pic_link,'|'); 
            }
            ?>
           <a class="a-buyerinformlook" href="<?php echo $pic_link;?>" target="_blank"><?php echo $lang['inform_pic_view'];?></a>
          </span>
          <span class="span-buyerinform5">
          <?php 
            if($val['inform_state']==='1') echo $lang['inform_state_unhandle']; 
            if($val['inform_state']==='2') echo $lang['inform_state_handled'];
          ?>
          </span>
          <span class="span-buyerinform6 spanblue">
          <?php 
              if($val['inform_handle_type']==='1') echo $lang['inform_handle_type_unuse']; 
              if($val['inform_handle_type']==='2') echo $lang['inform_handle_type_venom']; 
              if($val['inform_handle_type']==='3') echo $lang['inform_handle_type_valid']; 
          ?>
          </span>
          
        </div>
        <dl class="dl-buyerinformlist">
          <dt><?php echo $lang['inform_content'].$lang['nc_colon'];?></dt>
          <dd><?php echo $val['inform_content'];?></dd>
        </dl>
        <dl class="dl-buyerinformlist">
          <dt><?php echo $lang['inform_handle_message'].$lang['nc_colon'];?></dt>
          <dd>
            <?php 
                if(empty($val['inform_handle_message'])) {
                    echo $lang['inform_text_none'];
                }
                else {
                    echo $val['inform_handle_message'].'('.date("Y-m-d",$val['inform_handle_datetime']).')';
                }
            ?>
          </dd>
        </dl>
      </li> 
    </ul>
    <?php }?>
    <?php }else{?>
     <ul class="ul-buyerinformlist">
       <li><?php echo $lang['no_record'];?></li>
     </ul>
    <?php }?>
  </div>
</div>
<?php  if (count($output['list'])>0) { ?>
<div class="store">
  <div class="pagination-store">
  <?php echo $output['show_page'];?>
  </div>
</div>
<?php }?>
<script type="text/javascript">
$(document).ready(function(){
    $(".inform_detail").hide();
    $(".show_detail").click(function(){
        $(".inform_detail").hide();
        $(this).parents("tr").next(".inform_detail").show();
    });
    $(".close_detail").click(function(){
        $(this).parents(".inform_detail").hide();
    });
});
function submit_search_form(){
        $('#act').val('member_inform');
        $('#op').val('inform_list');
        $('#list_form').submit();
}
</script>