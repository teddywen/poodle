<!-- Modal -->
<div class="modal fade" id="back_problem_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">退单理由</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="span5">
                        <form class="back_problem_form" action="<?php echo Yii::app()->baseUrl;?>/problem/problemFlow/backProblem" method="post" onsubmit="return false;">
                            <input type="hidden" name="pid" value="<?php echo $problem->id;?>" />
                            <div style="margin-bottom: 20px;">
                                <label for="inputEmail" style="margin-bottom: 10px;">请填写退单理由：</label>
                                <textarea name="problem_log_remark" rows="3" cols="60" maxlength="255" style="width: 100%; resize: none;" required></textarea>
                            </div>
                            <div><button id="button" class="btn btn-info btn_back_problem">确定</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>