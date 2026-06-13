<?php
include("process/connect.php");

$query = mysqli_query($conn,"
    SELECT
        tb_order.*,
        tb_payment.id_payment,
        tb_order_list.id_order_list,
        tb_menu_list.name,
        tb_order_list.menu,
        tb_menu_list.price,
        tb_order_list.note,
        tb_order_list.total,
        SUM(tb_menu_list.price * tb_order_list.total) AS total_price
    FROM tb_order_list
    LEFT JOIN tb_order
        ON tb_order.id_order = tb_order_list.`order`
    LEFT JOIN tb_menu_list
        ON tb_menu_list.id = tb_order_list.menu
    LEFT JOIN tb_payment
        ON tb_payment.id_payment = tb_order.id_order
    WHERE tb_order.id_order = '".intval($_GET['order'])."'
    GROUP BY tb_order_list.id_order_list
");
  
  $kode = $_GET["order"];
  $table = $_GET["table"];
  $customer = $_GET["customer"];
while ($record = mysqli_fetch_array($query)){
  $result[] = $record;

  //$kode = $record["id_order"];
  //$table = $record["table"];
  //$customer = $record["customer"];
}

$select_menu = mysqli_query($conn,"SELECT id,name FROM tb_menu_list");
?>

<div class="col-lg-9 mt-2">
    <div class="card">
  <div class="card-header">
    Halaman Order Item
  </div>
  <div class="card-body">
    <a href="index.php?x=order" class="btn btn-info mb-3"><i class="bi bi-arrow-left"></i></a>
    <div class="row">
            <div class="col-lg-3">
              <div class="form-floating mb-3">
                <input disabled type="text" class="form-control" id="order_code" value="<?php echo $kode; ?>">
                <label for="order_code">Order Code</label>
              </div>
            </div>
            <div class="col-lg-2">  
             <div class="form-floating mb-3">
                <input disabled type="text" class="form-control" id="table" value="<?php echo $table; ?>">
                <label for="table">Table</label>
              </div>
            </div>
            <div class="col-lg-3">  
             <div class="form-floating mb-3">
                <input disabled type="text" class="form-control" id="customer" value="<?php echo $customer; ?>">
                <label for="customer">Customer</label>
              </div>
            </div>    
          </div>
    <!-- start Modal add item -->
<div class="modal fade" id="ModalAddItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Menu</h1>  
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/input_order_item_process.php"  method="post">
          <input type="hidden" name="order_code" value="<?php echo $kode ?>">
          <input type="hidden" name="table" value="<?php echo $table ?>">
          <input type="hidden" name="customer" value="<?php echo $customer ?>"> 
          <div class="row">
            <div class="col-lg-8">
              <div class="form-floating mb-3">
                <select class="form-select" name="menu" id="">
                  <option selected hidden value="">Choose a Menu</option>
                  <?php
                     foreach ($select_menu as $value) {
                        echo "<option value=$value[id]>$value[name]</option>";
                     }
                  ?>
                </select>
                <label for="menu">Food or Beverage</label>
                <div class="invalid-feedback">
                  Please Choose a Menu.
                </div>
              </div>
            </div>
            <div class="col-lg-4">  
              <div class="form-floating mb-3">
                <input type="number" class="form-control" id="floatingInput" placeholder="Quantity" name="quantity" required>
                <label for="floatingInput">Quantity</label>
                <div class="invalid-feedback">
                  Please Input Quantity.
                </div>
              </div>
            </div>  
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Note" name="note">
                <label for="floatingNote">Note</label>
              </div>
            </div>
          </div>  
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="input_order_item_validate" value="12345">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal add item -->

          <?php
          if (empty($result)) {
              echo "Tidak ada data Menu";
          } else {
          foreach ($result as $row) {
          ?>

<!-- start Modal edit menu-->
<div class="modal fade" id="ModalEdit<?php echo $row['id_order_list']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data Menu</h1>  
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/edit_order_item_process.php"  method="post">
          <input type="hidden" name="id" value="<?php echo $row['id_order_list'] ?>">
          <input type="hidden" name="order_code" value="<?php echo $kode ?>">
          <input type="hidden" name="table" value="<?php echo $table ?>">
          <input type="hidden" name="customer" value="<?php echo $customer ?>"> 
          <div class="row">
            <div class="col-lg-8">
              <div class="form-floating mb-3">
                <select class="form-select" name="menu" id="">
                  <option selected hidden value="">Choose a Menu</option>
                  <?php
                     foreach ($select_menu as $value) {
                      if($row['menu'] == $value['id']) {
                        echo "<option selected value=$value[id]>$value[name]</option>";
                      }else{
                        echo "<option value=$value[id]>$value[name]</option>";
                      }
                     }
                  ?>
                </select>
                <label for="menu">Food or Beverage</label>
                <div class="invalid-feedback">
                  Please Choose a Menu.
                </div>
              </div>
            </div>
            <div class="col-lg-4">  
              <div class="form-floating mb-3">
                <input type="number" class="form-control" id="floatingInput" placeholder="Quantity" name="quantity" required
                value="<?php echo $row['total'] ?>">
                <label for="floatingInput">Quantity</label>
                <div class="invalid-feedback">
                  Please Input Quantity.
                </div>
              </div>
            </div>  
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Note" name="note" value="<?php echo $row['note'] ?>">
                <label for="floatingNote">Note</label>
              </div>
            </div>
          </div>  
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="edit_order_item_validate" value="12345">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal edit menu-->

<!-- start Modal Delete menu -->
<div class="modal fade" id="ModalDelete<?php echo $row['id_order_list']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Menu</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/delete_order_item_process.php"  method="post">
          <input type="hidden" value="<?php echo $row['id_order_list'] ?>" name="id">
          <input type="hidden" name="order_code" value="<?php echo $kode ?>">
          <input type="hidden" name="table" value="<?php echo $table ?>"> 
          <input type="hidden" name="customer" value="<?php echo $customer ?>"> 
          <div class="col-lg-12">
            Apakah anda ingin menghapus menu <b><?php echo $row['name'] ?></b>?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger" name="delete_order_item_validate" value="12345">Delete</button>
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

    <!-- start Modal payment -->
<div class="modal fade" id="ModalPayment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Payment</h1>  
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr class="text-nowrap">
                <th scope="col">Menu</th>
                <th scope="col">Price</th>
                <th scope="col">Quantity</th>
                <th scope="col">Status</th>
                <th scope="col">Note</th>
                <th scope="col">Price</th>
              </tr>
            </thead>
        <tbody>
          <?php
          $total = 0;
          foreach ($result as $row) {
          ?>
          <tr>
            <td><?php echo $row['name'] ?></td>
            <td><?php echo number_format($row['price'], 0, ',', '.') ?></td>
            <td><?php echo $row['total'] ?></td>
            <td><?php echo $row['note'] ?></td>
            <td><?php echo number_format($row['total_price'], 0, ',', '.') ?></td>
          </tr>
          <?php
          $total += $row['total_price'];
          }
          ?>
          <tr>
            <td colspan="5" class="fw-bold">
              Total Price
            </td>
            <td class="fw-bold">
              <?php echo number_format($total, 0, ',', '.') ?>
            </td>
          </tr>
        </tbody>
        </table>
        </div>
        <form class="needs-validation" novalidate action="process/payment_process.php"  method="post">
          <input type="hidden" name="order_code" value="<?php echo $kode ?>">
          <input type="hidden" name="table" value="<?php echo $table ?>">
          <input type="hidden" name="customer" value="<?php echo $customer ?>"> 
          <input type="hidden" name="total_money" value="<?php echo $total ?>"> 
          <div class="row">
            <div class="col-lg-12">  
              <div class="form-floating mb-3">
                <input type="number" class="form-control" id="floatingInput" placeholder="Amount of Money" name="money" required>
                <label for="floatingInput">Amount of Money</label>
                <div class="invalid-feedback">
                  Please Input Amount of Money.
                </div>
              </div>
            </div>  
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="payment_validate" value="12345">Payment Now</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal payment -->

    <div class="table-responsive">
    <table class="table table-hover">
  <thead>
    <tr class="text-nowrap">
      <th scope="col">Menu</th>
      <th scope="col">Price</th>
      <th scope="col">Quantity</th>
      <th scope="col">Note</th>
      <th scope="col">Price</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $total = 0;
    foreach ($result as $row) {
    ?>
    <tr>
      <td><?php echo $row['name'] ?></td>
      <td><?php echo number_format($row['price'], 0, ',', '.') ?></td>
      <td><?php echo $row['total'] ?></td>
      <td><?php echo (!empty($row['id_payment'])) ? "<span class='badge text-bg-success'>Paid</span>" : 
      "<span class='badge text-bg-danger'>Unpaid</span>"; ?></td>
      <td><?php echo $row['note'] ?></td>
      <td><?php echo number_format($row['total_price'], 0, ',', '.') ?></td>
      <td>
        <div class="d-flex">
        <button class="<?php echo (!empty($result[0]['id_payment'])) ? 'btn btn-secondary btn-sm me-1 disabled' : 'btn btn-warning btn-sm me-1'; ?>" 
        data-bs-toggle="modal" data-bs-target="#ModalEdit<?php echo $row['id_order_list']; ?>">
            <i class="bi bi-pencil-square"></i>
        </button>
        <button class="<?php echo (!empty($result[0]['id_payment'])) ? 'btn btn-secondary btn-sm me-1 disabled' : 'btn btn-danger btn-sm me-1'; ?>" 
        data-bs-toggle="modal" data-bs-target="#ModalDelete<?php echo $row['id_order_list']; ?>">
            <i class="bi bi-trash"></i>
        </button>
        </div>
      </td>
    </tr>
    <?php
    $total += $row['total_price'];
    }
    ?>
    <tr>
      <td colspan="5" class="fw-bold">
        Total Price
      </td>
      <td class="fw-bold">
        <?php echo number_format($total, 0, ',', '.') ?>
      </td>
    </tr>
  </tbody>
</table>
</div>
<?php
}
?>
<div>
<button class="<?php echo (!empty($result[0]['id_payment'])) ? 'btn btn-secondary disabled' : 'btn btn-success'; ?>" data-bs-toggle="modal"
        data-bs-target="#ModalAddItem"><i class="bi bi-plus-circle-fill"></i> Add Item</button>
<button class="<?php echo (!empty($result[0]['id_payment'])) ? 'btn btn-secondary disabled' : 'btn btn-primary'; ?>" data-bs-toggle="modal" data-bs-target="#ModalPayment">
          <i class="bi bi-credit-card-fill"></i> Payment</button>
</div>
  </div>
</div>
    </div>