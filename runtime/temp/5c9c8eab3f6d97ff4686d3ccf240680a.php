<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:44:"./application/admin/view/shan/food_menu.html";i:1548675392;s:55:"/home/www/yey/application/admin/view/public/layout.html";i:1548654013;}*/ ?>
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
<style type="text/css" media="screen">
	
</style>
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<div class="subject">
				<h3>营养膳食--食谱列表展示</h3>
				<h5>食谱展示</h5>
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
			<li>营养膳食--食谱展示：展示菜品食谱</li>
		</ul>
	</div>
	<div class="flexigrid">
		<div class="mDiv">
			<div class="ftitle">
				<h3>食谱列表</h3>
				<h5>(共<?php echo count($list); ?>条记录)</h5>
			</div>
			<div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>
			<!-- <form class="navbar-form form-inline" action="<?php echo U('Shan/buy_list'); ?>" method="get">
				<div class="sDiv">
					开始时间：
					<div class="sDiv2">
						
						<input type="date" size="30" value="<?php echo $start; ?>" name="start" class="qsbox" style="width: 150px">
					</div>
					结束时间：
					<div class="sDiv2">
						
						
						<input type="date" size="30" value="<?php echo $end; ?>" name="end" class="qsbox" style="width: 150px">
						<input type="submit" class="btn" value="搜索">
					</div>
				</div>
			</form> -->
		</div>
		<div class="hDiv">
			<div class="hDivBox">
				<table cellspacing="0" cellpadding="0">
					<thead>
					<tr>
						<th class="sign">
							<div style="width: 24px;"><i class="ico-check"></i></div>
						</th>
						
						<!-- <th align="left" abbr="ac_id" >
							<div style="text-align: left; width: 100px;" class="">食谱ID</div>
						</th> -->
						<th align="left" abbr="ac_id" >
							<div style="text-align: left; width: 100px;" class="">食谱名</div>
						</th>
						<th align="left" abbr="ac_id" >
							<div style="text-align: left; width: 100px;" class="">食谱图片</div>
						</th>
						<th align="left" abbr="ac_id" >
							<div style="text-align: left; width: 100px;" class="">操作</div>
						</th>
					</tr>
					</thead>
				</table>
			</div>
		</div>
			<div class="tDiv">
			<div class="tDiv2">
				<div class="fbutton">
					<a href="/index.php?m=Admin&c=Shan&a=qian_food_add"">
						<div class="add" title="添加食谱">
							<span><i class="fa fa-plus"></i>添加食谱</span>
						</div>
					</a>
				</div>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="bDiv" style="height: auto;" >
			<div id="flexigrid" cellpadding="0" cellspacing="0" border="0" style="width:100%;height: 600px;">
				<table style="width: 500px;float: left;">
					<tbody>
					<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$val): ?>
				<tr>
					<td class="sign" style="width: 24px;">
							<div style="width: 24px;"><i class="ico-check"></i></div>
						</td>
					<td style="width: 100px;"><?php echo $val['name']; ?></td>
					<td style="width: 100px;"><img src="/public/img/<?php echo $val['img']; ?>" alt="这是<?php echo $val['name']; ?>,材料有<?php echo $val['cai']; ?>" style="width:50px;height: 50px;"></td>
					<td style="width: 200px;">
						<a href="javascript:void(0)" class="btn blue xq" class="btn" id="<?php echo $val['id']; ?>"><i class="fa fa-pencil-square-o"></i>查看详情</a>
		                <a class="btn red"  href="/index.php?m=Admin&c=Shan&a=food_menu_del&id=<?php echo $val['id']; ?>"><i class="fa fa-trash-o"></i>删除</a>
						
					</td>
				</tr>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			
					</tbody>
				</table>
				<div id="main" style="width: 1000px;height: 1000px;float: right;text-align: center;"></div>
	<!--分页位置-->
		
			</div>
			<div  style="float: left;margin-top: -80px;">
			　	<ul class="pager">
					<li><a href="?index.php&m=Admin&c=Shan&a=food_menu&page=<?php echo $uppage; ?>">&laquo;上一页</a></li>
					<li><a href="?index.php&m=Admin&c=Shan&a=food_menu&page=<?php echo $nextpage; ?>"><span>下一页&raquo;</span></a></li>
				</ul>
			</div>

			<div class="iDiv" style="display: none;"></div>
		</div>

		<!-- <?php echo $page; ?> --> </div>
</div>
<script>
	$(document).on('click',".xq",function(){
		var id = $(this).attr("id")
		$.ajax({
			url:"/index.php?m=Admin&c=Shan&a=getXQ",
			data:{id:id},
			dataType:'json',
			success:function(res){
				var str=`
					<table class="table">
								<tr>
									<td>菜品ID</td>
									<td>`+res.id+`</td>
								</tr>
								<tr>
									<td>菜谱名</td>
									<td>${res.name}</td>
								</tr>
								<tr>
									<td>所需食材</td>
									<td>${res.cai}</td>
								</tr>
								<tr>
									<td>菜品图片</td>
									<td><img src="/public/img/`+res.img+`" style="width:200px;height:200px;" alt=""></td>
								</tr>
								<tr>
								<td>修改图片：</td>
								<td><form action="/index.php?m=Admin&c=Shan&a=img_save" method="post" enctype="multipart/form-data"><input type="hidden" name="id" value="${res.id}"><input type="file" name="file"><input type="submit" value="修改"></form></td>
								</tr>
							</table>


				`
				$("#main").html(str)
			}
		})
	})
</script>
</body>
</html>
