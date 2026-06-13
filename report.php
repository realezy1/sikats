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
    JOIN tb_payment ON tb_payment.id_payment = tb_order.id_order
    GROUP BY tb_order.id_order ORDER BY order_time ASC");
  
while ($record = mysqli_fetch_array($query)){
  $result[] = $record;
}

//  $select_cat_menu = mysqli_query($conn,"SELECT id_cat,menu_category FROM tb_category");
?>

<div class="col-lg-9 mt-2">
    <div class="card">
  <div class="card-header">
    Halaman Report
  </div>
  <div class="card-body">

    <?php
      if (empty($result)) {
        echo "Tidak ada data Menu";
      } else {
        foreach ($result as $row) {
    ?>

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
      <th scope="col">Order Time</th>
      <th scope="col">Payment Time</th>
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
      <td><?php echo $row['order_time'] ?></td>
      <td><?php echo $row['payment_time'] ?></td>
      <td>
        <div class="d-flex">
          <a class="btn btn-info btn-sm me-1" href="./?x=viewitem&order=<?php echo $row['id_order']. "&table=".$row['table']."&customer=".$row['customer'] ?>">
            <i class="bi bi-eye"></i></a>
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