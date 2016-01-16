<h1 class="text-center">Featured Cameras</h1>  
<!-- Separate row class for each category -->
<div class="row">
  <?php foreach($featured_category_two as $featured): ?>
    <div class="card">
      <?php echo $this->Html->image($featured['Product']['id'] . '.jpg', array(
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

<h1 class="text-center">Featured Backpacks</h1>  
<div class="row">
  <?php foreach($featured_category_three as $featured): ?>
    <div class="card">
      <?php echo $this->Html->image($featured['Product']['id'] . '.jpg', array(
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