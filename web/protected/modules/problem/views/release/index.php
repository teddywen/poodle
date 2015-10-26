<h3 class="text-left">发布新问题</h3>
<form class="form-horizontal">
	<div class="control-group">
        <label class="control-label" for="inputEmail">地址：</label>
		<div class="controls">
			<input class="input-xxlarge" name="addredd" type="text" />
		</div>
	</div>
	<div class="control-group">
        <label class="control-label" for="inputPassword">问题描述：</label>
		<div class="controls">
			<textarea name="description" class="input-xxlarge" rows="5" style="resize: none;" maxlength="255"></textarea>
		</div>
	</div>
	<div class="control-group">
        <label class="control-label" for="inputPassword">问题图片：</label>
		<div class="controls">
			<input type="file" id="f_image" name="problem_image" />
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			 <button type="submit" class="btn">登陆</button>
		</div>
	</div>
</form>
<script type="text/javascript" src="<?php echo Yii::app()->params->js_url;?>/file/ajaxfileupload.js"></script>
<script type="text/javascript">
    $(function(){
        $("#f_image").change(function(){
            ajaxFileUpload();
        });
    });
    function ajaxFileUpload(){
        $.ajaxFileUpload({
            url: '<?php echo Yii::app()->baseUrl?>/image', //用于文件上传的服务器端请求地址
            secureuri: false, //是否需要安全协议，一般设置为false
            fileElementId: 'f_image', //文件上传域的ID
            dataType: 'json', //返回值类型 一般设置为json
            success: function (data, status){  //服务器成功响应处理函数
                console.log(data);
            },
            error: function (data, status, e){//服务器响应失败处理函数
//                 alert(e);
            }
        });
        return false;
    }
</script>