<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:48:"./application/admin/view/student/source_add.html";i:1548654014;s:55:"/home/www/yey/application/admin/view/public/layout.html";i:1548654013;}*/ ?>
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
        <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>学生管理 -- 添加目标生源</h3>
                <h5>网站系统管理员资料</h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="form" method="post" action="/index.php?m=Admin&c=Student&a=source_add">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="user_name"><em>*</em>学生名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="name" value="<?php echo $info['name']; ?>" id="user_name" maxlength="20" class="input-txt">
                    <span class="err" id="err_user_name"></span>
                    <p class="notic">学生名称</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="email"><em>*</em>性别</label>
                </dt>
                <dd class="opt">
                    <input type="radio" name="sex" value="1">男
                   <input type="radio" name="sex" value="0">女
                   
                    <span class="err" id="err_email"></span><p class="notic">性别</p>
                </dd>

            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="password"><em>*</em>学生生日</label>
                </dt>
                <dd class="opt">
                    <input type="date" name="birthday" maxlength="18" value="<?php echo $info['birthday']; ?>" id="password" class="input-txt">
                    <span class="err" id="err_password"></span><p class="notic">学生生日</p>
                </dd>

            </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="password"><em>*</em>家长姓名</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="family_name" maxlength="18" value="<?php echo $info['family_name']; ?>" id="password" class="input-txt">
                    <span class="err" id="err_password"></span><p class="notic">家长姓名</p>
                </dd>

            </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="password"><em>*</em>关系</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="relation" maxlength="18" value="<?php echo $info['relation']; ?>" id="password" class="input-txt">
                    <span class="err" id="err_password"></span><p class="notic">关系</p>
                </dd>

            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="password"><em>*</em>籍贯</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="jiguan" maxlength="18" value="<?php echo $info['jiguan']; ?>" id="password" class="input-txt">
                    <span class="err" id="err_password"></span><p class="notic">籍贯</p>
                </dd>

            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="password"><em>*</em>家庭住址</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="home" maxlength="18" value="<?php echo $info['home']; ?>" id="password" class="input-txt">
                    <span class="err" id="err_password"></span><p class="notic">籍贯</p>
                </dd>

            </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="password"><em>*</em>家长所在单位</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="unit" maxlength="18" value="<?php echo $info['unit']; ?>" id="password" class="input-txt">
                    <span class="err" id="err_password"></span><p class="notic">家长所在单位</p>
                </dd>

            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="password"><em>*</em>学历</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="xueli" maxlength="18" value="<?php echo $info['xueli']; ?>" id="password" class="input-txt">
                    <span class="err" id="err_password"></span><p class="notic">学历</p>
                </dd>

            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="password"><em>*</em>家长微信</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="wechat" maxlength="18" value="<?php echo $info['wechat']; ?>" id="password" class="input-txt">
                    <span class="err" id="err_password"></span><p class="notic">家长微信</p>
                </dd>

            </dl>

             <dl class="row">
                <dt class="tit">
                    <label for="password"><em>*</em>家长电话</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="tel" maxlength="18" value="<?php echo $info['tel']; ?>" id="password" class="input-txt">
                    <span class="err" id="err_password"></span><p class="notic">家长电话</p>
                </dd>

            </dl>

             <dl class="row">
                <dt class="tit">
                    <label for="password"><em>*</em>入园经历</label>
                </dt>
                <dd class="opt">
                  <input type="radio" name="go" value="1">有
                  <input type="radio" name="go" value="0">无
                    <span class="err" id="err_password"></span><p class="notic">入园经历</p>
                </dd>

            </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="password"><em>*</em>入园意向</label>
                </dt>
                <dd class="opt">
                    <input type="radio" name="idea" value="1">强烈
                  <input type="radio" name="idea" value="2">正常
                  <input type="radio" name="idea" value="3">放弃园所
                    <span class="err" id="err_password"></span><p class="notic">入园意向</p>
                </dd>

            </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="password"><em>*</em>信息来源</label>
                </dt>
                <dd class="opt">
                    <select name="source_id">
                        <?php if(is_array($message_source) || $message_source instanceof \think\Collection || $message_source instanceof \think\Paginator): if( count($message_source)==0 ) : echo "" ;else: foreach($message_source as $key=>$val): ?>
                           <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option> 
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        
                    </select>
                    <span class="err" id="err_password"></span><p class="notic">信息来源</p>
                </dd>

            </dl>
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
                        <?php if(is_array($info) || $info instanceof \think\Collection || $info instanceof \think\Paginator): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $item['id']; ?>" ><?php echo $item['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
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
                        <?php if(is_array($class) || $class instanceof \think\Collection || $class instanceof \think\Paginator): $i = 0; $__LIST__ = $class;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $item['id']; ?>" ><?php echo $item['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <span class="err" id="err_class_id"></span>
                    <p class="notic">班级名称</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>托费标准</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="tuo" placeholder="费用">
                    <input type="text" name="tuo_zhou" placeholder="周期">
                    <span class="err" id="err_class_id"></span>
                    <p class="notic">班级名称</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>餐费标准</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="can" placeholder="费用">
                    <input type="text" name="can_zhou" placeholder="周期">
                    <span class="err" id="err_class_id"></span>
                    <p class="notic">班级名称</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>校车标准</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="xiao" placeholder="费用">
                    <input type="text" name="xiao_zhou" placeholder="周期">
                    <span class="err" id="err_class_id"></span>
                    <p class="notic">班级名称</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>其他收费</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="qi" placeholder="费用">
                    <input type="text" name="qi_zhou" placeholder="周期">
                    <span class="err" id="err_class_id"></span>
                    <p class="notic">班级名称</p>
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

    // $("#garden_id").change(function(){
    //     var garden_id=$(this).val();
    //     $.ajax({
    //         url:"/index.php?m=Admin&c=Student&a=ClassHandle",
    //         data:{garden_id:garden_id},
    //         dataType:'json',
    //         success:function(res){
    //
    //             var str=""
    //             $.each(res,function(i,v){
    //                 str +=' <option value="'+v.class_id+'">'+v.class_name+'</option> ';
    //             })
    //             $("#class_id").html(str)
    //         }
    //     })
    //
    // })

    function adsubmit(){
        $("#form").submit();
    }

</script>
</body>
</html>