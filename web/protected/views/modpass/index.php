<?php if($error != ""): ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>提示!</strong> <?php echo $error; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form class="form-horizontal" method="post">
            <div class="form-group">
                <label for="old_pass" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">原密码: </label>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <input class="form-control" id="old_pass" name="old_pass" type="password" value="<?php echo $old_pass;?>" placeholder="原密码" />
                </div>
            </div>
            <div class="form-group">
                <label for="new_pass" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">新密码: </label>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <input class="form-control" id="new_pass" name="new_pass" type="password" value="<?php echo $new_pass;?>" placeholder="新密码" />
                </div>
            </div>
            <div class="form-group">
                <label for="new_pass_confirm" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">新密码重复: </label>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <input class="form-control" id="new_pass_confirm" name="new_pass_confirm" type="password" value="<?php echo $new_pass_confirm;?>" placeholder="新密码重复" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2 col-lg-10 col-md-10 col-sm-10 col-xs-10">
                    <button type="submit" name="submit" class="btn btn-primary" value="1">确定</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if($ok): ?>
<script type="text/javascript">
(function() {
    alert("密码设置成功, 请重新登录。");
    window.location.href = "<?php echo $this->createUrl("/site/logout"); ?>";
})();
</script>
<?php endif; ?>