<?php
include '../config/koneksi.php';
session_start();

class Armada
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function createData($data)
    {
        $merk = mysqli_real_escape_string($this->koneksi, $data['merk']);
        $nopol = mysqli_real_escape_string($this->koneksi, $data['nopol']);
        $thn_beli = mysqli_real_escape_string($this->koneksi, $data['thn_beli']);
        $deskripsi = mysqli_real_escape_string($this->koneksi, $data['deskripsi']);
        $jenis_kendaraan_id = mysqli_real_escape_string($this->koneksi, $data['jenis_kendaraan_id']);
        $kapasitas_kursi = mysqli_real_escape_string($this->koneksi, $data['kapasitas_kursi']);
        $rating = mysqli_real_escape_string($this->koneksi, $data['rating']);
        $query = "INSERT INTO armada (merk, nopol, thn_beli, deskripsi, jenis_kendaraan_id, kapasitas_kursi, rating)
                  VALUES ('$merk', '$nopol', '$thn_beli', '$deskripsi', '$jenis_kendaraan_id', '$kapasitas_kursi', '$rating')";
        return mysqli_query($this->koneksi, $query);
    }

    public function read()
    {
        $query = "SELECT armada.*, jenis_kendaraan.nama AS nama_jenis
                  FROM armada
                  INNER JOIN jenis_kendaraan ON armada.jenis_kendaraan_id = jenis_kendaraan.id";
        return mysqli_query($this->koneksi, $query);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM armada WHERE id = $id";
        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_assoc($result);
    }

    public function updateData($id, $data)
    {
        $merk = mysqli_real_escape_string($this->koneksi, $data['merk']);
        $nopol = mysqli_real_escape_string($this->koneksi, $data['nopol']);
        $thn_beli = mysqli_real_escape_string($this->koneksi, $data['thn_beli']);
        $deskripsi = mysqli_real_escape_string($this->koneksi, $data['deskripsi']);
        $jenis_kendaraan_id = mysqli_real_escape_string($this->koneksi, $data['jenis_kendaraan_id']);
        $kapasitas_kursi = mysqli_real_escape_string($this->koneksi, $data['kapasitas_kursi']);
        $rating = mysqli_real_escape_string($this->koneksi, $data['rating']);
        $query = "UPDATE armada SET
                  merk = '$merk',
                  nopol = '$nopol',
                  thn_beli = '$thn_beli',
                  deskripsi = '$deskripsi',
                  jenis_kendaraan_id = '$jenis_kendaraan_id',
                  kapasitas_kursi = '$kapasitas_kursi',
                  rating = '$rating'
                  WHERE id = $id";
        return mysqli_query($this->koneksi, $query);
    }

    public function deleteData($id)
    {
        $query = "DELETE FROM armada WHERE id = $id";
        return mysqli_query($this->koneksi, $query);
    }

    public function getJenisKendaraan()
    {
        $query = "SELECT * FROM jenis_kendaraan";
        return mysqli_query($this->koneksi, $query);
    }
}

$armada = new Armada($koneksi);
$jenisKendaraanList = $armada->getJenisKendaraan();

// Create and Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        // Update
        if ($armada->updateData($_POST['id'], $_POST)) {
            $_SESSION['message'] = 'Data armada berhasil diupdate';
        } else {
            $_SESSION['message'] = 'Data armada gagal diupdate';
        }
    } else {
        // Create
        if ($armada->createData($_POST)) {
            $_SESSION['message'] = 'Data armada berhasil ditambah';
        } else {
            $_SESSION['message'] = 'Data armada gagal ditambah';
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Delete
if (isset($_GET['delete'])) {
    if ($armada->deleteData($_GET['delete'])) {
        $_SESSION['message'] = 'Data armada berhasil dihapus';
    } else {
        $_SESSION['message'] = 'Data armada gagal dihapus';
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$dataArmada = $armada->read();
$editData = null;
if (isset($_GET['edit'])) {
    $editData = $armada->getById($_GET['edit']);
}

$i = 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Armada</title>
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
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Armada</h1>

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
                    <?= $editData ? 'Edit Data Armada' : 'Tambah Data Armada' ?>
                </h2>
                <form method="POST" class="space-y-4">
                    <?php if ($editData): ?>
                        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                    <?php endif; ?>
                    <div>
                        <label for="merk" class="block text-sm font-medium text-gray-700 mb-1">Merk</label>
                        <input type="text" id="merk" name="merk" required
                            value="<?= $editData ? $editData['merk'] : '' ?>"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label for="nopol" class="block text-sm font-medium text-gray-700 mb-1">Nomor Polisi</label>
                        <input type="text" id="nopol" name="nopol" required
                            value="<?= $editData ? $editData['nopol'] : '' ?>"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label for="thn_beli" class="block text-sm font-medium text-gray-700 mb-1">Tahun Pembelian</label>
                        <input type="number" id="thn_beli" name="thn_beli" required
                            value="<?= $editData ? $editData['thn_beli'] : '' ?>"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"><?= $editData ? $editData['deskripsi'] : '' ?></textarea>
                    </div>
                    <div>
                        <label for="jenis_kendaraan_id" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kendaraan</label>
                        <select id="jenis_kendaraan_id" name="jenis_kendaraan_id" required
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                            <option value="">Pilih Jenis Kendaraan</option>
                            <?php while ($rowJenis = mysqli_fetch_assoc($jenisKendaraanList)): ?>
                                <option value="<?= $rowJenis['id'] ?>"
                                    <?= ($editData && $editData['jenis_kendaraan_id'] == $rowJenis['id']) ? 'selected' : '' ?>>
                                    <?= $rowJenis['nama'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label for="kapasitas_kursi" class="block text-sm font-medium text-gray-700 mb-1">Kapasitas Kursi</label>
                        <input type="number" id="kapasitas_kursi" name="kapasitas_kursi" required
                            value="<?= $editData ? $editData['kapasitas_kursi'] : '' ?>"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                        <input type="number" id="rating" name="rating" min="1" max="5"
                            value="<?= $editData ? $editData['rating'] : '' ?>"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
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
                    <h2 class="text-lg font-semibold text-gray-800">Daftar Armada</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merk</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nopol</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Beli</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kendaraan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = mysqli_fetch_assoc($dataArmada)): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $i++; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['merk'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['nopol'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['thn_beli'] ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500"><?= $row['deskripsi'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['nama_jenis'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['kapasitas_kursi'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['rating'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        <div class="flex space-x-3 justify-end">
                                            <a href="?edit=<?= $row['id'] ?>"
                                                class="action-btn text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md text-sm font-medium">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                            <a href="?delete=<?= $row['id'] ?>"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus dataini?')"
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