<?php
class Randevu {
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

    public function randevuEkle($randevuTarih, $randevuSaati, $doktorid, $randevuDurum, $hastaid) {
        $sql = "INSERT INTO tbl_randevu (randevuTarih, randevuSaati, doktorid, randevuDurum, hastaid) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssisi', $randevuTarih, $randevuSaati, $doktorid, $randevuDurum, $hastaid);
        return $stmt->execute();
    }

    public function randevuGuncelle($randevuid, $randevuTarih, $randevuSaati, $doktorid, $randevuDurum, $hastaid) {
        $sql = "UPDATE tbl_randevu SET randevuTarih = ?, randevuSaati = ?, doktorid = ?, randevuDurum = ?, hastaid = ? WHERE randevuid = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssisii', $randevuTarih, $randevuSaati, $doktorid, $randevuDurum, $hastaid, $randevuid);
        return $stmt->execute();
    }

    public function randevuSil($randevuid) {
        $sql = "DELETE FROM tbl_randevu WHERE randevuid = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $randevuid);
        return $stmt->execute();
    }

    public function randevulariGetir($hastaid) {
        $sql = "SELECT * FROM tbl_randevu WHERE hastaid = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $hastaid);
        $stmt->execute();
        $result = $stmt->get_result();
        $randevular = [];
        while ($row = $result->fetch_assoc()) {
            $randevular[] = $row;
        }
        return $randevular;
    }

    public function randevuGetir($randevuid) {
        $sql = "SELECT * FROM tbl_randevu WHERE randevuid = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $randevuid);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>