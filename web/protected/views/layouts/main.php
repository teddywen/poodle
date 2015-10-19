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
    			<ul class="nav nav-tabs">
    				<li class="active">
    					<a href="#">首页</a>
    				</li>
    				<li>
    					<a href="#">资料</a>
    				</li>
    				<li class="disabled">
    					<a href="#">信息</a>
    				</li>
    			</ul>
    			<!--content开始-->
    			<div class="page-header">
    				<h1>
    					页标题范例 <small>此处编写页标题</small>
    				</h1>
    			</div>
    			<form class="form-search">
    				<input class="input-medium search-query" type="text" /> <button type="submit" class="btn">查找</button>
    			</form>
    			<ul class="thumbnails">
    				<li class="span4">
    					<div class="thumbnail">
    						<img alt="300x200" src="img/people.jpg" />
    						<div class="caption">
    							<h3>
    								冯诺尔曼结构
    							</h3>
    							<p>
    								也称普林斯顿结构，是一种将程序指令存储器和数据存储器合并在一起的存储器结构。程序指令存储地址和数据存储地址指向同一个存储器的不同物理位置。
    							</p>
    							<p>
    								<a class="btn btn-primary" href="#">浏览</a> <a class="btn" href="#">分享</a>
    							</p>
    						</div>
    					</div>
    				</li>
    				<li class="span4">
    					<div class="thumbnail">
    						<img alt="300x200" src="img/city.jpg" />
    						<div class="caption">
    							<h3>
    								哈佛结构
    							</h3>
    							<p>
    								哈佛结构是一种将程序指令存储和数据存储分开的存储器结构，它的主要特点是将程序和数据存储在不同的存储空间中，进行独立编址。
    							</p>
    							<p>
    								<a class="btn btn-primary" href="#">浏览</a> <a class="btn" href="#">分享</a>
    							</p>
    						</div>
    					</div>
    				</li>
    				<li class="span4">
    					<div class="thumbnail">
    						<img alt="300x200" src="img/sports.jpg" />
    						<div class="caption">
    							<h3>
    								改进型哈佛结构
    							</h3>
    							<p>
    								改进型的哈佛结构具有一条独立的地址总线和一条独立的数据总线，两条总线由程序存储器和数据存储器分时复用，使结构更紧凑。
    							</p>
    							<p>
    								<a class="btn btn-primary" href="#">浏览</a> <a class="btn" href="#">分享</a>
    							</p>
    						</div>
    					</div>
    				</li>
    			</ul>
    			<div class="pagination">
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
    						<a href="#">6</a>
    					</li>
    					<li>
    						<a href="#">7</a>
    					</li>
    					<li>
    						<a href="#">8</a>
    					</li>
    					<li>
    						<a href="#">下一页</a>
    					</li>
    				</ul>
    			</div>
    			<script type="text/javascript">
    		    $(".pagination").bootstrapPaginator({currentPage: 4,totalPages: 10,numberOfPages:5});
    			</script>
    			<!--content结束-->
    		</div>
    	</div>
    </div>
</body>
</html>