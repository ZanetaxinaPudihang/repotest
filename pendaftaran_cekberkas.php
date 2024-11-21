<?php
session_start();
include('db_connect.php'); 

// Pastikan user_id sudah ada di session sebelum lanjut
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; 

    // Query untuk mendapatkan data user
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $nama_lengkap = $user['nama_lengkap'];
        $username = $user['username'];
    } else {
        exit("User not found.");
    }

    // Query untuk mendapatkan status file
    $sql = "SELECT status_file_pernyataan, status_file_ktp, status_file_kk, status_file_aktif_kuliah, 
                   status_file_ktm, status_file_krs, status_file_akreditasi, status_file_spp, status_file_rekening 
            FROM upload_files WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $status_file_pernyataan = $row['status_file_pernyataan'];
    $status_file_ktp = $row['status_file_ktp'];
    $status_file_kk = $row['status_file_kk'];
    $status_file_aktif_kuliah = $row['status_file_aktif_kuliah'];
    $status_file_ktm = $row['status_file_ktm'];
    $status_file_krs = $row['status_file_krs'];
    $status_file_akreditasi = $row['status_file_akreditasi'];
    $status_file_spp = $row['status_file_spp'];
    $status_file_rekening = $row['status_file_rekening'];
    // Ambil catatan dari database
    $sql_notes = "SELECT notes_berkas FROM upload_files WHERE user_id = ?";
    $stmt_notes = $conn->prepare($sql_notes);
    $stmt_notes->bind_param("i", $user_id);
    $stmt_notes->execute();
    $result_notes = $stmt_notes->get_result();

    if ($result_notes->num_rows > 0) {
        $notes_data = $result_notes->fetch_assoc();
        $notes_berkas = $notes_data['notes_berkas'];
    } else {
        $notes_berkas = "Belum ada catatan.";
    }
} else {
    echo "Harap pastikan semua file telah dipilih dan di-upload dengan benar.";
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
        

        nav{
          z-index: 5; 
          background-color: #0077C0; 
          font-size: 14px;
          position: fixed; 
          top: 0; 
          width: 100%; 
          height: 80px; 
        }


      .sidebar {
        margin: 0;
        padding: 0;
        width: 300px;
        background-color: white;
        position: fixed;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 10;

        }
      .sidebar a {
          display: block;
          color: black;
          padding: 16px;
          text-decoration: none;
          padding-left: 80px;
      }
      .sidebar a.active {
          background-color: #0077C0;
          color: white;
      }
      .sidebar a:hover:not(.active) {
          background-color: #e2e2e2 ;
          color: #0077C0;
      }

      .isi{
        margin-left: 300px; 
          margin-top: 80px;
          margin-bottom: 30px;
          padding: 30px;
        }
        .melengkapi_persyaratan{
            border: 1px solid #D9D9D9;
            border-radius:10px;
            background-color: white;
            padding:30px;
            margin-bottom: 30px;
        }

        
        .card-title{
            font-size:16px;
        }
        .card-text{
            font-size:12px;
            color:#ABABAB;
            line-height: 1.25;
        }

        .card{
            margin-top: 5px;
            
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
      
        .diterima {
            color: #28a745;
        }

        .ditolak {
            color: #dc3545;
        }

        .pending {
            color: #ffc107;
        }

    </style>
    <title>SBM Morut</title>
</head>
<body>
      <!-- Nav  Bar --> 
      <nav class="navbar navbar-expand-lg" style="position: fixed;">
        <div class="container-fluid" >
            <div class="collapse navbar-collapse">  
                <ul class="navbar-nav ms-auto"> 
                    <li class="nav-item dropdown" style="padding-right: 80px;">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">
                            <img src="foto_profil.png" alt="Profile" class="profile-pic me-2" style="border-radius: 50px;">
                            <div class="d-flex flex-column">
                            <h5 class="card-title"> <?php echo $nama_lengkap; ?> </h5>
                            <p class="card-text" style="color:white;"><?php echo $username; ?></p>
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


    <!-- side nav bar --> 
    <!-- Sidebar -->
    <div class="sidebar">
      <a class="navbar-brand" href="#" style="display:flex;">
          <img src="Lambang-Morowali-Utara 1.png" alt="Logo" class="d-inline-block align-text-top" width="38" height="55">
          <p style="font-size: 12px; font-weight:bold; padding-top:12px; padding-left:20px;">Kabupaten</br>Morowali Utara</p>
      </a>
      <a href="program_beasiswa.php">Informasi Beasiswa</a>
      <a class="active" href="pendaftaran_jenjang.php">Pendaftaran</a>
      <a href="lacak_pengajuan.php">Lacak Pengajuan</a>
      <a href="profil.php">Profil</a>
  </div>

  <!-- Isi -->
  <div class="isi">
    <div class="melengkapi_persyaratan">
        <p style="font-size:16px; font-weight:bold;">Melengkapi Persyaratan</p>
        <hr>
        <div class="d-flex mt-4">
            <div class="list-container">
                    <ul class="list-unstyled">
                        <a href="pendaftaran_jenjang.php" class="menu_melengkapi ">
                        <li class="d-flex align-items-center ">
                            <img src="icon_jenjang.png" alt="Jenjang" style="width: 30px; height: 30px;">
                            <div style="padding-left:10px;">
                                <strong>Pilih Jenjang Beasiswa</strong><br>
                                <small>Pilih jenjang yang sedang ditempuh</small>
                            </div>
                        </li>
                        </a>
    
                        <a href="pendaftaran_biodata.php" class="menu_melengkapi">
                        <li class="d-flex align-items-center">
                            <img src="icon_updatebiodata.png" alt="updatebiodata" style="width: 30px; height: 30px;" >
                            <div style="padding-left:10px;">
                                <strong>Update Biodata</strong><br>
                                <small>Melengkapi/Update Biodata</small>
                            </div>
                        </li>
                        </a>
    
                        <a href="pendaftaran_upload.php" class="menu_melengkapi">
                        <li class="d-flex align-items-center ">
                            <img src="icon_uploadsyarat.png" alt="uploadpersyaratan" style="width: 30px; height: 30px;">
                            <div style="padding-left:10px;">
                                <strong>Upload Persyaratan</strong><br>
                                <small>Lengkapi Berkas Persyaratan</small>
                            </div>
                        </li>
                        </a>
    
                        <a href="pendaftaran_cekberkas.php" class="menu_melengkapi aktif">
                        <li class="d-flex align-items-center ">
                            <img src="icon_cekberkas.png" alt="cekberkas" style="width: 30px; height: 30px;">
                            <div style="padding-left:10px;">
                                <strong>Cek Kembali Berkas</strong><br>
                                <small>Cek Kelengkapan Berkas</small>
                            </div>
                        </li>
                        </a>
                    </ul>
                </div>
                <div style="width: 1px; background-color: #CBCBCB; margin: 0 10px; height: auto;"></div>

            <!-- Upload Persyaratan -->
             
            <div class="list-container" style="margin-left:px;  flex:1">
                <ul class="list-unstyled">
                    <li class="d-flex align-items-center mb-3">
                        <img src="icon_upload.png" alt="Logo" class="me-3">
                        <div style="color:#0077C0;">
                            <strong>Upload Persyaratan</strong>
                        </div>
                    </li>
                </ul>

            <!-- Surat pernyataan -->
            <div class="card border-secondary mb-3 mt-4" style="background-color:#0077C0; ">
                    <div class="card-header" style= "color:white; font-weight:bold; font-size:12px;">Catatan terkait kelengkapan berkas</div>
                    <div class="card-body bg-light">
                        <p class="card-text" style="color:#0077C0; "><?php echo nl2br(htmlspecialchars($notes_berkas)); ?></p>
                    </div>
                </div>
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="">
                        <p class="card-title">Upload Surat Pernyataan</p>
                        <p class="card-text">Upload Surat dalam bentuk PDF</p>
                    </div>
                    <div class="icon <?php echo ($status_file_pernyataan == 'diterima') ? 'diterima' : (($status_file_pernyataan == 'ditolak') ? 'ditolak' : 'menunggu'); ?>">
                        <i class="fas <?php echo ($status_file_pernyataan == 'diterima') ? 'fa-check-circle' : (($status_file_pernyataan == 'ditolak') ? 'fa-times-circle' : 'fa-hourglass-half'); ?>"></i>
                    </div>
                </div>
            </div>

            <!-- KTP -->
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="">
                        <p class="card-title">Upload foto copy/scan KTP</p>
                        <p class="card-text">Format File dalam bentuk PDF/jpg</p>
                    </div>
                    <div class="icon <?php echo ($status_file_ktp == 'diterima') ? 'diterima' : (($status_file_ktp == 'ditolak') ? 'ditolak' : 'menunggu'); ?>">
                        <i class="fas <?php echo ($status_file_ktp == 'diterima') ? 'fa-check-circle' : (($status_file_ktp == 'ditolak') ? 'fa-times-circle' : 'fa-hourglass-half'); ?>"></i>
                    </div>
                </div>
            </div>

            <!-- KK -->
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="">
                        <p class="card-title">Upload foto copy/scan Kartu Keluarga</p>
                        <p class="card-text">Format File dalam bentuk PDF</p>
                    </div>
                    <div class="icon <?php echo ($status_file_kk == 'diterima') ? 'diterima' : (($status_file_kk == 'ditolak') ? 'ditolak' : 'menunggu'); ?>">
                        <i class="fas <?php echo ($status_file_kk == 'diterima') ? 'fa-check-circle' : (($status_file_kk == 'ditolak') ? 'fa-times-circle' : 'fa-hourglass-half'); ?>"></i>
                    </div>
                </div>
            </div>

            <!-- Surat Aktif -->
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="">
                        <p class="card-title">Upload Surat Keterangan Aktif Kuliah</p>
                        <p class="card-text">Format File dalam bentuk PDF</p>
                    </div>
                    <div class="icon <?php echo ($status_file_aktif_kuliah == 'diterima') ? 'diterima' : (($status_file_aktif_kuliah == 'ditolak') ? 'ditolak' : 'menunggu'); ?>">
                        <i class="fas <?php echo ($status_file_aktif_kuliah == 'diterima') ? 'fa-check-circle' : (($status_file_aktif_kuliah == 'ditolak') ? 'fa-times-circle' : 'fa-hourglass-half'); ?>"></i>
                    </div>
                </div>
            </div>

            <!-- Kartu Mahasiswa -->
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="">
                        <p class="card-title">Upload Kartu Mahasiswa</p>
                        <p class="card-text">Format File dalam bentuk PDF/jpg</p>
                    </div>
                    <div class="icon <?php echo ($status_file_ktm == 'diterima') ? 'diterima' : (($status_file_ktm == 'ditolak') ? 'ditolak' : 'menunggu'); ?>">
                        <i class="fas <?php echo ($status_file_ktm == 'diterima') ? 'fa-check-circle' : (($status_file_ktm == 'ditolak') ? 'fa-times-circle' : 'fa-hourglass-half'); ?>"></i>
                    </div>
                </div>
            </div>

            <!-- KRS -->
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="">
                        <p class="card-title">Upload Kartu Rencana Studi (KRS)</p>
                        <p class="card-text">Format File dalam bentuk PDF</p>
                    </div>
                    <div class="icon <?php echo ($status_file_krs == 'diterima') ? 'diterima' : (($status_file_krs == 'ditolak') ? 'ditolak' : 'menunggu'); ?>">
                        <i class="fas <?php echo ($status_file_krs == 'diterima') ? 'fa-check-circle' : (($status_file_krs == 'ditolak') ? 'fa-times-circle' : 'fa-hourglass-half'); ?>"></i>
                    </div>
                </div>
            </div>

            <!-- Akreditasi -->
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="">
                        <p class="card-title">Upload Sertifikat Akreditasi Universitas/Prodi</p>
                        <p class="card-text">Format File dalam bentuk PDF/jpg</p>
                    </div>
                    <div class="icon <?php echo ($status_file_akreditasi == 'diterima') ? 'diterima' : (($status_file_akreditasi == 'ditolak') ? 'ditolak' : 'menunggu'); ?>">
                        <i class="fas <?php echo ($status_file_akreditasi == 'diterima') ? 'fa-check-circle' : (($status_file_akreditasi == 'ditolak') ? 'fa-times-circle' : 'fa-hourglass-half'); ?>"></i>
                    </div>
                </div>
            </div> 
            
            <!-- Bukti Bayar -->
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="">
                        <p class="card-title">Upload Bukti Pembayaran SPP/UKT/BOP semester berjalan</p>
                        <p class="card-text">Format File dalam bentuk PDF/jpg</p>
                    </div>
                    <div class="icon <?php echo ($status_file_spp == 'diterima') ? 'diterima' : (($status_file_spp == 'ditolak') ? 'ditolak' : 'menunggu'); ?>">
                        <i class="fas <?php echo ($status_file_spp == 'diterima') ? 'fa-check-circle' : (($status_file_spp == 'ditolak') ? 'fa-times-circle' : 'fa-hourglass-half'); ?>"></i>
                    </div>
                </div>
            </div>
            
            <!-- Bukti Bayar -->
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="">
                        <p class="card-title">Upload foto copy Buku Rekening Bank Sulteng (BPD)</p>
                        <p class="card-text"></i>Format File dalam bentuk PDF/jpg</p>
                    </div>
                    <div class="icon <?php echo ($status_file_rekening == 'diterima') ? 'diterima' : (($status_file_rekening == 'ditolak') ? 'ditolak' : 'menunggu'); ?>">
                        <i class="fas <?php echo ($status_file_rekening == 'diterima') ? 'fa-check-circle' : (($status_file_rekening == 'ditolak') ? 'fa-times-circle' : 'fa-hourglass-half'); ?>"></i>
                    </div>
                </div>
            </div>
            

            
                

            </div>
        </div>
    </div>
</div>
</div>

 <!-- Footer --> 
  
            
      <!-- Footer --> 
    <footer class="text-center text-lg-start">
        <div class="container text-center text-md-start mt-5">
          <div class="row mt-3">
            <div class="col-sm-4">
              <a class="navbar-brand" href="#" style="display:flex; padding-left:80px;">
                <img src="Lambang-Morowali-Utara 1.png" alt="Logo" class="d-inline-block align-text-top" width="38" height="55">
                <p style="font-size: 12px; font-weight:bold; padding-top:12px; padding-left:20px;">Kabupaten</br>Morowali Utara</p>
              </a>
              <div style="padding-left:82px; padding-top:10px;">
                <a href="#" class="me-4 text-reset"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="me-4 text-reset"><i class="fab fa-youtube"></i></a>
                <a href="#" class="me-4 text-reset"><i class="fab fa-instagram"></i></a>
              </div>
                <form id="feedback-form"style="padding-left:80px;padding-top:10px; margin-right:10px;">
                    <div class="mb-3">
                        <textarea class="form-control" id="message" rows="4" placeholder="kritik/saran"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                      <button type="submit" class="btnkirim">Kirim</button>
                    </div>
                </form>
            </div>

            <div class="col-sm-4">
              <h6 class="text-uppercase fw-bold mb-4">Link Terkait</h6>
              <p><a href="#!" class="text-reset" style="text-decoration: none;">Website Pemerintah</a></p>
              <p><a href="#!" class="text-reset" style="text-decoration: none;">Data Sekolah Kabupaten Morowali Utara</a></p>
              <p><a href="#!" class="text-reset" style="text-decoration: none;">Beasiswa Lainnya</a></p>
            </div>

            <div class="col-sm-4">
              <h6 class="text-uppercase fw-bold mb-4">Hubungi Kami</h6>
              <p><i class="fa-brands fa-whatsapp me-3"></i>(082) 2094498433993</p>
              <p><i class="fas fa-envelope me-3"></i>morowali@gmail.com</p>
              <p><i class="fas fa-phone me-3"></i>(082) 2094498433993</p>
              <h6 class="text-uppercase fw-bold mb-4">Alamat</h6>
              <p><i class="fa-brands fa-whatsapp me-3"></i>Jl.a[aaaaaahxcxvgsgvxhvxhvanbjbbkkm</p>
            </div>
          </div>
        </div>

      <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
        © 2024 Copyright:
        <a class="text-reset fw-bold" href="#">Morowali Utara</a>
      </div>
    </footer>
    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    
</body>
</html>