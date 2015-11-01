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
    	submitProblemDealForm('solve_unqualified_form');
    });
    //弹出退单理由对话框
    $(".btn_go_back_problem").click(function(){
    	$("#back_problem_modal").modal("show");
    });
  //提交退单表单
    $(".btn_back_problem").click(function(){
    	submitProblemDealForm('back_problem_form');
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
            console.log(res);console.log(res.length);
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