<?php


include('koneksi.php');

if (isset($_POST['btnSimpan'])) {
    $id_buka_rekening = $_POST['id_buka_rekening'];


    $check_duplicate = mysqli_query($koneksi, "SELECT COUNT(*) FROM buka_rekening 
    WHERE id_buka_rekening = '$id_buka_rekening'");
    $is_duplicate = mysqli_fetch_assoc($check_duplicate)['COUNT(*)'];

    if ($is_duplicate > 0) {

        echo "<script>
            alert('Data dengan ID Buka Rekening sudah ada. Silakan gunakan 
            ID yang berbeda.');
            document.location = 'buka_rekening.php';
        </script>";
    } else {

        $simpan = mysqli_query($koneksi, "INSERT INTO buka_rekening (id_buka_rekening, no_identitas,
        nama_calon, tanggal_lahir, alamat_calon, 
        nama_ibu_kandung, no_telepon_calon, income_calon, setoran_awal, 
        tanggal_pembukaan, cs_id_cs, type_rekening_id_type) VALUES (
                '$_POST[id_buka_rekening]',
                '$_POST[no_identitas]',
                '$_POST[nama_calon]',
                '$_POST[tgl_lahir]',
                '$_POST[alamat]',
                '$_POST[ibu_kandung]',
                '$_POST[no_telp]',
                '$_POST[income]',
                '$_POST[setoran_awal]',
                '$_POST[tgl_pembukaan]',
                '$_POST[id_cs]',
                '$_POST[tipe_rekening]'
            )");

        if ($simpan) {
            echo "<script>
                alert('Data Berhasil disimpan');
                document.location = 'buka_rekening.php';
            </script>";
        } else {
            echo "<script>
                alert('Data Gagal Disimpan');
                document.location = 'buka_rekening.php';
            </script>";
        }
    }
}






if (isset($_POST['btnUpdate'])) {
    if (!isset($_GET['hal']) || ($_GET['hal'] != "edit")) {
        // Memeriksa apakah id_buka_rekening sudah ada
        $id_buka_rekening = $_POST['id_buka_rekening'];
        $check_duplicate = mysqli_query($koneksi, "SELECT COUNT(*) FROM buka_rekening WHERE id_buka_rekening = '$id_buka_rekening'");
        $is_duplicate = mysqli_fetch_assoc($check_duplicate)['COUNT(*)'];

        if ($is_duplicate > 0) {
            // Ada duplikasi, update data yang sudah ada
            $update_existing = mysqli_query($koneksi, "UPDATE buka_rekening SET
                                            no_identitas = '$_POST[no_identitas]',
                                            nama_calon = '$_POST[nama_calon]',
                                            tanggal_lahir = '$_POST[tgl_lahir]',
                                            alamat_calon = '$_POST[alamat]',
                                            nama_ibu_kandung = '$_POST[ibu_kandung]',
                                            no_telepon_calon = '$_POST[no_telp]',
                                            income_calon = '$_POST[income]',
                                            setoran_awal = '$_POST[setoran_awal]',
                                            tanggal_pembukaan = '$_POST[tgl_pembukaan]',
                                            cs_id_cs = '$_POST[id_cs]',
                                            type_rekening_id_type = '$_POST[tipe_rekening]'
                                            WHERE id_buka_rekening = '$id_buka_rekening'");

            if ($update_existing) {
                echo "<script>
                    alert('Data Berhasil diupdate');
                    document.location = 'buka_rekening.php';
                </script>";
            } else {
                echo "<script>
                    alert('Gagal mengupdate data yang sudah ada.');
                    document.location = 'buka_rekening.php';
                </script>";
            }
        }
    }
}

if (isset($_GET['hal']) && $_GET['hal'] == 'hapus') {
    // Pastikan parameter no_registrasi ada dan tidak kosong
    if (isset($_GET['no_reg']) && !empty($_GET['no_reg'])) {
        $no_registrasi_to_delete = $_GET['no_reg'];

        // Ambil id_buka_rekening dan no_registrasi dari data yang akan dihapus
        $query_get_data = mysqli_query($koneksi, "SELECT id_buka_rekening, no_registrasi FROM buka_rekening WHERE no_registrasi = '$no_registrasi_to_delete'");
        $data_to_delete = mysqli_fetch_assoc($query_get_data);

        // Lakukan query penghapusan
        $hapus = mysqli_query($koneksi, "DELETE FROM buka_rekening WHERE no_registrasi = '$no_registrasi_to_delete'");

        if ($hapus) {
            // Perbarui nilai id_buka_rekening dan no_registrasi untuk data dengan id_buka_rekening lebih besar
            mysqli_query($koneksi, "UPDATE buka_rekening SET 
                                    id_buka_rekening = id_buka_rekening - 1,
                                    no_registrasi = no_registrasi - 1 
                                    WHERE id_buka_rekening > '{$data_to_delete['id_buka_rekening']}'");

            echo "<script>
                alert('Data berhasil dihapus');
                document.location = 'buka_rekening.php';
            </script>";
        } else {
            echo "<script>
                alert('Gagal menghapus data');
                document.location = 'buka_rekening.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Parameter no_registrasi tidak valid');
            document.location = 'buka_rekening.php';
        </script>";
    }

    // Hentikan eksekusi skrip untuk menghindari pemrosesan lebih lanjut
    exit();
}

// proses hapus data
if (isset($_GET['hal']) && $_GET['hal'] == 'hapus') {
    // Pastikan parameter no_registrasi ada dan tidak kosong
    if (isset($_GET['no_reg']) && !empty($_GET['no_reg'])) {
        $no_registrasi_to_delete = $_GET['no_reg'];

        // Lakukan query penghapusan
        $hapus = mysqli_query($koneksi, "DELETE FROM buka_rekening WHERE no_registrasi = '$no_registrasi_to_delete'");

        if ($hapus) {
            echo "<script>
                alert('Data berhasil dihapus');
                document.location = 'buka_rekening.php';
            </script>";
        } else {
            echo "<script>
                alert('Gagal menghapus data');
                document.location = 'buka_rekening.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Parameter no_registrasi tidak valid');
            document.location = 'buka_rekening.php';
        </script>";
    }

    // Hentikan eksekusi skrip untuk menghindari pemrosesan lebih lanjut
    exit();
}

// fungsi untuk mendapatkan id buka rekening baru 
function generateNewId($koneksi)
{
    $query_get_max_id = mysqli_query($koneksi, "SELECT MAX(id_buka_rekening) as max_id FROM buka_rekening");
    $result = mysqli_fetch_assoc($query_get_max_id);
    $max_id = $result['max_id'];

    if ($max_id === null) {
        // Jika belum ada data di database,
        return 'REG5502301'; // ID awal jika database kosong
    } else {
        // Jika ada data di database,
        $last_number = substr($max_id, -4);
        $new_number = intval($last_number) + 1;
        return 'REG550' . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }
}

// Panggil fungsi untuk mendapatkan ID baru
$new_id = generateNewId($koneksi);







//deklarasi variabel untuk menampung data yang akan di edit
$vIdbukarekening = "";
$vNoIdentitas = "";
$vNamacalon = "";
$vTglLahir = "";
$vAlamat = "";
$vIbuKandung = "";
$vNoTelp = "";
$vIncome = "";
$vSetoranAwal = "";
$vTglPembukaan = "";
$vIdcs = "";
$vIdtype = "";


// pengujian jika tombol edit di klik
if (isset($_GET['hal'])) {
    // pengujian jika edit data
    if ($_GET['hal'] == "edit") {
        // tampilkan data yang akan di edit
        $tampil = mysqli_query($koneksi, "SELECT * FROM buka_rekening WHERE no_registrasi = '$_GET[no_reg]'");
        $data = mysqli_fetch_array($tampil);

        if ($data) {
            // data ditemukan
            $vIdbukarekening = $data['id_buka_rekening'];
            $vNoIdentitas = $data['no_identitas'];
            $vNamacalon = $data['nama_calon'];
            $vTglLahir = $data['tanggal_lahir'];
            $vAlamat = $data['alamat_calon'];
            $vIbuKandung = $data['nama_ibu_kandung'];
            $vNoTelp = $data['no_telepon_calon'];
            $vIncome = $data['income_calon'];
            $vSetoranAwal = $data['setoran_awal'];
            $vTglPembukaan = $data['tanggal_pembukaan'];
            $vIdcs = $data['cs_id_cs'];
            $vIdtype = $data['type_rekening_id_type'];

        }



    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buka Rekening</title>
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
        z-index: 100;
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
                            <form method="POST" action="buka_rekening.php">
                                <div class="grid grid-cols-2 gap-4 sm:grid-cols-2 mb-2 ">
                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">Id
                                            Buka Rekening
                                        </label>
                                        <input type="text" id="newIdInput"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukkan Id Buka Rekening" name="id_buka_rekening"
                                            value="<?= $vIdbukarekening ?>" value="<?= $new_id ?>" readonly
                                            id="newIdInput">
                                    </div>

                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            No. Identitas
                                        </label>
                                        <input type="text" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukkan No. Identitas" name="no_identitas"
                                            value="<?= $vNoIdentitas ?>">
                                    </div>

                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-2">
                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Nama Calon
                                        </label>
                                        <input type="text" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukan Nama Calon" name="nama_calon"
                                            value="<?= $vNamacalon ?>">
                                    </div>

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
                                            Alamat
                                        </label>
                                        <input type="text" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukkan Alamat" name="alamat" value="<?= $vAlamat ?>">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-2">
                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Nama Ibu Kandung
                                        </label>
                                        <input type="text" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukkan Nama Ibu" name="ibu_kandung"
                                            value="<?= $vIbuKandung ?>">
                                    </div>

                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            No.Telp Calon
                                        </label>
                                        <input type="text" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukkan No. Telp" name="no_telp" value="<?= $vNoTelp ?>">
                                    </div>
                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Income Calon
                                        </label>
                                        <input type="text" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukkan Income" name="income" value="<?= $vIncome ?>">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-2">
                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Setoran Awal
                                        </label>
                                        <input type="text" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukkan Setoran Awal" name="setoran_awal"
                                            value="<?= $vSetoranAwal ?>">
                                    </div>

                                    <div class="mb-2">
                                        <label for="base-input"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Tanggal Pembukaan
                                        </label>
                                        <input type="date" id="base-input"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Masukkan Tgl.Pembukaan" name="tgl_pembukaan"
                                            value="<?= $vTglPembukaan ?>">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-2">

                                    <div>
                                        <label for="default"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">Id
                                            Cs</label>
                                        <select id="default"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            name="id_cs">
                                            <?php
                                            // Tampilkan opsi untuk setiap ID Cs
                                            $id_cs_options = [
                                                "220101" => "Hafidz Rahmatullah",
                                                "220102" => "Siti Aisyah",
                                                "220103" => "Dewi Lestari"
                                            ];

                                            foreach ($id_cs_options as $value => $label) {
                                                // Tentukan apakah opsi saat ini harus dipilih
                                                $selected = ($vIdcs == $value) ? 'selected' : '';

                                                // Tampilkan elemen <option> dengan label dan nilai yang sesuai
                                                echo "<option value='$value' $selected>$label</option>";
                                            }
                                            ?>

                                        </select>
                                    </div>

                                    <div>
                                        <label for="default"
                                            class="block mb-1 ml-2 text-sm font-medium text-gray-900 dark:text-white">Tipe
                                            Rekening</label>
                                        <select id="default"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            name="tipe_rekening">
                                            <?php
                                            // Tampilkan opsi untuk setiap tipe rekening
                                            $tipe_rekening = [
                                                "330001" => "Tahapan Xpresi",
                                                "330002" => "Tahapan Gold",
                                                "330003" => "Tahapan Berjangka",
                                                "330004" => "Simpanan Pelajar"
                                            ];

                                            foreach ($tipe_rekening as $value => $label) {
                                                // Tentukan apakah opsi saat ini harus dipilih
                                                $selected = ($tipe_rekening == $value) ? 'selected' : '';

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
                        Work Hard Try Hard for your future</h1>
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
                        <th style="width: 10%; padding: 0.75rem;">ID Buka Rekening</th>
                        <th style="width: 10%; padding: 0.75rem;">No.Identitas</th>
                        <th style="width: 15%; padding: 0.75rem;">Nama Calon</th>
                        <th style="width: 10%; padding: 0.75rem;">Tgl.Lahir</th>
                        <th style="width: 15%; padding: 0.75rem;">Alamat Calon</th>
                        <th style="width: 15%; padding: 0.75rem;">Nama Ibu Kandung</th>
                        <th style="width: 10%; padding: 0.75rem;">No.Telepon</th>
                        <th style="width: 10%; padding: 0.75rem;">Income Calon</th>
                        <th style="width: 10%; padding: 0.75rem;">Setoran Awal</th>
                        <th style="width: 10%; padding: 0.75rem;">Tgl.Pembukaan</th>
                        <th style="width: 10%; padding: 0.75rem;">ID CS</th>
                        <th style="width: 10%; padding: 0.75rem;">Tipe Rekening</th>
                        <th style="width: 10%; padding: 0.75rem;">Aksi</th>
                        <!-- Sesuaikan dengan kolom lainnya -->
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    <?php
                    // Tampilkan data dari database
                    $tampil = mysqli_query($koneksi, "SELECT * FROM buka_rekening ORDER BY id_buka_rekening DESC");
                    while ($data = mysqli_fetch_array($tampil)) {
                        ?>

                        <tr class=" text-gray-700">
                            <td class="text-center">
                                <?= $data['id_buka_rekening'] ?>
                            </td>
                            <td>
                                <?= $data['no_identitas'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['nama_calon'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['tanggal_lahir'] ?>
                            </td>
                            <td>
                                <?= $data['alamat_calon'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['nama_ibu_kandung'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['no_telepon_calon'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['income_calon'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['setoran_awal'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['tanggal_pembukaan'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['cs_id_cs'] ?>
                            </td>
                            <td class="text-center">
                                <?= $data['type_rekening_id_type'] ?>
                            </td>
                            <td>

                                <button class="mt-3 mb-2">
                                    <a href="buka_rekening.php?hal=hapus&no_reg=<?= $data['no_registrasi'] ?>"
                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 size-sm mt-3">Hapus</a>
                                </button>

                                <button class="mt-3 mb-2">
                                    <a href="buka_rekening.php?hal=edit&no_reg=<?= $data['no_registrasi'] ?>"
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
                        data.email.toLowerCase().includes(searchValue) ||
                        data.buka_rekening_id_buka_rekening.toLowerCase().includes(searchValue)
                    );
                });

                // Mengganti isi tabel dengan data hasil pencarian
                $("#dataTable tbody").empty();
                $.each(filteredData, function (index, data) {
                    $("#dataTable tbody").append(
                        "<tr>" +
                        "<td>" + data.id_nasabah + "</td>" +
                        "<td>" + data.nama + "</td>" +
                        "<td>" + data.alamat + "</td>" +
                        "<td>" + data.no_telepon + "</td>" +
                        "<td>" + data.email + "</td>" +
                        "<td>" + data.tanggal_lahir + "</td>" +
                        "<td>" + data.pekerjaan + "</td>" +
                        "<td>" + data.status + "</td>" +
                        "<td>" + data.income + "</td>" +
                        "<td>" + data.buka_rekening_id_buka_rekening + "</td>" +
                        "<td>" + data.gender_id_gender + "</td>" +
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
        // Untuk mengaktifkan fitur pencarian selesai
        function showNewId() {
            // Tampilkan ID baru di dalam input teks
            document.getElementById('newIdInput').value = '<?= $new_id ?>';
        }


    </script>


</body>
<footer class="bg-gray-100">
    <div class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8">

        <p class="mx-auto mt-6 max-w-md text-center leading-relaxed text-gray-500">
            Temukan Jalan Mu Raih Mimpi Bersama Kami .
        </p>

        <p class="mx-auto mt-2 max-w-md text-center leading-relaxed text-gray-500">
            Go To <a href="#">carrer-bank-utuk.co.id</a>
        </p>

        <ul class="mt-12 flex justify-center gap-6 md:gap-8">
            <li>
                <a href="/" rel="noreferrer" target="_blank" class="text-gray-700 transition hover:text-gray-700/75">
                    <span class="sr-only">Facebook</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </li>

            <li>
                <a href="/" rel="noreferrer" target="_blank" class="text-gray-700 transition hover:text-gray-700/75">
                    <span class="sr-only">Instagram</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </li>

            <li>
                <a href="/" rel="noreferrer" target="_blank" class="text-gray-700 transition hover:text-gray-700/75">
                    <span class="sr-only">Twitter</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                    </svg>
                </a>
            </li>

            <li>
                <a href="/" rel="noreferrer" target="_blank" class="text-gray-700 transition hover:text-gray-700/75">
                    <span class="sr-only">GitHub</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </li>

            <li>
                <a href="/" rel="noreferrer" target="_blank" class="text-gray-700 transition hover:text-gray-700/75">
                    <span class="sr-only">Dribbble</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c5.51 0 10-4.48 10-10S17.51 2 12 2zm6.605 4.61a8.502 8.502 0 011.93 5.314c-.281-.054-3.101-.629-5.943-.271-.065-.141-.12-.293-.184-.445a25.416 25.416 0 00-.564-1.236c3.145-1.28 4.577-3.124 4.761-3.362zM12 3.475c2.17 0 4.154.813 5.662 2.148-.152.216-1.443 1.941-4.48 3.08-1.399-2.57-2.95-4.675-3.189-5A8.687 8.687 0 0112 3.475zm-3.633.803a53.896 53.896 0 013.167 4.935c-3.992 1.063-7.517 1.04-7.896 1.04a8.581 8.581 0 014.729-5.975zM3.453 12.01v-.26c.37.01 4.512.065 8.775-1.215.25.477.477.965.694 1.453-.109.033-.228.065-.336.098-4.404 1.42-6.747 5.303-6.942 5.629a8.522 8.522 0 01-2.19-5.705zM12 20.547a8.482 8.482 0 01-5.239-1.8c.152-.315 1.888-3.656 6.703-5.337.022-.01.033-.01.054-.022a35.318 35.318 0 011.823 6.475 8.4 8.4 0 01-3.341.684zm4.761-1.465c-.086-.52-.542-3.015-1.659-6.084 2.679-.423 5.022.271 5.314.369a8.468 8.468 0 01-3.655 5.715z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </li>
        </ul>
    </div>
</footer>

</html>