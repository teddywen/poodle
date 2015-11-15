<?php
    if(file_exists(dirname(__FILE__).'/_search.php')){
        include_once(dirname(__FILE__).'/_search.php');
    }
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table table-bordered table-hover table-striped">
        	<thead>
				<tr>
					<th>
						编号
					</th>
					<th>
						描述
					</th>
					<th>
						发布人
					</th>
					<th>
						处理单位
					</th>
					<th>
						状态
					</th>
					<th>
						是否延期
					</th>
					<th>
						是否联动
					</th>
					<th>
						发布时间
					</th>
					<th>
						分配时间
					</th>
					<th>
						审核时间
					</th>
					<th>
						操作
					</th>
				</tr>
			</thead>
			<tbody>
		        <?php if(!empty($problems)):?>
		        <?php foreach($problems as $problem):?>
				<tr>
					<td>
						<?php echo $problem->id;?>
					</td>
					<td>
						<?php echo $problem->description;?>
					</td>
					<td>
						<?php echo $problem->release_username;?>
					</td>
					<td>
						<?php echo $problem->deal_username;?>
					</td>
					<td>
						<?php echo ProblemService::$status[$problem->status];?>
					</td>
					<td>
						<?php echo $problem->is_delay==1?"是":"否";?>
					</td>
					<td>
						<?php echo $problem->is_assistant==1?"是":"否";?>
					</td>
					<td>
						<?php echo date('Y-m-d H:i:s', $problem->create_time) ;?>
					</td>
					<td>
						<?php echo !empty($problem->assign_time)?date('Y-m-d H:i:s', $problem->assign_time):"" ;?>
					</td>
					<td>
						<?php echo !empty($problem->check_time)?date('Y-m-d H:i:s', $problem->check_time):"" ;?>
					</td>
					<td>
					    <a href="<?php echo $this->createUrl("/problem/index/view", array("id"=>$problem->id, "back_url"=>urlencode(Util::getCurrentUrl())));?>" class="btn btn-primary">查看</a>
					</td>
				</tr>
		        <?php endforeach;?>
		        <?php else:?>
				<tr>
					<td colspan="11" class="text-center">
						暂无数据
					</td>
				</tr>
		        <?php endif;?>
			</tbody>
        </table>
    </div>
</div>

<?php
    $total_page = ceil($count / $this->PAGE_SIZE);
    if($total_page > 1){
        if(file_exists(Yii::app()->basePath.'/views/common/pager.php')){
            require_once(Yii::app()->basePath.'/views/common/pager.php');
        }
    }
?>