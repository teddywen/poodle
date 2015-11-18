<?php if(Yii::app()->user->hasFlash($result_key)):?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>提示!</strong><?php echo Yii::app()->user->getFlash($result_key);?>
            </div>
        </div>
    </div>
<?php endif;?>

<form class="form-horizontal" method="post">
    <div class="imgname_lists" style="display: none;"></div>
    <div class="form-group">
        <label for="address" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">地址: </label>
        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
            <input class="form-control" id="address" name="address" type="text" value="<?php echo isset($post_data['address'])?$post_data['address']:"";?>" required />
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">问题描述: </label>
        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
            <textarea class="form-control" id="description" name="description" rows="5" maxlength="255" required><?php echo isset($post_data['description']) ? CHtml::encode($post_data['description']) : "";?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="f_image" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">问题图片: </label>
        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 up_file_div">
            <input type="file" id="f_image" name="problem_image" />
            <label class="help-inline text-danger">最大文件不能超过12M</label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2 col-lg-10 col-md-10 col-sm-10 col-xs-10">
            <ul class="form-control-static list-unstyled list-inline hidden img_lists"></ul>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2 col-lg-10 col-md-10 col-sm-10 col-xs-10">
            <button type="submit" class="btn btn-primary">发布</button>
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
        var img_radion = r_img_size.radio, t_width = r_img_size.width, t_height = r_img_size.height;
        var img_height = 190;
        var img_width = Math.ceil(img_height * img_radion);
        var img_str = '<img src="/upload/images/'+r_img_path+'" width="'+img_width+'px" height="'+img_height+'px" class="img-rounded" />'
        var li_str = '<li style="margin-bottom: 15px;" data-imgname="'+r_img_path+'">'
            + '<a class="close remove_img" style="color: red;" title="删除">×</a>'
            + img_str + '</i>';
        $(".imgname_lists").append('<input type="hidden" name="img_names[]" value="'+r_img_path+","+t_width+","+t_height+'" />');
        if ($(".img_lists").hasClass("hidden")) {
            $(".img_lists").removeClass("hidden")
        }
        $(".img_lists").append(li_str);
    }
    //删除照片
    function removeImg(obj){
        var li_obj = $(obj).closest("li");
        var img_name = $(li_obj).attr("data-imgname");
        $(".imgname_lists").find("input[value='"+img_name+"']").remove();
        $(li_obj).remove();
        if ($(".img_lists").html()=="") {
            $(".img_lists").addClass("hidden");
        }
    }
</script>