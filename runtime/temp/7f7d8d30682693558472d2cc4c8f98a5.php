<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:40:"./application/admin/view/techang/XQ.html";i:1548752900;}*/ ?>
<table border="1" style="background-color:deepskyblue;">
    <tr>
        <td>特长课ID</td>
        <td><?php echo $data['id']; ?></td>
    </tr>
    <tr>
        <td>特长课名称</td>
        <td><?php echo $data['name']; ?></td>
    </tr>
    <tr>
        <td>授课人</td>
        <td><?php echo $data['ren_name']; ?></td>
    </tr>
    <tr>
        <td>配课老师</td>
        <td><?php echo $data['ren_name2']; ?></td>
    </tr>
    <tr>
        <td>价格</td>
        <td><?php echo $data['money']; ?></td>
    </tr>
    <tr>
        <td>结算比例</td>
        <td><?php echo $data['bili']; ?></td>
    </tr>
    <tr>
        <td>月课时</td>
        <td><?php echo $data['class_num']; ?></td>
    </tr>
    <?php if(is_array($data['ke']) || $data['ke'] instanceof \think\Collection || $data['ke'] instanceof \think\Paginator): if( count($data['ke'])==0 ) : echo "" ;else: foreach($data['ke'] as $key=>$val): ?>
        <tr>
            <td><?php echo $val['name']; ?></td>
            <td><?php echo $val['time']; ?></td>
        </tr>
    <?php endforeach; endif; else: echo "" ;endif; ?>

</table>