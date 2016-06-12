<?php
if (empty($params['class']))
	$class = 'alert alert-info';
else if ($params['class'] == 'success')
    $class = 'alert alert-success';
else if ($params['class'] == 'error')
	$class = 'alert alert-danger';
else
	$class = 'alert alert-info';
?>
<div class="<?= $class ?>"><?= h($message) ?></div>
