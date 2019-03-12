<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:40:"./application/admin/view/assess/fen.html";i:1548654011;s:55:"/home/www/yey/application/admin/view/public/layout.html";i:1548654013;}*/ ?>
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
            
            .ayou_div1 {
                
                float: left;
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
                <h3>园所管理--考核管理--考核评分管理</h3>
                <h5>考核评分管理</h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div id="explanation" class="explanation" style="color: rgb(44, 188, 163); background-color: rgb(237, 251, 248); width: 99%; height: 100%;">
        <div id="checkZoom" class="title"><i class="fa fa-lightbulb-o"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span title="收起提示" id="explanationZoom" style="display: block;"></span>
        </div>
        <ul>
            <li>园所管理--考核管理--考核评分管理：展示各个职位的考核评分指标</li>
        </ul>
    </div>
    <div class="flexigrid">
        <div class="mDiv">
            <div class="ftitle">
                <h3>考核评分列表</h3>
                <h5>(共<?php echo count($list); ?>条记录)</h5>
            </div>
            <div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>
       
           <!--  <form class="navbar-form form-inline" action="<?php echo U('Admin/index'); ?>" method="get">
                <div class="sDiv">
                    <div class="sDiv2">
                        <input type="text" size="30" name="keywords" class="qsbox" placeholder="搜索相关数据...">
                        <input type="submit" class="btn" value="搜索">
                    </div>
                </div>
            </form> -->
        </div>
             <div class="tDiv">
            <div class="tDiv2">
                <div class="fbutton">
                    <a href="<?php echo U('Assess/type_add',['id'=>1]); ?>">
                        <div class="add" title="前去采购">
                            <span><i class="fa fa-plus"></i>添加评分标准分类</span>
                        </div>
                    </a>
                    <a href="<?php echo U('Assess/fen_add',['id'=>0]); ?>">
                        <div class="add" title="前去采购">
                            <span><i class="fa fa-plus"></i>添加园长评分标准</span>
                        </div>
                    </a>
                    <a href="<?php echo U('Assess/fen_add',['id'=>1]); ?>">
                        <div class="add" title="前去采购">
                            <span><i class="fa fa-plus"></i>添加员工评分标准</span>
                        </div>
                    </a>
                   <!--   <a href="<?php echo U('Assess/fen_type_add'); ?>">
                        <div class="add" title="前去采购">
                            <span><i class="fa fa-plus"></i>添加评分分类</span>
                        </div>
                    </a> -->
                </div>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="ayou_div1">


                <!--设置选项卡-->
                <div class="ayou_div2">

                    <ul id="a_1">
                        <h3>所属集团：</h3>
                        <li class="a_11 cl" id="0" cat="ji_id">全部</li>
                        <?php if(is_array($ji) || $ji instanceof \think\Collection || $ji instanceof \think\Paginator): if( count($ji)==0 ) : echo "" ;else: foreach($ji as $key=>$val): ?>
                            <li id="<?php echo $val['id']; ?>" cat="ji_id" class="cl"><?php echo $val['name']; ?></li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>

                    </ul>
                </div>

            <!--<div class="ayou_div2">-->

                <!--<ul id="a_2">-->
                    <!--<h3>所属园区/部门：</h3>-->
                    <!--<span id="garden">-->
					<!--<li class="a_11 cl" id="0" cat="garden_id">全部</li>-->

						<!--<?php if(is_array($garden) || $garden instanceof \think\Collection || $garden instanceof \think\Paginator): if( count($garden)==0 ) : echo "" ;else: foreach($garden as $key=>$val): ?>-->
							<!--<li id="<?php echo $val['id']; ?>" cat="garden_id" class="cl"><?php echo $val['name']; ?></li>-->
						<!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
					<!--</span>-->
                <!--</ul>-->
            <!--</div>-->

            <!--设置选项卡-->
            <div class="ayou_div2">

                <ul id="a_3">
                    <h3>评分对象：</h3>
                    <span id="ren">
                    <li class="a_11 cl" id="0" cat="role_id">全部</li>
                    
                        <?php if(is_array($ren) || $ren instanceof \think\Collection || $ren instanceof \think\Paginator): if( count($ren)==0 ) : echo "" ;else: foreach($ren as $key=>$val): ?>
                            <li id="<?php echo $val['id']; ?>" cat="role_id" class="cl"><?php echo $val['name']; ?></li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </span>
                </ul>
        </div>
          <div class="ayou_div1">

            <!--设置选项卡-->
            <div class="ayou_div2">

                <ul id="a_4">
                    <h3>评分分类：</h3>
                    <span id="fen">
                    <li class="a_11 cl" id="0" cat="type_id">全部</li>
                    
                        <?php if(is_array($type) || $type instanceof \think\Collection || $type instanceof \think\Paginator): if( count($type)==0 ) : echo "" ;else: foreach($type as $key=>$val): ?>
                            <li id="<?php echo $val['id']; ?>" cat="type_id" class="cl"><?php echo $val['name']; ?></li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </span>
                </ul>
        </div>
      <!--   <select name="role" class="role">
            <option value="0">请选择</option>
           
            <?php if(is_array($role) || $role instanceof \think\Collection || $role instanceof \think\Paginator): if( count($role)==0 ) : echo "" ;else: foreach($role as $key=>$val): ?>
                 <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </select> -->
        <br>
        <table border="1" style="background-color:yellow;text-align: center;">
             
                
                    <tr style="background-color: skyblue;">
                        <td>编号</td>
                        <td style="width:200px;">加分标准</td>
                        <td>成长值奖励值</td>
                    </tr>
                    <tbody id="tbd">
                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$val): ?>
                        <tr>
                            <td><?php echo $val['number']; ?></td>
                            <td><?php echo $val['desc']; ?></td>
                            <td><?php echo $val['fen']; ?></td>
                        </tr> 
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    
                </tbody>
        </table>
        <div id="fen" style="width:500px;height: 500px;float: left">
  


        </div>
        <div id="xq" style="width:500px;float: left">
            
        </div>

        
