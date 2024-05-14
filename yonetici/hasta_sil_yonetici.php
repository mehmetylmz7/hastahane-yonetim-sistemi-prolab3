<?php
//veri tabanı bağlantısı
const host = "localhost";
const username= "root";
const password="";
const database="hastanedb";

$baglanti=mysqli_connect(host, username, password, database);

if(mysqli_connect_errno()>0)
{
   die("hata: ".mysqli_connect_errno());
}

// Formdan gelen verileri al
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hasta_tc = $_POST['hasta_tc'];

    // Veri doğrulama
    if (empty($hasta_tc)) {
        // Eğer herhangi bir alan boşsa
        echo "Lütfen tüm alanları doldurun.";
    } else {
        // Hastanın varlığını kontrol et
        $query_check = "SELECT * FROM tbl_hasta WHERE hastaTC = '$hasta_tc'";
        $result_check = mysqli_query($baglanti, $query_check);
        if (mysqli_num_rows($result_check) == 0) {
            // Hastanın olmadığına dair bir hata göster
            echo "Bu TC numarasına sahip bir hasta bulunamadı.";
        } else {
            // Hastanın randevularını sil
            $query_delete_randevu = "DELETE FROM tbl_randevu WHERE hastaid IN (SELECT hastaid FROM tbl_hasta WHERE hastaTC = '$hasta_tc')";
            if (mysqli_query($baglanti, $query_delete_randevu)) {
                // Hastanın tüm randevuları silindi, şimdi hastayı sil
                $query_delete_hasta = "DELETE FROM tbl_hasta WHERE hastaTC = '$hasta_tc'";
                if (mysqli_query($baglanti, $query_delete_hasta)) {
                    echo "Hasta başarıyla silindi.";
                } else {
                    echo "Hasta silinirken bir hata oluştu: " . mysqli_error($baglanti);
                }
            } else {
                echo "Hastanın randevularını silerken bir hata oluştu: " . mysqli_error($baglanti);
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
    <title>Hasta Silme</title>
</head>
<body>
    <h1>Silmek İstediğiniz Hastanın Gerekli Bilgilerini Giriniz</h1>

    <h2>Hastadan Bilgiler</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        
        <label for="tc">TC Numarası:</label><br>
        <input type="text" id="tc" name="hasta_tc"><br>

        <input type="submit" value="Sil">
    </form>

    <!-- Anasayfaya dön butonu -->
    <form action="http://localhost/courseapp/" method="get">
        <input type="submit" value="Anasayfaya Dön">
    </form>
</body>
</html>
