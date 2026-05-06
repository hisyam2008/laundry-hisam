<?php
class MemberModel {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function all() {
        return $this->db->query("SELECT * FROM tb_member ORDER BY id ASC")->fetchAll();
    }
    public function find($id) {
        $s = $this->db->prepare("SELECT * FROM tb_member WHERE id = ?");
        $s->execute([$id]); return $s->fetch();
    }
    public function create($nama, $alamat, $jenis_kelamin, $tlp) {
        $this->db->prepare("INSERT INTO tb_member (nama, alamat, jenis_kelamin, tlp) VALUES (?,?,?,?)")->execute([$nama, $alamat, $jenis_kelamin, $tlp]);
    }
    public function update($id, $nama, $alamat, $jenis_kelamin, $tlp) {
        $this->db->prepare("UPDATE tb_member SET nama=?, alamat=?, jenis_kelamin=?, tlp=? WHERE id=?")->execute([$nama, $alamat, $jenis_kelamin, $tlp, $id]);
    }
    public function delete($id) {
        $this->db->prepare("DELETE FROM tb_member WHERE id = ?")->execute([$id]);
    }
    public function count() {
        return $this->db->query("SELECT COUNT(*) FROM tb_member")->fetchColumn();
    }
    public function latest($limit = 5) {
        return $this->db->query("SELECT * FROM tb_member ORDER BY id DESC LIMIT $limit")->fetchAll();
    }
}