</div>
</body>
</html>
<script>
        function zouni(myID) {
                //设置选项一栏
                var a_1 = document.getElementById(myID);
                var a_1lis = a_1.getElementsByTagName('li');
                //li div数量相等 下标一样 使用遍历
                for(var i = 0; i < a_1lis.length; i++) {
                    //保存鼠标移入当前li的下标
                    a_1lis[i].index = i;
                    //鼠标点击
                    a_1lis[i].onclick = function() {
                        //去掉指定li样式影藏所有div
                        for(var j = 0; j < a_1lis.length; j++) {
                            a_1lis[j].className = 'cl';
                        }
                        //显示当前li想其对应的div        
                        a_1lis[this.index].className = 'a_11 cl';
                    }
                }
            };
            zouni('a_1');
            // zouni('a_2');
            zouni('a_3');
            zouni('a_4');

    $(document).on('click','.cl',function(){
        $(this).addClass("a_11")
        $(this).siblings().attr("class","cl")
        var cat = $(this).attr("cat");
        var id = $(this).attr("id");
        if(id =='0'){
            history.go(0)
        }
        if(cat=='ji_id'){
            $.ajax({
                url:"/index.php?m=Admin&c=Assess&a=getRole",
                data:{id:id,cat:cat},
                dataType:'json',
                success:function(res){
                    // console.log(res)
                    var st ='<li class="a_11 cl" cat="role_id" id="0">全部</li>';
                    $.each(res,function(i,val){
                        st +=`<li id="${val.id}" cat="role_id" class="cl">${val.name}</li>`
                    })

                    $("#ren").html(st)
                }
            })
            $.ajax({
                url:"/index.php?m=Admin&c=Assess&a=getJiType",
                data:{id:id,status:status},
                dataType:'json',
                success:function(res){
                    var str ='<li class="a_11 cl" cat="type_id" id="0">全部</li>';
                    $.each(res,function(i,val){
                        str +=`<li id="${val.id}" cat="type_id" class="cl">${val.name}</li>`
                    })
                    $("#fen").html(str)
                }
            })
        }
         if(cat=='role_id'){
           var tid = $(this).html()
                $.ajax({
                    url:"/index.php?m=Admin&c=Assess&a=getFenType",
                    data:{tid:tid},
                    type:'post',  
                    dataType:'json',
                    success:function(res){
                        var sttt ='<li class="a_11 cl" cat="type_id" id="0">全部</li>';
                        $.each(res,function(i,val){
                            sttt +=`<li id="${val.id}" cat="type_id" class="cl">${val.name}</li>`
                        })
                            $("#fen").html(sttt)
                    }
                })
        }
        var obj = $(".a_11");
        // console.log(obj)
        var arr=new Array();
        $(obj).each(function(i,v){
            var dat={
                id:v.id,
                type:$(this).attr("cat")
            };
            arr.push(dat)
        })
        console.log(arr)
        // return;
       
        $.ajax({
            url:"/index.php?m=Admin&c=Assess&a=getFenData",
            data:{"arr":arr},
            type:'post',  
            dataType:'json',
            success:function(res){
               var str = ""
                $.each(res,function(i,val){
                    str +=`<tr>
                            <td>${val.number}</td>
                            <td>${val.desc}</td>
                            <td>${val.fen}</td>
                        </tr> `
                })
                $("#tbd").html(str)
            }

        })
    })
    $(document).on('change',".role",function(){
        $("#fen").empty();
        $("#xq").empty();
        var role = $(this).val();
        $.ajax({
            url:'/index.php?m=Admin&c=Assess&a=getFen',
            data:{role:role},
            dataType:'json',
            success:function(res){
                var str=`<table border="1">
                        <tr style="background-color: skyblue;">
                            <th>考核分类</th>
                            <th>操作</th>
                        </tr>`;
                 $.each(res.data,function(i,v){
                    str+=`
                        <tr>
                            <td>`+v.name+`</td>
                            <td><button type="button" class='btn' type_id="${v.type_id}" role_id="`+role+`">查看详情</button></td>
                        </tr>
                    `;
                 })  


                    

                
                str +=`</table>`;
                $("#fen").html(str);
            }
        })
    })
    $(document).on('click',".btn",function(){
        var type_id = $(this).attr("type_id");
        var role_id = $(this).attr("role_id");
        $.ajax({
            url:'/index.php?m=Admin&c=Assess&a=getFenXQ',
            data:{role_id:role_id,type_id:type_id},
            dataType:'json',
            success:function(res){
                str=` <table border="1" style="background-color:yellow;display: inline-block;text-align: center;">
                <thead>
                    <tr>
                        <th colspan="3">${res[0]['name']}</th>

                    </tr>
                </thead>
                <tbody>
                    <tr style="background-color: skyblue;">
                        <td>编号</td>
                        <td style="width:200px;">加分标准</td>
                        <td>成长值奖励值</td>
                    </tr>
                    `;
                   
           
                $.each(res,function(i,v){
                    str+=` <tr>
                        <td>${v.number}</td>
                        <td>${v.desc}</td>
                        <td>${v.fen}</td>
                    </tr>`;
                })
                str +=`</tbody></table>`;

                $("#xq").html(str)
            }
        })
    })
</script>