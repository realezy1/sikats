    <nav class="navbar navbar-expand navbar-dark bg-primary sticky-top">
  <div class="container-lg">
    <a class="navbar-brand" href="."><i class="bi bi-fork-knife"></i>Katsukai</a>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo $hasil ["username"]; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end mt-2">
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ModalChangeProfile"><i class="bi bi-person-square"></i> Profile</a></li>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ModalChangePassword"><i class="bi bi-key"></i></i> Change Password</a></li>
            <li><a class="dropdown-item" href="index.php?x=logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- start Modal Change Password -->
<div class="modal fade" id="ModalChangePassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Change Password</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/change_password_process.php"  method="post">
          <div class="row">
            <div class="col-lg-6">  
              <div class="form-floating mb-3">
                <input disabled type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="username" 
                value="<?php echo $_SESSION['username_sikats'] ?>" required>
                <label for="floatingInput">Email</label>
                <div class="invalid-feedback">
                  Please Input Email.
                </div>
              </div>
            </div>
            <div class="col-lg-6">
                <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword" name="oldpassword" required>
                <label for="floatingInput">Old Password</label>
                <div class="invalid-feedback">
                  Please Input Old Password.
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">  
              <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword" name="newpassword" required>
                <label for="floatingInput">New Password</label>
                <div class="invalid-feedback">
                  Please Input New Passwword.
                </div>
              </div>
            </div>
            <div class="col-lg-6">
                <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword" name="confirmnewpassword" required>
                <label for="floatingInput">Confirm New Password</label>
                <div class="invalid-feedback">
                  Please Confirm New Password.
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="change_password_validate" value="12345">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal Change Password -->