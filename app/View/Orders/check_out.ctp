<?php include 'states.php'; ?>
<h1>Checkout</h1>
<h2>Your items</h2>
<table class='table'>
	<tr>
		<th>Product</th>
		<th>Description</th>
		<th>Price</th>
		<th>Qty</th>
		<th>Item Total</th>
	</tr>
	<?php foreach ($order['Product'] as $product): ?>
	<tr>
		<td><?php echo $product['name']; ?></td>
		<td><?php echo $product['description']; ?></td>
		<td><?php echo '$' . $product['price'];	?></td>
		<td><?php echo $product['OrdersProducts']['qty']; ?></td>
		<td><?php echo '$' . number_format((float)($product['OrdersProducts']['qty'] * $product['price']),2, '.', ','); ?></td>
	</tr>
	<?php endforeach; ?>	
</table>
<p class='text-right'>Subtotal: $<?php echo $order_totals['subtotal']; ?></p>
<p class='text-right'>Tax: $<?php echo $order_totals['tax']; ?></p>
<p class='text-right'>Shipping: $0.00</p>
<p class='text-right'>Total: $<?php echo $order_totals['total']; ?></p>
<!-- Shipping Information -->
<h2>Shipping Information</h2>
<p class='text-muted'>Please verify your shipping information</p>
<div class='col-md-4'>
		<?php echo $this->Form->create('Order'); ?>
	<div class='form-group'> 
		<?php echo $this->Form->input('Order.shipping_firstname', array('label' => 'First Name', 'class' => 'form-control')); ?>
	</div>
	<div class='form-group'>
		<?php echo $this->Form->input('Order.shipping_lastname', array('label' => 'Last Name', 'class' => 'form-control')); ?>
	</div>
</div>
<div class='col-md-4'>	
	<div class='form-group'> 
		<?php echo $this->Form->input('Order.shipping_address', array('label' => 'Address', 'class' => 'form-control')); ?>
	</div>
	<div class='form-group'>
		<?php echo $this->Form->input('Order.shipping_city', array('label' => 'City', 'class' => 'form-control')); ?>
	</div>
</div>
<div class='col-md-4'>	
	<div class='form-group'>
		<?php echo $this->Form->input('Order.shipping_state', array(
																'options' => $states,
																'label' => 'State', 
																'class' => 'form-control'
															)
														); ?>
	</div>
	<div class='form-group'>
		<?php echo $this->Form->input('Order.shipping_zipcode', array('label' => 'Zipcode', 'class' => 'form-control')); ?>
	</div>
</div>
<!-- Billing Information -->
<h2>Billing Information</h2>
<p class='text-muted'>Please verify your billing information</p>
<div class='col-md-4'>
	<div class='form-group'>
		<?php echo $this->Form->input('Order.billing_firstname', array('label' => 'First Name', 'class' => 'form-control')); ?>
	</div>
	<div class='form-group'>
		<?php echo $this->Form->input('Order.billing_lastname', array('label' => 'Last Name', 'class' => 'form-control')); ?>
	</div>
</div>
<div class='col-md-4'>	
	<div class='form-group'> 
		<?php echo $this->Form->input('Order.billing_address', array('label' => 'Address', 'class' => 'form-control')); ?>
	</div>
	<div class='form-group'>
		<?php echo $this->Form->input('Order.billing_city', array('label' => 'City', 'class' => 'form-control')); ?>
	</div>
</div>
<div class='col-md-4'>	
	<div class='form-group'>
		<?php echo $this->Form->input('Order.billing_state', array(
																'options' => $states,
																'label' => 'State', 
																'class' => 'form-control'
															)
														); ?>
	</div>
	<div class='form-group'>
		<?php echo $this->Form->input('Order.billing_zipcode', array('label' => 'Zipcode', 'class' => 'form-control')); ?>
	</div>
	<div class='form-group pull-right'>
		<input class="btn btn-lg btn-primary" type="submit" value="Place Order">
	</div>
</div>