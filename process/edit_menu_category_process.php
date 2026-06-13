<?php
include("connect.php");
$id = (isset($_POST["id"])) ? htmlentities($_POST["id"]) : "" ;
$menu_type = (isset($_POST["menu_type"])) ? htmlentities($_POST["menu_type"]) : "" ;
$menu_category = (isset($_POST["menu_category"])) ? htmlentities($_POST["menu_category"]) : "" ;

if (!empty($_POST["input_menu_category_validate"])) {
    $select = mysqli_query($conn,"SELECT menu_category FROM tb_category WHERE menu_category='$menu_category'");
    if (mysqli_num_rows($select) > 0) {
        $message = '<script>
                    alert("Kategori yang dimasukkan telah ada");
                    window.location="../index.php?x=category";
                    </script>';
    }else {
        $query = mysqli_query($conn,"UPDATE tb_category SET menu_type='$menu_type', menu_category='$menu_category' 
        WHERE id_cat='$id'");
        if ($query) {
        $message = '<script>
                        alert("Kategori berhasil dimasukkan");
                        window.location="../index.php?x=category";
                    </script>';
        } else {
            $message = '<script>
                            alert("Kategori gagal dimasukkan");
                            window.location="../index.php?x=category";
                        </script>';
        }
    }
}echo $message;
?>