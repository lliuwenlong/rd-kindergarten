<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:44:"./application/admin/view/teach/recordXQ.html";i:1548654015;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h2>学生档案详情</h2>
	<table border="1" style="background-color: red;text-align: center;">
			
			<thead>
				<tr style="background-color: skyblue;">
					<th>ID</th>
					<th>活动内容标题</th>
					<th>活动内容图片</th>
					<th>添加时间</th>
				</tr>
			</thead>
			<tbody id="tbd">
				<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$val): ?>
					

						<tr>
							<td><?php echo $val['id']; ?></td>
							<td><?php echo $val['desc']; ?></td>
							<td>
								<?php if(is_array($val['img']) || $val['img'] instanceof \think\Collection || $val['img'] instanceof \think\Paginator): if( count($val['img'])==0 ) : echo "" ;else: foreach($val['img'] as $key=>$v): ?>
								      <img style="width:50px;height:50px;" src="/public/img/<?php echo $v; ?>">
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</td>
							<td><?php echo date("Y-m-d",$val['addtime']); ?></td>
							
							<!-- <td><button type="button" id="<?php echo $val['staff_id']; ?>">评分</button><button type="button" id="<?php echo $val['staff_id']; ?>">详情</button></td> -->
						</tr>
					
				<?php endforeach; endif; else: echo "" ;endif; ?>
				
			</tbody>
		</table>
</body>
</html>