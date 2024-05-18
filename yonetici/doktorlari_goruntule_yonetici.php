<?php
// Veritabanı bağlantısı
const host = "localhost";
const username= "root";
const password="";
const database="hastanedb2";

$baglanti=mysqli_connect(host, username, password, database);

if(mysqli_connect_errno()>0)
{
   die("Hata: ".mysqli_connect_errno());
}

// Tüm doktorları listeleme sorgusu
$query = "SELECT * FROM tbl_doktor";
$result = mysqli_query($baglanti, $query);

if(mysqli_num_rows($result) > 0) {
    echo "<h1>Tüm Doktorların Listesi</h1>";
    echo "<table border='1'>";
    echo "<tr><th>Ad</th><th>Soyad</th><th>Branş</th><th>TC Numarası</th></tr>";
    
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['doktorAd']."</td>";
        echo "<td>".$row['doktorSoyad']."</td>";
        echo "<td>".$row['doktorBrans']."</td>";
        echo "<td>".$row['doktorTC']."</td>";
        // echo "<td>".$row['doktorSifre']."</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Hiç doktor kaydı bulunamadı.";
}

// Veritabanı bağlantısını kapat
mysqli_close($baglanti);
?>
