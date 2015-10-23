<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <span class="navbar-brand">Haney's Outdoor Emporium</span>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li><?php echo $this->Html->link('Products', array('controller' => 'products', 'action' => 'index')); ?></li>
        <li><?php echo $this->Html->link('View Cart', array('controller' => 'orders', 'action' => 'view')); ?></li>
      </ul>
    </div>
  </div>
</nav>