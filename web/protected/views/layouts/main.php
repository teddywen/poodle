<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link href="<?php echo Yii::app()->params->css_url;?>/bootstrap/bootstrap-combined.min.css" rel="stylesheet">
	<script type="text/javascript" src="<?php echo Yii::app()->params->js_url;?>/jquery-1.11.3.min.js"></script>
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
    					<ul class="nav nav-list well">
    						<li class="nav-header">
    							列表标题
    						</li>
    						<li class="active">
    							<a href="#">首页</a>
    						</li>
    						<li>
    							<a href="#">库</a>
    						</li>
    						<li>
    							<a href="#">应用</a>
    						</li>
    						<li class="nav-header" style="font-">
    							用户管理
    						</li>
    						<li>
    							<a href="#">用户列表</a>
    						</li>
    						<li>
    							<a href="<?php echo Yii::app()->baseUrl?>/user/category">单位分类</a>
    						</li>
    					</ul>
    				</div>
    			    <!-- 菜单结束-->
    			    <!-- 内容开始 -->
    			    <?php echo $content;?>
    			    <!-- 内容结束 -->
    			</div>
    		</div>
    	</div>
    </div>
    <script type="text/javascript">
        $(function(){
		    $(".pagination").bootstrapPaginator({currentPage: 4,totalPages: 10,numberOfPages:10});
        });
    </script>
</body>
</html>