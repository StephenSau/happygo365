<?php

defined('haipinlegou') or exit('Access Invalid!');

class storeControl extends BaseMemberStoreControl
{

    const EXPORT_SIZE = 5000;

    public function __construct()
    {
        parent::__construct();
        Language::read('member_store_index');
    }

    public function indexOp()
    {
        Language::read('member_home_index');
        $lang = Language::getLangContent();

        if (C('payment')) {
            $model_payment = Model('payment');
            if (file_exists(BasePath . DS . 'api' . DS . 'payment' . DS . 'payment.inc.php')) {
                require_once(BasePath . DS . 'api' . DS . 'payment' . DS . 'payment.inc.php');
            }
            $payment_list = $model_payment->getPaymentList();
            if (strtoupper(CHARSET) == 'GBK') {
                $payment_list = Language::getGBK($payment_list);
            }
            $payment_list = $model_payment->checkinstallPayment($payment_list);
            $store_payment = array();
            if (is_array($payment_list) && !empty($payment_list)) {
                foreach ($payment_list as $key => $val) {
                    if ($val['install'] == 1 && @in_array($val['code'], $payment_inc)) $store_payment[] = $val;
                }
            }
            Tpl::output('store_payment', $store_payment);
        }

        $model_store = Model('store');
        $store_info = $model_store->shopStore(array('store_id' => $_SESSION['store_id']));

        if ($store_info['store_end_time'] != '') {
            $store_info['store_end_time'] = number_format(($store_info['store_end_time'] - time()) / (3600 * 24), 2);
        }
        $store_info['store_end_time'] = $store_info['store_end_time'] ? ($store_info['store_end_time'] <= 0) ? $lang['store_end'] : $store_info['store_end_time'] . $lang['store_day'] : $lang['store_no_limit'];

        $store_info['credit_arr'] = getCreditArr($store_info['store_credit']);

        $hash_key = $_SESSION['store_id'];
        if ($_cache = rcache($hash_key, 'store')) {
            foreach ($_cache as $k => $v) {
                $store_info[$k] = $v;
            }
        } else {

            $store_info['store_desccredit_rate'] = @round($store_info['store_desccredit'] / 5 * 100, 2);
            $store_info['store_servicecredit_rate'] = @round($store_info['store_servicecredit'] / 5 * 100, 2);
            $store_info['store_deliverycredit_rate'] = @round($store_info['store_deliverycredit'] / 5 * 100, 2);

            $store_grade = ($setting = F('store_grade')) ? $setting : H('store_grade', true, 'file');
            $store_grade = $store_grade[$store_info['grade_id']];
            $store_info['grade_id'] = $store_grade['sg_id'];
            $store_info['grade_name'] = $store_grade['sg_name'];
            $store_info['grade_goodslimit'] = $store_grade['sg_goods_limit'];
            $store_info['grade_albumlimit'] = $store_grade['sg_album_limit'];
        }
        if (!file_exists(BasePath . DS . ATTACH_GOODS . DS . $_SESSION['store_id'])) {
            mkdir(BasePath . DS . ATTACH_GOODS . DS . $_SESSION['store_id'], 0777, true);
        }

        Tpl::output('store_info', $store_info);

        $model_goods = Model('goods');
        $model = Model();
        $add_time_to = date("Y-m-d");
        $add_time_from = date("Y-m-d", (strtotime($add_time_to) - 60 * 60 * 24 * 30));
        Tpl::output('add_time_from', $add_time_from);
        Tpl::output('add_time_to', $add_time_to);

        $member_model = Model('member');
        $member_info = $member_model->infoMember(array('member_id' => $_SESSION['member_id']));
        Tpl::output('member_info', $member_info);

        $model_article = Model('article');
        $condition = array();
        $condition['article_show'] = '1';
        $condition['ac_id'] = '1';
        $condition['order'] = 'article_sort desc,article_time desc';
        $condition['limit'] = '5';
        $show_article = $model_article->getArticleList($condition);
        //S脚部文章输出
        $list = $this->_article();
        //E脚部文章输出
        Tpl::output('show_article', $show_article);
        $phone_array = explode(',', C('site_phone'));
        Tpl::output('phone_array', $phone_array);

        Tpl::output('menu_sign', 'index');
        Tpl::showpage('home');
    }


    //S脚部文章输出
    private function _article()
    {

        if (file_exists(BasePath . '/cache/index/article.php')) {
            include(BasePath . '/cache/index/article.php');
            Tpl::output('show_article', $show_article);
            Tpl::output('article_list', $article_list);
            return;
        }
        $model_article_class = Model('article_class');
        $model_article = Model('article');
        $show_article = array();
        $article_list = array();
        $notice_class = array('notice', 'store', 'about');
        $code_array = array('member', 'store', 'payment', 'sold', 'service', 'about');
        $notice_limit = 5;
        $faq_limit = 5;

        $class_condition = array();
        $class_condition['home_index'] = 'home_index';
        $class_condition['order'] = 'ac_sort asc';
        $article_class = $model_article_class->getClassList($class_condition);
        $class_list = array();
        if (!empty($article_class) && is_array($article_class)) {
            foreach ($article_class as $key => $val) {
                $ac_code = $val['ac_code'];
                $ac_id = $val['ac_id'];
                $val['list'] = array();
                $class_list[$ac_id] = $val;
            }
        }

        $condition = array();
        $condition['article_show'] = '1';
        $condition['home_index'] = 'home_index';
        $condition['field'] = 'article.article_id,article.ac_id,article.article_url,article.article_title,article.article_time,article.article_content,article_class.ac_name,article_class.ac_parent_id';
        $condition['order'] = 'article_sort desc,article_time desc';
        $condition['limit'] = '300';
        $article_array = $model_article->getJoinList($condition);

        if (!empty($article_array) && is_array($article_array)) {
            foreach ($article_array as $key => $val) {
                $ac_id = $val['ac_id'];
                $ac_parent_id = $val['ac_parent_id'];
                if ($ac_parent_id == 0) {
                    $class_list[$ac_id]['list'][] = $val;
                } else {
                    $class_list[$ac_parent_id]['list'][] = $val;
                }
            }
        }
        if (!empty($class_list) && is_array($class_list)) {
            foreach ($class_list as $key => $val) {
                $ac_code = $val['ac_code'];
                if (in_array($ac_code, $notice_class)) {
                    $list = $val['list'];
                    array_splice($list, $notice_limit);
                    $val['list'] = $list;
                    $show_article[$ac_code] = $val;
                }
                if (in_array($ac_code, $code_array)) {
                    $list = $val['list'];
                    $val['class']['ac_name'] = $val['ac_name'];
                    array_splice($list, $faq_limit);
                    $val['list'] = $list;
                    $article_list[] = $val;
                }
            }
        }
        $string = "<?php\n\$show_article=" . var_export($show_article, true) . ";\n";
        $string .= "\$article_list=" . var_export($article_list, true) . ";\n?>";
        file_put_contents(BasePath . '/cache/index/article.php', compress_code($string));
        Tpl::output('show_article', $show_article);
        Tpl::output('article_list', $article_list);
    }

    //E脚部文章输出


    public function statisticsOp()
    {
        $model = model();
        $add_time_to = date("Y-m-d");
        $add_time_from = date("Y-m-d", (strtotime($add_time_to) - 60 * 60 * 24 * 30));
        $goods_selling = 0;
        $goods_show0 = 0;
        $goods_storage = 0;
        $consult = 0;
        $inform = 0;
        $complain = 0;
        $progressing = 0;
        $pending = 0;
        $shipped = 0;
        $shipping = 0;
        $evalseller = 0;
        $return = 0;
        $refund = 0;
        $order30 = 0;

        $goods_selling = $model->table('goods')->where(array('store_id' => $_SESSION['store_id'], 'goods_show' => '1'))->count();
        $goods_show0 = $model->table('goods')->where(array('store_id' => $_SESSION['store_id'], 'goods_state' => '1'))->count();
        $goods_storage = $model->table('goods')->where(array('store_id' => $_SESSION['store_id'], 'goods_state' => '0', 'goods_show' => '0'))->count();
        $consult = $model->table('consult')->where(array('seller_id' => $_SESSION['member_id'], 'consult_reply' => ''))->count();

        $condition = array();
        $condition['inform_store_id'] = $_SESSION['store_id'];
        $condition['inform_state'] = 2;
        $condition['inform_handle_type'] = 3;
        $condition['inform_datetime'] = array(array('gt', strtotime($add_time_from)), array('lt', strtotime($add_time_to) + 60 * 60 * 24), 'and');
        $inform = $model->table('inform')->where($condition)->count();

        $condition = array();
        $condition['accused_id'] = $_SESSION['member_id'];
        $condition['complain_state'] = array(array('gt', 10), array('lt', 90), 'and');
        $complain = $model->table('complain')->where($condition)->count();

        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['order_state'] = array(array('gt', 0), array('neq', 40), 'and');
        $progressing = $model->table('order')->where($condition)->count();

        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['order_state'] = array(array('gt', 0), array('lt', 11), 'and');
        $pending = $model->table('order')->where($condition)->count();

        $shipped = $model->table('order')->where(array('store_id' => $_SESSION['store_id'], 'order_state' => 20))->count();

        $shipping = $model->table('order')->where(array('store_id' => $_SESSION['store_id'], 'order_state' => 30))->count();
        $eval_condition = "store_id=" . $_SESSION['store_id'] . " and order_state=40 and refund_state<2 ";
        $eval_condition .= "and evalseller_status = 0 and (((finnshed_time+60*60*24*15)>" . time() . ") or ((evaluation_time+60*60*24*15)>" . time() . ")) ";
        $evalseller = $model->table('order')->where($eval_condition)->count();

        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['add_time'] = array(array('egt', strtotime($add_time_from)), array('elt', strtotime($add_time_to) + 60 * 60 * 24), 'and');
        $order30 = $model->table('order')->where($condition)->count();
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['return_state'] = array('gt', 1);
        $condition['add_time'] = array(array('egt', strtotime($add_time_from)), array('elt', strtotime($add_time_to) + 60 * 60 * 24), 'and');
        $return = $model->table('return')->where($condition)->count();
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['refund_state'] = array('gt', 1);
        $condition['add_time'] = array(array('egt', strtotime($add_time_from)), array('elt', strtotime($add_time_to) + 60 * 60 * 24), 'and');
        $refund = $model->table('refund_log')->where($condition)->count();


        $statistics = array(
            'goods_selling' => $goods_selling,
            'goods_storage' => $goods_storage,
            'goods_show0' => $goods_show0,
            'consult' => $consult,
            'inform' => $inform,
            'progressing' => $progressing,
            'complain' => $complain,
            'pending' => $pending,
            'shipped' => $shipped,
            'shipping' => $shipping,
            'evalseller' => $evalseller,
            'return' => $return,
            'refund' => $refund,
            'order30' => $order30
        );
        exit(json_encode($statistics));
    }


    public function store_goods_classOp()
    {
        $model_class = Model('my_goods_class');

        if ($_GET['type'] == 'ok') {
            if (intval($_GET['class_id']) != 0) {
                $class_info = $model_class->getClassInfo(array('stc_id' => intval($_GET['class_id'])));
                Tpl::output('class_info', $class_info);
            }
            if (intval($_GET['top_class_id']) != 0) {
                Tpl::output('class_info', array('stc_parent_id' => intval($_GET['top_class_id'])));
            }

            //S脚部文章输出
            $list = $this->_article();
            //E脚部文章输出

            $goods_class = $model_class->getClassList(array('store_id' => $_SESSION['store_id'], 'stc_top' => 1));
            Tpl::output('goods_class', $goods_class);
            Tpl::showpage('store_goods_class_add', 'null_layout');
        } else {
            $goods_class = $model_class->getTreeClassList(array('store_id' => $_SESSION['store_id']), 2);
            $str = '';
            if (is_array($goods_class) and count($goods_class) > 0) {
                foreach ($goods_class as $key => $val) {
                    $row[$val['stc_id']] = $key + 1;
                    $str .= intval($row[$val['stc_parent_id']]) . ",";
                }
                $str = substr($str, 0, -1);
            } else {
                $str = '0';
            }

            //S脚部文章输出
            $list = $this->_article();
            //E脚部文章输出

            Tpl::output('map', $str);
            Tpl::output('class_num', count($goods_class) - 1);
            Tpl::output('goods_class', $goods_class);

            self::profile_menu('store_goods_class', 'store_goods_class');
            Tpl::output('menu_sign', 'store_goods_class');
            Tpl::output('menu_sign_url', 'index.php?act=store&op=store_goods_class');
            Tpl::output('menu_sign1', 'goods_class');
            Tpl::showpage('store_goods_class');
        }
    }

    public function goods_class_saveOp()
    {
        $model_class = Model('my_goods_class');
        if ($_POST['stc_id'] != '') {
            $choeck_class = $model_class->getClassInfo(array('stc_id' => intval($_POST['stc_id']), 'store_id' => $_SESSION['store_id']));
            if (empty($choeck_class)) {
                showDialog(Language::get('store_goods_class_wrong'));
            }
            $state = $model_class->editGoodsClass($_POST, intval($_POST['stc_id']));
            if ($state) {
                showDialog(Language::get('nc_common_save_succ'), 'index.php?act=store&op=store_goods_class', 'succ', empty($_GET['inajax']) ? '' : 'CUR_DIALOG.close();');
            } else {
                showDialog(Language::get('nc_common_save_fail'));
            }
        } else {
            $state = $model_class->addGoodsClass($_POST);
            if ($state) {
                showDialog(Language::get('nc_common_save_succ'), 'index.php?act=store&op=store_goods_class', 'succ', empty($_GET['inajax']) ? '' : 'CUR_DIALOG.close();');
            } else {
                showDialog(Language::get('nc_common_save_fail'));
            }
        }
    }

    public function drop_goods_classOp()
    {
        $model_class = Model('my_goods_class');
        $drop_state = $model_class->dropGoodsClass(trim($_GET['class_id']));
        if ($drop_state) {
            showDialog(Language::get('nc_common_del_succ'), 'index.php?act=store&op=store_goods_class', 'succ');
        } else {
            showDialog(Language::get('nc_common_del_fail'));
        }
    }


