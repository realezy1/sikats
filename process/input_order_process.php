<?php
session_start();
include("connect.php");
$order_code = (isset($_POST["order_code"])) ? htmlentities($_POST["order_code"]) : "" ;
$table = (isset($_POST["table"])) ? htmlentities($_POST["table"]) : "" ;
$customer = (isset($_POST["customer"])) ? htmlentities($_POST["customer"]) : "" ;

if (!empty($_POST["input_order_validate"])) {    
    $select = mysqli_query($conn,"SELECT * FROM tb_order WHERE id_order='$order_code'");
    if (mysqli_num_rows($select) > 0) {
        $message = '<script>
                    alert("Order yang dimasukkan telah ada");
                    window.location="../index.php?x=order";
                    </script>';
    }else {
        $query = mysqli_query($conn,"INSERT INTO tb_order (id_order,`table`,customer,servicer) 
        values ('$order_code','$table','$customer',$_SESSION[id_sikats])");
        if ($query) {
        $message = '<script>
                        alert("Data berhasil dimasukkan");
                        window.location="../index.php?x=orderitem&order='.$order_code.'&table='.$table.'&customer='.$customer.'";
                    </script>';
        } else {
            $message = '<script>
                            alert("Data gagal dimasukkan");
                            window.location="../index.php?x=order";
                        </script>';
        }
    }
}echo $message;
?>