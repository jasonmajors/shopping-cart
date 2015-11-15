<h1>Checkout</h1>
<h2>Your items</h2>
<table class='table'>
	<tr>
		<th>Product</th>
		<th>Description</th>
		<th>Price</th>
		<th>Qty</th>
	</tr>
	<?php foreach ($order['Product'] as $product): ?>
	<tr>
		<td><?php echo $product['name']; ?></td>
		<td><?php echo $product['description']; ?></td>
		<td><?php echo $product['price'];	?></td>
		<td><?php echo $product['OrdersProducts']['qty']; ?></td>
	</tr>
	<?php endforeach; ?>	
</table>
<p><?php echo "Total: $$total"; ?></p>
<h2>Billing Information</h2>
<?php 
	/* Array of the state initials for the dropdown select menu
	$states = array(

		)
	*/
?>
<?php 
	echo $this->Form->create('Order'); 
	echo $this->Form->input('address');
	echo $this->Form->input('city');
	echo $this->Form->input('state');
	echo $this->Form->input('zipcode');
	echo $this->Form->end('Place Order');
?>

