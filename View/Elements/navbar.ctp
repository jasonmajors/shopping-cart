<nav class="navbar navbar-inverse" style="border-radius: 0px">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <span class="navbar-brand">Jumbo's Outdoor Emporium</span>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li><?php echo $this->Html->link('Home', array('controller' => 'products', 'action' => 'index')); ?></li>
      <?php if ($loggedIn): ?>
        <li><?php echo $this->Html->link('View Cart', array('controller' => 'orders', 'action' => 'view')); ?></li>
        <li><?php echo $this->Html->link('Orders', array('controller' => 'orders', 'action' => 'myOrders')); ?></li>
        <li><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?></li>
      <?php else: ?>
        <li><?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login')); ?></li>
        <li><?php echo $this->Html->link('Register', array('controller' => 'users', 'action' => 'add')); ?></li>
      <?php endif; ?> 
      </ul>
    </div>
  </div>
</nav>