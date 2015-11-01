<!-- Modal -->
<div class="modal fade" id="solve_unqualified_modal" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">打回理由</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="span5">
                        <form class="solve_unqualified_form" action="<?php echo Yii::app()->baseUrl;?>/problem/problemFlow/setSolveResult" method="post" onsubmit="return false;">
                            <input type="hidden" name="pid" value="<?php echo $problem->id;?>" />
                            <input name="solve_result" value="0" type="hidden" />
                            <div style="margin-bottom: 20px;">
                                <label for="inputEmail" style="margin-bottom: 10px;">请填写打回理由：</label>
                                <textarea class="problem_log_remark" name="problem_log_remark" rows="3" cols="60" maxlength="255" style="width: 100%; resize: none;"></textarea>
                            </div>
                            <div>
                                <button id="button" class="btn btn-info btn_solve_unqualified">确定</button>
                                <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>