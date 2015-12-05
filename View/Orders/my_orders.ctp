<?php if (!$orders): ?>
<h1>No order history</h1>
<?php else: ?>
<h1>Your Orders</h1>
<table class='table table-condensed'>
	<tr>
		<?php echo '<th>' . $this->paginator->sort('id', 'Order No.') . '</th>'; ?>
		<?php echo '<th>' . $this->paginator->sort('modified', 'Purchased') . '</th>'; ?>
		<?php echo '<th>' . $this->paginator->sort('shipping_firstname', 'Shipped To') . '</th>'; ?>
		<?php echo '<th>' . $this->paginator->sort('total', 'Total') . '</th>'; ?>
	</tr>
	<?php foreach ($orders as $order): ?>
		<td><?php echo $this->Html->link($order['Order']['id'], array('controller' => 'orders', 'action' => 'viewOrder', $order['Order']['id'])); ?></td>
		<td><?php echo date("F j, Y", strtotime($order['Order']['modified'])); ?></td>
		<td><?php echo $order['Order']['shipping_firstname'] . ' ' . $order['Order']['shipping_lastname'] . '<br>' . $order['Order']['shipping_address'] . '<br>' . $order['Order']['shipping_city'] .  ', ' . $order['Order']['shipping_state'] . ' ' . $order['Order']['shipping_zipcode']; ?></td>
		<td><?php echo '$' . number_format((float)$order['Order']['total'],2, '.', ','); ?></td>
	</tr>
	<?php endforeach; ?>
</table>	

<?php
$paginator = $this->Paginator;
// TODO: Pagination section - works but not happy with the look
// First page button
echo $paginator->first('<< ');
// Previous page if not on first page
if($paginator->hasPrev()) {
    echo $paginator->prev(' < ');
}
// Show the page numbers
echo $this->paginator->numbers();
// Next page if not on last page
if($paginator->hasNext()) {
    echo $paginator->next(' > ');
}
echo $paginator->last(' >>');	
?>

<?php endif; ?>
