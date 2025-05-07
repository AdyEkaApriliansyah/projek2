<?php
session_start();
include '../config/koneksi.php';

if (isset($_SESSION['loggedin'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['loggedin'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Admin</title>
    <link rel="stylesheet" href="../src/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-sky-100 to-blue-200 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-2xl rounded-3xl flex max-w-4xl w-full overflow-hidden">
        <!-- Left Side with Illustration -->
        <div class="w-1/2 bg-sky-600 text-white p-10 flex flex-col justify-center items-center hidden md:flex">
            <h2 class="text-3xl font-bold mb-4">Selamat Datang!</h2>
            <p class="text-lg text-center">Masuk ke dashboard admin dan kelola data Anda dengan mudah.</p>
            <img src="../src/login.svg" alt="Login Illustration" class="mt-8 w-3/4">
        </div>

        <!-- Right Side with Form -->
        <div class="w-full md:w-1/2 p-8 sm:p-12">
            <h2 class="text-2xl font-bold text-sky-700 mb-6 text-center">Login Admin</h2>

            <?php if (isset($error)): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm text-center">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block mb-1 text-sky-700 font-medium">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Masukkan username"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-sky-500 focus:outline-none" />
                </div>

                <div>
                    <label for="password" class="block mb-1 text-sky-700 font-medium">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan password"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-sky-500 focus:outline-none" />
                </div>

                <button type="submit"
                    class="w-full bg-sky-600 hover:bg-sky-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                    Masuk
                </button>
            </form>

            <div class="text-center mt-6">
                <a href="../index.php" class="text-sky-600 hover:text-sky-800 text-sm inline-flex items-center transition-colors">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>

</html>