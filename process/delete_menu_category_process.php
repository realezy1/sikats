<?php
include("connect.php");
$id = (isset($_POST["id"])) ? htmlentities($_POST["id"]) : "" ;

if (!empty($_POST["delete_menu_category_validate"])) {
    $select = mysqli_query($conn,"SELECT category FROM tb_menu_list WHERE category='$id'");
    if (mysqli_num_rows($select) > 0) {
        $message = '<script>
                    alert("Kategori telah digunakan pada daftar menu dan tidak dapat dihapus");
                    window.location="../index.php?x=category";
                    </script>';
    }else {
        $query = mysqli_query($conn,"DELETE FROM tb_category WHERE id_cat='$id'");
        if ($query) {
            $message = '<script>
                            alert("Data berhasil dihapus");
                            window.location="../index.php?x=category";
                        </script>';
        } else {
            $message = '<script>
                            alert("Data gagal dihapus");
                            window.location="../index.php?x=category";
                        </script>';
        }
    }
}echo $message;
?>