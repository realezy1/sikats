<?php

$conn = mysqli_connect("localhost", "root", "", "db_sikats");

if (!$conn) {
    die("Koneksi gagal");
}