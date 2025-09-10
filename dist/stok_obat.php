<?php
include 'security.php';
include 'koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$user_id = $_SESSION['user_id'] ?? 0;

// Flash message
function set_flash($msg){
    $_SESSION['flash_message'] = $msg;
}

// Set tab aktif
function set_active_tab($tab){
    $_SESSION['active_tab'] = $tab;
}

// Proses Simpan Stok Masuk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi             = $_POST['aksi'] ?? '';
    $id_obat          = isset($_POST['id_obat']) ? (int) $_POST['id_obat'] : 0;
    $id_supplier      = isset($_POST['id_supplier']) ? (int) $_POST['id_supplier'] : 0;
    $jumlah           = isset($_POST['jumlah']) ? (int) $_POST['jumlah'] : 0;
    $harga_beli       = isset($_POST['harga_beli']) ? (float) $_POST['harga_beli'] : 0;
    $tanggal          = $_POST['tanggal'] ?? date('Y-m-d');
    $tanggal_kadaluwarsa = $_POST['tanggal_kadaluwarsa'] ?: null;
    $cara_bayar       = $_POST['cara_bayar'] ?? '';
    $jatuh_tempo      = $_POST['jatuh_tempo'] ?: null;
    $keterangan       = trim($_POST['keterangan'] ?? '');

    if($id_obat <= 0 || $id_supplier <= 0 || $jumlah <= 0 || $harga_beli <= 0){
        set_flash("❌ Semua field wajib diisi dengan benar.");
        set_active_tab('input');
        header("Location: stok_obat.php");
        exit;
    }

    $total = $jumlah * $harga_beli;

    $stmt = $conn->prepare("INSERT INTO stok_masuk 
        (tanggal_masuk, id_obat, id_supplier, jumlah, harga_beli, total, cara_bayar, jatuh_tempo, tanggal_kadaluwarsa, keterangan)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiddssss", $tanggal, $id_obat, $id_supplier, $jumlah, $harga_beli, $total, $cara_bayar, $jatuh_tempo, $tanggal_kadaluwarsa, $keterangan);

    if($stmt->execute()){
        // --- Ambil stok lama dan harga beli lama ---
        $stok_lama_data = $conn->query("SELECT stok, harga_beli FROM obat WHERE id=$id_obat")->fetch_assoc();
        $stok_lama = (int)$stok_lama_data['stok'];
        $harga_lama = (float)$stok_lama_data['harga_beli'];

        // --- Hitung harga beli rata-rata tertimbang ---
        $stok_total = $stok_lama + $jumlah;
        $harga_baru = ($stok_lama * $harga_lama + $jumlah * $harga_beli) / $stok_total;

        // --- Update stok dan harga beli di tabel obat ---
        $stmt2 = $conn->prepare("UPDATE obat SET stok = ?, harga_beli = ? WHERE id = ?");
        $stmt2->bind_param("idi", $stok_total, $harga_baru, $id_obat);
        $stmt2->execute();
        $stmt2->close();

        set_flash("✅ Stok masuk berhasil ditambahkan dan harga beli otomatis diperbarui.");
        set_active_tab('data');
    } else {
        set_flash("❌ Gagal: " . $stmt->error);
        set_active_tab('input');
    }

    $stmt->close();
    header("Location: stok_obat.php");
    exit;
}

// Ambil data obat dan supplier
$data_obat = $conn->query("SELECT * FROM obat ORDER BY nama_obat ASC");
$data_supplier = $conn->query("SELECT * FROM supplier ORDER BY nama ASC");

