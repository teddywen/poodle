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
    					页标题范例 <small>此处编写页标题</small>
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
    						<li class="nav-header">
    							功能列表
    						</li>
    						<li>
    							<a href="#">资料</a>
    						</li>
    						<li>
    							<a href="#">设置</a>
    						</li>
    						<li class="divider">
    						</li>
    						<li>
    							<a href="#">帮助</a>
    						</li>
    					</ul>
    				</div>
    			    <!-- 菜单结束-->
    			    <!-- 内容开始 -->
    				<div class="span10">
    					<form class="form-search form-inline">
    						<input class="input-medium search-query" type="text" /> <button type="submit" class="btn">查找</button>
    					</form>
    					<table class="table table-condensed table-bordered table-hover">
    						<thead>
    							<tr>
    								<th>
    									编号
    								</th>
    								<th>
    									产品
    								</th>
    								<th>
    									交付时间
    								</th>
    								<th>
    									状态
    								</th>
    							</tr>
    						</thead>
    						<tbody>
    							<tr>
    								<td>
    									1
    								</td>
    								<td>
    									TB - Monthly
    								</td>
    								<td>
    									01/04/2012
    								</td>
    								<td>
    									Default
    								</td>
    							</tr>
    							<tr class="success">
    								<td>
    									1
    								</td>
    								<td>
    									TB - Monthly
    								</td>
    								<td>
    									01/04/2012
    								</td>
    								<td>
    									Approved
    								</td>
    							</tr>
    							<tr class="error">
    								<td>
    									2
    								</td>
    								<td>
    									TB - Monthly
    								</td>
    								<td>
    									02/04/2012
    								</td>
    								<td>
    									Declined
    								</td>
    							</tr>
    							<tr class="warning">
    								<td>
    									3
    								</td>
    								<td>
    									TB - Monthly
    								</td>
    								<td>
    									03/04/2012
    								</td>
    								<td>
    									Pending
    								</td>
    							</tr>
    							<tr class="info">
    								<td>
    									4
    								</td>
    								<td>
    									TB - Monthly
    								</td>
    								<td>
    									04/04/2012
    								</td>
    								<td>
    									Call in to confirm
    								</td>
    							</tr>
    						</tbody>
    					</table>
    					<div class="pagination pagination-large">
    						<ul>
    							<li>
    								<a href="#">上一页</a>
    							</li>
    							<li>
    								<a href="#">1</a>
    							</li>
    							<li>
    								<a href="#">2</a>
    							</li>
    							<li>
    								<a href="#">3</a>
    							</li>
    							<li>
    								<a href="#">4</a>
    							</li>
    							<li>
    								<a href="#">5</a>
    							</li>
    							<li>
    								<a href="#">下一页</a>
    							</li>
    						</ul>
    					</div>
    				</div>
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