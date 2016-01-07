<div class='row-fluid'>
    <!-- Display product img -->
    <div class='col-lg-4'>
    <?php echo $this->Html->image($product['Product']['id'] . '.jpg', array(
                                                                    'alt' => 'Product Image',
                                                                    'class' => 'img-responsive')
                                                                ); ?>
    <!-- end product img -->
    </div>
    <div class='col-lg-6'>
        <h1><?php echo $product['Product']['name']; ?></h1>
        <p>Price: $<span><?php echo number_format((float)$product['Product']['price'],2, '.', ','); ?></span></p>
        <!-- Product highlights -->
        <ul>
            <?php 
            // Check to see if the Product has a highlight field (max is 5)
            // If the highlight field is set, echo it out as a list item
            for ($i = 1; $i <= 5; $i++) {
                // Product table has them stored as 'highlight1', 'highlight2', etc.
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
</div>
<!-- End cart -->