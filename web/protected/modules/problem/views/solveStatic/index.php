<?php ?>
<?php
    $calendar_icon = Yii::app()->params->image_url.'/calendar.gif';
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">搜索</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">分配时间: </label>
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
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
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2 col-lg-10 col-md-10 col-sm-10 col-xs-10">
                            <input type="submit" class="btn btn-primary" name="preview" value="预览" />
                            <input type="submit" class="btn btn-success second-btn" name="export" value="导出汇总" />
                            <input type="hidden" name="preview_assign_start_date" value="<?php echo $assign_start_date; ?>">
                            <input type="hidden" name="preview_assign_end_date" value="<?php echo $assign_end_date; ?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                <?php if(!empty($statistics)):?>
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
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">
                            暂无数据
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>