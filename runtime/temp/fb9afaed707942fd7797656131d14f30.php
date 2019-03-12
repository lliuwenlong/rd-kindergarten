<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:43:"./application/admin/view/assess/garden.html";i:1548812307;s:55:"/home/www/yey/application/admin/view/public/layout.html";i:1548654013;}*/ ?>
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
<!--<link rel="stylesheet" href="/public/bootstrap/css/bootstrap.css">-->
<!--<script src="/public/bootstrap/js/bootstrap.js"></script>-->
<!--<script src="/public/bootstrap/js/jquery-1.7.2.min.js"></script>-->

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
				<h3>园所管理--考核管理--园务考核</h3>
				
			</div>
		</div>
	</div>

	<div class="flexigrid">

		<div class="ayou_div1">

			<!--设置选项卡-->
			<div class="ayou_div2">

				<ul id="a_1">
					<h3>所属集团：</h3>
					<li class="a_11 cl" id="0" cat="ji_id">全部</li>
					<?php if(is_array($where['ji']) || $where['ji'] instanceof \think\Collection || $where['ji'] instanceof \think\Paginator): if( count($where['ji'])==0 ) : echo "" ;else: foreach($where['ji'] as $key=>$val): ?>
						<li id="<?php echo $val['id']; ?>" cat="ji_id" class="cl"><?php echo $val['name']; ?></li>
					<?php endforeach; endif; else: echo "" ;endif; ?>
					
				</ul>
		</div>


			<!--设置选项卡-->
			<div class="ayou_div2">

				<ul id="a_2">
					<h3>所属园区/部门：</h3>
					<span id="garden">
					<li class="a_11 cl" id="0" cat="garden_id">全部</li>
					
						<?php if(is_array($where['garden']) || $where['garden'] instanceof \think\Collection || $where['garden'] instanceof \think\Paginator): if( count($where['garden'])==0 ) : echo "" ;else: foreach($where['garden'] as $key=>$val): ?>
							<li id="<?php echo $val['id']; ?>" cat="garden_id" class="cl"><?php echo $val['name']; ?></li>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</span>
				</ul>
		</div>

			<!--设置选项卡-->
			<div class="ayou_div2">

				<ul id="a_3">
					<h3>所属班级：</h3>
					<span id="class">
					<li class="a_11 cl" id="0" cat="class_id">全部</li>
					
						<?php if(is_array($where['class']) || $where['class'] instanceof \think\Collection || $where['class'] instanceof \think\Paginator): if( count($where['class'])==0 ) : echo "" ;else: foreach($where['class'] as $key=>$val): ?>
						<li id="<?php echo $val['id']; ?>" cat="class_id" class="cl"><?php echo $val['name']; ?></li>
					<?php endforeach; endif; else: echo "" ;endif; ?>
					</span>
					
				</ul>
		</div>

			<!--设置选项卡-->
			<div class="ayou_div2">

				<ul id="a_4">
					<h3>所属职位：</h3>
					<span id="ren">
					<li class="a_11 cl" id="0" cat="o_id">全部</li>
					<?php if(is_array($where['post']) || $where['post'] instanceof \think\Collection || $where['post'] instanceof \think\Paginator): if( count($where['post'])==0 ) : echo "" ;else: foreach($where['post'] as $key=>$val): ?>
						<li id="<?php echo $val['id']; ?>" cat="o_id" class="cl"><?php echo $val['name']; ?></li>
					<?php endforeach; endif; else: echo "" ;endif; ?>
					</span>
				</ul>
		</div>

			<!--设置选项卡-->
			<div class="ayou_div2">

				<ul id="a_5">
					<h3>评分分类：</h3>
					<span id="fen">
						<li class="a_11 cl" id="0" cat="fen_id">全部</li>
						<?php if(is_array($where['type']) || $where['type'] instanceof \think\Collection || $where['type'] instanceof \think\Paginator): if( count($where['type'])==0 ) : echo "" ;else: foreach($where['type'] as $key=>$val): ?>
							<li id="<?php echo $val['id']; ?>" cat="fen_id" class="cl"><?php echo $val['name']; ?></li>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</span>
				</ul>
		</div>
		</div>

	<div class="bDiv" style="height: auto;">
		<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
		<table class="table" >
			
			<thead>
				<tr style="background-color: skyblue;">
					<th>编号</th>
					<th>员工姓名</th>
					<th>职务</th>
					<th>联系方式</th>
					<th>所在园区/部门</th>
					<th>所在集团</th>
					<th>状态</th>
					<th>评分</th>
					
				</tr>
			</thead>
			<tbody id="tbd">
				<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$val): ?>
					<tr>
						<td><?php echo $val['staff_id']; ?></td>
						<td><?php echo $val['staff_name']; ?></td>
						<td><?php echo $val['r_name']; ?></td>
						<td><?php echo $val['staff_tel']; ?></td>
						<td><?php echo $val['g_name']; ?></td>
						<td><?php echo $val['j_name']; ?></td>
						<td>
							<?php if($val['staff_status'] == 1): ?>
								在职
								<?php else: ?>
								离职
							<?php endif; ?>
						</td>
						<td><?php echo $val['fen']; ?></td>
						<!-- <td><button type="button" id="<?php echo $val['staff_id']; ?>">评分</button><button type="button" id="<?php echo $val['staff_id']; ?>">详情</button></td> -->
					</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>
				
			</tbody>
		</table>
		</div>
		</div>

