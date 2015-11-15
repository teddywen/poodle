<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link href="<?php echo Yii::app()->params->css_url;?>/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->params->css_url;?>/poodle.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo Yii::app()->params->js_url;?>/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->params->js_url;?>/bootstrap/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->params->js_url;?>/bootstrap/bootstrap-paginator.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->params->js_url;?>/jquery.form.js"></script>
</head>
<body>
    <div class="container">
        <!--Page Title-->
        <div class="row main-header">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <p><h1>上海 · 崇明 <small>建设美好家园</small></h1></p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <p></p>
                <dl class="dl-horizontal pull-right">
                    <dt>您好! </dt><dd><?php echo Yii::app()->user->getGovUser()->username; ?></dd>
                    <dt>上次登录时间 :</dt><dd><?php echo date("Y-m-d H:i:s", Yii::app()->user->getGovUser()->last_login_time); ?></dd>
                </dl>
            </div>
        </div><!--Page Title End-->

        <!--Page Menu-->
        <?php $module_id = $this->module&&$this->module->id?strtolower($this->module->id):""; ?>
        <?php $controller_id = strtolower($this->id); ?>
        <?php $action_id = strtolower($this->action->id); ?>
        
        <?php $subnav_release_active = $module_id == 'problem' && $controller_id == 'release' && $action_id == 'index'; ?>
        <?php $subnav_problem_active = $module_id == 'problem' && $controller_id == 'index' && in_array($action_id, array("index", "view")); ?>
        <?php $subnav_static_release_active = $module_id == 'problem' && $controller_id == 'releasestatic' && $action_id == 'index'; ?>
        <?php $subnav_static_solve_active = $module_id == 'problem' && $controller_id == 'solvestatic' && $action_id == 'index'; ?>
        <?php $subnav_user_active = $module_id == 'user' && $controller_id == 'index' && in_array($action_id, array("index", "update")); ?>
        <?php $subnav_create_user_active = $module_id == 'user' && $controller_id == 'index' && $action_id == 'create'; ?>
        <?php $subnav_category_active = $module_id == 'user' && $controller_id == 'category' && $action_id == 'index'; ?>
        <?php $subnav_create_category_active = $module_id == 'user' && $controller_id == 'category' && $action_id == 'create'; ?>
        <?php $subnav_admin_active = $module_id == 'user' && $controller_id == 'manager' && in_array($action_id, array("index", "update")); ?>
        <?php $subnav_create_admin_active = $module_id == 'user' && $controller_id == 'manager' && $action_id == 'create'; ?>
        <?php $subnav_operation_active = $module_id == 'user' && $controller_id == 'operation' && $action_id == 'index'; ?>
        <?php $subnav_modpass_active = $module_id == '' && $controller_id == 'modpass' && $action_id == 'index'; ?>
        
        <?php $nav_problem_active = $subnav_release_active || $subnav_problem_active || $subnav_static_release_active || $subnav_static_solve_active; ?>
        <?php $nav_user_active = $subnav_user_active || $subnav_create_user_active || $subnav_category_active || $subnav_create_category_active; ?>
        <?php $nav_admin_active = $subnav_admin_active || $subnav_create_admin_active; ?>
        <?php $nav_operation_active = $subnav_operation_active; ?>
        <?php $nav_profile_active = $subnav_modpass_active; ?>
        <div class="row main-menu">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <ul class="nav nav-tabs">
                    <?php if(Yii::app()->user->checkAccess('manage_problem_finder')): ?>
                        <li role="presentation" <?php if($nav_problem_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/problem/release");?>">问题管理</a></li>
                    <?php elseif(Yii::app()->user->checkAccess('manage_problem_admin') || Yii::app()->user->checkAccess('manage_problem_unit')): ?>
                        <li role="presentation" <?php if($nav_problem_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/problem/index");?>">问题管理</a></li>
                    <?php endif; ?>
                    <?php if(Yii::app()->user->checkAccess('manage_user')): ?>
                        <li role="presentation" <?php if($nav_user_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/user/index");?>">用户管理</a></li>
                    <?php endif; ?>
                    <?php if(Yii::app()->user->checkAccess('manage_admin')): ?>
                        <li role="presentation" <?php if($nav_admin_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/user/manager");?>">管理员管理</a></li>
                    <?php endif; ?>
                    <?php if(Yii::app()->user->checkAccess('manage_operation')): ?>
                        <li role="presentation" <?php if($nav_operation_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/user/operation");?>">操作日志</a></li>
                    <?php endif; ?>
                    <li role="presentation" class="dropdown <?php if($nav_profile_active): ?>active<?php endif; ?>">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                          账户 / 退出 <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $this->createUrl("/modpass/index");?>">账户</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?php echo $this->createUrl("/site/logout");?>">退出</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div><!--Page Menu End-->

        <div class="row main-breadcrumbs">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php $this->widget('application.widgets.BreadcrumbsWidget', array("links" => $this->breadcrumbs));?>
            </div>
        </div>

        <!--Page Body-->
        <div class="row main-body">
            <!--Sub Menu-->
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <ul class="nav nav-pills nav-stacked">
                    <?php if($nav_problem_active): ?>
                        <?php if(Yii::app()->user->checkAccess('create_problem')): ?>
                            <li role="presentation" <?php if($subnav_release_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/problem/release");?>">发布问题</a></li>
                        <?php endif; ?>
                        <?php if(Yii::app()->user->checkAccess('list_problem')): ?>
                            <li role="presentation" <?php if($subnav_problem_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/problem/index");?>">问题列表</a></li>
                        <?php endif; ?>
                        <?php if(Yii::app()->user->checkAccess('export_release_problem')): ?>
                            <li role="presentation" <?php if($subnav_static_release_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/problem/releaseStatic");?>">反馈汇总</a></li>
                        <?php endif; ?>
                        <?php if(Yii::app()->user->checkAccess('export_solve_problem')): ?>
                            <li role="presentation" <?php if($subnav_static_solve_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/problem/solveStatic");?>">整改汇总</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($nav_user_active): ?>
                        <?php if(Yii::app()->user->checkAccess('list_user')): ?>
                            <li role="presentation" <?php if($subnav_user_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/user/index");?>">用户列表</a></li>
                        <?php endif; ?>
                        <?php if(Yii::app()->user->checkAccess('create_user')): ?>
                            <li role="presentation" <?php if($subnav_create_user_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/user/index/create");?>">添加用户 <strong>+</strong></a></li>
                        <?php endif; ?>
                        <?php if(Yii::app()->user->checkAccess('list_category')): ?>
                            <li role="presentation" <?php if($subnav_category_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/user/category");?>">单位分类</a></li>
                        <?php endif; ?>
                        <?php if(Yii::app()->user->checkAccess('create_category')): ?>
                            <li role="presentation" <?php if($subnav_create_category_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/user/category/create");?>">添加单位分类 <strong>+</strong></a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($nav_admin_active): ?>
                        <?php if(Yii::app()->user->checkAccess('list_admin')): ?>
                            <li role="presentation" <?php if($subnav_admin_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/user/manager");?>">管理员列表</a></li>
                        <?php endif; ?>
                        <?php if(Yii::app()->user->checkAccess('create_admin')): ?>
                            <li role="presentation" <?php if($subnav_create_admin_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/user/manager/create");?>">添加管理员 <strong>+</strong></a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($nav_operation_active): ?>
                        <?php if(Yii::app()->user->checkAccess('list_operation')): ?>
                            <li role="presentation" <?php if($subnav_operation_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/user/operation");?>">日志列表</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($nav_profile_active): ?>
                        <li role="presentation" <?php if($subnav_modpass_active): ?>class="active"<?php endif; ?>><a href="<?php echo $this->createUrl("/modpass/index");?>">修改密码</a></li>
                        <li role="presentation"><a href="<?php echo $this->createUrl("/site/logout");?>">退出</a></li>
                    <?php endif; ?>
                </ul>
            </div><!--Sub Menu End-->
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                <?php echo $content;?>
            </div>
        </div><!--Page Body End-->

        <!--Page Footer-->
        <div class="row main-footer">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p class="text-center">Powered by scw team<br/>Ver 1.0.0</p>
            </div>            
        </div><!--Page Footer End-->
    </div>
</body>
</html>