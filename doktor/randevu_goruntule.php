<?php
session_start();

const host = "localhost";
const username= "root";
const password="";
const database="hastanedb2";

if(isset($_SESSION["doktorTC"])) {
    $doktorTC = $_SESSION["doktorTC"];

    // Veritabanı bağlantısı
    $baglanti=mysqli_connect(host, username, password, database);

    if (mysqli_connect_errno()) {
        die("Veritabanına bağlanırken bir hata oluştu: " . mysqli_connect_error());
    }

    // Doktorun kimliğini bul
    $sql_doktorID = "SELECT doktorid FROM tbl_doktor WHERE doktorTC = ?";
    $stmt_doktorID = mysqli_prepare($baglanti, $sql_doktorID);
    mysqli_stmt_bind_param($stmt_doktorID, "s", $doktorTC);
    mysqli_stmt_execute($stmt_doktorID);
    mysqli_stmt_store_result($stmt_doktorID);
    
    if(mysqli_stmt_num_rows($stmt_doktorID) == 1) {
        mysqli_stmt_bind_result($stmt_doktorID, $doktorID);
        mysqli_stmt_fetch($stmt_doktorID);

        // Aktif ve geçmiş randevuları ayırmak için tarih kontrolü
        $bugun = date("Y-m-d");
        $sql_randevular = "SELECT r.*, h.* 
                            FROM tbl_randevu r 
                            INNER JOIN tbl_hasta h ON r.hastaid = h.hastaid 
                            WHERE r.doktorid = ? 
                            ORDER BY r.randevuTarih";
        $stmt_randevular = mysqli_prepare($baglanti, $sql_randevular);
        mysqli_stmt_bind_param($stmt_randevular, "i", $doktorID);
        mysqli_stmt_execute($stmt_randevular);
        $result_randevular = mysqli_stmt_get_result($stmt_randevular);

        // Başlık sadece bir kez yazılsın
        $gecmis_randevu_basligi_yazildi = false;
        $aktif_randevu_basligi_yazildi = false;

        if(mysqli_num_rows($result_randevular) > 0) {
            while($row_randevu = mysqli_fetch_assoc($result_randevular)) {
                // Randevu tarihini al
                $randevu_tarih = $row_randevu["randevuTarih"];

                // Bugünden sonraki randevuları aktif olarak işaretle, geçmiş randevuları ayır
                if($randevu_tarih >= $bugun) {
                    if(!$aktif_randevu_basligi_yazildi) {
                        echo "<h2>Aktif Randevular</h2>";
                        $aktif_randevu_basligi_yazildi = true;
                    }
                } else {
                    if(!$gecmis_randevu_basligi_yazildi) {
                        echo "<h2>Geçmiş Randevular</h2>";
                        $gecmis_randevu_basligi_yazildi = true;
                    }
                }

                // Randevu bilgilerini yazdır
                echo "<p><b>Randevu Tarihi:</b> " . $row_randevu["randevuTarih"] . "</p>";
                echo "<p><b>Randevu Saati:</b> " . $row_randevu["randevuSaati"] . "</p>";
                echo "<p><b>Randevu Durumu:</b> " . $row_randevu["randevuDurum"] . "</p>";

                // Hasta bilgilerini yazdır
                echo "<p><b>Hasta Adı:</b> " . $row_randevu["hastaAd"] . "</p>";
                echo "<p><b>Hasta Soyadı:</b> " . $row_randevu["hastaSoyad"] . "</p>";
                echo "<p><b>Hasta TC:</b> " . $row_randevu["hastaTC"] . "</p>";

                echo "<br>"; // Her randevu arasında bir satır boşluk bırak
            }
        } else {
            echo "Randevu bulunamadı.";
        }
    } else {
        echo "Doktor bulunamadı.";
    }

    mysqli_stmt_close($stmt_doktorID);
    mysqli_close($baglanti);
} else {
    echo "Doktor girişi yapmadınız.";
}
?>
