<?php
include("connect.php");
$id = (isset($_POST["id"])) ? htmlentities($_POST["id"]) : "" ;
$photo = (isset($_POST["photo"])) ? htmlentities($_POST["photo"]) : "" ;
if (!empty($_POST["input_user_validate"])) {
    $query = mysqli_query($conn,"DELETE FROM tb_menu_list WHERE id='$id'");
    if ($query) {
        unlink("../assets/img/");
        $message = '<script>
                        alert("Data berhasil dihapus");
                        window.location="../index.php?x=menu";
                    </script>';
    } else {
        $message = '<script>
                        alert("Data gagal dihapus");
                        window.location="../index.php?x=menu";
                    </script>';
    }
}echo $message;
?>