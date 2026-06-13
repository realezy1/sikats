<?php
include("process/connect.php");
$query = mysqli_query($conn,"SELECT * FROM tb_category");
while ($record = mysqli_fetch_array($query)){
  $result[] = $record;
}
?>

<div class="col-lg-9 mt-2">
    <div class="card">
  <div class="card-header">
    Halaman Kategori
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col d-flex justify-content-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalTambahUser"> Tambah Kategori</button>
      </div>
    </div>
    <!-- start Modal add menu -->
<div class="modal fade" id="ModalTambahUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kategori</h1>  
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/input_menu_category_process.php"  method="post">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-floating mb-3">
                <select class="form-select" name="menu_type" id="">
                  <option value="1">Makanan</option>
                  <option value="2">Minuman</option>
                </select>
                <label for="floatingInput">Menu Type</label>
                <div class="invalid-feedback">
                  Please Input Menu Type.
                </div>
              </div>
            </div>
            <div class="col-lg-6">  
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Menu Category" name="menu_category" required>
                <label for="floatingInput">Menu Category</label>
                <div class="invalid-feedback">
                  Please Input Menu Category  .
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="input_menu_category_validate" value="12345">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal add menu -->

<?php 
foreach ($result as $row) {
?>
<!-- start Modal edit -->
<div class="modal fade" id="ModalEdit<?php echo $row['id_cat']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data Kategori</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/edit_menu_category_process.php"  method="post">
          <input type="hidden" value="<?php echo $row['id_cat'] ?>" name="id">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-floating mb-3">
                <select class="form-select" aria-label="Default select example" required name="menu_type" id="">
                  <?php
                  $data = array("Makanan","Minuman");
                  foreach($data as $key => $value ) {
                      if($row["menu_type"] == $key+1) {
                        echo "<option selected value='".($key+1)."'>$value</option>";
                    }else {
                      echo "<option value='" . ($key+1) . "'>$value</option>";
                    }
                  }
                  ?>
                </select>
                <label for="floatingInput">Menu Type</label>
                <div class="invalid-feedback">
                  Please Input Menu Type.
                </div>
              </div>
            </div>
            <div class="col-lg-6">  
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Menu Category" name="menu_category" required
                value="<?php echo $row['menu_category'] ?>">
                <label for="floatingInput">Menu Category</label>
                <div class="invalid-feedback">
                  Please Input Menu Category  .
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="input_menu_category_validate" value="12345">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal edit -->

<!-- start Modal Delete -->
<div class="modal fade" id="ModalDelete<?php echo $row['id_cat']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Data Kategori</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/delete_menu_category_process.php"  method="post">
          <input type="hidden" value="<?php echo $row['id_cat'] ?>" name="id">
          <div class="col-lg-12">
            Apakah anda ingin menghapus kategori <b><?php echo $row['menu_category'] ?></b>?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger" name="delete_menu_category_validate" value="12345">Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal Delete -->
    <?php
     }
      if (empty($result)) {
        echo "Tidak ada data user";
      } else {
    ?>
    <!-- Tabel Kategori Menu -->
    <div class="table-responsive">
    <table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Menu Type</th>
      <th scope="col">Menu Category</th>
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
      <td><?php echo ($row['menu_type']==1) ? "Makanan" : "Minuman"?></td>
      <td><?php echo $row['menu_category'] ?></td>
      <td class="d-flex">
        <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalEdit<?php echo $row['id_cat']?>">
          <i class="bi bi-pencil-square"></i></i></button>
        <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal" data-bs-target="#ModalDelete<?php echo $row['id_cat']?>">
          <i class="bi bi-trash"></i></i></button>
      </td>
    </tr>
    <?php
    }
    ?>
  </tbody>
</table>
</div>
<!-- Tabel Kategori Menu -->
<?php
}
?>
  </div>
</div>
    </div>