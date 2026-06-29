<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Manajemen Aset IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Asset IT Management</a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">Halo, {{ auth()->user()->name }} (<strong>{{ ucfirst(auth()->user()->role) }}</strong>)</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row mb-3">
        <div class="col-md-8">
            <input type="text" id="search" class="form-control" placeholder="Cari berdasarkan nama aset atau nomor serial...">
        </div>
        <div class="col-md-4 text-end">
            @if(auth()->user()->role == 'admin')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assetModal" onclick="resetForm()">Tambah Aset Baru</button>
            @endif
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Aset</th>
                        <th>Serial Number</th>
                        <th>Kategori (Master)</th>
                        <th>Lokasi (Master)</th>
                        <th>Status</th>
                        @if(auth()->user()->role == 'admin') <th>Aksi</th> @endif
                    </tr>
                </thead>
                <tbody id="assetTableBody">
                    </tbody>
            </table>
        </div>
    </div>
</div>

@if(auth()->user()->role == 'admin')
<div class="modal fade" id="assetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Aset IT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="assetForm">
                    <input type="hidden" id="asset_id">
                    <div class="mb-3">
                        <label class="form-label">Nama Aset</label>
                        <input type="text" id="nama_aset" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Serial Number</label>
                        <input type="text" id="serial_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori (Master Data)</label>
                        <select id="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi (Master Data)</label>
                        <select id="location_id" class="form-select" required>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->nama_lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Kondisi</label>
                        <select id="status" class="form-select">
                            <option value="Bagus">Bagus</option>
                            <option value="Rusak">Rusak</option>
                            <option value="Perbaikan">Perbaikan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const userRole = "{{ auth()->user()->role }}";
    const csrfToken = "{{ csrf_token() }}";

    // 1. AJAX FETCH & LIVE SEARCH (DOM MANIPULATION)
    function loadAssets(keyword = '') {
        fetch(`/assets/fetch?search=${keyword}`)
            .then(res => res.json())
            .then(data => {
                let rows = '';
                if(data.length === 0) {
                    rows = `<tr><td colspan="6" class="text-center text-muted">Tidak ada data aset ditemukan.</td></tr>`;
                } else {
                    data.forEach(asset => {
                        rows += `<tr>
                            <td>${asset.nama_aset}</td>
                            <td><code>${asset.serial_number}</code></td>
                            <td><span class="badge bg-secondary">${asset.category.nama_kategori}</span></td>
                            <td>${asset.location.nama_lokasi}</td>
                            <td><span class="badge ${asset.status === 'Bagus' ? 'bg-success' : (asset.status === 'Rusak' ? 'bg-danger' : 'bg-warning')}">${asset.status}</span></td>
                            ${userRole === 'admin' ? `
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editAsset(${asset.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteAsset(${asset.id})">Hapus</button>
                            </td>` : ''}
                        </tr>`;
                    });
                }
                document.getElementById('assetTableBody').innerHTML = rows;
            });
    }

    // Trigger Live Search saat mengetik
    document.getElementById('search').addEventListener('input', function() {
        loadAssets(this.value);
    });

    // Jalankan load data saat halaman dibuka pertama kali
    loadAssets();

    // Reset Form Modal
    function resetForm() {
        document.getElementById('assetForm').reset();
        document.getElementById('asset_id').value = '';
        document.getElementById('modalTitle').innerText = 'Tambah Aset IT';
    }

    // 2. AJAX ASYNCHRONOUS CREATE & UPDATE
    if(userRole === 'admin') {
        document.getElementById('assetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('asset_id').value;
            const url = id ? `/assets/${id}` : '/assets';
            const method = id ? 'PUT' : 'POST';

            const payload = {
                nama_aset: document.getElementById('nama_aset').value,
                serial_number: document.getElementById('serial_number').value,
                category_id: document.getElementById('category_id').value,
                location_id: document.getElementById('location_id').value,
                status: document.getElementById('status').value,
            };

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(res => {
                alert(res.message);
                bootstrap.Modal.getInstance(document.getElementById('assetModal')).hide();
                loadAssets(); // Memperbarui tabel tanpa reload halaman (Asynchronous)
            })
            .catch(() => alert('Terjadi kesalahan! Periksa kembali inputan Anda (Nomor Serial tidak boleh kembar).'));
        });
    }

    // AJAX GET DATA UNTUK EDIT
    function editAsset(id) {
        fetch(`/assets/${id}/edit`)
            .then(res => res.json())
            .then(asset => {
                document.getElementById('asset_id').value = asset.id;
                document.getElementById('nama_aset').value = asset.nama_aset;
                document.getElementById('serial_number').value = asset.serial_number;
                document.getElementById('category_id').value = asset.category_id;
                document.getElementById('location_id').value = asset.location_id;
                document.getElementById('status').value = asset.status;
                
                document.getElementById('modalTitle').innerText = 'Edit Data Aset';
                new bootstrap.Modal(document.getElementById('assetModal')).show();
            });
    }

    // 3. AJAX ASYNCHRONOUS DELETE
    function deleteAsset(id) {
        if(confirm('Apakah Anda yakin ingin menghapus aset ini?')) {
            fetch(`/assets/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            })
            .then(res => res.json())
            .then(res => {
                alert(res.message);
                loadAssets(); // Memperbarui tabel tanpa reload halaman (Asynchronous)
            });
        }
    }
</script>
</body>
</html>