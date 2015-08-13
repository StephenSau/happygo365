<?php defined('haipinlegou') or exit('Access Invalid!');?>
<style>
.wrap { font-size:12px;}
.pagination{margin-top:0;overflow:hidden;}
.pagination ul {float:right;}
.tabmenu {
    display: block;
    height: 32px;
    position: relative;
    width: 100%;
}
.tabmenu .tab {
    background: url("templates/default/images/member/ncus_repeat_x.png") repeat-x scroll center -100px rgba(0, 0, 0, 0);
    height: 32px;
    padding: 0 2%;
    width: 96%;
}
.tabmenu .tab li {
    float: left;
    margin-right: 4px;
}
.tabmenu .tab .active a {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #fff;
    border-color: #aed2ff #aed2ff #fff;
    border-image: none;
    border-radius: 4px 4px 0 0;
    border-style: solid;
    border-width: 1px;
    color: #000;
    cursor: default;
    display: inline-block;
    font-weight: 700;
    height: 30px;
    line-height: 30px;
    padding: 0 10px;
}
.tabmenu .tab .active a:hover {
    cursor: default;
    text-decoration: none;
}
.tabmenu .tab .normal a {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #e8f2ff;
    border-color: #aed2ff;
    border-image: none;
    border-radius: 4px 4px 0 0;
    border-style: solid;
    border-width: 1px 1px 0;
    color: #5f718b;
    display: inline-block;
    height: 26px;
    line-height: 26px;
    margin-top: 4px;
    padding: 0 10px;
}
.tabmenu .tab .normal a:hover {
    background-color: #f9fafc;
    border-color: #c4d5e0;
    color: #498cd0;
    text-decoration: none;
}
.tabmenu .text-intro {
    color: #999;
    line-height: 20px;
    position: absolute;
    right: 5px;
    top: 5px;
    z-index: 99;
}
.search-form {
    border-bottom: 1px solid #aed2ff;
    width: 100%;
}
.search-form th {
    color: #777;
    line-height: 22px;
    padding: 10px 0;
    text-align: right;
    width: 80px;
}
.search-form td {
    padding: 10px 0;
    text-align: left;
}
.search-form select {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #fff;
    border-color: #ccc #ddd #ddd #ccc;
    border-image: none;
    border-style: solid;
    border-width: 1px;
    box-shadow: 2px 2px 1px 0 #e7e7e7 inset;
    height: 22px;
}
.search-form select:hover {
    background-color: #fff;
}
.search-form select:focus {
    background-color: #fff;
    border-color: #ccc;
    box-shadow: 1px 1px 1px 0 #e7e7e7;
}
.search-form select option {
    background-color: #fff;
    height: 20px;
    padding-left: 12px;
}
.search-form input.text {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #fff;
    border-color: #ccc #ddd #ddd #ccc;
    border-image: none;
    border-style: solid;
    border-width: 1px;
    font-family: Tahoma;
    height: 16px;
    line-height: 16px;
    padding: 1px 2px 3px 4px !important;
    width: 148px;
}
.search-form input[type="text"] {
    box-shadow: 2px 2px 1px 0 #e7e7e7 inset;
}
.search-form input[type="text"]:hover {
    background-color: #fff;
}
.search-form input[type="text"]:focus {
    background-color: #fff;
    border-color: #ccc;
    box-shadow: 1px 1px 1px 0 #e7e7e7;
}
.search-form input.submit, .search-form a.submit {
    background: url("templates/default/images/member/ncus_public.png") no-repeat scroll -150px -105px rgba(0, 0, 0, 0);
    border: 0 none;
    cursor: pointer;
    font-size: 0;
    height: 22px;
    vertical-align: middle;
    width: 60px;
}
.search-form input[type="submit"] {
    background: url("templates/default/images/member/ncus_public.png") no-repeat scroll -150px -105px rgba(0, 0, 0, 0);
    border: 0 none;
    cursor: pointer;
    font-size: 0;
    height: 22px;
    width: 60px;
}
.search-form input[type="submit"]:hover {
    background-position: -210px -105px;
}
.ncu-table-style {
    border-collapse: collapse;
    line-height: 20px;
    width: 100%;
}
.ncu-table-style thead th {
    background: url("templates/default/images/member/ncus_repeat_x.png") repeat-x scroll center -40px rgba(0, 0, 0, 0);
    border-bottom: 1px solid #c4d5e0;
    color: #5f718b;
    padding: 5px 0 6px;
    text-align: center;
}
.ncu-table-style thead td {
    background-color: #f7f7f7;
    border-top: 1px solid #eeeeee;
    color: #5f718b;
    padding-top: 5px;
}
.ncu-table-style thead td label, .ncu-table-style tfoot td label {
    color: #555;
    cursor: pointer;
    display: inline;
    float: left;
    margin-right: 10px;
}
.ncu-table-style tbody th {
    background-color: #edf5ff;
    border-top: 1px solid #aed2ff;
    padding: 4px 0;
}
.ncu-table-style tbody th a {
    color: #0579c6;
}
.ncu-table-style tbody td {
    background-color: #fff;
    padding: 12px 0;
    text-align: center;
}
.ncu-table-style tfoot td {
    background-color: #fff;
    border-top: 1px solid #c4d5e0;
    color: #5f718b;
    padding: 5px 0 6px;
}
.bd-line td {
    border-top: 1px solid #eee;
}
.norecord {
    padding: 50px 250px !important;
}
.norecord i {
    background: url("templates/default/images/member/ncus_public.png") no-repeat scroll 0 -50px rgba(0, 0, 0, 0);
    display: inline-block;
    float: left;
    height: 44px;
    margin-right: 16px;
    width: 44px;
}
.norecord span {
    display: inline-block;
    float: left;
    font-family: "Î¢ÈíÑÅºÚ";
    font-size: 1.3em;
    font-weight: 700;
    line-height: 44px;
    text-align: left;
    width: 250px;
}
.norecord a {
    clear: both;
    display: block;
    float: left;
    margin-top: 10px;
}
.order tbody tr td.sep-row {
    border: 0 none;
    height: 12px;
    padding: 0;
}
.order tbody tr:hover td.sep-row {
    background-color: #fff;
    border: 0 none;
}
.order tbody tr th {
    border: 1px solid #c4d5e0;
}
.order tbody tr td {
    border-bottom: 1px solid #c4d5e0;
    vertical-align: top;
}
.order tbody tr td.bdl {
    border-left: 1px solid #c4d5e0;
}
.order tbody tr td.bdr {
    border-right: 1px solid #c4d5e0;
}
.order .norecord {
    border-bottom: 0 none !important;
}
.order a.snsshare-btn {
    background-color: #f8fbfe;
    border: 1px solid #80b8d2;
    border-radius: 4px;
    float: right;
    line-height: 15px;
    margin-right: 16px;
    padding: 2px 3px;
}
.order a.snsshare-btn i {
    background: url("templates/default/images/member/ncus_public.png") no-repeat scroll -120px -406px rgba(0, 0, 0, 0);
    float: left;
    height: 15px;
    width: 15px;
}
.order a.snsshare-btn h5 {
    color: #80b8d2;
    float: left;
    line-height: 15px;
    margin-left: 1px;
    text-decoration: none;
}
.order .buyer {
    color: #555;
    display: block;
    position: relative;
}
.order .buyer-info {
    display: none;
}
.order .buyer:hover .buyer-info {
    background-color: #fff9d4;
    border: 1px solid #fec500;
    border-radius: 5px;
    display: block;
    left: 90px;
    padding: 4px;
    position: absolute;
    top: -40px;
    z-index: 8;
}
.order .buyer-info em {
    background: url("templates/default/images/member/ncus_public.png") no-repeat scroll -173px -407px rgba(0, 0, 0, 0);
    height: 14px;
    left: -8px;
    position: absolute;
    top: 37px;
    width: 8px;
    z-index: 9;
}
.order .buyer-info .con {
    background: none repeat scroll 0 0 #fff;
    display: block;
    overflow: hidden;
    padding: 5px;
}
.order .buyer-info h3 {
    color: #c33700;
    font-size: 1em;
    font-weight: 700;
    overflow: hidden;
    padding: 5px 0;
}
.order .buyer-info h3 i {
    background: url("templates/default/images/member/ncus_public.png") no-repeat scroll -143px -408px rgba(0, 0, 0, 0);
    float: left;
    height: 11px;
    margin: 5px 5px 4px 12px;
    width: 17px;
}
.order .buyer-info h3 span {
    float: left;
}
.order .buyer-info dl {
    clear: both;
    color: #777;
    overflow: hidden;
    padding: 2px 0;
    width: 220px;
}
.order .buyer-info dt {
    float: left;
    text-align: right;
    width: 80px;
}
.order .buyer-info dd {
    float: left;
    text-align: left;
    width: 140px;
}
.ncu-order-view {
    background: none repeat scroll 0 0 #fff;
    border-radius: 4px;
    overflow: hidden;
    padding: 10px 20px;
}
.ncu-order-view h2 {
    border-bottom: 1px solid #c4d5e0;
    color: #498cd0;
    font-family: "Î¢ÈíÑÅºÚ";
    font-size: 20px;
    height: 40px;
    line-height: 40px;
    padding-left: 10px;
}
.ncu-order-view h3 {
    background-color: #f9fafc;
    border: 1px solid #c4d5e0;
    box-shadow: 1px 1px 0 #fff inset;
    color: #0579c6;
    font-family: "Î¢ÈíÑÅºÚ";
    font-size: 1.2em;
    overflow: hidden;
    padding: 8px 0 8px 12px;
}
.ncu-order-view h4 {
    border-bottom: 1px dashed #e7e7e7;
    color: #555;
    font-weight: 700;
    padding: 6px 0 6px 24px;
}
.ncu-order-view dl {
    overflow: hidden;
    padding: 10px 1px;
}
.ncu-order-view dt {
    color: #5f718b;
    float: left;
    padding: 6px 0;
    text-align: right;
    width: 9%;
}
.ncu-order-view dd {
    color: #888;
    float: left;
    padding: 6px 0;
    width: 24%;
}
.ncu-order-view dd strong {
    color: #fe4e02;
}
.ncu-order-view input[type="submit"], .ncu-order-view .submit {
    background: url("templates/default/images/member/ncus_public.png") no-repeat scroll 0 -110px rgba(0, 0, 0, 0);
    border: 0 none;
    border-radius: 4px;
    box-shadow: 1px 1px 0 rgba(0, 0, 0, 0.1);
    color: #555;
    cursor: pointer;
    font-weight: 700;
    height: 34px;
    width: 120px;
}
.ncu-order-view input[type="submit"]:hover, .ncu-order-view .submit:hover {
    background-position: 0 -150px;
    box-shadow: none;
    color: #000;
}
.ncu-order-view .upload-appeal-pic {
    margin-left: 28px;
    padding: 5px;
}
.ncu-order-view .upload-appeal-pic p {
    padding: 5px;
}
.order_detail_list {
    clear: both;
    color: #656565;
    list-style: outside none none;
}
.order_detail_list li {
    border-top: 1px solid #efefef;
    padding: 8px 10px;
}
.ncu-order-view .log-list {
    color: #666;
    list-style: outside none none;
    padding: 5px 10px;
}
.ncu-order-view .log-list li {
    margin: 8px 0;
}
.ncu-order-view .log-list li .operator {
    color: #fe5400;
    font-weight: 700;
    margin-right: 5px;
}
.ncu-order-view .log-list li .log-time {
    font-style: italic;
    font-weight: 700;
    margin: 0 5px;
}
.ncu-order-view .log-list li .order-status {
    font-style: italic;
    font-weight: 700;
    margin: 0 5px;
}
.ncu-order-view .log-list li .reason {
    font-style: italic;
    font-weight: bold;
    margin: 0 5px;
}
.progress {
    color: #999;
    line-height: 20px;
    margin: 0;
    padding: 10px 20px;
}
.progress li.text {
    background-image: none;
    border: 1px dashed #e7e7e7;
    float: left;
    font-size: 1.2em;
    margin: 10px;
    padding: 10px 20px;
}
.progress li.next-step {
    background: url("templates/default/images/member/ncus_public.png") no-repeat scroll -283px -382px rgba(0, 0, 0, 0);
    float: left;
    height: 16px;
    margin: 24px 0;
    padding: 0;
    width: 16px;
}
.progress li.red {
    color: red;
    font-weight: 600;
}
.progress li.green {
    color: green;
    font-weight: 600;
}
.ncu-order-view .btn {
    background: url("templates/default/images/member/btn.gif") no-repeat scroll 0 -335px rgba(0, 0, 0, 0);
    border: 0 none;
    color: #3e3e3c;
    font-size: 12px;
    height: 22px;
    width: 53px;
}
.ncu-order-view .btn1 {
    background: url("templates/default/images/member/btn.gif") no-repeat scroll 0 -365px rgba(0, 0, 0, 0);
    border: 0 none;
    color: #3e3e3c;
    font-size: 12px;
    height: 22px;
    width: 68px;
}
.ncu-btn1 {
    background: url("templates/default/images/member/ncus_public.png") no-repeat scroll -150px -61px rgba(0, 0, 0, 0);
    cursor: pointer;
    float: left;
    height: 22px;
    margin-right: 8px;
}
.ncu-btn1 span {
    background: url("templates/default/images/member/ncus_public.png") no-repeat scroll right -61px rgba(0, 0, 0, 0);
    color: #000;
    float: left;
    margin-left: 6px;
    padding: 1px 18px 1px 12px;
}
.ncu-btn1:hover {
    background-position: -150px -83px;
    text-decoration: none;
}
.ncu-btn1:hover span {
    background-position: right -83px;
    color: #0579c6;
}
a.ncu-btn2 {
    background: url("templates/default/images/member/ncus_repeat_x.png") repeat-x scroll center -73px rgba(0, 0, 0, 0);
    border: 1px solid #8d8d8d;
    border-radius: 4px;
    box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
    color: #555;
    display: inline-block;
    height: 20px;
    line-height: 19px;
    padding-left: 10px;
    padding-right: 10px;
    text-align: center;
}
a.ncu-btn2:hover {
    background-position: 0 -40px;
    border-color: #aed2ff;
    box-shadow: none;
    color: #498cd0;
    text-decoration: none;
}
a.ncu-btn3 {
    background: url("templates/default/images/member/ncus_repeat_x.png") repeat-x scroll center -73px rgba(0, 0, 0, 0);
    border: 1px solid #8d8d8d;
    border-radius: 4px;
    box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
    color: #555;
    display: block;
    font-weight: 700;
    height: 20px;
    line-height: 18px;
    padding: 3px 12px;
    position: absolute;
    right: 0;
    top: -2px;
    z-index: 1;
}
a.ncu-btn3:hover {
    background-position: 0 -40px;
    border-color: #aed2ff;
    box-shadow: none;
    color: #000;
}
a.ncu-btn4 {
    background: url("templates/default/images/member/ncus_repeat_x.png") repeat-x scroll center -73px rgba(0, 0, 0, 0);
    border: 1px solid #8d8d8d;
    border-radius: 4px;
    box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
    color: #555;
    display: block;
    font-weight: 700;
    height: 20px;
    line-height: 18px;
    padding: 3px 12px;
    text-align: center;
}
a.ncu-btn4:hover {
    background-position: 0 -40px;
    border-color: #aed2ff;
    box-shadow: none;
    color: #06c;
    text-decoration: none;
}
a.ncu-btn5 {
    background: url("templates/default/images/member/ncus_public.png") no-repeat scroll 0 -110px rgba(0, 0, 0, 0);
    border: 0 none;
    border-radius: 4px;
    box-shadow: 1px 1px 0 rgba(0, 0, 0, 0.1);
    color: #555;
    cursor: pointer;
    display: inline-block;
    font-weight: 700;
    height: 34px;
    line-height: 32px;
    text-align: center;
    width: 120px;
}
a.ncu-btn5:hover {
    background-position: 0 -150px;
    box-shadow: none;
    color: #000;
    text-decoration: none;
}
a.ncu-btn6 {
    background: url("templates/default/images/member/ncus_repeat_x.png") repeat-x scroll center -140px rgba(0, 0, 0, 0);
    border: 1px solid #64a8e1;
    border-radius: 4px;
    box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
    color: #fff;
    display: inline-block;
    font-weight: 600;
    height: 20px;
    line-height: 19px;
    padding-left: 10px;
    padding-right: 10px;
    text-align: center;
}
a.ncu-btn6:hover {
    background-position: 0 -40px;
    border-color: #aed2ff;
    box-shadow: none;
    color: #498cd0;
    text-decoration: none;
}
a.ncu-btn7 {
    background: url("templates/default/images/member/ncus_repeat_x.png") repeat-x scroll center -250px rgba(0, 0, 0, 0);
    border: 1px solid #71a133;
    border-radius: 4px;
    box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
    color: #fff;
    display: inline-block;
    font-weight: 600;
    height: 20px;
    line-height: 19px;
    padding-left: 10px;
    padding-right: 10px;
    text-align: center;
}
a.ncu-btn7:hover {
    background-position: 0 -40px;
    border-color: #71a133;
    box-shadow: none;
    color: #4a6923;
    text-decoration: none;
}


