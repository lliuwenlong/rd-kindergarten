<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:44:"./application/admin/view/teach/teaching.html";i:1548654015;s:55:"/home/www/yey/application/admin/view/public/layout.html";i:1548654013;}*/ ?>
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
				<h3>园所管理--保教管理--巡园日志</h3>
				
			</div>
		</div>
	</div>
	
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
		

			<!--&lt;!&ndash;设置选项卡&ndash;&gt;-->
			<!--<div class="ayou_div2">-->

				<!--<ul id="a_3">-->
					<!--<h3>所属班级：</h3>-->
					<!--<span id="class">-->
					<!--<li class="a_11 cl" id="0" cat="class_id">全部</li>-->
					<!---->
						<!--<?php if(is_array($where['class']) || $where['class'] instanceof \think\Collection || $where['class'] instanceof \think\Paginator): if( count($where['class'])==0 ) : echo "" ;else: foreach($where['class'] as $key=>$val): ?>-->
						<!--<li id="<?php echo $val['id']; ?>" cat="class_id" class="cl"><?php echo $val['name']; ?></li>-->
					<!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
					<!--</span>-->
					<!---->
				<!--</ul>-->
		<!--</div>-->
		
		</div>
		<!-- <hr>
		<div class="flexigrid">
				<div class="tDiv">
		            <div class="tDiv2">
		                <div class="fbutton">
		                    <a href="<?php echo U('Teach/statement_add',['id'=>0]); ?>">
		                        <div class="add" title="前去采购">
		                            <span><i class="fa fa-plus"></i>园所评分标准添加</span>
		                        </div>
		                    </a>
		                  
		                </div>
		            </div>
		        </div>
		 </div> -->
		<hr>
		<table border="1" style="background-color: red;text-align: center;">
			
			<thead>
				<tr style="background-color: skyblue;">

					<th>ID</th>
					<th>教学封面</th>
					<th>教学文件</th>
					<th>教学内容图片</th>
					<th>教学内容视频</th>
					<th>添加时间</th>
					<th>添加人</th>
				</tr>
			</thead>
			<tbody id="tbd">
				<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$val): ?>
					<tr>
						<td><?php echo $val['id']; ?></td>	
						<td><img src="/public/img/<?php echo $val['mian']; ?>" style="width:50px;height:50px;"></td>	
						<td><?php echo $val['wen']; ?></td>	
						<td><?php echo $val['img']; ?></td>	
						<td><?php echo $val['shipin']; ?></td>	
						<td><?php echo $val['addtime']; ?></td>
						<td><?php echo $val['admin_id']; ?></td>

					</tr>
				<?php endforeach; endif; else: echo "" ;endif; ?>
				
			</tbody>
		</table>
		

<script>
	$(document).on('mouseover','.img',function(){
		 // oElement.style.cursor = "pointer";
		$(this).children().show();
	})
	$(document).on('mouseout','.img',function(){
		 // oElement.style.cursor = "pointer";
		$(this).children("img").hide();
	})
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
			// zouni('a_3');
			

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
			var tid = $(this).html()
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
		$.ajax({
			url:"/index.php?m=Admin&c=Teach&a=getteachingData",
			data:{"arr":arr},
			type:'post',  
			dataType:'json',
			success:function(res){
				var str='';
				$.each(res,function (i,val) {
					str +=`
						<tr>
						<td>${val.id}</td>
						<td><img src="/public/img/${val.mian}" style="width:50px;height:50px;"></td>
						<td>${val.wen}</td>
						<td>${val.img}</td>
						<td>${val.shipin}</td>
						<td>${val.addtime}</td>
						<td>${val.admin_id}</td>

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