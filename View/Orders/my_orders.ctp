<?php print_r($orders); ?>
<h1>Your Orders</h1>
<table>
	<tr>
		<th>Order No.</th>
		<th>Date of purchase</th>
		<th>Shipped to</th>
	</tr>
	<?php foreach ($orders as $order): ?>
		<td><?php echo $order['Order']['id']; ?></td>
		<td><?php echo date("F j, Y", strtotime($order['Order']['modified'])); ?></td>
		<td><?php echo $order['Order']['address'] . '<br>' . $order['Order']['city'] . '<br>' . $order['Order']['zipcode']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>		