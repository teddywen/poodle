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
										<input type="submit" class="btn btn-danger" name="refuse" value="拒绝" />
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

<?php  
	//拒绝延时申请form
    // if(in_array($problem->status, array(ProblemService::WAIT_CHECKING)) && file_exists(dirname(__FILE__).'/_delay_refuse.php')){
    //     require_once(dirname(__FILE__).'/_delay_refuse.php');
    // }
?>

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