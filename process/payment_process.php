<?php
session_start();
include("connect.php");
$order_code = (isset($_POST["order_code"])) ? htmlentities($_POST["order_code"]) : "" ;
$table = (isset($_POST["table"])) ? htmlentities($_POST["table"]) : "" ;
$customer = (isset($_POST["customer"])) ? htmlentities($_POST["customer"]) : "" ;
$total_money = (isset($_POST["total_money"])) ? htmlentities($_POST["total_money"]) : "" ;
$money = (isset($_POST["money"])) ? htmlentities($_POST["money"]) : "" ;

$money = str_replace('.', '', $money);
$total_money = str_replace('.', '', $total_money);

$change = (int)$money - (int)$total_money;

if (!empty($_POST["payment_validate"])) {    
    if($change<0){
    $message='<script>
                alert("Your amount of money is not enough");
                window.location="../index.php?x=orderitem&order='.$order_code.'&table='.$table.'&customer='.$customer.'";
              </script>';
    }else{
        $query = mysqli_query($conn,"INSERT INTO tb_payment (id_payment,money,total_money) 
        values ('$order_code','$money','$total_money')");
        if ($query) {
        $message = '<script>
                        alert("Payment successful \nYour change Rp.'.$change.'");
                        window.location="../index.php?x=orderitem&order='.$order_code.'&table='.$table.'&customer='.$customer.'";
                    </script>';
        } else {
            $message = '<script>
                            alert("Payment failed");
                            window.location="../index.php?x=orderitem&order='.$order_code.'&table='.$table.'&customer='.$customer.'";
                        </script>';
        }
    }
}
echo $message;
?>