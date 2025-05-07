<?php
include './config/koneksi.php';

class ArmadaDisplay
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function getArmadaForDisplay()
    {
        $query = "SELECT
        armada.merk,
        armada.thn_beli,
        armada.deskripsi,
        armada.jenis_kendaraan_id,
        jenis_kendaraan.nama AS nama_jenis,
        armada.kapasitas_kursi,
        armada.rating
      FROM armada
      INNER JOIN jenis_kendaraan ON armada.jenis_kendaraan_id = jenis_kendaraan.id";
        $result = mysqli_query($this->koneksi, $query);
        $armada = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $armada[] = $row;
        }
        return $armada;
    }
}

$armadaDisplay = new ArmadaDisplay($koneksi);
$listArmada = $armadaDisplay->getArmadaForDisplay();

class TestimoniDisplay
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function getTestimoniPeminjam()
    {
        $query = "SELECT
                    p.nama_peminjam,
                    p.keperluan_pinjam,
                    p.mulai,
                    p.selesai,
                    a.merk AS nama_armada,
                    p.komentar_peminjam,
                    p.armada_id -- Tambahkan baris ini untuk mengambil armada_id
                  FROM peminjaman p
                  INNER JOIN armada a ON p.armada_id = a.id
                  WHERE p.status_pinjam = 'selesai' -- Hanya tampilkan peminjaman yang sudah selesai (opsional)
                  ";
        $result = mysqli_query($this->koneksi, $query);
        $testimonis = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $testimonis[] = $row;
        }
        return $testimonis;
    }
}

$testimoniDisplay = new TestimoniDisplay($koneksi);
$listTestimoni = $testimoniDisplay->getTestimoniPeminjam();



