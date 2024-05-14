<?php
//veri tabanı baglantısı
const host = "localhost";
const username= "root";
const password="";
const database="hastanedb";

$baglanti=mysqli_connect(host, username, password, database);

if(mysqli_connect_errno()>0)
{
   die("hata: ".mysqli_connect_errno());
}

// Tüm hastaları listeleme sorgusu
$query = "SELECT * FROM tbl_hasta";
$result = mysqli_query($baglanti, $query);

if(mysqli_num_rows($result) > 0) {
    echo "<h1>Tüm Hastaların Listesi</h1>";
    echo "<table border='1'>";
    echo "<tr><th>Ad</th><th>Soyad</th><th>TC Numarası</th><th>Telefon</th><th>Cinsiyet</th><th>Adres</th><th>Doğum Tarihi</th></tr>";
    
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['hastaAd']."</td>";
        echo "<td>".$row['hastaSoyad']."</td>";
        echo "<td>".$row['hastaTC']."</td>";
        echo "<td>".$row['hastaTelefon']."</td>";
        //echo "<td>".$row['hastasifre']."</td>";
        echo "<td>".$row['hastaCinsiyet']."</td>";
        echo "<td>".$row['hastaAdres']."</td>";
        echo "<td>".$row['hastaDogumTarihi']."</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Hiç hasta kaydı bulunamadı.";
}

// Veritabanı bağlantısını kapat
mysqli_close($baglanti);
?>
