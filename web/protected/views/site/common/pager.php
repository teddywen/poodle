<?php
    $start_num = $page * $this->PAGE_SIZE + 1;
    $end_num = $start_num + $this->PAGE_SIZE;
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
    $(".pagination").bootstrapPaginator({currentPage: <?php echo $page;?>,totalPages: <?php echo $count;?>,numberOfPages:10});
    
</script>