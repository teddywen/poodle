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
</head>
<body>
    <div class="container-fluid">
    	<?php echo $content;?>

        <div class="row footer">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p class="text-center">Powered by scw team<br/>Ver 1.0.0</p>
            </div>            
        </div>
    </div>
</body>
</html>