// Ambil tab aktif
$activeTab = $_SESSION['active_tab'] ?? 'input';
unset($_SESSION['active_tab']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Stok Masuk Obat &mdash; Apotek-KU</title>
<link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css" />
<link rel="stylesheet" href="assets/css/style.css" />
<link rel="stylesheet" href="assets/css/components.css" />
<style>
#notif-toast { position: fixed; top: 20px; right: 20px; z-index: 9999; display: none; min-width: 250px; }
.table-nowrap td, .table-nowrap th { white-space: nowrap; }
.table thead th { background-color: #000 !important; color: #fff !important; }
</style>
</head>
<body>
<div id="app">
  <div class="main-wrapper main-wrapper-1">
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
      <section class="section">
        <div class="section-body">

        <?php if(isset($_SESSION['flash_message'])): ?>
            <div id="notif-toast" class="alert alert-info">
                <?= $_SESSION['flash_message'] ?>
            </div>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
              <h4>Obat Masuk</h4>
            </div>
            <div class="card-body">
              <ul class="nav nav-tabs" id="stokTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link <?= $activeTab=='input'?'active':'' ?>" id="input-tab" data-toggle="tab" href="#input" role="tab">Input Obat Masuk</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?= $activeTab=='data'?'active':'' ?>" id="data-tab" data-toggle="tab" href="#data" role="tab">Data Obat Masuk</a>
                </li>
              </ul>

              <div class="tab-content mt-3">
                <!-- Form Input Stok Masuk -->
                <div class="tab-pane fade <?= $activeTab=='input'?'show active':'' ?>" id="input" role="tabpanel">
                  <form method="POST">
                    <input type="hidden" name="aksi" value="tambah">
                    <div class="form-row">
                      <div class="form-group col-md-3">
                        <label>Tanggal Masuk</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                      </div>
                      <div class="form-group col-md-3">
                        <label>Tanggal Kadaluwarsa</label>
                        <input type="date" name="tanggal_kadaluwarsa" class="form-control">
                      </div>
                      <div class="form-group col-md-4">
                        <label>Obat</label>
                        <select name="id_obat" class="form-control" required>
                          <option value="">-- Pilih Obat --</option>
                          <?php
                          $data_obat->data_seek(0);
                          while($row = $data_obat->fetch_assoc()):
                          ?>
                          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama_obat']) ?> (Stok: <?= $row['stok'] ?>)</option>
                          <?php endwhile; ?>
                        </select>
                      </div>
                      <div class="form-group col-md-2">
                        <label>Supplier</label>
                        <select name="id_supplier" class="form-control" required>
                          <option value="">-- Pilih Supplier --</option>
                          <?php
                          $data_supplier->data_seek(0);
                          while($row = $data_supplier->fetch_assoc()):
                          ?>
                          <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama']) ?></option>
                          <?php endwhile; ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-2">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" min="1" required>
                      </div>
                      <div class="form-group col-md-3">
                        <label>Harga Beli</label>
                        <input type="number" name="harga_beli" class="form-control" min="0" required>
                      </div>
                      <div class="form-group col-md-3">
                        <label>Total Harga</label>
                        <input type="number" name="total_harga" class="form-control" readonly>
                      </div>
                      <div class="form-group col-md-2">
                        <label>Cara Bayar</label>
                        <select name="cara_bayar" class="form-control" required>
                          <option value="">-- Pilih Cara Bayar --</option>
                          <option value="Tunai">Tunai</option>
                          <option value="Tempo">Tempo</option>
                          <option value="Transfer">Transfer</option>
                        </select>
                      </div>
                      <div class="form-group col-md-2">
                        <label>Jatuh Tempo</label>
                        <input type="date" name="jatuh_tempo" class="form-control">
                      </div>
                      <div class="form-group col-md-4">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control">
                      </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                  </form>
                </div>

                <!-- Data Stok Masuk -->
                <div class="tab-pane fade <?= $activeTab=='data'?'show active':'' ?>" id="data" role="tabpanel">
                  <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped table-nowrap">
                      <thead class="thead-dark">
                        <tr>
                          <th>No</th>
                          <th>Tanggal Masuk</th>
                          <th>Tanggal Kadaluwarsa</th>
                          <th>Obat</th>
                          <th>Supplier</th>
                          <th>Jumlah</th>
                          <th>Harga Beli</th>
                          <th>Total</th>
                          <th>Cara Bayar</th>
                          <th>Jatuh Tempo</th>
                          <th>Keterangan</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                      $data_masuk = $conn->query("SELECT sm.*, o.nama_obat, s.nama AS supplier
                                                  FROM stok_masuk sm
                                                  JOIN obat o ON sm.id_obat=o.id
                                                  JOIN supplier s ON sm.id_supplier=s.id
                                                  ORDER BY sm.tanggal_masuk DESC");
                      $no=1;
                      while($row=$data_masuk->fetch_assoc()):
                      ?>
                        <tr>
                          <td><?= $no++; ?></td>
                          <td><?= date('d-m-Y', strtotime($row['tanggal_masuk'])) ?></td>
                          <td><?= $row['tanggal_kadaluwarsa'] ? date('d-m-Y', strtotime($row['tanggal_kadaluwarsa'])) : '-' ?></td>
                          <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                          <td><?= htmlspecialchars($row['supplier']) ?></td>
                          <td><?= $row['jumlah'] ?></td>
                          <td><?= number_format($row['harga_beli'],0,',','.') ?></td>
                          <td><?= number_format($row['total'],0,',','.') ?></td>
                          <td><?= htmlspecialchars($row['cara_bayar']) ?></td>
                          <td><?= $row['jatuh_tempo'] ? date('d-m-Y', strtotime($row['jatuh_tempo'])) : '-' ?></td>
                          <td><?= htmlspecialchars($row['keterangan']) ?></td>
                        </tr>
                      <?php endwhile; ?>
                      </tbody>
                    </table>
                  </div>
                </div>

              </div>
            </div>
          </div>

        </div>
      </section>
    </div>
  </div>
</div>

<script src="assets/modules/jquery.min.js"></script>
<script src="assets/modules/popper.js"></script>
<script src="assets/modules/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
<script src="assets/modules/moment.min.js"></script>
<script src="assets/js/stisla.js"></script>
<script src="assets/js/scripts.js"></script>
<script src="assets/js/custom.js"></script>
<script>
$(document).ready(function(){
    var toast = $('#notif-toast');
    if(toast.length){
        toast.fadeIn(300).delay(2000).fadeOut(500);
    }

    // Update Total Harga otomatis
    $('input[name="jumlah"], input[name="harga_beli"]').on('input', function(){
        var jumlah = parseFloat($('input[name="jumlah"]').val()) || 0;
        var harga  = parseFloat($('input[name="harga_beli"]').val()) || 0;
        $('input[name="total_harga"]').val(jumlah * harga);
    });
});
</script>
</body>
</html>
