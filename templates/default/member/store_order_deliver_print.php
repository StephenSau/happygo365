<!DOCTYPE html>
<html>
    <head>
        <title>顺丰接口测试</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" href="/vendor/shunfeng/template/style/print.css" media="screen"/>
        <script type="text/javascript" src="/vendor/shunfeng/template/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="/vendor/shunfeng/template/js/print.js"></script>
    </head>
    <body>
        <div id="loadbox">
            <div id="loadboxer">
                <div id="load_ico">
                    <div id="load_icoimg"><img src="/vendor/shunfeng/template/images/loading.gif" width="100%" height="100%" /></div>
                </div>
                <div id="load_content">
                    正在传递信息
                </div>
            </div>
        </div>
        <div id="downbox">
            <div id="downboxer">
                <div id="down_a"><a id="down_file" href="#"><img src="/vendor/shunfeng/template/images/zipbag.jpg" width="100%" height="100%" /></a></div>
                <div id="down_content">
                    <div id="down_title">点击下载订单打印文件</div>
                    <div id="down_body">下载到文件后，请用解压打开。然后打印订单图片。</div>
                </div>
            </div>
        </div>
        <div class="main">
            <div class="testtitle">顺丰订单模版生成器</div>
            <div class="testbox">
                <div class="que_title"><span>填写您的订单信息</span></div>

                <div class="que_content">
                    <table width="100%">
                        <tr>
                            <td align="right" width="80px"><b>订单号：</b></td>
                            <td><input type="input" id="orderid" value="<?php echo $output['order_info']['order_sn']; ?>" class="inputstyle" /></td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>运单号：</b></td>
                            <td><input type="input" id="mailno" value="<?php echo $output['order_info']['shipping_code']; ?>" class="inputstyle" /></td>
                        </tr>
                    
                        <tr>
                            <td align="right" width="80px"><b>快件类型：</b></td>
                            <td>
                                <select class="inputstyle" id="express_type">
<!--                                    <option value="电商速配">电商速配</option>-->
                                   <option value="电商特惠" selected="true">电商特惠</option>
                                    <!-- <option value="标准快递" selected="true">标准快递 </option><option value="顺丰特惠">顺丰特惠</option>
                                    
                                    <option value="电商速配">电商速配</option>-->
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td height="20" colspan="2"><input id="insure" type="hidden" value="<?php echo $output['order_info']['order_amount']; ?>"/></td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>寄件方公司：</b></td>
                            <td><input type="input" id="j_company" value="<?php echo $output['daddress_info']['company']; ?>" class="inputstyle" /></td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>寄件方姓名：</b></td>
                            <td><input type="input" id="j_contact" value="<?php echo $output['daddress_info']['seller_name']; ?>" class="inputstyle" /></td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>寄件方电话：</b></td>
                            <td><input type="input" id="j_tel" value="<?php echo $output['daddress_info']['mob_phone']; ?>" class="inputstyle" /></td>
                        </tr>   
                        <tr>
                            <td align="right" width="80px"><b>寄件省市区：</b></td>
                            <td>
                                <input type="input" id="j_province" style="width:60px" value="<?php echo $output['j_daddressinfo_arr']['0']; ?>" class="inputstyle" />
                                <input type="input" id="j_city" style="width:60px" value="<?php echo $output['j_daddressinfo_arr']['1']; ?>" class="inputstyle" />
                                <input type="input" id="j_qu" style="width:60px" value="<?php echo $output['j_daddressinfo_arr']['2']; ?>" class="inputstyle" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>寄件方地址：</b></td>
                            <td><input type="input" id="j_address" style="width:240px" value="<?php echo $output['daddress_info']['address']; ?>" class="inputstyle" /></td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>寄件地编号：</b></td>
                            <td><input type="input" id="j_number" style="width: 60px" value="<?php echo $output['j_number']; ?>" class="inputstyle" /></td>
                        </tr>
                        <tr>
                            <td height="20" colspan="2"></td>
                        </tr> 
                        <tr>
                            <td align="right" width="80px"><b>到件方公司：</b></td>
                            <td><input type="input" id="d_company" value="" class="inputstyle" /></td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>到件方姓名：</b></td>
                            <td><input type="input" id="d_contact" value="<?php echo $output['order_info']['true_name']; ?>" class="inputstyle" /></td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>到件方电话：</b></td>
                            <td><input type="input" id="d_tel" value="<?php echo $output['order_info']['mob_phone']; ?>" class="inputstyle" /></td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>到件省市区：</b></td>
                            <td>
                                <input type="input" id="d_province" style="width:60px" value="<?php echo $output['d_orderaddressinfo_arr']['0']; ?>" class="inputstyle" />
                                <input type="input" id="d_city" style="width:60px" value="<?php echo $output['d_orderaddressinfo_arr']['1']; ?>" class="inputstyle" />
                                <input type="input" id="d_qu" style="width:60px" value="<?php echo $output['d_orderaddressinfo_arr']['2']; ?>" class="inputstyle" />
                            </td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>到件方地址：</b></td>
                            <td><input type="input" id="d_address" style="width:240px" value="<?php echo $output['order_info']['address']; ?>" class="inputstyle" /></td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>到件地编号：</b></td>
                            <td><input type="input" id="d_number" style="width: 60px" value="<?php echo $output['d_number']; ?>" class="inputstyle" /></td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>代收金额：</b></td>
                            <td><input type="input" id="daishou" style="width:240px" value="0" class="inputstyle" /></td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>备注：</b></td>
                            <td><input type="input" id="remark" style="width:240px" value="<?php echo $output['order_info']['deliver_explain']; ?>" class="inputstyle" /></td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>物件：</b></td>
                            <td><input type="input" id="things" style="width:240px" value="<?php echo $output['things']; ?>" class="inputstyle" /></td>
                        </tr>
                        <tr>
                            <td height="20" colspan="2"></td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>付款方式：</b></td>
                            <td>
                                <select class="inputstyle" id="pay_method">
                               
                                    <option value="寄付月结" selected="true">寄付月结</option>
                                  
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"><b>付款帐号：</b></td>
                            <td><input type="input" id="custid" style="width:240px" value="0201356453" class="inputstyle" /></td>
                        </tr>
                        <tr>
                            <td height="20" colspan="2"></td>
                        </tr>
                        <tr>
                            <td align="right" width="80px"></td>
                            <td><input type="button" class="btn me" value="让我们愉快地生成订单" onClick="PrintOrd();" /></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
