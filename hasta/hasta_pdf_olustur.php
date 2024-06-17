<?php
session_start();

require('fpdf/fpdf.php');

// İlgili hasta TC'sini al
$hastaTC = $_SESSION["hastaTC"];

// JSON dosyasının yolunu oluştur
$json_dosyasi_yolu = 'C:/xampp/htdocs/courseapp/tibbi_raporlar/' . $hastaTC . '_raporlar.json';

// JSON dosyasını oku ve içeriği bir değişkene at
$json_verisi = file_get_contents($json_dosyasi_yolu);

// JSON verisini diziye çevir
$hasta_verileri = json_decode($json_verisi, true);

// PDF dosyasının yolunu oluştur
$pdf_dosyasi_yolu = 'C:/xampp/htdocs/courseapp/tibbi_raporlar/' . $hastaTC . '_raporlar.pdf';

class PDF extends FPDF
{
    // Başlık
    function Header()
    {
        $date = date('d/m/Y');
        $this->SetFont('courier', 'B', 20);
        $this->Cell(150);
        $this->Cell(30, 10, 'HASTA RAPORU', 0, 1, 'C');
        $this->SetFont('courier', '', 15);
        $this->Cell(140);
        $this->Cell(30, 10, 'TARIH: ' . $date, 0, 1, 'C');
        $this->Ln(10);
    }

    // Hasta bilgileri tablosu
    function HastaBilgileriTable($hasta_bilgileri)
    {
        $this->SetFont('courier', 'B', 16);
        $this->Cell(0, 10, 'Hasta Bilgileri', 0, 1, 'L');
        $this->SetFont('courier', '', 12);
        foreach ($hasta_bilgileri as $key => $value) {
            $this->Cell(50, 10, ucfirst($key), 1);
            $this->Cell(0, 10, $value, 1, 1);
        }
        $this->Ln(10);
    }

    // Tıbbi raporlar tablosu
    function TibbiRaporlarTable($tibbi_raporlar)
    {
        $this->SetFont('courier', 'B', 16);
        $this->Cell(0, 10, 'Tıbbi Raporlar', 0, 1, 'L');
        $this->SetFont('courier', 'i', 13);
        $this->Cell(20, 6, 'ID', 1, 0, 'C');
        $this->Cell(50, 6, 'Tarih', 1, 0, 'C');
        $this->Cell(120, 6, 'İçerik', 1, 1, 'C');
        foreach ($tibbi_raporlar as $rapor) {
            $this->Cell(20, 6, $rapor['raporid'], 1, 0, 'C');
            $this->Cell(50, 6, $rapor['raporTarihi'], 1, 0, 'C');
            $this->Cell(120, 6, $rapor['raporIcerigi'], 1, 1, 'C');
        }
        $this->Ln(10);
    }

    // Geçmiş randevular tablosu
    function GecmisRandevularTable($gecmis_randevular)
    {
        $this->SetFont('courier', 'B', 16);
        $this->Cell(0, 10, 'Geçmiş Randevular', 0, 1, 'L');
        $this->SetFont('courier', 'i', 13);
        $this->Cell(20, 6, 'ID', 1, 0, 'C');
        $this->Cell(40, 6, 'Tarih', 1, 0, 'C');
        $this->Cell(30, 6, 'Saat', 1, 0, 'C');
        $this->Cell(40, 6, 'Doktor Adı', 1, 0, 'C');
        $this->Cell(40, 6, 'Soyadı', 1, 0, 'C');
        $this->Cell(30, 6, 'Branş', 1, 0, 'C');
        $this->Cell(30, 6, 'Durum', 1, 1, 'C');
        foreach ($gecmis_randevular as $randevu) {
            $this->Cell(20, 6, $randevu['randevuid'], 1, 0, 'C');
            $this->Cell(40, 6, $randevu['randevuTarih'], 1, 0, 'C');
            $this->Cell(30, 6, $randevu['randevuSaati'], 1, 0, 'C');
            $this->Cell(40, 6, $randevu['doktorAd'], 1, 0, 'C');
            $this->Cell(40, 6, $randevu['doktorSoyad'], 1, 0, 'C');
            $this->Cell(30, 6, $randevu['doktorBrans'], 1, 0, 'C');
            $this->Cell(30, 6, $randevu['randevuDurum'], 1, 1, 'C');
        }
        $this->Ln(10);
    }

    // Alt başlık
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('courier', 'i', 20);
        $this->Cell(200, 10, 'Bu rapor hasta bilgilerini içermektedir', 0, 0, 'C');
    }
}

// PDF oluştur
$pdf = new PDF();
$pdf->AddPage('P', 'A4');

// Hasta bilgilerini ekle
$pdf->HastaBilgileriTable($hasta_verileri['hasta_bilgileri']);

// Tıbbi raporları ekle
if (!empty($hasta_verileri['tibbi_raporlar'])) {
    $pdf->TibbiRaporlarTable($hasta_verileri['tibbi_raporlar']);
}

// Geçmiş randevuları ekle
if (!empty($hasta_verileri['gecmis_randevular'])) {
    $pdf->GecmisRandevularTable($hasta_verileri['gecmis_randevular']);
}

// PDF dosyasını oluştur ve kaydet
$pdf->Output('F', $pdf_dosyasi_yolu);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tıbbi Raporlar</title>
</head>
<body>
    <h1>Tıbbi Raporlar</h1>

    <?php if (file_exists($pdf_dosyasi_yolu)) { ?>
        <!-- PDF görüntüleyici -->
        <iframe src="<?php echo 'http://localhost/courseapp/tibbi_raporlar/' . $hastaTC . '_raporlar.pdf'; ?>" width="100%" height="500px"></iframe>

        <!-- PDF indirme butonu -->
        <a href="<?php echo 'http://localhost/courseapp/tibbi_raporlar/' . $hastaTC . '_raporlar.pdf'; ?>" download>
            <button>PDF İndir</button>
        </a>
    <?php } else { ?>
        <p>PDF oluşturulamadı veya mevcut değil.</p>
    <?php } ?>

    <!-- Anasayfaya dön butonu -->
    <form action="http://localhost/courseapp/" method="get">
        <input type="submit" value="Anasayfaya Dön">
    </form>
</body>
</html>