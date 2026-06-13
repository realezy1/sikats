<?php
session_start();
include("connect.php");
$id = (isset($_POST["id"])) ? htmlentities($_POST["id"]) : "" ;
$oldpassword = (isset($_POST["oldpassword"])) ? md5(htmlentities($_POST["oldpassword"])) : "" ;
$newpassword = (isset($_POST["newpassword"])) ? md5(htmlentities($_POST["newpassword"])) : "" ;
$confirmnewpassword = (isset($_POST["confirmnewpassword"])) ? md5(htmlentities($_POST["confirmnewpassword"])) : "" ;

if (!empty($_POST['change_password_validate'])) {
    $query = mysqli_query($conn,"SELECT * FROM tb_user WHERE username = '$_SESSION[username_sikats]' && password = '$oldpassword'");
    $hasil = mysqli_fetch_array($query);
    if($hasil) {
        if ($newpassword == $confirmnewpassword) {
            $query = mysqli_query($conn,"UPDATE tb_user SET password='$newpassword' WHERE username = '$_SESSION[username_sikats]'");
            if ($query) {
                $message = '<script>
                                alert("Password berhasil diubah");
                                window.history.back();
                            </script>';
            }else {
                $message = '<script>
                        alert("Password gagal diubah");
                        window.history.back();
                    </script>';
            }
        }else{
            $message = '<script>
                        alert("Password baru tidak sesuai");
                        window.history.back();
                    </script>';
        }
    }else {
        $message = '<script>
                        alert("Password lama tidak sesuai");
                        window.history.back();
                    </script>';
    }
  }else{
    header('location:../index.php?x=home');
  }
echo $message;
?>