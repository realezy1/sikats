<?php
include("connect.php");

$id = isset($_POST["id"]) ? htmlentities($_POST["id"]) : "";
$name = isset($_POST["name"]) ? htmlentities($_POST["name"]) : "";
$description = isset($_POST["description"]) ? htmlentities($_POST["description"]) : "";
$menu_category = isset($_POST["menu_category"]) ? htmlentities($_POST["menu_category"]) : "";
$price = isset($_POST["price"]) ? htmlentities($_POST["price"]) : "";
$stock = isset($_POST["stock"]) ? htmlentities($_POST["stock"]) : "";

if (!empty($_POST["input_menu_validate"])) {
    $select = mysqli_query(
        $conn,
        "SELECT * FROM tb_menu_list WHERE name='$name' AND id != '$id'"
    );
    if (mysqli_num_rows($select) > 0) {
        $message = '<script>
                        alert("Nama menu yang dimasukkan telah ada");
                        window.location="../index.php?x=menu";
                    </script>';
    } else {
        // TIDAK GANTI FOTO
        if ($_FILES["photo"]["error"] == 4) {
            $query = mysqli_query(
                $conn,
                "UPDATE tb_menu_list SET
                name='$name',
                description='$description',
                category='$menu_category',
                price='$price',
                stock='$stock'
                WHERE id='$id'"
            );
        } else {
            // GANTI FOTO
            $kode_rand = rand(10000,999999)."-";
            $target_dir = "../assets/img/".$kode_rand;
            $target_file = $target_dir . basename($_FILES["photo"]["name"]);
            $imageType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $cek = getimagesize($_FILES["photo"]["tmp_name"]);
            if ($cek === false) {
                $message = '<script>
                                alert("File yang dipilih bukan gambar");
                                window.location="../index.php?x=menu";
                            </script>';
                echo $message;
                exit();
            }
            if ($_FILES["photo"]["size"] > 500000) {
                $message = '<script>
                                alert("File foto yang diupload terlalu besar");
                                window.location="../index.php?x=menu";
                            </script>';
                echo $message;
                exit();
            }

            if (
                $imageType != "jpg" &&
                $imageType != "jpeg" &&
                $imageType != "png" &&
                $imageType != "gif"
            ) {
                $message = '<script>
                                alert("Format gambar harus JPG, JPEG, PNG, atau GIF");
                                window.location="../index.php?x=menu";
                            </script>';
                echo $message;
                exit();
            }
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $query = mysqli_query(
                    $conn,
                    "UPDATE tb_menu_list SET
                    photo='" . $kode_rand . $_FILES['photo']['name'] . "',
                    name='$name',
                    description='$description',
                    category='$menu_category',
                    price='$price',
                    stock='$stock'
                    WHERE id='$id'"
                );
            } else {
                $message = '<script>
                                alert("Maaf, terjadi kesalahan file tidak dapat diupload");
                                window.location="../index.php?x=menu";
                            </script>';
                echo $message;
                exit();
            }
        }
        if ($query) {
            $message = '<script>
                            alert("Data berhasil diubah");
                            window.location="../index.php?x=menu";
                        </script>';
        } else {
            $message = '<script>
                            alert("Data gagal diubah");
                            window.location="../index.php?x=menu";
                        </script>';
        }
    }
    echo $message;
}
?>