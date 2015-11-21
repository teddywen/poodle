<?php
    $opetate_url = ''; $assign_control_disabled = 'disabled="disabled"';
    if(Yii::app()->user->checkAccess('finder') && $problem->status == ProblemService::BE_CREATED){
        $opetate_url = '/problem/problemFlow/cancelProblem';
    }
    if(Yii::app()->user->checkAccess('admin') && !in_array($problem->status, array(ProblemService::WAIT_CHECKING, ProblemService::BE_QUALIFIED, ProblemService::BE_CANCELED, ProblemService::BE_CLOSED))){
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
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title problem-title">问题详情</h3><a href="<?php echo urldecode($back_url) ?>" class="btn btn-default back">返回</a>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <form class="form-horizontal problem_info_form" method="post" action="<?php echo $opetate_url;?>" onsubmit="return false;">
                    <input type="hidden" name="pid" value="<?php echo $problem->id;?>" />
                    <div class="imgname_lists" style="display: none;"></div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">编号: </label>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <p class="form-control-static"><mark><strong>#<?php echo $problem->id;?></strong></mark></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">状态: </label>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <p class="form-control-static"><?php echo ProblemService::$status[$problem->status];?></p>
                        </div>
                    </div>
                    <?php if(!in_array($problem->status, array(ProblemService::BE_CREATED, ProblemService::BE_CANCELED, ProblemService::BE_CLOSED))):?>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">分配时间: </label>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <p class="form-control-static"><?php echo date('Y-m-d H:i:s', $problem->assign_time);?></p>
                        </div>
                    </div>
                    <?php endif;?>
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
                    <?php $deal_day = floor($problem->deal_time / 24); ?>
                    <!-- 管理员对新创建和被退回的问题进行指派 -->
                    <?php if(Yii::app()->user->checkAccess('admin')):?>
                        <?php
                            $cate_service = new CategoryService();
                            $gov_cates = $cate_service->getAvailableCate();
                            if(!in_array($problem->status, array(ProblemService::BE_CREATED, ProblemService::BE_CLOSED))){
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
                                <?php $total_day = floor(($problem->deal_time + $problem->delay_time )/ 24); ?>
                                <?php $deal_day = floor($problem->deal_time / 24); ?>
                                <?php $delay_day = floor($problem->delay_time / 24); ?>
                                <p class="form-control-static"><?php echo $total_day; ?>天 <?php if($delay_day > 0) echo "({$deal_day}天 + 延时{$delay_day}天)"; ?></p>
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
                    <?php if(in_array($problem->status, array(ProblemService::WAIT_CHECKING, ProblemService::BE_QUALIFIED, ProblemService::BE_UNQUALIFIED))): ?>
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
                                <?php if((Yii::app()->user->checkAccess('shutdown_new_problem') && 
                                            Yii::app()->user->getId() == $problem->release_uid &&
                                            in_array($problem->status, array(ProblemService::BE_CREATED)))): // finder?>
                                    <button type="button" name="solve_unqualified" class="btn btn-danger btn_submit_form">撤销</button>
                                <?php endif;?>
                                <?php if(Yii::app()->user->checkAccess('dispatch_problem') && 
                                            !in_array($problem->status, array(ProblemService::WAIT_CHECKING, ProblemService::BE_QUALIFIED, ProblemService::BE_CANCELED, ProblemService::BE_CLOSED))): ?>
                                    <button type="button" class="btn btn-primary btn_submit_form">分配</button>
                                <?php endif; ?>
                                <?php if(Yii::app()->user->checkAccess('manager_close_problem') && 
                                            in_array($problem->status, array(ProblemService::BE_CREATED))): ?>
                                    <button type="button" class="btn btn-danger btn_close_problem">删除</button>
                                <?php endif; ?>
                                <?php if(Yii::app()->user->checkAccess('approval_problem') && 
                                            in_array($problem->status, array(ProblemService::WAIT_CHECKING))): ?>
                                    <button type="button" name="solve_qualified" class="btn btn-success btn_solve_qualified">通过</button>
                                    <button type="button" name="solve_unqualified" class="btn btn-danger btn_go_solve_unqualified">打回</button>
                                <?php endif; ?>
                                <?php if(Yii::app()->user->checkAccess('apply_problem') && Yii::app()->user->getId() == $problem->deal_uid):?>
                                    <?php if(in_array($problem->status, array(ProblemService::BE_DEALING, ProblemService::APPLY_DELAYING))):?>
                                    <a href="<?php echo $this->createUrl("/problem/solve", array("pid"=>$problem->id, "back_url_top"=>$back_url, "back_url"=>urlencode(Util::getCurrentUrl()))); ?>" 
                                        class="btn btn-primary">上传处理结果</a>
                                    <?php elseif(in_array($problem->status, array(ProblemService::WAIT_CHECKING, ProblemService::BE_UNQUALIFIED))):?>
                                    <a href="<?php echo $this->createUrl("/problem/solve", array("pid"=>$problem->id, 'modify_solve' => 1, "back_url_top"=>$back_url, "back_url"=>urlencode(Util::getCurrentUrl()))); ?>" 
                                        class="btn btn-warning">修改处理结果</a>
                                    <?php endif;?>
                                <?php endif;?>
                                <?php if(Yii::app()->user->checkAccess('apply_problem') && 
                                            Yii::app()->user->getId() == $problem->deal_uid && 
                                            in_array($problem->status, array(ProblemService::BE_DEALING, ProblemService::BE_UNQUALIFIED, ProblemService::APPLY_DELAYING))): ?>
                                    
                                <?php endif; ?>
                                <?php if(Yii::app()->user->checkAccess('delay_apply_problem') &&
                                            Yii::app()->user->getId() == $problem->deal_uid && 
                                            in_array($problem->status, array(ProblemService::BE_DEALING, ProblemService::BE_UNQUALIFIED))): ?>
                                    <button type="button" class="btn btn-warning btn_go_delay_problem">申请延时</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if(!in_array($problem->status, array(ProblemService::BE_CREATED))): ?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">延时申请 <small>共计可延时:<mark><?php echo floor($problem->delay_time / 24); ?></mark>天</small></h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>申请编号</th>
                                <th>申请理由</th>
                                <th>申请天数</th>
                                <th>状态 (未通过理由)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($problemDelayLogs)): ?>
                                <?php foreach($problemDelayLogs as $problemDelayLog): ?>
                                    <?php $data = CJSON::decode($problemDelayLog->data); ?>
                                    <tr <?php if($problemDelayLog->status == ProblemLogService::STATUS_DELAY_AGREE): ?>
                                            class="success"
                                        <?php elseif($problemDelayLog->status == ProblemLogService::STATUS_DELAY_REFUSE): ?>
                                            class="danger"
                                        <?php elseif($problemDelayLog->status == ProblemLogService::STATUS_DELAY_CANCEL): ?>
                                            class="active"
                                        <?php endif; ?>>
                                        <td><mark><strong>#<?php echo $problemDelayLog->id; ?></strong></mark></td>
                                        <td><?php echo $problemDelayLog->remark; ?></td>
                                        <td><?php echo floor($data["hour"] / 24); ?></td>
                                        <td>
                                            <?php if($problemDelayLog->status == ProblemLogService::STATUS_DELAY_WAIT): ?>
                                                等待审核
                                            <?php elseif($problemDelayLog->status == ProblemLogService::STATUS_DELAY_CANCEL): ?>
                                                已撤销
                                            <?php elseif($problemDelayLog->status == ProblemLogService::STATUS_DELAY_AGREE): ?>
                                                已通过
                                            <?php elseif($problemDelayLog->status == ProblemLogService::STATUS_DELAY_REFUSE): ?>
                                                未通过 <?php if(isset($data["delay_refuse_remark"])) echo "(理由: " . CHtml::encode($data["delay_refuse_remark"]) . ")"; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center">暂无申请...</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
    //申请延时form
    if (Yii::app()->user->checkAccess('delay_apply_problem') &&
            Yii::app()->user->getId() == $problem->deal_uid && 
            in_array($problem->status, array(ProblemService::BE_DEALING, ProblemService::BE_UNQUALIFIED)) && 
            file_exists(dirname(__FILE__).'/_apply_delay_problem.php')) {
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