<h3 class="text-left">发布新问题</h3>
<?php if(Yii::app()->user->hasFlash($result_key)):?>
<div class="alert alert-error">
	<h4>提示!</h4>
	<?php echo Yii::app()->user->getFlash($result_key);?>
</div>
<?php endif;?>
<form class="form-horizontal" method="post">
    <div class="imgname_lists" style="display: none;"></div>
	<div class="control-group">
        <label class="control-label" for="inputEmail">地址：</label>
		<div class="controls">
			<input class="input-xxlarge" name="addredd" type="text" required />
		</div>
	</div>
	<div class="control-group">
        <label class="control-label" for="inputPassword">问题描述：</label>
		<div class="controls">
			<textarea name="description" class="input-xxlarge" rows="5" style="resize: none;" maxlength="255" required></textarea>
		</div>
	</div>
	<div class="control-group">
        <label class="control-label" for="inputPassword">问题图片：</label>
		<div class="controls up_file_div">
			<input type="file" id="f_image" name="problem_image" />
            <label class="help-inline" style="color: red;">最大文件不能超过8M</label>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
            <ul class="unstyled inline img_lists">
            </ul>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			 <button type="submit" class="btn btn-info">发布</button>
		</div>
	</div>
</form>
<script type="text/javascript" src="<?php echo Yii::app()->params->js_url;?>/file/ajaxfileupload.js"></script>
<script type="text/javascript">
    $(function(){
        $(".up_file_div").on("change", "#f_image", function(){
            ajaxFileUpload();
        });
        $(".img_lists").on("click", ".remove_img", function(){
            if(confirm("确定删除该照片？")){
                removeImg(this);
            }
        });
    });
    //上传照片
    function ajaxFileUpload(){
        $.ajaxFileUpload({
            url: '<?php echo Yii::app()->baseUrl?>/image', //用于文件上传的服务器端请求地址
            secureuri: false, //是否需要安全协议，一般设置为false
            fileElementId: 'f_image', //文件上传域的ID
            dataType: 'json', //返回值类型 一般设置为json
            success: function (data, status){  //服务器成功响应处理函数
                var r_code = data.code;
                if(r_code == 0){
                    var r_msg = data.msg;
                    alert(r_msg);
                }
                else{
                    var img_info = data.img_info;
                    addNewImage(img_info);
                }
            },
            error: function (data, status, e){//服务器响应失败处理函数
            }
        });
        return false;
    }
    //展示图片缩略图
    function addNewImage(img_info){
        var r_img_path = img_info.img_path;
        var r_img_size = img_info.img_size;
        var img_radion = r_img_size.radio;
        var img_height = 190;
        var img_width = Math.ceil(img_height * img_radion);
        var img_str = '<img src="/upload/images/'+r_img_path+'" width="'+img_width+'px" height="'+img_height+'px" class="img-rounded" />'
        var li_str = '<li style="margin-bottom: 15px;" data-imgname="'+r_img_path+'">'
            + '<a class="close remove_img" style="color: red;" title="删除">×</a>'
            + img_str + '</i>';
        $(".imgname_lists").append('<input type="hidden" name="img_names[]" value="'+r_img_path+'" />');
        $(".img_lists").append(li_str);
    }
    //删除照片
    function removeImg(obj){
        var li_obj = $(obj).closest("li");
        var img_name = $(li_obj).attr("data-imgname");
        $(".imgname_lists").find("input[value='"+img_name+"']").remove();
        $(li_oj).remove();
    }
</script>