<?php

$localhost = "localhost";
$username = "root";
$password = "";
$database = "rental";



$koneksi = mysqli_connect($localhost, $username, $password, $database) or die("connection failed : " . mysqli_connect_error());
