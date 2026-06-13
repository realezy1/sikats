<?php
include("process/connect.php");
$query = mysqli_query($conn,"SELECT * FROM tb_user");
while ($record = mysqli_fetch_array($query)){
  $result[] = $record;
}
?>

<div class="col-lg-9 mt-2">
    <div class="card">
  <div class="card-header">
    Halaman User
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col d-flex justify-content-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalTambahUser"> Tambah User</button>
      </div>
    </div>
    <!-- start Modal add user -->
<div class="modal fade" id="ModalTambahUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah User</h1>  
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/input_user_process.php"  method="post">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Your Name" name="name" required>
                <label for="floatingInput">Name</label>
                <div class="invalid-feedback">
                  Please Input Name.
                </div>
              </div>
            </div>
            <div class="col-lg-6">  
              <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="username" required>
                <label for="floatingInput">Email</label>
                <div class="invalid-feedback">
                  Please Input Email.
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4">
              <div class="form-floating mb-3">
              <select class="form-select" aria-label="Default select example" name="level" required>
                <option selected hidden value="">Choose a user level</option>
                <option value="1">Admin</option>
                <option value="2">Kasir</option>
                <option value="3">Pelayan</option>
                <option value="4">Dapur</option>
              </select>
              <label for="floatingInput">Level User</label>
              <div class="invalid-feedback">
                  Please Choose a Level.
                </div>
            </div>
            </div>
            <div class="col-lg-8">  
              <div class="form-floating mb-3">
                <input type="number" class="form-control" id="floatingInput" placeholder="08xxxxxxxx" name="mobile_number" required>
                <label for="floatingInput">Mobile Number</label>
                <div class="invalid-feedback">
                  Please Input Mobile Number.
                </div>
              </div>
            </div>
            <div class="row">
            <div class="col-lg-12">
              <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingInput" placeholder="Password" disabled value="12345" name="password">
                <label for="floatingPassword">Password</label>
              </div>
            </div>
          </div>  
          </div>  
          <div class="form-floating">
            <textarea class="form-control" id="" style="height:100px;" name="alamat" required></textarea>
            <label for="floatingInput">Address</label>
            <div class="invalid-feedback">
                  Please Input Address.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="input_user_validate" value="12345">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal add user -->

