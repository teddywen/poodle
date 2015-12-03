//页面加载完成执行
$(function(){
	//提交表单
    $(".btn_submit_form").click(function(){
    	submitProblemDealForm('problem_info_form');
    });
    //联动选择单位
    $("#gov_cate_id").change(function(){
        var cate_id = $(this).val();
        if(cate_id.length > 0){
            getCateUsers(cate_id);
        }
    });
    //审核通过处理结果
    $(".btn_solve_qualified").click(function(){
    	$(".problem_info_form").append('<input name="solve_result" value="1" type="hidden" />');
    	submitProblemDealForm('problem_info_form');
    });
    //打回处理结果对话框
    $(".btn_go_solve_unqualified").click(function(){
    	$("#solve_unqualified_modal").modal('show');
    });
    //提交打回表单
    $(".btn_solve_unqualified").click(function(){
    	if($(this).closest("form").find(".problem_log_remark").val().length <= 0){
    		alert("请填写理由");
    		return false;
    	}
    	submitProblemDealForm('solve_unqualified_form');
    });
    //弹出退单理由对话框
    $(".btn_go_back_problem").click(function(){
    	$("#back_problem_modal").modal("show");
    });
    //提交退单表单
    $(".btn_back_problem").click(function(){
    	if($(this).closest("form").find(".problem_log_remark").val().length <= 0){
    		alert("请填写理由");
    		return false;
    	}
    	submitProblemDealForm('back_problem_form');
    });
    //弹出申请延时对话框
    $(".btn_go_delay_problem").click(function(){
    	$("#delay_problem_modal").modal("show");
    });
    //提交延时申请表单
    $(".btn_delay_problem").click(function(){
    	if($(this).closest("form").find(".problem_log_remark").val().length <= 0){
    		alert("请填写理由");
    		return false;
    	}
    	submitProblemDealForm('delay_problem_form');
    });
    //弹出申请联动对话框
    $(".btn_go_assisted_problem").click(function(){
    	$("#assisted_problem_modal").modal("show");
    });
    //提交联动申请表单
    $(".btn_assisted_problem").click(function(){
    	if($(this).closest("form").find(".problem_log_remark").val().length <= 0){
    		alert("请填写理由");
    		return false;
    	}
    	submitProblemDealForm('assisted_problem_form');
    });
    //重新分配联动
    $(".btn_reset_assisted").click(function(){
    	$(".reset_assisted_units").show();
    });
    //判断是否需要联动
    $(".rdi_need_assistant").click(function(){
    	var cur_value = $(this).val();
    	if(cur_value == 1){
    		$(".assisted_units_lists").show();
    	}
    	else{
    		$(".assisted_units_lists").hide();
    	}
    });
    //管理员关闭问题按钮
    $(".btn_close_problem").click(function(){
    	$.ajax({
    		url:"/problem/problemFlow/closeProblem",
    		data:{pid:$("input[name='pid']").val()},
    		dataType:"json",
    		success:function(res){
    			var r_msg = res.msg, r_code = res.code;
                alert(r_msg);
                if(r_code == 1){
                    window.location.reload();
                }
    		}
    	});
    });
//    //弹出检查延时申请对话框
//    $(".btn_go_check_delayapply").click(function(){
//    	$("#check_delay_modal").modal("show");
//    });
//    //提交检查延时申请表单
//    $(".btn_check_delayapply").click(function(){
//    	if($(this).closest("form").find(".problem_log_remark").val().length <= 0){
//    		alert("请填写理由");
//    		return false;
//    	}
//    	submitProblemDealForm('check_delay_form');
//    });
    //弹出检查联动申请对话框
    $(".btn_go_check_assistedapply").click(function(){
    	$("#check_assisted_modal").modal("show");
    });
    //提交检查联动申请表单
    $(".btn_check_assistedapply").click(function(){
    	if($(this).closest("form").find(".problem_log_remark").val().length <= 0){
    		alert("请填写理由");
    		return false;
    	}
    	submitProblemDealForm('check_assisted_form');
    });
});
//页面加载完成执行结束

//选择单位
function getCateUsers(cate_id){
    if($(".option_users_"+cate_id).length > 0){
    	showOptionUsers($(".option_users_"+cate_id).html());
    	return;
    }
    $.ajax({
        url:"/user/index/getCateUsers",
        dataType:"JSON",
        data:{cate_id:cate_id},
        success:function(res){
            // console.log(res);console.log(res.length);
            if(res.length > 0){
            	setOptionUsers(res, cate_id);
            }
            else{
            	$("#gov_users").hide();
                $(".set_deal_time").hide();
                alert("该分类下暂无用户");
            }
        }
    });
}
//设置单位选项
function setOptionUsers(users, cate_id){
    var u_length = users.length, option_users = '';
    for(var i=0;i<u_length;i++){
    	option_users += '<option value="'+users[i].uid+'">'+users[i].username+'</option>';
    }
    $(".deal_user_container").append('<div class="option_users_'+cate_id+'" style="display: none;">'+option_users+'</div>');
    showOptionUsers(option_users);
}
//选择单位选项
function showOptionUsers(user_lists){
	$("#gov_users").html(user_lists);
	$("#gov_users").show();
    $(".set_deal_time").show();
}
//提交问题处理表单
function submitProblemDealForm(submit_form_selector){
	$("."+submit_form_selector).ajaxSubmit({
        dataType:"json",
        success: function(res){
            var r_msg = res.msg, r_code = res.code;
            alert(r_msg);
            if(r_code == 1){
                window.location.reload();
            }
        }
    });
}