    private function export_excel($order_list)
    {
        $name = $_GET['state_type'] == 'order_no_shipping' ? '_待发货' : '_已发货';
        $filename = strval(date('Ymd_His'));
        header("Content-type:application/vnd.ms-excel");
        header('Content-Disposition:attachment;filename=' . $filename . $name . '.xls');


        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns:dt="uuid:C2F41010-65B3-11d1-A29F-00AA00C14882"
    xmlns="http://www.w3.org/TR/REC-html40">

    <head>
    <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
    <meta name=ProgId content=Excel.Sheet>
    <meta name=Generator content="Microsoft Excel 11">
    <link rel=File-List href="订单信息模板v1.files/filelist.xml">
    <link rel=Edit-Time-Data href="订单信息模板v1.files/editdata.mso">
    <link rel=OLE-Object-Data href="订单信息模板v1.files/oledata.mso">
    <!--[if gte mso 9]><xml>
   <x:ExcelWorkbook>
   <x:ExcelWorksheets>
     <x:ExcelWorksheet>
     <x:Name></x:Name>
     <x:WorksheetOptions>
       <x:DisplayGridlines/>
     </x:WorksheetOptions>
     </x:ExcelWorksheet>
   </x:ExcelWorksheets>
   </x:ExcelWorkbook>
   </xml><![endif]-->
    </head>

    <body link=blue vlink=purple>

     <table x:str border=1 cellpadding=0 cellspacing=0 style="border-collapse:
     collapse;table-layout:fixed;">
     <tr>
      <td colspan=17 class=xl70>订单信息表</td>
     </tr>
     <tr>
      <td  class=xl69 >序号</td>
      <td class=xl69 style="border-left:none">订单号</td>
      <td class=xl69 style="border-left:none">订单时间</td>
      <td class=xl69 style="border-left:none">注册人手机</td>
      <td class=xl69 style="border-left:none">注册人姓名</td>
      <td class=xl69 style="border-left:none">注册人身份证</td>
      <td class=xl69 style="border-left:none">发货人</td>
      <td class=xl69 style="border-left:none">发货人电话</td>
      <td class=xl69 style="border-left:none">收货人</td>
      <td class=xl69 style="border-left:none">收货人身份证</td>
      <td class=xl69 style="border-left:none">省</td>
      <td class=xl69 style="border-left:none">市</td>
      <td class=xl69 style="border-left:none">区</td>
      <td class=xl69 style="border-left:none">收货地址</td>
      <td class=xl69 style="border-left:none">收货人电话</td>
      <td class=xl69 style="border-left:none">货号</td>
      <td class=xl69 style="border-left:none">商品名称</td>
      <td class=xl69 style="border-left:none">商品外文</td>
      <td class=xl69 style="border-left:none">数量</td>
      <td class=xl69 style="border-left:none">毛重/kg</td>
      <td class=xl69 style="border-left:none">净重/kg</td>
      <td class=xl69 style="border-left:none">商品单价</td>
      <td class=xl69 style="border-left:none">清关底价</td>
      <td class=xl69 style="border-left:none">HS编码</td>
      <td class=xl69 style="border-left:none">行邮税号</td>
      <td class=xl69 style="border-left:none">条形码</td>
      <td class=xl69 style="border-left:none">关税</td>
      <td class=xl69 style="border-left:none">运费</td>
      <td class=xl69 style="border-left:none">总价</td>
      <td class=xl69 style="border-left:none">清关总价</td>

     </tr>
      ';
        $number = 0;
        foreach ($order_list as $key => $val) {

            $model = Model();
            $number++;//序号
            $order_id = intval($val['order_id']);

            $model_store_order = Model('store_order');

            $order_goods_list = $model_store_order->storeOrderGoodsList(['order_id' => $order_id]);

            $goodsCounts = count($order_goods_list); //每张单里面有多少个商品

            $cou = 0; //计算器

            $memberInfo = $model->table('member')->field('member_name,member_truename,member_id_card')->where("member_id = '" . $val['buyer_id'] . "'")->find();

            if ($cou == 0) {
                $area_info = explode('	', $val['rec_info']);
                echo '<tr>
              			<td rowspan = "' . $goodsCounts . '">' . $number . '</td>
              			<td rowspan = "' . $goodsCounts . '">' . $val["order_sn"] . '</td>
              			<td rowspan = "' . $goodsCounts . '">' . date('Y/m/d H:i:s', $val["add_time"]) . '</td>
              		    <td rowspan = "' . $goodsCounts . '">' . $val['buyer_name'] . '</td>
              			<td rowspan = "' . $goodsCounts . '">' . $memberInfo['member_truename'] . '</td>
              			<td rowspan = "' . $goodsCounts . '">' . $memberInfo['member_id_card'] . '</td>
              			<td rowspan = "' . $goodsCounts . '">' . $val['store_name'] . '</td>
              			<td rowspan = "' . $goodsCounts . '">' . $val["store_tel"] . '</td>
              			<td rowspan = "' . $goodsCounts . '">' . $val["true_name"] . '</td>
              			<td rowspan = "' . $goodsCounts . '">' . $val["member_id_card"] . '</td>
              			<td rowspan = "' . $goodsCounts . '">' . $area_info['0'] . '</td>
              			<td rowspan = "' . $goodsCounts . '">' . $area_info['1'] . '</td>
              			<td rowspan = "' . $goodsCounts . '">' . $area_info['2'] . '</td>
              			<td rowspan = "' . $goodsCounts . '">' . $val["address"] . '</td>
              			<td rowspan = "' . $goodsCounts . '">' . $val["mob_phone"] . '</td>
              			';
            }


            foreach ($order_goods_list as $valgoods) {
                $goods_spec_info = $model->table('goods_spec')->where(array('spec_id' => $valgoods['spec_id']))->find();

                $goods_records_info = $model->table('goods_records')->where(['goods_article_num' => $goods_spec_info['spec_goods_serial']])->find();

                $str = '<td>' . $goods_spec_info['spec_goods_serial'] . '</td>';
                $str .= '<td>' . $valgoods['goods_name'] . '</td>';
                $str .= '<td>' . $goods_records_info['foreign_language'] . '</td>';
                $str .= '<td>' . ($valgoods['goods_num'] * ($goods_spec_info['store_base'] >= 1 ? $goods_spec_info['store_base'] : 1)) . '</td>'; //商品数量 * 基数
                $str .= '<td>' . $goods_records_info['gross_weight'] . '</td>'; //商品毛重
                $str .= '<td>' . $goods_records_info['net_weight'] . '</td>'; //商品净重
                $str .= '<td>' . $valgoods['goods_price'] . '</td>'; //商品单价
                $str .= '<td>' . number_format(ceil($goods_records_info['declaration_price'] * 0.8), 2) . '</td>'; //清关允许的最低价格
                $str .= '<td>' . $goods_records_info['goods_commodity_code'] . '</td>'; //HS编码
                $str .= '<td>' . $goods_records_info['tax_num'] . '</td>'; //行邮税号
                $str .= '<td>' . $goods_records_info['bar_code'] . '</td>'; //条形码

                if ($cou > 0 && $goodsCounts > 1) {
                    $str = '<tr>' . $str . '</tr>';
                }

                if ($cou == 0) {
                    $str .= '<td>' . $val['ems'] . '</td>'; //关税
                    $str .= '<td rowspan = "' . $goodsCounts . '">' . $val['shipping_fee'] . '</td>'; //运费
                    $str .= '<td rowspan = "' . $goodsCounts . '">' . $val['order_amount'] . '</td>';  //总价
                    $str .= '</tr>';
                }

                $cou++;

                echo $str;

            }
        }

        echo '</table>
    </body>
    </html>';


    }

    private function export_excels($order_list)
    {

        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=export_data.xls");


        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns:dt="uuid:C2F41010-65B3-11d1-A29F-00AA00C14882"
    xmlns="http://www.w3.org/TR/REC-html40">

    <head>
    <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
    <meta name=ProgId content=Excel.Sheet>
    <meta name=Generator content="Microsoft Excel 11">
    <link rel=File-List href="订单信息模板v1.files/filelist.xml">
    <link rel=Edit-Time-Data href="订单信息模板v1.files/editdata.mso">
    <link rel=OLE-Object-Data href="订单信息模板v1.files/oledata.mso">
    <!--[if gte mso 9]><xml>
     <o:DocumentProperties>
      <o:Author>hplg1</o:Author>
      <o:LastAuthor>user</o:LastAuthor>
      <o:Created>2015-02-14T02:23:19Z</o:Created>
      <o:LastSaved>2015-03-20T03:31:02Z</o:LastSaved>
      <o:Version>11.9999</o:Version>
     </o:DocumentProperties>
     <o:CustomDocumentProperties>
      <o:KSOProductBuildVer dt:dt="string">2052-9.1.0.4984</o:KSOProductBuildVer>
     </o:CustomDocumentProperties>
    </xml><![endif]-->
    <style>
    <!--table
            {mso-displayed-decimal-separator:"\.";
            mso-displayed-thousand-separator:"\,";}
    @page
            {margin:1.0in .75in 1.0in .75in;
            mso-header-margin:.51in;
            mso-footer-margin:.51in;
            mso-page-orientation:landscape;}
    tr
            {mso-height-source:auto;
            mso-ruby-visibility:none;}
    col
            {mso-width-source:auto;
            mso-ruby-visibility:none;}
    br
            {mso-data-placement:same-cell;}
    .style0
            {mso-number-format:General;
            text-align:general;
            vertical-align:middle;
            white-space:nowrap;
            mso-rotate:0;
            mso-background-source:auto;
            mso-pattern:auto;
            color:windowtext;
            font-size:12.0pt;
            font-weight:400;
            font-style:normal;
            text-decoration:none;
            font-family:宋体;
            mso-generic-font-family:auto;
            mso-font-charset:134;
            border:none;
            mso-protection:locked visible;
            mso-style-name:常规;
            mso-style-id:0;}
    td
            {mso-style-parent:style0;
            padding-top:1px;
            padding-right:1px;
            padding-left:1px;
            mso-ignore:padding;
            color:windowtext;
            font-size:12.0pt;
            font-weight:400;
            font-style:normal;
            text-decoration:none;
            font-family:宋体;
            mso-generic-font-family:auto;
            mso-font-charset:134;
            mso-number-format:General;
            text-align:general;
            vertical-align:middle;
            border:none;
            mso-background-source:auto;
            mso-pattern:auto;
            mso-protection:locked visible;
            white-space:nowrap;
            mso-rotate:0;}
    .xl65
            {mso-style-parent:style0;
            text-align:center;
            border:.5pt solid windowtext;}
    .xl66
            {mso-style-parent:style0;
            border:.5pt solid windowtext;}
    .xl67
            {mso-style-parent:style0;
            mso-number-format:"General Date";
            border:.5pt solid windowtext;}
    .xl68
            {mso-style-parent:style0;
            text-align:center;}
    .xl69
            {mso-style-parent:style0;
            font-size:11.0pt;
            font-weight:700;
            text-align:center;
            border:.5pt solid windowtext;
            background:silver;
            mso-pattern:auto none;}
    .xl70
            {mso-style-parent:style0;
            font-size:14.0pt;
            text-align:center;}
    ruby
            {ruby-align:left;}
    rt
            {color:windowtext;
            font-size:9.0pt;
            font-weight:400;
            font-style:normal;
            text-decoration:none;
            font-family:宋体;
            mso-generic-font-family:auto;
            mso-font-charset:134;
            mso-char-type:none;
            display:none;}
    -->
    </style>
    <!--[if gte mso 9]><xml>
     <x:ExcelWorkbook>
      <x:ExcelWorksheets>
       <x:ExcelWorksheet>
        <x:Name>Sheet1</x:Name>
        <x:WorksheetOptions>
         <x:DefaultRowHeight>285</x:DefaultRowHeight>
         <x:StandardWidth>2304</x:StandardWidth>
         <x:Print>
          <x:ValidPrinterInfo/>
          <x:PaperSizeIndex>9</x:PaperSizeIndex>
          <x:Scale>75</x:Scale>
          <x:HorizontalResolution>600</x:HorizontalResolution>
          <x:VerticalResolution>600</x:VerticalResolution>
         </x:Print>
         <x:PageBreakZoom>100</x:PageBreakZoom>
         <x:Selected/>
         <x:Panes>
          <x:Pane>
           <x:Number>3</x:Number>
           <x:ActiveRow>4</x:ActiveRow>
           <x:ActiveCol>4</x:ActiveCol>
          </x:Pane>
         </x:Panes>
         <x:ProtectContents>False</x:ProtectContents>
         <x:ProtectObjects>False</x:ProtectObjects>
         <x:ProtectScenarios>False</x:ProtectScenarios>
        </x:WorksheetOptions>
       </x:ExcelWorksheet>
       <x:ExcelWorksheet>
        <x:Name>Sheet2</x:Name>
        <x:WorksheetOptions>
         <x:DefaultRowHeight>285</x:DefaultRowHeight>
         <x:StandardWidth>2304</x:StandardWidth>
         <x:Print>
          <x:ValidPrinterInfo/>
          <x:PaperSizeIndex>9</x:PaperSizeIndex>
          <x:VerticalResolution>0</x:VerticalResolution>
         </x:Print>
         <x:PageBreakZoom>100</x:PageBreakZoom>
         <x:ProtectContents>False</x:ProtectContents>
         <x:ProtectObjects>False</x:ProtectObjects>
         <x:ProtectScenarios>False</x:ProtectScenarios>
        </x:WorksheetOptions>
       </x:ExcelWorksheet>
       <x:ExcelWorksheet>
        <x:Name>Sheet3</x:Name>
        <x:WorksheetOptions>
         <x:DefaultRowHeight>285</x:DefaultRowHeight>
         <x:StandardWidth>2304</x:StandardWidth>
         <x:Print>
          <x:ValidPrinterInfo/>
          <x:PaperSizeIndex>9</x:PaperSizeIndex>
          <x:VerticalResolution>0</x:VerticalResolution>
         </x:Print>
         <x:PageBreakZoom>100</x:PageBreakZoom>
         <x:ProtectContents>False</x:ProtectContents>
         <x:ProtectObjects>False</x:ProtectObjects>
         <x:ProtectScenarios>False</x:ProtectScenarios>
        </x:WorksheetOptions>
       </x:ExcelWorksheet>
      </x:ExcelWorksheets>
      <x:WindowHeight>10350</x:WindowHeight>
      <x:WindowWidth>20730</x:WindowWidth>
      <x:WindowTopX>0</x:WindowTopX>
      <x:WindowTopY>0</x:WindowTopY>
      <x:ProtectStructure>False</x:ProtectStructure>
      <x:ProtectWindows>False</x:ProtectWindows>
     </x:ExcelWorkbook>
    </xml><![endif]-->
    </head>

    <body link=blue vlink=purple>

    <table x:str border=0 cellpadding=0 cellspacing=0 width=1305 style="border-collapse:
     collapse;table-layout:fixed;width:982pt">
     <col width=46 style="mso-width-source:userset;mso-width-alt:1472;width:35pt">
     <col width=150 style="mso-width-source:userset;mso-width-alt:4800;width:113pt">
     <col width=98 style="mso-width-source:userset;mso-width-alt:3136;width:74pt">
     <col width=82 style="mso-width-source:userset;mso-width-alt:2624;width:62pt">
     <col width=179 style="mso-width-source:userset;mso-width-alt:5728;width:134pt">
     <col width=82 style="mso-width-source:userset;mso-width-alt:2624;width:62pt">
     <col width=122 style="mso-width-source:userset;mso-width-alt:3904;width:92pt">
     <col width=129 style="mso-width-source:userset;mso-width-alt:4128;width:97pt">
     <col width=115 style="mso-width-source:userset;mso-width-alt:3680;width:86pt">
     <col width=151 style="mso-width-source:userset;mso-width-alt:4832;width:113pt">
     <col width=73 style="mso-width-source:userset;mso-width-alt:2336;width:55pt">
     <col width=78 style="mso-width-source:userset;mso-width-alt:2496;width:59pt">
     <tr height=25 style="height:18.75pt">
      <td colspan=12 height=25 class=xl70 width=1305 style="height:18.75pt;
      width:982pt">订单信息表</td>
     </tr>
     <tr height=19 style="height:14.25pt">
      <td height=19 class=xl69 style="height:14.25pt">序号</td>
      <td class=xl69 style="border-left:none">订单号</td>
      <td class=xl69 style="border-left:none">订单时间</td>
      <td class=xl69 style="border-left:none">发货人</td>
      <td class=xl69 style="border-left:none">发货人电话</td>
      <td class=xl69 style="border-left:none">收货人姓名</td>
      <td class=xl69 style="border-left:none">身份证号</td>
      <td class=xl69 style="border-left:none">收货地址</td>
      <td class=xl69 style="border-left:none">收货人电话号码</td>
      <td class=xl69 style="border-left:none">商品编号</td>
      <td class=xl69 style="border-left:none">商品名称</td>
      <td class=xl69 style="border-left:none">商品外文名称</td>
      <td class=xl69 style="border-left:none">数量</td>
      <td class=xl69 style="border-left:none">商品单价</td>
      <td class=xl69 style="border-left:none">关税</td>
      <td class=xl69 style="border-left:none">运费</td>
      <td class=xl69 style="border-left:none">总价</td>
      
     </tr>
      ';

        // print_r($order_list);exit();

        $number = 0;
        foreach ($order_list as $key => $val) {

            $model = Model();
            $number++;//序号
            $order_id = intval($val['order_id']);

            $model_store_order = Model('store_order');
            $order_goods_list = $model_store_order->storeOrderGoodsList(array('order_id' => $order_id));

            $datalanguage = '';//商品外文
            $datagoodsno = '';//商品编号
            foreach ($order_goods_list as $valgoods) {
                $goods_spec_info = $model->table('goods_spec')->where(array('spec_id' => $valgoods['spec_id']))->find();
                $goods_article_num = $goods_spec_info['spec_goods_serial'];
                $goods_records_info = $model->table('goods_records')->where(array('goods_article_num' => $goods_article_num))->find();
                $goods_foreign_language = $goods_records_info["foreign_language"];
                $datalanguage .= $goods_foreign_language . "<br/>";
                $datagoodsno .= "" . $goods_spec_info["spec_goods_serial"] . "<br/>"; //商品编号

            }


            $datagoods = '';
            foreach ($order_goods_list as $valgoods) {
                $goods_name = $valgoods["goods_name"];
                $datagoods .= $goods_name . "<br/>";
            }


            $datanum = '';
            foreach ($order_goods_list as $valgoods) {
                $datanum .= "" . $valgoods["goods_num"] . "<br/>";
            }

            $dataprice = '';
            foreach ($order_goods_list as $valgoods) {
                $dataprice .= "" . $valgoods['goods_price'] . "<br/>";
            }

            $datashipping = '';
            foreach ($order_goods_list as $valgoods) {
                $datashipping .= "" . $valgoods['shipping_name'] . "<br/>";
            }

            //		$datagoodsno='';
            //		foreach($order_goods_list as $valgoods){
            //		$datagoodsno .= "".$valgoods["goods_item_no"] . "<br/>"; 
            //		}
            //                
            //		$datagoods='';
            //		foreach($order_goods_list as $valgoods){
            //		$goods_name=$valgoods["goods_name"];  
            //		$datagoods .= $goods_name."<br/>"; 
            //		}
            //                
            //                $datalanguage='';
            //                foreach($order_goods_list as $valgoods){
            //                $goods_foreign_language=$valgoods["foreign_language"];  
            //                $datalanguage .= $goods_foreign_language. "<br/>"; 
            //                }
            //                
            //		$datanum='';
            //		foreach($order_goods_list as $valgoods){
            //		$datanum .= "".$valgoods["goods_num"]."<br/>"; 
            //		}
            //                
            //                $dataprice='';
            //                foreach($order_goods_list as $valgoods){
            //                    $dataprice .= "".$valgoods['goods_price']."<br/>";
            //                }
            //                
            //                $datashipping='';
            //                foreach($order_goods_list as $valgoods){
            //                    $datashipping .= "".$valgoods['shipping_name ']."<br/>";
            //                }
            echo '<tr height=121 style=\'mso-height-source:userset;height:90.75pt\'>
                <td height=121 class=xl26 style=\'height:90.75pt\' x:num>' . $number . '</td>
              <td class=xl66 style="border-top:none;border-left:none">' . $val["order_sn"] . '</td>
              <td class=xl67 style="border-top:none;border-left:none">' . date("Y-m-d", $val["add_time"]) . '</td>
                    <td class=xl67 style="border-top:none;border-left:none">' . $val["store_name"] . '</td>
                    <td class=xl67 style="border-top:none;border-left:none">' . $val["store_tel"] . '</td>
              <td class=xl66 style="border-top:none;border-left:none">' . $val["true_name"] . '</td>
              <td class=xl66 style="border-top:none;border-left:none">' . $val["consignee_id_num"] . '</td>
              <td class=xl66 style="border-top:none;border-left:none">' . $val["address"] . '</td>
              <td class=xl66 style="border-top:none;border-left:none">' . $val["mob_phone"] . '</td>
              <td class=xl66 style="border-top:none;border-left:none">' . $datagoodsno . '</td>
              <td class=xl65 style="border-top:none;border-left:none">' . $datagoods . '</td>
              <td class=xl66 style="border-top:none;border-left:none">' . $datalanguage . '</td>
              <td class=xl65 style="border-top:none;border-left:none">' . $datanum . '</td>
              <td class=xl65 style="border-top:none;border-left:none">' . $dataprice . '</td>
                      <td class=xl65 style="border-top:none;border-left:none">' . $val['ems'] . '</td>
                      <td class=xl65 style="border-top:none;border-left:none">' . $val['shipping_fee'] . '</td>
                  <td class=xl65 style="border-top:none;border-left:none">' . $val['order_amount'] . '</td>
             </tr>';
        }
        echo '
    </table>

    </body>

    </html>';

    }


