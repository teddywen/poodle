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
		<label class="control-label" for="inputEmail">单位分类：</label>
		<div class="controls">
            <select name="gov_cate_id">
                <option value="0">未分类</option>
                <?php if(!empty($cates)):?>
                <?php foreach($cates as $cate):?>
                <option value="<?php echo $cate->id;?>"<?php if($model->gov_cate_id==$cate->id):?> selected="selected"<?php endif;?>><?php echo $cate->cate_name;?></option>
                <?php endforeach;?>
                <?php endif;?>
            </select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputEmail">用户类型：</label>
		<div class="controls">
            <select name="u_type" required>
                <?php $u_types = Yii::app()->params['gov_user_type'];?>
                <?php if(!empty($u_types)):?>
                <?php foreach($u_types as $key=>$u_type):?>
                <option value="<?php echo $key;?>" <?php if($model->u_type==$key):?> selected="selected"<?php endif;?>><?php echo $u_type;?></option>
                <?php endforeach;?>
                <?php endif;?>
            </select>
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
            url:"<?php echo Yii::app()->baseUrl;?>/user/index/resetPwd",
            dataType:"json",
            data:{uid:uid},
            success:function(res){
                var r_msg = res.msg;
                alert(r_msg);
            }
        });
    }
</script>