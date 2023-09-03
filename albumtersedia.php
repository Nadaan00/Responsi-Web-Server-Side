<?php
session_start();
?>

<?php
    $albumPilih = 0;
    if(isset($_COOKIE['keranjang'])){
        $albumPilih = $_COOKIE['keranjang'];
    }

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $albumPilih = $albumPilih.",".$id;
        setcookie('keranjang', $albumPilih, time()+3600);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Album Tersedia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> Tugas PWS </title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        table, td, th {
            border: 1px solid gray;
            margin-top: 5%;
            margin-left: 28%;
        }
 
        table {
            border-collapse: collapse;
        }
 
        .tengah{
            margin: auto;
        }

        .tengah h3  {
            color: #800080;
            text-align: center;
            margin-top: 3%;
        }

        input {
            margin-left: 28%;
            margin-top: 2%
        }
    </style>
</head>
<body>
    <?php
        if(!isset($_SESSION['username'])) {
            header("Location: login.php");  
        }
    ?>

         <!--header-->
         <div class="medsos">
            <div class="container">
                <ul>
                    <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                    <li><a href="https://www.instagram.com/ndaa.an_/"><i class="fa-brands fa-instagram"></i></a></li>
                    <li><a href="https://alvo.chat/NY"><i class="fa-brands fa-whatsapp"></i></a></li>
                </ul>
            </div>
        </div>

        <header>
            <div class="container">
            <h1><a href="index.html"> Borahae Shop </a></h1>
            <ul>
                <li><a href="index.php"> Home </a></li>
                <li class="active"><a href="albumtersedia.php"> Album </a></li> 
                <li><a href="keranjang.php"> Keranjang </a></li>
                <li><a href="login.php"> Login </a></li>
                <li><a href="logout.php"> Logout </a></li>
            </ul>
            </div>
        </header>

        <!--banner-->
        <section class="banner">
            <h2> Welcome To Our Magic Shop </h2>
        </section>

    <div class="tengah">
        <?php
            require_once 'album.php';
            $sql = "select * from album where id not in (".$albumPilih.") and stok > 0 order by id desc";
            $hasils = bacaAlbum($sql);
            if(count($hasils) > 0){
                echo "<h3>DAFTAR ALBUM TERSEDIA</h3>";
                echo "<table>";
                echo "<tr>
                <th>Foto</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Operasi</th>
                </tr>";
            
                foreach($hasils as $hasil){
                    echo "<tr>";
                    echo "<td><img src='album/{$hasil['foto']}' width='100'></td>"; 
                    echo "<td>{$hasil['nama']}</td>"; 
                    echo "<td>{$hasil['harga']}</td>"; 
                    echo "<td>{$hasil['stok']}</td>"; 
                    echo "<td><a href='$_SERVER[PHP_SELF]?id={$hasil['id']}'>Beli</a></td>"; 
                    echo "</tr>\n"; 
                }
            
                echo "</table>";
            }
        ?>

        <input type="button" value="Beli" onClick="window.location.assign('beli.php')">
    </div>
</body>
<!--footer-->
<footer>
    <div class="container">
        <small> Copyright &copy; 2022 - Nada Anis Nurjihan, All Rights Reserved,
            Pemrograman Web Server Side</small>
    </div>
</footer>
</html>