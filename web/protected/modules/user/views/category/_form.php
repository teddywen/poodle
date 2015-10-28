<?php if(Yii::app()->user->hasFlash($result_key)):?>
<div class="alert alert-error">
	<h4>提示!</h4>
	<?php echo Yii::app()->user->getFlash($result_key);?>
</div>
<?php endif;?>
<form class="form-horizontal validate" method="post">
	<div class="control-group">
		<label class="control-label" for="inputEmail">分类名：</label>
		<div class="controls">
			<input name="cate_name" type="text" value="<?php echo $model->cate_name;?>" required />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">是否有效：</label>
		<div class="controls">
			<label class="radio inline">
				<input type="radio" name="status" value="1" <?php if($model->status == 1) echo 'checked="checked"' ?> /> 有效
			</label>
			<label class="radio inline">
				<input type="radio" name="status" value="0" <?php if($model->status == 0) echo 'checked="checked"';?> /> 无效
			</label>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			 <button type="submit" class="btn btn-info">确定</button>
		</div>
	</div>
</form>