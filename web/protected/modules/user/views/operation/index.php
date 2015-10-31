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
            <button type="submit" class="btn btn-primary">查找</button>
        </form>
        <ul class="nav nav-list">
            <li class="divider"></li>
        </ul>
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