<!-- Modal -->
<div class="modal fade" id="assisted_problem_modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 750px;">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">申请联动</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                        <form class="assisted_problem_form" action="<?php echo Yii::app()->baseUrl;?>/problem/problemFlow/applyAssitedProblem" method="post" onsubmit="return false;">
                            <input type="hidden" name="pid" value="<?php echo $problem->id;?>" />
                            <div class="form-group">
                                <label for="problem_log_remark" style="margin-bottom: 10px;">请填写重新分配联动理由：</label>
                                <textarea class="form-control modal-textarea problem_log_remark" name="problem_log_remark" rows="3" cols="60" maxlength="255" style="width: 100%; resize: none;"></textarea>
                            </div>
                            <div class="form-group set_deal_time">
                        		<label for="inputEmail">联动单位：</label>
                        		<?php
                                    $users = $user_service->getAvailableUsers();
                                    $line_num = 5; $own_user_key = -1;
                                    $users_num = count($users) - 1; $empty_td_num = $line_num - ($users_num) % $line_num;
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
                                    <td style="width: 150px;">
                                        <label class="checkbox">
                                            <input type="checkbox" name="user_ids[]" value="<?php echo $user->id;?>" style="margin-left: 0px;" /><label><?php echo $user->username;?></label>
                                        </label>
                                    </td>
                        		<?php if(($key % $line_num) == 4 || $key == ($users_num - 1)):?>
                        		<?php if($key == ($users_num - 1) && $empty_td_num > 0):?>
                        		<?php for($i=0;$i<$empty_td_num;$i++):?>
                        		<td style="width: 150px;">&nbsp;</td>
                        		<?php endfor;?>
                        		<?php endif;?>
                        		</tr>
                        		<?php endif;?>
                        		<?php endif;?>
                        		<?php endforeach;?>
                        		</table>
                        		<?php endif;?>
                        	</div>
                            <div>
                                <button id="button" class="btn btn-info btn_assisted_problem">确定</button>
                                <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>