<div class="span10">
    <form class="form-search">
        <div class="form-group">
            <label for="old_pass">原密码</label>
            <input type="password" id="old_pass" name="old_pass" class="form-control input-medium search-query" value="<?php echo $old_pass; ?>" />
        </div>
        <div class="form-group">
            <label for="new_pass">新密码</label>
            <input type="password" id="new_pass" name="new_pass" class="form-control input-medium search-query" value="<?php echo $new_pass; ?>" />
        </div>
        <div class="form-group">
            <label for="new_pass_confirm">新密码重复</label>
            <input type="password" id="new_pass_confirm" name="new_pass_confirm" class="form-control input-medium search-query" value="<?php echo $new_pass_confirm; ?>" />
        </div>
        <input type="submit" name="submit" class="btn btn-primary" value="确认" />
    </form>
    <?php if($error != ""): ?>
        <p style="color:red"><?php echo $error; ?></p>
    <?php endif; ?>
</div>
<?php if($ok): ?>
<script type="text/javascript">
(function() {
    alert("密码设置成功, 请重新登录。");
    window.location.href = "<?php echo $this->createUrl("/site/index"); ?>";
})();
</script>
<?php endif; ?>