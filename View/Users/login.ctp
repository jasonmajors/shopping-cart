<?php echo $this->Form->create('User', array(
										'class' => 'form-signin',
										'inputDefaults' => array(
											'div' => False,
											'label' => False,
										)
									)
								); 
							?>
<fieldset>
		<h2 class="form-signin-heading">Please sign in</h2>
		<label for="UserEmail" class="sr-only">Email Address</label>
		<?php echo $this->Form->input('email', array(
													'class' => 'form-control',
													'placeholder' => 'Email Address'
												)
											); 
										?>

		<label for="UserPassword" class="sr-only">Password</label>									
		<?php echo $this->Form->input('password', array(
													'class' => 'form-control',
													'placeholder' => 'Password'
												)
											); 
										?>

</fieldset>	
<?php echo $this->Form->submit('Sign in', array(
										'class' => 'btn btn-lg btn-primary btn-block'
									)
								); ?>
<h5 class="text-right"><?php echo $this->Html->link('No account? Register', array('controller' => 'users', 'action' => 'add')); ?></h5>