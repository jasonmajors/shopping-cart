<div class='col-md-4'>
<?php echo $this->Form->create('User', array('class' => 'form-horizontal')); ?>
<fieldset>
	<legend><?php echo __('Register'); ?></legend>
	<div class='form-group'>
		<?php echo $this->Form->input('firstname', array('label' => 'First Name', 'class' => 'form-control')); ?>
	</div>
	<div class='form-group'>
		<?php echo $this->Form->input('lastname', array('label' => 'Last Name', 'class' => 'form-control')); ?>
	</div>
	<div class='form-group'>
		<?php echo $this->Form->input('email', array('label' => 'Email', 'class' => 'form-control')); ?>
	</div>
	<div class='form-group'>
		<?php echo $this->Form->input('address', array('label' => 'Street Address', 'class' => 'form-control')); ?>
	</div>
	<div class='form-group'>
		<?php echo $this->Form->input('city', array('label' => 'City', 'class' => 'form-control')); ?>
	</div>
	<div class='form-group'>
		<?php echo $this->Form->input('state', array('label' => 'State', 'class' => 'form-control')); ?>
	</div>
	<div class='form-group'>
		<?php echo $this->Form->input('zipcode', array('label' => 'Zipcode', 'class' => 'form-control')); ?>
	</div>
	<div class='form-group'>
		<?php echo $this->Form->input('password', array('label' => 'Password', 'class' => 'form-control')); ?>
	</div>

</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>