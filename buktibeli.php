<!DOCTYPE html>
<html>
<head>
    <title>Bukti Pembelian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> Tugas PWS </title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        @media print{
            #tombol{
                display: none;
            } 
        }
 
        table, td, th {
            border: 1px solid gray;
        }
 
        table {
            border-collapse: collapse;
        }
 
        .tengah {
            width: 60%;
            margin: auto;
        }
 
        small{
            color: red;
        }

        .tengah h2 {
            color: #800080;
            margin-top: 3%;
            margin-left: 30%;
        }
    </style>
</head>
<body>
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
            $idhjual = $_GET['idhjual'];
            require_once "koneksi.php";
            $link = koneksi();
            $sql = "select * from hjual where idhjual = $idhjual ";
            $hasil = mysqli_query($link, $sql);
            $row = mysqli_fetch_array($hasil);
            echo "<pre>";
            echo "<h2>BUKTI PEMBELIAN</h2>";
            echo "<br>";
            echo "NO. NOTA : ".date("Ymd").$row['idhjual']."<br>";
            echo "TANGGAL : ".$row['tanggal']."<br>";
            echo "NAMA : ".$row['namacust']."<br>";
            $sql = "select album.nama, djual.harga, djual.qty, (djual.harga * djual.qty) as jumlah from djual inner join album on djual.idbarang = album.id where djual.idhjual = $idhjual ";
            $hasil = mysqli_query($link, $sql);
            echo "<br>";
            echo "<table>";
            echo "<tr>";
            echo " <th> Nama Barang </th>";
            echo " <th> Quantity </th>";
            echo " <th> Harga </th>";
            echo " <th> Jumlah </th>";
            echo "</tr>";

            $jumlah = 0;
            while($row = mysqli_fetch_array($hasil)){
                $jumlah += $row['jumlah'];
                echo "<tr>";
                echo " <td>".$row['nama']."</td>"; 
                echo " <td align='right'>".$row['qty']."</td>"; 
                echo " <td align='right'>".$row['harga']."</td>"; 
                echo " <td align='right'>".$row['jumlah']."</td>"; 
                echo "</tr>"; 
            }
            echo "<tr>";
            echo "<td>"."</td>";
            echo "<td>"."</td>";
            echo "<td align='right'><b> Total Harga </b></td>";
            echo "<td align='right'><b>".$jumlah."</b></td>";
            echo "</tr>";
 
            echo "</table>";
            echo "<br>";
            echo "</pre>";
        ?>
        <div id="tombol">
            <input type="button" value="Beli Lagi" onClick="window.location.assign('keranjang.php')">
            <input type="button" value="Print" onClick="window.print()">
        </div>
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