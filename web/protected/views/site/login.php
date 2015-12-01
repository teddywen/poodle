<div class="container-fluid">
    <div class="row">
        <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><img src="<?php echo Yii::app()->params->image_url;?>/logo.png" class="logo"></div> -->
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <p><h1><?php echo Yii::app()->params->address_name;?> <small><?php echo Yii::app()->params->project_name;?></small></h1></p>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <p></p>
            <dl class="dl-horizontal pull-right">
                <dt>审核时间 :</dt><dd><mark>工作日 <?php echo Yii::app()->params->check_time;?></mark></dd>
                <dt>联系方式 :</dt><dd><mark>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Yii::app()->params->contact_phone;?></mark></dd>
            </dl>
        </div>
    </div>
</div>

<div class="jumbotron login-center-block">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-4">
                <h1>问题管理中心</h1>
                <p>欢迎使用堡镇问题管理中心</p>
                <p><a class="btn btn-primary btn-lg login-center-btn" href="#" role="button" data-toggle="modal" data-target="#loginModal">马上登录</a></p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row login-footer">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <p class="text-center">Powered by scw team<br/>Ver 1.0.0</p>
            </div>    
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">登录</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <form>
                            <div class="form-group">
                                <label for="username">用户名</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="用户名">
                            </div>
                            <div class="form-group">
                                <label for="password">密码</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="密码">
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" id="remember_me" name="remember_me" checked="true">下次直接登录</label>
                            </div>
                            <div class="form-group"><button id="submit" class="btn btn-primary">登录</button></div>
                            <p id="error" class="bg-warning text-danger login-error hidden"></p>
                        </form>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
$(function(){
    $("#submit").on("click", function() {
        $.ajax({
            url: "<?php echo Yii::app()->createUrl("site/dologin"); ?>", 
            type: "post", 
            dataType: "json", 
            data: {
                username: $("#username").val(), 
                password: $("#password").val(),
                remember_me: ($("#remember_me").is(':checked') ? "on" : "off")
            }, 
            success: function(ret) {
                if (ret.ok) {
                    $("#error").removeClass("show").addClass("hidden");
                    window.location.href = "<?php echo Yii::app()->createUrl("site/index"); ?>";
                } else {
                    $("#error").html("* " + ret.msg).removeClass("hidden").addClass("show");
                }
            }
        });
        return false;
    });
});
//-->
</script>