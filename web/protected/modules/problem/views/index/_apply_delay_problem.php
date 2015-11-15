<!-- Modal -->
<div class="modal fade" id="delay_problem_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">申请延时</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                        <form class="delay_problem_form" action="<?php echo $this->createUrl("/problem/problemFlow/applyDelayProblem");?>" method="post" onsubmit="return false;">
                            <input type="hidden" name="pid" value="<?php echo $problem->id;?>" />
                            <div class="form-group">
                                <label for="problem_log_remark">请填写延时理由: </label>
                                <textarea class="form-control modal-textarea problem_log_remark" name="problem_log_remark" rows="3" cols="60" maxlength="255" placeholder="由于..."></textarea>
                            </div>
                            <div class="row"><div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group set_deal_time">
                                    <label>需要延时: </label>
                                    <select id="delay_day" name="delay_day" class="form-control">
                                        <?php for($i=1;$i<=30;$i++):?>
                                            <option value="<?php echo $i;?>"><?php echo $i;?>天</option>
                                        <?php endfor;?>
                                    </select>
                                </div>
                            </div></div>
                            <div class="form-group"><button id="button" class="btn btn-primary btn_delay_problem">确定</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>