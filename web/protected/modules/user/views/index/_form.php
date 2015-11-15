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
        <form class="form-horizontal validate" method="post">
            <div class="form-group">
                <label for="username" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">用户名: </label>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <input class="form-control" id="username" name="username" type="text" value="<?php echo $model->username;?>" placeholder="用户名" required />
                </div>
            </div>
            <div class="form-group">
                <label for="gov_cate_id" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">单位分类: </label>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <select id="gov_cate_id" name="gov_cate_id" class="form-control">
                        <option value="0">未分类</option>
                        <?php if(!empty($cates)):?>
                            <?php foreach($cates as $cate):?>
                                <option value="<?php echo $cate->id;?>"<?php if($model->gov_cate_id==$cate->id):?> selected="selected"<?php endif;?>><?php echo $cate->cate_name;?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="u_type" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">用户类型: </label>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <select id="u_type" name="u_type" class="form-control" required>
                        <?php $u_types = Yii::app()->params['gov_user_type'];?>
                        <?php if(!empty($u_types)):?>
                            <?php foreach($u_types as $key=>$u_type):?>
                                <?php if($key > 2) continue;?>
                                <option value="<?php echo $key;?>" <?php if($model->u_type==$key):?> selected="selected"<?php endif;?>><?php echo $u_type;?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
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
                    <?php if($model->id > 0): ?>
                        <a href="javascript:void(0);" class="btn btn-danger btn_reset_pwd second-btn" data-uid="<?php echo $model->id;?>">重置密码</a>
                    <?php endif;?>
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