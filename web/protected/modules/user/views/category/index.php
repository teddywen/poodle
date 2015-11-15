<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">搜索</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="s_cate_name" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">分类名: </label>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <input class="form-control" id="s_cate_name" name="s_cate_name" value="<?php echo isset($_REQUEST['s_cate_name'])?trim($_REQUEST['s_cate_name']):"";?>" type="text" placeholder="分类名"/>
                        </div>
                    </div>
                    <?php $s_status = isset($_REQUEST['s_status'])?intval($_REQUEST['s_status']):"";?>
                    <div class="form-group">
                        <label for="s_status" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">状态: </label>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <select name="s_status" class="form-control">
                                <option value="">全部</option>
                                <option value="1"<?php if(strlen($s_status)>0&&$s_status==1):?> selected="selected"<?php endif;?>>启用</option>
                                <option value="0"<?php if(strlen($s_status)>0&&$s_status==0):?> selected="selected"<?php endif;?>>禁用</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-xs-offset-2 col-lg-10 col-md-10 col-sm-10 col-xs-10">
                            <button type="submit" class="btn btn-primary">查找</button>
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
                        <a href="<?php echo $this->createUrl("/user/category/update", array("id"=>$list->id, "back_url"=>urlencode(Util::getCurrentUrl())));?>" class="btn btn-primary">修改</a>
                        <?php if($list->status == 1):?>
                            <a href="javascript:void(0);" data-cid="<?php echo $list->id;?>" class="btn btn-danger btn_disable">禁用</a>
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
    </div>
</div>

<?php
    if(ceil($count / $this->PAGE_SIZE) > 1){
        if(file_exists(Yii::app()->basePath.'/views/common/pager.php')){
            require_once(Yii::app()->basePath.'/views/common/pager.php');
        }
    }
?>
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