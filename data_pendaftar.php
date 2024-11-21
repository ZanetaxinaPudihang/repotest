<?php
session_start();
include('db_connect.php');


if (isset($_SESSION['admin_id'])) {
   
    $user_id = $_SESSION['admin_id'];
    $sql = "SELECT * FROM admin WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        
        $user = $result->fetch_assoc();
        $nama_admin = $user['nama_admin'];
        $username_admin = $user['username_admin'];

    } else {
        exit();
    }
} else {
    exit();
}

$sql_pengajuan = "SELECT * FROM users";
$stmt_pengajuan = $conn->prepare($sql_pengajuan);
$stmt_pengajuan->execute();
$result_pengajuan = $stmt_pengajuan->get_result();
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
            padding-right:25px;
            min-width:325px;
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
        <p style="font-size:16px; font-weight:bold; text-align:center;">Data pendaftar Beasiswa Morowali Utara</p>
        <hr>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Jenis Kelamin</th>
                    <!--<th>Jenjang</th>
                    <th>Status</th>-->
                    <th>Action</th>
                </tr>  
            </thead>
            <tbody>
                <?php
                // Menampilkan data peserta beasiswa
                if ($result_pengajuan->num_rows > 0) {
                    // Loop untuk menampilkan setiap baris data
                    while ($row = $result_pengajuan->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['jenis_kelamin']) . "</td>";
                        //echo "<td>" . htmlspecialchars($row['Jenjang']) . "</td>";
                        //echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>
                                <a href='action_admin.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Lihat</a>
                                <a href='ubah_status.php?id=" . $row['id'] . "' class='btn btn-success btn-sm'>Edit</a> 
                                <a href='ubah_status.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Tidak ada data peserta beasiswa yang ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</div>

 <!-- Footer --> 
  
            
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