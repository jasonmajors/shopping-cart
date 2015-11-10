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
		<td><?php echo $order['Order']['address'] . '<br>' . $order['Order']['city'] . '<br>' . $order['Order']['zipcode']; ?></td>
		<td><?php echo '$' . number_format((float)$order['Order']['total'],2, '.', ','); ?></td>
	</tr>
	<?php endforeach; ?>
</table>		