?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarRental - Sewa Mobil Terpercaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1502877338535-766e1452684a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #3b82f6;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .mobile-menu {
            transition: all 0.3s ease;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-question.active+.faq-answer {
            max-height: 300px;
        }

        .faq-question.active i {
            transform: rotate(180deg);
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="text-gray-800 bg-gray-50 scroll-smooth">
    <?php
    // Sample car data
    $cars = [
        [
            'name' => 'Toyota Avanza',
            'image' => 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1064&q=80',
            'price' => 350000,
            'capacity' => 7,
            'transmission' => 'Manual',
            'fuel' => 'Bensin'
        ],
        [
            'name' => 'Honda Brio',
            'image' => 'https://images.unsplash.com/photo-1550355291-bbee04a92027?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1036&q=80',
            'price' => 300000,
            'capacity' => 5,
            'transmission' => 'Automatic',
            'fuel' => 'Bensin'
        ],
        [
            'name' => 'Mitsubishi Xpander',
            'image' => 'https://images.unsplash.com/photo-1601362840469-51e4d8d58785?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80',
            'price' => 400000,
            'capacity' => 7,
            'transmission' => 'Automatic',
            'fuel' => 'Bensin'
        ],
        [
            'name' => 'Toyota Innova',
            'image' => 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1064&q=80',
            'price' => 450000,
            'capacity' => 8,
            'transmission' => 'Manual',
            'fuel' => 'Diesel'
        ],
    ];

    // Sample testimonials
    $testimonials = [
        [
            'name' => 'Budi Santoso',
            'photo' => 'https://randomuser.me/api/portraits/men/32.jpg',
            'text' => 'Pelayanan sangat memuaskan, mobil bersih dan terawat. Proses sewa yang mudah membuat perjalanan bisnis saya lancar.',
            'rating' => 5
        ],
        [
            'name' => 'Siti Rahma',
            'photo' => 'https://randomuser.me/api/portraits/women/44.jpg',
            'text' => 'Harga terjangkau dengan kualitas mobil yang bagus. Driver sangat profesional dan ramah.',
            'rating' => 4
        ],
        [
            'name' => 'Agus Purnomo',
            'photo' => 'https://randomuser.me/api/portraits/men/75.jpg',
            'text' => 'Sudah 3 kali sewa di sini dan selalu puas. Mobilnya selalu dalam kondisi prima.',
            'rating' => 5
        ],
    ];
    ?>

    <!-- Header Navigation -->
    <header class="bg-white shadow-lg fixed w-full z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="#" class="font-bold text-2xl text-blue-600 flex items-center">
                        <i class="fas fa-car-side mr-2"></i>
                        <span class="hidden sm:inline">CarRental</span>
                    </a>
                </div>
                <nav class="hidden md:flex space-x-8">
                    <a href="#beranda" class="nav-link text-gray-800 hover:text-blue-600 font-medium">Beranda</a>
                    <a href="#layanan" class="nav-link text-gray-800 hover:text-blue-600 font-medium">Layanan</a>
                    <a href="#mobil" class="nav-link text-gray-800 hover:text-blue-600 font-medium">Armada</a>
                    <a href="#tentang" class="nav-link text-gray-800 hover:text-blue-600 font-medium">Tentang</a>
                    <a href="#testimoni" class="nav-link text-gray-800 hover:text-blue-600 font-medium">Testimoni</a>
                    <a href="#kontak" class="nav-link text-gray-800 hover:text-blue-600 font-medium">Kontak</a>
                    <a href="./pages/login.php" class="nav-link text-gray-800 hover:text-blue-600 font-medium">Login</a>
                </nav>
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-800 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
            <div id="mobile-menu" class="mobile-menu hidden md:hidden mt-3 pb-2">
                <a href="#beranda" class="block py-2 px-4 text-gray-800 hover:bg-blue-50 rounded">Beranda</a>
                <a href="#layanan" class="block py-2 px-4 text-gray-800 hover:bg-blue-50 rounded">Layanan</a>
                <a href="#mobil" class="block py-2 px-4 text-gray-800 hover:bg-blue-50 rounded">Armada</a>
                <a href="#tentang" class="block py-2 px-4 text-gray-800 hover:bg-blue-50 rounded">Tentang</a>
                <a href="#testimoni" class="block py-2 px-4 text-gray-800 hover:bg-blue-50 rounded">Testimoni</a>
                <a href="#kontak" class="block py-2 px-4 text-gray-800 hover:bg-blue-50 rounded">Kontak</a>
                <a href="./pages/login.php" class="block py-2 px-4 text-gray-800 hover:bg-blue-50 rounded">Login</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="beranda" class="hero-bg h-screen flex items-center justify-center text-white pt-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">Sewa Mobil Terbaik <br> untuk Perjalanan Anda</h1>
            <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto">Pilihan lengkap kendaraan berkualitas dengan harga terjangkau untuk setiap kebutuhan perjalanan bisnis maupun liburan Anda</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#pesan" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-calendar-alt mr-2"></i> Pesan Sekarang
                </a>
                <a href="#mobil" class="bg-white text-blue-600 hover:bg-gray-100 font-bold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-car mr-2"></i> Lihat Armada
                </a>
            </div>
        </div>
    </section>

    <!-- Search Box -->
    <section id="pesan" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="bg-white rounded-xl shadow-xl p-6 md:p-10 -mt-20 relative z-20 max-w-4xl mx-auto border border-gray-100">
                <h2 class="text-2xl md:text-3xl font-bold mb-4 text-center text-gray-800">Pesan Mobil Sekarang</h2>
                <p class="text-center text-gray-600 mb-8">Isi formulir berikut atau hubungi kami untuk informasi lebih lanjut.</p>

                <form id="formPesan" action="" method="POST" class="space-y-6">
                    <div>
                        <label for="nama" class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" placeholder="Masukkan nama Anda"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label for="telepon" class="block text-gray-700 font-medium mb-2">Nomor Telepon / WhatsApp</label>
                        <input type="tel" id="telepon" name="telepon" placeholder="08xxxxxxxxxx"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label for="pesan" class="block text-gray-700 font-medium mb-2">Pesan / Pertanyaan</label>
                        <textarea id="pesan" name="pesan" rows="4" placeholder="Tulis pesan Anda"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                            Kirim Pesan
                        </button>
                    </div>
                </form>

                <div class="mt-10 text-center text-gray-600">
                    <p class="font-semibold">Hubungi Kontak Kami:</p>
                    <p>📞 0812-3456-7890</p>
                    <p>✉️ rentalmobil@example.com</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4 text-gray-800">Layanan Kami</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Kami menyediakan berbagai layanan untuk memastikan perjalanan Anda nyaman dan menyenangkan</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-md card-hover text-center border border-gray-100">
                    <div class="text-blue-600 text-4xl mb-4">
                        <i class="fas fa-car"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Sewa Mobil</h3>
                    <p class="text-gray-600">Pilihan mobil lengkap dari ekonomis hingga premium untuk kebutuhan perjalanan Anda</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md card-hover text-center border border-gray-100">
                    <div class="text-blue-600 text-4xl mb-4">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Sewa Driver</h3>
                    <p class="text-gray-600">Driver profesional dan berpengalaman untuk menjamin perjalanan aman dan nyaman</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md card-hover text-center border border-gray-100">
                    <div class="text-blue-600 text-4xl mb-4">
                        <i class="fas fa-route"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Antar Jemput</h3>
                    <p class="text-gray-600">Layanan antar jemput ke bandara, stasiun, atau tempat lainnya sesuai kebutuhan</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md card-hover text-center border border-gray-100">
                    <div class="text-blue-600 text-4xl mb-4">
                        <i class="fas fa-suitcase-rolling"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Paket Wisata</h3>
                    <p class="text-gray-600">Paket khusus untuk perjalanan wisata dengan rute yang sudah direncanakan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Cars Section -->
    <section id="mobil" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4 text-gray-800">Armada Mobil Kami</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Berbagai pilihan mobil berkualitas dengan kondisi prima untuk memenuhi kebutuhan perjalanan Anda</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($listArmada as $mobil): ?>
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 card-hover">
                        <div class="p-5">
                            <h3 class="text-xl font-bold mb-2 text-gray-800"><?php echo $mobil['merk']; ?></h3>
                            <div class="flex items-center mb-3">
                                <span class="text-blue-600 font-bold text-xl">
                                    <?php
                                    for ($i = 0; $i < $mobil['rating']; $i++) {
                                        echo '<i class="fas fa-star text-yellow-500 mr-1"></i>';
                                    }
                                    ?>
                                </span>
                                <span class="text-gray-500 text-sm ml-1">/rating</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-users text-blue-500 mr-2"></i>
                                    <span><?php echo $mobil['kapasitas_kursi']; ?> Orang</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-car text-blue-500 mr-2"></i>
                                    <span><?php echo $mobil['nama_jenis']; ?></span>
                                </div>
                                <div class="col-span-2 text-gray-600 text-sm">
                                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                    <span><?php echo $mobil['deskripsi']; ?> (Tahun <?php echo $mobil['thn_beli']; ?>)</span>
                                </div>
                                <div class="hidden">
                                    Jenis ID: <?php echo $mobil['jenis_kendaraan_id']; ?>
                                </div>
                            </div>
                            <a href="#pesan" class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                                <i class="fas fa-calendar-check mr-2"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-10">
                <a href="#" class="inline-block bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                    Lihat Semua Mobil <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="tentang" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center gap-10">
                <div class="lg:w-1/2">
                    <div class="relative rounded-xl overflow-hidden shadow-lg">
                        <img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1183&q=80" alt="Tentang CarRental" class="w-full h-auto">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-600/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6 text-white">
                            <h3 class="text-xl font-bold">10+ Tahun Pengalaman</h3>
                            <p>Melayani ribuan pelanggan</p>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <h2 class="text-3xl font-bold mb-6 text-gray-800">Tentang Kami</h2>
                    <p class="text-lg text-gray-600 mb-4">
                        CarRental adalah penyedia jasa sewa mobil terpercaya yang telah berpengalaman lebih dari 10 tahun dalam industri transportasi. Kami berkomitmen untuk memberikan pelayanan terbaik dengan armada mobil berkualitas dan terawat.
                    </p>
                    <p class="text-lg text-gray-600 mb-6">
                        Dengan tim profesional dan berpengalaman, kami menjamin kepuasan pelanggan dalam setiap perjalanan. Kami melayani berbagai kebutuhan transportasi baik untuk keperluan bisnis, keluarga, maupun liburan.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div class="flex items-center bg-white p-3 rounded-lg shadow-sm">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-check-circle text-blue-600"></i>
                            </div>
                            <span class="text-gray-800">Mobil Terawat</span>
                        </div>
                        <div class="flex items-center bg-white p-3 rounded-lg shadow-sm">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-check-circle text-blue-600"></i>
                            </div>
                            <span class="text-gray-800">Harga Transparan</span>
                        </div>
                        <div class="flex items-center bg-white p-3 rounded-lg shadow-sm">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-check-circle text-blue-600"></i>
                            </div>
                            <span class="text-gray-800">Driver Profesional</span>
                        </div>
                        <div class="flex items-center bg-white p-3 rounded-lg shadow-sm">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-check-circle text-blue-600"></i>
                            </div>
                            <span class="text-gray-800">Pelayanan 24 Jam</span>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#kontak" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 text-center">
                            <i class="fas fa-phone-alt mr-2"></i> Hubungi Kami
                        </a>
                        <a href="#testimoni" class="bg-white border border-blue-600 text-blue-600 hover:bg-blue-50 font-bold py-3 px-6 rounded-lg transition duration-300 text-center">
                            <i class="fas fa-star mr-2"></i> Testimoni
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimoni" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4 text-gray-800">Testimoni Pelanggan</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Pengalaman pelanggan yang telah menggunakan layanan kami</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($listTestimoni as $testimoni): ?>
                    <div class="bg-gray-50 p-6 rounded-xl shadow-md border border-gray-100">
                        <div class="mb-4">
                            <h4 class="font-bold text-gray-800"><?php echo $testimoni['nama_peminjam']; ?></h4>
                            <p class="text-gray-500 text-sm">Meminjam <?php echo $testimoni['nama_armada']; ?></p>
                        </div>
                        <p class="text-gray-600 italic">"<?php echo $testimoni['keperluan_pinjam']; ?>"</p>
                        <div class="mt-4 text-sm text-gray-600">
                            <i class="fas fa-calendar-alt mr-2"></i> Mulai: <?php echo date('d-m-Y', strtotime($testimoni['mulai'])); ?>
                            <br>
                            <i class="fas fa-calendar-check mr-2"></i> Selesai: <?php echo date('d-m-Y', strtotime($testimoni['selesai'])); ?>
                        </div>
                        <?php if (!empty($testimoni['komentar_peminjam'])): ?>
                            <div class="mt-4 text-gray-700">
                                <strong>Komentar:</strong> <?php echo $testimoni['komentar_peminjam']; ?>
                            </div>
                        <?php endif; ?>
                        <div class="mt-2 text-xs text-gray-400">
                            ID Armada: <?php echo $testimoni['armada_id']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4 text-gray-800">Pertanyaan Umum</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Temukan jawaban atas pertanyaan yang sering diajukan</p>
            </div>
            <div class="max-w-3xl mx-auto">
                <div class="mb-4">
                    <button class="faq-question w-full text-left font-bold p-4 bg-white rounded-lg shadow-md flex justify-between items-center">
                        <span>Apa saja persyaratan untuk menyewa mobil?</span>
                        <i class="fas fa-chevron-down text-blue-600 transition-transform"></i>
                    </button>
                    <div class="faq-answer bg-white rounded-b-lg shadow-sm px-4">
                        <p class="py-4 text-gray-600 border-t">Persyaratan untuk menyewa mobil meliputi KTP yang masih berlaku, SIM A atau SIM Internasional, dan deposit sesuai dengan jenis mobil yang disewa. Untuk pemesanan perusahaan, diperlukan juga surat tugas atau surat keterangan dari perusahaan.</p>
                    </div>
                </div>
                <div class="mb-4">
                    <button class="faq-question w-full text-left font-bold p-4 bg-white rounded-lg shadow-md flex justify-between items-center">
                        <span>Apakah tersedia layanan antar jemput?</span>
                        <i class="fas fa-chevron-down text-blue-600 transition-transform"></i>
                    </button>
                    <div class="faq-answer bg-white rounded-b-lg shadow-sm px-4">
                        <p class="py-4 text-gray-600 border-t">Ya, kami menyediakan layanan antar jemput ke bandara, stasiun, hotel, atau lokasi lainnya dengan biaya tambahan yang terjangkau. Anda dapat memilih layanan ini saat melakukan pemesanan.</p>
                    </div>
                </div>
                <div class="mb-4">
                    <button class="faq-question w-full text-left font-bold p-4 bg-white rounded-lg shadow-md flex justify-between items-center">
                        <span>Bagaimana kebijakan pembatalan reservasi?</span>
                        <i class="fas fa-chevron-down text-blue-600 transition-transform"></i>
                    </button>
                    <div class="faq-answer bg-white rounded-b-lg shadow-sm px-4">
                        <p class="py-4 text-gray-600 border-t">Pembatalan dapat dilakukan minimal 24 jam sebelum jadwal penggunaan untuk mendapatkan pengembalian dana penuh. Pembatalan kurang dari 24 jam akan dikenakan biaya 50% dari total harga sewa.</p>
                    </div>
                </div>
                <div class="mb-4">
                    <button class="faq-question w-full text-left font-bold p-4 bg-white rounded-lg shadow-md flex justify-between items-center">
                        <span>Apakah mobil sudah termasuk asuransi?</span>
                        <i class="fas fa-chevron-down text-blue-600 transition-transform"></i>
                    </button>
                    <div class="faq-answer bg-white rounded-b-lg shadow-sm px-4">
                        <p class="py-4 text-gray-600 border-t">Ya, semua mobil kami sudah dilengkapi dengan asuransi dasar yang mencakup kerusakan akibat kecelakaan. Anda juga dapat memilih asuransi tambahan untuk perlindungan yang lebih komprehensif.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4 text-gray-800">Hubungi Kami</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Jangan ragu untuk menghubungi kami jika Anda memiliki pertanyaan atau membutuhkan informasi lebih lanjut</p>
            </div>
            <div class="flex flex-col lg:flex-row gap-8">
                <div class="lg:w-1/2">
                    <form action="#" method="POST" class="bg-gray-50 p-6 rounded-xl shadow-md border border-gray-100">
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" id="name" name="name" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan nama lengkap">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" id="email" name="email" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan email">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="phone" class="block text-gray-700 font-medium mb-2">Nomor Telepon</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone-alt text-gray-400"></i>
                                </div>
                                <input type="tel" id="phone" name="phone" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan nomor telepon">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="message" class="block text-gray-700 font-medium mb-2">Pesan</label>
                            <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Tulis pesan Anda"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                            <i class="fas fa-paper-plane mr-2"></i> Kirim Pesan
                        </button>
                    </form>
                </div>
                <div class="lg:w-1/2">
                    <div class="bg-gray-50 p-6 rounded-xl shadow-md border border-gray-100 h-full">
                        <h3 class="text-xl font-bold mb-6 text-gray-800">Informasi Kontak</h3>
                        <div class="space-y-5">
                            <div class="flex items-start">
                                <div class="bg-blue-100 p-3 rounded-full mr-4">
                                    <i class="fas fa-map-marker-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Alamat</h4>
                                    <p class="text-gray-600">Jl. Raya Utama No. 123, Kota Jakarta, 12345</p>
                                    <a href="https://maps.google.com" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm mt-1 inline-block">
                                        <i class="fas fa-map-marked-alt mr-1"></i> Lihat di Peta
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="bg-blue-100 p-3 rounded-full mr-4">
                                    <i class="fas fa-phone-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Telepon</h4>
                                    <p class="text-gray-600">+62 21 1234 5678</p>
                                    <a href="tel:+622112345678" class="text-blue-600 hover:text-blue-800 text-sm mt-1 inline-block">
                                        <i class="fas fa-phone-volume mr-1"></i> Klik untuk Telepon
                                    </a>
                                    <p class="text-gray-500 text-sm mt-1">WhatsApp: +62 812 3456 7890</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="bg-blue-100 p-3 rounded-full mr-4">
                                    <i class="fas fa-envelope text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Email</h4>
                                    <p class="text-gray-600">info@carrental.com</p>
                                    <a href="mailto:info@carrental.com" class="text-blue-600 hover:text-blue-800 text-sm mt-1 inline-block">
                                        <i class="fa fa-envelope mr-1">Kirim pesan di email kami</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const button = document.getElementById("mobile-menu-button");
        const menu = document.getElementById("mobile-menu");

        button.addEventListener("click", () => {
            menu.classList.toggle("hidden");
        });
    });
</script>
<script>
    document.getElementById('formPesan').addEventListener('submit', function(e) {
        e.preventDefault(); // Mencegah reload
        alert('Data terkirim!');
        this.reset(); // Reset form setelah submit
    });
</script>

</html>