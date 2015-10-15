<h1>Available Products</h1>
<table>
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
		<td><?php echo $product['Product']['name']; ?></td>
		<td><?php echo $product['Product']['description']; ?></td>
		<td><?php echo $product['Product']['price']; ?></td>
		<td><?php echo $this->Form->create('OrdersProducts', 
											array('url' => 
												array('controller' => 'orders', 'action' => 'create')
											)
										); ?>
			<?php echo $this->Form->input('qty'); ?>
			<?php echo $this->Form->input('product_id', array('default' => $product['Product']['id'], 'type' => 'hidden')); ?>
			<?php echo $this->Form->end('Add'); ?>
		</td>
		<!-- <td><?php echo $this->Html->link('Add', array('controller' => 'orders', 'action' => 'create', $product['Product']['id'])); ?> -->
	</tr>
	<?php endforeach; ?>	
</table>
<p><?php if ($user_id) {
	echo $this->Html->link('View Cart', array('controller' => 'orders', 'action' => 'view', $user_id));
} ;?>

<p><?php echo $this->Html->link('Register', array('controller' => 'users', 'action' => 'add')); ?></p>