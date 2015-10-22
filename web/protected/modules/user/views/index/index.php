<div class="span10">
	<div class="row" style="margin-bottom: 20px;">
		<div class="span2" style="width: 100px;">
            <a class="btn btn-block btn-info" href="<?php echo Yii::app()->baseUrl?>/user/index/create">新建用户</a>
		</div>
	</div>
	<ul class="nav nav-list"> 
         <li class="divider"></li>  
    </ul>
	<form class="form-search form-inline">
		分类名：<input class="input-medium search-query" name="s_cate_name" value="<?php echo isset($_REQUEST['s_cate_name'])?trim($_REQUEST['s_cate_name']):"";?>" type="text" />
		<?php $s_status = isset($_REQUEST['s_status'])?intval($_REQUEST['s_status']):"";?>
		状态：<select name="s_status" class="form-control input-medium">
                <option value="">全部</option>
                <option value="1"<?php if(strlen($s_status)>0&&$s_status==1):?> selected="selected"<?php endif;?>>启用</option>
                <option value="0"<?php if(strlen($s_status)>0&&$s_status==0):?> selected="selected"<?php endif;?>>禁用</option>
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
					用户名
				</th>
				<th>
					分类名称
				</th>
				<th>
					用户类型
				</th>
				<th>
					状态
				</th>
				<th>
					最后登录时间
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
					<?php echo $list->username;?>
				</td>
				<td>
					<?php echo $list->gov_cate_name;?>
				</td>
				<td>
					<?php
					   $u_type_str = isset(Yii::app()->params['gov_user_type'][$list->u_type])?Yii::app()->params['gov_user_type'][$list->u_type]:"";
					   echo $u_type_str;
				   ?>
				</td>
				<td>
					<?php echo $list->status==1?'开启':'禁用';?>
				</td>
				<td>
					<?php echo date('Y-m-d H:i:s', $list->last_login_time);?>
				</td>
				<td>
					<?php echo date('Y-m-d H:i:s', $list->create_time);?>
				</td>
				<td>
					<?php echo date('Y-m-d H:i:s', $list->update_time) ;?>
				</td>
				<td>
				    <a href="<?php echo Yii::app()->baseUrl?>/user/category/update?id=<?php echo $list->id;?>" class="btn btn-primary">修改</a>
					<?php if($list->status == 1):?>
					<a href="javascript:void(0);" data-cid="<?php echo $list->id;?>" class="btn btn-warning btn_disable">禁用</a>
					<?php else:?>
					<a href="javascript:void(0);" data-cid="<?php echo $list->id;?>" class="btn btn-success btn_enable">启用</a>
					<?php endif;?>
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
<script type="text/javascript">
    $(function(){
        //禁用按钮
        $(".btn_disable").click(function(){
            if(confirm("确定禁用此分类？")){
                var cid = $(this).attr("data-cid");
                changeCateStatus(cid, 0);
            }
        });
        //启用按钮
        $(".btn_enable").click(function(){
            if(confirm("确定启用此分类？")){
                var cid = $(this).attr("data-cid");
                changeCateStatus(cid, 1);
            }
        });
    });
    function changeCateStatus(cid, status){
        $.ajax({
            url:"/user/category/changeStatus",
            dataType:"json",
            data:{cid:cid,status:status},
            success:function(res){
                var r_code = res.code;
                var r_msg = res.msg;
                if(r_code == 1){
                    alert(r_msg);
                    window.location.reload();
                }
                else{
                    alert(r_msg);
                }
            }
        });
    }
</script>