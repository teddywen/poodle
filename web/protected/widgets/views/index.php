<?php if(is_array($this->links) && !empty($this->links)): ?>
    <ol class="breadcrumb">
        <?php $loop = 0; ?>
        <?php $count = count($this->links); ?>
        <?php foreach ($this->links as $key => $value): ?>
            <?php if(++$loop == $count): ?>
                <li class="active"><?php echo $value; ?></li>
            <?php elseif(is_int($key)): ?>
                <li><a href="#"><?php echo $value; ?></a></li>
            <?php elseif(is_array($value)): ?>
                <?php $url = array_shift($value);?>
                <li><a href="<?php echo Yii::app()->getController()->createUrl($url, $value); ?>"><?php echo $key; ?></a></li>
            <?php else: ?>
                <li><a href="<?php echo $value; ?>"><?php echo $key; ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
<?php endif; ?>