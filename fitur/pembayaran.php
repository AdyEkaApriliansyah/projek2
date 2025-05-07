<?php
include '../config/koneksi.php';
session_start();

class Pembayaran
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function createData($data)
    {
        $tanggal = mysqli_real_escape_string($this->koneksi, $data['tanggal']);
        $jumlah_bayar = mysqli_real_escape_string($this->koneksi, $data['jumlah_bayar']);
        $peminjaman_id = mysqli_real_escape_string($this->koneksi, $data['peminjaman_id']);
        $query = "INSERT INTO pembayaran (tanggal, jumlah_bayar, peminjaman_id)
                  VALUES ('$tanggal', '$jumlah_bayar', '$peminjaman_id')";
        return mysqli_query($this->koneksi, $query);
    }

    public function read()
    {
        $query = "SELECT pembayaran.*, peminjaman.nama_peminjam AS nama_peminjam, peminjaman.keperluan_pinjam AS keperluan_pinjam
                  FROM pembayaran
                  INNER JOIN peminjaman ON pembayaran.peminjaman_id = peminjaman.id";
        return mysqli_query($this->koneksi, $query);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM pembayaran WHERE id = $id";
        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_assoc($result);
    }

    public function updateData($id, $data)
    {
        $tanggal = mysqli_real_escape_string($this->koneksi, $data['tanggal']);
        $jumlah_bayar = mysqli_real_escape_string($this->koneksi, $data['jumlah_bayar']);
        $peminjaman_id = mysqli_real_escape_string($this->koneksi, $data['peminjaman_id']);
        $query = "UPDATE pembayaran SET
                  tanggal = '$tanggal',
                  jumlah_bayar = '$jumlah_bayar',
                  peminjaman_id = '$peminjaman_id'
                  WHERE id = $id";
        return mysqli_query($this->koneksi, $query);
    }

    public function deleteData($id)
    {
        $query = "DELETE FROM pembayaran WHERE id = $id";
        return mysqli_query($this->koneksi, $query);
    }

    public function getPeminjaman()
    {
        $query = "SELECT * FROM peminjaman";
        return mysqli_query($this->koneksi, $query);
    }
}

$pembayaran = new Pembayaran($koneksi);
$dataPeminjamanList = $pembayaran->getPeminjaman();

// Create and Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        // Update
        if ($pembayaran->updateData($_POST['id'], $_POST)) {
            $_SESSION['message'] = 'Data pembayaran berhasil diupdate';
        } else {
            $_SESSION['message'] = 'Data pembayaran gagal diupdate';
        }
    } else {
        // Create
        if ($pembayaran->createData($_POST)) {
            $_SESSION['message'] = 'Data pembayaran berhasil ditambah';
        } else {
            $_SESSION['message'] = 'Data pembayaran gagal ditambah';
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Delete
if (isset($_GET['delete'])) {
    if ($pembayaran->deleteData($_GET['delete'])) {
        $_SESSION['message'] = 'Data pembayaran berhasil dihapus';
    } else {
        $_SESSION['message'] = 'Data pembayaran gagal dihapus';
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$dataPembayaran = $pembayaran->read();
$editData = null;
if (isset($_GET['edit'])) {
    $editData = $pembayaran->getById($_GET['edit']);
}

$i = 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .action-btn {
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Pembayaran</h1>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
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

            <div class="bg-blue-50 rounded-lg p-6 mb-8 border border-blue-100">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <?= $editData ? 'Edit Data Pembayaran' : 'Tambah Data Pembayaran' ?>
                </h2>
                <form method="POST" class="space-y-4">
                    <?php if ($editData): ?>
                        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                    <?php endif; ?>
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembayaran</label>
                        <input type="date" id="tanggal" name="tanggal" required
                            value="<?= $editData ? $editData['tanggal'] : date('Y-m-d') ?>"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label for="jumlah_bayar" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pembayaran</label>
                        <input type="number" id="jumlah_bayar" name="jumlah_bayar" required
                            value="<?= $editData ? $editData['jumlah_bayar'] : '' ?>"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label for="peminjaman_id" class="block text-sm font-medium text-gray-700 mb-1">ID Peminjaman</label>
                        <select id="peminjaman_id" name="peminjaman_id" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                            <option value="">Pilih ID Peminjaman</option>
                            <?php while ($rowPeminjaman = mysqli_fetch_assoc($dataPeminjamanList)): ?>
                                <option value="<?= $rowPeminjaman['id'] ?>"
                                    <?= ($editData && $editData['peminjaman_id'] == $rowPeminjaman['id']) ? 'selected' : '' ?>>
                                    <?= $rowPeminjaman['id'] ?> - <?= $rowPeminjaman['nama_peminjam'] ?> (<?= $rowPeminjaman['keperluan_pinjam'] ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit"
                            class="action-btn bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm">
                            <?= $editData ? 'Update Data' : 'Simpan Data' ?>
                        </button>
                        <?php if ($editData): ?>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>"
                                class="action-btn bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-lg shadow-sm">
                                Batal
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Daftar Pembayaran</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Bayar</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Peminjaman</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peminjam</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keperluan Pinjam</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = mysqli_fetch_assoc($dataPembayaran)): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $i++; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['tanggal'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['jumlah_bayar'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['peminjaman_id'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['nama_peminjam'] ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500"><?= $row['keperluan_pinjam'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        <div class="flex space-x-3 justify-end">
                                            <a href="?edit=<?= $row['id'] ?>"
                                                class="action-btn text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md text-sm font-medium">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                            <a href="?delete=<?= $row['id'] ?>"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data pembayaran ini?')"
                                                class="action-btn text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md text-sm font-medium">
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">
                <a href="../pages/dashboard.php" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition duration-200 ease-in-out shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</body>

</html>