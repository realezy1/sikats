<?php
include("connect.php");
$name = (isset($_POST["name"])) ? htmlentities($_POST["name"]) : "" ;
$description = (isset($_POST["description"])) ? htmlentities($_POST["description"]) : "" ;
$menu_category = (isset($_POST["menu_category"])) ? htmlentities($_POST["menu_category"]) : "" ;
$price = (isset($_POST["price"])) ? htmlentities($_POST["price"]) : "" ;
$stock = (isset($_POST["stock"])) ? htmlentities($_POST["stock"]) : "" ;

$kode_rand = rand(10000,999999)."-";
$target_dir = "../assets/img/".$kode_rand;
$target_file = $target_dir .basename($_FILES["photo"]["name"]);
$imageType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));    

if (!empty($_POST["input_menu_validate"])) {    
// Check is that image or not
$cek = getimagesize($_FILES["photo"]["tmp_name"]);
if ($cek === false) {
    $message = "Ini bukan file gambar";
    $statusUpload = 0;
}else{
    $statusUpload = 1;
    if(file_exists($target_file)){
        $message = "Maaf, file yang dimasukkan telah ada";
        $statusUpload = 0;
    }else{
        if($_FILES["photo"]["size"] > 500000){ //500Kb
            $message = "File foto yang diupload terlalu besar";
            $statusUpload = 0;
        }else{
            if($imageType != "jpg" && $imageType != "png" && $imageType != "jpeg" && $imageType != "gif"){
                $message = "Maaf, hanya diperbolehkan gambar yang memiliki format JPG, PNG, JPEG, GIF";
                $statusUpload = 0;
            }
        }
    }
}

if($statusUpload == 0){
    $message = '<script>
                    alert("'.$message.', gambar tidak dapat diupload");
                    window.location="../index.php?x=menu";
                </script>';
}else{
    $select = mysqli_query($conn,"SELECT * FROM tb_menu_list WHERE name='$name'");
    if (mysqli_num_rows($select) > 0) {
        $message = '<script>
                        alert("Nama menu yang dimasukkan telah ada");   
                        window.location="../index.php?x=menu";
                    </script>';
    }else{
        if(move_uploaded_file($_FILES["photo"]["tmp_name"],$target_file)){
            $query = mysqli_query($conn,"INSERT INTO tb_menu_list (photo,name,description,category,price,stock) 
                    values ('". $kode_rand.$_FILES["photo"]["name"]."','$name','$description','$menu_category','$price','$stock')");
            if ($query) {
            $message = '<script>    
                            alert("Data berhasil dimasukkan");
                            window.location="../index.php?x=menu";
                        </script>';
            } else {
                $message = '<script>
                                alert("Data gagal dimasukkan");
                                window.location="../index.php?x=menu";
                            </script>';
            }
        }else{
            $message = '<script>
                            alert("Maaf, terjadi kesalahan file tidak dapat diupload");
                            window.location="../index.php?x=menu";
                        </script>';
        }
    }
}
}
echo $message;
?>