    public function store_orderOp()
    {
        $model_store_order = Model('store_order');
        $model_member = Model('member');
        /**
         * 订单分页
         */
        $array['refund_state'] = 'no';
        $array['return_state'] = 'no';
        //--------------------
        $page = new Page();
        $page->setEachNum(10);
        $page->setStyle('admin');
        /*搜索条件*/
        $array = array();
        $array['order_state'] = trim($_GET['state_type']) == '' ? 'store_order' : trim($_GET['state_type']);
        $array['order_country'] = trim($_GET['order_country']);
        $array['order_provider'] = trim($_GET['order_provider']);
        $array['goods_item_no'] = trim($_GET['goods_item_no']);
        $array['buyer_name'] = trim($_GET['buyer_name']);
        $array['order_sn'] = trim($_GET['order_sn']);
        $array['order_evalseller_able'] = trim($_GET['eval']);
        $array['add_time_from'] = strtotime($_GET['add_time_from']);
        $array['add_time_to'] = strtotime($_GET['add_time_to']);
        $array['payment_time_from'] = strtotime($_GET['pay_time_from']);
        $array['payment_time_to'] = !empty($_GET['pay_time_to']) ? strtotime($_GET['pay_time_to']) + 86399 : '';
        if ($array['add_time_to'] > 0) {
            $array['add_time_to'] += 86400;
        }

        $order_list = $model_store_order->storeOrderList($array, $page);
        if (is_array($order_list) && !empty($order_list)) {

            if ($_GET['state_type'] == 'order_no_shipping' || $_GET['state_type'] == 'order_shipping') {
                //导出excel
                if ($_GET['excel'] == 1) {
                    $array['refund_state'] = 'no';
                    $array['return_state'] = 'no';
                    $order_listexcel = $model_store_order->storeOrderListexcel($array);
                    $this->export_excel($order_listexcel);
                    exit();
                }
            }


            $order_id_array = array();
            $order_array = array();
            $member_id_array = array();
            $member_array = array();
            $refund_array = array();
            $return_array = array();
            foreach ($order_list as $v) {
                if ($v['order_id'] == '') continue;
                $order_id_array[] = $v['order_id'];
                $order_array[$v['order_id']] = $v;
                if (!in_array($v['buyer_id'], $member_id_array)) $member_id_array[] = $v['buyer_id'];
            }
            $goods_list = $model_store_order->storeOrderGoodsList(array('order_id' => "'" . implode("','", $order_id_array) . "'"));
            $member_list = $model_member->getMemberList(array('in_member_id' => "'" . implode("','", $member_id_array) . "'"));
            if (is_array($member_list) && !empty($member_list)) {
                foreach ($member_list as $val) {
                    $member_array[$val['member_id']] = $val;
                }
            }

            $model_refund = Model('refund');
            $condition = array();
            $condition['seller_id'] = $_SESSION['member_id'];
            $condition['order_ids'] = "'" . implode("','", $order_id_array) . "'";
            $condition['refund_type'] = '1';
            $refund_list = $model_refund->getList($condition);
            if (is_array($refund_list) && !empty($refund_list)) {
                foreach ($refund_list as $val) {
                    $refund_array[$val['order_id']] = $val;
                }
            }

            $model_return = Model('return');
            $condition = array();
            $condition['seller_id'] = $_SESSION['member_id'];
            $condition['order_ids'] = "'" . implode("','", $order_id_array) . "'";
            $condition['return_type'] = '1';
            $return_list = $model_return->getList($condition);
            if (is_array($return_list) && !empty($return_list)) {
                foreach ($return_list as $val) {
                    $return_array[$val['order_id']] = $val;
                }
            }
        }
        $goods_array = array();
        if (is_array($goods_list) && !empty($goods_list)) {
            $store_class = Model('store');
            foreach ($goods_list as $val) {
                $order_id = $val['order_id'];
                if (is_array($refund_array[$order_id]) && !empty($refund_array[$order_id])) $val['refund'] = $refund_array[$order_id];
                if (is_array($return_array[$order_id]) && !empty($return_array[$order_id])) $val['return'] = $return_array[$order_id];
                $val['spec_info_arr'] = '';
                if (!empty($val['spec_info'])) {
                    $val['spec_info_arr'] = unserialize($val['spec_info']);
                }
                $val['state_info'] = orderStateInfo($val['order_state'], $val['refund_state']);

                if ($val['evalseller_status'] == 1) {
                    $val['able_evaluate'] = false;
                } else {
                    $val['able_evaluate'] = true;
                }
                if ($val['able_evaluate'] && $val['evaluation_status'] == 0 && (intval($val['finnshed_time']) + 60 * 60 * 24 * 15) < time()) {
                    $val['able_evaluate'] = false;
                } elseif ($val['able_evaluate'] && $val['evaluation_status'] == 1 && (intval($val['evaluation_time']) + 60 * 60 * 24 * 15) < time()) {
                    $val['able_evaluate'] = false;
                }
                $val['member_info'] = $member_array[$val['buyer_id']];
                $goods_array[$val['order_id']][] = $val;
            }
        }

        foreach ($goods_array as $key => $val) {
            foreach ($val as $k => $v) {
                $param['table'] = 'change';
                $param['field'] = 'order_id';
                $param['value'] = $v['order_id'];
                $arr = Db::getRow($param, "*");
                $goods_array[$key][$k]['buyer_message'] = $arr['buyer_message'];
            }
        }

        //S脚部文章输出
        $list = $this->_article();
        //E脚部文章输出

        Tpl::output('goods_array', $goods_array);
        Tpl::output('order_array', $order_array);


        Tpl::output('show_page', $page->show());

        Tpl::output('complain_time_limit', C('complain_time_limit'));

        self::profile_menu('store_order', $array['order_state']);
        Tpl::output('menu_sign', 'store_order');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_order');
        Tpl::output('menu_sign1', empty($_GET['state_type']) ? 'all_order' : $_GET['state_type']);
        Tpl::showpage('store_order');
    }

