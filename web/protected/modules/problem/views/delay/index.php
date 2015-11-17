<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th>问题编号</th>
					<th>问题描述</th>
					<th>延时理由</th>
					<th>延时天数</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(!empty($delayApplies)): ?>
					<?php foreach ($delayApplies as $key => $delayApply): ?>
						<tr>
							<td><mark><strong>#<?php echo $delayApply["id"]; ?></strong></mark></td>
							<td><?php echo $delayApply["description"]; ?></td>
							<td><?php echo $delayApply["remark"]; ?></td>
							<td><?php $data = CJSON::decode($delayApply["data"]); echo floor($data["hour"] / 24); ?></td>
							<td>
								<form action="<?php echo $this->createUrl("/problem/problemflow/approvaldelayapply"); ?>" method="post">
									<input type="hidden" name="id" value="<?php echo $delayApply["id"]; ?>" />
									<input type="hidden" name="log_id" value="<?php echo $delayApply["log_id"]; ?>" />
									<a href="<?php echo $this->createUrl("/problem/index/view", array("id"=>$delayApply["id"])); ?>" target="_blank" class="btn btn-primary">查看</a>
									<?php if(Yii::app()->user->checkAccess("delay_approval_problem")): ?>
										<input type="submit" class="btn btn-success" name="agree" value="同意" />
										<a name="refuse" class="btn btn-danger" data-toggle="modal" data-target="#delay_refuse_modal_<?php echo $delayApply["id"]; ?>" href="javascript:void(0);">拒绝</a>
										<?php echo $this->renderPartial("_delay_refuse", array("modal_id"=>"delay_refuse_modal_{$delayApply["id"]}")); ?>
									<?php endif; ?>
								</form>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr><td colspan="5" class="text-center">暂无申请...</td></tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript"><!--
$(function(){
	$('form').ajaxForm({
	    	dataType: "json", 
	    	success: function(res) {
	    		alert(res.msg);
	    		if (res.code == 1) {
	    			window.location.reload();
	    		}
	    	}
	    });
});
//-->
</script>