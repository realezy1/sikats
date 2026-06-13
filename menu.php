<?php
include("process/connect.php");
$query = mysqli_query($conn,"SELECT * FROM tb_menu_list
  LEFT JOIN tb_category ON tb_category.id_cat = tb_menu_list.category");
while ($record = mysqli_fetch_array($query)){
  $result[] = $record;
}

$select_cat_menu = mysqli_query($conn,"SELECT id_cat,menu_category FROM tb_category");
?>

<div class="col-lg-9 mt-2">
    <div class="card">
  <div class="card-header">
    Halaman Daftar Menu
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col d-flex justify-content-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalTambahMenu"> Tambah Menu</button>
      </div>
    </div>
    <!-- start Modal add menu -->
<div class="modal fade" id="ModalTambahMenu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Menu</h1>  
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/input_menu_process.php"  method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-lg-6">
              <div class="input-group mb-3">
                <input type="file" class="form-control py-3" id="UploadPhoto" placeholder="Your Name" name="photo" required>
                <label class="input-group-text" for="UploadPhoto">Upload Photo</label>
                <div class="invalid-feedback">
                  Please Input Photo.
                </div>
              </div>
            </div>
            <div class="col-lg-6">  
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Menu Name" name="name" required>
                <label for="floatingInput">Menu Name</label>
                <div class="invalid-feedback">
                  Please Input Menu Name.
                </div>
              </div>
            </div>  
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Description" name="description">
                <label for="floatingDescription">Description</label>
              </div>
            </div>
          </div>  
          <div class="row">
            <div class="col-lg-4">
              <div class="form-floating mb-3">
              <select class="form-select" aria-label="Default select example" name="menu_category" required>
                <option selected hidden value="">Choose a Category</option>
                <?php
                  foreach ($select_cat_menu as $value) {
                    echo "<option value=".$value['id_cat'].">$value[menu_category]</option>";
                  }
                ?>
              </select>
              <label for="floatingInput">Menu Category</label>
              <div class="invalid-feedback">
                  Please Choose a Category.
                </div>
            </div>
            </div>
            <div class="col-lg-4">  
              <div class="form-floating mb-3">
                <input type="number" class="form-control" id="floatingInput" placeholder="Price" name="price" required>
                <label for="floatingInput">Price</label>
                <div class="invalid-feedback">
                  Please Input Price.
                </div>
              </div>
            </div>
            <div class="col-lg-4">  
              <div class="form-floating mb-3">
                <input type="number" class="form-control" id="floatingInput" placeholder="Stock" name="stock" required>
                <label for="floatingInput">Stock</label>
                <div class="invalid-feedback">
                  Please Input Stock.
                </div>
              </div>
            </div>
          </div>  
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="input_menu_validate" value="12345">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal add menu -->

          <?php
          if (empty($result)) {
              echo "Tidak ada data Menu";
          } else {
          foreach ($result as $row) {
          ?>
<!-- start Modal view menu -->
<div class="modal fade" id="ModalView<?php echo $row['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Data Menu</h1>  
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/input_menu_process.php"  method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-lg-12">  
              <div class="form-floating mb-3">
                <input disabled type="text" class="form-control" id="floatingInput" value="<?php echo $row['name'] ?>">
                <label for="floatingInput">Menu Name</label>
                <div class="invalid-feedback">
                  Please Input Menu Name.
                </div>
              </div>
            </div>  
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-floating mb-3">
                <input disabled type="text" class="form-control" id="floatingInput" value="<?php echo $row['description'] ?>">
                <label for="floatingDescription">Description</label>
              </div>
            </div>
          </div>  
          <div class="row">
            <div class="col-lg-4">
              <div class="form-floating mb-3">
              <select disabled class="form-select" aria-label="Default select example">
                <option selected hidden value="">Choose a Category</option>
                <?php
                  foreach ($select_cat_menu as $value) {
                    if($row['category'] == $value['id_cat']) {
                      echo "<option selected value=".$value['id_cat'].">$value[menu_category]</option>";
                    }else{
                      echo "<option value=".$value['id_cat'].">$value[menu_category]</option>";
                    }
                  }
                ?>
              </select>
              <label for="floatingInput">Menu Category</label>
              <div class="invalid-feedback">
                  Please Choose a Category.
                </div>
            </div>
            </div>
            <div class="col-lg-4">  
              <div class="form-floating mb-3">
                <input disabled type="number" class="form-control" id="floatingInput" value="<?php echo $row['price'] ?>">
                <label for="floatingInput">Price</label>
                <div class="invalid-feedback">
                  Please Input Price.
                </div>
              </div>
            </div>
            <div class="col-lg-4">  
              <div class="form-floating mb-3">
                <input disabled type="number" class="form-control" id="floatingInput" value="<?php echo $row['stock'] ?>">
                <label for="floatingInput">Stock</label>
                <div class="invalid-feedback">
                  Please Input Stock.
                </div>
              </div>
            </div>
          </div>  
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="input_menu_validate" value="12345">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal view menu -->

<!-- start Modal edit menu-->
<div class="modal fade" id="ModalEdit<?php echo $row['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data Menu</h1>  
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/edit_menu_process.php"  method="post" enctype="multipart/form-data">
          <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
          <div class="row">
            <div class="col-lg-6">
              <div class="input-group mb-3">
                <input type="file" class="form-control py-3" id="UploadPhoto" placeholder="Your Name" name="photo">
                <label class="input-group-text" for="UploadPhoto">Upload Photo</label>
                <div class="invalid-feedback">
                  Please Input Photo.
                </div>
              </div>
            </div>
            <div class="col-lg-6">  
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Menu Name" name="name" required
                value="<?php echo $row['name'] ?>">
                <label for="floatingInput">Menu Name</label>
                <div class="invalid-feedback">
                  Please Input Menu Name.
                </div>
              </div>
            </div>  
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Description" name="description"
                value="<?php echo $row['description'] ?>" required>
                <label for="floatingInput">Description</label>
              </div>
            </div>
          </div>  
          <div class="row">
            <div class="col-lg-4">
              <div class="form-floating mb-3">
              <select class="form-select" aria-label="Default select example" name="menu_category" required>
                <option selected hidden value="">Choose a Category</option>
                <?php
                  foreach ($select_cat_menu as $value) {
                    if($row['category'] == $value['id_cat']) {
                      echo "<option selected value=".$value['id_cat'].">$value[menu_category]</option>";
                    }else{
                      echo "<option value=".$value['id_cat'].">$value[menu_category]</option>";
                    }
                  }
                ?>
              </select>
              <label for="floatingInput">Menu Category</label>
              <div class="invalid-feedback">
                  Please Choose a Category.
                </div>
            </div>
            </div>
            <div class="col-lg-4">  
              <div class="form-floating mb-3">
                <input type="number" class="form-control" id="floatingInput" placeholder="Price" name="price" required
                value="<?php echo $row['price'] ?>">
                <label for="floatingInput">Price</label>
                <div class="invalid-feedback">
                  Please Input Price.
                </div>
              </div>
            </div>
            <div class="col-lg-4">  
              <div class="form-floating mb-3">
                <input type="number" class="form-control" id="floatingInput" placeholder="Stock" name="stock" required
                value="<?php echo $row['stock'] ?>">
                <label for="floatingInput">Stock</label>
                <div class="invalid-feedback">
                  Please Input Stock.
                </div>
              </div>
            </div>
          </div>  
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="input_menu_validate" value="12345">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal edit menu-->

<!-- start Modal Delete menu -->
<div class="modal fade" id="ModalDelete<?php echo $row['id']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Data User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/delete_menu_process.php"  method="post">
          <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
          <input type="hidden" value="<?php echo $row['photo'] ?>" name="photo">
          <div class="col-lg-12">
            Apakah anda ingin menghapus menu <b><?php echo $row['name'] ?></b>?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger" name="input_user_validate" value="12345">Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal Delete menu-->

    <?php
     }
    ?>

    <div class="table-responsive">
    <table class="table table-hover">
  <thead>
    <tr class="text-nowrap">
      <th scope="col">No</th>
      <th scope="col">Photo</th>
      <th scope="col">Name</th>
      <th scope="col">Description</th>
      <th scope="col">Menu Type</th>
      <th scope="col">Category</th>
      <th scope="col">Price</th>
      <th scope="col">Stock</th>
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
      <td>
        <div style="width: 70px;">
          <img src="assets/img/<?php echo $row['photo'] ?>" class="img-thumbnail" alt="...">
        </div>
      </td>
      <td><?php echo $row['name'] ?></td>
      <td><?php echo $row['description'] ?></td>
      <td><?php echo ($row['menu_type'] == 1) ? "Makanan" : "Minuman"?></td>
      <td><?php echo $row['menu_category'] ?></td>
      <td><?php echo number_format($row['price'], 0, ',', '.') ?></td>
      <td><?php echo $row['stock'] ?></td>
      <td>
        <div class="d-flex">
        <button class="btn btn-info btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalView<?php echo $row['id']?>">
          <i class="bi bi-eye"></i></button>
        <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalEdit<?php echo $row['id']?>">
          <i class="bi bi-pencil-square"></i></i></button>
        <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalDelete<?php echo $row['id']?>">
          <i class="bi bi-trash"></i></i></button>
        </div>
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