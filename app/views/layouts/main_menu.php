<?php
  use Core\Router;
  use Core\H;
  use App\Models\{Users, Carts};
  $menu = Router::getMenu('menu_acl');
  $userMenu = Router::getMenu('user_menu');
  $cartItemCount = Carts::itemCountCurrentCart();
  $cartActive = (H::currentPage() == PROOT.'cart')? " active" : "";
?>
<header class="full">
  <nav class="grid-width navbar navbar-expand-lg navbar-dark">
    <!-- Brand and toggle get grouped for better mobile display -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_menu" aria-controls="main_menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="<?=PROOT?>home">
      <img class="header-logo" src="<?=PROOT?>images/optimized/storylogo.png" alt="storytime logo">
    </a>
    <ul class="navbar-nav mx-auto">
      <?php if($cartItemCount > 0): ?>
        <li class="nav-item nav-cart">
          <span class="nav-badge"><?=$cartItemCount?></span>
          <a href="<?=PROOT?>cart" class="nav-link<?=$cartActive?>"><i class="fas fa-shopping-cart"></i></a>
        </li>
      <?php endif; ?>
      <?= H::buildMenuListItems($userMenu,"dropdown-menu-right"); ?>
    </ul>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse links-wrapper" id="main_menu">
      <ul class="navbar-nav ml-auto ">
        <?= H::buildMenuListItems($menu); ?>
      </ul>

    </div><!-- /.navbar-collapse -->
  </nav>
</header>
