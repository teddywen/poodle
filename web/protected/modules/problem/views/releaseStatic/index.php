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
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2">
                            <label><input type="checkbox" name="with_image" <?php if($with_image == "on"): ?>checked<?php endif; ?>> 是否显示问题图片</label>
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
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th rowspan="2">序号</th>
                    <th rowspan="2">单位</th>
                    <th rowspan="2">派单日期</th>
                    <th rowspan="2">派单数</th>
                    <th rowspan="2">截止日期</th>
                    <th colspan="4" class="text-center">存在问题</th>
                    <th rowspan="2">申请延时</th>
                    <th rowspan="2">完成情况</th>
                    <th rowspan="2">操作</th>
                </tr>
                <tr>
                    <th rowspan="1">编号</th>
                    <th rowspan="1">问题地址</th>
                    <th rowspan="1">具体问题</th>
                    <th rowspan="1">问题图片</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 0; ?>
                <?php foreach($statistics as $deal_user => $assign_date_rows): ?>
                    <?php ++$no; ?>
                    <?php $rowspan_deal_user = 0; foreach($assign_date_rows as $rows) $rowspan_deal_user += count($rows); ?>
                    <?php foreach($assign_date_rows as $assign_date => $rows): ?>
                        <?php $rowspan_assign_date = count($rows); ?>
                        <?php foreach($rows as $key => $row): ?>
                            <tr>
                                <?php if(!isset($current_deal_user) || $current_deal_user != $deal_user): ?>
                                    <?php $current_deal_user = $deal_user; ?>
                                    <td rowspan="<?php echo $rowspan_deal_user; ?>"><?php echo $no; ?></td>
                                    <td rowspan="<?php echo $rowspan_deal_user; ?>"><?php echo $deal_user; ?></td>
                                <?php endif; ?>
                                <?php if($key == 0): ?>
                                    <td rowspan="<?php echo $rowspan_assign_date; ?>"><?php echo date("Y.n.j", strtotime($assign_date)); ?></td>
                                    <td rowspan="<?php echo $rowspan_assign_date; ?>"><?php echo $rowspan_assign_date; ?></td>
                                <?php endif; ?>
                                <td><?php echo date("Y.n.j", $row["assign_time"] + ($row["deal_time"] + $row["delay_time"]) * 3600); ?></td>
                                <td>#<?php echo $row["id"]; ?></td>
                                <td class="text-left"><?php echo $row["address"]; ?></td>
                                <td class="text-left"><?php echo $row["description"]; ?></td>
                                <td>
                                    <?php if($with_image == "on" && $row["img_paths"]): ?>
                                        <?php $img_paths = explode(",", $row["img_paths"]); ?>
                                        <?php $img_widths = explode(",", $row["img_widths"]); ?>
                                        <?php $img_heights = explode(",", $row["img_heights"]); ?>
                                        <?php foreach($img_paths as $key => $img_path): ?>
                                            <img src="<?php echo $img_path; ?>" width="100" class="img-responsive">
                                            <?php break; // just display one. ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $row["is_delay"] ? "延时{$row["delay_time"]}小时" : "";?></td>
                                <td><?php if ($row['duration_lv'] == 0) echo "未完成"; if ($row['duration_lv'] == 1) echo "准时完成"; if ($row['duration_lv'] == 2) echo "超时完成";?></td>
                                <?php $nav_status = in_array($row['status'], array_keys(Yii::app()->params->sub_nav_status))?$row['status']:999; ?>
                                <td><a href="<?php echo $this->createUrl("/problem/index/view", array("id"=>$row['id'], "nav_status"=>$nav_status));?>" class="btn btn-info" target="_blank">查看</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>


        
    </div>
</div>