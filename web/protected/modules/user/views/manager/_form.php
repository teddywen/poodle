<?php if(Yii::app()->user->hasFlash($result_key)):?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <ul class="list-group">
                <li class="list-group-item list-group-item-danger"><strong>提示!</strong> <?php echo Yii::app()->user->getFlash($result_key);?></li>
            </ul>
        </div>
    </div>
<?php endif;?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form class="form-horizontal" method="post">
            <div class="form-group">
                <label for="username" class="col-lg-1 col-md-1 col-sm-1 col-xs-1 control-label">用户名: </label>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <input class="form-control" id="username" name="username" type="text" value="<?php echo $model->username;?>" placeholder="用户名" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-1 col-md-1 col-sm-1 col-xs-1 control-label">状态: </label>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <label class="radio-inline">
                        <input type="radio" name="status" value="1" <?php if($model->status == 1) echo 'checked="checked"' ?>> 启用
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status" value="0" <?php if($model->status == 0) echo 'checked="checked"' ?>> 禁用
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-1 col-md-offset-1 col-sm-offset-1 col-xs-offset-1 col-lg-11 col-md-11 col-sm-11 col-xs-11">
                    <button type="submit" class="btn btn-primary">确定</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="javascript:void(0);" class="btn btn-danger btn_reset_pwd" data-uid="<?php echo $model->id;?>">重置密码</a>
                </div>
            </div>
        </form>
    </div>
</div>
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