    //订单信息导出
    public function export_step1Op()
    {
        Language::read('trade');
        $lang = Language::getLangContent();

        $model_store_order = Model('store_order');

        $condition = array();
        if (trim($_GET['search_name']) != '' and trim($_GET['field']) != '') {
            $condition[$_GET['field']] = trim($_GET['search_name']);
        }

        if (in_array($_GET['status'], array('0', '10', '11', '20', '30', '40', '50'))) {
            $condition['order_state'] = $_GET['status'];
        }

        if (!empty($_GET['add_time_from'])) {
            $time1 = strtotime($_GET['add_time_from']);
        }
        if (!empty($_GET['add_time_to'])) {
            $time2 = strtotime($_GET['add_time_to']);
            if ($time2 !== false) $time2 = $time2 + 86400;
        }
        if ($time1 && $time2) {
            $condition['addtime'] = array('between', array($time1, $time2));
        } elseif ($time1) {
            $condition['addtime'] = array('egt', $time1);
        } elseif ($time2) {
            $condition['addtime'] = array('elt', $time2);
        }
        if (is_numeric($_GET['order_amount_from']) && is_numeric($_GET['order_amount_to'])) {
            $condition['order_amount'] = array('between', array($_GET['order_amount_from'], $_GET['order_amount_to']));
        } elseif (is_numeric($_GET['order_amount_from'])) {
            $condition['order_amount'] = array('egt', $_GET['order_amount_from']);
        } elseif (is_numeric($_GET['order_amount_to'])) {
            $condition['order_amount'] = array('elt', $_GET['order_amount_to']);
        }

        if (!empty($_GET['order_country'])) {
            $condition['order_country'] = trim($_GET['order_country']);
        }
        if (!empty($_GET['order_provider'])) {
            $condition['order_provider'] = trim($_GET['order_provider']);
        }


        $model = Model();
        $page = new page();
        if (!is_numeric($_GET['curpage'])) {
            //$count = $model->table('order')->where($condition)->count();
            //统计订单数量
            $order_list = $model_store_order->storeOrderList($condition);
            $count = 0;
            foreach ($order_list as $k => $v) {
                $count++;
            }
            $array = array();
            if ($count > self::EXPORT_SIZE) {
                $page = ceil($count / self::EXPORT_SIZE);
                for ($i = 1; $i <= $page; $i++) {
                    $limit1 = ($i - 1) * self::EXPORT_SIZE + 1;
                    $limit2 = $i * self::EXPORT_SIZE > $count ? $count : $i * self::EXPORT_SIZE;
                    $array[$i] = $limit1 . ' ~ ' . $limit2;
                }
                Tpl::output('list', $array);
                Tpl::output('download_lang', Language::get('order_manage'));
                Tpl::output('murl', 'index.php?act=trade&op=order_manage');
                Tpl::showpage('export.excel');
            } else {
                $field = 'order_sn,seller_id,store_id,store_name,order_state,order_amount,buyer_id,buyer_name,buyer_email,add_time,payment_id,payment_name,shipping_fee,shipping_name';
                //$data = $model->table('order')->field($field)->where($condition)->order('order_id desc')->limit(self::EXPORT_SIZE)->select();
                $order_list = $model_store_order->storeOrderList($condition);
                $this->createExcel($order_list);
            }
        } else {
            $field = 'order_sn,seller_id,store_id,store_name,order_state,order_amount,buyer_id,buyer_name,buyer_email,add_time,payment_id,payment_name,shipping_fee,shipping_name';
            $limit1 = ($_GET['curpage'] - 1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            //$data = $model->table('order')->field($field)->where($condition)->order('order_id desc')->limit("{$limit1},{$limit2}")->select();
            $order_list = $model_store_order->storeOrderList($condition);
            $this->createExcel($order_list);
        }
    }

    //导出excel
    private function createExcel($data = array())
    {
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();

        $excel_obj->setStyle(array('id' => 's_title', 'Font' => array('FontName' => '宋体', 'Size' => '12', 'Bold' => '1')));

        $excel_data[0][] = array('styleid' => 's_title', 'data' => L('exp_od_no'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => L('exp_od_store'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => L('exp_od_buyer'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => L('exp_od_xtimd'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => L('exp_od_count'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => L('exp_od_yfei'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => L('exp_od_paytype'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => L('exp_od_state'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => L('exp_od_storeid'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => L('exp_od_selerid'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => L('exp_od_buyerid'));
        $excel_data[0][] = array('styleid' => 's_title', 'data' => L('exp_od_bemail'));

        $cn_state = array(L('exp_od_sta_qx'), L('exp_od_sta_dfk'), L('exp_od_sta_dqr'), L('exp_od_sta_yfk'), L('exp_od_sta_yfh'), L('exp_od_sta_yjs'), L('exp_od_sta_dsh'), L('exp_od_sta_yqr'));
        foreach ((array)$data as $k => $v) {
            if ($v['order_state'] == 0) $v['order_state'] = '00';
            $v['order_state'] = str_replace(array('00', 10, 11, 20, 30, 40, 50, 60), $cn_state, $v['order_state']);
            $tmp = array();
            $tmp[] = array('data' => $v['order_sn']);
            $tmp[] = array('data' => $v['store_name']);
            $tmp[] = array('data' => $v['buyer_name']);
            $tmp[] = array('data' => date('Y-m-d H:i:s', $v['add_time']));
            $tmp[] = array('format' => 'Number', 'data' => ncPriceFormat($v['order_amount']));
            $tmp[] = array('format' => 'Number', 'data' => ncPriceFormat($v['shipping_fee']));
            $tmp[] = array('data' => $v['payment_name']);
            $tmp[] = array('data' => $v['order_state']);
            $tmp[] = array('data' => $v['store_id']);
            $tmp[] = array('data' => $v['seller_id']);
            $tmp[] = array('data' => $v['buyer_id']);
            $tmp[] = array('data' => $v['buyer_email']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data, CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset(L('exp_od_order'), CHARSET));
        $excel_obj->generateXML($excel_obj->charset(L('exp_od_order'), CHARSET) . $_GET['curpage'] . '-' . date('Y-m-d-H', time()));
    }


    public function change_aOp()
    {
        if (!empty($_GET['id'])) {
            $data['deliver'] = 1;
            if (Db::update('order', $data, "order_id=" . $_GET['id'])) {
                echo "<script>location.href='index.php?act=store&op=store_order';</script>";
            }
        }
    }


    public function change_stateOp()
    {
        $state_type = trim($_GET['state_type']);
        $order_id = intval($_GET['order_id']);
        if ($state_type == '') return;

        $model_order = Model('order');


        if (!$model_order->checkOrderBelongStore($order_id, $_SESSION['store_id'])) {
            showMessage(Language::get('store_order_invalid_order'), '', '', 'error');
        }

        $array = array();
        switch ($state_type) {

            case 'order_confirm':
                $temp_file = 'store_order_confirm';
                $state_code = 60;
                break;

            case 'store_order_pay':
                $temp_file = 'store_order_pay';
                $state_code = 20;
                $array['payment_time'] = time();
                break;

            case 'store_order_edit_price':
                $temp_file = 'store_order_edit_price';
                $state_code = 10;
                if (chksubmit()) {
                    $array['order_amount'] = (ncPriceFormat(trim($_POST['order_amount'])) + ncPriceFormat(trim($_POST['shipping_fee'])));
                    $array['shipping_fee'] = ncPriceFormat(trim($_POST['shipping_fee']));
                    $model_order->updateOrder($array, $order_id);
                } else {
                    $order_info = $model_order->getOrderById($order_id, 'simple');
                    Tpl::output('order_info', $order_info);
                }
                break;

            case 'store_order_cancel':
                $temp_file = 'store_order_cancel';
                $state_code = 0;
                break;
        }

        //S脚部文章输出
        $list = $this->_article();
        //E脚部文章输出

        if (chksubmit()) {
            $array['order_state'] = $state_code;
            if ($state_code == 0) {

                $model_store_order = Model('store_order');
                $goods_list = $model_store_order->storeOrderGoodsList(array('order_id' => $order_id));
                $model_goods = Model('goods');
                if (is_array($goods_list) and !empty($goods_list)) {
                    foreach ($goods_list as $val) {
                        $model_goods->updateSpecStorageGoods(array('spec_goods_storage' => array('value' => $val['goods_num'], 'sign' => 'increase'), 'spec_salenum' => array('value' => $val['goods_num'], 'sign' => 'decrease')), $val['spec_id']);
                        $model_goods->updateGoods(array('salenum' => array('value' => $val['goods_num'], 'sign' => 'decrease')), $val['goods_id']);
                    }
                }
            }

            $model_order->addLogOrder($state_code, $order_id, ($_POST['state_info1'] != '' ? $_POST['state_info1'] : $_POST['state_info']));
            $model_order->updateOrder($array, $order_id);

            $order = $model_order->getOrderById(intval($_GET['order_id']), 'simple');


            $param = array(
                'site_url' => SiteUrl,
                'site_name' => $GLOBALS['setting_config']['site_name'],
                'store_name' => $order['store_name'],
                'buyer_name' => $order['buyer_name'],
                'order_sn' => $order['order_sn'],
                'order_id' => $order['order_id'],
                'invoice_no' => $order['shipping_code'],
                'reason' => $_POST['state_info1'] != '' ? $_POST['state_info1'] : $_POST['state_info']
            );
            $code = '';
            switch ($state_type) {

                case 'store_order_pay':
                    $code = 'email_tobuyer_offline_pay_success_notify';
                    break;

                case 'store_order_edit_price':
                    $code = 'email_tobuyer_adjust_fee_notify';
                    break;

                case 'store_order_cancel':
                    $code = 'email_tobuyer_cancel_order_notify';
                    break;
            }
            if ($code != '') {
                $this->send_notice($order['buyer_id'], $code, $param);
            }
            showDialog(Language::get('nc_common_save_succ'), 'reload', 'succ', empty($_GET['inajax']) ? '' : 'CUR_DIALOG.close();');
        } else {
            Tpl::output('order_id', $order_id);
            //脚部文章输出
            $article = $this->_article();
            Tpl::showpage($temp_file, 'null_layout');
        }
    }

    public function show_orderOp()
    {
        $order_id = intval($_GET['order_id']);


        $model_order = Model('order');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['order_id'] = $order_id;
        $order_info = $model_order->getOrderById($order_id, 'all', $condition);

        $param['table'] = 'address';
        $param['field'] = 'member_id';
        $param['value'] = $order_info['buyer_id'];
        $row = Db::getRow($param, "*");
        $order_info['card'] = $row['card'];
        $order_info['idcard'] = $row['idcard'];
        $order_info['idcard2'] = $row['idcard2'];

        $order_id = intval($order_info['order_id']);
        if ($order_id == 0) {
            var_dump($order_id);
            exit;
            showMessage(Language::get('miss_argument'), '', 'html', 'error');
        }
        $order_info['state_info'] = orderStateInfo($order_info['order_state'], $order_info['refund_state']);
        Tpl::output('order_info', $order_info);


        if (!empty($order_info['group_id']) && is_numeric($order_info['group_id'])) {
            $group_name = Model()->table('goods_group')->getfby_group_id($order_info['group_id'], 'group_name');
            Tpl::output('group_name', $group_name);
        }


        $model_store = Model('store');
        $store_info = $model_store->shopStore(array('store_id' => $order_info['store_id']));
        Tpl::output('store_info', $store_info);

        $model_store_order = Model('store_order');

        $order_goods_list = $model_store_order->storeOrderGoodsList(array('order_id' => $order_id));
        Tpl::output('order_goods_list', $order_goods_list);


        $log_list = $model_order->orderLoglist($order_id);
        Tpl::output('order_log', $log_list);

        $model_refund = Model('refund');
        $condition = array();
        $condition['seller_id'] = $_SESSION['member_id'];
        $condition['order_id'] = $order_id;
        $condition['refund_state'] = '2';
        $condition['order'] = 'log_id asc';
        $refund_list = $model_refund->getList($condition);
        Tpl::output('refund_list', $refund_list);

        $model_return = Model('return');
        $condition = array();
        $condition['seller_id'] = $_SESSION['member_id'];
        $condition['order_id'] = $order_id;
        $condition['return_state'] = '2';
        $condition['order'] = 'return.return_id asc';
        $return_list = $model_return->getReturnGoodsList($condition);
        Tpl::output('return_list', $return_list);

        //S脚部文章输出
        $list = $this->_article();
        //E脚部文章输出

        self::profile_menu('member_order', 'member_order');
        Tpl::output('menu_sign', 'show_order');
        Tpl::output('left_show', 'order_view');
        Tpl::showpage('store_order_view');
    }

    public function order_declarationOp()
    {
        if (!empty($_GET['order_id'])) {
            $update_array['examine'] = 1;

            if (Db::update('order', $update_array, "order_id = " . $_GET['order_id'] . "")) {
                echo "<script>location.href='index.php?act=store&op=show_order&order_id=" . $_GET['order_id'] . "'</script>";
            }

        }

    }

    public function deliverOp()
    {
        if (!empty($_GET['id'])) {
            $data['deliver'] = 1;
            if (Db::update('order', $data, "order_id = " . $_GET['id'] . "")) {
                echo "<script>location.href='index.php?act=store&op=show_order&order_id=" . $_GET['id'] . "';</script>";
            }
        }
    }

    public function get_order_recordOp()
    {

        $store = Model('store');
        $item = $store->create_order_itemOp($data);

        $dir = date('Y') . '/' . date('M') . '/';

        $newName = MD5(time() . rand(000, 9999));
        if (!file_exists($dir)) {
            mkdir($dir, '0777', true);
        }
        file_put_contents($dir . $newName . ".xml", $item);
        $zip = new ZipArchive();
        $zipname = date('YmdHis', time()) . '.zip';
        if (!file_exists($zipname)) {
            $zip->open($zipname, ZipArchive::OVERWRITE);
            $zip->addFile($dir . $newName . ".xml");
            $content = file_get_contents($dir . $newName . ".xml");
            if ($content !== false) {
                $zip->addFromString($dir . $newName . ".xml", $content);
            }

            $zip->close();
            $dw = new download($zipname);
            $dw->getfiles();
            unlink($zipname);
        }
    }


    public function paymentOp()
    {

        $model_payment = Model('payment');

        $payment_list = $model_payment->getPaymentList();

        if (strtoupper(CHARSET) == 'GBK') {
            $payment_list = Language::getGBK($payment_list);
        }

        if (file_exists(BasePath . DS . 'api' . DS . 'payment' . DS . 'payment.inc.php')) {

            require_once(BasePath . DS . 'api' . DS . 'payment' . DS . 'payment.inc.php');
        }

        $payment_list = $model_payment->checkinstallPayment($payment_list);

        //S脚部文章输出
        $list = $this->_article();
        //E脚部文章输出
        Tpl::output('payment_inc', $payment_inc);
        Tpl::output('payment_list', $payment_list);

        self::profile_menu('payment', 'payment');
        Tpl::output('menu_sign', 'payment');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=payment');
        Tpl::output('menu_sign1', 'payment_list');
        Tpl::showpage('payment_list');
    }

    public function store_yinlianOp()
    {
        $model = Model('payment');

        $model->upload_yinlian();
        //脚部文章输出
        $article = $this->_article();

        Tpl::output('payment_code', $_GET['payment_code']);
        Tpl::showpage('store_warehouse_file');
    }

    public function store_shanghuOp()
    {
        $model = Model('payment');
        $model->upload_shanghu();
        //脚部文章输出
        $article = $this->_article();

        Tpl::output('payment_code', $_GET['payment_code']);
        Tpl::showpage('store_warehouse_file');
    }

    public function add_paymentOp()
    {
        $model_payment = Model('payment');

        $paymemt_code = trim($_REQUEST['payment_code']);
        $check_payment = $model_payment->checkPayment($paymemt_code);
        if (!$check_payment) {
            showDialog(Language::get('store_payment_not_exists'));
        }
        $payment_info = $model_payment->getPaymentInfo($paymemt_code, 'file');

        if (strtoupper(CHARSET) == 'GBK') {
            $payment_info = Language::getGBK($payment_info);
        }

        if ($_GET['submit'] == 'ok') {

            $yinlian = Model('yinlian');
            if (!empty($_POST)) {
                $data['store_id'] = $_SESSION['store_id'];
                $data['shanghu_id'] = $_POST['shanghu_id'];

                $data['yinlian'] = $_POST['yinlian'];
                $data['shanghu'] = $_POST['shanghu'];
                $row = $yinlian->where(array('store_id' => $_SESSION['store_id']))->find();
                if (!empty($row)) {
                    Db::update('yinlian', $data, "store_id=" . $_SESSION['store_id']);

                } else {
                    Db::insert('yinlian', $data);
                }

            }

            $model_payment->savePayment($payment_info['config']);
            showDialog(Language::get('nc_common_save_succ'), 'index.php?act=store&op=payment', 'succ');
        }

        $payment_array = $model_payment->getPaymentInfo($paymemt_code, 'sql');
        if (is_array($payment_array) and !empty($payment_array)) {
            $payment_info['payment_id'] = $payment_array['payment_id'];
            $payment_info['payment_info'] = $payment_array['payment_info'];
            $payment_info['payment_state'] = $payment_array['payment_state'];
            $payment_info['payment_sort'] = $payment_array['payment_sort'];
            $config_array = unserialize($payment_array['payment_config']);
            if (is_array($config_array) and !empty($config_array)) {
                foreach ($payment_info['config'] as $k => $v) {
                    $payment_info['config'][$k]['value'] = $config_array[$k];
                }
            }
        }

        Tpl::output('payment_info', $payment_info);
        self::profile_menu('payment', 'payment');
        Tpl::output('menu_sign', 'payment');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=payment');
        Tpl::output('menu_sign1', 'payment_list');
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('payment_add');
    }

    public function uninstall_paymentOp()
    {
        $model_payment = Model('payment');
        $payment_id = intval($_GET['payment_id']);
        $uninstall_state = $model_payment->uninstallPayment($payment_id);
        if ($uninstall_state) {
            showDialog(Language::get('nc_common_op_succ'), 'index.php?act=store&op=payment', 'succ');
        } else {
            showDialog(Language::get('nc_common_op_fail'));
        }
    }

    public function checknameOp()
    {
        if (!$this->checknameinner()) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function store_navigation_addOp()
    {

        $model_class = Model('store_navigation_partner');

        if (!empty($_GET['type'])) {

            if (intval($_GET['sn_id']) > 0) {

                $sn_info = $model_class->getOneNavigation(intval($_GET['sn_id']));

                Tpl::output('sn_info', $sn_info);
            }

            $editor_multimedia = false;
            Tpl::output('editor_multimedia', $editor_multimedia);
            if ($_GET['type'] == 'add') {
                self::profile_menu('store_navigation', 'store_navigation_add');
                Tpl::output('menu_sign', 'store_navigation');
                Tpl::output('menu_sign_url', 'index.php?act=store&op=store_navigation');
                Tpl::output('menu_sign1', 'navigation_add');
                //脚部文章输出
                $article = $this->_article();
                Tpl::showpage('store_navigation_form');
            }
            if ($_GET['type'] == 'edit') {
                self::profile_menu('store_navigation_edit', 'store_navigation_edit');
                Tpl::output('menu_sign', 'store_navigation');
                Tpl::output('menu_sign_url', 'index.php?act=store&op=store_navigation');
                Tpl::output('menu_sign1', 'navigation_edit');
                //脚部文章输出
                $article = $this->_article();
                Tpl::showpage('store_navigation_edit');
            }
        }
    }

    public function store_navigationOp()
    {

        $model_class = Model('store_navigation_partner');


        if (chksubmit()) {

            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["sn_title"], "require" => "true", "message" => Language::get('store_navigation_name_null')),
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showValidateError($error);
            }
            if (intval($_POST['sn_id']) > 0) {

                if ($model_class->updateNavigation($_POST)) {
                    showDialog(Language::get('nc_common_save_succ'), "index.php?act=store&op=store_navigation", 'succ');
                } else {
                    showDialog(Language::get('nc_common_save_fail'));
                }
            } else {

                if ($model_class->addNavigation($_POST)) {
                    showDialog(Language::get('nc_common_save_succ'), "index.php?act=store&op=store_navigation", 'succ');
                } else {
                    showDialog(Language::get('nc_common_save_fail'));
                }
            }
        }

        if ($_GET['drop'] == 'single' && (intval($_GET['sn_id'])) > 0) {

            if ($model_class->checkNavigation($_SESSION['store_id'], intval($_GET['sn_id']))) {

                if ($model_class->delNavigation(intval($_GET['sn_id']))) {
                    showDialog(Language::get('nc_common_op_succ'), "index.php?act=store&op=store_navigation", 'succ');
                } else {
                    showDialog(Language::get('nc_common_op_fail'));
                }
            }
        } elseif ($_GET['drop'] == 'all' && !empty($_GET['sn_id'])) {

            $sn_array = explode(',', $_GET['sn_id']);
            if (!empty($sn_array) && is_array($sn_array)) {
                foreach ($sn_array as $key => $value) {
                    $value = intval($value);

                    if ($model_class->checkNavigation($_SESSION['store_id'], $value)) {

                        if (!$model_class->delNavigation($value)) {
                            showDialog(Language::get('nc_common_save_fail'));
                        }
                    }
                }
                showDialog(Language::get('nc_common_op_succ'), "index.php?act=store&op=store_navigation", 'succ');
            }
        }

        $condition['sn_store_id'] = $_SESSION['store_id'];
        $navigation_list = $model_class->getNavigationList($condition);

        Tpl::output('navigation_list', $navigation_list);

        self::profile_menu('store_navigation', 'store_navigation');
        Tpl::output('menu_sign', 'store_navigation');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_navigation');
        Tpl::output('menu_sign1', 'navigation_list');
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_navigation_list');
    }


    public function store_partnerOp()
    {

        $model_class = Model('store_navigation_partner');

        if (!empty($_GET['type'])) {

            if (intval($_GET['sp_id']) > 0) {

                $sp_info = $model_class->getOnePartner(intval($_GET['sp_id']));

                Tpl::output('sp_info', $sp_info);
            }

            Tpl::output('type', $_GET['type']);
            Tpl::showpage('store_partner_form', 'null_layout');
            die;
        }

        if (chksubmit()) {

            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["sp_title"], "require" => "true", "message" => Language::get('store_partner_title_null')),
                array("input" => $_POST["sp_link"], "require" => "true", "message" => Language::get('store_partner_wrong_href')),
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showValidateError($error);
            }
            if (intval($_POST['sp_id']) > 0) {

                if (!$model_class->updatePartner($_POST)) {
                    showDialog(Language::get('nc_common_save_fail'));
                }
            } else {

                if (!$model_class->addPartner($_POST)) {
                    showDialog(Language::get('nc_common_save_fail'));
                }
            }
            showDialog(Language::get('nc_common_save_succ'), 'index.php?act=store&op=store_partner', 'succ', empty($_GET['inajax']) ? '' : 'CUR_DIALOG.close();');
        }

        if ($_GET['drop'] == 'single' && (intval($_GET['sp_id'])) > 0) {

            if ($model_class->delPartner(intval($_GET['sp_id']))) {
                showDialog(Language::get('nc_common_del_succ'), 'index.php?act=store&op=store_partner', 'succ');
            } else {
                showDialog(Language::get('nc_common_del_fail'));
            }
        } elseif ($_GET['drop'] == 'all' && !empty($_GET['sp_id'])) {

            $sp_array = explode(',', $_GET['sp_id']);
            if (!empty($sp_array) && is_array($sp_array)) {
                foreach ($sp_array as $key => $value) {
                    $value = intval($value);

                    if (!$model_class->delPartner($value)) {
                        showDialog(Language::get('store_partner_del_fail'));
                    }
                }
                showDialog(Language::get('nc_common_del_succ'), 'index.php?act=store&op=store_partner', 'succ');
            }
        }

        $condition['sp_store_id'] = $_SESSION['store_id'];
        $partner_list = $model_class->getPartnerList($condition);

        Tpl::output('partner_list', $partner_list);

        self::profile_menu('store_partner', 'store_partner');
        Tpl::output('menu_sign', 'store_partner');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_partner');
        Tpl::output('menu_sign1', 'partner_list');
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_partner_list');
    }

    public function store_certifiedOp()
    {

        Language::read('member_store_cert');

        $model_class = Model('store');

        $store_info = $model_class->shopStore(array('store_id' => $_SESSION['store_id']));
        if (!empty($_FILES) && is_array($_FILES)) {

            if ($_FILES['cert_autonym']['name'] != '' || $_FILES['cert_material']['name'] != '') {
                $shop_array = array(
                    'store_id' => $_SESSION['store_id']
                );
                $upload = new UploadFile();
                $upload->set('default_dir', ATTACH_AUTH);
                if ($_FILES['cert_autonym']['name'] != '') {
                    $result = $upload->upfile('cert_autonym');
                    if ($result) {
                        $shop_array['name_auth'] = '2';
                        $shop_array['store_image'] = $upload->file_name;

                        if (!empty($shop_array['store_image']) && !empty($store_info['store_image'])) {
                            @unlink(BasePath . DS . ATTACH_AUTH . DS . $store_info['store_image']);
                        }
                    } else {
                        showDialog($upload->error);
                    }
                }
                if ($_FILES['cert_material']['name'] != '') {
                    $upload->set('file_name', '');
                    $result1 = $upload->upfile('cert_material');
                    if ($result1) {
                        $shop_array['store_auth'] = '2';
                        $shop_array['store_image1'] = $upload->file_name;

                        if (!empty($shop_array['store_image1']) && !empty($store_info['store_image1'])) {
                            @unlink(BasePath . DS . ATTACH_AUTH . DS . $store_info['store_image1']);
                        }
                    } else {
                        showDialog($upload->error);
                    }
                }
                $rs = $model_class->storeUpdate($shop_array);
                if ($rs) {
                    showDialog(Language::get('nc_common_save_succ'), 'index.php?act=store&op=store_certified', 'succ');
                } else {
                    showDialog(Language::get('nc_common_save_fail'));
                }
            } else {
                showDialog(Language::get('member_store_cert_sel_file'));
            }
        }
        Tpl::output('store_info', $store_info);

        self::profile_menu('store_setting', 'store_certified');
        Tpl::output('menu_sign', 'store_setting');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_setting');
        Tpl::output('menu_sign1', 'store_cert');
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_certified_form');
    }

    public function store_printsetupOp()
    {
        $model = Model();
        $store_info = $model->table('store')->where(array('store_id' => $_SESSION['store_id']))->find();
        if (empty($store_info)) {
            showDialog(Language::get('store_storeinfo_error'), 'index.php?act=store', 'error');
        }
        if (chksubmit()) {
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST['store_printdesc'], "require" => "true", "validator" => "Length", "min" => 1, "max" => 200, "message" => Language::get('store_printsetup_desc_error'))
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showDialog($error);
            }
            $update_arr = array();

            if ($_FILES['store_stamp']['name'] != '') {
                $upload = new UploadFile();
                $upload->set('default_dir', ATTACH_STORE);
                if ($_FILES['store_stamp']['name'] != '') {
                    $result = $upload->upfile('store_stamp');
                    if ($result) {
                        $update_arr['store_stamp'] = $upload->file_name;

                        if (!empty($store_info['store_stamp'])) {
                            @unlink(BasePath . DS . ATTACH_STORE . DS . $store_info['store_stamp']);
                        }
                    }
                }
            }
            $update_arr['store_printdesc'] = $_POST['store_printdesc'];
            $rs = $model->table('store')->where(array('store_id' => $_SESSION['store_id']))->update($update_arr);
            if ($rs) {
                showDialog(Language::get('nc_common_save_succ'), 'index.php?act=store&op=store_printsetup', 'succ');
            } else {
                showDialog(Language::get('nc_common_save_fail'), 'index.php?act=store&op=store_printsetup', 'error');
            }
        } else {
            Tpl::output('store_info', $store_info);
            self::profile_menu('store_setting', 'store_printsetup');
            Tpl::output('menu_sign', 'store_setting');
            Tpl::output('menu_sign_url', 'index.php?act=store&op=store_setting');
            Tpl::output('menu_sign1', 'store_printsetup');
            //脚部文章输出
            $article = $this->_article();
            Tpl::showpage('store_printsetup');
        }
    }

    public function store_settingOp()
    {

        $model_class = Model('store');

        $setting_config = $GLOBALS['setting_config'];
        $store_id = $_SESSION['store_id'];
        $store_info = $model_class->shopStore(array('store_id' => $store_id));
        $subdomain_edit = intval($setting_config['subdomain_edit']);
        $subdomain_times = intval($setting_config['subdomain_times']);
        $store_domain_times = intval($store_info['store_domain_times']);
        $subdomain_length = explode('-', $setting_config['subdomain_length']);
        $subdomain_length[0] = intval($subdomain_length[0]);
        $subdomain_length[1] = intval($subdomain_length[1]);
        if ($subdomain_length[0] < 1 || $subdomain_length[0] >= $subdomain_length[1]) {
            $subdomain_length[0] = 3;
            $subdomain_length[1] = 12;
        }
        Tpl::output('subdomain_length', $subdomain_length);

        if (chksubmit()) {


            $_GET['store_name'] = $_POST["store_name"];
            if (!$this->checknameinner()) {
                showDialog(Language::get('store_create_store_name_exists'));
            }
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["store_name"], "require" => "true", "message" => Language::get('store_setting_name_null')),
                array("input" => $_POST["area_id"], "require" => "true", "message" => Language::get('store_save_area_null'))
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showValidateError($error);
            }
            $_POST['store_domain'] = trim($_POST['store_domain']);
            $store_domain = strtolower($_POST['store_domain']);

            if (!empty($store_domain) && $store_domain != $store_info['store_domain']) {
                $store_domain_count = strlen($store_domain);
                if ($store_domain_count < $subdomain_length[0] || $store_domain_count > $subdomain_length[1]) {
                    showMessage(Language::get('store_setting_wrong_uri') . ': ' . $setting_config['subdomain_length'], '', 'html', 'error');
                }
                if (!preg_match('/^[\w-]+$/i', $store_domain)) {
                    showDialog(Language::get('store_setting_lack_uri'));
                }
                $store = $model_class->shopStore(array(
                    'store_domain' => $store_domain
                ));

                if (!empty($store) && ($store_id != $store['store_id'])) {
                    showMessage(Language::get('store_setting_exists_uri'), '', 'html', 'error');
                }

                $subdomain_reserved = @explode(',', $setting_config['subdomain_reserved']);
                if (!empty($subdomain_reserved) && is_array($subdomain_reserved)) {
                    if (in_array($store_domain, $subdomain_reserved)) {
                        showDialog(Language::get('store_setting_invalid_uri'));
                    }
                }
                if ($subdomain_times > $store_domain_times) {
                    $param['store_domain'] = $store_domain;
                    if (!empty($store_info['store_domain'])) $param['store_domain_times'] = $store_domain_times + 1;
                    $param['store_id'] = $store_id;
                    $model_class->storeUpdate($param);
                }
                $_POST['store_domain'] = '';
            }
            $_POST['store_id'] = $store_id;


            $upload = new UploadFile();

            if (!empty($_FILES['store_logo']['name'])) {
                $upload->set('default_dir', ATTACH_STORE);
                $upload->set('thumb_width', 100);
                $upload->set('thumb_height', 100);
                $upload->set('thumb_ext', '_small');
                $upload->set('ifremove', true);
                $result = $upload->upfile('store_logo');
                if ($result) {
                    $_POST['store_logo'] = $upload->thumb_image;
                } else {
                    showDialog($upload->error);
                }
            }

            if (!empty($_POST['store_logo']) && !empty($_POST['store_old_logo'])) {
                @unlink(BasePath . DS . ATTACH_STORE . DS . $_POST['store_old_logo']);
            }

            if (!empty($_FILES['store_banner']['name'])) {
                $upload->set('default_dir', ATTACH_STORE);
                $upload->set('thumb_ext', '');
                $upload->set('file_name', '');
                $upload->set('ifremove', false);
                $result = $upload->upfile('store_banner');
                if ($result) {
                    $_POST['store_banner'] = $upload->file_name;
                } else {
                    showDialog($upload->error);
                }
            }

            if (!empty($_POST['store_banner']) && !empty($_POST['store_old_banner'])) {
                @unlink(BasePath . DS . ATTACH_STORE . DS . $_POST['store_old_banner']);
            }

            if (!empty($_FILES['store_label']['name'])) {
                $upload->set('default_dir', ATTACH_STORE);
                $upload->set('thumb_ext', '');
                $upload->set('file_name', '');
                $upload->set('ifremove', false);
                $result = $upload->upfile('store_label');
                if ($result) {
                    $_POST['store_label'] = $upload->file_name;
                } else {
                    showDialog($upload->error);
                }
            }

            if (!empty($_POST['store_label']) && !empty($_POST['store_old_label'])) {
                @unlink(BasePath . DS . ATTACH_STORE . DS . $_POST['store_old_label']);
            }


            $_POST['description'] = $_POST['store_description'];
            $model_class->setStore($_POST);
            showDialog(Language::get('nc_common_save_succ'), 'index.php?act=store&op=store_setting', 'succ');
        }

        $model_store_grade = Model('store_grade');
        $store_grade = $model_store_grade->getOneGrade($store_info['grade_id']);

        $editor_multimedia = false;
        $sg_fun = @explode('|', $store_grade['sg_function']);
        if (!empty($sg_fun) && is_array($sg_fun)) {
            foreach ($sg_fun as $fun) {
                if ($fun == 'editor_multimedia') {
                    $editor_multimedia = true;
                }
            }
        }
        Tpl::output('editor_multimedia', $editor_multimedia);
        if ($subdomain_edit == 1 && ($subdomain_times > $store_domain_times)) {
            Tpl::output('subdomain_edit', $subdomain_edit);
        }

        $model_store_gradelog = Model('store_gradelog');
        $gradelog = $model_store_gradelog->getLogInfo(array('gl_shopid' => $store_id, 'gl_allowstate' => '0', 'order' => ' gl_id desc '));
        Tpl::output('gradelog', $gradelog);

        //S脚部文章输出
        $list = $this->_article();
        //E脚部文章输出

        self::profile_menu('store_setting', 'store_setting');
        Tpl::output('store_info', $store_info);
        Tpl::output('store_grade', $store_grade);
        Tpl::output('subdomain', $setting_config['enabled_subdomain']);
        Tpl::output('subdomain_times', $setting_config['subdomain_times']);
        Tpl::output('menu_sign', 'store_setting');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_setting');
        Tpl::output('menu_sign1', 'store_setting');

        Tpl::showpage('store_setting_form');
    }

    public function store_slideOp()
    {

        $model_store = Model('store');
        $model_upload = Model('upload');

        if ($_POST['form_submit'] == 'ok') {

            $update = array();
            $update['store_slide'] = implode(',', $_POST['image_path']);
            $update['store_slide_url'] = implode(',', $_POST['image_url']);
            $update['store_id'] = $_SESSION['store_id'];
            $model_store->storeUpdate($update);


            $model_upload->delByWhere(array('upload_type' => 7, 'store_id' => $_SESSION['store_id']));
            showDialog(Language::get('nc_common_save_succ'), 'index.php?act=store&op=store_slide', 'succ');
        }


        $upload_info = $model_upload->getUploadList(array('upload_type' => 7, 'store_id' => $_SESSION['store_id']), 'file_name');
        if (is_array($upload_info) && !empty($upload_info)) {
            foreach ($upload_info as $val) {
                @unlink(ATTACH_SLIDE . DS . $val['file_name']);
            }
        }
        $model_upload->delByWhere(array('upload_type' => 7, 'store_id' => $_SESSION['store_id']));

        $store_info = $model_store->getOne($_SESSION['store_id']);
        if ($store_info['store_slide'] != '' && $store_info['store_slide'] != ',,,,') {
            Tpl::output('store_slide', explode(',', $store_info['store_slide']));
            Tpl::output('store_slide_url', explode(',', $store_info['store_slide_url']));
        }
        self::profile_menu('store_setting', 'store_slide');
        Tpl::output('menu_sign', 'store_setting');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_setting');
        Tpl::output('menu_sign1', 'store_slide');
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_slide_form');
    }

    public function silde_image_uploadOp()
    {
        $upload = new UploadFile();
        $upload->set('default_dir', ATTACH_SLIDE . DS . $upload->getSysSetPath());
        $upload->set('max_size', C('image_max_filesize'));

        $result = $upload->upfile($_POST['id']);


        $output = array();
        if (!$result) {

            if (strtoupper(CHARSET) == 'GBK') {
                $upload->error = Language::getUTF8($upload->error);
            }
            $output['error'] = $upload->error;
            echo json_encode($output);
            die;
        }

        $img_path = $upload->getSysSetPath() . $upload->file_name;


        $model_upload = Model('upload');

        if (intval($_POST['file_id']) > 0) {
            $file_info = $model_upload->getOneUpload($_POST['file_id']);
            @unlink(ATTACH_SLIDE . DS . $file_info['file_name']);

            $update_array = array();
            $update_array['upload_id'] = intval($_POST['file_id']);
            $update_array['file_name'] = $img_path;
            $update_array['file_size'] = $_FILES[$_POST['id']]['size'];
            $model_upload->update($update_array);

            $output['file_id'] = intval($_POST['file_id']);
            $output['id'] = $_POST['id'];
            $output['file_name'] = $img_path;
            echo json_encode($output);
            die;
        } else {

            $insert_array = array();
            $insert_array['file_name'] = $img_path;
            $insert_array['upload_type'] = '7';
            $insert_array['file_size'] = $_FILES[$_POST['id']]['size'];
            $insert_array['store_id'] = $_SESSION['store_id'];
            $insert_array['upload_time'] = time();

            $result = $model_upload->add($insert_array);

            if (!$result) {
                @unlink(ATTACH_SLIDE . DS . $img_path);
                $output['error'] = Language::get('store_slide_upload_fail', 'UTF-8');
                echo json_encode($output);
                die;
            }

            $output['file_id'] = $result;
            $output['id'] = $_POST['id'];
            $output['file_name'] = $img_path;
            echo json_encode($output);
            die;
        }
    }

    public function dorp_imgOp()
    {

        $model_upload = Model('upload');
        $file_info = $model_upload->getOneUpload(intval($_GET['file_id']));
        if (!$file_info) {
            $img_src = str_replace('..', '', $_GET['img_src']);
            @unlink(ATTACH_SLIDE . DS . $img_src);
        } else {
            @unlink(ATTACH_SLIDE . DS . $file_info['file_name']);
            $model_upload->del(intval($_GET['file_id']));
        }
        echo json_encode(array('succeed' => Language::get('nc_common_save_succ', 'UTF-8')));
        die;
    }

    public function themeOp()
    {

        $store_class = Model('store');
        $store_info = $store_class->shopStore(array(
            'store_id' => $_SESSION['store_id']
        ));

        $style_data = array();
        $style_configurl = BASE_TPL_PATH . DS . 'store' . DS . 'style' . DS . "styleconfig.php";
        if (file_exists($style_configurl)) {
            include_once($style_configurl);
        }

        if (strtoupper(CHARSET) == 'GBK') {
            $style_data = Language::getGBK($style_data);
        }

        $curr_store_theme = !empty($store_info['store_theme']) ? $store_info['store_theme'] : 'default';

        $curr_image = TEMPLATES_PATH . '/store/style/' . $curr_store_theme . '/images/preview.jpg';
        $curr_theme = array(
            'curr_name' => $curr_store_theme,
            'curr_truename' => $style_data[$curr_store_theme]['truename'],
            'curr_image' => $curr_image
        );
        $theme_model = Model('store_theme');
        $state = $theme_model->getShowStyle($curr_store_theme);
        Tpl::output('style_state', $state);

        $grade_class = Model('store_grade');
        $grade = $grade_class->getOneGrade($store_info['grade_id']);

        $themes = explode('|', $grade['sg_template']);

        foreach ($style_data as $key => $val) {
            if (in_array($key, $themes)) {
                $theme_list[$key] = array(
                    'name' => $key,
                    'truename' => $val['truename'],
                    'image' => TEMPLATES_PATH . '/store/style/' . $key . '/images/preview.jpg'
                );
            }
        }

        self::profile_menu('store_theme', 'store_theme');
        Tpl::output('menu_sign', 'store_theme');
        Tpl::output('store_info', $store_info);
        Tpl::output('curr_theme', $curr_theme);
        Tpl::output('theme_list', $theme_list);
        Tpl::output('menu_sign_url', 'index.php?act=store&op=theme');
        Tpl::output('menu_sign1', 'valid_theme');
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_theme');
    }

    public function set_themeOp()
    {

        $lang = Language::getLangContent();
        $style = isset($_GET['style_name']) ? trim($_GET['style_name']) : null;

        if (!empty($style) && file_exists(BASE_TPL_PATH . DS . '/store/style/' . $style . '/images/preview.jpg')) {
            $store_class = Model('store');
            $rs = $store_class->storeUpdate(array('store_id' => $_SESSION['store_id'], 'store_theme' => $style));
            showDialog($lang['store_theme_congfig_success'], 'reload', 'succ');
        } else {
            showDialog($lang['store_theme_congfig_fail'], '', 'succ');
        }
    }

    public function store_couponOp()
    {
        $model_coupon = Model('coupon');

        if (!empty($_GET['type'])) {

            $model_coupon_class = Model('coupon_class');
            $condition = array();
            $condition['class_show'] = '1';
            $condition['order'] = 'class_sort desc,class_id desc';
            $class_list = $model_coupon_class->getCouponClass($condition);
            if (empty($class_list)) {
                Tpl::output('msg', Language::get('store_coupon_null_class'));
                Tpl::showpage('../msg', 'null_layout');
                exit;
            }


            if (trim($_GET['type'] == 'edit')) {
                $param = array();
                $coupon_array = array();

                $coupon_id = intval($_GET['coupon_id']);


                if (!$model_coupon->checkCouponBelongStore($coupon_id, $_SESSION['store_id'])) {
                    showMessage(Language::get('store_coupon_error'), '', '', 'error');
                }


                $param['coupon_id'] = $coupon_id;
                $coupon_array = $model_coupon->getCoupon($param);
                $coupon_array = $coupon_array[0];
                $old_pic = $coupon_array['coupon_pic'];
                if ($coupon_array['coupon_lock'] == '2') {
                    Tpl::output('turnoff', yes);
                }
                $coupon_array['coupon_pic'] = $coupon_array['coupon_pic'] != '' ? $coupon_array['coupon_pic'] : SiteUrl . DS . ATTACH_COUPON . DS . 'default.gif';
                $coupon_array['coupon_desc'] = htmlspecialchars_decode($coupon_array['coupon_desc']);
                $coupon_array['coupon_start_date'] = date('Y-m-d', $coupon_array['coupon_start_date']);
                $coupon_array['coupon_end_date'] = date('Y-m-d', $coupon_array['coupon_end_date']);
                Tpl::output('coupon', $coupon_array);
                Tpl::output('old_pic', $old_pic);
            }

            Tpl::output('coupon_class', $class_list);
            Tpl::output('type', $_GET['type']);
            //脚部文章输出
            $article = $this->_article();
            Tpl::showpage('member_coupon.form', 'null_layout');
            die;
        }


        if (chksubmit()) {

            if ($_POST['type'] != '') {
                $validate = new Validate();
                $validate->validateparam = array(
                    array('input' => trim($_POST['coupon_name']), 'require' => true, 'message' => Language::get('store_coupon_name_null')),
                    array('input' => trim($_POST['coupon_value']), 'require' => true, 'validator' => 'Currency', 'message' => Language::get('store_coupon_price_error')),
                    array('input' => $_POST['start_time'], 'require' => true, 'message' => Language::get('store_coupon_start_time_null')),
                    array('input' => $_POST['end_time'], 'require' => true, 'message' => Language::get('store_coupon_end_time_null'))
                );
                $error = $validate->validate();
                if ($error) {
                    showValidateError($error);
                }
                switch ($_POST['type']) {
                    case 'edit':
                        $flag = false;
                        $filename = '';
                        $update = array();
                        $update['coupon_title'] = trim($_POST['coupon_name']);
                        $update['coupon_price'] = trim($_POST['coupon_value']);
                        $update['coupon_desc'] = htmlspecialchars(trim($_POST['coupon_desc']));
                        $update['coupon_pic'] = trim($_POST['coupon_pic']);
                        $date = explode('-', $_POST['start_time']);
                        $update['coupon_start_date'] = mktime(0, 0, 0, $date[1], $date[2], $date[0]);
                        unset($date);
                        $date = explode('-', $_POST['end_time']);
                        $update['coupon_end_date'] = mktime(0, 0, 0, $date[1], $date[2], $date[0]);
                        unset($date);
                        $update['coupon_allowstate'] = '0';
                        $update['coupon_class_id'] = $_POST['coupon_class'];
                        $where['coupon_id'] = trim($_POST['coupon_id']);
                        $where['store_id'] = $_SESSION['store_id'];
                        if ($model_coupon->update_coupon($update, $where)) {
                            showDialog(Language::get('store_coupon_update_success'), 'index.php?act=store&op=store_coupon', 'succ', 'CUR_DIALOG.close();');
                        } else {
                            showDialog(Language::get('store_coupon_update_fail'));
                        }
                        break;
                    case 'add':
                        $update = array();
                        $update['coupon_title'] = trim($_POST['coupon_name']);
                        $update['coupon_price'] = trim($_POST['coupon_value']);
                        $update['coupon_desc'] = htmlspecialchars(trim($_POST['coupon_desc']));
                        $update['coupon_pic'] = trim($_POST['coupon_pic']);
                        $date = explode('-', $_POST['start_time']);
                        $update['coupon_start_date'] = mktime(0, 0, 0, $date[1], $date[2], $date[0]);
                        unset($date);
                        $date = explode('-', $_POST['end_time']);
                        $update['coupon_end_date'] = mktime(0, 0, 0, $date[1], $date[2], $date[0]);
                        unset($date);
                        $update['coupon_allowstate'] = '0';
                        $update['coupon_state'] = '2';
                        $update['store_id'] = $_SESSION['store_id'];
                        $update['coupon_class_id'] = $_POST['coupon_class'];
                        $update['coupon_add_date'] = time();
                        if ($model_coupon->add_coupon($update)) {
                            showDialog(Language::get('store_coupon_add_success'), 'index.php?act=store&op=store_coupon', 'succ', 'CUR_DIALOG.close();');
                        } else {
                            showDialog(Language::get('store_coupon_add_fail'));
                        }
                        break;

                }

            }

        }

        if (trim($_GET['coupon_id'] != '')) {

            $update = array();
            $id_array = explode(',', trim($_GET['coupon_id']));
            $coupon_id = "'" . implode("','", $id_array) . "'";
            $update['coupon_id_in'] = trim($coupon_id);
            $update['store_id'] = $_SESSION['store_id'];
            $update['coupon_allowstate2'] = '1';
            if ($model_coupon->del_coupon($update)) {
                showDialog(Language::get('store_coupon_del_success'), 'index.php?act=store&op=store_coupon', 'succ');
                exit;
            } else {
                showDialog(Language::get('store_coupon_del_fail'), 'index.php?act=store&op=store_coupon');
                exit;
            }
        }

        $page = new Page();
        $page->setEachNum(8);
        $page->setStyle('admin');

        $condition = array();

        $condition = array();
        if (trim($_GET['key']) != '' && trim($_GET['key']) != Language::get('store_coupon_name')) {

            $condition['coupon_name_like'] = trim($_GET['key']);

        }
        if ($_GET['add_time_from'] != '') {

            $time = explode('-', $_GET['add_time_from']);
            $condition['time_from'] = mktime(0, 0, 0, $time[1], $time[2], $time[0]);

        }
        if ($_GET['add_time_to'] != '') {

            $time = explode('-', $_GET['add_time_to']);
            $condition['time_to'] = mktime(0, 0, 0, $time[1], $time[2], $time[0]);

        }
        if ($_GET['add_time_to'] != '' && $_GET['add_time_from'] != '' && $condition['time_from'] > $condition['time_to']) {
            $_GET['add_time_from'] = $_GET['add_time_to'] = '';
            showMessage(Language::get('store_coupon_time_error'), '', 'html', 'error');
        }


        $condition['store_id'] = $_SESSION['store_id'];
        $coupon_list = $model_coupon->getCoupon($condition, $page);
        if (is_array($coupon_list) && !empty($coupon_list)) {
            $state = array('1' => Language::get('nc_no'), '2' => Language::get('nc_yes'));
            $allowstate = array('0' => Language::get('store_coupon_allow_state'), '1' => Language::get('store_coupon_allow_yes'), '2' => Language::get('store_coupon_allow_no'));
            foreach ($coupon_list as $k => $v) {
                $coupon_list[$k]['pic'] = $v['coupon_pic'] ? $v['coupon_pic'] : SiteUrl . DS . ATTACH_COUPON . DS . 'defatul.gif';
                $coupon_list[$k]['state'] = $state[$v['coupon_state']];
                $coupon_list[$k]['allowstate'] = $allowstate[$v['coupon_allowstate']];
            }
        }
        $model_coupon->update_coupon(array('coupon_state' => '1'), array('coupon_state' => '2', 'coupon_novalid' => true, 'store_id' => $_SESSION['store_id']));


        self::profile_menu('store_coupon', 'store_coupon');
        Tpl::output('count', count($coupon_list));
        Tpl::output('coupons', $coupon_list);
        Tpl::output('show_page', $page->show());
        Tpl::output('menu_sign', 'store_coupon');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_coupon');
        Tpl::output('menu_sign1', 'coupon_list');
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('member_coupon.index');
    }

    public function store_activityOp()
    {
        $page = new Page();
        $page->setEachNum(10);
        $page->setStyle('admin');
        $activity = Model('activity');

        $list = $activity->getList(array('activity_type' => '1', 'opening' => true, 'order' => 'activity.activity_sort asc'), $page);

        Tpl::output('list', $list);
        Tpl::output('show_page', $page->show());
        self::profile_menu('store_activity', 'store_activity');
        Tpl::output('menu_sign', 'store_activity');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_activity');
        Tpl::output('menu_sign1', 'activity_list');
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_activity.list');
    }

    public function activity_applyOp()
    {

        $activity_id = intval($_GET['activity_id']);
        if ($activity_id <= 0) {
            showMessage(Language::get('miss_argument'), 'index.php?act=store&op=store_activity', 'html', 'error');
        }
        $activity_model = Model('activity');
        $activity_info = $activity_model->getOneById($activity_id);

        if (empty($activity_info) || $activity_info['activity_type'] != '1' || $activity_info['activity_state'] != 1 || $activity_info['activity_start_date'] > time() || $activity_info['activity_end_date'] < time()) {
            showMessage(Language::get('store_activity_not_exists'), 'index.php?act=store&op=store_activity', 'html', 'error');
        }
        Tpl::output('activity_info', $activity_info);
        $list = array();
        $gc = Model('goods_class');
        $gc_list = $gc->getTreeClassList(3, array('gc_show' => 1, 'order' => 'gc_parent_id asc,gc_sort asc,gc_id asc'));
        foreach ($gc_list as $k => $gc) {
            $gc_list[$k]['gc_name'] = '';
            $gc_list[$k]['gc_name'] = str_repeat("&nbsp;", $gc['deep'] * 2) . $gc['gc_name'];
        }
        Tpl::output('gc_list', $gc_list);

        $brand = Model('brand');
        $brand_list = $brand->getBrandList(array());
        Tpl::output('brand_list', $brand_list);

        $activity_detail_model = Model('activity_detail');
        $list = $activity_detail_model->getGoodsJoinList(array('activity_id' => "$activity_id", 'store_id' => "{$_SESSION['store_id']}", 'activity_detail_state_in' => "'0','1','3'", 'group' => 'activity_detail_state asc'));

        $item_ids = array();
        if (is_array($list) and !empty($list)) {
            foreach ($list as $k => $v) {
                $item_ids[] = $v['item_id'];
            }
        }
        Tpl::output('list', $list);
        //S脚部文章输出
        $list = $this->_article();
        //E脚部文章输出

        $condition = array();
        if ($_GET['gc_id'] != '') {
            $condition['gc_id'] = intval($_GET['gc_id']);
        }
        if ($_GET['brand_id'] != '') {
            $condition['brand_id'] = intval($_GET['brand_id']);
        }
        if (trim($_GET['name']) != '') {
            $condition['keyword'] = trim($_GET['name']);
        }
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['goods_show'] = '1';
        $condition['goods_state'] = '0';
        $condition['goods_store_state'] = 'open';
        if (!empty($item_ids)) {
            $condition['no_goods_id'] = implode(',', $item_ids);
        }
        $page = new Page();
        $page->setEachNum(16);
        $page->setStyle('admin');
        $page->setNowPage(empty($_GET['curpage']) ? 1 : intval($_GET['curpage']));
        $goods = Model('goods');
        $goods_list = $goods->getGoods($condition, $page, '*', 'brand');
        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $page->show());
        Tpl::output('search', $_GET);

        self::profile_menu('activity_apply', 'activity_apply');
        Tpl::output('menu_sign', 'store_activity');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_activity');
        Tpl::output('menu_sign1', 'activity_apply');
        Tpl::showpage('store_activity.apply');
    }

    public function quicklinkOp()
    {
        if (chksubmit()) {
            $store = Model('store');
            $store->storeUpdate(array('store_id' => $_SESSION['store_id'], 'store_center_quicklink' => serialize($_POST['doc_content'])));
            showDialog(Language::get('nc_common_save_succ'), 'reload', 'succ', empty($_GET['inajax']) ? '' : 'CUR_DIALOG.close();');
        }
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_quicklink', 'null_layout');
    }

    public function activity_apply_saveOp()
    {

        if (empty($_POST['item_id'])) {
            showDialog(Language::get('store_activity_choose_goods'), 'index.php?act=store&op=store_activity');
        }
        $activity_id = intval($_POST['activity_id']);
        if ($activity_id <= 0) {
            showDialog(Language::get('miss_argument'), 'index.php?act=store&op=store_activity');
        }

        $activity_model = Model('activity');
        $activity = $activity_model->getOneByid($activity_id);

        if (empty($activity) || $activity['activity_type'] != '1' || $activity['activity_state'] != '1' || $activity['activity_start_date'] > time() || $activity['activity_end_date'] < time()) {
            showDialog(Language::get('store_activity_not_exists'), 'index.php?act=store&op=store_activity');
        }
        $activity_detail = Model('activity_detail');
        $list = $activity_detail->getList(array('store_id' => "{$_SESSION['store_id']}", 'activity_id' => "$activity_id"));
        $ids = array();
        $ids_state2 = array();
        if (is_array($list) and !empty($list)) {
            foreach ($list as $ad) {
                $ids[] = $ad['item_id'];
                if ($ad['activity_detail_state'] == '2') {
                    $ids_state2[] = $ad['item_id'];
                }
            }
        }

        $condition_goods = array();
        $condition_goods['store_id'] = $_SESSION['store_id'];
        $condition_goods['goods_show'] = '1';
        $condition_goods['goods_state'] = '0';
        $condition_goods['goods_store_state'] = 'open';
        foreach ($_POST['item_id'] as $item_id) {
            $item_id = intval($item_id);
            if (!in_array($item_id, $ids)) {
                $condition_goods['goods_id'] = "$item_id";
                $input = array();
                $input['activity_id'] = $activity_id;
                $goods = Model('goods');
                $item = $goods->getGoods($condition_goods, '', '*', 'store');
                $item = $item[0];
                if (empty($item)) {
                    continue;
                }
                $input['item_name'] = $item['goods_name'];
                $input['item_id'] = $item_id;
                $input['store_id'] = $item['store_id'];
                $input['store_name'] = $item['store_name'];
                $activity_detail->add($input);
            } elseif (in_array($item_id, $ids_state2)) {
                $input = array();
                $input['activity_detail_state'] = '0';
                $activity_detail->updateList($input, array('item_id' => $item_id));
            }
        }
        showDialog(Language::get('store_activity_submitted'), 'reload', 'succ');
    }

    public function goods_class_ajaxOp()
    {
        $rzt['done'] = true;
        if (!isset($_GET['ajax']) and $_GET['column'] != 'stc_state') {
            showMessage(Language::get('invalid_request'), '', 'html', 'error');
        } elseif ($_GET['id'] == '') {
            $rzt['done'] = false;
            $rzt['msg'] = Language::get('miss_argument');
        } elseif ($_GET['value'] == '') {
            $rzt['done'] = false;
            $rzt['msg'] = Language::get('miss_argument');
        } elseif ($_GET['column'] == '') {
            $rzt['done'] = false;
            $rzt['msg'] = Language::get('miss_argument');
        } else {
            switch ($_GET['column']) {
                case 'stc_name':
                    break;
                case 'stc_sort':
                    if (!preg_match("/^\d+$/", $_GET['value'])) {
                        $rzt['done'] = false;
                        $rzt['msg'] = Language::get('wrong_argument');
                    } elseif ($_GET['value'] < 0 or $_GET['value'] > 255) {
                        $rzt['done'] = false;
                        $rzt['msg'] = Language::get('wrong_argument');
                    }
                    break;
                case 'stc_state':
                    if (!in_array($_GET['value'], array('0', '1'))) {
                        $rzt['done'] = false;
                        $rzt['msg'] = Language::get('invalid_request');
                    }
                    break;
                default:
                    $rzt['done'] = false;
                    $rzt['msg'] = Language::get('wrong_argument');
            }
            if ($rzt['done']) {
                $input = array();
                $input[$_GET['column']] = $_GET['value'];
                $model_class = Model('my_goods_class');
                $result = $model_class->editGoodsClass($input, intval($_GET['id']));
                if ($result) {
                    $class_info = $model_class->getClassInfo(array('stc_id' => intval($_GET['id'])));
                    switch ($_GET['column']) {
                        case 'stc_name':
                            $rzt['retval'] = $class_info['stc_name'];
                            break;
                        case 'stc_sort':
                            $rzt['retval'] = $class_info['stc_sort'];
                            break;
                    }
                } else {
                    $rzt['done'] = false;
                    $rzt['msg'] = Language::get('store_goods_class_ajax_update_fail');
                }
            }
        }
        echo json_encode($rzt);
    }

    public function ajax_change_store_codeOp()
    {

        $store_model = Model('store');


        $store_info = $store_model->getOne($_SESSION['store_id']);
        if ($store_info['store_code'] != 'default_qrcode.png') @unlink(ATTACH_STORE . DS . $store_info['store_code']);


        require_once(BasePath . DS . 'resource' . DS . 'phpqrcode' . DS . 'index.php');
        $PhpQRCode = new PhpQRCode();
        if (C('enabled_subdomain') == 1 && $store_info['store_domain'] != '') {
            $PhpQRCode->set('date', ncUrl(array('act' => 'show_store', 'id' => $_SESSION['store_id']), 'store', $store_info['store_domain']));
        } else {
            $PhpQRCode->set('date', SiteUrl . DS . ncUrl(array('act' => 'show_store', 'id' => $_SESSION['store_id']), 'store', $store_info['store_domain']));
        }
        $PhpQRCode->set('pngTempDir', ATTACH_STORE . DS);
        $url = $PhpQRCode->init();
        $store_model->storeUpdate(array('store_code' => $url, 'store_id' => $_SESSION['store_id']));

        echo json_encode($url);
    }

    private function profile_menu($menu_type, $menu_key = '')
    {
        Language::read('member_layout');
        $menu_array = array();
        switch ($menu_type) {
            case 'store_goods_class':
                $menu_array = array(
                    1 => array('menu_key' => 'store_goods_class', 'menu_name' => Language::get('nc_member_path_goods_class'), 'menu_url' => 'index.php?act=store&op=store_goods_class'));
                break;
            case 'store_order':
                $menu_array = array(
                    1 => array('menu_key' => 'store_order', 'menu_name' => Language::get('nc_member_path_all_order'), 'menu_url' => 'index.php?act=store&op=store_order'),
                    2 => array('menu_key' => 'order_pay', 'menu_name' => Language::get('nc_member_path_wait_pay'), 'menu_url' => 'index.php?act=store&op=store_order&state_type=order_pay'),
                    12 => array('menu_key' => 'pay_confirm', 'menu_name' => Language::get('nc_member_path_pay_confirm'), 'menu_url' => 'index.php?act=store&op=store_order&state_type=pay_confirm'),
                    3 => array('menu_key' => 'order_submit', 'menu_name' => Language::get('nc_member_path_submitted'), 'menu_url' => 'index.php?act=store&op=store_order&state_type=order_submit'),
                    4 => array('menu_key' => 'order_no_shipping', 'menu_name' => Language::get('nc_member_path_wait_send'), 'menu_url' => 'index.php?act=store&op=store_order&state_type=order_no_shipping'),
                    5 => array('menu_key' => 'order_shipping', 'menu_name' => Language::get('nc_member_path_sent'), 'menu_url' => 'index.php?act=store&op=store_order&state_type=order_shipping'),
                    6 => array('menu_key' => 'order_finish', 'menu_name' => Language::get('nc_member_path_finished'), 'menu_url' => 'index.php?act=store&op=store_order&state_type=order_finish'),
                    7 => array('menu_key' => 'order_cancel', 'menu_name' => Language::get('nc_member_path_canceled'), 'menu_url' => 'index.php?act=store&op=store_order&state_type=order_cancel'),
                    8 => array('menu_key' => 'order_refund', 'menu_name' => Language::get('nc_member_path_refund'), 'menu_url' => 'index.php?act=store&op=store_order&state_type=order_refund'),
                );
                break;
            case 'payment':
                $menu_array = array(
                    1 => array('menu_key' => 'payment', 'menu_name' => Language::get('nc_member_path_payment_list'), 'menu_url' => 'index.php?act=store&op=payment')
                );
                break;
            case 'store_navigation':
                $menu_array = array(
                    1 => array('menu_key' => 'store_navigation', 'menu_name' => Language::get('nc_member_path_nav_list'), 'menu_url' => 'index.php?act=store&op=store_navigation'),
                    2 => array('menu_key' => 'store_navigation_add', 'menu_name' => Language::get('store_navigation_new'), 'menu_url' => 'index.php?act=store&op=store_navigation_add&type=add')
                );
                break;
            case 'store_navigation_edit':
                $menu_array = array(
                    1 => array('menu_key' => 'store_navigation', 'menu_name' => Language::get('nc_member_path_nav_list'), 'menu_url' => 'index.php?act=store&op=store_navigation'),
                    2 => array('menu_key' => 'store_navigation_add', 'menu_name' => Language::get('store_navigation_new'), 'menu_url' => 'index.php?act=store&op=store_navigation_add&type=add'),
                    3 => array('menu_key' => 'store_navigation_edit', 'menu_name' => Language::get('store_navigation_edit'), 'menu_url' => '#')
                );
                break;
            case 'store_partner':
                $menu_array = array(
                    1 => array('menu_key' => 'store_partner', 'menu_name' => Language::get('nc_member_path_partner_list'), 'menu_url' => 'index.php?act=store&op=store_partner')
                );
                break;
            case 'store_setting':
                $menu_array = array(
                    1 => array('menu_key' => 'store_setting', 'menu_name' => Language::get('nc_member_path_store_config'), 'menu_url' => 'index.php?act=store&op=store_setting'),
                    2 => array('menu_key' => 'store_callcenter', 'menu_name' => Language::get('nc_member_path_store_callcenter'), 'menu_url' => 'index.php?act=store_callcenter'),
                    3 => array('menu_key' => 'store_certified', 'menu_name' => Language::get('nc_member_path_store_cert'), 'menu_url' => 'index.php?act=store&op=store_certified'),
                    4 => array('menu_key' => 'store_map', 'menu_name' => Language::get('nc_member_path_store_map'), 'menu_url' => 'index.php?act=map'),
                    5 => array('menu_key' => 'store_slide', 'menu_name' => Language::get('nc_member_path_store_slide'), 'menu_url' => 'index.php?act=store&op=store_slide'),
                    6 => array('menu_key' => 'store_printsetup', 'menu_name' => Language::get('nc_member_path_store_printsetup'), 'menu_url' => 'index.php?act=store&op=store_printsetup')
                );
                break;
            case 'store_theme':
                $menu_array = array(
                    1 => array('menu_key' => 'store_theme', 'menu_name' => Language::get('nc_member_path_valid_theme'), 'menu_url' => 'index.php?act=store&op=theme')
                );
                break;
            case 'store_coupon':
                $menu_array = array(
                    1 => array('menu_key' => 'store_coupon', 'menu_name' => Language::get('nc_member_path_coupon_list'), 'menu_url' => 'index.php?act=store&op=store_coupon')
                );
                break;
            case 'store_activity':
                $menu_array = array(
                    1 => array('menu_key' => 'store_activity', 'menu_name' => Language::get('nc_member_path_activity_list'), 'menu_url' => 'index.php?act=store&op=store_activity')
                );
                break;
            case 'activity_apply':
                $menu_array = array(
                    1 => array('menu_key' => 'store_activity', 'menu_name' => Language::get('nc_member_path_activity_list'), 'menu_url' => 'index.php?act=store&op=store_activity'),
                    2 => array('menu_key' => 'activity_apply', 'menu_name' => Language::get('nc_member_path_join_activity'), 'menu_url' => '')
                );
                break;
        }
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }

    public function store_goods_recordsOp()
    {
        $model = Model('goods_records');
        /*
        if(!empty($_POST) && !empty($_POST["is_seller"]) && !empty($_POST["store_id"]))
        {
            $model = Model('store');
            $row = $model->where(array('store_id'=>$_POST['store_id']))->find();
            $_POST['store_name'] = $row['store_name'];
            $_POST['declare_date'] = date('Y-m-d H:i:s',time());
            $_POST['input_date'] = date('Y-m-d H:i:s',time());
            if(Db::insert('goods_records',$_POST))
            {
                echo "<script>alert('信息提交成功！');</script>";
            }
        }
        */
        //分页
        $page = new page();
        $page->setEachNum(10);
        $page->setStyle('admin');

        if (!empty($_GET['goods_name']) and $_GET['goods_name'] != '请输入商品名称') {
            $param['store_id'] = $_SESSION['store_id'];
            $param['order'] = 'id';
            $param['like_goods_name'] = $_GET['goods_name'];
            $goods_records_list = $model->getGoodsRcords($param, $page, '*');
        } else {
            $param['store_id'] = $_SESSION['store_id'];
            $param['order'] = 'id';
            $goods_records_list = $model->getGoodsRcords($param, $page, '*');
        }
        //S脚部文章输出
        $list = $this->_article();
        //E脚部文章输出

        self::profile_menu('store_goods_record', 'store_goods_record');
        Tpl::output('menu_sign', 'store_goods_record');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_goods_record');
        Tpl::output('menu_sign1', 'goods_record');
        Tpl::output('goods_records_list', $goods_records_list);
        Tpl::output('page', $page->show());


        Tpl::showpage('store_goods_record');
    }


    public function store_goods_record_delOp()
    {
        if (!empty($_GET['id'])) {
            if (Db::delete('goods_records', "id=" . $_GET['id'])) {
                echo "<script>alert('Delete success!');location.href='index.php?act=store&op=store_goods_records';</script>";
            } else {
                echo "<script>alert('Delete failed!');location.href='index.php?act=store&op=store_goods_records';</script>";
            }
        } else {
            echo "<script>alert('找不到对应商品!');location.href='index.php?act=store&op=store_goods_records';</script>";
        }
    }


    public function store_goods_editOp()
    {
        $model = Model('goods_records');
        if (!empty($_GET['id'])) {
            $row = $model->where(array('id' => $_GET['id']))->find();
        }

        if (!empty($_POST)) {
            $_POST['examine'] = 0;
            if (Db::update('goods_records', $_POST, "id=" . $_GET['id'])) {
                echo "<script>alert('编辑成功！');location.href='index.php?act=store&op=store_goods_records';</script>";
            }
        }

        Tpl::output('row', $row);
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_goods_edit');
    }

    public function get_recordOp()
    {
        $model = Model('goods_records');
        if (!empty($_GET['id'])) {

            $row = $model->where("id= " . $_GET['id'] . "")->find();

            $item = $model->create_item($row);

            $model->get_packets($item);
        }
    }

    public function store_goods_done_listOp()
    {
        $page = new page();
        $page->setEachNum(2);
        $page->setStyle('admin');

        $model = Model('goods_records');
        $goods_records_done_list = $model->getGoodsRcords($_SESSION["store_id"], $page, '*');

        //$goods_records_done_list = $model->where(array('store_id'=>$_SESSION["store_id"]))->order('id')->page(10)->select();
        //$page = $model->showpage();
        self::profile_menu('store_goods_done_list', 'store_goods_done_list');
        Tpl::output('menu_sign', 'store_goods_done_list');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_goods_done_list');
        Tpl::output('page', $page->show());
        Tpl::output('goods_records_done_list', $goods_records_done_list);
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_goods_done_list');
    }

    public function store_goods_record_listOp()
    {
        $model = Model('goods_records');

        if (!empty($_POST)) {
            if (!empty($_POST['goods_name'])) {
                $goods_records_list = $model->where(array('store_id' => $_SESSION['store_id'], 'goods_name' => $_POST['goods_name']))->order('id')->page(10)->select();
            }

            $page = $model->showpage();

            Tpl::output('goods_records_list', $goods_records_list);

        } else {
            $goods_records_list = $model->where(array('store_id' => $_SESSION["store_id"]))->page(10)->select();
            $page = $model->showpage();
            Tpl::output('goods_records_list', $goods_records_list);
        }
        self::profile_menu('store_goods_record_list', 'store_goods_record_list');
        Tpl::output('menu_sign', 'store_goods_record_list');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_goods_record_list');
        Tpl::output('page', $page);
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_goods_record_list');
    }


    public function store_warehouse_addOp()
    {
        if (!empty($_POST)) {
            if (empty($_POST['id_all'])) {
                unset($_POST['id_all']);
                $_POST['warehouse'] = 1;
                if (Db::update('goods_records', $_POST, "id=" . $_POST['id'])) {
                    echo "<script>alert('申报成功！');</script>";
                }

            } elseif (!empty($_POST['id_all'])) {
                $_POST['warehouse'] = 1;
                $data = $_POST;
                unset($data['id_all']);
                unset($data['id']);
                //组装字符串
                $id = explode(',', $_POST['id_all']);
                $ids = '';
                foreach ($id as $k => $v) {
                    $ids .= "'" . $v . "'" . ",";
                }
                $ids = substr($ids, 0, -1);
                //print_R($ids);exit;
                if (Db::update('goods_records', $data, " id in (" . $ids . ")")) {
                    echo "<script>alert('申报成功！');</script>";
                }
            }

        }

        $page = new page();
        $model = Model('goods_records');
        $goods_records_list = $model->where(array('store_id' => $_SESSION["store_id"], 'examine' => 1))->page(10)->select();
        $page = $model->showpage();
        self::profile_menu('store_warehouse_add', 'store_warehouse_add');
        Tpl::output('menu_sign', 'store_warehouse_add');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_warehouse_add');
        Tpl::output('menu_sign1', 'store_warehouse_add');
        Tpl::output('goods_records_list', $goods_records_list);
        Tpl::output('page', $page);
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_warehouse_add');
    }


    public function store_warehouse_listOp()
    {
        $model = Model('goods_records');
        $param['store_id'] = $_SESSION['store_id'];
        if (!empty($_POST)) {
            $condition = $_POST;
            foreach ($condition as $k => $v) {
                if (!empty($v)) {
                    $param[$k] = $v;
                }
            }

            $row = $model->where($param)->page(10)->select();
            $page = $model->showpage();
            Tpl::output('page', $page);
            Tpl::output('row', $row);
        } else {

            $sql = "select * from `shopnc_goods_records` where `warehouse`=1 group by `jincang_input`";
            $row = Db::getAll($sql);

            $page = $model->showpage();
            Tpl::output('page', $page);
            Tpl::output('row', $row);
        }
        self::profile_menu('store_warehouse_list', 'store_warehouse_list');
        Tpl::output('menu_sign', 'store_warehouse_list');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_warehouse_list');
        Tpl::output('menu_sign1', 'store_warehouse_list');
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_warehouse_list');
    }

    public function store_warehouse_fileOp()
    {
        $n = 0;
        $model = Model('store');
        if (!empty($_FILES['file']['name'])) {
            $file = $_FILES['file'];


            if ($file['size'] > 2 * 1024 * 1024) {
                echo "<script>parent.error('上传文件超过2M')</script>";
            }


            if ($file['type'] !== 'application/vnd.ms-excel') {
                echo "<script>parent.error('上传文件类型不对')</script>";
            }


            $dir = date('h') . '/' . date('i') . '/' . date('s') . '/';

            if (!file_exists(dirname(dirname(__FILE__)) . '/tmp/warehouse_list' . $dir))
                mkdir(dirname(dirname(__FILE__)) . '/tmp/warehouse_list/' . $dir, '0777', true);


            $newName = MD5(time() . rand(000, 9999)) . strchr($file['name'], '.');

            if (move_uploaded_file($file['tmp_name'], dirname(dirname(__FILE__)) . '/tmp/warehouse_list/' . $dir . $newName)) {
                $arr = 'tmp/warehouse_list/' . $dir . $newName;

                $file = fopen($arr, 'r');

                while ($data = fgetcsv($file)) {
                    $data[0] = mb_convert_encoding($data[0], "UTF-8", "GBK");
                    $data[1] = mb_convert_encoding($data[1], "UTF-8", "GBK");
                    $data[2] = mb_convert_encoding($data[2], "UTF-8", "GBK");
                    $data[3] = mb_convert_encoding($data[3], "UTF-8", "GBK");
                    $data[4] = mb_convert_encoding($data[4], "UTF-8", "GBK");
                    $data[5] = mb_convert_encoding($data[5], "UTF-8", "GBK");
                    $data[6] = mb_convert_encoding($data[6], "UTF-8", "GBK");
                    $data[7] = mb_convert_encoding($data[7], "UTF-8", "GBK");
                    $data[8] = mb_convert_encoding($data[8], "UTF-8", "GBK");
                    $data[9] = mb_convert_encoding($data[9], "UTF-8", "GBK");
                    $data[10] = mb_convert_encoding($data[10], "UTF-8", "GBK");
                    $data[11] = mb_convert_encoding($data[11], "UTF-8", "GBK");
                    $data[12] = mb_convert_encoding($data[12], "UTF-8", "GBK");
                    $data[13] = mb_convert_encoding($data[13], "UTF-8", "GBK");
                    $data[14] = mb_convert_encoding($data[14], "UTF-8", "GBK");
                    $data[15] = mb_convert_encoding($data[15], "UTF-8", "GBK");
                    if ($data[0] == '商品申请编号') continue;


                    $brr = array();
                    $brr['is_seller'] = $_SESSION['is_seller'];
                    $brr['store_id'] = $_SESSION['store_id'];
                    $brr['goods_apply_num'] = $data[0];
                    $brr['operation_type'] = $data[1];
                    $brr['goods_article_num'] = $data[2];
                    $brr['goods_name'] = $data[3];
                    $brr['goods_format'] = $data[4];
                    $brr['goods_commodity_code'] = $data[5];
                    $brr['declaration_unit'] = $data[6];
                    $brr['declaration_price'] = $data[7];
                    $brr['tax_num'] = $data[8];
                    $brr['gross_weight'] = $data[9];
                    $brr['net_weight'] = $data[10];
                    $brr['goods_not'] = $data[11];
                    $brr['ieflag'] = $data[12];
                    $brr['tax_name'] = $data[13];
                    $brr['goods_note'] = $data[14];
                    $brr['bar_code'] = $data[15];
                    $brr['declare_date'] = date('Y-m-d h:i:s', time());
                    $brr['input_date'] = date('Y-m-d h:i:s', time());
                    $row = $model->where(array('store_id' => $_SESSION['store_id']))->find();
                    $brr['store_name'] = $row['store_name'];

                    $param['table'] = 'goods_records';
                    $param['field'] = 'goods_name';
                    $param['value'] = $brr['goods_name'];
                    $row = Db::getRow('goods_records', "*");
                    if (!empty($row['goods_custom_num'])) {
                        $id .= $row['id'] . ',';

                    } else {
                        ++$n;
                    }

                }
                fclose($file);
                if ($n > 0) {
                    echo "<script>parent.success(0)</script>";

                } else {
                    echo "<script>parent.success(1,'" . $id . "')</script>";
                }

            } else {
                echo "<script>parent.success(0)</script>";
            }
        }
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_warehouse_file');
    }

    public function store_warehouse_commodityOp()
    {
        $goods = Model('goods_records');
        $row = $goods->where(array('store_id' => $_SESSION['store_id'], 'warehouse' => 2))->page(10)->select();

        $page = $goods->showpage();
        Tpl::output('page', $page);
        Tpl::output('row', $row);

        self::profile_menu('store_warehouse_commodity', 'store_warehouse_commodity');
        Tpl::output('menu_sign', 'store_warehouse_commodity');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_warehouse_commodity');
        Tpl::output('menu_sign1', 'store_warehouse_commodity');
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_warehouse_commodity');
    }

    public function store_warehouse_detailOp()
    {
        $model = Model('goods_records');
        if (!empty($_GET['id'])) {
            $row = $model->where(array('store_id' => $_SESSION['store_id'], 'jincang_input' => $_GET['id']))->select();

            Tpl::output('row', $row);
        }
        self::profile_menu('store_warehouse_detail', 'store_warehouse_detail');
        Tpl::output('menu_sign', 'store_warehouse_detail');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_warehouse_detail');
        Tpl::output('menu_sign1', 'store_warehouse_detail');
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_warehouse_detail');
    }

    public function store_warehouse_csvOp()
    {
        $model = Model('goods_records');
        $model->upload_csv();
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_warehouse_file');
    }

    public function store_jincang_csvOp()
    {
        $model = Model('goods_records');
        $model->jincang_cupload_csv();
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_warehouse_file');
    }


    public function company_informationOp()
    {
        $param['table'] = 'company_apply';
        $param['field'] = "store_id";
        $param['value'] = $_SESSION["store_id"];
        $row = Db::getRow($param, "*");
        self::profile_menu('company_information', 'company_information');
        Tpl::output('menu_sign', 'company_information');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=company_information');
        Tpl::output('menu_sign1', 'company_information');
        Tpl::output('row', $row);
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('company_information');
    }

    public function company_applyOp()
    {
        if (!empty($_POST)) {
            $param['table'] = 'company_apply';
            $param['field'] = "store_id";
            $param['value'] = $_SESSION["store_id"];
            $row = Db::getRow($param, "*");
            if (!empty($row)) {
                echo "<script>alert('不能重复提交申请！');</script>";

            } else {
                $_POST["store_id"] = $_SESSION["store_id"];
                $_POST["company_num"] = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 10), 1))), -8, 8);
                if ($id = Db::insert('company_apply', $_POST)) {
                    echo "<script>location.href='index.php?act=store&op=company_information'</script>";
                }
            }

        }
        self::profile_menu('company_apply', 'company_apply');
        Tpl::output('menu_sign', 'company_apply');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=company_apply');
        Tpl::output('menu_sign1', 'company_apply');
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('company_apply');
    }

    public function store_declaration_addOp()
    {
        if (!empty($_POST)) {
            $_POST["store_id"] = $_SESSION["store_id"];
            if (Db::insert('entry', $_POST)) {
                echo "<script>alert('添加成功!');</script>";
            }
        }
        self::profile_menu('store_declaration_add', 'store_declaration_add');
        Tpl::output('menu_sign', 'store_declaration_add');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_declaration_add');
        Tpl::output('menu_sign1', 'store_declaration_add');
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_declaration_add');
    }

    public function store_declaration_listOp()
    {
        $model = Model('entry');
        $row = $model->where(array('store_id' => $_SESSION['store_id']))->page(10)->select();
        $page = $model->showPage();
        self::profile_menu('store_declaration_list', 'store_declaration_list');
        Tpl::output('menu_sign', 'store_declaration_list');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_declaration_list');
        Tpl::output('menu_sign1', 'store_declaration_list');
        Tpl::output('row', $row);
        Tpl::output('page', $page);
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_declaration_list');
    }

    public function store_declaration_detailOp()
    {
        $model = Model('entry');
        if (!empty($_GET['id'])) {
            $row = $model->where(array("id" => $_GET['id']))->find();
        }
        self::profile_menu('store_declaration_detail', 'store_declaration_detail');
        Tpl::output('menu_sign', 'store_declaration_detail');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=store_declaration_detail');
        Tpl::output('menu_sign1', 'store_declaration_add');
        Tpl::output('row', $row);
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_declaration_detail');
    }

    public function store_declaration_add_fileOp()
    {
        if (!empty($_FILES['file']['name'])) {
            $file = $_FILES['file'];


            if ($file['size'] > 2 * 1024 * 1024) {
                echo "<script>alert('上传文件超过2M');</script>";
            }


            $arr = explode('|', 'image/jpeg|image/png|image/gif');
            if (!in_array($file['type'], $arr)) {
                echo "<script>alert('上传文件类型不对');</script>";
            }


            $dir = date('Y') . '/' . date('M') . '/';
            if (!file_exists('tmp/declaration' . $dir))
                mkdir('tmp/declaration/' . $dir, '0777', true);


            $newName = MD5(time() . rand(000, 9999)) . strchr($file['name'], '.');

            if (move_uploaded_file($file['tmp_name'], 'tmp/declaration/' . $dir . $newName)) {
                $arr = 'tmp/declaration/' . $dir . $newName;
                echo "<script>parent.success(1,'" . $arr . "')</script>";
            } else {
                echo "<script>parent.success(0)</script>";
            }
        }
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_declaration_add_file');
    }

    public function store_declaration_add_file2Op()
    {
        if (!empty($_FILES['file']['name'])) {
            $file = $_FILES['file'];


            if ($file['size'] > 2 * 1024 * 1024) {
                echo "<script>alert('上传文件超过2M');</script>";
            }


            $arr = explode('|', 'image/jpeg|image/png|image/gif');
            if (!in_array($file['type'], $arr)) {
                echo "<script>alert('上传文件类型不对');</script>";
            }


            $dir = date('Y') . '/' . date('M') . '/';
            if (!file_exists('tmp/declaration' . $dir))
                mkdir('tmp/declaration/' . $dir, '0777', true);


            $newName = MD5(time() . rand(000, 9999)) . strchr($file['name'], '.');

            if (move_uploaded_file($file['tmp_name'], 'tmp/declaration/' . $dir . $newName)) {
                $arr = 'tmp/declaration/' . $dir . $newName;
                echo "<script>parent.success_one(1,'" . $arr . "')</script>";
            } else {
                echo "<script>parent.success_one(0)</script>";
            }
        }
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('store_declaration_add_file2');
    }


    public function loading_listOp()
    {
        $model = Model('warehouses_lists');
        $row = $model->where(array('store_id' => $_SESSION['store_id'], 'examine' => 1))->page(10)->select();
        $page = $model->showPage();
        self::profile_menu('loading_list', 'loading_list');
        Tpl::output('menu_sign', 'loading_list');
        Tpl::output('menu_sign_url', 'index.php?act=store&op=loading_list');
        Tpl::output('menu_sign1', 'loading_list');
        Tpl::output('page', $page);
        Tpl::output('row', $row);
        //脚部文章输出
        $article = $this->_article();
        Tpl::showpage('loading_list');
    }

    public function loading_list_detailOp()
    {
        $model = Model('warehouses_lists');
        if (!empty($_GET['id'])) {
            $row = $model->where(array('id' => $_GET['id']))->find();
            $page = $model->showPage();
            self::profile_menu('loading_list_detail', 'loading_list_detail');
            Tpl::output('menu_sign', 'store_declaration_detail');
            Tpl::output('menu_sign_url', 'index.php?act=store&op=loading_list_detail');
            Tpl::output('menu_sign1', 'loading_list_detail');
            Tpl::output('row', $row);
            //脚部文章输出
            $article = $this->_article();
            Tpl::showpage('loading_list_detail');
        }
    }

}