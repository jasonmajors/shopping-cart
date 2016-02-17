<h1>Available Products</h1>
<table class='table'>
    <tr>
        <?php echo '<th>' . $this->paginator->sort('name', 'Product') . '</th>'; ?>
        <?php echo '<th>' . $this->paginator->sort('description', 'Description') . '</th>'; ?>
        <?php echo '<th>' . $this->paginator->sort('price', 'Price') . '</th>'; ?>
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