<?php
session_start();
include('db_connect.php');

// Mengecek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];  

// Mengambil data admin
$sql_admin = "SELECT * FROM admin WHERE id = ?";
$stmt_admin = $conn->prepare($sql_admin);
$stmt_admin->bind_param("i", $admin_id);
$stmt_admin->execute();
$result_admin = $stmt_admin->get_result();

if ($result_admin->num_rows > 0) {
    $admin = $result_admin->fetch_assoc();
    $nama_admin = $admin['nama_admin']; 
    $username_admin = $admin['username_admin']; 
} else {
    echo "Admin tidak ditemukan.";
    exit();
}

// Mengecek apakah ada ID pengguna yang diterima
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];  

    // Mengambil data pengguna
    $sql_user = "SELECT * FROM users WHERE id = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $nama_lengkap = $user['nama_lengkap'];
        $username = $user['username'];
        $nik = $user['nik'];
        $no_hp = $user['no_hp'];
        $status = $user['status'];
        

        // Menangani pembaruan status jika form diposting
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
            $new_status = $_POST['status'];


            $sql_update_status = "UPDATE users SET status = ? WHERE id = ?";
            $stmt_update_status = $conn->prepare($sql_update_status);
            $stmt_update_status->bind_param("si", $new_status, $user_id);

            if ($stmt_update_status->execute()) {
                $sql_insert_riwayat = "INSERT INTO riwayat_status (user_id, status) VALUES (?, ?)";
                $stmt_insert_riwayat = $conn->prepare($sql_insert_riwayat);
                $stmt_insert_riwayat->bind_param("is", $user_id, $new_status);

                if ($stmt_insert_riwayat->execute()) {
                    header("Location: admin_verifikasi.php?id=" . $user_id);
                    exit();
                } else {
                    echo "Error inserting status history: " . $stmt_insert_riwayat->error;
                }
            } else {
                echo "Error updating status: " . $stmt_update_status->error;
            }
        }

        $sql_files = "SELECT * FROM upload_files WHERE user_id = ?";
        $stmt_files = $conn->prepare($sql_files);
        $stmt_files->bind_param("i", $user_id);
        $stmt_files->execute();
        $result_files = $stmt_files->get_result();

        if ($result_files->num_rows > 0) {
            $files = $result_files->fetch_assoc();

            $status_surat_pernyataan = $files['status_file_pernyataan'];  
            $status_ktp = $files['status_file_ktp'];
            $status_kk = $files['status_file_kk'];
            $status_file_aktif_kuliah = $files['status_file_aktif_kuliah'];
            $status_ktm = $files['status_file_ktm'];
            $status_krs = $files['status_file_krs'];
            $status_akreditasi = $files['status_file_akreditasi'];
            $status_spp = $files['status_file_spp'];
            $status_rekening = $files['status_file_rekening'];
        } else {
            echo "Data berkas tidak ditemukan.";
            exit();
        }
    } else {
        echo "Data pengguna tidak ditemukan.";
        exit();
    }
} else {
    echo "ID pengguna tidak ditemukan.";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Link ke fontawesome -->  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Link ke bootstrap -->  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        *{
            font-family: verdana;
          }
        
        body{
          background-color: #ededed;
        }
          
        footer{
          padding-top : 10px;
          color:white;
          background-color: #0077C0;
          z-index: 10;
          position: relative;
        }
        
        .btnkirim{
                background-color: white;
                color: black;
                padding: 0px 20px;
                border: none;
                outline: none;
              }
        
        /*Navbar*/
        nav{
          z-index: 5; 
          background-color: #0077C0; 
          font-size: 14px;
          position: fixed; 
          top: 0; 
          width: 100%; 
          height: 80px; 
        }
        .navbar-brand{
          color: white;
          margin-left:80px;
          }



      .isi{ 
          margin-top: 80px;
          padding: 30px 80px;
          flex:1;
        }
        html, body {
            height: 100%; 
            margin: 0; 
        }

        body {
            display: flex;
            flex-direction: column;
        }

        footer {
            background-color: #0077C0; 
            color: white; 

            text-align: center;
            width: 100%;
        }

        .melengkapi_persyaratan{
            border: 1px solid #D9D9D9;
            border-radius:10px;
            background-color: white;
            padding:30px;
            margin-bottom: 30px;

        }

        
        .list-container {
            justify-content: center;
            align-items: center;
            min-width:360px;
        }
        .card-title{
            font-size:16px;
            font-weight:bold;
        }
        .card-text{
            font-size:12px;
            color:#ABABAB;
            line-height: 1.25;
        }
        .list-item {
            display: flex;
            align-items: center;
        }
        .menu_melengkapi{
            text-decoration:none;
            color:inherit;
            display: block;
            border-radius: 2px;
            padding: 5px 0px 5px 5px;
            margin-bottom: 10px;
            
        }
        .menu_melengkapi:hover {
            background-color: #0077C0;
            color: white;
        }

        .menu_melengkapi:hover:not(.aktif) img {
            filter: brightness(0) saturate(100%) invert(100%);
        }

        .menu_melengkapi.aktif{
            background-color: #e2e2e2;
            color: #0077C0;
        }
        .ui-w-100 {
            width: 100px !important;
            height: auto;
        }

        .card {
            background-clip: padding-box;
            box-shadow: 0 1px 4px rgba(24,28,33,0.012);
        }

        .text-light {
            color: #babbbc !important;
        }

        .card .row-bordered>[class*=" col-"]::after {
            border-color: rgba(24,28,33,0.075);
        }    

        .text-xlarge {
            font-size: 170% !important;
        }

        .profil{
            margin:10px;
            width: 500px;
            
        }
        .nowrap {
            white-space: nowrap;
        }

    
        table {
            width: 100%; 
            border:none;
            
        }

        th, td {
            margin: 20px;
            border: none;
            text-align: left;
            padding:0px;
            font-size:13px;
        }
        .judultabel th{
            font-weight:normal;
            background-color: #e6e3e4;
            padding:10px;

        }

        @media screen and (max-width: 768px) {
            table {
                width: 100%;
                display: block; 
                overflow-x: auto; 
                -webkit-overflow-scrolling: touch; 
            }

            th, td {
                white-space: nowrap;
            }
        }

    </style>
    <title>SBM Morut</title>
</head>
<body>
      <!-- Nav  Bar --> 
      <nav class="navbar navbar-expand-lg" style="position: fixed;">
        <div class="container-fluid" >
            
            <a class="navbar-brand" href="#" style="display:flex; padding: left 80px;">
                <img src="Lambang-Morowali-Utara 1.png" alt="Logo" class="d-inline-block align-text-top" width="38" height="55">
                <p style="font-size: 12px; font-weight:bold; padding-top:12px; padding-left:20px;">Kabupaten</br>Morowali Utara</p>
            </a>
            <div class="collapse navbar-collapse">  
                <ul class="navbar-nav ms-auto"> 
                    <li class="nav-item dropdown" style="padding-right: 80px;">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">
                            <img src="foto_profil.png" alt="Profile" class="profile-pic me-2" style="border-radius: 50px;">
                            <div class="d-flex flex-column">
                              <span class="me-2"><?php echo $nama_admin ?></span>
                              <span style="font-size: 10px;"><?php echo $username_admin ?></span>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="Home.html">Keluar</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



  <!-- Isi -->
  <div class="isi">
    <div class="melengkapi_persyaratan">
        <p style="font-size:16px; font-weight:bold;">Melengkapi Persyaratan</p>
        <hr>
        <div class="d-flex mt-4">
            <div class="list-container">
                <ul class="list-unstyled">
                    
                <a href="action_admin.php?id=<?= $user['id'] ?>" class="menu_melengkapi">
                            <li class="d-flex align-items-center">
                                <img src="icon_updatebiodata.png" alt="updatebiodata" style="width: 30px; height: 30px;" >
                                <div style="padding-left:10px;">
                                    <strong>Biodata Mahasiswa</strong><br>
                                    <small>Cek kelengkapan Biodata Mahasiswa</small>
                                </div>
                            </li>
                        </a>

    
                        <a href="cek_berkas_admin.php?id=<?= $user['id'] ?>" class="menu_melengkapi">
                            <li class="d-flex align-items-center">
                                <img src="icon_updatebiodata.png" alt="updatebiodata" style="width: 30px; height: 30px;" >
                                <div style="padding-left:10px;">
                                    <strong>Berkas Mahasiswa</strong><br>
                                    <small>Cek kelengkapan Berkas Mahasiswa</small>
                                </div>
                            </li>
                        </a>
    
                        <a href="admin_verifikasi.php?id=<?= $user['id'] ?>" class="menu_melengkapi aktif">
                            <li class="d-flex align-items-center">
                                <img src="icon_updatebiodata.png" alt="updatebiodata" style="width: 30px; height: 30px;" >
                                <div style="padding-left:10px;">
                                    <strong>Verifikasi</strong><br>
                                    <small>Verifikasi Tahapan</small>
                                </div>
                            </li>
                        </a>
                    </ul>
            </div>
            <div style="width: 1px; background-color: #CBCBCB; margin: 0 10px; height: auto;"></div>

            <!-- Data Diri -->
            <div class="list-container" style="margin-left:10px;">
            <div class="media align-items-center py-3 mb-3">
              <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="d-block ui-w-100 rounded-circle">
              <div class="media-body ml-4">
                <h5 class="font-weight-bold mb-0"><?= $nama_lengkap ?></h5>
                <p style="color:grey; margin:5px 0px;">Status : <?= $status ?></p>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Ubah Status
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Status</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body mb-4">
                            <form action="" method="POST">
                                <div class="form-group">
                                    <label for="status">Pilih Status</label>
                                    <select name="status" class="form-select" id="status">
                                        <option value="" selected disabled>Pilih Status</option>
                                        <option value="Proses Pendaftaran" <?= ($status == 'Proses Pendaftaran') ? 'selected' : ''; ?>>Proses Pendaftaran</option>
                                        <option value="Dokumen Diterima" <?= ($status == 'Dokumen Diterima') ? 'selected' : ''; ?>>Dokumen Diterima</option>
                                        <option value="Dokumen Belum Lengkap" <?= ($status == 'Dokumen Belum Lengkap') ? 'selected' : ''; ?>>Dokumen Belum Lengkap</option>
                                        <option value="Dalam Seleksi Administrasi" <?= ($status == 'Dalam Seleksi Administrasi') ? 'selected' : ''; ?>>Dalam Seleksi Administrasi</option>
                                        <option value="Lulus Seleksi Administrasi" <?= ($status == 'Lulus Seleksi Administrasi') ? 'selected' : ''; ?>>Lulus Seleksi Administrasi</option>
                                        <option value="Menunggu Pengumuman" <?= ($status == 'Menunggu Pengumuman') ? 'selected' : ''; ?>>Menunggu Pengumuman</option>
                                        <option value="Diterima" <?= ($status == 'Diterima') ? 'selected' : ''; ?>>Diterima</option>
                                        <option value="Ditolak" <?= ($status == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                                    </select>
                                </div>
                                <div class="mt-3 text-end">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                </div>
            </div>

            <div class="card mt-4">
              <div class="card-body">
                <table class="table" style="border-radius:20px;">
                  <tbody>
                    <tr class="profil">
                      <td>Tanggal Pendaftaran</td>
                      <td>01/23/2017</td>
                    </tr>
                    <tr class="profil">
                      <td >NIK</td>
                      <td><?= $nik ?></td>
                    </tr>
                    <tr class="profil">
                      <td>Pilihan Jenjang</td>
                      <td><?= $jenjang ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <hr class="border-light m-0">
              <div class="table-responsive" style="margin:20px;">
                <table class="table card-table m-0">
                    <tbody>
                        <tr class="judultabel">
                        <th>Kelengkapan Berkas</th>
                        <th>Surat Pernyataan</th>
                        <th>KTP</th>
                        <th>Kartu Keluarga</th>
                        <th>Surat Aktif Kuliah</th>
                        <th>Kartu Tanda Mahasiswa</th>
                        <th>Kartu Rencana Studi</th>
                        <th>Akreditasi</th>
                        <th>Bukti Pembayaran SPP</th>
                        <th>Buku Rekening</th>
                        </tr>
                        <tr>
                        <td>Users</td>
                        <!-- Surat Pernyataan -->
                        <td>
                            <?php if ($status_surat_pernyataan == 'diterima'): ?>
                            <span class="fa fa-check text-primary"></span>
                            <?php else: ?>
                            <span class="fa fa-times text-danger"></span>
                            <?php endif; ?>
                        </td>
                        <!-- KTP -->
                        <td>
                            <?php if ($status_ktp == 'diterima'): ?>
                            <span class="fa fa-check text-primary"></span>
                            <?php else: ?>
                            <span class="fa fa-times text-danger"></span>
                            <?php endif; ?>
                        </td>
                        <!-- Kartu Keluarga -->
                        <td>
                            <?php if ($status_kk == 'diterima'): ?>
                            <span class="fa fa-check text-primary"></span>
                            <?php else: ?>
                            <span class="fa fa-times text-danger"></span>
                            <?php endif; ?>
                        </td>
                        <!-- Surat Aktif Kuliah -->
                        <td>
                            <?php if ($status_file_aktif_kuliah == 'diterima'): ?>
                            <span class="fa fa-check text-primary"></span>
                            <?php else: ?>
                            <span class="fa fa-times text-danger"></span>
                            <?php endif; ?>
                        </td>
                        <!-- Kartu Tanda Mahasiswa -->
                        <td>
                            <?php if ($status_ktm == 'diterima'): ?>
                            <span class="fa fa-check text-primary"></span>
                            <?php else: ?>
                            <span class="fa fa-times text-danger"></span>
                            <?php endif; ?>
                        </td>
                        <!-- Kartu Rencana Studi -->
                        <td>
                            <?php if ($status_krs == 'diterima'): ?>
                            <span class="fa fa-check text-primary"></span>
                            <?php else: ?>
                            <span class="fa fa-times text-danger"></span>
                            <?php endif; ?>
                        </td>
                        <!-- Akreditasi -->
                        <td>
                            <?php if ($status_akreditasi == 'diterima'): ?>
                            <span class="fa fa-check text-primary"></span>
                            <?php else: ?>
                            <span class="fa fa-times text-danger"></span>
                            <?php endif; ?>
                        </td>
                        <!-- Bukti Pembayaran SPP -->
                        <td>
                            <?php if ($status_spp == 'diterima'): ?>
                            <span class="fa fa-check text-primary"></span>
                            <?php else: ?>
                            <span class="fa fa-times text-danger"></span>
                            <?php endif; ?>
                        </td>
                        <!-- Buku Rekening -->
                        <td>
                            <?php if ($status_rekening == 'diterima'): ?>
                            <span class="fa fa-check text-primary"></span>
                            <?php else: ?>
                            <span class="fa fa-times text-danger"></span>
                            <?php endif; ?>
                        </td>
                        </tr>
                    </tbody>
                </table>
            </div>
                </div>
            </div>
        </div>
    </div>
</div>


 <!-- Footer --> 
    <footer class="text-center text-lg-start">
      <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
        © 2024 Copyright:
        <a class="text-reset fw-bold" href="#">Morowali Utara</a>
      </div>
    </footer>
    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
