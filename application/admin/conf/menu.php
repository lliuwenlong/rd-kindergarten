<?php
return	array(	
	'system'=>array('name'=>'系统','child'=>array(
				// array('name' => '权限','child'=>array(
				// 		array('name' => '管理员列表', 'act'=>'index', 'op'=>'Admin'),
				// 		array('name' => '角色管理', 'act'=>'role', 'op'=>'Admin'),
				// 		array('name'=>'权限资源列表','act'=>'right_list','op'=>'System'),
    //                     array('name' => '管理员日志', 'act'=>'log', 'op'=>'Admin'),
				// )),
                 array('name' => '分类管理','child'=>array(
                        array('name' => '分类列表', 'act'=>'inv', 'op'=>'Classify'),
                        // array('name' => '添加分类', 'act'=>'admin_info', 'op'=>'Classify'),
  
                )),
                // array('name' => '投资人管理','child'=>array(
                //     array('name' => '投资人管理  ', 'act'=>'index', 'op'=>'People'),
                // )),
                array('name' => '机构管理','child'=>array(
                        
                        array('name' => '机构列表', 'act'=>'index', 'op'=>'Organization'),
                        // array('name' => '人员管理  ', 'act'=>'index', 'op'=>'People'),
                         // array('name' => '人员列表', 'act'=>'index', 'op'=>'People'),
                        // array('name' => '添加机构', 'act'=>'add', 'op'=>'Organization'),
                )),
                  array('name' => '人员管理','child'=>array(
                        
                        // array('name' => '机构列表', 'act'=>'index', 'op'=>'Organization'),
                        array('name' => '人员管理', 'act'=>'index', 'op'=>'People'),
                         // array('name' => '人员列表', 'act'=>'index', 'op'=>'People'),
                        // array('name' => '添加机构', 'act'=>'add', 'op'=>'Organization'),
                )),
	)),
    'menu'=>array('name'=>'菜单管理','child'=>array(
            array('name' => '菜单','child'=>array(
                        array('name' => '菜单列表', 'act'=>'inv', 'op'=>'Menu'),
                        // array('name' => '权限管理', 'act'=>'node_list', 'op'=>'Menu'),
                )),

    )),
      'node'=>array('name'=>'权限管理','child'=>array(
            array('name' => '权限','child'=>array(
                        array('name' => '添加权限', 'act'=>'node', 'op'=>'Menu'),
                        // array('name' => '权限管理', 'act'=>'node_list', 'op'=>'Menu'),
                )),

    )),
        
		
	'manage'=>array('name'=>'园所经营','child'=>array(
		array('name' => '前台管理','child' => array(
                array('name' => '收入数据', 'act'=>'index', 'op'=>'Income'),
                array('name' => '支出数据', 'act'=>'index', 'op'=>'Expend'),
                array('name' => '园所备用金', 'act'=>'index', 'op'=>'Spare'),
//                array('name' => '财务报表', 'act'=>'Finance', 'op'=>'Statement'),
//                array('name' => '单项统计', 'act'=>'single', 'op'=>'Statement'),
                array('name' => '资产明细统计', 'act'=>'index', 'op'=>'Property'),
                array('name' => '物资保管账', 'act'=>'index', 'op'=>'Custody'),
                array('name' => '任务管理', 'act'=>'index', 'op'=>'Task'),
                array('name' => '明细账', 'act'=>'index', 'op'=>'Account'),

        )),
//        array('name' => '后台管理','child' => array(
//                array('name' => '收入数据', 'act'=>'index', 'op'=>'Income'),
//        )),
        // array('name' => '收入数据','child' => array(
        //         array('name' => '收入数据', 'act'=>'index', 'op'=>'Income'),
        // )),
        // array('name' => '支出数据','child' => array(
        //     array('name' => '支出数据', 'act'=>'index', 'op'=>'Expend'),
        // )),
        // array('name' => '园所备用金','child' => array(
        //     array('name' => '园所备用金', 'act'=>'index', 'op'=>'Spare'),
        // )),
        // array('name' => '报表','child' => array(
        //     array('name' => '财务报表', 'act'=>'Finance', 'op'=>'Statement'),
        //     array('name' => '单项统计', 'act'=>'Single', 'op'=>'Statement'),
        // )),
        // array('name' => '资产明细统计','child' => array(
        //     array('name' => '资产明细统计', 'act'=>'index', 'op'=>'Property'),
        // )),
        // array('name' => '物资保管账','child' => array(
        //     array('name' => '物资保管账', 'act'=>'index', 'op'=>'Custody'),
        // )),
        // array('name' => '任务管理','child' => array(
        //     array('name' => '任务管理', 'act'=>'index', 'op'=>'Task'),
        // )),
        // array('name' => '明细账','child' => array(
        //     array('name' => '明细账', 'act'=>'index', 'op'=>'Account'),
        // )),
	)),
    'message'=>array('name'=>'信息公告','child'=>array(
        array('name' => '通知公告','child' => array(
            array('name' => '通知公告展示', 'act'=>'show', 'op'=>'Inform'),
            array('name' => '通知公告添加', 'act'=>'index', 'op'=>'Inform'),

        )),
        array('name' => '工作任务','child' => array(
            array('name' => '工作任务展示', 'act'=>'show', 'op'=>'Work'),
            array('name' => '工作任务添加', 'act'=>'index', 'op'=>'Work'),

        )),
        array('name' => '园所动态','child' => array(
            array('name' => '园所动态展示', 'act'=>'show', 'op'=>'Dynamic'),
            array('name' => '园所动态添加', 'act'=>'index', 'op'=>'Dynamic'),

        )),
    )),

	'management'=>array('name'=>'园所管理','child'=>array(
        array('name' => '学生管理','child' => array(
            array('name' => '学生档案', 'act'=>'index', 'op'=>'Student'),
            array('name' => '添加学生', 'act'=>'student_add', 'op'=>'Student'),
            array('name' => '目标生源', 'act'=>'student_source', 'op'=>'Student'),
        )),
         array('name' => '考核管理','child' => array(
            array('name' => '管理指标', 'act'=>'index', 'op'=>'Assess'),
            array('name' => '园务考核评分', 'act'=>'garden', 'op'=>'Assess'),
            array('name' => '考核评分标准', 'act'=>'fen', 'op'=>'Assess'),
        )),
        array('name' => '特长管理','child' => array(
            array('name' => '特长统计', 'act'=>'index', 'op'=>'Techang'),
        )),
        array('name' => '人事管理','child' => array(
            array('name' => '添加员工', 'act'=>'index', 'op'=>'Staff'),
            array('name' => '员工信息', 'act'=>'show', 'op'=>'Staff'),
             array('name' => '考勤统计', 'act'=>'attendance', 'op'=>'Staff'),
            // array('name' => '成长统计', 'act'=>'growth', 'op'=>'Staff'),
            array('name' => '离职统计', 'act'=>'dimission', 'op'=>'Staff'),
            array('name' => '晋升统计', 'act'=>'promote', 'op'=>'Staff'),
            array('name' => '在职统计', 'act'=>'job', 'op'=>'Staff'),
            array('name' => '骨干生成统计', 'act'=>'backbone', 'op'=>'Staff'),
            array('name' => '招聘统计', 'act'=>'recruit', 'op'=>'Staff'),

        )),
        array('name' => '初始设置','child' => array(
            array('name' => '园所管理', 'act'=>'garden', 'op'=>'Staff'),
            array('name' => '职位管理', 'act'=>'position', 'op'=>'Staff'),
            array('name' => '部门管理', 'act'=>'branch', 'op'=>'Staff'),
            array('name' => '薪酬级别', 'act'=>'grade', 'op'=>'Staff'),
//            array('name' => '级别设置', 'act'=>'levels', 'op'=>'Staff'),
        )),
        array('name' => '保教管理','child' => array(
            array('name' => '幼儿考勤记录', 'act'=>'clocking_in', 'op'=>'Teach'),
            array('name' => '成长档案', 'act'=>'record', 'op'=>'Teach'),
            array('name' => '教育教学', 'act'=>'teaching', 'op'=>'Teach'),
            array('name' => '巡园日志', 'act'=>'patrol', 'op'=>'Teach'),
            array('name' => '考核报表', 'act'=>'statement', 'op'=>'Teach'),
//            array('name' => '园长日报', 'act'=>'garden_day', 'op'=>'Teach'),
//            array('name' => '工作日报', 'act'=>'job_day', 'op'=>'Teach'),
            array('name' => '工作日志', 'act'=>'journal', 'op'=>'Staff'),
        )),
	)),
       'shan'=>array('name'=>'营养膳食','child'=>array(
            array('name' => '营养膳食','child' => array(
                // array('name' => '添加菜品', 'act'=>'qian_food_add', 'op'=>'shan'),
                // array('name' => '月菜谱', 'act'=>'shan_add', 'op'=>'shan'),
                array('name' => '营养标准', 'act'=>'ying_biao', 'op'=>'shan'),
                array('name' => '食谱列表', 'act'=>'food_menu', 'op'=>'shan'),
                // array('name' => '替换菜品', 'act'=>'shan_source', 'op'=>'shan'),
                // array('name' => '制作周菜谱', 'act'=>'week_menu', 'op'=>'shan'),
                array('name' => '采购清单', 'act'=>'buy_list', 'op'=>'shan'),
                 array('name' => '食谱分类', 'act'=>'menu_type_list', 'op'=>'shan'),
            )),
            // array('name' => '后台管理','child' => array(
            //     array('name' => '添加菜谱', 'act'=>'hou_menu_add', 'op'=>'shan'),
            //     array('name' => '食谱分类', 'act'=>'hou_menu_type', 'op'=>'shan'),
            
            // )),    
    )),
  

);