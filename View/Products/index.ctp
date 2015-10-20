<h1>Available Products</h1>
<table class='table'>
	<tr>
		<th>Product ID</th>
		<th>Product</th>
		<th>Description</th>
		<th>Price</th>
		<th>Add to Cart</th>
	</tr>
	<?php foreach ($products as $product): ?>
	<tr>
		<td><?php echo $product['Product']['id']; ?></td>
		<td>
			<?php echo $this->Html->link($product['Product']['name'], array(
																'controller' => 'products', 
																'action' => 'view', 
																$product['Product']['id'])); ?>
		</td>
		<td><?php echo $product['Product']['description']; ?></td>
		<td><?php echo $product['Product']['price']; ?></td>
		<td>
			<?php echo $this->Form->create('OrdersProducts', array(	
													'class' => 'form-inline',
													'url' => array('controller' => 'orders', 'action' => 'create'),
													'inputDefaults' => array(
														'label' => false,
														//'div' => false
													)
												)
											); 
										?>
			<div class='form-group'>							
			<?php echo $this->Form->input('qty'); ?>
			</div>
			<?php echo $this->Form->input('product_id', array('default' => $product['Product']['id'], 'type' => 'hidden')); ?>
			<!-- order_id will be retrieved in the create() method -->
			<div class='form-group'>
			<?php echo $this->Form->end(array(
											'label' => 'Add',
										)
									); 
								?>
			</div>
		</td>
	</tr>
	<?php endforeach; ?>	
</table>
<p><?php if ($user_id) {
	echo $this->Html->link('View Cart', array('controller' => 'orders', 'action' => 'view'));
} ;?>

<p><?php echo $this->Html->link('Register', array('controller' => 'users', 'action' => 'add')); ?></p>