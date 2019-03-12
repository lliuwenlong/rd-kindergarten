<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:44:"./application/admin/view/shan/ying_biao.html";i:1548654013;s:55:"/home/www/yey/application/admin/view/public/layout.html";i:1548654013;}*/ ?>
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
				<h3>营养膳食--营养标准</h3>
				<h5>营养标准</h5>
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
			<li>营养膳食--营养标准：规定营养标准 展示每周营养成分</li>
		</ul>
	</div>
	<div class="flexigrid">
		<div class="mDiv">
			<div class="ftitle">
				<h3>营养成分标准</h3>
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
						<th class="sign" axis="col0">
							<div style="width: 24px;"><i class="ico-check"></i></div>
						</th>
						<th align="left" abbr="article_title" axis="col3" class="">
							<div style="text-align: left; width: 100px;" class="">   </div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">能量</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">蛋白质</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">脂肪</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">碳水化合物</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">膳食纤维</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">维生素A</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">维生素B1</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">维生素B2</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">尼克酸</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">维生素C</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">维生素E</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">钙</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">磷</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">钾</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">纳</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">镁</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">铁</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">锌</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">硒</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">铜</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: left; width: 100px;" class="">钼</div>
						</th>
						<th style="width:100%" axis="col7">
							<div></div>
						</th>
					</tr>
					</thead>
				</table>
			</div>
		</div>
		<div class="tDiv">
			<div class="tDiv2">
				<div class="fbutton">
				<!-- 	<a href="<?php echo U('Shan/ying_biao_she'); ?>">
						<div class="add" title="前去采购">
							<span><i class="fa fa-plus"></i>设置营养标准</span>
						</div>
					</a> -->
				</div>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="bDiv" style="height: auto;">
			<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
				<table>
					<tbody>
					<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $k=>$vo): ?>
						<tr id="<?php echo $vo['id']; ?>">
							<td class="sign">
								<div style="width: 24px;"><i class="ico-check"></i></div>
							</td>
							<td align="left" >
								<div style="text-align: left; width: 100px;"><?php echo $vo['name']; ?></div>
							</td>
							<td align="left" class="cl" state="0" type="nl" >
								<div style="text-align: left; width: 100px;"><?php echo $vo['nl']; ?></div>
							</td>
							<td align="left" class="cl" state="0" type="dbz">
								<div style="text-align: left; width: 100px;"><?php echo $vo['dbz']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="zf">
								<div style="text-align: left; width: 100px;"><?php echo $vo['zf']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="tan">
								<div style="text-align: left; width: 100px;"><?php echo $vo['tan']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="xian">
								<div style="text-align: left; width: 100px;"><?php echo $vo['xian']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="weia">
								<div style="text-align: left; width: 100px;"><?php echo $vo['weia']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="weib1">
								<div style="text-align: left; width: 100px;"><?php echo $vo['weib1']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="weib2">
								<div style="text-align: left; width: 100px;"><?php echo $vo['weib2']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="ni">
								<div style="text-align: left; width: 100px;"><?php echo $vo['ni']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="weic">
								<div style="text-align: left; width: 100px;"><?php echo $vo['weic']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="weie">
								<div style="text-align: left; width: 100px;"><?php echo $vo['weie']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="gai">
								<div style="text-align: left; width: 100px;"><?php echo $vo['gai']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="lin">
								<div style="text-align: left; width: 100px;"><?php echo $vo['lin']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="jia">
								<div style="text-align: left; width: 100px;"><?php echo $vo['jia']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="na">
								<div style="text-align: left; width: 100px;"><?php echo $vo['na']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="mei">
								<div style="text-align: left; width: 100px;"><?php echo $vo['mei']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="tei">
								<div style="text-align: left; width: 100px;"><?php echo $vo['tei']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="xin">
								<div style="text-align: left; width: 100px;"><?php echo $vo['xin']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="xi">
								<div style="text-align: left; width: 100px;"><?php echo $vo['xi']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="tong">
								<div style="text-align: left; width: 100px;"><?php echo $vo['tong']; ?></div>
							</td>
							<td align="left" class="cl" state="0"  type="bo">
								<div style="text-align: left; width: 100px;"><?php echo $vo['bo']; ?></div>
							</td>
							
							
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
<script>

	// 显示文本框并显示原值
	$(document).on("click", ".cl", function () {
		var _this = $(this);
		var name = _this.attr("type")
		var old_val = _this.children().html(); // 获取要修改的值
		_this.html("<input type='text' style='width: 100px;' name=" + name + " class='focus' value=" + old_val + " />"); // 显示文本框
		$(".focus").focus(); // 存在瑕疵，光标无法聚焦到文本最后的位置
		$(":text").select(); // 改进，弥补瑕疵，全选文字
	})
	$(document).on("blur", ".focus", function () {
		var _this = $(this);
		var val=_this.val();
		var id=_this.parent().parent().attr("id");
		var name=_this.attr("name");
		$.ajax({
			url:'/index.php?m=Admin&c=Shan&a=ying_biao',
			data:{val:val,id:id,name:name},
			dataType:'json',
			success:function(res){

				
			}
		})
		_this.parent().html('<div style="text-align: left; width: 100px;">'+val+'</div>');
	
	})
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