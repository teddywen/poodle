<?php $max_page_num = ceil($count / $this->PAGE_SIZE);?>
<?php if($max_page_num > 1):?>
<?php
    $start_num = ($page - 1) * $this->PAGE_SIZE + 1;
    $end_num = $page * $this->PAGE_SIZE;
    $end_num = $end_num>$max_page_num?$max_page_num:$end_num;
?>
<div class="pagination pagination-large">
	<ul class="page_lists">
		<li>
			<a href="javascript:void(0);" data-page="<?php echo $start_num - 1;?>">上一页</a>
		</li>
        <?php for($i=$start_num;$i<=$end_num;$i++):?>
		<li>
			<a href="javascript:void(0);" data-page="<?php echo $i;?>"><?php echo $i;?></a>
		</li>
        <?php endfor;?>
		<li>
			<a href="javascript:void(0);" data-page="<?php echo $end_num - 1;?>">下一页</a>
		</li>
	</ul>
</div>
<script type="text/javascript">
    $(function(){
        var options = {
    		currentPage: <?php echo $page;?>,
    		totalPages: <?php echo $count;?>,
			numberOfPages:10,
			pageUrl: function(type, page, current){
				var cur_url = window.location.href;
	            var cur_page = $(this).attr(data-page);
	            var forward_url = cur_url.replace("page=<?php echo $page;?>","page=" + page); 
			    return forward_url;
			}
        };
    });
</script>
<?php endif;?>