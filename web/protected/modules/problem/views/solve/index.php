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
<?php $modify_solve = isset($_GET['modify_solve'])?intval($_GET['modify_solve']):0;?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form class="form-horizontal" method="post">
            <input type="hidden" name="modify_solve" value="<?php echo $modify_solve;?>" />
            <div class="form-group">
                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">地址: </label>
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                    <p class="form-control-static"><?php echo $problem->address;?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">问题描述: </label>
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                    <p class="form-control-static"><?php echo $problem->description;?></p>
                </div>
            </div>
            <!-- 
            <div class="form-group">
                <label for="f_image" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">凭证图片: </label>
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 up_file_div">
                    <input type="file" id="f_image" name="problem_image" />
                    <label class="help-inline text-danger">最大文件不能超过<?php echo Yii::app()->params->max_upload_image_size;?>M</label>
                </div>
            </div> -->
            <div class="form-group">
                <label for="f_image" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">问题图片: </label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 up_file_div">
                    <div id="pick">选择文件</div>
                    <label class="help-inline text-danger">最大文件不能超过<?php echo Yii::app()->params->max_upload_image_size;?>M</label>
                    <div class="upload_info text-info"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2 col-lg-10 col-md-10 col-sm-10 col-xs-10">
                    <?php
                        $has_img = 0;
                        if($modify_solve == 1 && !empty($solve_images)){
                            $has_img = 1;
                        }
                    ?>
                    <ul class="form-control-static list-unstyled list-inline img_lists <?php if(!$has_img):?>hidden<?php endif;?>">
                    <?php if($modify_solve == 1 && !empty($solve_images)):?>
                        <?php foreach($solve_images as $solve_image):?>
                        <?php
                            $img_path = substr($solve_image->img_path, strlen(Yii::app()->params->upload_img_url));
                            $img_width = $solve_image->img_width; $img_height = $solve_image->img_height;
                            $show_height = 190; $show_width = ceil($show_height * round($img_width/ $img_height, 2));
                            $form_img_value = $img_path.','.$img_width.','.$img_height;
                            $cur_img_lists[] = '<input type="hidden" name="img_names[]" value="'.$form_img_value.'">';
                        ?>
                        <input type="hidden" name="cur_images[]" value="<?php echo $form_img_value;?>" />
                        <li style="margin-bottom: 15px;" data-imgname="<?php echo $img_path;?>" data-width="<?php echo $img_width;?>" data-height="<?php echo $img_height;?>">
                        <a class="close remove_img" style="color: red;" title="删除">×</a><img src="<?php echo $solve_image->img_path;?>" width="<?php echo $show_width;?>" height="<?php echo $show_height;?>" class="img-rounded"></li>
                        <?php endforeach;?>
                    <?php endif;?>
                    </ul>
                </div>
            </div>
            <div class="imgname_lists" style="display: none;">
            <?php if(isset($cur_img_lists)&&!empty($cur_img_lists)) echo implode('', $cur_img_lists);?>
            </div>
            <?php if($problem->deal_uid == Yii::app()->user->id):?>
            <div class="form-group">
                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2 col-lg-10 col-md-10 col-sm-10 col-xs-10">
                    <button type="submit" class="btn btn-primary">提交审核</button>
                </div>
            </div>
            <?php endif;?>
        </form>
    </div>
</div>

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

        var uploader = WebUploader.create({
            // 验证单个文件大小是否超出限制, 超出则不允许加入队列。
            fileSingleSizeLimit: <?php echo Yii::app()->params->max_upload_image_size * 1024 * 1024;?>, 
            // 指定Drag And Drop拖拽的容器，如果不指定，则不启动。
            // dnd: '#pick', 
            // 去重， 根据文件名字、文件大小和最后修改时间来生成hash Key
            duplicate: true, 
            // 选完文件后，是否自动上传。
            auto: true,
            // swf文件路径
            swf: '<?php echo Yii::app()->params->plugin_url;?>/webuploader-0.1.5/Uploader.swf',
            // 文件接收服务端。
            server: '<?php echo Yii::app()->baseUrl?>/image',
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#pick',
            // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
            // compress: false, 
            // 只允许选择图片文件。
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });
        // 当有文件被添加进队列的时候
        var pickText = '';
        uploader.on( 'fileQueued', function( file ) {
            pickText = $("#pick .webuploader-pick").html();
            $(".upload_info").append('<div id="' + file.id + '" class="item">' + 
                '<h4 class="info">' + file.name + '</h4>' + 
                '<p class="state">等待上传...</p>' + '</div>');
        });
        // 文件上传过程中创建进度条实时显示。
        uploader.on( 'uploadProgress', function( file, percentage ) {
            var $li = $( '#'+file.id ),
                $percent = $li.find('.progress .progress-bar');

            // 避免重复创建
            if ( !$percent.length ) {
                $percent = $('<div class="progress progress-striped active">' +
                  '<div class="progress-bar" role="progressbar" style="width: 0%">' +
                  '</div>' +
                '</div>').appendTo( $li ).find('.progress-bar');
            }

            $li.find('p.state').text('上传中');

            $percent.css( 'width', percentage * 100 + '%' );
        });
        uploader.on( 'uploadSuccess', function( file, response ) {
            $( '#'+file.id ).find('p.state').text('已上传');
            var r_code = response.code;
            if(r_code == 0){
                var r_msg = response.msg;
                alert(r_msg);
            }
            else{
                var img_info = response.img_info;
                addNewImage(img_info);
            }
        });
        uploader.on('uploadComplete', function( file ) {
            $( '#'+file.id ).fadeOut();
        });
        uploader.on( 'uploadError', function( file, reason ) {
            $( '#'+file.id ).find('p.state').text('上传出错');
            console.log(file);
            console.log(reason);
        });
        uploader.on( 'error', function( type ) {
            console.log(type);
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
        var img_str = '<img src="/upload/images/'+r_img_path+'" width="'+img_width+'" height="'+img_height+'" class="img-rounded" />'
        var li_str = '<li style="margin-bottom: 15px;" data-imgname="'+r_img_path+'">'
            + '<a class="close remove_img" style="color: red;" title="删除">×</a>'
            + img_str + '</i>';
        $(".imgname_lists").append('<input type="hidden" name="img_names[]" value="'+r_img_path+","+t_width+","+t_height+'" />');
        if ($(".img_lists").hasClass("hidden")) {
            $(".img_lists").removeClass("hidden");
        }
        $(".img_lists").append(li_str);
    }
    //删除照片
    function removeImg(obj){
        var li_obj = $(obj).closest("li");
        var img_name = $(li_obj).attr("data-imgname");
        var img_width = $(li_obj).data("width");
        var img_height = $(li_obj).data("height");
        $(".imgname_lists").find("input[value='"+img_name+","+img_width+","+img_height+"']").remove();
        $(li_obj).remove();
        if ($(".img_lists").html()=="") {
            $(".img_lists").addClass("hidden");
        }
    }
</script>