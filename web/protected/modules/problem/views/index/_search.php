<?php
    $nav_status = isset($_GET['nav_status'])?intval($_GET['nav_status']):0;
    //关键字
    $s_keyword = isset($_GET['s_keyword'])?$_GET['s_keyword']:'';
    $calendar_icon = Yii::app()->params->image_url.'/calendar.gif';
    $start_time = date('Y-m-01'); $end_time = date('Y-m-d');
    //创建时间
    $create_start_time = isset($_GET['create_start_time'])?$_GET['create_start_time']:$start_time;
    $create_end_time = isset($_GET['create_end_time'])?$_GET['create_end_time']:$end_time;
    //更新时间
    $update_start_time = isset($_GET['update_start_time'])?$_GET['update_start_time']:"";
    $update_end_time = isset($_GET['update_end_time'])?$_GET['update_end_time']:"";
    //指派单位
    $user_service = new UserService();
    if(Yii::app()->user->checkAccess('admin')){
        $cate_service = new CategoryService();
        $gov_cates = $cate_service->getAvailableCate();
        $s_gov_cate_id = isset($_REQUEST['s_gov_cate_id'])&&strlen($_REQUEST['s_gov_cate_id'])>0?intval($_REQUEST['s_gov_cate_id']):"";
        $s_deal_uid = isset($_REQUEST['s_deal_uid'])&&strlen($_REQUEST['s_deal_uid'])>0?intval($_REQUEST['s_deal_uid']):"";
        $select_gov_users = array();
        if(!empty($s_gov_cate_id)){
            $select_gov_users = $user_service->getUserByCate($s_gov_cate_id);
        }
        $gov_users_display = "display: none";
        if(!empty($select_gov_users)){
            $gov_users_display = "";
        }
    }
    //状态：
    $s_status = isset($_REQUEST['s_status'])&&strlen($_REQUEST['s_status'])>0?intval($_REQUEST['s_status']):"";
    //发布人
    $release_users = $user_service->getUserByType(1);
    $s_release_uid = isset($_REQUEST['s_release_uid'])&&strlen($_REQUEST['s_release_uid'])>0?intval($_REQUEST['s_release_uid']):"";
    //延时和联动
    $s_delay = isset($_REQUEST['s_delay'])&&strlen($_REQUEST['s_delay'])>0?intval($_REQUEST['s_delay']):"";
    $s_assisted = isset($_REQUEST['s_assisted'])&&strlen($_REQUEST['s_assisted'])>0?intval($_REQUEST['s_assisted']):"";
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">搜索</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal">
                    <?php if($nav_status): ?>
                        <input type="hidden" name="nav_status" value="<?php echo $nav_status; ?>" />
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="s_keyword" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">关键字: </label>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <input type="text" id="s_keyword" name="s_keyword" value="<?php echo $s_keyword;?>" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">发布时间: </label>
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                    <div class="form-control-static">
                                        <?php
                                            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                                                'attribute' => 'visit_time',
                                                'language' => 'zh_cn',
                                                'name' => 'create_start_time',
                                                'value' => $create_start_time,
                                                'options' => array(
                                                    'showOn' => 'both',
                                                    'buttonImage' => $calendar_icon,
                                                    'buttonImageOnly' => true,
                                                    'maxDate' => 'new Date()',
                                                    'dateFormat' => 'yy-mm-dd'
                                                ),
                                                'htmlOptions' => array(
                                                    'style' => 'width: 120px'
                                                )
                                            ));
                                        ?>
                                        -
                                        <?php
                                            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                                                'attribute' => 'visit_time',
                                                'language' => 'zh_cn',
                                                'name' => 'create_end_time',
                                                'value' => $create_end_time,
                                                'options' => array(
                                                    'showOn' => 'both',
                                                    'buttonImage' => $calendar_icon,
                                                    'buttonImageOnly' => true,
                                                    'maxDate' => 'new Date()',
                                                    'dateFormat' => 'yy-mm-dd'
                                                ),
                                                'htmlOptions' => array(
                                                    'style' => 'width: 120px'
                                                )
                                            ));
                                        ?>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">更新时间: </label>
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                    <div class="form-control-static">
                                        <?php
                                            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                                                'attribute' => 'visit_time',
                                                'language' => 'zh_cn',
                                                'name' => 'update_start_time',
                                                'value' => $update_start_time,
                                                'options' => array(
                                                    'showOn' => 'both',
                                                    'buttonImage' => $calendar_icon,
                                                    'buttonImageOnly' => true,
                                                    'maxDate' => 'new Date()',
                                                    'dateFormat' => 'yy-mm-dd'
                                                ),
                                                'htmlOptions' => array(
                                                    'style' => 'width: 120px'
                                                )
                                            ));
                                        ?>
                                        -
                                        <?php
                                            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                                                'attribute' => 'visit_time',
                                                'language' => 'zh_cn',
                                                'name' => 'update_end_time',
                                                'value' => $update_end_time,
                                                'options' => array(
                                                    'showOn' => 'both',
                                                    'buttonImage' => $calendar_icon,
                                                    'buttonImageOnly' => true,
                                                    'maxDate' => 'new Date()',
                                                    'dateFormat' => 'yy-mm-dd'
                                                ),
                                                'htmlOptions' => array(
                                                    'style' => 'width: 120px'
                                                )
                                            ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(Yii::app()->user->checkAccess('admin')): ?>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <label for="s_release_uid" class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label">发布人: </label>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <select name="s_release_uid" class="form-control">
                                            <option value="">全部</option>
                                            <?php if(!empty($release_users)):?>
                                                <?php foreach($release_users as $release_user):?>
                                                    <option value="<?php echo $release_user->id;?>"<?php if($s_release_uid==$release_user->id):?> selected="selected"<?php endif;?>><?php echo $release_user->username;?></option>
                                                <?php endforeach;?>
                                            <?php endif;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <label for="gov_cate_id" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">指派给: </label>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <select id="gov_cate_id" name="s_gov_cate_id" class="form-control">
                                            <option value="">全部</option>
                                            <?php if(!empty($gov_cates)):?>
                                                <?php foreach($gov_cates as $gov_cate):?>
                                                    <option value="<?php echo $gov_cate->id;?>"<?php if($s_gov_cate_id==$gov_cate->id):?> selected="selected"<?php endif;?>><?php echo $gov_cate->cate_name;?></option>
                                                <?php endforeach;?>
                                            <?php endif;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <div class="form-group">
                                <label for="s_delay" class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label">延时: </label>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <select name="s_delay" class="form-control">
                                        <option value="">全部</option>
                                        <option value="1"<?php if($s_delay===1):?> selected="selected"<?php endif;?>>是</option>
                                        <option value="0"<?php if($s_delay===0):?> selected="selected"<?php endif;?>>否</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <div class="form-group">
                                <label for="s_assisted" class="col-lg-4 col-md-4 col-sm-4 col-xs-4 control-label">联动: </label>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <select name="s_assisted" class="form-control">
                                        <option value="">全部</option>
                                        <option value="1"<?php if($s_assisted===1):?> selected="selected"<?php endif;?>>是</option>
                                        <option value="0"<?php if($s_assisted===0):?> selected="selected"<?php endif;?>>否</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if($nav_status == 999):?>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <div class="form-group">
                                <label for="s_status" class="col-lg-6 col-md-6 col-sm-6 col-xs-6 control-label">状态: </label>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <select name="s_status" class="form-control">
                                        <option value="">全部</option>
                                        <?php $all_status = ProblemService::$status;?>
                                        <?php foreach($all_status as $key=>$status):?>
                                            <option value="<?php echo $key;?>"<?php if(strlen($s_status)>0&&$key==$s_status):?> selected="selected"<?php endif;?>><?php echo $status;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2 col-lg-10 col-md-10 col-sm-10 col-xs-10">
                            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> 查找</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
    	//联动选择单位
    	$("#gov_cate_id").change(function(){
    	    var cate_id = $(this).val();
    	    if(cate_id.length > 0){
    	    	$.ajax({
    	            url:"/user/index/getCateUsers",
    	            dataType:"JSON",
    	            data:{cate_id:cate_id},
    	            success:function(res){
    	                // console.log(res);console.log(res.length);
    	                if(res.length > 0){
    	                	var u_length = res.length, option_users = '';
    	                    for(var i=0;i<u_length;i++){
    	                    	option_users += '<option value="'+res[i].uid+'">'+res[i].username+'</option>';
    	                    }
    	                    $("#gov_users").append(option_users);
    	                    $("#gov_users").show();
    	                }
    	                else{
    	                	$("#gov_users").hide();
    	                    $(".set_deal_time").hide();
    	                    alert("该分类下暂无用户");
    	                }
    	            }
    	        });
    	    }
    	    else{
    	        $("#gov_users").hide();
    	    }
    	});
    });

    
</script>