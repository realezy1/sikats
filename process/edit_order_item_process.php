<?php
session_start();
include("connect.php");
$order_code = (isset($_POST["order_code"])) ? htmlentities($_POST["order_code"]) : "" ;
$id = (isset($_POST["id"])) ? htmlentities($_POST["id"]) : "" ;
$table = (isset($_POST["table"])) ? htmlentities($_POST["table"]) : "" ;
$customer = (isset($_POST["customer"])) ? htmlentities($_POST["customer"]) : "" ;
$note = (isset($_POST["note"])) ? htmlentities($_POST["note"]) : "" ;
$menu = (isset($_POST["menu"])) ? htmlentities($_POST["menu"]) : "" ;
$quantity = (isset($_POST["quantity"])) ? htmlentities($_POST["quantity"]) : "" ;

if (!empty($_POST["edit_order_item_validate"])) {    
    $select = mysqli_query($conn,"SELECT * FROM tb_order_list WHERE menu='$menu' && `order`='$order_code'
    && id_order_list != $id");
    if (mysqli_num_rows($select) > 0) {
        $message = '<script>
                    alert("Item yang dimasukkan telah ada");
                    window.location="../index.php?x=orderitem&order='.$order_code.'&table='.$table.'&customer='.$customer.'";
                    </script>';
    }else {
        $query = mysqli_query($conn,"UPDATE tb_order_list SET menu='$menu',total='$quantity',note='$note' WHERE id_order_list='$id'");
        if ($query) {
        $message = '<script>
                        alert("Data berhasil dimasukkan");
                        window.location="../index.php?x=orderitem&order='.$order_code.'&table='.$table.'&customer='.$customer.'";
                    </script>';
        } else {
            $message = '<script>
                            alert("Data gagal dimasukkan");
                            window.location="../index.php?x=orderitem&order='.$order_code.'&table='.$table.'&customer='.$customer.'";
                        </script>';
        }
    }
}echo $message;
?>