<?php 
session_start();

// sunucu -> hastanedb

const host = "localhost";
const username= "root";
const password="";
const database="hastanedb";

$baglanti=mysqli_connect(host, username, password, database);

if(mysqli_connect_errno()>0)
{
    die("hata: ".mysqli_connect_errno());
}

echo"mysql baglantisi olusturuldu";

// Doktorun TC'sini al
if(isset($_SESSION["doktorTC"])) {
    $doktorTC = $_SESSION["doktorTC"];

    // Doktor ID'sini al
    $sql_doktor = "SELECT doktorid FROM tbl_doktor WHERE doktorTC = '$doktorTC'";
    $result_doktor = mysqli_query($baglanti, $sql_doktor);

    if (mysqli_num_rows($result_doktor) > 0) {
        $row_doktor = mysqli_fetch_assoc($result_doktor);
        $doktorID = $row_doktor["doktorid"];

        // Doktorun hastalarını al
        $sql_hastalar = "SELECT * FROM tbl_hasta WHERE hastaid IN (SELECT hastaid FROM tbl_randevu WHERE doktorid = '$doktorID')";
        $result_hastalar = mysqli_query($baglanti, $sql_hastalar);

        echo "<h2>Hastalar</h2>";
        echo "<form action='' method='post'>";
        echo "<select name='hastaID'>";
        // Varsayılan seçenek olarak "Seçiniz" ekle
        echo "<option value='' selected>Seçiniz</option>";
        // Hastaları seçenekler halinde listele
        while($row_hasta = mysqli_fetch_assoc($result_hastalar)) {
            echo "<option value='".$row_hasta["hastaid"]."'>".$row_hasta["hastaAd"]." ".$row_hasta["hastaSoyad"]."</option>";
        }
        echo "</select>";
        echo "<input type='submit' value='Seç'>";
        echo "</form>";

        // Seçilen hastanın geçmiş randevularını göster
        if(isset($_POST["hastaID"])) {
            $selectedHastaID = $_POST["hastaID"];
            $sql_gecmis_randevular = "SELECT * FROM tbl_randevu WHERE hastaid = '$selectedHastaID'";
            $result_gecmis_randevular = mysqli_query($baglanti, $sql_gecmis_randevular);

            echo "<h2>Seçilen Hastanın Geçmiş Randevuları</h2>";
            echo "<table><tr><th>Randevu ID</th><th>Tarih</th><th>Saat</th><th>Durum</th><th>Doktor Adı</th><th>Branşı</th></tr>";
            while($row_randevu = mysqli_fetch_assoc($result_gecmis_randevular)) {
                $randevuID = $row_randevu["randevuid"];
                $doktorID = $row_randevu["doktorid"];
                // Doktorun adını ve branşını al
                $sql_doktor_info = "SELECT doktorAd, doktorBrans FROM tbl_doktor WHERE doktorid = '$doktorID'";
                $result_doktor_info = mysqli_query($baglanti, $sql_doktor_info);
                $row_doktor_info = mysqli_fetch_assoc($result_doktor_info);
                $doktorAd = $row_doktor_info["doktorAd"];
                $doktorBrans = $row_doktor_info["doktorBrans"];
                echo "<tr><td>".$randevuID."</td><td>".$row_randevu["randevuTarih"]."</td><td>".$row_randevu["randevuSaati"]."</td><td>".$row_randevu["randevuDurum"]."</td><td>".$doktorAd."</td><td>".$doktorBrans."</td></tr>";
            }
            echo "</table>";
        }
    } else {
        die("Doktor bulunamadı.");
    }
}

mysqli_close($baglanti);

echo"mysql baglantisi kapatildi";
?>
