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
        <input type="submit" class="btn btn-info" name="preview" value="预览" />
        <input type="submit" class="btn btn-success" name="export" value="导出汇总" />
        <input type="hidden" name="preview_assign_start_date" value="<?php echo $assign_start_date; ?>">
        <input type="hidden" name="preview_assign_end_date" value="<?php echo $assign_end_date; ?>">
    </div>
</form>
<?php foreach($statistics as $assign_date => $deal_username_rows): ?>
    <?php $no = 1; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th rowspan="3">序号</th>
                <th rowspan="3">单位</th>
                <th rowspan="3">派单日期</th>
                <th rowspan="3">发现问题数</th>
                <th colspan="2">存在问题</th>
                <th colspan="3">整改计划</th>
                <th rowspan="3">需要县镇联动</th>
                <th rowspan="3">申请延时</th>
                <th rowspan="3">完成情况</th>
                <th rowspan="3">备注</th>
            </tr>
            <tr>
                <th rowspan="2">序号</th>
                <th rowspan="2">具体问题</th>
                <th>7天内完成</th>
                <th>14天内完成</th>
                <th>1个月完成</th>
            </tr>
            <tr>
                <th><?php echo date("Y.n.j", strtotime($assign_date) + 7 * 24 * 3600); ?></th>
                <th><?php echo date("Y.n.j", strtotime($assign_date) + 14 * 24 * 3600); ?></th>
                <th><?php echo date("Y.n.j", strtotime($assign_date) + 30 * 24 * 3600); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($deal_username_rows as $deal_username => $rows): ?>
                <?php $count = count($rows); ?>
                <?php foreach($rows as $key => $row): ?>
                    <tr>
                        <?php if($key == 0): ?>
                            <td rowspan="<?php echo $count; ?>"><?php echo $no++; ?></td>
                            <td rowspan="<?php echo $count; ?>"><?php echo $deal_username; ?></td>
                            <td rowspan="<?php echo $count; ?>"><?php echo date("Y.n.j", strtotime($assign_date)); ?></td>
                            <td rowspan="<?php echo $count; ?>"><?php echo $count; ?></td>
                        <?php endif; ?>
                        <td><?php echo $key + 1;?></td>
                        <td><p class="text-left" style="margin:0px;"><?php echo $row["description"]?></p></td>
                        <td><?php echo $row["duration_lv"] == 1 ? "√" : "";?></td>
                        <td><?php echo $row["duration_lv"] == 2 ? "√" : "";?></td>
                        <td><?php echo $row["duration_lv"] == 3 ? "√" : "";?></td>
                        <td><?php echo $row["is_assistant"] ? "√": "";?></td>
                        <td><?php echo $row["is_delay"] ? ("{$row["delay_day"]}天完成"): "";?></td>
                        <td><?php echo $row["status"] == ProblemService::BE_QUALIFIED ? "完成": "";?></td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>