<?php
//session_start();

if (empty($_SESSION["username_sikats"])) {
    header("Location: login.php");
    exit;
}

include "process/connect.php";

$username = $_SESSION["username_sikats"];

$query = mysqli_query(
    $conn,
    "SELECT * FROM tb_user WHERE username='$username'"
);

$hasil = mysqli_fetch_assoc($query);
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Header -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SiKats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  </head>
  <body>
    <!-- Header -->
    <?php include "header.php"; ?>
    <!-- End Header -->
<div class="container-lg">
  <div class="row mb-5">
    <!-- SideBar -->
    <?php include "sidebar.php"; ?>
    <!-- End SideBar -->

    <!-- Content -->
    <?php
      include $page;
    ?>
    <!-- End Content -->
  </div>
  <div class="fixed-bottom text-center bg-light py-2">
    Copyright 2022 - <?php echo date("Y") ?> Aksal
  </div>

</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
       <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
(() => {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()
    </script>
  </body>
</html>