<?php 
foreach ($result as $row) {
?>
<!-- start Modal view -->
<div class="modal fade" id="ModalView<?php echo $row['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Data User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/input_user_process.php"  method="post">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-floating mb-3">
                <input disabled type="text" class="form-control" id="floatingInput" placeholder="Your Name" name="name" value="<?php echo $row['name'] ?>">
                <label for="floatingInput">Name</label>
              </div>
            </div>
            <div class="col-lg-6">  
              <div class="form-floating mb-3">
                <input disabled type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="username" value="<?php echo $row['username'] ?>">
                <label for="floatingInput">Email</label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4">
              <div class="form-floating mb-3">
              <select class="form-select" aria-label="Default select example" required name="level" id="" disabled>
                  <?php
                  $data = array("Admin","Kasir","Pelayan","Dapur");
                  foreach($data as $key => $value ) {
                      if($row["level"] == $key+1) {
                        echo "<option selected value='$key'>$value</option>";
                    }else {
                      echo "<option value='$key'>$value</option>";
                    }
                  }
                  ?>
                </select>
              <label for="floatingInput">Level User</label>
            </div>
            </div>
            <div class="col-lg-8">  
              <div class="form-floating mb-3">
                <input disabled type="number" class="form-control" id="floatingInput" placeholder="08xxxxxxxx" name="mobile_number" value="<?php echo $row['mobile_number'] ?>">
                <label for="floatingInput">Mobile Number</label>
              </div>
            </div> 
          </div>  
          <div class="form-floating">
            <textarea disabled class="form-control" name="alamat" style="height:100px;"><?php echo $row['alamat']; ?></textarea>
            <label for="floatingInput">Alamat</label>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal view -->

<!-- start Modal edit -->
<div class="modal fade" id="ModalEdit<?php echo $row['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/edit_user_process.php"  method="post">
          <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-floating mb-3">
                <input required type="text" class="form-control" id="floatingInput" placeholder="Your Name" name="name" value="<?php echo $row['name'] ?>">
                <label for="floatingInput">Name</label>
                <div class="invalid-feedback">
                  Please Input Name.
                </div>
              </div>
            </div>
            <div class="col-lg-6">  
              <div class="form-floating mb-3">
                <input <?php echo ($row['username'] == $_SESSION['username_sikats']) ? 'disabled' : '' ; ?> required type="email" class="form-control" id="floatingInput" 
                placeholder="name@example.com" name="username" value="<?php echo $row['username'] ?>">
                <label for="floatingInput">Email</label>
                <div class="invalid-feedback">
                  Please Input Email.
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4">
              <div class="form-floating mb-3">
                <select class="form-select" aria-label="Default select example" required name="level" id="">
                  <?php
                  $data = array("Admin","Kasir","Pelayan","Dapur");
                  foreach($data as $key => $value ) {
                      if($row["level"] == $key+1) {
                        echo "<option selected value='".($key+1)."'>$value</option>";
                    }else {
                      echo "<option value='" . ($key+1) . "'>$value</option>";
                    }
                  }
                  ?>
                </select>
              <label for="floatingInput">Level User</label>
              <div class="invalid-feedback">
                  Please Choose a Level.
                </div>
            </div>
            </div>
            <div class="col-lg-8">  
              <div class="form-floating mb-3">
                <input required type="number" class="form-control" id="floatingInput" placeholder="08xxxxxxxx" name="mobile_number" value="<?php echo $row['mobile_number'] ?>">
                <label for="floatingInput">Mobile Number</label>
                <div class="invalid-feedback">
                  Please Input Mobile Number.
                </div>
              </div>
            </div> 
          </div>  
          <div class="form-floating">
            <textarea required class="form-control" name="alamat" style="height:100px;"><?php echo $row['alamat']; ?></textarea>
            <label for="floatingInput">Address</label>
            <div class="invalid-feedback">
                  Please Input Address.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="input_user_validate" value="12345">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal edit -->

<!-- start Modal Delete -->
<div class="modal fade" id="ModalDelete<?php echo $row['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Data User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/delete_user_process.php"  method="post">
          <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
          <div class="col-lg-12">
            <?php
                if($row['username'] == $_SESSION['username_sikats']) {
                  echo "<div class='alert alert-danger'>You can't delete your account</div>";
                }else{
                  echo "Are you sure you want to delete <b>$row[username]</b>?";
                }
            ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger" name="input_user_validate" value="12345"
            <?php echo ($row['username'] == $_SESSION['username_sikats']) ? 'disabled' : '' ; ?>>Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal Delete -->

<!-- start Modal Reset Password -->
<div class="modal fade" id="ModalResetPassword<?php echo $row['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Reset Password</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/reset_password_process.php" method="post">
          <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
          <div class="col-lg-12">
            <?php
                if($row['username'] == $_SESSION['username_sikats']) {
                  echo "<div class='alert alert-danger'>You can't reset your own password</div>";
                }else{
                  echo "Are you sure you want to reset this password account<b> $row[username]</b>?";
                }
            ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success" name="input_user_validate" value="12345"
            <?php echo ($row['username'] == $_SESSION['username_sikats']) ? 'disabled' : '' ; ?>>Reset Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal Reset Password -->
    <?php
     }
      if (empty($result)) {
        echo "Tidak ada data user";
      } else {
    ?>

    <div class="table-responsive">
    <table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Level</th>
      <th scope="col">Mobile Number</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $no = 1;
    foreach ($result as $row) {
    ?>
    <tr>
      <th scope="row"><?php echo $no++ ?></th>
      <td><?php echo $row['name'] ?></td>
      <td><?php echo $row['username'] ?></td>
      <td>
        <?php
              if ($row['level'] == 1) {
                echo "Admin";
              } else if ($row["level"] == 2) {
                echo "Kasir";
              } else if ($row["level"] == 3) {
                echo "Pelayan";
              } else if ($row["level"] == 4) {
                echo "Dapur";
              }
          ?>
      </td>
      <td><?php echo $row['mobile_number'] ?></td>
      <td class="d-flex">
        <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalView<?php echo $row['id']?>">
          <i class="bi bi-eye"></i></button>
        <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalEdit<?php echo $row['id']?>">
          <i class="bi bi-pencil-square"></i></i></button>
        <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalDelete<?php echo $row['id']?>">
          <i class="bi bi-trash"></i></i></button>
        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalResetPassword<?php echo $row['id']?>">
          <i class="bi bi-key"></i></i></button>
      </td>
    </tr>
    <?php
    }
    ?>
  </tbody>
</table>
</div>
<?php
}
?>
  </div>
</div>
    </div>