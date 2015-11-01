<?php
/* @var $this OperationController */
?>
<!--<h1>--><?php //echo $this->id . '/' . $this->action->id; ?><!--</h1>-->

<div class="container-fluid">
<div class="row">
	<div class="col-md-12">
		<h3 class="text-info">
			用户操作列表
		</h3>

        <form class="form-search form-inline">
            <div class="text-center">
            用户名：<input type="text" name="u_name_value" class="form-control input-small" <?php if($u_name_value):?>value="<?php echo $u_name_value?>"<?php endif;?>>
            用户所属单位：<select name="u_cate_value" class="form-control input-small">
                <option value="" <?php if($u_cate_value == ""):?>selected="selected"<?php endif;?>>全部</option>
                <?php foreach($cates as $cat):?>
                    <option value="<?php echo $cat->id;?>" <?php if($u_cate_value == $cat->id):?>selected="selected"<?php endif;?>>
                        <?php echo $cat->cate_name;?>
                    </option>
                <?php endforeach;?>
            </select>
            用户类型：<select name="u_type_value" class="form-control input-medium">
                <option value="" <?php if($u_type_value == ""):?>selected="selected"<?php endif;?>>全部</option>
                <?php foreach($u_types as $u_id => $u_type):?>
                    <option value="<?php echo $u_id;?>" <?php if($u_type_value == $u_id):?>selected="selected"<?php endif;?>>
                        <?php echo $u_type;?>
                    </option>
                <?php endforeach;?>
            </select>
            操作类型：<select name="op_type_value" class="form-control input-medium">
                <option value="" <?php if($op_type_value == ""):?>selected="selected"<?php endif;?>>全部</option>
                <?php foreach($op_type_list as $op_id => $op_type):?>
                    <option value="<?php echo $op_id;?>" <?php if($op_type_value == $op_id):?>selected="selected"<?php endif;?>>
                        <?php echo $op_type;?>
                    </option>
                <?php endforeach;?>
            </select>
            <div>
            <div class="div-margin-top-10 text-center">
                <span>起始日期：</span>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'date_from',
                    'value' => (isset($date_from) && $date_from) ? $date_from : '',
                    'language' => 'zh',
                    'htmlOptions' => array(
                        'size' => '10',         // textField size
                        'maxlength' => '10',    // textField maxlength
                        'class' => 'input-small',
                        'id' => 'date_from',
                    ),
                ));
                ?>
                <span>结束日期：</span>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'date_to',
                    'value' => (isset($date_to) && $date_to) ? $date_to : '',
                    'language' => 'zh',
                    'htmlOptions' => array(
                        'size' => '10',         // textField size
                        'maxlength' => '10',    // textField maxlength
                        'class' => 'input-small',
                        'id' => 'date_to',
                    ),
                ));
                ?>
            </div>
            <div class="div-margin-top-10 col-md-12">
                <div class="text-center">
                    <a class="btn btn-danger" href="<?php echo Yii::app()->baseUrl;?>/user/operation">重置</a>
                    <input type="submit" id="filter-submit" class="btn btn-primary"  onclick="return false" value="查找">
                </div>
            </div>
        </form>
        <ul class="nav nav-list">
            <li class="divider"></li>
        </ul>
        <div class="operation-log-customer-alert alert alert-danger alert-dismissible fade in" role="alert">
<!--            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>-->
            <strong>结束日期需大于等于起始日期！</strong>
        </div>
		<table class="table table-hover table-bordered">
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
	</div>
</div>
</div>

<script type="text/javascript">
    $(function(){
        //validate date_from and date_to
        $("#filter-submit").click(function() {
            var _dateFrom, _dateTo;
            _dateFrom = $("#date_from").val();
            _dateTo = $("#date_to").val();

            if(_dateFromGreatorThanDateTo(_dateFrom, _dateTo)) {
                $('.operation-log-customer-alert').show();
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