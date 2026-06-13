<?php
include("connect.php");
$name = (isset($_POST["name"])) ? htmlentities($_POST["name"]) : "" ;
$username = (isset($_POST["username"])) ? htmlentities($_POST["username"]) : "" ;
$level = (isset($_POST["level"])) ? htmlentities($_POST["level"]) : "" ;
$mobile_number = (isset($_POST["mobile_number"])) ? htmlentities($_POST["mobile_number"]) : "" ;
$alamat = (isset($_POST["alamat"])) ? htmlentities($_POST["alamat"]) : "" ;
$password = md5('password');

if (!empty($_POST["input_user_validate"])) {    
    $select = mysqli_query($conn,"SELECT * FROM tb_user WHERE username='$username'");
    if (mysqli_num_rows($select) > 0) {
        $message = '<script>
                    alert("Username yang dimasukkan telah ada");
                    window.location="../index.php?x=user";
                    </script>';
    }else {
        $query = mysqli_query($conn,"INSERT INTO tb_user (name,username,level,mobile_number,alamat,password) 
        values ('$name','$username','$level','$mobile_number','$alamat','$password')");
        if ($query) {
        $message = '<script>
                        alert("Data berhasil dimasukkan");
                        window.location="../index.php?x=user";
                    </script>';
        } else {
            $message = '<script>
                            alert("Data gagal dimasukkan");
                        </script>';
        }
    }
}echo $message;
?>