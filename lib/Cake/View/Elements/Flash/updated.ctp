<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
?>
<div id="<?php echo h($class) ?>Message" class="<?php echo h($class) ?>">
	<?php echo $this->Html->link(h($message), array(
												'controller' => 'orders',
												'action' => 'view'
											)
										); 
	?>
</div>