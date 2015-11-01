<!-- Modal -->
<?php
    $delayapply_log = end($problem_log_service->getProblemStatusLog($problem->id, ProblemService::APPLY_DELAYING));
?>
<div class="modal fade" id="check_delay_modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">申请延时</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="span5">
                        <form class="form-horizontal check_delay_form" action="<?php echo Yii::app()->baseUrl;?>/problem/problemFlow/applyDelayProblem" method="post" onsubmit="return false;">
                            <input type="hidden" name="pid" value="<?php echo $problem->id;?>" />
                            <div style="margin-bottom: 20px;">
                                <label for="problem_log_remark" style="margin-bottom: 10px;">延时理由：</label>
                                <?php echo empty($delayapply_log)?"":$delayapply_log->remark;?>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label for="problem_log_remark" style="margin-bottom: 10px;">延时时长：</label>
                                <?php echo $deal_month;?>&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $deal_day;?>&nbsp;&nbsp;日
                            </div>
                        	<div class="control-group">
                        		<label class="control-label" style="width: 80px; text-align: left;">是否同意：</label>
                        		<div class="controls" style="margin-left: 50px;">
                        			<label class="radio inline">
                        				<input type="radio" name="status" value="1" checked="checked" /> 同意
                        			</label>
                        			<label class="radio inline">
                        				<input type="radio" name="status" value="0" /> 不同意
                        			</label>
                        		</div>
                        	</div>
                            <div class="control-group reset_deal_time" style="display: none;">
                        		<label for="inputEmail">需要延时：</label>
                                <select id="delay_month" name="delay_month" class="input-medium">
                                    <?php for($i=0;$i<=12;$i++):?>
                                    <option value="<?php echo $i;?>"<?php if($i==$delay_month):?> selected="selected"<?php endif;?>><?php echo $i;?></option>
                                    <?php endfor;?>
                                </select>&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;
                                <select id="delay_day" name="delay_day" class="input-medium">
                                    <?php for($i=1;$i<=30;$i++):?>
                                    <option value="<?php echo $i;?>"<?php if($i==$delay_day):?> selected="selected"<?php endif;?>><?php echo $i;?></option>
                                    <?php endfor;?>
                                </select>&nbsp;&nbsp;日
                        	</div>
                        	<div style="margin-bottom: 20px;">
                                <label for="inputEmail" style="margin-bottom: 10px;">请填写拒绝理由：</label>
                                <textarea class="problem_log_remark" name="problem_log_remark" rows="3" cols="60" maxlength="255" style="width: 100%; resize: none;"></textarea>
                            </div>
                            <div>
                                <button id="button" class="btn btn-info btn_check_delayapply">确定</button>
                                <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>