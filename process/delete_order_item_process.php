<?php
include("connect.php");
$id = (isset($_POST["id"])) ? htmlentities($_POST["id"]) : "" ;
$order_code = (isset($_POST["order_code"])) ? htmlentities($_POST["order_code"]) : "" ;
$table = (isset($_POST["table"])) ? htmlentities($_POST["table"]) : "" ;
$customer = (isset($_POST["customer"])) ? htmlentities($_POST["customer"]) : "" ;

if (!empty($_POST["delete_order_item_validate"])) {
        $query = mysqli_query($conn,"DELETE FROM tb_order_list WHERE id_order_list='$id'");
        if ($query) {
        $message = '<script>
                        alert("Data berhasil dihapus");
                        window.location="../index.php?x=orderitem&order='.$order_code.'&table='.$table.'&customer='.$customer.'";
                    </script>';
        } else {
            $message = '<script>
                            alert("Data gagal dihapus");
                            window.location="../index.php?x=orderitem&order='.$order_code.'&table='.$table.'&customer='.$customer.'";
                        </script>';
        }
    
}echo $message;
?>