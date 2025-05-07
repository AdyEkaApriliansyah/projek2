<?php
session_start();
include '../config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/flowbite@1.6.6/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body class="bg-gray-100">

    <div class="flex">
        <!-- Sidebar -->
        <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-5 transition-transform -translate-x-full bg-blue-800 text-white sm:translate-x-0" aria-label="Sidebar">
            <div class="h-full px-3 pb-4 overflow-y-auto">
                <a href="#" class="flex items-center mb-6 pl-2.5">
                    <i class="fas fa-car-side text-xl mr-2"></i>
                    <span class="text-lg font-semibold whitespace-nowrap">Rental Kendaraan</span>
                </a>
                <ul class="space-y-2 font-medium">
                    <li><a href="dashboard.php" class="flex items-center p-2 rounded-lg bg-blue-700 hover:bg-blue-600"><i class="fas fa-tachometer-alt mr-3"></i>Dashboard</a></li>
                    <li><a href="../fitur/armada.php" class="flex items-center p-2 rounded-lg hover:bg-blue-700"><i class="fas fa-car mr-3"></i>Data Armada</a></li>
                    <li><a href="../fitur/jenis_kendaraan.php" class="flex items-center p-2 rounded-lg hover:bg-blue-700"><i class="fas fa-list-alt mr-3"></i>Jenis Kendaraan</a></li>
                    <li><a href="../fitur/peminjam.php" class="flex items-center p-2 rounded-lg hover:bg-blue-700"><i class="fas fa-clipboard-list mr-3"></i>Peminjaman</a></li>
                    <li><a href="../fitur/pembayaran.php" class="flex items-center p-2 rounded-lg hover:bg-blue-700"><i class="fas fa-money-bill-wave mr-3"></i>Pembayaran</a></li>
                    <li class="pt-10"><a href="./logout.php" class="flex items-center p-2 rounded-lg hover:bg-red-600"><i class="fas fa-sign-out-alt mr-3"></i>Logout</a></li>
                </ul>
            </div>
        </aside>

        <div class="w-full sm:ml-64">
            <nav class="bg-white shadow-md sticky top-0 z-30">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center space-x-4">
                        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-200 focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="hidden md:block text-gray-600"><?= date('l, d F Y') ?></span>
                        <div class="relative">
                            <i class="fas fa-bell text-xl text-gray-600"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <img src="../src/a.avif" class="w-8 h-8 rounded-full" alt="User photo">
                            <span class="hidden md:block font-medium">Admin</span>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="p-6">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 p-4 mb-6 rounded">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <p class="text-green-700"><?= $_SESSION['message'] ?></p>
                            </div>
                            <button onclick="this.parentElement.parentElement.style.display='none'" class="text-green-700 hover:text-green-900">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-6">
                    <?php
                    $query_armada = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM armada");
                    $armada = mysqli_fetch_assoc($query_armada);

                    $query_jenis = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM jenis_kendaraan");
                    $jenis = mysqli_fetch_assoc($query_jenis);

                    $query_peminjaman = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman");
                    $peminjaman = mysqli_fetch_assoc($query_peminjaman);

                    $query_pembayaran = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pembayaran");
                    $pembayaran = mysqli_fetch_assoc($query_pembayaran);
                    ?>

                    <?php
                    $cards = [
                        ['title' => 'Total Armada', 'total' => $armada['total'], 'icon' => 'fa-car', 'color' => 'blue', 'link' => '../fitur/armada.php'],
                        ['title' => 'Jenis Kendaraan', 'total' => $jenis['total'], 'icon' => 'fa-list-alt', 'color' => 'green', 'link' => '../fitur/jenis_kendaraan.php'],
                        ['title' => 'Peminjaman', 'total' => $peminjaman['total'], 'icon' => 'fa-clipboard-list', 'color' => 'yellow', 'link' => '../fitur/peminjam.php'],
                        ['title' => 'Pembayaran', 'total' => $pembayaran['total'], 'icon' => 'fa-money-bill-wave', 'color' => 'purple', 'link' => '../fitur/pembayaran.php']
                    ];
                    foreach ($cards as $card):
                    ?>
                        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-transform transform hover:-translate-y-1">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-500"><?= $card['title'] ?></p>
                                    <h3 class="text-2xl font-bold"><?= $card['total'] ?></h3>
                                </div>
                                <div class="p-3 rounded-full bg-<?= $card['color'] ?>-100 text-<?= $card['color'] ?>-600">
                                    <i class="fas <?= $card['icon'] ?> text-xl"></i>
                                </div>
                            </div>
                            <a href="<?= $card['link'] ?>" class="mt-4 inline-flex items-center text-<?= $card['color'] ?>-600 hover:text-<?= $card['color'] ?>-800">
                                Lihat detail <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </main>
        </div>
    </div>

</body>

</html>