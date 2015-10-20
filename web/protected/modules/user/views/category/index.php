<div class="span10">
	<form class="form-search form-inline">
		<input class="input-medium search-query" type="text" /> <button type="submit" class="btn">查找</button>
	</form>
	<table class="table table-condensed table-bordered table-hover">
		<thead>
			<tr>
				<th>
					编号
				</th>
				<th>
					名称
				</th>
				<th>
					状态
				</th>
				<th>
					创建时间
				</th>
				<th>
					修改时间
				</th>
				<th>
					操作
				</th>
			</tr>
		</thead>
		<tbody>
            <?php if(!empty($lists)):?>
            <?php foreach($lists as $list):?>
			<tr>
				<td>
					<?php echo $list->id;?>
				</td>
				<td>
					<?php echo $list->cate_name;?>
				</td>
				<td>
					<?php echo $list->status==1?'开启':'禁用';?>
				</td>
				<td>
					<?php echo date('Y-m-d H:i:s', $list->create_time);?>
				</td>
				<td>
					<?php echo date('Y-m-d H:i:s', $list->update_time) ;?>
				</td>
				<td>
					...
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
	<?php if(ceil($count / $this->PAGE_SIZE) > 0):?>
	<div class="pagination pagination-large">
		<ul>
			<li>
				<a href="#">上一页</a>
			</li>
			<li>
				<a href="#">1</a>
			</li>
			<li>
				<a href="#">2</a>
			</li>
			<li>
				<a href="#">3</a>
			</li>
			<li>
				<a href="#">4</a>
			</li>
			<li>
				<a href="#">5</a>
			</li>
			<li>
				<a href="#">下一页</a>
			</li>
		</ul>
	</div>
	<?php endif;?>
</div>