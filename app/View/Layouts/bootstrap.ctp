<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
		<?php echo $this->fetch('title'); ?>

	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('bootstrap.min.css');
		echo $this->Html->css('sticky-footer.css');	
	?>
</head>
<body>
	
	<?php echo $this->element('navbar'); ?>
	<div class="container">
		<?php echo $this->fetch('content'); ?>
	</div>
	<footer class="footer">
		<div class="container">
		<?php echo $this->element('contact'); ?>
		</div>
	</footer>
</body>
<?php 
	echo $this->Html->script('jquery');
	echo $this->Html->script('bootstrap.min'); 
	
?>
</html>