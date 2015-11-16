<?php
    $opetate_url = ''; $assign_control_disabled = 'disabled="disabled"';
    if(Yii::app()->user->checkAccess('finder') && $problem->status == ProblemService::BE_CREATED){
        $opetate_url = '/problem/problemFlow/cancelProblem';
    }
    if(Yii::app()->user->checkAccess('admin') && in_array($problem->status, array(ProblemService::BE_CREATED, ProblemService::BE_BACKING, ProblemService::APPLY_DELAYING, ProblemService::APPLY_ASSISTING))){
        $opetate_url = '/problem/problemFlow/assignDealUser';
        $assign_control_disabled = '';
    }
    if(Yii::app()->user->checkAccess('unit') && in_array($problem->status, array(ProblemService::BE_ASSIGNED))){
        $opetate_url = '/problem/problemFlow/acceptProblem';
    }
    if(in_array($problem->status, array(ProblemService::WAIT_CHECKING))){
        $opetate_url = '/problem/problemFlow/setSolveResult';
    }
    $user_service = new UserService();
    $problem_log_service = new ProblemLogService();
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form class="form-horizontal problem_info_form" method="post" action="<?php echo $opetate_url;?>" onsubmit="return false;">
            <input type="hidden" name="pid" value="<?php echo $problem->id;?>" />
            <div class="imgname_lists" style="display: none;"></div>
            <div class="form-group">
                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">状态: </label>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <p class="form-control-static"><?php echo ProblemService::$status[$problem->status];?></p>
                </div>
            </div>
            <?php if($problem->status == ProblemService::BE_BACKING):?>
                <div class="form-group">
                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">退单理由: </label>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <p class="form-control-static"><?php echo !empty($back_info)?$back_info->remark:"";?></p>
                    </div>
                </div>
            <?php endif;?>
            <?php if($problem->status == ProblemService::BE_UNQUALIFIED):?>
                <?php $unqualified_infos = $problem_log_service->getProblemStatusLog($problem->id, ProblemService::BE_UNQUALIFIED); ?>
                <?php $unqualified_info = end($unqualified_infos); ?>
                <div class="form-group">
                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">打回理由: </label>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <p class="form-control-static"><?php echo !empty($unqualified_info)?$unqualified_info->remark:"";?></p>
                    </div>
                </div>
            <?php endif;?>
            <div class="form-group">
                <label for="address" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">地址: </label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <input class="form-control" id="address" name="address" type="text" value="<?php echo $problem->address;?>" disabled="true" />
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">问题描述: </label>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <textarea id="description" name="description" class="form-control" rows="5" maxlength="255" disabled="true"><?php echo CHtml::encode($problem->description);?></textarea>
                </div>
            </div>

            <!-- 管理员对新创建和被退回的问题进行指派 -->
            <?php if(Yii::app()->user->checkAccess('admin')):?>
                <?php
                    $cate_service = new CategoryService();
                    $gov_cates = $cate_service->getAvailableCate();
                    if($problem->status != ProblemService::BE_CREATED){
                        $select_gov_users = $user_service->getUserByCate($problem->deal_cate_id);
                    }
                ?>
                <div class="form-group">
                    <label for="gov_cate_id" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">指派单位: </label>
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                        <select id="gov_cate_id" name="gov_cate_id" class="form-control" <?php echo $assign_control_disabled;?>>
                            <option value="">请选择</option>
                            <?php if(!empty($gov_cates)):?>
                                <?php foreach($gov_cates as $gov_cate):?>
                                    <option value="<?php echo $gov_cate->id;?>"<?php if($gov_cate->id==$problem->deal_cate_id):?> selected="selected"<?php endif;?>><?php echo $gov_cate->cate_name;?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                    </div>
                </div>
                <?php
                    $gov_users_display = "display: none;";
                    if(isset($select_gov_users) && !empty($select_gov_users)){
                        $gov_users_display = "";
                    }
                ?>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2 col-lg-7 col-md-7 col-sm-7 col-xs-7">
                        <select id="gov_users" name="deal_uid" class="form-control" <?php echo $assign_control_disabled;?> style="<?php echo $gov_users_display;?>">
                        <?php if(empty($gov_users_display)):?>
                        <?php foreach($select_gov_users as $select_gov_user):?>
                        <option value="<?php echo $select_gov_user->id;?>"<?php if($select_gov_user->id==$problem->deal_uid):?> selected="selected"<?php endif;?>><?php echo $select_gov_user->username;?></option>
                        <?php endforeach;?>
                        <?php endif;?>
                        </select>
                    </div>
                </div>
                <div class="form-group set_deal_time" style="<?php echo $gov_users_display;?>">
                    <label for="deal_day" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">完成时间: </label>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <?php $deal_day = floor($problem->deal_time / 24); ?>
                        <select id="deal_day" name="deal_day" <?php echo $assign_control_disabled;?> class="form-control">
                            <?php for($i=1;$i<=30;$i++):?>
                                <option value="<?php echo $i;?>"<?php if($i==$deal_day):?> selected="selected"<?php endif;?>><?php echo $i;?>天</option>
                            <?php endfor;?>
                        </select>
                    </div>
                </div>
            <?php elseif(!in_array($problem->status, array(ProblemService::BE_CREATED, ProblemService::BE_CANCELED))):?>
                <div class="form-group">
                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">指派单位: </label>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <p class="form-control-static"><?php echo $problem->deal_username;?></p>
                    </div>
                </div>
                <div class="form-group set_deal_time">
                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">完成用时: </label>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <p class="form-control-static"><?php $deal_day;?>天</p>
                    </div>
                </div>
            <?php endif;?>

            <?php if($problem->status == ProblemService::APPLY_DELAYING):?>
                <?php $delayapply_logs = $problem_log_service->getProblemStatusLog($problem->id, ProblemService::APPLY_DELAYING); ?>
                <?php $delayapply_log = end($delayapply_logs); ?>
                <?php $delayapply_log_data = CJSON::decode($delayapply_log->data); ?>
                <?php $delay_day = floor($delayapply_log_data->hour / 24); ?>
                <div class="form-group">
                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">申请延时: </label>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <p class="form-control-static"><?php echo $delay_day;?>天</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">延时理由: </label>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <p class="form-control-static"><?php echo empty($delayapply_log) ? "" : $delayapply_log->remark;?></p>
                    </div>
                </div>
            <?php endif;?>

            <!-- 
            <?php if($problem->is_assistant != 0): ?>
                <?php $assistedapply_logs = $problem_log_service->getProblemStatusLog($problem->id, ProblemService::APPLY_ASSISTING); ?>
                <?php $assistedapply_log = end($assistedapply_logs); ?>
                <div class="control-group">
                    <label class="control-label" for="inputPassword">申请联动：</label>
                    <div class="controls">
                        <?php
                            $unit_uids = json_decode($problem->assist_unit, true);
                            $unit_users = $user_service->getGovUserByIds($unit_uids);
                            $users_str = '';
                            foreach($unit_users as $unit_user){
                                $users_str .= ','.$unit_user->username;
                            }
                            if(!empty($users_str)){
                                $users_str = substr($users_str, 1);
                            }
                        ?>
                        <label class="control-label" style="text-align: left;width: 100%;"><?php echo $users_str;?></label>
                        <?php if(Yii::app()->user->checkAccess('admin')):?>
                        <button type="button" class="btn btn-info btn_reset_assisted">重新分配</button>
                        <?php endif;?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPassword">联动理由：</label>
                    <div class="controls">
                        <label class="control-label" style="text-align: left;width: 100%;"><?php echo empty($assistedapply_log)?"":$assistedapply_log->remark;?></label>
                    </div>
                </div>
                <div class="control-group reset_assisted_units" style="display: none;">
                    <label class="control-label">需要联动：</label>
                    <div class="controls">
                        <label class="radio inline">
                            <input type="radio" class="rdi_need_assistant" name="need_assistant" value="1" checked="checked" /> 是
                        </label>
                        <label class="radio inline">
                            <input type="radio" class="rdi_need_assistant" name="need_assistant" value="0" /> 否
                        </label>
                    </div>
                </div>
                <div class="control-group reset_assisted_units assisted_units_lists" style="display: none;">
                    <label class="control-label" for="inputPassword">联动单位：</label>
                    <div class="controls">
                        <label for="inputEmail">联动单位：</label>
                        <?php
                            $users = $user_service->getAvailableUsers();
                            $line_num = 5; $own_user_key = -1;
                        ?>
                        <?php if(!empty($users)):?>
                        <table class="table">
                        <?php foreach($users as $key=>$user):?>
                        <?php if($user->id == $problem->deal_uid):?>
                        <?php $own_user_key = $key;?>
                        <?php else:?>
                        <?php if($own_user_key > 0 &&$key > $own_user_key):?>
                        <?php $key = $key - 1;?>
                        <?php endif;?>
                        <?php if($key == 0 || (($key % $line_num) == 0)):?>
                        <tr>
                        <?php endif;?>
                            <td>
                                <label class="checkbox">
                                    <input type="checkbox" name="user_ids[]" value="<?php echo $user->id;?>" <?php if(in_array($user->id, $unit_uids)):?> checked="checked"<?php endif;?> /> <?php echo $user->username;?>
                                </label>
                            </td>
                        <?php if((($key % $line_num) == 4)):?>
                        </tr>
                        <?php endif;?>
                        <?php endif;?>
                        <?php endforeach;?>
                        </table>
                        <?php endif;?>
                    </div>
                </div>
            <?php endif;?> -->
            <?php if(!(isset($problem_images) && empty($problem_images))):?>
            <div class="form-group">
                <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">问题图片: </label>
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                    <ul class="form-control-static list-unstyled list-inline img_lists">
                        <?php foreach($problem_images as $problem_image):?>
                            <?php $img_path = $problem_image->img_path; ?>
                            <?php $img_radio = round($problem_image->img_width / $problem_image->img_height, 2); ?>
                            <?php $img_height = 190; $img_width = $img_height * $img_radio; ?>
                            <li><img src="<?php echo $img_path;?>" width="<?php echo $img_width;?>" height="<?php echo $img_height;?>" class="img-rounded" /></li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
            <?php endif;?>
            <?php if(in_array($problem->status, array(ProblemService::WAIT_CHECKING, ProblemService::BE_QUALIFIED, ProblemService::BE_UNQUALIFIED, ProblemService::BE_CLOSED))): ?>
                <div class="form-group">
                    <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">解决图片: </label>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                        <ul class="form-control-static list-unstyled list-inline img_lists">
                            <?php $solve_images = $pimg_service->getImagesByPid($problem->id, 2); ?>
                            <?php foreach($solve_images as $solve_image): ?>
                                <?php $img_path = $solve_image->img_path; ?>
                                <?php $img_radio = round($solve_image->img_width / $solve_image->img_height, 2); ?>
                                <?php $img_height = 190; $img_width = $img_height * $img_radio; ?>
                                <li><img src="<?php echo $img_path;?>" width="<?php echo $img_width;?>" height="<?php echo $img_height;?>" class="img-rounded" /></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            <?php endif;?>

            <div class="form-group">
                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2 col-lg-10 col-md-10 col-sm-10 col-xs-10">
                    <div class="form-control-static">
                        <?php if(Yii::app()->user->checkAccess('finder') && $problem->status == ProblemService::BE_CREATED):?>
                        <button type="button" name="solve_unqualified" class="btn btn-danger btn_submit_form">撤销</button>
                        <?php endif;?>
                        <!-- 是发布人员，本页面只能观看信息，不能再进行操作 -->
                        <?php if(!Yii::app()->user->checkAccess('finder')): ?>
                            <?php if(Yii::app()->user->checkAccess('admin')): ?>
                                <?php if(in_array($problem->status, array(ProblemService::BE_CREATED, ProblemService::BE_BACKING, ProblemService::APPLY_DELAYING, ProblemService::APPLY_ASSISTING))): ?>
                                    <button type="button" class="btn btn-primary btn_submit_form">分配</button>
                                <?php endif; ?>
                                <?php if(in_array($problem->status, array(ProblemService::WAIT_CHECKING))): ?>
                                    <button type="button" name="solve_qualified" class="btn btn-success btn_solve_qualified">通过</button>
                                    <button type="button" name="solve_unqualified" class="btn btn-danger btn_go_solve_unqualified">打回</button>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if(Yii::app()->user->checkAccess('unit')): ?>
                                <?php if(in_array($problem->status, array(ProblemService::BE_ASSIGNED))): ?>
                                    <!--
                                    <button type="button" class="btn btn-success btn_submit_form">接受</button>
                                    <button type="button" class="btn btn-danger btn_go_back_problem">退单</button>-->
                                <?php endif; ?>
                                <?php if(in_array($problem->status, array(ProblemService::BE_ASSIGNED, ProblemService::WAIT_CHECKING, ProblemService::BE_BACKING))): ?>
                                    <a href="<?php echo $this->createUrl("/problem/solve", array("pid"=>$problem->id, "back_url_top"=>$back_url, "back_url"=>urlencode(Util::getCurrentUrl()))); ?>" 
                                        class="btn btn-primary">上传处理结果</a>
                                <?php endif; ?>
                                <?php if(in_array($problem->status, array(ProblemService::BE_ASSIGNED, ProblemService::BE_DEALING, ProblemService::APPLY_DELAYING, ProblemService::APPLY_ASSISTING))): ?>
                                    <?php if($problem->is_assistant == 0 && $problem->status != ProblemService::APPLY_ASSISTING): ?>
                                        <!--
                                        <button type="button" class="btn btn-warning btn_go_assisted_problem">申请联动</button>-->
                                    <?php endif; ?>
                                    <?php if($problem->status != ProblemService::APPLY_DELAYING): ?>
                                        <button type="button" class="btn btn-warning btn_go_delay_problem">申请延时</button>
                                    <?php endif; ?>
                                <?php endif;?>
                            <?php endif; ?>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
    //申请延时form
    if($problem->is_delay == 0 && in_array($problem->status, array(ProblemService::BE_ASSIGNED, ProblemService::BE_DEALING, ProblemService::APPLY_ASSISTING)) && file_exists(dirname(__FILE__).'/_apply_delay_problem.php')){
        require_once(dirname(__FILE__).'/_apply_delay_problem.php');
    }
    //申请联动form
    if($problem->is_assistant == 0 && in_array($problem->status, array(ProblemService::BE_ASSIGNED, ProblemService::BE_DEALING, ProblemService::APPLY_DELAYING)) && file_exists(dirname(__FILE__).'/_apply_assisted_problem.php')){
        require_once(dirname(__FILE__).'/_apply_assisted_problem.php');
    }
    //退单form
    if(in_array($problem->status, array(ProblemService::BE_ASSIGNED)) && file_exists(dirname(__FILE__).'/_back_problem.php')){
        require_once(dirname(__FILE__).'/_back_problem.php');
    }
    //打回处理结果form
    if(in_array($problem->status, array(ProblemService::WAIT_CHECKING)) && file_exists(dirname(__FILE__).'/_solve_unqualified.php')){
        require_once(dirname(__FILE__).'/_solve_unqualified.php');
    }
?>
<script type="text/javascript" src="<?php echo Yii::app()->params->js_url;?>/file/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params->js_url;?>/problem.js"></script>