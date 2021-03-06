<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:40:"./application/admin/view/staff/desc.html";i:1548654013;s:55:"/home/www/yey/application/admin/view/public/layout.html";i:1548654013;}*/ ?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link href="/public/static/css/main.css" rel="stylesheet" type="text/css">
<link href="/public/static/css/page.css" rel="stylesheet" type="text/css">
<link href="/public/static/font/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="/public/static/font/css/font-awesome-ie7.min.css">
<![endif]-->
<link href="/public/static/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="/public/static/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">html, body { overflow: visible;}</style>
<script type="text/javascript" src="/public/static/js/jquery.js"></script>
<script type="text/javascript" src="/public/static/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="/public/static/js/layer/layer.js"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
<script type="text/javascript" src="/public/static/js/admin.js"></script>
<script type="text/javascript" src="/public/static/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="/public/static/js/common.js"></script>
<script type="text/javascript" src="/public/static/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="/public/static/js/jquery.mousewheel.js"></script>
<script src="/public/js/myFormValidate.js"></script>
<script src="/public/js/myAjax2.js"></script>
<script src="/public/js/global.js"></script>
    <script type="text/javascript">
    function delfunc(obj){
    	layer.confirm('确认删除？', {
    		  btn: ['确定','取消'] //按钮
    		}, function(){
    		    // 确定
   				$.ajax({
   					type : 'post',
   					url : $(obj).attr('data-url'),
   					data : {act:'del',del_id:$(obj).attr('data-id')},
   					dataType : 'json',
   					success : function(data){
						layer.closeAll();
   						if(data.status==1){
                            layer.msg(data.msg, {icon: 1, time: 2000},function(){
                                location.href = '';
//                                $(obj).parent().parent().parent().remove();
                            });
   						}else{
   							layer.msg(data, {icon: 2,time: 2000});
   						}
   					}
   				})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }

    function selectAll(name,obj){
    	$('input[name*='+name+']').prop('checked', $(obj).checked);
    }

    function get_help(obj){

		window.open("http://www.tp-shop.cn/");
		return false;

        layer.open({
            type: 2,
            title: '帮助手册',
            shadeClose: true,
            shade: 0.3,
            area: ['70%', '80%'],
            content: $(obj).attr('data-url'),
        });
    }

    function delAll(obj,name){
    	var a = [];
    	$('input[name*='+name+']').each(function(i,o){
    		if($(o).is(':checked')){
    			a.push($(o).val());
    		}
    	})
    	if(a.length == 0){
    		layer.alert('请选择删除项', {icon: 2});
    		return;
    	}
    	layer.confirm('确认删除？', {btn: ['确定','取消'] }, function(){
    			$.ajax({
    				type : 'get',
    				url : $(obj).attr('data-url'),
    				data : {act:'del',del_id:a},
    				dataType : 'json',
    				success : function(data){
						layer.closeAll();
    					if(data == 1){
    						layer.msg('操作成功', {icon: 1});
    						$('input[name*='+name+']').each(function(i,o){
    							if($(o).is(':checked')){
    								$(o).parent().parent().remove();
    							}
    						})
    					}else{
    						layer.msg(data, {icon: 2,time: 2000});
    					}
    				}
    			})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }

    /**
     * 全选
     * @param obj
     */
    function checkAllSign(obj){
        $(obj).toggleClass('trSelected');
        if($(obj).hasClass('trSelected')){
            $('#flexigrid > table>tbody >tr').addClass('trSelected');
        }else{
            $('#flexigrid > table>tbody >tr').removeClass('trSelected');
        }
    }
    /**
     * 批量公共操作（删，改）
     * @returns {boolean}
     */
    function publicHandleAll(type){
        var ids = '';
        $('#flexigrid .trSelected').each(function(i,o){
//            ids.push($(o).data('id'));
            ids += $(o).data('id')+',';
        });
        if(ids == ''){
            layer.msg('至少选择一项', {icon: 2, time: 2000});
            return false;
        }
        publicHandle(ids,type); //调用删除函数
    }
    /**
     * 公共操作（删，改）
     * @param type
     * @returns {boolean}
     */
    function publicHandle(ids,handle_type){
        layer.confirm('确认当前操作？', {
                    btn: ['确定', '取消'] //按钮
                }, function () {
                    // 确定
                    $.ajax({
                        url: $('#flexigrid').data('url'),
                        type:'post',
                        data:{ids:ids,type:handle_type},
                        dataType:'JSON',
                        success: function (data) {
                            layer.closeAll();
                            if (data.status == 1){
                                layer.msg(data.msg, {icon: 1, time: 2000},function(){
                                    location.href = data.url;
                                });
                            }else{
                                layer.msg(data.msg, {icon: 2, time: 2000});
                            }
                        }
                    });
                }, function (index) {
                    layer.close(index);
                }
        );
    }
</script>  

</head>
<style>
    * {
        margin: 0;
        padding: 0;
        list-style: none;
    }


    /*设置中间鼠标点击事件*/

    .ayou_div2 {

        height: 46px;
        position: relative;
    }

    li {
        width: 129px;
        height: 44px;
        font-size: 16px;
        text-align: center;
        line-height: 44px;
        float: left;
        cursor: pointer;
    }
    /*设置显示li的样式*/
    h3{
        width: 120px;
        float: left;
        height: 44px;
        line-height: 44px;
        font-size: 16px;
    }
    .a_11 {
        background: #9bc248;
        color: #fff;
        border-bottom: none;
    }

</style>
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>员工管理</h3>
                <!--<h5>网站系统管理员列表</h5>-->
            </div>
        </div>
    </div>
    <!--<div class="ayou_div1">-->

        <!--&lt;!&ndash;设置选项卡&ndash;&gt;-->
        <!--<div class="ayou_div2">-->

            <!--<ul id="a_1">-->
                <!--<h3>所属集团：</h3>-->
                <!--<li class="a_11 cl" id="0" cat="ji_id">全部</li>-->
                <!--<?php if(is_array($where['ji']) || $where['ji'] instanceof \think\Collection || $where['ji'] instanceof \think\Paginator): if( count($where['ji'])==0 ) : echo "" ;else: foreach($where['ji'] as $key=>$val): ?>-->
                    <!--<li id="<?php echo $val['id']; ?>" cat="ji_id" class="cl"><?php echo $val['name']; ?></li>-->
                <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->

            <!--</ul>-->
        <!--</div>-->


        <!--&lt;!&ndash;设置选项卡&ndash;&gt;-->
        <!--<div class="ayou_div2">-->

            <!--<ul id="a_2">-->
                <!--<h3>所属园区/部门：</h3>-->
                <!--<span id="garden">-->
					<!--<li class="a_11 cl" id="0" cat="garden_id">全部</li>-->

						<!--<?php if(is_array($where['garden']) || $where['garden'] instanceof \think\Collection || $where['garden'] instanceof \think\Paginator): if( count($where['garden'])==0 ) : echo "" ;else: foreach($where['garden'] as $key=>$val): ?>-->
							<!--<li id="<?php echo $val['id']; ?>" cat="garden_id" class="cl"><?php echo $val['name']; ?></li>-->
						<!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
					<!--</span>-->
            <!--</ul>-->
        <!--</div>-->




    <!--</div>-->

    <!--&lt;!&ndash; 操作说明 &ndash;&gt;-->
    <!--<div id="explanation" class="explanation" style="color: rgb(44, 188, 163); background-color: rgb(237, 251, 248); width: 99%; height: 100%;">-->
    <!--<div id="checkZoom" class="title"><i class="fa fa-lightbulb-o"></i>-->
    <!--<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>-->
    <!--<span title="收起提示" id="explanationZoom" style="display: block;"></span>-->
    <!--</div>-->
    <!--<ul>-->
    <!--<li>管理员列表管理, 可修改后台管理员登录密码和所属角色</li>-->
    <!--</ul>-->
    <!--</div>-->

    <div class="flexigrid">
        <!--<div class="tDiv">-->
            <!--<div class="tDiv2">-->
                <!--<div class="fbutton">-->
                    <!--<a href="<?php echo U('Staff/index'); ?>">-->
                        <!--<div class="add" title="添加管理员">-->
                            <!--<span><i class="fa fa-plus"></i>添加员工</span>-->
                        <!--</div>-->
                    <!--</a>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div style="clear:both"></div>-->
        <!--</div>-->
        <div class="hDiv">
            <div class="hDivBox">
                <table cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th class="sign" axis="col0">
                            <div style="width: 24px;"><i class="ico-check"></i></div>
                        </th>
                        <th align="left" abbr="article_title" axis="col3" class="">
                            <div style="text-align: left; width: 100px;" class="">员工编号</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">员工姓名</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">出生日期</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">手机号</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">家庭住址</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">学历</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">是否统招</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">教证</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">入职时间</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">集团名称</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">职位</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">联系方式</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">所在园区</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">薪酬级别</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">薪酬标准</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">是否骨干</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">状态</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">转正日期</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 100px;" class="">最后登录时间</div>
                        </th>
                        <th style="width:100%" axis="col7">
                            <div></div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="bDiv" style="height: auto;">
            <div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
                <table>
                    <tbody id="tbd">
                        <tr>
                            <td class="sign">
                                <div style="width: 24px;"><i class="ico-check"></i></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $data['staff_id']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $data['staff_name']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $data['staff_date']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $data['staff_tel']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $data['staff_address']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $data['staff_education']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php if($data[education_status] == 1){ echo '是';}else{ echo '否';}  ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php if($data[teaching_status] == 1){ echo '是';}else{ echo '否';}  ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $data['entry_date']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $data['ji_name']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $data['post_name']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $data['staff_tel']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $data['garden_name']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $data['r_name']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $data['standard_name']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php if($data[backbone_status] == 1){ echo '是';}else{ echo '否';}  ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php if($data[staff_status] == 1){ echo "在职";}else{ echo "离职";}  ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $data['positive_date']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $data['time']; ?></div>
                            </td>
                            <td align="" class="" style="width: 100%;">
                                <div>&nbsp;</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="iDiv" style="display: none;"></div>
        </div>
        <!--分页位置-->
        <?php echo $page; ?> </div>
</div>
</body>
</html>