<?php $calendar_icon = Yii::app()->params->image_url.'/calendar.gif'; ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">搜索</h3>
            </div>
            <div class="panel-body">
                <div class="row operation-log-customer-alert hidden">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="alert alert-warning alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>提示!</strong>结束日期需大于等于起始日期！
                        </div>
                    </div>
                </div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="u_name_value" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">用户名: </label>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <input class="form-control" id="u_name_value" name="u_name_value" value="<?php echo $u_name_value ? $u_name_value : "";?>" type="text" placeholder="用户名"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="u_cate_value" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">用户所属单位: </label>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                           <select name="u_cate_value" class="form-control">
                                <option value="" <?php if($u_cate_value == ""):?>selected="selected"<?php endif;?>>全部</option>
                                <?php foreach($cates as $cat):?>
                                    <option value="<?php echo $cat->id;?>" <?php if($u_cate_value == $cat->id):?>selected="selected"<?php endif;?>>
                                        <?php echo $cat->cate_name;?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="op_type_value" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">操作类型: </label>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <select name="op_type_value" class="form-control">
                                <option value="" <?php if($op_type_value == ""):?>selected="selected"<?php endif;?>>全部</option>
                                <?php foreach($op_type_list as $op_id => $op_type):?>
                                    <option value="<?php echo $op_id;?>" <?php if($op_type_value == $op_id):?>selected="selected"<?php endif;?>>
                                        <?php echo $op_type;?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">操作日期: </label>
                        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                            <div class="form-control-static">
                                <?php
                                    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                                        'attribute' => 'visit_time',
                                        'language' => 'zh_cn',
                                        'name' => 'u_date_from',
                                        'value' => (isset($u_date_from) && $u_date_from) ? $u_date_from : '',
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
                                        'name' => 'u_date_to',
                                        'value' => (isset($u_date_to) && $u_date_to) ? $u_date_to : '',
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
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2 col-lg-10 col-md-10 col-sm-10 col-xs-10">
                            <button id="filter-submit" onclick="return false" type="submit" class="btn btn-primary">查找</button>
                            <a class="btn btn-danger second-btn" href="<?php echo $this->createUrl("/user/operation");?>">重置</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>
                    用户名
                </th>
                <th>
                    所属单位分类
                </th>
                <th>
                    用户类型
                </th>
                <th>
                    操作类型
                </th>
                <th>
                    操作时间
                </th>
                <th>
                    其他
                </th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($op_log_list)):?>
            <?php foreach($op_log_list as $list):?>
            <tr>
                <td>
                    <?php echo $list->users->username;?>
                </td>
                <td>
                    <?php echo $list->users->gov_cate_name;?>
                </td>
                <td>
                    <?php echo $u_types[$list->users->u_type];?>
                </td>
                <td>
                    <?php echo $op_type_list[$list->op_type];?>
                </td>
                <td>
                    <?php echo $list->op_time;?>
                </td>
                <td>
                    <?php echo $list->op_markup;?>
                </td>
            </tr>
            <?php endforeach;?>
            <?php else:?>
                <tr>
                    <td colspan="6" class="text-center">
                        暂无数据...
                    </td>
                </tr>
            <?php endif;?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        //validate date_from and date_to
        $("#filter-submit").click(function() {
            var _dateFrom, _dateTo;
            _dateFrom = $("#u_date_from").val();
            _dateTo = $("#u_date_to").val();

            if(_dateFromGreatorThanDateTo(_dateFrom, _dateTo)) {
                $('.operation-log-customer-alert').removeClass("hidden");
                return false;
            }
            $('form').submit();
        });
    });


    function _dateFromGreatorThanDateTo(dateFrom, dateTo) {
        var _dateFrom, _dateTo;
        _dateFrom = new Date(dateFrom);
        _dateTo = new Date(dateTo);
        return (_dateTo.getTime()) < (_dateFrom.getTime());
    }
</script>