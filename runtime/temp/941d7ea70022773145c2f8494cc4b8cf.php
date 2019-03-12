<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:41:"./application/admin/view/staff/index.html";i:1548815236;s:55:"/home/www/yey/application/admin/view/public/layout.html";i:1548654013;}*/ ?>
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
                <h3>人事管理 - 添加新员工</h3>
                <!--<h5>网站系统管理员资料</h5>-->
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
                    <label for="staff_name"><em>*</em>姓名</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="staff_name" value="<?php echo $info['user_name']; ?>" id="staff_name" maxlength="20" class="input-txt">
                    <span class="err" id="err_staff_name"></span>
                    <p class="notic">姓名</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="staff_date"><em>*</em>出生日期</label>
                </dt>
                <dd class="opt">
                    <input type="date" name="staff_date" value="<?php echo $info['email']; ?>" id="staff_date" class="input-txt" maxlength="40">
                    <span class="err" id="err_staff_date"></span><p class="notic">出生日期</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="staff_sex"><em>*</em>性别</label>
                </dt>
                <dd class="opt">
                    <input type="radio" name="staff_sex" value="1" id="staff_sex" class="input-txt" maxlength="40">男
                    <input type="radio" name="staff_sex" value="0" id="staff_sex" class="input-txt" maxlength="40">女
                    <span class="err" id="err_staff_sex"></span><p class="notic">性别</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="staff_tel"><em>*</em>手机号</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="staff_tel" value="<?php echo $info['email']; ?>" id="staff_tel" class="input-txt" maxlength="40">
                    <span class="err" id="err_staff_tel"></span><p class="notic">手机号</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="staff_address"><em>*</em>家庭住址</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="staff_address" value="<?php echo $info['email']; ?>" id="staff_address" class="input-txt" maxlength="40">
                    <span class="err" id="err_staff_address"></span><p class="notic">家庭住址</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="staff_education"><em>*</em>学历</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="staff_education" value="<?php echo $info['email']; ?>" id="staff_education" class="input-txt" maxlength="40">
                    <span class="err" id="err_staff_education"></span><p class="notic">学历</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="education_status"><em>*</em>是否统招</label>
                </dt>
                <dd class="opt">
                    <input type="radio" name="education_status" value="1" id="education_status" class="input-txt" maxlength="40">是
                    <input type="radio" name="education_status" value="0" id="education_status" class="input-txt" maxlength="40">否
                    <span class="err" id="err_education_status"></span><p class="notic">性别</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="teaching_status"><em>*</em>教证</label>
                </dt>
                <dd class="opt">
                    <input type="radio" name="teaching_status" value="1" id="teaching_status" class="input-txt" maxlength="40">是
                    <input type="radio" name="teaching_status" value="0" id="teaching_status" class="input-txt" maxlength="40">否
                    <span class="err" id="err_teaching_status"></span><p class="notic">教证</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="entry_date"><em>*</em>入职日期</label>
                </dt>
                <dd class="opt">
                    <input type="date" name="entry_date" value="<?php echo $info['email']; ?>" id="entry_date" class="input-txt" maxlength="40">
                    <span class="err" id="err_entry_date"></span><p class="notic">入职日期</p>
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
                    <label><em>*</em>任岗职位</label>
                </dt>
                <dd class="opt">
                    <select name="o_id" class="post_01">
                        <option value="" >请选择</option>
                        <?php if(is_array($post) || $post instanceof \think\Collection || $post instanceof \think\Paginator): $i = 0; $__LIST__ = $post;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $item['id']; ?>" ><?php echo $item['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <span class="err" id="err_post_id"></span>
                    <p class="notic">任岗职位</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>薪资类型</label>
                </dt>
                <dd class="opt">
                    <select name="pay_id" class="type_01">
                        <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $item['pay_id']; ?>"><?php echo $item['role_name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <span class="err" id="err_type_id"></span>
                    <p class="notic">薪资类型</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>薪资级别</label>
                </dt>
                <dd class="opt">
                    <select name="pay_rank_id" class="rank_01" >
                        <?php if(is_array($rank) || $rank instanceof \think\Collection || $rank instanceof \think\Paginator): $i = 0; $__LIST__ = $rank;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $item['pay_id']; ?>"  ><?php echo $item['pay_name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <span class="err" id="err_rank_id"></span>
                    <p class="notic">薪资级别</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>薪资标准</label>
                </dt>
                <dd class="opt">
                    <select name="pay_standard_id" class="standard_01">
                        <?php if(is_array($standard) || $standard instanceof \think\Collection || $standard instanceof \think\Paginator): $i = 0; $__LIST__ = $standard;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $item['pay_id']; ?>"><?php echo $item['pay_name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <span class="err" id="err_standard_id"></span>
                    <p class="notic">薪资标准</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="positive_date"><em>*</em>转正日期</label>
                </dt>
                <dd class="opt">
                    <input type="date" name="positive_date" value="<?php echo $info['email']; ?>" id="positive_date" class="input-txt" maxlength="40">
                    <span class="err" id="err_positive_date"></span><p class="notic">转正日期</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="transfer_date"><em>*</em>转岗日期</label>
                </dt>
                <dd class="opt">
                    <input type="date" name="entry_date" value="<?php echo $info['email']; ?>" id="transfer_date" class="input-txt" maxlength="40">
                    <span class="err" id="err_transfer_date"></span><p class="notic">转岗日期</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>转正岗位</label>
                </dt>
                <dd class="opt">
                    <select name="positive_post_id">
                        <?php if(is_array($post) || $post instanceof \think\Collection || $post instanceof \think\Paginator): $i = 0; $__LIST__ = $post;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $id; ?>"  ><?php echo $item['name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <span class="err" id="err_positive_post_id"></span>
                    <p class="notic">转正岗位</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="dimission_date"><em>*</em>离职日期</label>
                </dt>
                <dd class="opt">
                    <input type="date" name="dimission_date" value="<?php echo $info['email']; ?>" id="dimission_date" class="input-txt" maxlength="40">
                    <span class="err" id="err_dimission_date"></span><p class="notic">离职日期</p>
                </dd>
            </dl>
            <div class="bot"><a href="JavaScript:void(0);" onclick="adsubmit();" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).on('change','.j_01',function () {
        var j_id = $(this).val()
        $.ajax({
            url:"/index.php/Admin/Staff/AjaxJ",
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
                $.ajax({
                    url:"/index.php/Admin/Staff/AjaxP",
                    data:{id:j_id},
                    dataType:"json",
                    type:"post",
                    success:function(data){
                        var tr = "<select name='position_id' class='post_01'><option value='' >请选择</option>";
                        $.each(data,function (i,v) {
                            tr += "<option value='"+v.id+"'  >"+v.name+"</option>"
                        })
                        tr += "</select>";
                        $(".post_01").html(tr);
                    }
                })
                $.ajax({
                    url:"/index.php/Admin/Staff/a",
                    data:{j_id:j_id},
                    dataType:"json",
                    type:"post",
                    success:function (data) {
                        var tr = '<select name="pay_id" class="type_01"><option value="" >请选择</option>'
                        $.each(data,function (i,v) {
                            tr +='<option value="'+v.id+'">'+v.o_name+'</option>';
                        })
                            tr += '</select>';
                        $("type_01").html(tr);
                    }
                })

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
                $.ajax({
                    url:"/index.php/Admin/Staff/AjaxP",
                    data:{id:garden_id},
                    dataType:"json",
                    type:"post",
                    success:function(data){
                        var tr = "<select name='position_id' class='post_01'><option value='' >请选择</option>";
                        $.each(data,function (i,v) {
                            tr += "<option value='"+v.id+"'  >"+v.name+"</option>"
                        })
                        tr += "</select>";
                        $(".post_01").html(tr);
                    }
                })

            }
        })
    })
    $(document).on('change','.class_01',function () {
        var class_id = $(this).val()
        $.ajax({
            url:"/index.php/Admin/Staff/AjaxGarden",
            type:"post",
            data:{class_id:class_id},
            dataType:'json',
            success:function (data) {
                $.ajax({
                    url:"/index.php/Admin/Staff/AjaxP",
                    data:{id:class_id},
                    dataType:"json",
                    type:"post",
                    success:function(data){
                        var tr = "<select name='position_id' class='post_01'><option value='' >请选择</option>";
                        $.each(data,function (i,v) {
                            tr += "<option value='"+v.id+"'  >"+v.name+"</option>"
                        })
                        tr += "</select>";
                        $(".post_01").html(tr);
                    }
                })

            }
        })
    })
    $(document).on('change','.type_01',function () {
        var pay_id = $(this).val()
        $.ajax({
            url:"/index.php/Admin/Staff/rank",
            type:"post",
            data:{pay_id:pay_id},
            dataType:'json',
            success:function (data) {
                var tr = "<select name='pay_rank_id' class='rank_01' >";
                $.each(data.rank,function (i,v) {
                    tr += "<option value='"+v.pay_id+"'  >"+v.pay_name+"</option>"
                })
                tr += "</select>";
                $(".rank_01").html(tr);

                var se = "<select name='pay_standard_id' class='standard_01'>";
                $.each(data.standard,function (i,v) {
                    se += "<option value='"+v.pay_id+"'  >"+v.pay_name+"</option>"
                })
                se += "</select>";
//                console.log(se);
                $(".standard_01").html(se);
            }
        })
    })
    function adsubmit(){
        $('.err').show();
        if($("#staff_name").val() == ""){
            alert('名称不能为空');
            return false;
        }else if($("#staff_tel").val() == ""){
            alert('手机号不能为空');
            return false;
        }
        if($("#"))
        $.ajax({
            async:false,
            url:'/index.php?m=Admin&c=Staff&a=IndexHandle&t='+Math.random(),
            data:$('#adminHandle').serialize(),
            type:'post',
            dataType:'json',
            success:function(data){
                // console.log(data);return false;
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