<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><img src="<?php echo Yii::app()->params->image_url;?>/logo.jpg" class="logo"></div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"></div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="site-center-block center-block">
            <h2 class="text-center">问题管理中心</h2>
            <p class="lead text-center">敌军还有三十秒到达战场 碾碎他们...</p>
            <div class="text-center"><button type="button" class="site-center-block-btn btn btn-info btn-lg">马上登录</button></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
    </div>
</div>
