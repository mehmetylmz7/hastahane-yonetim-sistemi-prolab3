<?php
class Hasta {
    private $db;

    public function __construct($host, $username, $password, $database) {
        $this->db = new mysqli($host, $username, $password, $database);

        if ($this->db->connect_error) {
            die("Veritabanı bağlantı hatası: " . $this->db->connect_error);
        }
    }

    public function __destruct() {
        $this->db->close();
    }

    public function hastaEkle($hastaAd, $hastaSoyad, $hastaTC, $hastaTelefon, $hastaSifre, $hastaCinsiyet, $hastaAdres, $hastaDogumTarihi) {
        $sql = "INSERT INTO tbl_hasta (hastaAd, hastaSoyad, hastaTC, hastaTelefon, hastaSifre, hastaCinsiyet, hastaAdres, hastaDogumTarihi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssssssss', $hastaAd, $hastaSoyad, $hastaTC, $hastaTelefon, $hastaSifre, $hastaCinsiyet, $hastaAdres, $hastaDogumTarihi);
        return $stmt->execute();
    }

    public function hastaGuncelle($hastaid, $hastaAd, $hastaSoyad, $hastaTC, $hastaTelefon, $hastaSifre, $hastaCinsiyet, $hastaAdres, $hastaDogumTarihi) {
        $sql = "UPDATE tbl_hasta SET hastaAd = ?, hastaSoyad = ?, hastaTC = ?, hastaTelefon = ?, hastaSifre = ?, hastaCinsiyet = ?, hastaAdres = ?, hastaDogumTarihi = ? WHERE hastaid = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssssssssi', $hastaAd, $hastaSoyad, $hastaTC, $hastaTelefon, $hastaSifre, $hastaCinsiyet, $hastaAdres, $hastaDogumTarihi, $hastaid);
        return $stmt->execute();
    }

    public function hastaSil($hastaid) {
        $sql = "DELETE FROM tbl_hasta WHERE hastaid = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $hastaid);
        return $stmt->execute();
    }

    public function hastalariGetir() {
        $sql = "SELECT * FROM tbl_hasta";
        $result = $this->db->query($sql);
        $hastalar = [];
        while ($row = $result->fetch_assoc()) {
            $hastalar[] = $row;
        }
        return $hastalar;
    }

    public function hastaGetir($hastaid) {
        $sql = "SELECT * FROM tbl_hasta WHERE hastaid = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $hastaid);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>