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

// Formdan gelen verileri al
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doktor_tc = $_POST['doktor_tc'];

    // Veri doğrulama
    if (empty($doktor_tc)) {
        // Eğer herhangi bir alan boşsa
        echo "Lütfen tüm alanları doldurun.";
    } else {
        // Doktorun varlığını kontrol et
        $query_check = "SELECT * FROM tbl_doktor WHERE doktorTC = '$doktor_tc'";
        $result_check = mysqli_query($baglanti, $query_check);
        if (mysqli_num_rows($result_check) == 0) {
            // Doktor yoksa
            echo "Bu TC numarasına sahip bir doktor bulunamadı.";
        } else {
            // Doktor varsa, randevularını kontrol et
            $row_doktor = mysqli_fetch_assoc($result_check);
            $doktorID = $row_doktor['doktorid'];

            // Doktorun aktif randevusu var mı kontrol et
            $query_check_randevu = "SELECT * FROM tbl_randevu WHERE doktorid = '$doktorID' AND randevuTarih >= CURDATE()";
            $result_check_randevu = mysqli_query($baglanti, $query_check_randevu);

            if (mysqli_num_rows($result_check_randevu) > 0) {
                // Doktorun aktif randevusu varsa
                echo "Doktorunuzun gelecek tarihlerde randevusu bulunmaktadır. Doktor silinemez.";
            } else {
                // Doktorun randevularını sil
                $query_delete_randevu = "DELETE FROM tbl_randevu WHERE doktorid = '$doktorID'";
                if (mysqli_query($baglanti, $query_delete_randevu)) {
                    // Randevular başarıyla silindi, şimdi doktoru silebiliriz
                    $query_delete_doktor = "DELETE FROM tbl_doktor WHERE doktorTC = '$doktor_tc'";
                    if (mysqli_query($baglanti, $query_delete_doktor)) {
                        echo "Doktor başarıyla silindi.";
                    } else {
                        echo "Doktor silinirken bir hata oluştu: " . mysqli_error($baglanti);
                    }
                } else {
                    echo "Randevuları silerken bir hata oluştu: " . mysqli_error($baglanti);
                }
            }
        }
    }
}

// Veritabanı bağlantısını kapat
mysqli_close($baglanti);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doktor Silme</title>
</head>
<body>
    <h1>Silmek İstediğiniz Doktorun Gerekli Bilgilerini Giriniz</h1>

    <h2>Doktordan Bilgiler</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        
        <label for="tc">TC Numarası:</label><br>
        <input type="text" id="tc" name="doktor_tc"><br>

        <input type="submit" value="Sil">
    </form>

    <!-- Anasayfaya dön butonu -->
    <form action="http://localhost/courseapp/" method="get">
        <input type="submit" value="Anasayfaya Dön">
    </form>
</body>
</html>