</div>
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
			zouni('a_2');
			zouni('a_3');
			zouni('a_4');
			zouni('a_5');
	$(document).ready(function(){
		// 表格行点击选中切换
		$('#flexigrid > table>tbody >tr').click(function(){
			$(this).toggleClass('trSelected');
		});

		// 点击刷新数据
		$('.fa-refresh').click(function(){
			location.href = location.href;
		});

	});

	$(document).on('click',".cl",function(){
		$(this).addClass("a_11")
		$(this).siblings().attr("class","cl")
		var cat = $(this).attr("cat");
		var id = $(this).attr("id");
		if(id =='0'){
			history.go(0)
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
		// console.log(arr);
		// return;
		
		
		if(cat=='ji_id'){
		    $("#class").html('<li class="a_11 cl" id="0" cat="class_id">全部</li>');
			$.ajax({
				url:"/index.php?m=Admin&c=Assess&a=getGarden",
				data:{id:id,cat:cat},
				dataType:'json',
				success:function(res){
					// console.log(res)
					var st ='<li class="a_11 cl" cat="garden_id" id="0">全部</li>';
					$.each(res,function(i,val){
						st +=`<li id="${val.id}" cat="garden_id" class="cl">${val.name}</li>`
					})

					$("#garden").html(st)
				}
			})
            $.ajax({
                url:"/index.php?m=Admin&c=Assess&a=getRole",
                data:{id:id,cat:cat},
                dataType:'json',
                success:function(res){
                    // console.log(res)
                    var st ='<li class="a_11 cl" cat="o_id" id="0">全部</li>';
                    $.each(res,function(i,val){
                        st +=`<li id="${val.id}" cat="o_id" class="cl">${val.name}</li>`
                    })

                    $("#ren").html(st)
                }
            })
            $.ajax({
                url:"/index.php?m=Admin&c=Assess&a=getJiType",
                data:{id:id,status:status},
                dataType:'json',
                success:function(res){
                    var str ='<li class="a_11 cl" cat="fen_id" id="0">全部</li>';
                    $.each(res,function(i,val){
                        str +=`<li id="${val.id}" cat="fen_id" class="cl">${val.name}</li>`
                    })
                    $("#fen").html(str)
                }
            })
		}
		if(cat=='garden_id'){
			$.ajax({
				url:"/index.php?m=Admin&c=Assess&a=getClass",
				data:{id:id,cat:cat},
				dataType:'json',
				success:function(res){
					// console.log(res)
					var stt ='<li class="a_11 cl" cat="class_id" id="0">全部</li>';
					$.each(res,function(i,val){
						stt +=`<li id="${val.id}" cat="class_id" class="cl">${val.name}</li>`
					})

					$("#class").html(stt)
				}
			})
		}

		if(cat =='o_id'){
			var tid = $(this).html();
			// console.log(tid);return;
				$.ajax({
					url:"/index.php?m=Admin&c=Assess&a=getFenType",
					data:{tid:tid},
					type:'post',  
					dataType:'json',
					success:function(res){
						var sttt ='<li class="a_11 cl" cat="fen_id" id="0">全部</li>';
						$.each(res,function(i,val){
							sttt +=`<li id="${val.id}" cat="fen_id" class="cl">${val.name}</li>`
						})
							$("#fen").html(sttt)
					}
				})
			

		}
		
		console.log(arr);
		// arr =JSON.stringify(arr);
		// console.log(arr);
		$.ajax({
			url:"/index.php?m=Admin&c=Assess&a=getData",
			data:{"arr":arr},
			type:'post',  
            // traditional :true,
			dataType:'json',
			success:function(res){
				// console.log(res)
				str ='';
				$.each(res,function(i,val){
					str +=`
					<tr>
						<td>${val.staff_id}</td>
						<td>${val.staff_name}</td>
						<td>${val.r_name}</td>
						<td>${val.staff_tel}</td>
						<td>${val.g_name}</td>
						<td>${val.j_name}</td>
						<td>`;
							if(val.staff_status==1){
								str +='在职'
							}else{
								str +='离职'
							}
						str +=`
						</td>
						<td>${val.fen}</td>
						<!-- <td><button type="button" id="${val.staff_id}">评分</button><button type="button" id="${val.staff_id}">详情</button></td> -->
					</tr>
					`;
				})
				$("#tbd").html(str)
			}
		})
	})


	function delfun(obj) {
		// 删除按钮
		layer.confirm('确认删除？', {
			btn: ['确定', '取消'] //按钮
		}, function () {
			$.ajax({
				type: 'post',
				url: $(obj).attr('data-url'),
				data : {act:'del',admin_id:$(obj).attr('data-id')},
				dataType: 'json',
				success: function (data) {
					if (data.status == 1) {
						layer.msg(data.msg,{icon: 1,time: 1000},function () {
							$(obj).parent().parent().parent().remove();
						})
					} else {
						layer.msg(data.msg,{icon: 2,time: 2000})
					}
				}
			})
		}, function () {
		});
	}
</script>
</body>
</html>