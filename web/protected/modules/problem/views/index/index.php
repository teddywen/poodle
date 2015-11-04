<?php
    if(file_exists(dirname(__FILE__).'/_search.php')){
        include_once(dirname(__FILE__).'/_search.php');
    }
?>
<table class="table table-condensed table-bordered table-hover">
	<thead>
		<tr>
			<th>
				编号
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
				创建时间
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
			    <a href="<?php echo Yii::app()->baseUrl?>/problem/index/view?id=<?php echo $problem->id;?>" class="btn btn-info">查看</a>
			</td>
		</tr>
        <?php endforeach;?>
        <?php else:?>
		<tr>
			<td colspan="8">
				暂无数据...
			</td>
		</tr>
        <?php endif;?>
	</tbody>
</table>
<?php
    if(ceil($count / $this->PAGE_SIZE) > 1){
        if(file_exists(Yii::app()->basePath.'/views/common/pager.php')){
            require_once(Yii::app()->basePath.'/views/common/pager.php');
        }
    }
?>