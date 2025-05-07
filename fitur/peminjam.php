<?php
include '../config/koneksi.php';
session_start();

class Peminjam
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function createData($data)
    {
        $nama_peminjam = mysqli_real_escape_string($this->koneksi, $data['nama_peminjam']);
        $ktp_peminjam = mysqli_real_escape_string($this->koneksi, $data['ktp_peminjam']);
        $keperluan_pinjam = mysqli_real_escape_string($this->koneksi, $data['keperluan_pinjam']);
        $mulai = mysqli_real_escape_string($this->koneksi, $data['mulai']);
        $selesai = mysqli_real_escape_string($this->koneksi, $data['selesai']);
        $biaya = mysqli_real_escape_string($this->koneksi, $data['biaya']);
        $armada_id = mysqli_real_escape_string($this->koneksi, $data['armada_id']);
        $komentar_peminjam = mysqli_real_escape_string($this->koneksi, $data['komentar_peminjam']);
        $status_pinjam = mysqli_real_escape_string($this->koneksi, $data['status_pinjam']);
        $query = "INSERT INTO peminjaman (nama_peminjam, ktp_peminjam, keperluan_pinjam, mulai, selesai, biaya, armada_id, komentar_peminjam, status_pinjam)
                  VALUES ('$nama_peminjam', '$ktp_peminjam', '$keperluan_pinjam', '$mulai', '$selesai', '$biaya', '$armada_id', '$komentar_peminjam', '$status_pinjam')";
        return mysqli_query($this->koneksi, $query); // Ini kemungkinan besar line 27
    }

    public function read()
    {
        $query = "SELECT peminjaman.*, armada.merk AS merk_armada, armada.nopol AS nopol_armada
                  FROM peminjaman
                  INNER JOIN armada ON peminjaman.armada_id = armada.id";
        return mysqli_query($this->koneksi, $query);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM peminjaman WHERE id = $id";
        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_assoc($result);
    }

    public function updateData($id, $data)
    {
        $nama_peminjam = mysqli_real_escape_string($this->koneksi, $data['nama_peminjam']);
        $ktp_peminjam = mysqli_real_escape_string($this->koneksi, $data['ktp_peminjam']);
        $keperluan_pinjam = mysqli_real_escape_string($this->koneksi, $data['keperluan_pinjam']);
        $mulai = mysqli_real_escape_string($this->koneksi, $data['mulai']);
        $selesai = mysqli_real_escape_string($this->koneksi, $data['selesai']);
        $biaya = mysqli_real_escape_string($this->koneksi, $data['biaya']);
        $armada_id = mysqli_real_escape_string($this->koneksi, $data['armada_id']);
        $komentar_peminjam = mysqli_real_escape_string($this->koneksi, $data['komentar_peminjam']);
        $status_pinjam = mysqli_real_escape_string($this->koneksi, $data['status_pinjam']);
        $query = "UPDATE peminjaman SET
                  nama_peminjam = '$nama_peminjam',
                  ktp_peminjam = '$ktp_peminjam',
                  keperluan_pinjam = '$keperluan_pinjam',
                  mulai = '$mulai',
                  selesai = '$selesai',
                  biaya = '$biaya',
                  armada_id = '$armada_id',
                  komentar_peminjam = '$komentar_peminjam',
                  status_pinjam = '$status_pinjam'
                  WHERE id = $id";
        return mysqli_query($this->koneksi, $query);
    }

    public function deleteData($id)
    {
        $query = "DELETE FROM peminjaman WHERE id = $id";
        return mysqli_query($this->koneksi, $query);
    }

    public function getArmada()
    {
        $query = "SELECT * FROM armada";
        return mysqli_query($this->koneksi, $query);
    }
}

$peminjam = new Peminjam($koneksi);
$dataArmadaList = $peminjam->getArmada();

