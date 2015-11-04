<?php ?>
<?php
    $calendar_icon = Yii::app()->params->image_url.'/calendar.gif';
    $start_time = date('Y-m-01'); $end_time = date('Y-m-d');
    //创建时间
    $create_start_time = isset($_GET['create_start_time'])?$_GET['create_start_time']:$start_time;
    $create_end_time = isset($_GET['create_end_time'])?$_GET['create_end_time']:$end_time;
    //更新时间
    $update_start_time = isset($_GET['update_start_time'])?$_GET['update_start_time']:"";
    $update_end_time = isset($_GET['update_end_time'])?$_GET['update_end_time']:"";
    //指派单位
    if(Yii::app()->user->checkAccess('admin')){
        $cate_service = new CategoryService(); $user_service = new UserService();
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
<form class="form-search form-inline">
    <div style="margin-bottom: 10px;">
    	发布时间：
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
                    'dateFormat' => 'yy-mm-dd',
                ),
                'htmlOptions' => array(
                    'style' => 'width: 120px',
                ),
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
                    'dateFormat' => 'yy-mm-dd',
                ),
                'htmlOptions' => array(
                    'style' => 'width: 120px',
                ),
            ));
        ?>
        &nbsp;&nbsp;
                        状态：
        <select name="s_status" class="form-control input-medium">
            <option value="">全部</option>
            <?php $all_status = ProblemService::$status;?>
            <?php foreach($all_status as $key=>$status):?>
            <option value="<?php echo $key;?>"<?php if(strlen($s_status)>0&&$key==$s_status):?> selected="selected"<?php endif;?>><?php echo $status;?></option>
            <?php endforeach;?>
        </select>
        <?php if(Yii::app()->user->checkAccess('admin')):?>
        &nbsp;&nbsp;
                        发布人：
        <select name="s_release_uid" class="form-control input-medium">
            <option value="">全部</option>
            <?php if(!empty($release_users)):?>
            <?php foreach($release_users as $release_user):?>
            <option value="<?php echo $release_user->id;?>"<?php if($s_release_uid==$release_user->id):?> selected="selected"<?php endif;?>><?php echo $release_user->username;?></option>
            <?php endforeach;?>
            <?php endif;?>
        </select>
        <?php endif;?>
    </div>
    <div style="margin-bottom: 10px;">
    	更新时间：
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
                    'dateFormat' => 'yy-mm-dd',
                ),
                'htmlOptions' => array(
                    'style' => 'width: 120px',
                ),
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
                    'dateFormat' => 'yy-mm-dd',
                ),
                'htmlOptions' => array(
                    'style' => 'width: 120px',
                ),
            ));
        ?>
        <?php if(Yii::app()->user->checkAccess('admin')):?>
        &nbsp;&nbsp;
                        指派给：
    	<select id="gov_cate_id" name="s_gov_cate_id" class="form-control input-medium">
            <option value="">全部</option>
            <?php if(!empty($gov_cates)):?>
            <?php foreach($gov_cates as $gov_cate):?>
            <option value="<?php echo $gov_cate->id;?>"<?php if($s_gov_cate_id==$gov_cate->id):?> selected="selected"<?php endif;?>><?php echo $gov_cate->cate_name;?></option>
            <?php endforeach;?>
            <?php endif;?>
        </select>
        <select id="gov_users" name="s_deal_uid" class="form-control input-medium" style="<?php echo $gov_users_display;?>">
            <?php if(empty($gov_users_display)):?>
            <?php foreach($select_gov_users as $select_gov_user):?>
            <option value="<?php echo $select_gov_user->id;?>"<?php if($select_gov_user->id==$s_deal_uid):?> selected="selected"<?php endif;?>><?php echo $select_gov_user->username;?></option>
            <?php endforeach;?>
            <?php endif;?>
        </select>
        <?php endif;?>
    </div>
    <div style="margin-bottom: 10px;">
                        延时：
        <select name="s_delay" class="form-control input-medium">
            <option value="">全部</option>
            <option value="1"<?php if($s_delay===1):?> selected="selected"<?php endif;?>>是</option>
            <option value="0"<?php if($s_delay===0):?> selected="selected"<?php endif;?>>否</option>
        </select>
        &nbsp;&nbsp;
                        联动：
        <select name="s_assisted" class="form-control input-medium">
            <option value="">全部</option>
            <option value="1"<?php if($s_assisted===1):?> selected="selected"<?php endif;?>>是</option>
            <option value="0"<?php if($s_assisted===0):?> selected="selected"<?php endif;?>>否</option>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    	<button type="submit" class="btn btn-primary">查找</button>
    	<?php if(Yii::app()->user->checkAccess('admin')):?>
        <button type="submit" class="btn btn-success" name="explode_csv">导出订单</button>
        <?php endif;?>
    </div>
</form>
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
    	                console.log(res);console.log(res.length);
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