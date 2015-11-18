<?php if ($empty): ?>
	<h2>Your cart is empty</h2>
	<h4><?php echo $this->Html->link('Return to shopping', array('controller' => 'products', 'action' => 'index')); ?></h4>
<?php else: ?>
	<h1>Your Shopping Cart</h1>
	<table class='table'>
		<tr>
			<th>Product ID</th>
			<th>Product</th>
			<th>Description</th>
			<th>Price</th>
			<th>Qty</th>
			<th>Remove</th>
		</tr>
		<?php foreach ($order['Product'] as $product): ?>
		<tr>
			<td><?php echo $product['id']; ?></td>
			<td><?php echo $product['name']; ?></td>
			<td><?php echo $product['description']; ?></td>
			<td><?php echo '$' . $product['price'];	?></td>
			<td><?php echo $product['OrdersProducts']['qty']; ?></td>
			<td><?php echo $this->Html->link('Remove', array('controller' => 'orders', 'action' => 'deleteEntry', $order['Order']['id'], $product['id'])); ?></td>
		</tr>
		<?php endforeach; ?>	
	</table>
	<p class='text-right'><?php echo "Tax: $$tax"; ?></p>
	<p class='text-right'>Shipping: $0.00</p>
	<p class='text-right'><?php echo "Total: $$total"; ?></p>
	<h4><?php echo $this->Html->link('Continue to checkout', array('controller' => 'orders', 'action' => 'checkOut', $order['Order']['id'])); ?></h4>
<?php endif; ?>