<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:41:"./application/admin/view/staff/grade.html";i:1548758337;s:55:"/home/www/yey/application/admin/view/public/layout.html";i:1548654013;}*/ ?>
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
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>薪酬级别</h3>
                <!--<h5>网站系统管理员列表</h5>-->
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <!--<div id="explanation" class="explanation" style="color: rgb(44, 188, 163); background-color: rgb(237, 251, 248); width: 99%; height: 100%;">-->
    <!--<div id="checkZoom" class="title"><i class="fa fa-lightbulb-o"></i>-->
    <!--<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>-->
    <!--<span title="收起提示" id="explanationZoom" style="display: block;"></span>-->
    <!--</div>-->
    <!--<ul>-->
    <!--&lt;!&ndash;<li>管理员列表管理, 可修改后台管理员登录密码和所属角色</li>&ndash;&gt;-->
    <!--&lt;!&ndash;</ul>&ndash;&gt;-->
    <!--&lt;!&ndash;</div>&ndash;&gt;-->
    <!--&lt;!&ndash;<button class="jia">+</button>&ndash;&gt;-->

    <div class="flexigrid">
        <!--<div class="tDiv2">-->
            <!--<div class="fbutton">-->
                <!--<a href="<?php echo U('Staff/levels'); ?>">-->
                    <!--<div class="add" title="添加管理员">-->
                        <!--<span><i class="fa fa-plus"></i>添加岗位级别</span>-->
                    <!--</div>-->
                <!--</a>-->
                <!--<a href="<?php echo U('Staff/levels_rank'); ?>">-->
                    <!--<div class="add" title="添加管理员">-->
                        <!--<span><i class="fa fa-plus"></i>添加薪酬级别</span>-->
                    <!--</div>-->
                <!--</a>-->
                <!--<a href="<?php echo U('Staff/levels_standard'); ?>">-->
                    <!--<div class="add" title="添加管理员">-->
                        <!--<span><i class="fa fa-plus"></i>添加档位级别</span>-->
                    <!--</div>-->
                <!--</a>-->
            <!--</div>-->
        <!--</div>-->
        <!--<div class="mDiv">-->
            <!--<div class="ftitle">-->
                <!--<h3>薪酬级别</h3>-->
                <!--<h5>(共<?php echo count($garden); ?>条记录)</h5>-->
            <!--</div>-->
            <!--<div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>-->
            <!--<form class="navbar-form form-inline" action="<?php echo U('Admin/index'); ?>" method="get">-->
                <!--<div class="sDiv">-->
                    <!--<div class="sDiv2">-->
                        <!--<input type="text" size="30" name="keywords" class="qsbox" placeholder="搜索相关数据...">-->
                        <!--<input type="submit" class="btn" value="搜索">-->
                    <!--</div>-->
                <!--</div>-->
            <!--</form>-->
        <!--</div>-->
        <!--<div class="hDiv">-->
            <!--<div class="hDivBox">-->
                <!--<table cellspacing="0" cellpadding="0">-->
                    <!--<thead>-->
                    <!--<tr>-->
                        <!--<th align="left" abbr="article_title" axis="col3" class="">-->
                            <!--<div style="text-align: left; width: 200px;" class="">集团名称</div>-->
                        <!--</th>-->
                        <!--<th align="left" abbr="article_title" axis="col3" class="">-->
                            <!--<div style="text-align: left; width: 200px;" class="">岗位级别</div>-->
                        <!--</th>-->
                        <!--<th align="left" abbr="article_title" axis="col3" class="">-->
                            <!--<div style="text-align: left; width: 100px;" class="">薪酬级别</div>-->
                        <!--</th>-->
                        <!--<?php  foreach($data as $key => $val){  ?>-->
                            <!--<?php if(count($val['standard']) == $count){ ?>-->
                            <!--<?php  foreach($val["standard"] as $k => $v){  ?>-->
                            <!--<th align="left" abbr="article_title" axis="col3" class="">-->
                                <!--<div style="text-align: left; width: 50px;" class=""><?= $v[0] ?></div>-->
                            <!--</th>-->
                            <!--<?php } ?>-->
                            <!--<?php } ?>-->
                        <!--<?php } ?>-->
                        <!--&lt;!&ndash;<th style="width:100%" axis="col7">&ndash;&gt;-->
                            <!--&lt;!&ndash;<div></div>&ndash;&gt;-->
                        <!--&lt;!&ndash;</th>&ndash;&gt;-->
                    <!--</tr>-->
                    <!--</thead>-->
                <!--</table>-->
            <!--</div>-->
        <!--</div>-->
        <div class="tDiv">
            <div class="tDiv2">
                <div class="fbutton">
                    <a href="<?php echo U('Staff/levels'); ?>">
                        <div class="add" title="添加">
                            <span><i class="fa fa-plus"></i>添加</span>
                        </div>
                    </a>
                </div>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="bDiv" style="height: auto;">
            <div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
                <table>
                    <tr style="background-color:grey;">
                        <td align="left" class="<?php echo $vo['role_id']; ?>">
                            <div style="text-align: left; width: 50px;">集团名称</div>
                        </td>
                        <td align="left" class="<?php echo $vo['role_id']; ?>">
                            <div style="text-align: left; width: 80px;">岗位级别</div>
                        </td>
                        <td align="left" class="<?php echo $vo['role_id']; ?>">
                            <div style="text-align: left; width: 50px;">薪酬级别</div>
                        </td>
                        <?php  foreach($data as $key => $val){  if(count($val['standard']) == $count){  foreach($val["standard"] as $k => $v){  ?>
                        <td align="left" class="<?php echo $vo['role_id']; ?>">
                            <div style="text-align: left; width: 20px;"><?= $v[0] ?></div>
                        </td>
                        <?php } } } ?>

                        <!--<td align="left" class="">-->
                        <!--<div style="text-align: left; width: 100px;"><a href="/index.php?m=Admin&c=Staff&a=JTmoneyXQ&id=<?php echo $vo['id']; ?>"><button type="button">查看详情</button></a></div>-->
                        <!--&lt;!&ndash;</td>&ndash;&gt;-->
                        <!--<td align="" class="" style="width: 100%;">-->
                        <!--<div>&nbsp;</div>-->
                        <!--</td>-->
                    </tr>
                    <tbody>
                    <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $k=>$vo): ?>
                        <tr>
                            <td align="left">

                                <div style="text-align: left; width: 50px;"><?php echo $vo['ji_name']; ?></div>

                            </td>
                            <td align="left" class="<?php echo $vo['role_id']; ?>">
                                <div style="text-align: left; width: 80px;"><?php echo $vo['name']; ?></div>
                            </td>
                            <td align="left" class="<?php echo $vo['role_id']; ?>">
                                <div style="text-align: left; width: 50px;"><?php echo $vo['jibie']; ?></div>
                            </td>
                            <?php foreach($vo[standard] as $key => $val){ ?>
                                <td align="left" class="<?php echo $vo['role_id']; ?>">
                                    <div style="text-align: left; width: 20px;"><?= $val[1] ?></div>
                                </td>
                            <?php } ?>
                            <!--<td align="left" class="">-->
                                <!--<div style="text-align: left; width: 100px;"><a href="/index.php?m=Admin&c=Staff&a=JTmoneyXQ&id=<?php echo $vo['id']; ?>"><button type="button">查看详情</button></a></div>-->
                            <!--&lt;!&ndash;</td>&ndash;&gt;-->
                            <!--<td align="" class="" style="width: 100%;">-->
                                <!--<div>&nbsp;</div>-->
                            <!--</td>-->
                        </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="iDiv" style="display: none;"></div>
        </div>
        <!--分页位置-->
        <?php echo $page; ?> </div>
