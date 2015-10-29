<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><img src="<?php echo Yii::app()->params->image_url;?>/logo.jpg" class="img-rounded logo"></div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <p></p>
        <p class="text-right">审核时间: 工作日10:00 ~ 15:00</p>
        <p class="text-right">TEL: 012-6229-3361</p>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="site-center-block center-block">
            <h2 class="text-center">问题管理中心</h2>
            <p class="lead text-center">敌军还有三十秒到达战场 碾碎他们...</p>
            <p class="lead text-center">项目不完成我习主席就倒过来念!!!</p>
            <div class="text-center"><button type="button" class="site-center-block-btn btn btn-info btn-lg" data-toggle="modal" data-target="#loginModal">马上登录</button></div>
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
                                <label><input type="checkbox" id="remember_me" name="remember_me">下次直接登录</label>
                            </div>
                            <div class="form-group"><button id="submit" class="btn btn-info">登录</button></div>
                            <p id="error" class="bg-warning site-error-padding hidden"></p>
                        </form>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"></div>
                </div>
            </div>
            <!--
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>-->
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
                remember_me: $("#remember_me").val()
            }, 
            success: function(ret) {
                if (ret.ok) {
                    $("#error").removeClass("show").addClass("hidden");
                    alert(ret.msg);
                    window.location.href = "<?php echo Yii::app()->createUrl("site/index"); ?>";
                } else {
                    $("#error").html(ret.msg).removeClass("hidden").addClass("show");
                }
            }
        });
        return false;
    });
});
//-->
</script>