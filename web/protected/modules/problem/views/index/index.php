<?php
    $calendar_icon = Yii::app()->params->image_url.'/calendar.gif';
    $start_time = date('Y-m-01'); $end_time = date('Y-m-d');
    $create_start_time = isset($_GET['create_start_time'])?$_GET['create_start_time']:$start_time;
    $create_end_time = isset($_GET['create_end_time'])?$_GET['create_end_time']:$end_time;
?>
<form class="form-search form-inline">
	发布时间：
	<?php
        $this->widget('zii.widgets.jui.CJuiDatePicker',array(
            'attribute' => 'visit_time',
            'language' => 'zh_cn',
            'name' => 'create_start_time',
            'value' => $create_start_time,
            'options' => array(
                'showOn' => 'both',
                'buttonImage' => $calendar_icon,
                'buttonImageOnly' => true,
                'minDate' => 'new Date()',
                'dateFormat' => 'yy-mm-dd',
            ),
            'htmlOptions' => array(
                'style' => 'width: 80px',
            ),
        ));
    ?>
    -
    <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker',array(
            'attribute' => 'visit_time',
            'language' => 'zh_cn',
            'name' => 'create_end_time',
            'value' => $create_end_time,
            'options' => array(
                'showOn' => 'both',
                'buttonImage' => $calendar_icon,
                'buttonImageOnly' => true,
                'minDate' => 'new Date()',
                'dateFormat' => 'yy-mm-dd',
            ),
            'htmlOptions' => array(
                'style' => 'width: 80px',
            ),
        ));
    ?>
	<?php $s_gov_cate_id = isset($_REQUEST['s_gov_cate_id'])&&strlen($_REQUEST['s_gov_cate_id'])>0?intval($_REQUEST['s_gov_cate_id']):"";?>
	单位分类：<select name="s_gov_cate_id" class="form-control input-medium">
            <option value="">全部</option>
            <option value="0"<?php if(strlen($s_gov_cate_id)>0&&$s_gov_cate_id==0):?> selected="selected"<?php endif;?>>未分类</option>
            <?php if(!empty($cates)):?>
            <?php foreach($cates as $cate):?>
            <option value="<?php echo $cate->id;?>"<?php if($s_gov_cate_id==$cate->id):?> selected="selected"<?php endif;?>><?php echo $cate->cate_name;?></option>
            <?php endforeach;?>
            <?php endif;?>
        </select>
	<button type="submit" class="btn btn-primary">查找</button>
</form>
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
			<td colspan="6">
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