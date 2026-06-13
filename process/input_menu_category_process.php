<?php
include("connect.php");
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
        $query = mysqli_query($conn,"INSERT INTO tb_category (menu_type,menu_category) 
        values ('$menu_type','$menu_category')");
        if ($query) {
        $message = '<script>
                        alert("Data berhasil dimasukkan");
                        window.location="../index.php?x=category";
                    </script>';
        } else {
            $message = '<script>
                            alert("Data gagal dimasukkan");
                            window.location="../index.php?x=category";
                        </script>';
        }
    }
}echo $message;
?>