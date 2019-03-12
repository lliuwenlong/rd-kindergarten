<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:40:"./application/admin/view/work/index.html";i:1548654015;s:55:"/home/www/yey/application/admin/view/public/layout.html";i:1548654013;}*/ ?>
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
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"></a>
            <div class="subject">
                <h3>工作任务</h3>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="adminHandle" method="post">
        <input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
        <input type="hidden" name="admin_id" value="<?php echo $info['admin_id']; ?>">
        <input type="hidden" name="auth_code" value="<?php echo \think\Config::get('AUTH_CODE'); ?>"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>集团名称</label>
                </dt>
                <dd class="opt">
                    <select name="ji_id" class="j_01">
                        <option value="" >请选择</option>
                        <?php if(is_array($jituan) || $jituan instanceof \think\Collection || $jituan instanceof \think\Paginator): $i = 0; $__LIST__ = $jituan;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $item['id']; ?>" ><?php echo $item['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <span class="err" id="err_j_id"></span>
                    <p class="notic">集团名称</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>园所名称/部门名称</label>
                </dt>
                <dd class="opt">
                    <select name="garden_id" class="garden_01">
                        <option value="" >请选择</option>
                    </select>
                    <span class="err" id="err_garden_id"></span>
                    <p class="notic">园所名称/部门名称</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>班级名称</label>
                </dt>
                <dd class="opt">
                    <select name="class_id" class="class_01">
                        <option value="" >请选择</option>
                    </select>
                    <span class="err" id="err_class_id"></span>
                    <p class="notic">班级名称</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="work_title"><em>*</em>标题</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="work_title" value="<?php echo $info['user_name']; ?>" id="work_title" maxlength="20" class="input-txt">
                    <span class="err" id="err_work_title"></span>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="work_desc"><em>*</em>内容</label>
                </dt>
                <dd class="opt">
                    <textarea name="work_desc" id="work_desc" cols="50" rows="50"></textarea>
                    <!--<input type="text" name="email" value="<?php echo $info['email']; ?>" id="email" class="input-txt" maxlength="40">-->
                </dd>

            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="work_type"><em>*</em>通知选择</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="work_type" maxlength="18" value="<?php echo $info['password']; ?>" id="work_type" class="input-txt">

                </dd>

            </dl>
            <div class="bot"><a href="JavaScript:void(0);" onclick="adsubmit();" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).on('change','.j_01',function () {
        var j_id = $(this).val()
        if(j_id == ""){
            var td = "<select name='position_id' class='post_01'><option value='' >请选择</option>";
            td += "</select>";
            $(".garden_01").html(td);
            $(".class_01").html(td);
            $(".post_01").html(td);
        }
        $.ajax({
            url:"/index.php/Admin/Work/AjaxJ",
            type:"post",
            data:{j_id:j_id},
            dataType:'json',
            success:function (data) {
                // console.log(data);return false;
                var tr = "<select name='garden_id' class='garden_01'><option value='' >请选择</option>";
                $.each(data,function (i,v) {
                    tr += "<option value='"+v.id+"'  >"+v.name+"</option>"
                })
                tr += "</select>";

                $(".garden_01").html(tr);
            }
        })
    })
    $(document).on('change','.garden_01',function () {
        var garden_id = $(this).val()
        $.ajax({
            url:"/index.php/Admin/Staff/AjaxGarden",
            type:"post",
            data:{garden_id:garden_id},
            dataType:'json',
            success:function (data) {
                // console.log(data);
                var tr = "<select name='class_id' class='class_01'><option value='' >请选择</option>";
                $.each(data,function (i,v) {
                    tr += "<option value='"+v.id+"'  >"+v.name+"</option>"
                })
                tr += "</select>";
                $(".class_01").html(tr);

            }
        })
    })
    // 判断输入框是否为空
    function adsubmit(){
        $('.err').show();
        $.ajax({
            async:false,
            url:'/index.php?m=Admin&c=Work&a=workHandle&t='+Math.random(),
            data:$('#adminHandle').serialize(),
            type:'post',
            dataType:'json',
            success:function(data){
//                console.log(data);return false;
                if(data.status != 1){
                    layer.msg(data.msg,{icon: 2,time: 2000})
                    $.each(data.result,function (index,item) {
                        $('#err_'+index).text(item)
                    })
                }else{
                    layer.msg(data.msg,{icon: 1,time: 1000},function () {
                        window.location.href = data.url;
                    })
                }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                $('#error').html('<span class="error">网络失败，请刷新页面后重试!</span>');
            }
        });
    }
</script>
</body>
</html>