<?php print_r($order); ?>
<h1>Your Shopping Cart</h1>
<table>
	<tr>
		<th>Product ID</th>
		<th>Product</th>
		<th>Description</th>
		<th>Price</th>
		<th>Qty</th>
	</tr>
	<?php foreach ($order[0]['Product'] as $product): ?>
	<tr>
		<td><?php echo $product['id']; ?></td>
		<td><?php echo $product['name']; ?></td>
		<td><?php echo $product['description']; ?></td>
		<td><?php echo $product['price']; ?></td>
		<td><?php echo $product['OrdersProducts']['qty']; ?></td>
	</tr>
	<?php endforeach; ?>	
</table>
<?php echo "Total: $$total"; ?>