</style>

<div class="wrap">

  <div class="tabmenu">

    <?php include template('member/member_submenu');?>

  </div>

  <form id="voucher_list_form" method="get">

    <table class="search-form">

      <input type="hidden" id='act' name='act' value='member_voucher' />

      <input type="hidden" id='op' name='op' value='voucher_list' />

      <tr>

        <td style="width:85%">&nbsp;</td>

        <td class="w100 tr"><select name="select_detail_state">

            <option value="0" <?php if (!$_GET['select_detail_state'] == '0'){echo 'selected=true';}?>> <?php echo $lang['voucher_voucher_state']; ?> </option>

            <?php if (!empty($output['voucherstate_arr'])){?>

            <?php foreach ($output['voucherstate_arr'] as $k=>$v){?>

            	<option value="<?php echo $v[0];?>" <?php if ($_GET['select_detail_state'] == $v[0]){echo 'selected=true';}?>> <?php echo $v[1];?> </option>

            <?php }?>

            <?php }?>

          </select></td>

        <td class="w90 tc"><input type="submit" class="submit" onclick="submit_search_form()" value="<?php echo $lang['nc_search'];?>" /></td>

      </tr>

    </table>

  </form>

  <table class="ncu-table-style">

    <thead>

      <tr>

      	<th></th>

      	<th class="w150"><?php echo $lang['voucher_voucher_code'];?></th>

        <th class="w60"><?php echo $lang['voucher_voucher_price'];?></th>

        <th><?php echo $lang['voucher_voucher_storename'];?></th>

        <th class="w150"><?php echo $lang['voucher_voucher_indate'];?></th>

        <th class="w150"><?php echo $lang['voucher_voucher_usecondition'];?></th>

        <th class="w60"><?php echo $lang['voucher_voucher_state'];?></th>

        <th class="w60"><?php echo $lang['nc_handle'];?></th>

      </tr>

    </thead>

    <tbody>

      <?php  if (count($output['list'])>0) { ?>

      <?php foreach($output['list'] as $val) { ?>

      <tr class="bd-line">

      	<td><img src="<?php echo $val['voucher_t_customimg'];?>" /></td>

      	<td><?php echo $val['voucher_code'];?></td>

        <td><?php echo $val['voucher_price'].$lang['currency_zh'];?></td>

        <td class="goods-price"><a href="<?php echo ncUrl(array('act'=>'index'))?>" target="_blank"><?php echo $val['store_name'];?></a></td>

        <td class="goods-time"><?php echo date("Y-m-d",$val['voucher_start_date']).'~'.date("Y-m-d",$val['voucher_end_date']);?></td>

        <td><?php echo $lang['voucher_voucher_usecondition_desc'].$val['voucher_limit'].$lang['currency_zh'];?></td>

        <td><?php foreach ((array)$output['voucherstate_arr'] as $k=>$v){?>

        		<?php if ($v[0] == $val['voucher_state']){ echo $v[1];break;}?>

        	<?php }?></td>

        <td>

        	<?php if ($val['voucher_state'] == '1'){?>

        	<a href="<?php echo ncUrl(array('act'=>'index'))?>" target="_blank"><?php echo $lang['voucher_voucher_readytouse'];?></a>

        	<?php } elseif ($val['voucher_state'] == '2'){?>

        		<a href="index.php"><?php echo $lang['voucher_voucher_vieworder'];?></a>

        		<?php }?>

        	</td>

      </tr>

      <?php }?>

    </tbody>

    <?php } else { ?>

    <tbody>

      <tr>

        <td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>

      </tr>

    </tbody>

    <?php } ?>

    <?php  if (count($output['list'])>0) { ?>

    <tfoot>

      <tr>

        <td colspan="20"><div class="pagination"><?php echo $output['show_page'];?></div></td>

      </tr>

    </tfoot>

    <?php } ?>

  </table>

</div>