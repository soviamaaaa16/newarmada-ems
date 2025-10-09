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
            <button class="btn btn-light border rounded-pill px-3 py-2 shadow-sm hover-elevate" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                <i class="bi bi-folder-plus me-2"></i> Buat Folder
            </button>

            <!-- Upload File -->
            <form action="<?= base_url('drive/upload') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="folder_id" value="<?= esc($currentFolder['id'] ?? '') ?>">
                <input type="file" name="file" id="uploadFile" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.xls,.xlsx,.csv"
                    hidden onchange="this.form.submit()">
                <button type="button" class="btn btn-light border rounded-pill px-3 py-2 shadow-sm hover-elevate" onclick="document.getElementById('uploadFile').click()">
                    <i class="bi bi-upload me-2"></i> Upload File
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
            <div class="row">
                <?php foreach ($folders as $folder): ?>
                    <div class="col-md-2 col-sm-6 mb-4">
                        <div class="card file-card shadow-sm border-0 h-100">
                            <a href="<?= base_url('drive/f/' . $folder['id']) ?>" class="text-decoration-none text-dark d-block">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-folder-fill text-warning" style="font-size:25px;"></i>
                                    <span class="ms-3 text-truncate" title="<?= esc($folder['name']) ?>">
                                        <?= esc($folder['name']) ?>
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Loop File -->
            <?php foreach ($files as $file): ?>
                <?php
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $icon = "bi bi-file-earmark-fill text-primary";
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    $icon = "bi bi-image text-danger";
                if ($ext === 'pdf')
                    $icon = "bi bi-filetype-pdf text-danger";
                if (in_array($ext, ['doc', 'docx']))
                    $icon = "bi bi-filetype-docx text-info";
                if (in_array($ext, ['xls', 'xlsx', 'csv']))
                    $icon = "bi bi-filetype-xlsx text-success";
                if (in_array($ext, ['ppt', 'pptx']))
                    $icon = "bi bi-file-earmark-ppt text-warning";
                if (in_array($ext, ['zip', 'rar']))
                    $icon = "bi bi-file-earmark-zip text-secondary";

                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                $isPDF = ($ext === 'pdf');
                $fileUrl = base_url('drive/download/' . $file['id']);
                $previewUrl = base_url('drive/preview/' . $file['id']);
                ?>
                    
                <!-- <div class="col-md-2 col-6 text-center mb-4">
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
                    <!-- </div>
                </div>  -->

                <div class="col-md-2 col-sm-4 col-6 mb-4">
                    <div class="card file-card border-0 shadow-sm h-100" data-preview-url="<?= $fileUrl ?>" data-filename="<?= esc($file['name']) ?>">
                        <div class="file-preview bg-light d-flex align-items-center justify-content-center style=height:150px; overflow:hidden;">
                            <?php if ($isImage): ?>
                                <img src="<?= $fileUrl ?>" 
                                     alt="<?= esc($file['name']) ?>" 
                                     class="img-fluid rounded-top" 
                                     style="max-height: 150px; object-fit: cover;">
                            <?php elseif ($isPDF): ?>
                                <canvas id="pdf-preview-<?= $file['id'] ?>" style="max-width:95%; border-radius:8px;"></canvas>
                                <script>
                                    pdfjsLib.GlobalWorkerOptions.workerSrc = "<?= base_url('assets/pdfjs/build/pdf.worker.min.js') ?>";
                                    const url<?= $file['id'] ?> = "<?= $fileUrl ?>";
                                    const canvas<?= $file['id'] ?> = document.getElementById("pdf-preview-<?= $file['id'] ?>");
                                    const ctx<?= $file['id'] ?> = canvas<?= $file['id'] ?>.getContext("2d");

                                    pdfjsLib.getDocument(url<?= $file['id'] ?>).promise.then(pdf => {
                                        pdf.getPage(1).then(page => {
                                            const viewport = page.getViewport({ scale: 0.25 });
                                            canvas<?= $file['id'] ?>.height = viewport.height;
                                            canvas<?= $file['id'] ?>.width = viewport.width;
                                            page.render({ canvasContext: ctx<?= $file['id'] ?>, viewport: viewport });
                                        }).catch(err => {
                                            ctx<?= $file['id'] ?>.font = "12px sans-serif";
                                            ctx<?= $file['id'] ?>.fillText("Preview gagal dimuat", 10, 50);
                                        });
                                    }).catch(() => {
                                        ctx<?= $file['id'] ?>.font = "12px sans-serif";
                                        ctx<?= $file['id'] ?>.fillText("File tidak dapat dibaca", 10, 50);
                                    });
                                </script>
                            <?php else: ?>
                                <i class="<?= $icon ?>" style="font-size:48px;"></i>
                            <?php endif; ?>
                        </div>  

                        <!-- <div class="card-body p-2 text-center">
                            <p class="small mb-1 d-flex align-items-center justify-content gap-1">
                                <i class="<?= $icon ?>" style="font-size: 1rem;"></i>
                                <span class="text-truncate d-inline-block ms-1" style="max-width: 80%;"><?= esc($file['name']) ?></span>
                            </p>
                        </div> -->

                        <div class="d-flex align-items-center justify-content-between px-2">
                            <div class="d-flex align-items-center flex-grow-1 overflow-hidden">
                                <i class="<?= $icon ?> text-danger fs-5 me-2 sm-2"></i>
                                <span class="text-truncate" style="max-width: 80%;">
                                    <?= esc($file['name']) ?>
                                </span>
                            </div>

                            <div class="dropdown ms-2">
                                <button class="btn btn-sm border-0 text-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="<?= base_url('drive/download/' . $file['id']) ?>">
                                            <i class="bi bi-download me-2"></i> Download
                                        </a>
                                    </li>
                                    <li>
                                        <button class="dropdown-item btn-rename" data-id="<?= $file['id'] ?> " 
                                        data-name="<?= esc($file['name']) ?> ">
                                            <i class="bi bi-pencil-square me-2"></i> Rename
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item btn-delete" data-del-file="<?= $file['id'] ?>">
                                            <i class="bi bi-trash me-2"></i> Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
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

<!-- Modal Rename -->
<div class="modal fade" id="renameModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Ganti Nama</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="renameFileId">
            <div class="mb-3">
                <input type="text" id="renameFileName" class="form-control" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" id="btnSaveRename">OK</button>
        </div>
    </div>
  </div>
</div>

<div id="lightboxOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-flex align-items-center justify-content-center" style="z-index: 1050;">
    <div id="lightboxContent" class="position-relative bg-white rounded shadow p-2" style="max-width: 90%; max-height: 90%;">
        <button id="lightboxClose" class="btn btn-sm btn-light position-absolute top-0 end-0 m-2">
            <i class="bi bi-x-lg"></i>
        </button>
        <div id="lightboxBody" class="text-center"></div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    $(document).ready(function () {
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
    
        $(document).on('click', '.btn-rename', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
        
            $('#renameFileId').val(id);
            $('#renameFileName').val(name);
            $('#renameModal').modal('show');
        });
    });
</script>
<?= $this->endSection() ?>