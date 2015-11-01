<!-- Modal -->
<div class="modal fade" id="delay_problem_modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">申请延时</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="span5">
                        <form class="form-horizontal delay_problem_form" action="<?php echo Yii::app()->baseUrl;?>/problem/problemFlow/applyDelayProblem" method="post" onsubmit="return false;">
                            <input type="hidden" name="pid" value="<?php echo $problem->id;?>" />
                            <div style="margin-bottom: 20px;">
                                <label for="problem_log_remark" style="margin-bottom: 10px;">请填写延时理由：</label>
                                <textarea class="problem_log_remark" name="problem_log_remark" rows="3" cols="60" maxlength="255" style="width: 100%; resize: none;"></textarea>
                            </div>
                            <div class="control-group set_deal_time">
                        		<label for="inputEmail">需要延时：</label>
                                <select id="delay_month" name="delay_month" class="input-medium">
                                    <?php for($i=0;$i<=12;$i++):?>
                                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                    <?php endfor;?>
                                </select>&nbsp;&nbsp;月&nbsp;&nbsp;&nbsp;&nbsp;
                                <select id="delay_day" name="delay_day" class="input-medium">
                                    <?php for($i=1;$i<=30;$i++):?>
                                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                    <?php endfor;?>
                                </select>&nbsp;&nbsp;日
                        	</div>
                            <div>
                                <button id="button" class="btn btn-info btn_delay_problem">确定</button>
                                <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>