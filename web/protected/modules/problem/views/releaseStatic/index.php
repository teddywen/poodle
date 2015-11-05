<?php ?>
<?php
    $calendar_icon = Yii::app()->params->image_url.'/calendar.gif';
    $start_time = date('Y-m-01'); $end_time = date('Y-m-d');
    //创建时间
    $assign_start_time = isset($_GET['assign_start_time'])?$_GET['assign_start_time']:$start_time;
    $assign_end_time = isset($_GET['assign_end_time'])?$_GET['assign_end_time']:$end_time;
?>
<form class="form-search form-inline">
    <div style="margin-bottom: 10px;">
    	分配时间：
    	<?php
            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                'attribute' => 'visit_time',
                'language' => 'zh_cn',
                'name' => 'assign_start_time',
                'value' => $assign_start_time,
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
                'name' => 'assign_end_time',
                'value' => $assign_end_time,
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
        <button type="submit" class="btn btn-success" name="explode_csv" value="1">导出汇总</button>
    </div>
</form>