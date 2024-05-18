<?php
// sunucu -> hastanedb2
const host = "localhost";
const username= "root";
const password="";
const database="hastanedb2";

$baglanti=mysqli_connect(host, username, password, database);

if(mysqli_connect_errno()>0) {
    die("hata: ".mysqli_connect_errno());
}

echo "MySQL bağlantısı oluşturuldu";

// Oturum başlat
session_start();

// Eğer oturum yoksa veya hastaTC oturumda tanımlı değilse, kullanıcıyı yönlendir.
if (!isset($_SESSION["hastaTC"])) {
    header("Location: http://localhost/courseapp/");
    exit;
}

// Oturumda hasta TC bilgisi mevcut ise, onu al.
$hastaTC = $_SESSION["hastaTC"];

// Hasta TC'sine göre hastaID'yi bul
$sql_hastaID = "SELECT hastaID FROM tbl_hasta WHERE hastaTC = '$hastaTC'";
$result_hastaID = mysqli_query($baglanti, $sql_hastaID);

if ($result_hastaID) {
    $row_hastaID = mysqli_fetch_assoc($result_hastaID);
    $hastaID = $row_hastaID['hastaID'];

    // HastaID'ye göre randevuları çek
    $sql_randevular = "SELECT * FROM tbl_randevu WHERE hastaid = '$hastaID'";
    $result_randevular = mysqli_query($baglanti, $sql_randevular);

    if ($result_randevular) {
        if (mysqli_num_rows($result_randevular) > 0) {
            echo "<h2>Randevularınız</h2>";
            echo "<ul>";
            while($row_randevu = mysqli_fetch_assoc($result_randevular)) {
                echo "<li>Randevu Tarihi: " . $row_randevu["randevuTarih"]. "</li>";
                echo "<li>Randevu Saati: " . $row_randevu["randevuSaati"]. "</li>";
                echo "<li>Randevu Durumu: " . $row_randevu["randevuDurum"]. "</li>";
            
                // Doktor bilgisini getir
                $doktorID = $row_randevu["doktorid"];
                $sql_doktor = "SELECT doktorAd, doktorBrans FROM tbl_doktor WHERE doktorid = '$doktorID'";
                $result_doktor = mysqli_query($baglanti, $sql_doktor);
                $row_doktor = mysqli_fetch_assoc($result_doktor);
                echo "<li>Doktor Adı: " . $row_doktor["doktorAd"] . "</li>";
                echo "<li>Doktor Branşı: " . $row_doktor["doktorBrans"] . "</li>";
            
                // Diğer randevu bilgilerini de buraya ekleyebilirsiniz.
                echo "<br>";            
            }
            
            echo "</ul>";
        } else {
            echo "Henüz randevunuz bulunmamaktadır.";
        }
    } else {
        echo "Randevu sorgusu hatası: " . mysqli_error($baglanti);
    }
} else {
    echo "HastaID sorgusu hatası: " . mysqli_error($baglanti);
}

// Veritabanı bağlantısını kapat
mysqli_close($baglanti);

echo "MySQL bağlantısı kapatıldı";
?>
