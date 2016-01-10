<h1>Kayaks</h1>
<div class="row">
  <?php foreach($featured_category_one as $featured): ?>
    <div class="card">
      <?php echo $this->Html->image('http://placehold.it/500x500', array(
                    'class' => 'img-responsive',
                    'alt' => 'Product Image',
                    'url' => array('controller' => 'products', 'action' => 'view', $featured['Product']['id'])
                    )
                  );
      ?>
      <h4 class="text-center"><?php echo $featured['Product']['name']; ?></h4>
    </div>
  <?php endforeach; ?>
</div>
<h1>Cameras</h1>  
<div class="row">
  <?php foreach($featured_category_two as $featured): ?>
    <div class="card">
      <?php echo $this->Html->image('http://placehold.it/500x500', array(
                    'class' => 'img-responsive',
                    'alt' => 'Product Image',
                    'url' => array('controller' => 'products', 'action' => 'view', $featured['Product']['id'])
                    )
                  );
      ?>
      <h4 class="text-center"><?php echo $featured['Product']['name']; ?></h4>
    </div>
  <?php endforeach; ?>
</div>

<h1>Available Products</h1>
<table class='table'>
    <tr>
        <th>Product</th>
        <th>Description</th>
        <th>Price</th>
        <th>Add to Cart</th>
    </tr>
    <?php foreach ($products as $product): ?>
    <tr>
        <?php $price = $product['Product']['price']; ?>
        <td>
            <?php echo $this->Html->link($product['Product']['name'], array(
                                                    'controller' => 'products', 
                                                    'action' => 'view', 
                                                    $product['Product']['id'])); ?>
        </td>
        <td><?php echo $product['Product']['description']; ?></td>
        <td><?php echo "$$price"; ?></td>
        <td>
            <?php echo $this->Form->create('OrdersProducts', array( 
                                                    'class' => 'form-inline',
                                                    'url' => array('controller' => 'orders', 'action' => 'create'),
                                                    'inputDefaults' => array(
                                                        'label' => false,
                                                    )
                                                )
                                            ); 
                                        ?>
            <div class='form-group'>                            
            <?php echo $this->Form->input('qty'); ?>
            </div>
            <?php echo $this->Form->input('product_id', array('default' => $product['Product']['id'], 'type' => 'hidden')); ?>
            <!-- order_id will be retrieved in the create() method -->
            <div class='form-group'>
            <?php echo $this->Form->end(array(
                                            'label' => 'Add',
                                        )
                                    ); 
                                ?>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>    
</table>
