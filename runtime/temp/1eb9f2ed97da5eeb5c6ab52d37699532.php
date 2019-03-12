<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:39:"./application/admin/view/menu/node.html";i:1549847317;s:55:"/home/www/yey/application/admin/view/public/layout.html";i:1548654013;}*/ ?>
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
                <h3>管理员 - 添加权限</h3>
                <h5>网站系统添加权限</h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="adminHandle" method="post">
       <!--  <input type="hidden" name="act" id="act" value="<?php echo $act; ?>">
        <input type="hidden" name="menu_id" value="<?php echo $menu_id; ?>"> -->
        <!-- <input type="hidden" name="auth_code" value="<?php echo \think\Config::get('AUTH_CODE'); ?>"/> -->
        <div class="ncap-form-default">
            <!-- <dl class="row">
                <dt class="tit">
                    <label for="user_name"><em>*</em>权限名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="node_name" value="<?php echo $info['node_name']; ?>" id="user_name" maxlength="20" class="input-txt">
                    <span class="err" id="err_user_name"></span>
                    <p class="notic">权限名称</p>
                </dd>
            </dl> -->
         <!--    <dl class="row">
                <dt class="tit">
                    <label for="user_name"><em>*</em>所属机构</label>
                </dt>
                <dd class="opt">
                    <select name="category_id">
                        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$val): ?>
                            <option value="<?php echo $val['category_id']; ?>"><?php echo $val['category_name']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        
                    </select>
                    <span class="err" id="err_user_name"></span>
                    <p class="notic">所属机构名称</p>
                </dd>
            </dl> -->
             <dl class="row" >
                <dt class="tit">
                    <label for="user_name"><em>*</em>选择机构</label>
                </dt>
                <dd class="opt">
                        <select class="sell" name="ji[]">
                           <option value='0'>请选择...</option>
                            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$v): ?>
                            <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                             <?php endforeach; endif; else: echo "" ;endif; ?>
                           
                        </select>
                   <!--  <span class="err" id="err_user_name"></span>
                    <p class="notic">所属分类</p> -->
                </dd>
            </dl>
              <dl class="row" >
                <dt class="tit">
                    <label for="user_name"><em>*</em>选择人员</label>
                </dt>
                <dd class="opt">
                        <button type="button" id="btn">确定机构人员</button>
                       
                   <!--  <span class="err" id="err_user_name"></span>
                    <p class="notic">所属分类</p> -->
                </dd>
            </dl>
            <dl class="row" >
                <dt class="tit">
                    <label for="user_name"><em>*</em>应有权限</label>
                </dt>
                <dd class="opt">
                        <h2>菜单等级：<span style="background-color:pink;">一级菜单</span><span style="background-color:skyblue;">二级菜单</span><span style="background-color:yellow;">三级菜单</span><span style="background-color:red;">四级菜单</span></h2>
                        <table border="1" style="background-color:pink;">
                            <?php if(is_array($node) || $node instanceof \think\Collection || $node instanceof \think\Paginator): if( count($node)==0 ) : echo "" ;else: foreach($node as $key=>$val): ?>
                                <tr>
                                    <td><input type="checkbox" class="node" name="node[]" value='<?php echo $val['id']; ?>'><?php echo $val['name']; ?></td>
                                    <?php if(is_array($val['son']) || $val['son'] instanceof \think\Collection || $val['son'] instanceof \think\Paginator): if( count($val['son'])==0 ) : echo "" ;else: foreach($val['son'] as $key=>$v): ?>
                                        <td style="background-color:skyblue;">
                                            <span  >
                                            <input type="checkbox" class="node" name="node[]" value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?>
                                            </span>
                                            <table border="1" style="background-color:yellow;" >
                                                <?php if(is_array($v['son']) || $v['son'] instanceof \think\Collection || $v['son'] instanceof \think\Paginator): if( count($v['son'])==0 ) : echo "" ;else: foreach($v['son'] as $key=>$vo): ?>
                                                <tr>
                                                    <td><input class="node" type="checkbox" name="node[]" value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></td>
                                                    <td>
                                                        <table border="1" style="background-color:red;">
                                                            <?php if(is_array($vo['son']) || $vo['son'] instanceof \think\Collection || $vo['son'] instanceof \think\Paginator): if( count($vo['son'])==0 ) : echo "" ;else: foreach($vo['son'] as $key=>$vol): ?>
                                                                <tr>
                                                                    <td><input class="node" type="checkbox" name="node[]" value="<?php echo $vol['id']; ?>"><?php echo $vol['name']; ?></td>
                                                                </tr>
                                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <?php endforeach; endif; else: echo "" ;endif; ?> 
                                            </table>
                                             
                                             
                                        </td>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </tr>

                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </table>
                       
                   
                        <!-- <select name="ren">
                            <option value='0'>请选择...</option>
                           
                        </select> -->
                   <!--  <span class="err" id="err_user_name"></span>
                    <p class="notic">所属分类</p> -->
                </dd>
            </dl>
            <!--<dl class="row">-->
                <!--<dt class="tit">-->
                    <!--<label for="user_name"><em>*</em>权限类型</label>-->
                <!--</dt>-->
                <!--<dd class="opt">-->
                    <!--<input type="radio" name="node_type" value="0" checked>读-->
                    <!--<input type="radio" name="node_type" value="1">写-->
                    <!--<span class="err" id="err_user_name"></span>-->
                    <!--<p class="notic">权限类型</p>-->
                <!--</dd>-->
            <!--</dl>-->
            <div class="bot"><a href="JavaScript:void(0);" onclick="adsubmit();" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    //复选框多选
    $(document).on("click",".node",function(){
        var _this = $(this)
        var menu_id = _this.val();

        $.ajax({
            url:'/index.php?m=Admin&c=Menu&a=nodecheck',
            data:{menu_id,menu_id},
            dataType:'json',
            success:function(res){
                console.log(res)
                $.each(res.data,function(i,v){
                    $("input[value="+v+"]").prop("checked",true)
                })
            }
        })
        if(_this.prop("checked")==true){
            _this.parent().parent().find("input").prop("checked",true)
        }else{
            _this.parent().parent().find("input").prop("checked",false)
        }
      
    })
    // 判断输入框是否为空
    function adsubmit(){
        $('.err').show();
        if($("#user_name").val() == ""){
            layer.msg('名称不能为空', {icon: 2,time: 1000});//alert('少年，密码不能为空！');
            return false;
        }
        $.ajax({
            async:false,
            url:'/index.php?m=Admin&c=Menu&a=nodeadd&t='+Math.random(),
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

    $(document).on("click","#btn",function(){
        $("#btn").next().remove();
        var id='';
        $(".sell :selected").each(function(){
            id +=$(this).val()+",";
        })
        
          $.ajax({
            url:"/index.php?m=Admin&c=Menu&a=getuser",
            data:{id:id},
            dataType:'json',
            success:function(res){
                if(res.status==0){
                    alert(res.msg)
                }else{
                    var str='<select name="ren"><option value="0">请选择...</option>';
                    $.each(res.data,function(i,v){
                       
                        str+='<option value="'+v.admin_id+'">'+v.user_name+'</option>'
                    })
                  
                    str+='</select>';
                    $("#btn").after(str)
                }
            }
        })
    })



        $(document).on('change','.sell',function(){
         var id = $(this).val();
      
        if(id==0){
            return false;
        }
        var obj =$(this);
        $.ajax({
            url:"/index.php?m=Admin&c=Organization&a=getji",
            dataType:'json',
            data:{id:id},
            success:function(res){
                 obj.nextAll().remove();
                 if(res.state==0){
                    return false;
                 }
                 var sel="<select class='sell' name='ji[]'><option value='0'>请选择...</option>";
                     //json的循环
                    $(res.data).each(function(key,val){
                        //自增的id                     市或区的名称
                        sel+="<option value='"+val.id+"'>"+val.name+"</option>";
                    })
                    sel+="</select>";
                    //后面追加
                    obj.after(sel);
            }

        })
        //传值
       
        })   
</script>
</body>
</html>