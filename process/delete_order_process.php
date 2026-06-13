<?php
include("connect.php");
$order_code = (isset($_POST["order_code"])) ? htmlentities($_POST["order_code"]) : "" ;

if (!empty($_POST["delete_order_validate"])) {
    $select = mysqli_query($conn,"SELECT `order` FROM tb_order_list WHERE `order`='$order_code'");
    if(mysqli_num_rows($select) > 0) {
        $message = '<script>
                        alert("Order telah memiliki item order, data tidak dapat dihapus");
                        window.location="../index.php?x=order";
                    </script>';
    }else{
        $query = mysqli_query($conn,"DELETE FROM tb_order WHERE id_order='$order_code'");
        if ($query) {
        $message = '<script>
                        alert("Data berhasil dihapus");
                        window.location="../index.php?x=order";
                    </script>';
        } else {
            $message = '<script>
                            alert("Data gagal dihapus");
                            window.location="../index.php?x=order";
                        </script>';
        }
    }
}echo $message;
?>