<h1>Checkout</h1>
<h1>Review Your Order</h1>
<table>
	<tr>
		<th>Product ID</th>
		<th>Product</th>
		<th>Description</th>
		<th>Price</th>
		<th>Qty</th>
	</tr>
	<?php foreach ($order['Product'] as $product): ?>
	<tr>
		<td><?php echo $product['id']; ?></td>
		<td><?php echo $product['name']; ?></td>
		<td><?php echo $product['description']; ?></td>
		<td><?php echo $product['price'];	?></td>
		<td><?php echo $product['OrdersProducts']['qty']; ?></td>
	</tr>
	<?php endforeach; ?>	
</table>
<p><?php echo "Total: $$total"; ?></p>
<h1>Please enter a shipping address</h1>
<?php 
	echo $this->Form->create('Order'); 
	echo $this->Form->input('address');
	echo $this->Form->input('city');
	echo $this->Form->input('state');
	echo $this->Form->input('zipcode');
	echo $this->Form->end('Place Order');
?>

