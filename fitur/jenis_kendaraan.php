<?php
include '../config/koneksi.php';
session_start();


class jenisKendaraan
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function createData($data)
    {
        $nama = mysqli_real_escape_string($this->koneksi, $data['nama']);
        $query = "INSERT INTO jenis_kendaraan(nama) values ('$nama')";
        return mysqli_query($this->koneksi, $query);
    }

    public function read()
    {
        $query = "SELECT * FROM jenis_kendaraan";
        return mysqli_query($this->koneksi, $query);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM jenis_kendaraan WHERE id = $id";
        $result = mysqli_query($this->koneksi, $query);
        return mysqli_fetch_assoc($result);
    }

    public function updateData($id, $data)
    {
        $nama = mysqli_real_escape_string($this->koneksi, $data['nama']);
        $query = "UPDATE jenis_kendaraan SET nama = '$nama' WHERE id = $id";
        return mysqli_query($this->koneksi, $query);
    }

    public function deleteData($id)
    {
        $query = "DELETE FROM jenis_kendaraan WHERE id = $id";
        return mysqli_query($this->koneksi, $query);
    }
}

$kendaraan = new jenisKendaraan($koneksi);

// Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama'])) {
    if (isset($_POST['id'])) {
        // Update
        if ($kendaraan->updateData($_POST['id'], $_POST)) {
            $_SESSION['message'] = 'Data berhasil diupdate';
        } else {
            $_SESSION['message'] = 'Data gagal diupdate';
        }
    } else {
        // Create
        if ($kendaraan->createData($_POST)) {
            $_SESSION['message'] = 'Data berhasil ditambah';
        } else {
            $_SESSION['message'] = 'Data gagal ditambah';
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Delete
if (isset($_GET['delete'])) {
    if ($kendaraan->deleteData($_GET['delete'])) {
        $_SESSION['message'] = 'Data berhasil dihapus';
    } else {
        $_SESSION['message'] = 'Data gagal dihapus';
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$jenis = $kendaraan->read();
$editData = null;
if (isset($_GET['edit'])) {
    $editData = $kendaraan->getById($_GET['edit']);
}

$i = 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jenis Kendaraan</title>
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
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Jenis Kendaraan</h1>

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

            <!-- Form Section -->
            <div class="bg-blue-50 rounded-lg p-6 mb-8 border border-blue-100">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    <?= $editData ? 'Edit Jenis Kendaraan' : 'Tambah Jenis Kendaraan' ?>
                </h2>
                <form method="POST" class="space-y-4">
                    <?php if ($editData): ?>
                        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                    <?php endif; ?>
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Jenis Kendaraan</label>
                        <input type="text" id="nama" name="nama" required
                            value="<?= $editData ? $editData['nama'] : '' ?>"
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

            <!-- Table Section -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Daftar Jenis Kendaraan</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Jenis</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = mysqli_fetch_assoc($jenis)): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $i++; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['nama'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        <div class="flex space-x-3 justify-end">
                                            <a href="?edit=<?= $row['id'] ?>"
                                                class="action-btn text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md text-sm font-medium">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                            <a href="?delete=<?= $row['id'] ?>"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
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