<?php
session_start();
include("connect.php");
$id = (isset($_POST["id"])) ? htmlentities($_POST["id"]) : "" ;
$note = (isset($_POST["note"])) ? htmlentities($_POST["note"]) : "" ;

if (!empty($_POST["accept_validate"])) {    
        $query = mysqli_query($conn,"UPDATE tb_order_list SET note='$note', status=1 WHERE id_order_list='$id'");
        if ($query) {
        $message = '<script>
                        alert("Pesanan berhasil diterima oleh dapur");
                        window.location="../index.php?x=kitchen";
                    </script>';
        } else {
            $message = '<script>
                            alert("Pesanan gagal diterima oleh dapur");
                            window.location="../index.php?x=kitchen";
                        </script>';
        }
}echo $message;
?>