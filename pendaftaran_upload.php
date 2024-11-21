<?php
session_start(); 
include('db_connect.php'); 
$user_id = $_SESSION['user_id']; 

if (isset($_SESSION['user_id'])) {
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
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
        $max_file_size = 10 * 1024 * 1024; 


        $files_to_upload = [
            'file_pernyataan' => 'file_pernyataan',
            'file_ktp' => 'file_ktp',
            'file_kk' => 'file_kk',
            'file_aktif_kuliah' => 'file_aktif_kuliah',
            'file_ktm' => 'file_ktm',
            'file_krs' => 'file_krs',
            'file_akreditasi' => 'file_akreditasi',
            'file_spp' => 'file_spp',
            'file_rekening' => 'file_rekening',
        ];

        
        $uploaded_paths = [];
        $all_files_uploaded = true;

      
        foreach ($files_to_upload as $file_input => $db_column) {
            if (isset($_FILES[$file_input]) && $_FILES[$file_input]['error'] == 0) {
                $file = $_FILES[$file_input];
        
            
                if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_file_size) {
        
                   
                    $file_name = uniqid($file_input . '_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                    $upload_dir = 'uploads/'; 
                    $file_path = $upload_dir . $file_name;
        
                    
                    if (move_uploaded_file($file['tmp_name'], $file_path)) {
                        
                        $uploaded_paths[$db_column] = $file_path; 
                    } else {
                        echo "Terjadi kesalahan saat memindahkan file $file_input.<br>";
                        $all_files_uploaded = false;
                    }
                } else {
                    echo "File $file_input tidak sesuai dengan format yang diperbolehkan atau terlalu besar.<br>";
                    $all_files_uploaded = false;
                }
            } else {
                
                echo "File $file_input belum di-upload.<br>";
                $uploaded_paths[$db_column] = NULL;  
                $all_files_uploaded = false; 
            }
        }


        if ($all_files_uploaded) {
            
            $sql = "INSERT INTO upload_files (file_pernyataan, file_ktp, file_kk, file_aktif_kuliah, 
                    file_ktm, file_krs, file_akreditasi, file_spp, file_rekening, user_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

          
            $stmt = $conn->prepare($sql);
            
            $file_pernyataan = $uploaded_paths['file_pernyataan'] ?? NULL;
            $file_ktp = $uploaded_paths['file_ktp'] ?? NULL;
            $file_kk = $uploaded_paths['file_kk'] ?? NULL;
            $file_aktif_kuliah = $uploaded_paths['file_aktif_kuliah'] ?? NULL;
            $file_ktm = $uploaded_paths['file_ktm'] ?? NULL;
            $file_krs = $uploaded_paths['file_krs'] ?? NULL;
            $file_akreditasi = $uploaded_paths['file_akreditasi'] ?? NULL;
            $file_spp = $uploaded_paths['file_spp'] ?? NULL;
            $file_rekening = $uploaded_paths['file_rekening'] ?? NULL;
            $user_id = $_SESSION['user_id']; 

            $stmt->bind_param(
                "ssssssssss", 
                $file_pernyataan, 
                $file_ktp, 
                $file_kk, 
                $file_aktif_kuliah, 
                $file_ktm, 
                $file_krs, 
                $file_akreditasi, 
                $file_spp, 
                $file_rekening, 
                $user_id
            );
      
            if ($stmt->execute()) {
                echo "Semua file berhasil di-upload dan path disimpan di database!";

            } else {
                echo "Terjadi kesalahan saat menyimpan data ke database.";
            }
            
            $stmt->close();
        } else {
            echo "Harap pastikan semua file telah dipilih dan di-upload dengan benar.";
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


      /*Sidebar*/
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

        .namafile {
            font-size: 12px;
            color: gray;
            margin-top: 5px;
            white-space: nowrap; 
            overflow: hidden; 
            text-overflow: ellipsis;
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
      <a class="active" href="#">Pendaftaran</a>
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
    
                        <a href="pendaftaran_upload.php" class="menu_melengkapi aktif">
                        <li class="d-flex align-items-center ">
                            <img src="icon_uploadsyarat.png" alt="uploadpersyaratan" style="width: 30px; height: 30px;">
                            <div style="padding-left:10px;">
                                <strong>Upload Persyaratan</strong><br>
                                <small>Lengkapi Berkas Persyaratan</small>
                            </div>
                        </li>
                        </a>
    
                        <a href="pendaftaran_cekberkas.php" class="menu_melengkapi">
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

                <form action="pendaftaran_upload.php" method="POST" enctype="multipart/form-data">

            <!-- Surat pernyataan -->
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-start" >
                        <div>
                            <p class="card-title">Upload Surat Pernyataan</p>
                            <p class="card-text">Format File dalam bentuk PDF</p>
                        </div>
                        <div class="upload-container" style="text-align: right;">
                            <input type="file" name="file_pernyataan" id="file_pernyataan" style="display: none;" required onchange="updateFileName('file_pernyataan', 'file_pernyataan_name')">
                            <button class="btn btn-outline-primary" type="button" id="uploadbutton_pernyataan" onclick="document.getElementById('file_pernyataan').click();">Upload</button>
                            <div class= "namafile" id="file_pernyataan_name" style="font-size: 12px; color: gray; margin-top: 5px;"></div>
                        </div>
                    </div>
                </div>

            <!-- KTP -->
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-start" >
                        <div>
                            <p class="card-title">Upload foto copy KTP</p>
                            <p class="card-text">Format File dalam bentuk PDF/JPG</p>
                        </div>
                        <div class="upload-container" style="text-align: right;">
                            <input type="file" name="file_ktp" id="file_ktp" style="display: none;" required onchange="updateFileName('file_ktp', 'file_ktp_name')">
                            <button class="btn btn-outline-primary" type="button" id="uploadbutton_ktp" onclick="document.getElementById('file_ktp').click();">Upload</button>
                            <div class= "namafile" id="file_ktp_name" style="font-size: 12px; color: gray; margin-top: 5px;"></div>
                        </div>
                    </div>
                </div>

            <!-- KK -->
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-start" >
                        <div>
                            <p class="card-title">Upload foto copy/scan Kartu Keluarga</p>
                            <p class="card-text">Format File dalam bentuk PDF</p>
                        </div>
                        <div class="upload-container" style="text-align: right;">
                            <input type="file" name="file_kk" id="file_kk" style="display: none;" required onchange="updateFileName('file_kk', 'file_kk_name')">
                            <button class="btn btn-outline-primary" type="button" id="uploadbutton_kk" onclick="document.getElementById('file_kk').click();">Upload</button>
                            <div class= "namafile" id="file_kk_name" style="font-size: 12px; color: gray; margin-top: 5px;"></div>
                        </div>
                    </div>
                </div>

            <!-- Surat Aktif -->
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-start" >
                        <div>
                            <p class="card-title">Upload Surat Aktif Kuliah</p>
                            <p class="card-text">Format File dalam bentuk PDF</p>
                        </div>
                        <div class="upload-container" style="text-align: right;">
                            <input type="file" name="file_aktif_kuliah" id="file_aktif_kuliah" style="display: none;" required onchange="updateFileName('file_aktif_kuliah', 'file_aktif_kuliah_name')">
                            <button class="btn btn-outline-primary" type="button" id="uploadbutton_surat_aktif" onclick="document.getElementById('file_aktif_kuliah').click();">Upload</button>
                            <div class= "namafile" id="file_aktif_kuliah_name" style="font-size: 12px; color: gray; margin-top: 5px;"></div>
                        </div>
                    </div>
                </div>

            <!-- Kartu Mahasiswa -->
                <!-- KTM -->
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-title">Upload KTM</p>
                            <p class="card-text">Format File dalam bentuk PDF</p>
                        </div>
                        <div class="upload-container" style="text-align: right;">
                            <input type="file" name="file_ktm" id="file_ktm" style="display: none;" required onchange="updateFileName('file_ktm', 'file_ktm_name')">
                            <button class="btn btn-outline-primary" type="button" id="uploadbutton_ktm" onclick="document.getElementById('file_ktm').click();">Upload</button>
                            <div class= "namafile" id="file_ktm_name" style="font-size: 12px; color: gray; margin-top: 5px;"></div>
                        </div>
                    </div>
                </div>

                <!-- KRS -->
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-title">Upload KRS</p>
                            <p class="card-text">Format File dalam bentuk PDF</p>
                        </div>
                        <div class="upload-container" style="text-align: right;">
                            <input type="file" name="file_krs" id="file_krs" style="display: none;" required onchange="updateFileName('file_krs', 'file_krs_name')">
                            <button class="btn btn-outline-primary" type="button" id="uploadbutton_krs" onclick="document.getElementById('file_krs').click();">Upload</button>
                            <div class= "namafile" id="file_krs_name" style="font-size: 12px; color: gray; margin-top: 5px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Akreditasi -->
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-title">Upload Akreditasi</p>
                            <p class="card-text">Format File dalam bentuk PDF</p>
                        </div>
                        <div class="upload-container" style="text-align: right;">
                            <input type="file" name="file_akreditasi" id="file_akreditasi" style="display: none;" required onchange="updateFileName('file_akreditasi', 'file_akreditasi_name')">
                            <button class="btn btn-outline-primary" type="button" id="uploadbutton_akreditasi" onclick="document.getElementById('file_akreditasi').click();">Upload</button>
                            <div class= "namafile" id="file_akreditasi_name" style="font-size: 12px; color: gray; margin-top: 5px;"></div>
                        </div>
                    </div>
                </div>

                <!-- SPP -->
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-title">Upload SPP</p>
                            <p class="card-text">Format File dalam bentuk PDF</p>
                        </div>
                        <div class="upload-container" style="text-align: right;">
                            <input type="file" name="file_spp" id="file_spp" style="display: none;" required onchange="updateFileName('file_spp', 'file_spp_name')">
                            <button class="btn btn-outline-primary" type="button" id="uploadbutton_spp" onclick="document.getElementById('file_spp').click();">Upload</button>
                            <div class= "namafile" id="file_spp_name" style="font-size: 12px; color: gray; margin-top: 5px;"></div>
                        </div>
                    </div>
                </div>

                <!-- rekening -->
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-title">Upload Rekening</p>
                            <p class="card-text">Format File dalam bentuk PDF</p>
                        </div>
                        <div class="upload-container" style="text-align: right;">
                            <input type="file" name="file_rekening" id="file_rekening" style="display: none;" required onchange="updateFileName('file_rekening', 'file_rekening_name')">
                            <button class="btn btn-outline-primary" type="button" id="uploadbutton_rekening" onclick="document.getElementById('file_rekening').click();">Upload</button>
                            <div class= "namafile" id="file_rekening_name" style="font-size: 12px; color: gray; margin-top: 5px;"></div>
                        </div>
                    </div>
                </div>
            
              <div class="text-end mt-4">
                    <input class="btn btn-primary" type="submit" name="submit" value="Simpan">
                </div>

                </form>
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

    <script>
        function displayFileName(inputId, outputId) {
            var input = document.getElementById(inputId);
            var output = document.getElementById(outputId);

            input.addEventListener('change', function() {
                if (input.files.length > 0) {
                    output.textContent = input.files[0].name;
                    output.style.display = 'block';
                } else {
                    output.style.display = 'none';
                }
            });
        }

        displayFileName('file_rekening', 'file_rekening_name');
        displayFileName('file_spp', 'file_spp_name');
        displayFileName('file_akreditasi', 'file_akreditasi_name');
        displayFileName('file_krs', 'file_krs_name');
        displayFileName('file_ktm', 'file_ktm_name');
        displayFileName('file_aktif_kuliah', 'file_aktif_kuliah_name');
        displayFileName('file_kk', 'file_kk_name');
        displayFileName('file_ktp', 'file_ktp_name');
        displayFileName('file_pernyataan', 'file_pernyataan_name');
    </script>

    
</body>
</html>