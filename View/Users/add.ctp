<?php echo $this->Form->create('User'); ?>
<fieldset>
	<legend><?php echo __('Register'); ?></legend>
	<?php 	
		echo $this->Form->input('firstname', array('label' => 'First Name'));
		echo $this->Form->input('lastname', array('label' => 'Last Name'));
		echo $this->Form->input('email');
		echo $this->Form->input('address');
		echo $this->Form->input('city');
		echo $this->Form->input('state');
		echo $this->Form->input('zipcode');
		echo $this->Form->input('password');
	?>	
</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>