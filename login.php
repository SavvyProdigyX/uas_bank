<?php
session_start();

// Sertakan file koneksi
include('koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["tUsername"];
    $password = $_POST["password"];

    // Buat query dengan parameterized statement
    $query = "SELECT * FROM login_db WHERE username=? AND password=?";
    $stmt = mysqli_prepare($koneksi, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);

    // Execute query
    mysqli_stmt_execute($stmt);

    // Check for errors in statement preparation
    if (!$stmt) {
        die('Error dalam persiapan pernyataan: ' . mysqli_error($koneksi));
    }

    // Check for errors in statement execution
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        die('Error dalam eksekusi pernyataan: ' . mysqli_error($koneksi));
    }

    // Check the result
    if (mysqli_num_rows($result) > 0) {
        // Login berhasil, arahkan ke halaman lain atau lakukan aksi lainnya
        header("Location: dashboard.php");
        $_SESSION['login_status'] = 'success';
        exit();
    } else {
        // Login gagal, arahkan kembali ke halaman login dengan pesan error
        header("Location: login.php?login_failed=true");
        $_SESSION['login_status'] = 'failed';
        exit();
    }
}

// Tutup koneksi
mysqli_close($koneksi);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<style>
    .card {
        margin-top: 15%;
    }

    h1 {
        font-family: 'JetBrains Mono', Courier, monospace;
    }
</style>

<body>
    <section class="bg-gray-50 min-h-screen flex items-center justify-center">
        <!-- login container -->
        <div class="bg-gray-100 flex rounded-2xl shadow-lg max-w-3xl p-5 items-center">
            <!-- form -->
            <div class="md:w-1/2 px-8 md:px-16">
                <h2 class="font-bold text-2xl text-[#002D74]">Login</h2>
                <p class="text-xs mt-2 text-[#002D74]">Masukkan username dan password Anda</p>

                <!-- Tambahkan pesan kesalahan jika login gagal -->
                <?php if (isset($_GET['login_failed']) && $_GET['login_failed'] === 'true'): ?>
                    <script>
                        // Tampilkan alert ketika login gagal
                        alert("Login gagal. Periksa kembali username dan password Anda.");
                    </script>
                    <?php $_SESSION['login_status'] = 'failed'; ?>
                <?php endif; ?>

                <form action="login.php" method="post" class="flex flex-col gap-4">
                    <input class="p-2 mt-8 rounded-xl border" type="text" name="tUsername"
                        placeholder="Masukkan Username">
                    <div class="relative">
                        <input class="p-2 rounded-xl border w-full" type="password" name="password"
                            placeholder="Masukkan Password">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="gray"
                            class="bi bi-eye absolute top-1/2 right-3 -translate-y-1/2" viewBox="0 0 16 16">
                            <path
                                d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                            <path
                                d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                        </svg>
                    </div>
                    <button class="bg-[#002D74] rounded-xl text-white py-2 hover:scale-105 duration-300">Login</button>
                </form>

                <div class="mt-6 grid grid-cols-3 items-center text-gray-400">
                    <hr class="border-gray-400">
                    <p class="text-center text-sm">ATAU</p>
                    <hr class="border-gray-400">
                </div>

                <div class="text-xs border-b border-[#002D74] py-4 text-[#002D74]">
                    <a href="#">Lupa password Anda?</a>
                </div>

                <div class="mt-3 text-xs flex justify-between items-center text-[#002D74]">
                </div>
            </div>

            <!-- image -->
            <div class="md:block hidden w-1/2">
                <img class="rounded-2xl" src="img/logi_pic.jpg">
            </div>
        </div>
    </section>

    <script>
        kofiWidgetOverlay.draw('mohamedghulam', {
            'type': 'floating-chat',
            'floating-chat.donateButton.text': 'Dukung saya',
            'floating-chat.donateButton.background-color': '#323842',
            'floating-chat.donateButton.text-color': '#fff'
        });
    </script>

</body>

</html>