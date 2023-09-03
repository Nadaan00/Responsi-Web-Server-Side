<?php  
  require_once __DIR__ . '/koneksi.php';
  
  function tambahAlbum($nama,$harga,$stok){
    $link = koneksi();
    $sql="insert into album(nama,harga,stok) values ('$nama',$harga,$stok)";
    $hasil=mysqli_query($koneksi, $sql);

    if($hasil){
      return true; 
    }else{
      return false;;
    }
  }

  function bacaAlbum($sql){
    require_once "koneksi.php";
    $hasils = array();
    $link = koneksi();
  
    if($result = mysqli_query($link, $sql)){
      if(mysqli_num_rows($result) > 0){
        $i = 0;
        while($row = mysqli_fetch_array($result)){
          $hasils[$i]['id']=$row['id'];
          $hasils[$i]['nama']=$row['nama'];
          $hasils[$i]['harga']=$row['harga'];
          $hasils[$i]['stok']=$row['stok'];
          $hasils[$i]['foto']=$row['foto'];
          $i++;
        }
        mysqli_free_result($result);
      }
    } else{
      die ("ERROR: Tidak dapat menjalankan $sql:". mysqli_error($koneksi));
    }
 
    mysqli_close($link);
    return $hasils;
  }

  function hapusAlbum($id){
    $link = koneksi();
    $sql="delete from album where id = $id";
    $hasil=mysqli_query($link, $sql);
    mysqli_close($link);

    if($hasil){
      return true; 
    }else{
      return false;;
    }
  }

  function updateAlbum($id, $nama, $harga, $stok){
    $link = koneksi();
    $sql="update album set 
      nama = '$nama',
      harga = $harga,
      stok = $stok
      where id = $id";
    $hasil=mysqli_query($link, $sql);
    mysqli_close($link);

    if($hasil){
      return true; 
    }else{
      return false;;
    }
  }
?>

</body>
</html>