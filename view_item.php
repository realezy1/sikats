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
}

$select_menu = mysqli_query($conn,"SELECT id,name FROM tb_menu_list");
?>

<div class="col-lg-9 mt-2">
    <div class="card">
  <div class="card-header">
    Halaman View Item
  </div>
  <div class="card-body">
    <a href="index.php?x=report" class="btn btn-info mb-3"><i class="bi bi-arrow-left"></i></a>
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

    <?php
      if (empty($result)) {
        echo "Tidak ada data Menu";
      } else {
        foreach ($result as $row) {
    ?>

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
            <td><?php echo $row['status'] ?></td>
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
      <td><?php echo (!empty($row['id_payment'])) ? "<span class='badge text-bg-success'>Paid</span>" : 
      "<span class='badge text-bg-danger'>Unpaid</span>"; ?></td>
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
<?php
}
?>
  </div>
</div>
    </div>