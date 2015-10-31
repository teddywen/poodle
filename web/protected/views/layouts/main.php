<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link href="<?php echo Yii::app()->params->css_url;?>/bootstrap/bootstrap-combined.min.css" rel="stylesheet">
	<link href="<?php echo Yii::app()->params->css_url;?>/my_style.css" rel="stylesheet">
	<script type="text/javascript" src="<?php echo Yii::app()->params->js_url;?>/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->params->js_url;?>/jquery.form.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->params->js_url;?>/bootstrap/bootstrap-paginator.min.js"></script>
	
</head>
<body>
    <div class="container">
    	<div class="row">
    		<div class="span12">
    			<div class="page-header">
    				<h1>
    					问题报告系统&nbsp;&nbsp;&nbsp;&nbsp;<small>发现问题&nbsp;&nbsp;解决问题</small>
    				</h1>
    			</div>
    			<!-- 菜单开始 -->
    			<div class="row">
    				<div class="span2">
    				    <?php
    				        $module_id = strtolower($this->module->id);
    				        $controller_id = strtolower($this->id);
    				        $action_id = strtolower($this->action->id);
    				    ?>
    					<ul class="nav nav-list well" style="font-size: 16px;">
                            <?php if(!Yii::app()->user->checkAccess('superAdmin')):?>
    						<li class="nav-header" style="font-size: 24px;">
    							问题管理
    						</li>
    						<?php if(Yii::app()->user->checkAccess('finder')):?>
    						<li<?php if($module_id=='problem'&&$controller_id=='release'&&$action_id=='index'):?> class="active"<?php endif;?>>
    							<a href="<?php echo Yii::app()->baseUrl;?>/problem/release">发布问题</a>
    						</li>
    						<?php endif;?>
    						<li<?php if($module_id=='problem'&&$controller_id=='index'&&$action_id=='index'):?> class="active"<?php endif;?>>
    							<a href="<?php echo Yii::app()->baseUrl;?>/problem">问题列表</a>
    						</li>
    						<?php if(Yii::app()->user->checkAccess('admin')):?>
    						<li class="nav-header" style="font-size: 24px;">
    							用户管理
    						</li>
    						<li<?php if($module_id=='user'&&$controller_id=='index'&&$action_id=='index'):?> class="active"<?php endif;?>>
    							<a href="<?php echo Yii::app()->baseUrl;?>/user">用户列表</a>
    						</li>
    						<li<?php if($module_id=='user'&&$controller_id=='category'&&$action_id=='index'):?> class="active"<?php endif;?>>
    							<a href="<?php echo Yii::app()->baseUrl;?>/user/category">单位分类</a>
    						</li>
                            <li <?php if($controller_id=='operation'&&$action_id=='index'):?> class="active"<?php endif;?>>
                                <a href="<?php echo Yii::app()->baseUrl;?>/user/operation">用户操作日志</a>
                            </li>
    						<?php endif;?>
    						<?php else:?>
    						<li class="nav-header" style="font-size: 24px;">
    							管理员管理
    						</li>
    						<li<?php if($module_id=='user'&&$controller_id=='manager'&&$action_id=='index'):?> class="active"<?php endif;?>>
    							<a href="<?php echo Yii::app()->baseUrl;?>/user/manager">管理员列表</a>
    						</li>
                            <li <?php if($controller_id=='operation'&&$action_id=='index'):?> class="active"<?php endif;?>>
                                <a href="<?php echo Yii::app()->baseUrl;?>/user/operation">用户操作日志</a>
                            </li>
    						<?php endif;?>
                            <li class="nav-header">
                                <a href="<?php echo Yii::app()->baseUrl;?>/modpass/index">修改密码</a>
                            </li>
    						<li class="nav-header">
    							<a href="<?php echo Yii::app()->baseUrl;?>/site/logout">退出</a>
    						</li>
    					</ul>
    				</div>
    			    <!-- 菜单结束-->
    			    <!-- 内容开始 -->
    			    <div class="span10">
    			    <?php echo $content;?>
    			    <!-- 内容结束 -->
                    </div>
    			</div>
    		</div>
    	</div>
    </div>
</body>
</html>