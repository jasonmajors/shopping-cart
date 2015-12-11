<!-- Display product img -->
<div class='col-md-6'>
<?php echo $this->Html->image('testplaceholder.png', array('alt' => 'Product Image')); ?>
<!-- end product img -->
</div>
<div class='col-md-6'>
    <h1><?php echo $product['Product']['name']; ?></h1>
    <p>Price: $<span><?php echo number_format((float)$product['Product']['price'],2, '.', ','); ?></span></p>
    <!-- Product highlights -->
    <ul>
        <?php 
        // Check to see if the Product has a highlight field (max is 5)
        // If the highlight field is set, echo it out as a list item
        for ($i = 1; $i <= 5; $i++) {
            $index = 'highlight' . (string)$i;
            if (isset($product['Product'][$index])) {
                echo '<li>' . $product['Product'][$index] . '</li>';
            } 
        }
        ?>
    </ul>

    <h3>Product Description</h3>
    <p><?php echo $product['Product']['description']; ?></p>

    <!-- Add to cart -->
    <?php echo $this->Form->create('OrdersProducts', array( 
                                                        'class' => 'form-inline',
                                                        'url' => array('controller' => 'orders', 'action' => 'create'),
                                                        'inputDefaults' => array(
                                                            'label' => false,
                                                        )
                                                    )
                                                ); ?>
    <div class='form-group'>                            
    <?php echo $this->Form->input('qty', array('label' => 'Qty:', 'default' => 1)); ?>
    </div>
    <?php echo $this->Form->input('product_id', array('default' => $product['Product']['id'], 'type' => 'hidden')); ?>
    <!-- order_id will be retrieved in the create() method -->
    <div class='form-group'>
    <?php echo $this->Form->end(array(
                                    'label' => 'Add to Cart',
                                )
                            ); 

    ?>
    </div>
</div>
<!-- End cart -->