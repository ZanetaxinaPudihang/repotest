<?php
session_start();
include('db_connect.php');


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        $sql_status = "SELECT * FROM riwayat_status WHERE user_id = ? ORDER BY tanggal_perubahan DESC";
        $stmt_status = $conn->prepare($sql_status);
        $stmt_status->bind_param("i", $user_id);
        $stmt_status->execute();
        $status_result = $stmt_status->get_result();
        
        $status_history = [];
        while ($status_data = $status_result->fetch_assoc()) {
            $status_history[] = $status_data;
        }

        if (count($status_history) > 0) {
            $last_status = $status_history[0]; 
            $_SESSION['status'] = $last_status['status'];
            $_SESSION['tanggal_perubahan'] = date('d-m-Y', strtotime($last_status['tanggal_perubahan']));
            $_SESSION['jam_perubahan'] = date('H:i:s', strtotime($last_status['jam_perubahan']));
        } else {
            
            $_SESSION['status'] = "Tidak ada status";
            $_SESSION['tanggal_perubahan'] = "Tanggal tidak tersedia";
            $_SESSION['jam_perubahan'] = "Jam tidak tersedia";
        }
    }
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
        * {
            font-family: verdana;
        }

        body {
            background-color: #ededed;
        }

        footer {
            padding-top: 10px;
            color: white;
            background-color: #0077C0;
            z-index: 10;
            position: relative;
        }

        .btnkirim {
            background-color: white;
            color: black;
            padding: 0px 20px;
            border: none;
            outline: none;
        }

        /* Navbar */
        nav {
            z-index: 5;
            background-color: #0077C0;
            font-size: 14px;
            position: fixed;
            top: 0;
            width: 100%;
            height: 80px;
        }

        /* Sidebar */
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
            background-color: #e2e2e2;
            color: #0077C0;
        }

        .isi {
            margin-left: 300px;
            margin-top: 80px;
            margin-bottom: 30px;
            padding: 30px;
        }

        .melengkapi_persyaratan {
            border: 1px solid #D9D9D9;
            border-radius: 10px;
            background-color: white;
            padding: 30px;
            margin-bottom: 30px;
        }

        .card-title {
            font-size: 16px;
        }

        .card-text {
            font-size: 12px;
            color: #ABABAB;
            line-height: 1.25;
        }

        .card {
            margin-top: 5px;
        }

        .tanggal_lacak {
            font-size: 14px;
        }

        .jam_lacak {
            font-size: 12px;
            color: gray;
        }

        .teks_biru {
            font-size: 14px;
            color: #0077C0;
        }

        .teks_hitam {
            font-size: 12px;
        }
    </style>
    <title>Lacak Pengajuan</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" style="position: fixed;">
        <div class="container-fluid">
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown" style="padding-right: 80px;">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">
                            <img src="foto_profil.png" alt="Profile" class="profile-pic me-2" style="border-radius: 50px;">
                            <div class="d-flex flex-column">
                                <span class="me-2">Inggrid Langgida</span>
                                <span style="font-size: 10px;">User</span>
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

    <!-- Sidebar -->
    <div class="sidebar">
        <a class="navbar-brand" href="#" style="display:flex;">
            <img src="Lambang-Morowali-Utara 1.png" alt="Logo" class="d-inline-block align-text-top" width="38" height="55">
            <p style="font-size: 12px; font-weight:bold; padding-top:12px; padding-left:20px;">Kabupaten</br>Morowali Utara</p>
        </a>
        <a href="program_beasiswa.php">Informasi Beasiswa</a>
        <a href="pendaftaran_jenjang.php">Pendaftaran</a>
        <a class="active" href="lacak_pengajuan.php">Lacak Pengajuan</a>
        <a href="profil.php">Profil</a>
    </div>

    <!-- Isi -->
    <div class="isi">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body" style="text-align: center;">
                            <img src="lacak_pengajuan.png" alt="Profile" class="profile-pic me-2" style=" width:57px; height:57px;">
                            <h5 class="card-title">Lacak Pengajuan</h5>
                            <p class="card-text">Tracking secara berkala pendaftaran Anda</p>
                        </div>
                    </div>
                </div>

                <!-- Isi lacak -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            if (isset($status_history) && count($status_history) > 0):
                                foreach ($status_history as $status_data):
                                    ?>
                                    <!-- Looping riwayat status -->
                                    <div class="row">
                                        <div class="col-md-4 text-end">
                                            <label for="tgl" class="tanggal_lacak" style="display: block;">
                                                <?php echo date('d-m-Y', strtotime($status_data['tanggal_perubahan'])); ?>
                                            </label>
                                            <label for="jam" class="jam_lacak">
                                                <?php echo date('H:i:s', strtotime($status_data['jam_perubahan'])); ?>
                                            </label>
                                        </div>
                                        <div class="col-md-1 text-center" style="margin-top:5px">
                                            <img src="garis_lacak.png" alt="garislacak">
                                        </div>
                                        <div class="col-md-7">
                                            <label for="akun" class="teks_biru" style="display: block;">
                                                <?php echo $status_data['status']; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php
                                endforeach;
                            else:
                                echo "Tidak ada riwayat status.";
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>