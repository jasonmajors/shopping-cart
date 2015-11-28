<?php if (!$orders): ?>
<h1>No order history</h1>
<?php else: ?>
<h1>Your Orders</h1>
<table class='table'>
	<tr>
		<th>Order No.</th>
		<th>Date of purchase</th>
		<th>Shipped to</th>
		<th>Total</th>
	</tr>
	<?php foreach ($orders as $order): ?>
		<td><?php echo $this->Html->link($order['Order']['id'], array('controller' => 'orders', 'action' => 'viewOrder', $order['Order']['id'])); ?></td>
		<td><?php echo date("F j, Y", strtotime($order['Order']['modified'])); ?></td>
		<td><?php echo $order['Order']['shipping_firstname'] . ' ' . $order['Order']['shipping_lastname'] . '<br>' . $order['Order']['shipping_address'] . '<br>' . $order['Order']['shipping_city'] .  ', ' . $order['Order']['shipping_state'] . ' ' . $order['Order']['shipping_zipcode']; ?></td>
		<td><?php echo '$' . number_format((float)$order['Order']['total'],2, '.', ','); ?></td>
	</tr>
	<?php endforeach; ?>
</table>		
<?php endif; ?>