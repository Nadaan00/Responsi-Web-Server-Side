<?php
    $albumPilih = 0;
    if(isset($_COOKIE['keranjang'])){
        $albumPilih = $_COOKIE['keranjang'];
    }

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $albumPilih = str_replace((",".$id),"",$albumPilih);
        setcookie('keranjang', $albumPilih, time()+3600);
    }

    $namacustErr = "";
    $emailErr = "";
    $notelpErr = "";
    $albumPilihErr = "";
    $namacust = "";
    $email = "";
    $notelp = "";
    $request_method = strtoupper($_SERVER['REQUEST_METHOD']);

    if ($request_method === 'POST'){
        $namacust = htmlspecialchars($_POST['namacust']);
        if(empty($namacust)){
            $namacustErr = "Nama belum diisi";
        }

        $email = htmlspecialchars($_POST['email']);
        if(empty($email)){
            $emailErr = "Email belum diisi";
        }
        
        $notelp = htmlspecialchars($_POST['notelp']);
        if(empty($notelp)){
            $notelpErr = "No. Telepon belum diisi";
        }

        $tanggal = date("Y-m-d");
 
        if(!isset($_COOKIE['keranjang'])){
            $albumPilihErr = "<br><small>Keranjang belanja kosong</small><br>"; 
        }
 
        if(empty($namacustErr) && empty($emailErr) && empty($notelpErr) && empty($albumPilihErr)){
            $qty = 1;
            $simpan = true;
            require_once "koneksi.php";
            $link = koneksi();
 
            $mulaiTransaksi = mysqli_begin_transaction($link);
            $sql = "insert into hjual (tanggal, namacust, email, notelp) value ('$tanggal','$namacust','$email','$notelp')";
            $hasil = mysqli_query($link, $sql);
            
            if(!$hasil){
                echo "Data customer gagal disimpan <br>";
                $simpan = false;
            }

            $idhjual = mysqli_insert_id($link);
            
            if($idhjual == 0){
                echo "Data customer tidak ada <br>";
                $simpan = false;
            }
 
            $album_array = explode(",", $albumPilih);
            $jumlah = count($album_array);

            if($jumlah == 0){
                echo "Tidak ada barang yang dipilih<br>";
                $simpan = false;
            }else{
                foreach($album_array as $idalbum){
                    if($idalbum == 0){
                        continue;
                    }
                    $sql = "select * from album where id = $idalbum ";
                    $hasil = mysqli_query($link, $sql);
                    
                    if(!$hasil){
                        echo " tidak ada<br>";
                        $simpan = false;
                        break;
                    }else{
                        $row = mysqli_fetch_array($hasil);
                        $stok = $row['stok'] - 1;
                        if($stok < 0){
                            echo "Stok album ".row['nama']." kosong<br>";
                            $simpan = false;
                            break;
                        }
                        $harga = $row['harga'];
                    }

                    $sql = "insert into djual (idhjual,idbarang,qty,harga) values ('$idhjual','$idalbum','$qty','$harga')";
                    $hasil = mysqli_query($link, $sql);
                    if(!$hasil){
                        echo "Detail jual gagal simpan<br>";
                        $simpan = false();
                        break; 
                    }

                    $sql = "update album set stok = $stok where id = $idalbum ";
                    $hasil = mysqli_query($link, $sql);
                    if(!$hasil){
                        echo "Update stok album gagal<br>";
                        $simpan = false;
                        break;
                    }
                } 
            }

            if($simpan){
                $komit = mysqli_commit($link); 
                echo "Pembelian berhasil<br>"; 
            }else{
                $rollback = mysqli_rollback($link);
                echo "Pembelian gagal<br>";
            }
            setcookie('keranjang',$albumPilih,time()-3600);
            header("Location: buktibeli.php?idhjual=$idhjual");
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembelian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> Tugas PWS </title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        table, td, th {
            border: 1px solid gray;
            margin-top: 2%;
        }
 
        table {
            border-collapse: collapse;
            margin-left: 30%;
        }
 
        .tengah{
            margin: auto;
        }
 
        small{
            color: red;
        }

        .tengah h2 {
            color: 	#800080;
            text-align: center;
            margin-top: 3%;
        } 

        form {
            margin-left: 30%;
            margin-top: 2%
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
            <h2>DATA PEMBELI </h2>
            <form class="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <label>
                    Nama:<br>
                    <input type="text" name="namacust" value="<?php echo $namacust;?>"><br>
                </label>
        
                <?php if(!empty($namacustErr)) echo "<small>$namacustErr</small><br>"; ?>

                <label>
                    <br>Email:<br>
                    <input type="email" name="email" value="<?php echo $email;?>"><br>
                </label>
        
                <?php if(!empty($emailErr)) echo "<small>$emailErr</small><br>"; ?>
        
                <label>
                    <br>No. Telepon:<br>
                    <input type="text" name="notelp" value="<?php echo $notelp;?>"><br>
                </label>
        
                <?php if(!empty($notelpErr)) echo "<small>$notelpErr</small><br>"; ?>
            
                <br><button type="submit">Simpan</button>
            </form>

            <?php
                if(!empty($albumPilihErr)){
                    echo $albumPilihErr;
                }
            ?>

            <?php
                require_once 'album.php';
                $sql = "select * from album where id in (".$albumPilih.")order by id desc";
                echo "<br><hr>";
                $hasils = bacaAlbum($sql);
                echo "<h2>KERANJANG BELANJA</h2>";
 
                if(count($hasils) > 0){
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
                        echo "<td><a href='$_SERVER[PHP_SELF]?id={$hasil['id']}'>Batal</a></td>"; 
                        echo "</tr>\n"; 
                    }
                    echo "</table>";
                }
            ?>
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