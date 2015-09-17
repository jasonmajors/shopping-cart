<h1>Available Products</h1>
<table>
	<tr>
		<th>Product ID</th>
		<th>Product</th>
		<th>Description</th>
		<th>Price</th>
	</tr>
	<?php foreach ($products as $product): ?>
	<tr>
		<td><?php echo $product['Product']['id']; ?></td>
		<td><?php echo $product['Product']['name']; ?></td>
		<td><?php echo $product['Product']['description']; ?></td>
		<td><?php echo $product['Product']['price']; ?></td>
	</tr>
	<?php endforeach; ?>	
</table>
<p><?php echo $this->Html->link('Register', array('controller' => 'users', 'action' => 'add')); ?></p>