
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}
include 'koneksi.php';
date_default_timezone_set('Asia/Jakarta');

require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';
require __DIR__ . '/../PHPMailer/src/Exception.php';






use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Fungsi kirim email aktivasi
function kirimEmailAktivasi($email, $nama) {
    global $conn;

    // Ambil setting mail dari database (ambil baris pertama)
    $res = mysqli_query($conn, "SELECT * FROM mail_settings LIMIT 1");
    if(mysqli_num_rows($res) == 0) return false; // jika tidak ada setting, keluar
    $mailSetting = mysqli_fetch_assoc($res);

    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = $mailSetting['mail_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $mailSetting['mail_username'];
        $mail->Password   = $mailSetting['mail_password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $mailSetting['mail_port'];

        //Recipients
        $mail->setFrom($mailSetting['mail_from_email'], $mailSetting['mail_from_name']);
        $mail->addAddress($email, $nama);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Akun Anda Telah Diaktifkan';
        $mail->Body    = "Halo <b>$nama</b>,<br><br>Akun Anda di sistem telah diaktifkan. Anda sekarang bisa login menggunakan email dan password Anda.<br><br>Salam,<br>Admin";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Optional: log error $mail->ErrorInfo
        return false;
    }
}

// Tambah pengguna
if (isset($_POST['tambah'])) {
    $nik      = mysqli_real_escape_string($conn, $_POST['nik']);
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_hp    = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (nik, nama, no_hp, email, password, status, created_at) 
            VALUES ('$nik','$nama','$no_hp','$email','$password','nonaktif',NOW())";
    mysqli_query($conn, $sql) or die("Gagal tambah pengguna: " . mysqli_error($conn));
    header("Location: pengguna.php?success=1");
    exit;
}

// Ubah status aktif/nonaktif
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $q  = mysqli_query($conn, "SELECT status, nama, email FROM users WHERE id=$id");
    $row= mysqli_fetch_assoc($q);
    $newStatus = ($row['status'] == 'aktif') ? 'nonaktif' : 'aktif';
    mysqli_query($conn, "UPDATE users SET status='$newStatus' WHERE id=$id") or die(mysqli_error($conn));

    // Jika diaktifkan, kirim email
    if ($newStatus == 'aktif') {
        kirimEmailAktivasi($row['email'], $row['nama']);
    }

    header("Location: pengguna.php?status_changed=1");
    exit;
}

// Edit pengguna
if (isset($_POST['edit'])) {
    $id      = intval($_POST['id']);
    $nik     = mysqli_real_escape_string($conn, $_POST['nik']);
    $nama    = mysqli_real_escape_string($conn, $_POST['nama']);
    $no_hp   = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql = "UPDATE users 
                   SET nik='$nik', nama='$nama', no_hp='$no_hp', email='$email', password='$password' 
                 WHERE id=$id";
    } else {
        $sql = "UPDATE users 
                   SET nik='$nik', nama='$nama', no_hp='$no_hp', email='$email' 
                 WHERE id=$id";
    }

    mysqli_query($conn, $sql) or die("Gagal edit pengguna: " . mysqli_error($conn));
    header("Location: pengguna.php?updated=1");
    exit;
}

// Ambil data pengguna
$query = "SELECT id, nik, nama, no_hp, email, status, created_at 
          FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $query) or die("Query Error: " . mysqli_error($conn));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
  <title>Data Pengguna &mdash; Apotek-KU</title>
  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/components.css" />
</head>
<body>
<div id="app">
  <div class="main-wrapper main-wrapper-1">
    <?php include 'navbar.php'; ?>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
      <section class="section">
        <div class="section-body">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h4>Daftar Pengguna</h4>
              <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Pengguna
              </button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover">
                  <thead class="thead-dark">
                    <tr>
                      <th>#</th>
                      <th>NIK</th>
                      <th>Nama</th>
                      <th>No HP</th>
                      <th>Email</th>
                      <th>Status</th>
                      <th>Dibuat</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                      <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                          <td><?= $no++; ?></td>
                          <td><?= htmlspecialchars($row['nik']); ?></td>
                          <td><?= htmlspecialchars($row['nama']); ?></td>
                          <td><?= htmlspecialchars($row['no_hp']); ?></td>
                          <td><?= htmlspecialchars($row['email']); ?></td>
                          <td>
                            <?php if ($row['status'] == 'aktif'): ?>
                              <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                              <span class="badge badge-secondary">Nonaktif</span>
                            <?php endif; ?>
                          </td>
                          <td><?= date("d-m-Y H:i", strtotime($row['created_at'])); ?></td>
                          <td class="d-flex flex-wrap">
                            <a href="pengguna.php?toggle=<?= $row['id']; ?>" 
                               class="btn btn-sm <?= ($row['status']=='aktif')?'btn-secondary':'btn-success'; ?> m-1">
                               <?= ($row['status']=='aktif')
                                   ? '<i class="fas fa-ban"></i> Nonaktifkan'
                                   : '<i class="fas fa-check"></i> Aktifkan'; ?>
                            </a>
                            <button 
                              class="btn btn-sm btn-warning btn-edit m-1"
                              data-id="<?= $row['id']; ?>"
                              data-nik="<?= htmlspecialchars($row['nik']); ?>"
                              data-nama="<?= htmlspecialchars($row['nama']); ?>"
                              data-nohp="<?= htmlspecialchars($row['no_hp']); ?>"
                              data-email="<?= htmlspecialchars($row['email']); ?>"
                              data-toggle="modal" 
                              data-target="#modalEdit">
                              <i class="fas fa-edit"></i>
                            </button>
                            <a href="hapus_pengguna.php?id=<?= $row['id']; ?>" 
                               onclick="return confirm('Yakin ingin menghapus pengguna ini?')" 
                               class="btn btn-sm btn-danger m-1">
                              <i class="fas fa-trash"></i>
                            </a>
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="8" class="text-center">Tidak ada data pengguna</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Pengguna</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group"><label>NIK</label><input type="text" name="nik" class="form-control" required></div>
        <div class="form-group"><label>Nama</label><input type="text" name="nama" class="form-control" required></div>
        <div class="form-group"><label>No HP</label><input type="text" name="no_hp" class="form-control" required></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" required></div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="POST" class="modal-content">
      <input type="hidden" name="id" id="edit-id">
      <div class="modal-header">
        <h5 class="modal-title">Edit Pengguna</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group"><label>NIK</label><input type="text" name="nik" id="edit-nik" class="form-control" required></div>
        <div class="form-group"><label>Nama</label><input type="text" name="nama" id="edit-nama" class="form-control" required></div>
        <div class="form-group"><label>No HP</label><input type="text" name="no_hp" id="edit-nohp" class="form-control" required></div>
        <div class="form-group"><label>Email</label><input type="email" name="email" id="edit-email" class="form-control" required></div>
        <div class="form-group"><label>Password (isi jika ingin ganti)</label><input type="password" name="password" class="form-control"></div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="edit" class="btn btn-primary">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
      </div>
    </form>
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
  // Kirim data ke modal edit
  $(document).on("click", ".btn-edit", function () {
    $("#edit-id").val($(this).data("id"));
    $("#edit-nik").val($(this).data("nik"));
    $("#edit-nama").val($(this).data("nama"));
    $("#edit-nohp").val($(this).data("nohp"));
    $("#edit-email").val($(this).data("email"));
  });
</script>
</body>
</html>
