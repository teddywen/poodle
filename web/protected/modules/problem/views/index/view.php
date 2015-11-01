<?php
    $opetate_url = ''; $assign_control_disabled = 'disbled="disabled"';
    if(Yii::app()->user->checkAccess('admin') && in_array($problem->status, array(ProblemService::BE_CREATED, ProblemService::BE_BACKING))){
        $opetate_url = '/problem/problemFlow/assignDealUser';
        $assign_control_disabled = '';
    }
    if(Yii::app()->user->checkAccess('unit') && in_array($problem->status, array(ProblemService::BE_ASSIGNED))){
        $opetate_url = '/problem/problemFlow/acceptProblem';
    }
    if(in_array($problem->status, array(ProblemService::WAIT_CHECKING))){
        $opetate_url = '/problem/problemFlow/setSolveResult';
    }
?>
<h3 class="text-left">问题详情</h3>
<form class="form-horizontal problem_info_form" method="post" action="<?php echo $opetate_url;?>" onsubmit="return false;">
    <input type="hidden" name="pid" value="<?php echo $problem->id;?>" />
    <div class="imgname_lists" style="display: none;"></div>
	<div class="control-group">
        <label class="control-label" for="inputEmail">状态：</label>
		<div class="controls">
			<label class="control-label" style="text-align: left;"><?php echo ProblemService::$status[$problem->status];?></label>
		</div>
	</div>
	<?php if($problem->status == ProblemService::BE_BACKING):?>
	<div class="control-group">
        <label class="control-label" for="inputEmail">退单理由：</label>
		<div class="controls">
        <?php
            $problem_log_service = new ProblemLogService();
            $log_infos = $problem_log_service->getProblemStatusLog($problem->id, ProblemService::BE_BACKING);
            $back_info = end($log_infos);
		?>
			<label class="control-label" style="text-align: left; width: 100%;"><?php echo !empty($back_info)?$back_info->remark:"";?></label>
		</div>
	</div>
	<?php endif;?>
	<div class="control-group">
        <label class="control-label" for="inputEmail">地址：</label>
		<div class="controls">
			<input class="input-xxlarge" name="address" type="text" value="<?php echo $problem->address;?>" disabled="true" />
		</div>
	</div>
	<div class="control-group">
        <label class="control-label" for="inputPassword">问题描述：</label>
		<div class="controls">
			<textarea name="description" class="input-xxlarge" rows="5" style="resize: none;" maxlength="255" disabled="true"><?php echo $problem->description;?></textarea>
		</div>
	</div>
	<!-- 管理员对新创建和被退回的问题进行指派 -->
	<?php if(Yii::app()->user->checkAccess('admin')):?>
	<div class="control-group">
		<label class="control-label" for="inputEmail">指派单位：</label>
		<?php
            $cate_service = new CategoryService();
            $gov_cates = $cate_service->getAvailableCate();
            if($problem->status != ProblemService::BE_CREATED){
                $user_service = new UserService();
                $select_gov_users = $user_service->getUserByCate($problem->deal_cate_id);
            }
        ?>
		<div class="controls deal_user_container">
            <select id="gov_cate_id" class="input-xxlarge" <?php echo $assign_control_disabled;?> style="margin-bottom: 10px;">
                <option value="">未分类</option>
                <?php if(!empty($gov_cates)):?>
                <?php foreach($gov_cates as $gov_cate):?>
                <option value="<?php echo $gov_cate->id;?>"<?php if($gov_cate->id==$problem->deal_cate_id):?> selected="selected"<?php endif;?>><?php echo $gov_cate->cate_name;?></option>
                <?php endforeach;?>
                <?php endif;?>
            </select>
            <?php
                $gov_users_display = "display: none;";
                if(isset($select_gov_users) && !empty($select_gov_users)){
                    $gov_users_display = "";
                }
            ?>
            <select id="gov_users" name="deal_uid" class="input-xxlarge" <?php echo $assign_control_disabled;?> style="<?php echo $gov_users_display;?>">
            <?php if(empty($gov_users_display)):?>
            <?php foreach($select_gov_users as $select_gov_user):?>
            <option value="<?php echo $select_gov_user->id;?>"<?php if($select_gov_user->id==$problem->deal_uid):?> selected="selected"<?php endif;?>><?php echo $select_gov_user->username;?></option>
            <?php endforeach;?>
            <?php endif;?>
            </select>
		</div>
	</div>
	<div class="control-group set_deal_time" style="<?php echo $gov_users_display?>">
		<label class="control-label" for="inputEmail">完成时间：</label>
		<div class="controls deal_user_container">
            <?php
                $deal_month = floor($problem->deal_time / (30 * 24));
                $deal_day = ($problem->deal_time - ($deal_month * 30 * 24)) / 24;
            ?>
            <select id="deal_month" name="deal_month" <?php echo $assign_control_disabled;?> class="input-large">
                <?php for($i=0;$i<=12;$i++):?>
                <option value="<?php echo $i;?>"<?php if($i==$deal_month):?> selected="selected"<?php endif;?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;
            <select id="deal_day" name="deal_day" <?php echo $assign_control_disabled;?> class="input-large">
                <?php for($i=1;$i<=30;$i++):?>
                <option value="<?php echo $i;?>"<?php if($i==$deal_day):?> selected="selected"<?php endif;?>><?php echo $i;?></option>
                <?php endfor;?>
            </select>&nbsp;&nbsp;日
		</div>
	</div>
	<?php elseif($problem->status != ProblemService::BE_CREATED):?>
	<div class="control-group">
		<label class="control-label" for="inputEmail">指派单位：</label>
		<div class="controls">
            <label class="control-label" style="text-align: left;"><?php echo $problem->deal_username;?></label>
		</div>
	</div>
	<div class="control-group set_deal_time">
		<label class="control-label" for="inputEmail">完成用时：</label>
		<div class="controls deal_user_container">
            <?php
                $deal_month = floor($problem->deal_time / (30 * 24));
                $deal_day = ($problem->deal_time - ($deal_month * 30 * 24)) / 24;
            ?>
            <label class="control-label" style="text-align: left;"><?php echo $deal_month;?>&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $deal_day;?>&nbsp;&nbsp;日</label>
		</div>
	</div>
	<?php endif;?>
	<div class="control-group">
        <label class="control-label" for="inputPassword">问题图片：</label>
		<div class="controls">
            <ul class="unstyled inline img_lists">
                <?php foreach($problem_images as $problem_image):?>
                <?php
                    $img_path = $problem_image->img_path;
                    $img_radio = round($problem_image->img_width / $problem_image->img_height, 2);
                    $img_height = 190; $img_width = $img_height * $img_radio;
                ?>
                <li style="margin-bottom: 15px;"><img src="<?php echo $img_path;?>" width="<?php echo $img_width;?>" height="<?php echo $img_height;?>" class="img-rounded" /></li>
            <?php endforeach;?>
            </ul>
		</div>
	</div>
	<?php if(in_array($problem->status, array(ProblemService::WAIT_CHECKING, ProblemService::BE_QUALIFIED, ProblemService::BE_UNQUALIFIED, ProblemService::BE_CLOSED))):?>
	<div class="control-group">
        <label class="control-label" for="inputPassword">解决图片：</label>
		<div class="controls">
            <ul class="unstyled inline img_lists">
                <?php
                    $solve_images = $pimg_service->getImagesByPid($problem->id, 2);
                ?>
                <?php foreach($solve_images as $solve_image):?>
                <?php
                    $img_path = $solve_image->img_path;
                    $img_radio = round($solve_image->img_width / $solve_image->img_height, 2);
                    $img_height = 190; $img_width = $img_height * $img_radio;
                ?>
                <li style="margin-bottom: 15px;"><img src="<?php echo $img_path;?>" width="<?php echo $img_width;?>" height="<?php echo $img_height;?>" class="img-rounded" /></li>
            <?php endforeach;?>
            </ul>
		</div>
	</div>
	<?php endif;?>
	<?php if(!Yii::app()->user->checkAccess('finder')):?><!-- 是发布人员，本页面只能观看信息，不能再进行操作 -->
	<div class="control-group">
        <?php if(Yii::app()->user->checkAccess('admin')):?>
        <?php if(in_array($problem->status, array(ProblemService::BE_CREATED, ProblemService::BE_BACKING))):?>
		<div class="controls">
            <button type="button" class="btn btn-info btn_submit_form">分配</button>
		</div>
		<?php endif;?>
		<?php if(in_array($problem->status, array(ProblemService::WAIT_CHECKING))):?>
		<div class="controls">
            <button type="button" name="solve_qualified" class="btn btn-success btn_solve_qualified">通过</button>
            <button type="button" name="solve_unqualified" class="btn btn-danger btn_go_solve_unqualified">打回</button>
		</div>
		<?php endif;?>
		<?php endif;?>
        <?php if(Yii::app()->user->checkAccess('unit')):?>
		<div class="controls">
            <?php if(in_array($problem->status, array(ProblemService::BE_ASSIGNED))):?>
            <button type="button" class="btn btn-success btn_submit_form">接受</button>
            <button type="button" class="btn btn-danger btn_go_back_problem">退单</button>
            <?php endif;?>
            <?php if(in_array($problem->status, array(ProblemService::BE_DEALING, ProblemService::BE_UNQUALIFIED))):?>
            <a href="<?php ?>/problem/solve?pid=<?php echo $problem->id;?>" class="btn btn-primary">上传处理结果</a>
            <?php endif;?>
            <?php if(in_array($problem->status, array(ProblemService::BE_ASSIGNED, ProblemService::BE_DEALING))):?>
            <button type="button" class="btn btn-warning btn_submit_form">申请联动</button>
            <button type="button" class="btn btn-inverse btn_submit_form">申请延时</button>
            <?php endif;?>
		</div>
        <?php endif;?>
	</div>
	<?php endif;?>
</form>
<?php
    //退单form
    if(file_exists(dirname(__FILE__).'/_back_problem.php')){
        require_once(dirname(__FILE__).'/_back_problem.php');
    }
    //打回处理结果form
    if(file_exists(dirname(__FILE__).'/_solve_unqualified.php')){
        require_once(dirname(__FILE__).'/_solve_unqualified.php');
    }
?>
<script type="text/javascript" src="<?php echo Yii::app()->params->js_url;?>/file/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params->js_url;?>/problem.js"></script>
