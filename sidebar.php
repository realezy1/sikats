<div class="col-lg-3">
      <nav class="navbar navbar-expand-lg bg-light rounded border mt-2">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
    aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel" style="width:250px">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Offcanvas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav nav-pills flex-column justify-content-end flex-grow-1">
          <li class="nav-item">
            <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x']=='home') ? 'active link-light' : 'link-dark' ; ?>" 
            href="index.php?x=home"><i class="bi bi-house-door"></i> Dashboard</a>
          </li>

          <?php if($hasil['level']==1 || $hasil['level']==2){?>
          <li class="nav-item">
            <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x']=='menu') ? 'active link-light' : 'link-dark' ; ?>" 
            href="index.php?x=menu"><i class="bi bi-menu-button-wide-fill"></i> Daftar Menu</a>
          </li>
          <li class="nav-item">
            <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x']=='category') ? 'active link-light' : 'link-dark' ; ?>" 
            href="index.php?x=category"><i class="bi bi-tags"></i></i> Category</a>
          </li>
          <?php } ?>
          
          <?php if($hasil['level']==1 || $hasil['level']==2 || $hasil['level']==3){?>
          <li class="nav-item">
            <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x']=='order') ? 'active link-light' : 'link-dark' ; ?>" 
            href="index.php?x=order"><i class="bi bi-cart4"></i> Order</a>
          </li> 
          <?php } ?>

          <?php if($hasil['level']==1 || $hasil['level']==4){?>
          <li class="nav-item">
            <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x']=='kitchen') ? 'active link-light' : 'link-dark' ; ?>" 
            href="index.php?x=kitchen"><i class="bi bi-fire"></i> Kitchen</a>
          </li>
          <?php } ?>  

          <?php if($hasil['level']==1){?> 
          <li class="nav-item">
            <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x']=='user') ? 'active link-light' : 'link-dark' ; ?>" 
            href="index.php?x=user"><i class="bi bi-card-heading"></i> User</a>
          </li>
          <li class="nav-item">
            <a class="nav-link ps-2 <?php echo (isset($_GET['x']) && $_GET['x']=='report') ? 'active link-light' : 'link-dark' ; ?>" 
            href="index.php?x=report"><i class="bi bi-clipboard-data"></i> Report</a>
          </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</nav>
    </div>