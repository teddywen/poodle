<!-- Modal -->
<div class="modal fade" id="back_problem_modal" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">退单理由</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="span5">
                        <form class="back_problem_form" action="<?php echo Yii::app()->baseUrl;?>/problem/problemFlow/backProblem" method="post" onsubmit="return false;">
                            <input type="hidden" name="pid" value="<?php echo $problem->id;?>" />
                            <div style="margin-bottom: 20px;">
                                <label for="inputEmail" style="margin-bottom: 10px;">请填写退单理由：</label>
                                <textarea class="problem_log_remark" name="problem_log_remark" rows="3" cols="60" maxlength="255" style="width: 100%; resize: none;"></textarea>
                            </div>
                            <div>
                                <button id="button" class="btn btn-info btn_back_problem">确定</button>
                                <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>