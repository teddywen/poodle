<?php ?>
<?php
    $calendar_icon = Yii::app()->params->image_url.'/calendar.gif';
?>
<form class="form-search form-inline">
    <div style="margin-bottom: 10px;">
    	分配时间：
    	<?php
            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                'attribute' => 'visit_time',
                'language' => 'zh_cn',
                'name' => 'assign_start_date',
                'value' => $assign_start_date,
                'options' => array(
                    'showOn' => 'both',
                    'buttonImage' => $calendar_icon,
                    'buttonImageOnly' => true,
                    'maxDate' => 'new Date()',
                    'dateFormat' => 'yy-mm-dd',
                ),
                'htmlOptions' => array(
                    'style' => 'width: 120px',
                ),
            ));
        ?>
        -
        <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                'attribute' => 'visit_time',
                'language' => 'zh_cn',
                'name' => 'assign_end_date',
                'value' => $assign_end_date,
                'options' => array(
                    'showOn' => 'both',
                    'buttonImage' => $calendar_icon,
                    'buttonImageOnly' => true,
                    'maxDate' => 'new Date()',
                    'dateFormat' => 'yy-mm-dd',
                ),
                'htmlOptions' => array(
                    'style' => 'width: 120px',
                ),
            ));
        ?>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button type="submit" class="btn btn-info" name="preview">预览</button>
        <button type="submit" class="btn btn-success" name="explode_csv" value="1">导出汇总</button>
    </div>
</form>
<?php if(!empty($statistics)):?>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>序号</th>
                <th>单位</th>
                <th>问题数</th>
                <th>已整改</th>
                <th>联动问题</th>
                <th>申请延期</th>
                <th>整改超时</th>
                <th>未整改</th>
                <th>备注</th>
            </tr>
        </thead>
        <tbody>
            <?php $total_problem_count = $total_problem_qualified_count = $total_problem_is_assistant_count = 
                $total_problem_is_delay_count = $total_problem_times_up_count = $total_problem_unqualified_count = 0; ?>
            <?php foreach($statistics as $key => $row): ?>
                <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td><?php echo $row["deal_username"]; ?></td>
                    <td><?php echo $row["problem_count"]; $total_problem_count += $row["problem_count"]; ?></td>
                    <td><?php echo $row["problem_qualified_count"]; $total_problem_qualified_count += $row["problem_qualified_count"]; ?></td>
                    <td><?php echo $row["problem_is_assistant_count"]; $total_problem_is_assistant_count += $row["problem_is_assistant_count"]; ?></td>
                    <td><?php echo $row["problem_is_delay_count"]; $total_problem_is_delay_count += $row["problem_is_delay_count"]; ?></td>
                    <td><?php echo $row["problem_times_up_count"]; $total_problem_times_up_count += $row["problem_times_up_count"]; ?></td>
                    <td><?php echo $row["problem_unqualified_count"]; $total_problem_unqualified_count += $row["problem_unqualified_count"]; ?></td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="2">合计</td>
                <td><?php echo $total_problem_count; ?></td>
                <td><?php echo $total_problem_qualified_count; ?></td>
                <td><?php echo $total_problem_is_assistant_count; ?></td>
                <td><?php echo $total_problem_is_delay_count; ?></td>
                <td><?php echo $total_problem_times_up_count; ?></td>
                <td><?php echo $total_problem_unqualified_count; ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>
<?php endif; ?>