// Create and Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        // Update
        if ($peminjam->updateData($_POST['id'], $_POST)) {
            $_SESSION['message'] = 'Data peminjam berhasil diupdate';
        } else {
            $_SESSION['message'] = 'Data peminjam gagal diupdate';
        }
    } else {
        // Create
        if ($peminjam->createData($_POST)) {
            $_SESSION['message'] = 'Data peminjam berhasil ditambah';
        } else {
            $_SESSION['message'] = 'Data peminjam gagal ditambah';
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Delete
if (isset($_GET['delete'])) {
    if ($peminjam->deleteData($_GET['delete'])) {
        $_SESSION['message'] = 'Data peminjam berhasil dihapus';
    } else {
        $_SESSION['message'] = 'Data peminjam gagal dihapus';
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$dataPeminjam = $peminjam->read();
$editData = null;
if (isset($_GET['edit'])) {
    $editData = $peminjam->getById($_GET['edit']);
}

$i = 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Peminjam</title>
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
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Peminjam</h1>

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
                    <?= $editData ? 'Edit Data Peminjam' : 'Tambah Data Peminjam' ?>
                </h2>
                <form method="POST" class="space-y-4">
                    <?php if ($editData): ?>
                        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                    <?php endif; ?>
                    <div>
                        <label for="nama_peminjam" class="block text-sm font-medium text-gray-700 mb-1">Nama Peminjam</label>
                        <input type="text" id="nama_peminjam" name="nama_peminjam" required
                            value="<?= $editData ? $editData['nama_peminjam'] : '' ?>"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label for="ktp_peminjam" class="block text-sm font-medium text-gray-700 mb-1">No. KTP Peminjam</label>
                        <input type="text" id="ktp_peminjam" name="ktp_peminjam" required
                            value="<?= $editData ? $editData['ktp_peminjam'] : '' ?>"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label for="keperluan_pinjam" class="block text-sm font-medium text-gray-700 mb-1">Keperluan Pinjam</label>
                        <textarea id="keperluan_pinjam" name="keperluan_pinjam" rows="3"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"><?= $editData ? $editData['keperluan_pinjam'] : '' ?></textarea>
                    </div>
                    <div>
                        <label for="mulai" class="block text-sm font-medium text-gray-700 mb-1">Mulai</label>
                        <input type="date" id="mulai" name="mulai" required
                            value="<?= $editData ? $editData['mulai'] : '' ?>"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label for="selesai" class="block text-sm font-medium text-gray-700 mb-1">Selesai</label>
                        <input type="date" id="selesai" name="selesai" required
                            value="<?= $editData ? $editData['selesai'] : '' ?>"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label for="biaya" class="block text-sm font-medium text-gray-700 mb-1">Biaya</label>
                        <input type="number" id="biaya" name="biaya"
                            value="<?= $editData ? $editData['biaya'] : '' ?>"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label for="armada_id" class="block text-sm font-medium text-gray-700 mb-1">Armada</label>
                        <select id="armada_id" name="armada_id" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                            <option value="">Pilih Armada</option>
                            <?php while ($rowArmada = mysqli_fetch_assoc($dataArmadaList)): ?>
                                <option value="<?= $rowArmada['id'] ?>"
                                    <?= ($editData && $editData['armada_id'] == $rowArmada['id']) ? 'selected' : '' ?>>
                                    <?= $rowArmada['merk'] ?> - <?= $rowArmada['nopol'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label for="komentar_peminjam" class="block text-sm font-medium text-gray-700 mb-1">Komentar Peminjam</label>
                        <textarea id="komentar_peminjam" name="komentar_peminjam" rows="2"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"><?= $editData ? $editData['komentar_peminjam'] : '' ?></textarea>
                    </div>
                    <div>
                        <label for="status_pinjam" class="block text-sm font-medium text-gray-700 mb-1">Status Pinjam</label>
                        <select id="status_pinjam" name="status_pinjam" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                            <option value="pending" <?= ($editData && $editData['status_pinjam'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                            <option value="disetujui" <?= ($editData && $editData['status_pinjam'] == 'disetujui') ? 'selected' : '' ?>>Disetujui</option>
                            <option value="ditolak" <?= ($editData && $editData['status_pinjam'] == 'ditolak') ? 'selected' : '' ?>>Ditolak</option>
                            <option value="selesai" <?= ($editData && $editData['status_pinjam'] == 'selesai') ? 'selected' : '' ?>>Selesai</option>
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
                    <h2 class="text-lg font-semibold text-gray-800">Daftar Peminjam</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peminjam</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. KTP</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keperluan</th>
                                <th scope="col" class="px-6 py-3 text-left textt-xs font-medium text-gray-500 uppercase tracking-wider">Mulai</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selesai</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Biaya</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Armada</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Komentar</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = mysqli_fetch_assoc($dataPeminjam)): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $i++; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['nama_peminjam'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['ktp_peminjam'] ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500"><?= $row['keperluan_pinjam'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['mulai'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['selesai'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['biaya'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['merk_armada'] ?> - <?= $row['nopol_armada'] ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500"><?= $row['komentar_peminjam'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php
                                        $status = $row['status_pinjam'];
                                        $badgeClass = '';
                                        if ($status == 'pending') {
                                            $badgeClass = 'bg-yellow-100 text-yellow-800';
                                        } elseif ($status == 'disetujui') {
                                            $badgeClass = 'bg-green-100 text-green-800';
                                        } elseif ($status == 'ditolak') {
                                            $badgeClass = 'bg-red-100 text-red-800';
                                        } elseif ($status == 'selesai') {
                                            $badgeClass = 'bg-blue-100 text-blue-800';
                                        }
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $badgeClass ?>">
                                            <?= ucfirst($status) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        <div class="flex space-x-3 justify-end">
                                            <a href="?edit=<?= $row['id'] ?>"
                                                class="action-btn text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md text-sm font-medium">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                            <a href="?delete=<?= $row['id'] ?>"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data peminjam ini?')"
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