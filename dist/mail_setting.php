<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// Tambah setting mail
if (isset($_POST['tambah'])) {
    $host       = mysqli_real_escape_string($conn, $_POST['mail_host']);
    $port       = intval($_POST['mail_port']);
    $username   = mysqli_real_escape_string($conn, $_POST['mail_username']);
    $password   = mysqli_real_escape_string($conn, $_POST['mail_password']);
    $from_email = mysqli_real_escape_string($conn, $_POST['mail_from_email']);
    $from_name  = mysqli_real_escape_string($conn, $_POST['mail_from_name']);
    $base_url   = mysqli_real_escape_string($conn, $_POST['base_url']);

    $sql = "INSERT INTO mail_settings (mail_host, mail_port, mail_username, mail_password, mail_from_email, mail_from_name, base_url)
            VALUES ('$host', $port, '$username', '$password', '$from_email', '$from_name', '$base_url')";
    mysqli_query($conn, $sql) or die("Gagal tambah setting: " . mysqli_error($conn));
    header("Location: mail_setting.php?success=1");
    exit;
}

// Edit setting mail
if (isset($_POST['edit'])) {
    $id         = intval($_POST['id']);
    $host       = mysqli_real_escape_string($conn, $_POST['mail_host']);
    $port       = intval($_POST['mail_port']);
    $username   = mysqli_real_escape_string($conn, $_POST['mail_username']);
    $password   = mysqli_real_escape_string($conn, $_POST['mail_password']);
    $from_email = mysqli_real_escape_string($conn, $_POST['mail_from_email']);
    $from_name  = mysqli_real_escape_string($conn, $_POST['mail_from_name']);
    $base_url   = mysqli_real_escape_string($conn, $_POST['base_url']);

    $sql = "UPDATE mail_settings 
            SET mail_host='$host', mail_port=$port, mail_username='$username', mail_password='$password',
                mail_from_email='$from_email', mail_from_name='$from_name', base_url='$base_url'
            WHERE id=$id";
    mysqli_query($conn, $sql) or die("Gagal edit setting: " . mysqli_error($conn));
    header("Location: mail_setting.php?updated=1");
    exit;
}

// Hapus setting mail
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM mail_settings WHERE id=$id") or die(mysqli_error($conn));
    header("Location: mail_setting.php?deleted=1");
    exit;
}

// Ambil data setting mail
$result = mysqli_query($conn, "SELECT * FROM mail_settings ORDER BY id DESC") or die(mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Mail Setting &mdash; Apotek-KU</title>
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
    <h4>
        Mail Setting
        <i class="fas fa-question-circle text-danger ml-2" style="cursor:pointer;" data-toggle="modal" data-target="#modalHelp"></i>
    </h4>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah">
        <i class="fas fa-plus"></i> Tambah Setting
    </button>
</div>

                       <div class="card-body">
    <div class="table-responsive" style="overflow-x:auto;">
        <table class="table table-bordered table-hover table-sm" style="white-space: nowrap;">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Host</th>
                    <th>Port</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>From Email</th>
                    <th>From Name</th>
                    <th>Base URL</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php $row = mysqli_fetch_assoc($result); // ambil hanya 1 baris ?>
                    <tr>
                        <td>1</td>
                        <td><?= htmlspecialchars($row['mail_host']); ?></td>
                        <td><?= htmlspecialchars($row['mail_port']); ?></td>
                        <td><?= htmlspecialchars($row['mail_username']); ?></td>
                        <td><?= htmlspecialchars($row['mail_password']); ?></td>
                        <td><?= htmlspecialchars($row['mail_from_email']); ?></td>
                        <td><?= htmlspecialchars($row['mail_from_name']); ?></td>
                        <td><?= htmlspecialchars($row['base_url']); ?></td>
                        <td class="d-flex justify-content-between">
                            <button class="btn btn-sm btn-warning btn-edit"
                                data-id="<?= $row['id']; ?>"
                                data-mail_host="<?= htmlspecialchars($row['mail_host']); ?>"
                                data-mail_port="<?= htmlspecialchars($row['mail_port']); ?>"
                                data-mail_username="<?= htmlspecialchars($row['mail_username']); ?>"
                                data-mail_password="<?= htmlspecialchars($row['mail_password']); ?>"
                                data-mail_from_email="<?= htmlspecialchars($row['mail_from_email']); ?>"
                                data-mail_from_name="<?= htmlspecialchars($row['mail_from_name']); ?>"
                                data-base_url="<?= htmlspecialchars($row['base_url']); ?>"
                                data-toggle="modal" data-target="#modalEdit">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <a href="mail_setting.php?hapus=<?= $row['id']; ?>" 
                               onclick="return confirm('Yakin ingin menghapus setting ini?')" 
                               class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data setting</td>
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
                <h5 class="modal-title">Tambah Mail Setting</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Mail Host</label>
                    <input type="text" name="mail_host" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Mail Port</label>
                    <input type="number" name="mail_port" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Mail Username</label>
                    <input type="text" name="mail_username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Mail Password</label>
                    <input type="text" name="mail_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>From Email</label>
                    <input type="email" name="mail_from_email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>From Name</label>
                    <input type="text" name="mail_from_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Base URL</label>
                    <input type="text" name="base_url" class="form-control" required>
                </div>
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
                <h5 class="modal-title">Edit Mail Setting</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Mail Host</label>
                    <input type="text" name="mail_host" id="edit-mail_host" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Mail Port</label>
                    <input type="number" name="mail_port" id="edit-mail_port" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Mail Username</label>
                    <input type="text" name="mail_username" id="edit-mail_username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Mail Password</label>
                    <input type="text" name="mail_password" id="edit-mail_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>From Email</label>
                    <input type="email" name="mail_from_email" id="edit-mail_from_email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>From Name</label>
                    <input type="text" name="mail_from_name" id="edit-mail_from_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Base URL</label>
                    <input type="text" name="base_url" id="edit-base_url" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="edit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Help -->
<div class="modal fade" id="modalHelp" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cara Membuat Password SMTP</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Berikut cara membuat password SMTP untuk email Anda:</p>
                <ol>
                    <li>Login ke akun email Anda (Gmail, Yahoo, dsb).</li>
                    <li>Masuk ke pengaturan akun, pilih bagian keamanan atau security.</li>
                    <li>Aktifkan <strong>2-Step Verification</strong> jika belum.</li>
                    <li>Buat <strong>App Password</strong> atau password khusus aplikasi.</li>
                    <li>Gunakan password ini pada field <strong>Mail Password</strong> di setting ini.</li>
                </ol>
                <p>Catatan: Password SMTP berbeda dengan password utama email Anda.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
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
$(document).on("click", ".btn-edit", function () {
    $("#edit-id").val($(this).data("id"));
    $("#edit-mail_host").val($(this).data("mail_host"));
    $("#edit-mail_port").val($(this).data("mail_port"));
    $("#edit-mail_username").val($(this).data("mail_username"));
    $("#edit-mail_password").val($(this).data("mail_password"));
    $("#edit-mail_from_email").val($(this).data("mail_from_email"));
    $("#edit-mail_from_name").val($(this).data("mail_from_name"));
    $("#edit-base_url").val($(this).data("base_url"));
});
</script>
</body>
</html>
