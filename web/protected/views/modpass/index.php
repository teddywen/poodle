<form class="form-horizontal validate">
    <div class="control-group">
    	<label class="control-label" for="old_pass">原密码：</label>
    	<div class="controls">
    		<input id="old_pass" name="old_pass" type="password" value="<?php echo $old_pass; ?>" required />
    	</div>
    </div>
    <div class="control-group">
    	<label class="control-label" for="new_pass">新密码：</label>
    	<div class="controls">
    		<input id="new_pass" name="new_pass" type="password" value="<?php echo $new_pass; ?>" required />
    	</div>
    </div>
    <div class="control-group">
    	<label class="control-label" for="new_pass_confirm">新密码重复：</label>
    	<div class="controls">
    		<input id="new_pass_confirm" name="new_pass_confirm" type="password" value="<?php echo $new_pass_confirm; ?>" required />
    	</div>
    </div>
	<div class="control-group">
		<div class="controls">
			 <button type="submit" name="submit" value="1" class="btn btn-info">确定</button> 
		</div>
	</div>
</form>
<?php if($error != ""): ?>
    <p style="color:red"><?php echo $error; ?></p>
<?php endif; ?>
<?php if($ok): ?>
<script type="text/javascript">
(function() {
    alert("密码设置成功, 请重新登录。");
    window.location.href = "<?php echo $this->createUrl("/site/logout"); ?>";
})();
</script>
<?php endif; ?>