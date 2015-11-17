<!-- Modal -->
<div class="modal fade" id="solve_unqualified_modal" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">打回理由</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                        <form class="solve_unqualified_form" action="<?php echo Yii::app()->baseUrl;?>/problem/problemFlow/setSolveResult" method="post" onsubmit="return false;">
                            <input type="hidden" name="pid" value="<?php echo $problem->id;?>" />
                            <input name="solve_result" value="0" type="hidden" />
                            <div class="form-group">
                                <label for="problem_log_remark">请填写打回理由: </label>
                                <textarea class="form-control problem_log_remark" name="problem_log_remark" rows="3" cols="60" maxlength="255" style="resize: none;" placeholder="由于..."></textarea>
                            </div>
                            <div class="form-group">
                                <button id="button" class="btn btn-info btn_solve_unqualified">确定</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>