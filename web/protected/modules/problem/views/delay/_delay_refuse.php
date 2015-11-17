<!-- Modal -->
<div class="modal fade" id="<?php echo $modal_id; ?>" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">拒绝延时申请理由</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                        <div class="form-group">
                            <label for="remark_<?php echo $modal_id; ?>">请填写拒绝延时申请理由: </label>
                            <textarea class="form-control" id="remark_<?php echo $modal_id; ?>" name="remark" rows="3" cols="60" maxlength="255" style="resize: none;" placeholder="由于..."></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-danger" name="refuse" value="拒绝" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>