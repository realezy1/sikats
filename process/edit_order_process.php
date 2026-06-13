<?php
session_start();
include("connect.php");
$order_code = (isset($_POST["order_code"])) ? htmlentities($_POST["order_code"]) : "" ;
$table = (isset($_POST["table"])) ? htmlentities($_POST["table"]) : "" ;
$customer = (isset($_POST["customer"])) ? htmlentities($_POST["customer"]) : "" ;

if (!empty($_POST["edit_order_validate"])) {    
    $select = mysqli_query($conn,"SELECT * FROM tb_order WHERE id_order='$order_code'");
        $query = mysqli_query($conn,"UPDATE tb_order SET `table`='$table',customer='$customer'
        WHERE id_order='$order_code'");
        if ($query) {
            $message = '<script>
                            alert("Data berhasil dimasukkan");
                            window.location="../index.php?x=order";
                        </script>';
        } else {
            $message = '<script>
                            alert("Data gagal dimasukkan");
                            window.location="../index.php?x=order";
                        </script>';
        }
    }
echo $message;
?>