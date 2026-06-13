<?php
include("process/connect.php");
date_default_timezone_set("Asia/Jakarta");
$query = mysqli_query($conn,"
    SELECT
        tb_order.*,
        tb_payment.*,
        tb_user.name AS servicer_name,
        SUM(tb_menu_list.price * tb_order_list.total) AS total_price
    FROM tb_order
    LEFT JOIN tb_user ON tb_user.id = tb_order.servicer
    LEFT JOIN tb_order_list ON tb_order_list.order = tb_order.id_order
    LEFT JOIN tb_menu_list ON tb_menu_list.id = tb_order_list.menu
    LEFT JOIN tb_payment ON tb_payment.id_payment = tb_order.id_order
    GROUP BY tb_order.id_order ORDER BY order_time DESC");
  
while ($record = mysqli_fetch_array($query)){
  $result[] = $record;
}

//  $select_cat_menu = mysqli_query($conn,"SELECT id_cat,menu_category FROM tb_category");
?>

<div class="col-lg-9 mt-2">
    <div class="card">
  <div class="card-header">
    Halaman Order
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col d-flex justify-content-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalTambahOrder"> Tambah Order</button>
      </div>
    </div>
    <!-- start Modal add order -->
<div class="modal fade" id="ModalTambahOrder" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Order</h1>  
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/input_order_process.php"  method="post">
          <div class="row">
            <div class="col-lg-3">
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="order_code" name="order_code" value="<?php echo date('ymdHi').rand(100,999) ?>" readonly>
                <label for="order_code">Order Code</label>
                <div class="invalid-feedback">
                  Please Input Order Code.
                </div>
              </div>
            </div>
            <div class="col-lg-2">  
              <div class="form-floating mb-3">
                <input type="number" class="form-control" id="floatingInput" placeholder="Table" name="table" required>
                <label for="table">Table</label>
                <div class="invalid-feedback">
                  Please Input Table.
                </div>
              </div>
            </div>
            <div class="col-lg-7">  
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Customer" name="customer" required>
                <label for="customer">Customer</label>
                <div class="invalid-feedback">
                  Please Input Customer.
                </div>
              </div>
            </div>  
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="input_order_validate" value="12345">Make Order</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal add order -->

          <?php
          if (empty($result)) {
              echo "Tidak ada data Menu";
          } else {
          foreach ($result as $row) {
          ?>

<!-- start Modal edit menu-->
<div class="modal fade" id="ModalEdit<?php echo $row['id_order']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data Menu</h1>  
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/edit_order_process.php"  method="post">
          <div class="row">
            <div class="col-lg-3">
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="order_code" name="order_code" value="<?php echo $row['id_order'] ?>" readonly>
                <label for="order_code">Order Code</label>
                <div class="invalid-feedback">
                  Please Input Order Code.
                </div>
              </div>
            </div>
            <div class="col-lg-2">  
              <div class="form-floating mb-3">
                <input type="number" class="form-control" id="floatingInput" placeholder="Table" name="table" required value="<?php echo $row['table'] ?>">
                <label for="table">Table</label>
                <div class="invalid-feedback">
                  Please Input Table.
                </div>
              </div>
            </div>
            <div class="col-lg-7">  
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Customer" name="customer" required value="<?php echo $row['customer'] ?>">
                <label for="customer">Customer</label>
                <div class="invalid-feedback">
                  Please Input Customer.
                </div>
              </div>
            </div>  
          </div> 
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="edit_order_validate" value="12345">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal edit menu-->

<!-- start Modal Delete menu -->
<div class="modal fade" id="ModalDelete<?php echo $row['id_order']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Data User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/delete_order_process.php"  method="post">
          <input type="hidden" value="<?php echo $row['id_order'] ?>" name="order_code">
          <div class="col-lg-12">
            Apakah anda ingin menghapus order atas nama <b><?php echo $row['customer'] ?></b> dengan kode order <b><?php echo $row['id_order'] ?></b>?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger" name="delete_order_validate" value="12345">Delete</button>
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
      <th scope="col">Order Code</th>
      <th scope="col">Customer</th>
      <th scope="col">Table</th>
      <th scope="col">Total Price</th>
      <th scope="col">Servicer</th>
      <th scope="col">Status</th>
      <th scope="col">Order Time</th>
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
      <td><?php echo $row['id_order'] ?></td>
      <td><?php echo $row['customer'] ?></td>
      <td><?php echo $row['table'] ?></td>
      <td><?php echo number_format($row['total_price'] ?? 0, 0, ',', '.') ?></td>
      <td><?php echo $row['servicer_name'] ?></td>
      <td><?php echo (!empty($row['id_payment'])) ? "<span class='badge text-bg-success'>Paid</span>" : 
      "<span class='badge text-bg-danger'>Unpaid</span>"; ?></td>
      <td><?php echo $row['order_time'] ?></td>
      <td>
        <div class="d-flex">
          <a class="btn btn-info btn-sm me-1" href="./?x=orderitem&order=<?php echo $row['id_order']. "&table=".$row['table']."&customer=".$row['customer'] ?>">
            <i class="bi bi-eye"></i></a>

          <button
            class="btn <?php echo !empty($row['id_payment']) ? 'btn-secondary' : 'btn-warning'; ?> btn-sm me-1"
            <?php if (empty($row['id_payment'])) { ?>
                data-bs-toggle="modal"
                data-bs-target="#ModalEdit<?php echo $row['id_order']; ?>"
            <?php } else { ?>
                disabled
            <?php } ?>><i class="bi bi-pencil-square"></i>
          </button>
          <button
              class="btn <?php echo !empty($row['id_payment']) ? 'btn-secondary' : 'btn-danger'; ?> btn-sm me-1"
              <?php if (empty($row['id_payment'])) { ?>
                  data-bs-toggle="modal"
                  data-bs-target="#ModalDelete<?php echo $row['id_order']; ?>"
              <?php } else { ?>
                  disabled
              <?php } ?>><i class="bi bi-trash"></i>
          </button>
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