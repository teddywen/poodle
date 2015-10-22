<p>默认密码为123456</p>
<form>
    <label>用户名</label>
    <input name="username" type="text" value="<?php echo $username; ?>" />
    <label>密码</label>
    <input name="password" type="password" value="<?php echo $password; ?>" />
    <label></label>
    <input name="login" type="submit" value="登陆">
    <?php if ($error != ""): ?>
        <p style="color:red"><?php echo $error; ?></p>
    <?php endif; ?>
</form>