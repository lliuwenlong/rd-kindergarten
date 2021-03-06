<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:46:"./application/admin/view/organization/add.html";i:1548654013;s:55:"/home/www/yey/application/admin/view/public/layout.html";i:1548654013;}*/ ?>
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
                <h3>管理员 - 添加机构</h3>
                <h5>网站系统机构管理</h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="form1" method="post" action="/index.php?m=Admin&c=Organization&a=add">
        <!-- <input type="hidden" name="id" value="<?php echo $id; ?>"> -->
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="user_name"><em>*</em>机构名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="name"  id="user_name" maxlength="20" class="input-txt">
                    <span class="err" id="err_user_name"></span>
                    <p class="notic">机构名称</p>
                </dd>
            </dl>
            
            <dl class="row" >
                <dt class="tit">
                    <label for="user_name"><em>*</em>所属分类</label>
                </dt>
                <dd class="opt">
                        <select class="sel" name="cat[]">
                           <option value='0'>请选择...</option>
                            <?php if(is_array($type) || $type instanceof \think\Collection || $type instanceof \think\Paginator): if( count($type)==0 ) : echo "" ;else: foreach($type as $key=>$v): ?>
                            <option value="<?php echo $v['category_id']; ?>"><?php echo $v['category_name']; ?></option>
                             <?php endforeach; endif; else: echo "" ;endif; ?>
                           
                        </select>
                   <!--  <span class="err" id="err_user_name"></span>
                    <p class="notic">所属分类</p> -->
                </dd>
            </dl>
             <dl class="row" >
                <dt class="tit">
                    <label for="user_name"><em>*</em>所属机构</label>
                </dt>
                <dd class="opt">
                        <select class="sell" name="ji[]">
                           <option value='0'>请选择...</option>
                            <?php if(is_array($type2) || $type2 instanceof \think\Collection || $type2 instanceof \think\Paginator): if( count($type2)==0 ) : echo "" ;else: foreach($type2 as $key=>$v): ?>
                            <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                             <?php endforeach; endif; else: echo "" ;endif; ?>
                           
                        </select>
                   <!--  <span class="err" id="err_user_name"></span>
                    <p class="notic">所属分类</p> -->
                </dd>
            </dl>
               <dl class="row">
                <dt class="tit">
                    <label for="user_name"><em>*</em>所属地区</label>
                </dt>
                <dd class="opt" id="addr">
                        <select class="addr" name="addr[]">
                           <option value='0'>请选择...</option>
                            <?php if(is_array($addr) || $addr instanceof \think\Collection || $addr instanceof \think\Paginator): if( count($addr)==0 ) : echo "" ;else: foreach($addr as $key=>$v): ?>
                            <option value="<?php echo $v['ID']; ?>"><?php echo $v['REGION_NAME']; ?></option>
                             <?php endforeach; endif; else: echo "" ;endif; ?>
                           
                        </select>
                   <!--  <span class="err" id="err_user_name"></span>
                    <p class="notic">所属分类</p> -->
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="user_name"><em>*</em>机构类型</label>
                </dt>
                <dd class="opt" id="addr">
                    <select  name="type">
                        <option value='xuan'>请选择...</option>
                        <option value="10">集团</option>
                        <option value="3">部门</option>
                        <option value="1">园所</option>
                        <option value="2">班级</option>
                        <option value="0">角色</option>

                    </select>
                    <!--  <span class="err" id="err_user_name"></span>
                     <p class="notic">所属分类</p> -->
                </dd>
            </dl>
               

           <!--  <dl class="row">
                <dt class="tit">
                    <label for="email"><em>*</em>所属投资人</label>
                </dt>
                <dd class="opt">
                	<select name="name" id="ren">

                		<option value="0">无投资人</option>
						<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $k=>$vo): ?>
						       <option value="<?php echo $vo['admin_id']; ?>"><?php echo $vo['user_name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
                	</select>
                	<select name="yuan">

                		
						<?php if(is_array($garden) || $garden instanceof \think\Collection || $garden instanceof \think\Paginator): if( count($garden)==0 ) : echo "" ;else: foreach($garden as $k=>$vo): ?>
						       <option value="<?php echo $vo['garden_id']; ?>"><?php echo $vo['garden_name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
                	</select>
                    <span class="err" id="err_email"></span><p class="notic">所属投资人</p>

                </dd>

            </dl> -->
    
         
            <div class="bot"><a href="JavaScript:void(0);" onclick="adsubmit();" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
        </div>
    </form>
</div>
</body>
</html>
<script>
      $(document).on('change','.addr',function(){
        var id = $(this).val();
        if(id==0){
            return false;
        }
        var obj =$(this);
        $.ajax({
            url:"/index.php?m=Admin&c=Organization&a=getaddr",
            dataType:'json',
            data:{id:id},
            success:function(res){
                 obj.nextAll().remove();
                 if(res.state==0){
                    return false;
                 }
                 var sel="<select class='addr' name='addr[]'><option value='0'>请选择...</option>";
                     //json的循环
                    $(res.data).each(function(key,val){
                        //自增的id                     市或区的名称
                        sel+="<option value='"+val.ID+"'>"+val.REGION_NAME+"</option>";
                    })
                    sel+="</select>";
                    //后面追加
                    obj.after(sel);
            }

        })
        //传值
       
        })
      $(document).on("click","#readdr",function(){
        var id="zhi";
        $.ajax({
            url:"/index.php?m=Admin&c=Organization&a=getaddr",
            data:{id:id},
            dataType:'json',
            success:function(res){
                var sel="<select class='addr' name='addr[]'><option value='0'>请选择...</option>";
                     //json的循环
                    $(res.data).each(function(key,val){
                        //自增的id                     市或区的名称
                        sel+="<option value='"+val.ID+"'>"+val.REGION_NAME+"</option>";
                    })
                    sel+="</select>";
                     $("#addr").html(sel)
            }
        })
        
       
      })
    $(document).on('change','.sell',function(){
        var id = $(this).val();
        $.ajax({
            url:"/index.php?m=Admin&c=Organization&a=getmoaddr",
            dataType:'json',
            data:{id:id},
            success:function(res){
                var sel='';
                     //json的循环
                    $(res).each(function(key,val){
                        //自增的id                     市或区的名称
                        sel+="<select class='sell' name='addr[]'>";
                        sel+="<option value='"+val.ID+"'>"+val.REGION_NAME+"</option>";
                        sel+="</select>";
                    })
                    sel+="<button type='button' id='readdr'>重新选取</button>";
                    $("#addr").html(sel)
                    
                    //后面追加
                    
            }
        })
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
  $(document).on('change','.sel',function(){
        var id = $(this).val();
        if(id==0){
            return false;
        }
        var obj =$(this);
        $.ajax({
            url:"/index.php?m=Admin&c=Organization&a=getcat",
            dataType:'json',
            data:{id:id},
            success:function(res){
                 obj.nextAll().remove();
                 if(res.state==0){
                    return false;
                 }
                 var sel="<select class='sel' name='cat[]'><option value='0'>请选择...</option>";
                     //json的循环
                    $(res.data).each(function(key,val){
                        //自增的id                     市或区的名称
                        sel+="<option value='"+val.category_id+"'>"+val.category_name+"</option>";
                    })
                    sel+="</select>";
                    //后面追加
                    obj.after(sel);
            }

        })
        //传值
       
        })


    function adsubmit(){
        $("#form1").submit();
    }
</script>