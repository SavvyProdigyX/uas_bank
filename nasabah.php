<?php
include 'koneksi.php';


if (isset($_GET['hal']) && $_GET['hal'] == 'hapus') {
    // Pastikan parameter no_registrasi ada dan tidak kosong
    if (isset($_GET['no_nas']) && !empty($_GET['no_nas'])) {
        $no_nasabah_to_delete = $_GET['no_nas'];

        // Ambil id_buka_rekening dan no_registrasi dari data yang akan dihapus
        $query_get_data = mysqli_query($koneksi, "SELECT id_nasabah, no_nasabah FROM nasabah WHERE no_nasabah = '$no_nasabah_to_delete'");
        $data_to_delete = mysqli_fetch_assoc($query_get_data);

        // Lakukan query penghapusan
        $hapus = mysqli_query($koneksi, "DELETE FROM nasabah WHERE no_nasabah = '$no_nasabah_to_delete'");

        if ($hapus) {
            // Perbarui nilai id_buka_rekening dan no_registrasi untuk data dengan id_buka_rekening lebih besar
            mysqli_query($koneksi, "UPDATE nasabah SET 
                                    id_nasabah = id_nasabah - 1,
                                    no_nasabah = no_nasabah - 1 
                                    WHERE id_nasabah > '{$data_to_delete['id_nasabah']}'");

            echo "<script>
                alert('Data berhasil dihapus');
                document.location = 'nasabah.php';
            </script>";
        } else {
            echo "<script>
                alert('Gagal menghapus data');
                document.location = 'nasabah.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Parameter no_nasabah tidak valid');
            document.location = 'nasabah.php';
        </script>";
    }

    // Hentikan eksekusi skrip untuk menghindari pemrosesan lebih lanjut
    exit();
}
// proses hapus data
if (isset($_GET['hal']) && $_GET['hal'] == 'hapus') {
    // Pastikan parameter no_registrasi ada dan tidak kosong
    if (isset($_GET['no_nas']) && !empty($_GET['no_nas'])) {
        $no_registrasi_to_delete = $_GET['no_nas'];

        // Lakukan query penghapusan
        $hapus = mysqli_query($koneksi, "DELETE FROM nasabah WHERE no_nasabah = '$no_registrasi_to_delete'");

        if ($hapus) {
            echo "<script>
                alert('Data berhasil dihapus');
                document.location = 'nasabah.php';
            </script>";
        } else {
            echo "<script>
                alert('Gagal menghapus data');
                document.location = 'nasabah.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Parameter no_nasabah tidak valid');
            document.location = 'nasabah.php';
        </script>";
    }

    // Hentikan eksekusi skrip untuk menghindari pemrosesan lebih lanjut
    exit();
}



//deklarasi variabel untuk menampung data yang akan di edit
$vIdbukarekening = "";
$vIdnasabah = "";
$vNama = "";
$vTglLahir = "";
$vAlamat = "";
$vEmail = "";
$vNoTelp = "";
$vIncome = "";
$vStatus = "";
$vJeniskelamin = "";
$vPekerjaan = "";


// pengujian jika tombol edit di klik
if (isset($_GET['hal'])) {
    // pengujian jika edit data
    if ($_GET['hal'] == "edit") {
        // tampilkan data yang akan di edit
        $tampil = mysqli_query($koneksi, "SELECT * FROM nasabah WHERE no_nasabah = '$_GET[no_nas]'");
        $data = mysqli_fetch_array($tampil);

        if ($data) {
            // data ditemukan
            $vIdnasabah = $data['id_nasabah'];
            $vNama = $data['nama'];
            $vAlamat = $data['alamat'];
            $vNoTelp = $data['no_telepon'];
            $vEmail = $data['email'];
            $vTglLahir = $data['tanggal_lahir'];
            $vPekerjaan = $data['pekerjaan'];
            $vStatus = $data['status'];
            $vIncome = $data['income'];
            $vIdbukarekening = $data['id_buka_rekening'];
            $vJeniskelamin = $data['gender_id_gender'];



        }



    }
}

if (isset($_POST['btnSimpan'])) {
    $id_nasabah = $_POST['id_nasabah'];

    // Periksa apakah id_buka_rekening sudah ada
    $check_duplicate = mysqli_query($koneksi, "SELECT COUNT(*) FROM nasabah WHERE id_nasabah = '$id_nasabah'");
    $is_duplicate = mysqli_fetch_assoc($check_duplicate)['COUNT(*)'];

    if ($is_duplicate > 0) {
        // Data sudah ada, tampilkan pesan alert
        echo "<script>
            alert('Data dengan ID Nasabah sudah ada. Silakan gunakan ID yang berbeda.');
            document.location = 'nasabah.php';
        </script>";
    } else {
        // Data belum ada, lakukan penyimpanan
        $simpan = mysqli_query($koneksi, "INSERT INTO nasabah (
            id_buka_rekening,
            no_identitas,
            nama_calon,
            tanggal_lahir,
            alamat_calon,
            nama_ibu_kandung,
            no_telepon_calon,
            income_calon,
            setoran_awal,
            tanggal_pembukaan,
            cs_id_cs,
            type_rekening_id_type
        ) VALUES (
            '{$_POST['id_buka_rekening']}',
            '{$_POST['no_identitas']}',
            '{$_POST['nama_calon']}',
            '{$_POST['tgl_lahir']}',
            '{$_POST['alamat']}',
            '{$_POST['ibu_kandung']}',
            '{$_POST['no_telp']}',
            '{$_POST['income']}',
            '{$_POST['setoran_awal']}',
            '{$_POST['tgl_pembukaan']}',
            '{$_POST['id_cs']}',
            '{$_POST['tipe_rekening']}'
        )");

        if ($simpan) {
            echo "<script>
                alert('Data Berhasil disimpan');
                document.location = 'nasabah.php';
            </script>";
        } else {
            echo "<script>
                alert('Data Gagal Disimpan');
                document.location = 'nasabah.php';
            </script>";
        }
    }
}

//  Mendapatkan last ID Nasabah
$queryLastId = mysqli_query($koneksi, "SELECT MAX(id_nasabah) AS max_id FROM nasabah");
$resultLastId = mysqli_fetch_assoc($queryLastId);
$lastId = $resultLastId['max_id'];

// Membuat ID Nasabah baru
$newId = $lastId + 1;



?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nasabah</title>

    <!-- Link CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.5.0/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</head>
<style>
    .sticky-header {
        position: sticky;
        top: 0;
        background-color: #2D3250;
        /* Ganti dengan warna latar belakang yang Anda inginkan */
        z-index: 100;
        /* Atur indeks z agar tetap di atas konten lainnya */
    }
</style>

<body>
    <div class="sticky-header">
        <header class="bg-white dark:bg-gray-900">
            <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="md:flex md:items-center md:gap-12">
                        <a class="block text-teal-600 dark:text-teal-600">
                            <span class="sr-only">Beranda</span>
                            <svg class="h-8" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                href="dashboard.php">
                                <path
                                    d="M0.41 10.3847C1.14777 7.4194 2.85643 4.7861 5.2639 2.90424C7.6714 1.02234 10.6393 0 13.695 0C16.7507 0 19.7186 1.02234 22.1261 2.90424C24.5336 4.7861 26.2422 7.4194 26.98 10.3847H25.78C23.7557 10.3549 21.7729 10.9599 20.11 12.1147C20.014 12.1842 19.9138 12.2477 19.81 12.3047H19.67C19.5662 12.2477 19.466 12.1842 19.37 12.1147C17.6924 10.9866 15.7166 10.3841 13.695 10.3841C11.6734 10.3841 9.6976 10.9866 8.02 12.1147C7.924 12.1842 7.8238 12.2477 7.72 12.3047H7.58C7.4762 12.2477 7.376 12.1842 7.28 12.1147C5.6171 10.9599 3.6343 10.3549 1.61 10.3847H0.41ZM23.62 16.6547C24.236 16.175 24.9995 15.924 25.78 15.9447H27.39V12.7347H25.78C24.4052 12.7181 23.0619 13.146 21.95 13.9547C21.3243 14.416 20.5674 14.6649 19.79 14.6649C19.0126 14.6649 18.2557 14.416 17.63 13.9547C16.4899 13.1611 15.1341 12.7356 13.745 12.7356C12.3559 12.7356 11.0001 13.1611 9.86 13.9547C9.2343 14.416 8.4774 14.6649 7.7 14.6649C6.9226 14.6649 6.1657 14.416 5.54 13.9547C4.4144 13.1356 3.0518 12.7072 1.66 12.7347H0V15.9447H1.61C2.39051 15.924 3.154 16.175 3.77 16.6547C4.908 17.4489 6.2623 17.8747 7.65 17.8747C9.0377 17.8747 10.392 17.4489 11.53 16.6547C12.1468 16.1765 12.9097 15.9257 13.69 15.9447C14.4708 15.9223 15.2348 16.1735 15.85 16.6547C16.9901 17.4484 18.3459 17.8738 19.735 17.8738C21.1241 17.8738 22.4799 17.4484 23.62 16.6547ZM23.62 22.3947C24.236 21.915 24.9995 21.664 25.78 21.6847H27.39V18.4747H25.78C24.4052 18.4581 23.0619 18.886 21.95 19.6947C21.3243 20.156 20.5674 20.4049 19.79 20.4049C19.0126 20.4049 18.2557 20.156 17.63 19.6947C16.4899 18.9011 15.1341 18.4757 13.745 18.4757C12.3559 18.4757 11.0001 18.9011 9.86 19.6947C9.2343 20.156 8.4774 20.4049 7.7 20.4049C6.9226 20.4049 6.1657 20.156 5.54 19.6947C4.4144 18.8757 3.0518 18.4472 1.66 18.4747H0V21.6847H1.61C2.39051 21.664 3.154 21.915 3.77 22.3947C4.908 23.1889 6.2623 23.6147 7.65 23.6147C9.0377 23.6147 10.392 23.1889 11.53 22.3947C12.1468 21.9165 12.9097 21.6657 13.69 21.6847C14.4708 21.6623 15.2348 21.9135 15.85 22.3947C16.9901 23.1884 18.3459 23.6138 19.735 23.6138C21.1241 23.6138 22.4799 23.1884 23.62 22.3947Z"
                                    fill="currentColor" />
                            </svg>
                        </a>
                    </div>

                    <div class="hidden md:block">
                        <nav aria-label="Global">
                            <ul class="flex items-center gap-6 text-sm">
                                <li>
                                    <a class="text-gray-500 transition hover:text-gray-500/75 dark:text-white dark:hover:text-white/75"
                                        href="dashboard.php">
                                        Beranda
                                    </a>
                                </li>

                                <li>
                                    <a class="text-gray-500 transition hover:text-gray-500/75 dark:text-white dark:hover:text-white/75"
                                        href="buka_rekening.php">
                                        Buka Rekening
                                    </a>
                                </li>

                                <li>
                                    <a class="text-gray-500 transition hover:text-gray-500/75 dark:text-white dark:hover:text-white/75"
                                        href="nasabah.php">
                                        Nasabah
                                    </a>
                                </li>

                                <li>
                                    <a class="text-gray-500 transition hover:text-gray-500/75 dark:text-white dark:hover:text-white/75"
                                        href="pelayanan.php">
                                        Pelayanan
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div class="block md:hidden">
                        <button
                            class="rounded bg-gray-100 p-2 text-gray-600 transition hover:text-gray-600/75 dark:bg-gray-800 dark:text-white dark:hover:text-white/75">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
    </div>
    </header>
    </div>

    <section class="bg-gray-100 w-full h-3/4 max-w-screen-xxl mx-auto mt-0">
        <div class="container-xxl mx-auto ">
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-2 mb-2 mt-2">
                <div class="mt-10">
                    <div class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row
                md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 ">
                        <div class="flex flex-col justify-between p-4 leading-normal">

                            <!-- awal form -->
                            <form method="POST" action="nasabah.php">
                                <div class="grid grid-cols-2 gap-4 sm:grid-cols-2 mb-2 ">
                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Id Nasabah
                                        </label>
                                        <input type="text" id="newIdInput"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukkan Id Nasabah" name="id_nasabah"
                                            value="<?= $vIdnasabah ?>" value="<?= $new_id ?>" readonly id="newIdInput">
                                    </div>

                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Nama
                                        </label>
                                        <input type="text" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukkan Nama" name="nama" value="<?= $vNama ?>">
                                    </div>

                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-2">
                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Alamat
                                        </label>
                                        <input type="text" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukkan Alamat" name="alamat" value="<?= $vAlamat ?>">
                                    </div>

                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            No.Telp
                                        </label>
                                        <input type="text" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukkan No. Telp" name="no_telp" value="<?= $vNoTelp ?>">
                                    </div>

                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Email
                                        </label>
                                        <input type="email" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukan Email" name="email" value="<?= $vEmail ?>">
                                    </div>

                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-2">

                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Tanggal Lahir
                                        </label>
                                        <input type="date" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukan Tanggal Lahir" name="tgl_lahir"
                                            value="<?= $vTglLahir ?>">
                                    </div>

                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Pekerjaan
                                        </label>
                                        <input type="text" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukkan Pekerjaan" name="pekerjaan"
                                            value="<?= $vPekerjaan ?>">
                                    </div>


                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Status
                                        </label>
                                        <input type="text" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukan Status" name="status" value="<?= $vStatus ?>">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-2">
                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Income Nasabah
                                        </label>
                                        <input type="text" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukkan Setoran Awal" name="income" value="<?= $vIncome ?>">
                                    </div>

                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            ID Buka Rekening
                                        </label>
                                        <input type="text" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukkan ID Buka Rekening" name="id_buka_rekening"
                                            value="<?= $vIdbukarekening ?>">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-2">

                                    <div>
                                        <label for="default"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">Jenis
                                            Kelamin</label>
                                        <select id="default"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            name="jenis_kelamin">
                                            <?php
                                            // Tampilkan opsi untuk setiap ID Cs
                                            $_jenis_kelamin_options = [
                                                "0" => "Perempuan",
                                                "1" => "Laki-Laki"

                                            ];

                                            foreach ($_jenis_kelamin_options as $value => $label) {
                                                // Tentukan apakah opsi saat ini harus dipilih
                                                $selected = ($vJeniskelamin == $value) ? 'selected' : '';

                                                // Tampilkan elemen <option> dengan label dan nilai yang sesuai
                                                echo "<option value='$value' $selected>$label</option>";
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-2">
                                    <button type="submit"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                                        name="btnSimpan">Simpan</button>

                                    <button type="submit"
                                        class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900"
                                        name="btnUpdate">Update</button>

                                    <button type="button"
                                        class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
                                        name="btnDataBaru" id="btnDataBaru" onclick="showNewId()">Data
                                        Baru</button>
                                </div>
                        </div>
                        </form>
                        <!-- akhir form -->
                    </div>
                </div>
                <div class="mt-10">
                    <h1
                        class="mb-4 text-lg font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
                        Selamat Bekerja !</h1>
                    <p class="mb-6 text-lg font-normal text-gray-500 lg:text-xl sm:px-3 xl:px-3 dark:text-gray-400">
                        Temukan Jalanmu untuk Berinovasi dan Mewujudkan Mimpi</p>
                </div>


            </div>
    </section>

    <div class="container mt-8 mb-10">
        <div class="mb-4 flex justify-between items-center">
            <div class="flex-shrink-0">
                <button
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md focus:outline-none focus:ring focus:border-blue-700">
                    Cari
                </button>
            </div>
            <div class="flex-grow ml-4">
                <input type="text" id="searchInput"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-400"
                    placeholder="Cari menggunakan Nama , Nama Ibu Kandung atau dengan ID Buka Rekening">
            </div>
        </div>
        <div class="overflow-x-auto overflow-y-auto h-96 rounded-lg border border-gray-200">
            <table id="dataTable" class="min-w-full divide-y-2 divide-white-200 bg-white text-sm">
                <thead class="ltr:text-center rtl:text-center sticky-header text-white">
                    <tr class="text-center">
                        <th style="width: 10%; padding: 0.75rem;">ID Nasabah</th>
                        <th style="width: 10%; padding: 0.75rem;">Nama</th>
                        <th style="width: 15%; padding: 0.75rem;">Alamat</th>
                        <th style="width: 10%; padding: 0.75rem;">No.Telp</th>
                        <th style="width: 15%; padding: 0.75rem;">Email</th>
                        <th style="width: 10%; padding: 0.75rem;">Tanggal Lahir</th>
                        <th style="width: 10%; padding: 0.75rem;">Pekerjaan</th>
                        <th style="width: 10%; padding: 0.75rem;">Status</th>
                        <th style="width: 10%; padding: 0.75rem;">Income Nasabah</th>
                        <th style="width: 10%; padding: 0.75rem;">ID Buka Rekening</th>
                        <th style="width: 10%; padding: 0.75rem;">Jenis Kelamin</th>
                        <th style="width: 10%; padding: 0.75rem;">Aksi</th>
                        <!-- Sesuaikan dengan kolom lainnya -->
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    <?php
                    // Tampilkan data dari database
                    $tampil = mysqli_query($koneksi, "SELECT * FROM nasabah ORDER BY id_nasabah DESC");
                    while ($data = mysqli_fetch_array($tampil)) {
                        ?>

                        <tr class=" text-gray-700">
                            <td class="text-center">
                                <?= $data['id_nasabah'] ?>
                            </td>
                            <td>
                                <?= $data['nama'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['alamat'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['no_telepon'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['email'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['tanggal_lahir'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['pekerjaan'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['status'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['income'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['id_buka_rekening'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['gender_id_gender'] ?>
                            </td>
                            <td>

                                <button class="mt-3 mb-2">
                                    <a href="nasabah.php?hal=hapus&no_nas=<?= $data['no_nasabah'] ?>"
                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 size-sm mt-3">Hapus</a>
                                </button>

                                <button class="mt-3 mb-2">
                                    <a href="nasabah.php?hal=edit&no_nas=<?= $data['no_nasabah'] ?>"
                                        class="text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-1.5 me-2 mb-2 dark:focus:ring-yellow-900 size-sm mt-3">Edit</a>

                                </button>

                            </td>
                            <!-- Sesuaikan dengan kolom lainnya -->
                        </tr>
                        <?php
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>

    !-- Tambahkan jQuery jika belum ada -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        // untuk mengaktifkan fitur pencarian
        $(document).ready(function () {
            $("#searchInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#dataTable tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });

        $(document).ready(function () {
            var originalData; // variabel untuk menyimpan data asli

            // Fungsi untuk menampilkan data sesuai pencarian
            function refreshTable(searchValue) {
                var filteredData = originalData.filter(function (data) {
                    return (
                        data.id_nasabah.includes(searchValue) ||
                        data.nama.toLowerCase().includes(searchValue) ||
                        data.id_buka_rekening.toLowerCase().includes(searchValue)
                    );
                });

                $("#dataTable tbody").empty();
                $.each(filteredData, function (index, data) {
                    $("#dataTable tbody").append(
                        "<tr>" +
                        "<td class='text-center'>" + data.no_nasabah + "</td>" +
                        "<td class='text-center'>" + data.id_nasabah + "</td>" +
                        "<td class='text-center'>" + data.nama + "</td>" +
                        "<td class='text-center'>" + data.alamat + "</td>" +
                        "<td class='text-center'>" + data.jam_no_telepon + "</td>" +
                        "<td class='text-center'>" + data.email + "</td>" +
                        "<td class='text-center'>" + data.tanggal_lahir + "</td>" +
                        "<td class='text-center'>" + data.pekerjaan + "</td>" +
                        "<td class='text-center'>" + data.status + "</td>" +
                        "<td class='text-center'>" + data.income + "</td>" +
                        "<td class='text-center'>" + data.id_buka_rekening + "</td>" +
                        "<td class='text-center'>" + data.gender_id_gender + "</td>" +
                        "</tr>"
                    );
                });
            }


            // Event listener untuk input pencarian
            $("#searchInput").on("input", function () {
                var searchValue = $(this).val().toLowerCase();
                refreshTable(searchValue);
            });
            // Pertama kali, tampilkan data asli
            refreshTable("");
        });


        function showNewId() {
            // Tampilkan ID baru di dalam input teks
            document.getElementById('newIdInput').value = '<?= $newId ?>';
        }
    </script>

</body>

</html>