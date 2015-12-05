<h1>Available Products</h1>
<table class='table'>
    <tr>
        <?php echo '<th>' . $paginator->sort('name', 'Product') . '</th>'; ?>
        <?php echo '<th>' . $paginator->sort('description', 'Description') . '</th>'; ?>
        <?php echo '<th>' . $paginator->sort('price', 'Price') . '</th>'; ?>
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