</div>
    <!--<table border="1" style="width: 1000px;">-->
        <!--<th>集团名称</th>-->
        <!--<th>岗位级别</th>-->
        <!--<th>薪酬级别</th>-->
        <!--<?php  foreach($data as $key => $val){  ?>-->
            <!--<?php if(count($val['standard']) == $count){ ?>-->
                <!--<?php  foreach($val["standard"] as $k => $v){  ?>-->
                    <!--<th><?= $v[0] ?></th>-->
                <!--<?php } ?>-->
            <!--<?php } ?>-->
        <!--<?php } ?>-->

        <!--<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $k=>$vo): ?>-->
        <!--<tr>-->
            <!--<td><?php echo $vo['ji_name']; ?></td>-->
            <!--<td><?php echo $vo['name']; ?></td>-->
            <!--<td><?php echo $vo['jibie']; ?></td>-->
            <!--<?php foreach($vo[standard] as $key => $val){ ?>-->
            <!--<td><?= $val[1] ?></td>-->
            <!--<?php } ?>-->
            <!--&lt;!&ndash;<td align="left" class="">&ndash;&gt;-->
            <!--&lt;!&ndash;<div style="text-align: left; width: 100px;"><a href="/index.php?m=Admin&c=Staff&a=JTmoneyXQ&id=<?php echo $vo['id']; ?>"><button type="button">查看详情</button></a></div>&ndash;&gt;-->
        <!--</tr>-->
        <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
    <!--</table>-->
</body>
<!--</html>-->
<!--<script src="/public/jquery.js"></script>-->
<script>
    $(".jia").click(function () {
        alert(555)
    })

    $(".jibie").click(function () {
        alert(55)
        // $(".rank_01").show();
    })

    // $(".jibie").blur(function () {
    //     $(".rank_01").hide();
    // })
</script>