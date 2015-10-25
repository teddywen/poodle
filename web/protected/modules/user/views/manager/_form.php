<?php if(Yii::app()->user->hasFlash($result_key)):?>
<p class="lead text-error">
	<?php echo Yii::app()->user->getFlash($result_key);?>
</p>
<?php endif;?>
<form class="form-horizontal validate" method="post">
	<div class="control-group">
		<label class="control-label" for="inputEmail">用户名：</label>
		<div class="controls">
			<input name="username" type="text" value="<?php echo $model->username;?>" required />
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
			 <?php if($model->id > 0):?>
			 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			 <a href="javascript:void(0);" class="btn btn-danger btn_reset_pwd" data-uid="<?php echo $model->id;?>">重置密码</a>
			 <?php endif;?>
		</div>
	</div>
</form>
<script type="text/javascript">
    $(function(){
        $(".btn_reset_pwd").click(function(){
            if(confirm("确定重置该用户密码？")){
                var uid = $(this).attr("data-uid");
                resetUserPwd(uid);
            }
        });
    });
    function resetUserPwd(uid){
        $.ajax({
            url:"<?php echo Yii::app()->baseUrl;?>/user/manager/resetPwd",
            dataType:"json",
            data:{uid:uid},
            success:function(res){
                var r_msg = res.msg;
                alert(r_msg);
            }
        });
    }
</script>