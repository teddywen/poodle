<?php if(Yii::app()->user->hasFlash($result_key)):?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>提示!</strong><?php echo Yii::app()->user->getFlash($result_key);?>
            </div>
        </div>
    </div>
<?php endif;?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    	<form class="form-horizontal validate" method="post">
    		<div class="form-group">
                <label for="cate_name" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">分类名: </label>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <input class="form-control" id="cate_name" name="cate_name" type="text" value="<?php echo $model->cate_name;?>" placeholder="分类名" required />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">状态: </label>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <label class="radio-inline">
                        <input type="radio" name="status" value="1" <?php if($model->status == 1) echo 'checked="checked"' ?> /> 启用
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status" value="0" <?php if($model->status == 0) echo 'checked="checked"';?> /> 禁用
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2 col-lg-10 col-md-10 col-sm-10 col-xs-10">
                    <button type="submit" class="btn btn-primary">确定</button>
                </div>
            </div>
    	</form>
    </div>
</div>