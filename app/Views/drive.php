<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <!-- Breadcrumbs -->
    <div class="row mb-3">
        <div class="col">
            <?php if (!empty($breadcrumbs)): ?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('drive') ?>">Root</a></li>
                        <?php foreach ($breadcrumbs as $idx => $b): ?>
                            <li class="breadcrumb-item <?= $idx === array_key_last($breadcrumbs) ? 'active' : '' ?>">
                                <?php if ($idx === array_key_last($breadcrumbs)): ?>
                                    <?= esc($b['name']) ?>
                                <?php else: ?>
                                    <a href="<?= base_url('drive/f/' . $b['id']) ?>"><?= esc($b['name']) ?></a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </nav>
            <?php else: ?>
                <div class="text-muted">/ Root</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Search Bar (opsional, implementasi backend menyusul) -->
    <div class="row justify-content-center mb-3">
        <div class="col-md-8">
            <form class="d-flex" action="<?= base_url('drive') ?>" method="get">
                <input class="form-control form-control-lg me-2 rounded-pill px-4 shadow-sm" type="search" name="q"
                    value="<?= esc($q ?? '') ?>" placeholder="Cari file atau folder..." aria-label="Search">
            </form>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col d-flex justify-content-center gap-2">

            <!-- Buat Folder -->
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                <i class="bi bi-folder-plus"></i> Buat Folder
            </button>

            <!-- Upload File -->
            <form action="<?= base_url('drive/upload') ?>" method="post" enctype="multipart/form-data">
                <?php // <?= csrf_field() ?>
                <input type="hidden" name="folder_id" value="<?= esc($currentFolder['id'] ?? '') ?>">
                <input type="file" name="file" id="uploadFile" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.xls,.xlsx,.csv"
                    hidden onchange="this.form.submit()">
                <button type="button" class="btn btn-primary" onclick="document.getElementById('uploadFile').click()">
                    <i class="bi bi-upload"></i> Upload File
                </button>
            </form>

        </div>
    </div>

    <!-- Folder & File List -->
    <div class="row">
        <?php if (empty($folders) && empty($files)): ?>
            <!-- Empty state -->
            <div class="col-12 text-center my-5 empty-state">
                <img src="<?= base_url('assets/img/undraw.svg') ?>" 
                     alt="Belum ada file atau folder" 
                     width="300">
                <h5 class="mt-3 text-muted">
                    Klik <b>Buat Folder</b> atau <b>Upload File</b> untuk menambahkan
                </h5>
            </div>

        <?php else: ?>

            <!-- Loop Folder -->
            <?php foreach ($folders as $folder): ?>
                <div class="col-md-2 col-6 text-center mb-4">
                    <a href="<?= base_url('drive/f/' . $folder['id']) ?>" class="text-decoration-none text-dark d-block">
                        <i class="bi bi-folder-fill text-warning" style="font-size:40px;"></i>
                        <p class="mt-2 small text-truncate" title="<?= esc($folder['name']) ?>">
                            <?= esc($folder['name']) ?>
                        </p>
                    </a>
                </div>
            <?php endforeach; ?>

            <!-- Loop File -->
            <?php foreach ($files as $file): ?>
                <?php
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $icon = "bi bi-file-earmark-fill text-primary";
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    $icon = "bi bi-file-earmark-image text-danger";
                if ($ext === 'pdf')
                    $icon = "bi bi-file-earmark-pdf text-danger";
                if (in_array($ext, ['doc', 'docx']))
                    $icon = "bi bi-file-earmark-word text-info";
                if (in_array($ext, ['xls', 'xlsx', 'csv']))
                    $icon = "bi bi-file-earmark-excel text-success";
                if (in_array($ext, ['ppt', 'pptx']))
                    $icon = "bi bi-file-earmark-ppt text-warning";
                if (in_array($ext, ['zip', 'rar']))
                    $icon = "bi bi-file-earmark-zip text-secondary";
                ?>
                <div class="col-md-2 col-6 text-center mb-4">
                    <i class="<?= $icon ?>" style="font-size:40px;"></i>
                    <p class="mt-2 small text-truncate" title="<?= esc($file['name']) ?>">
                        <?= esc($file['name']) ?>
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <a class="btn btn-sm btn-outline-secondary" href="<?= base_url('drive/download/' . $file['id']) ?>">
                            <i class="bi bi-download"></i>
                        </a>
                        <!-- (Opsional) tombol hapus file via JS fetch DELETE -->
                        <!--
                        <button class="btn btn-sm btn-outline-danger" data-del-file="<?= $file['id'] ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                        -->
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>

<!-- Modal Buat Folder -->
<div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('drive/folder') ?>" method="post">
            <?php // <?= csrf_field() ?>
            <input type="hidden" name="parent_id" value="<?= esc($currentFolder['id'] ?? '') ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFolderModalLabel">Buat Folder Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="name" placeholder="Nama Folder" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Buat</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Hapus file
    document.querySelectorAll('[data-del-file]').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Hapus file ini?')) return;
            const id = btn.getAttribute('data-del-file');
            const res = await fetch('<?= base_url('drive/file') ?>/' + id, {
                method: 'DELETE'
            });
            if (res.ok) location.reload();
            else alert('Gagal menghapus file');
        });
    });

    // Hapus folder (tambahkan tombol di listing folder bila perlu)
    // document.querySelectorAll('[data-del-folder]').forEach(btn=>{ ... })
</script>
<?= $this->endSection() ?>