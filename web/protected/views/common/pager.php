<?php $max_page_num = ceil($count / $this->PAGE_SIZE);?>
<?php if($max_page_num > 1):?>
<?php
    $start_num = ($page - 1) * $this->PAGE_SIZE + 1;
    $end_num = $page * $this->PAGE_SIZE;
    $end_num = $end_num>$max_page_num?$max_page_num:$end_num;
?>
<div>
总共<?php echo $count;?>条/<?php echo $max_page_num;?>页，当前第<?php echo $page;?>页
</div>
<div class="pagination pagination-large">
	<ul class="page_lists">
		<li>
			<a href="javascript:void(0);">上一页</a>
		</li>
        <?php for($i=$start_num;$i<=$end_num;$i++):?>
		<li>
			<a href="javascript:void(0);"><?php echo $i;?></a>
		</li>
        <?php endfor;?>
		<li>
			<a href="javascript:void(0);">下一页</a>
		</li>
	</ul>
</div>
<script type="text/javascript">
    $(function(){
        var options = {
    		currentPage: <?php echo $page;?>,
    		totalPages: <?php echo $max_page_num;?>,
			numberOfPages:10,
			pageUrl: function(type, page, current){
				var cur_url = window.location.href;
	            var forward_url = cur_url;
	            if(cur_url.indexOf('?') < 0){
	            	forward_url = forward_url+'?page='+page;
	            }
	            else{
	            	if(cur_url.indexOf('page') < 0){
	            		forward_url = forward_url + "&page="+page;
	            	}
	            	else{
	            		forward_url = forward_url.replace("page=<?php echo $page;?>","page=" + page); 
	            	}
	            }
			    return forward_url;
			}
        };
        $(".pagination").bootstrapPaginator(options);
    });
</script>
<?php endif;?>