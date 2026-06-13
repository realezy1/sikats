<?php
include("process/connect.php");

$query = mysqli_query($conn,"
    SELECT * FROM tb_order_list
    LEFT JOIN tb_order ON tb_order.id_order = tb_order_list.`order`
    LEFT JOIN tb_menu_list ON tb_menu_list.id = tb_order_list.menu
    LEFT JOIN tb_payment ON tb_payment.id_payment = tb_order.id_order
    ORDER BY order_time ASC");

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
    Halaman Kitchen
  </div>
  <div class="card-body">
    <a href="index.php?x=order" class="btn btn-info mb-3"><i class="bi bi-arrow-left"></i></a>

          <?php
          if (empty($result)) {
              echo "Tidak ada data Menu";
          } else {
          foreach ($result as $row) {
          ?>

<!-- start Modal accept-->
<div class="modal fade" id="accept<?php echo $row['id_order_list']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Accept Menu</h1>  
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/accept_process.php"  method="post">
          <input type="hidden" name="id" value="<?php echo $row['id_order_list'] ?>">
          <div class="row">
            <div class="col-lg-8">
              <div class="form-floating mb-3">
                <select disabled class="form-select" name="menu" id="">
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
                <input disabled type="number" class="form-control" id="floatingInput" placeholder="Quantity" name="quantity" required
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
            <button type="submit" class="btn btn-primary" name="accept_validate" value="12345">Accept</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal accept-->

<!-- start Modal ready-->
<div class="modal fade" id="ready<?php echo $row['id_order_list']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Ready</h1>  
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="needs-validation" novalidate action="process/ready_process.php"  method="post">
          <input type="hidden" name="id" value="<?php echo $row['id_order_list'] ?>">
          <div class="row">
            <div class="col-lg-8">
              <div class="form-floating mb-3">
                <select disabled class="form-select" name="menu" id="">
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
                <input disabled type="number" class="form-control" id="floatingInput" placeholder="Quantity" name="quantity" required
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
            <button type="submit" class="btn btn-primary" name="ready_validate" value="12345">Ready</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- end Modal ready-->

    <?php
     }
    ?>

    <div class="table-responsive">
    <table class="table table-hover">
  <thead>
    <tr class="text-nowrap">
      <th scope="col">No</th>
      <th scope="col">Order Code</th>
      <th scope="col">Order Time</th>
      <th scope="col">Menu</th>
      <th scope="col">Quantity</th>
      <th scope="col">Note</th>
      <th scope="col">Status</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $no =1;
    foreach ($result as $row) {
      if($row['status'] != 2) {
    ?>
    <tr>
      <td><?php echo $no++ ?></td>
      <td><?php echo $row['id_order'] ?></td>
      <td><?php echo $row['order_time'] ?></td>
      <td><?php echo $row['name'] ?></td>
      <td><?php echo $row['total'] ?></td>
      <td><?php echo $row['note'] ?></td>
      <td>
        <?php
          if($row['status'] == 1) {
            echo"<span class='badge text-bg-warning'>Masuk ke dapur</span>";
          }elseif ($row['status'] == 2) {
            echo"<span class='badge text-bg-primary'>Siap disajikan</span>";
          }
        ?>
      </td>
      <td>
        <div class="d-flex">
        <button class="<?php echo (!empty($row['status'])) ? 'btn btn-secondary btn-sm me-1 disabled' : 'btn btn-primary btn-sm me-1'; ?>" 
        data-bs-toggle="modal" data-bs-target="#accept<?php echo $row['id_order_list']; ?>">Accept</button>
        <button class="<?php echo (empty($row['status']) || $row['status']!=1) ? 'btn btn-secondary btn-sm me-1 disabled' : 'btn btn-success btn-sm me-1'; ?>" 
        data-bs-toggle="modal" data-bs-target="#ready<?php echo $row['id_order_list']; ?>">Ready</button>
        </div>
      </td>
    </tr>
    <?php
    }
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