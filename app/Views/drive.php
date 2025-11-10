<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<style>
    .preview-container {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        z-index: 1050;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .preview-container.active {
        opacity: 1;
        visibility: visible;
    }

    .preview-header {
        background: white;
        border-bottom: 1px solid #e0e0e0;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .preview-header h5 {
        margin: 0;
        font-weight: 500;
        font-size: 16px;
        color: #202124;
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        margin-right: 16px;
    }

    .preview-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .preview-actions .btn {
        border: none;
        background: none;
        color: #5f6368;
        cursor: pointer;
        padding: 8px 12px;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: background 0.2s;
        text-decoration: none;
    }

    .preview-actions .btn:hover {
        background: #f1f3f4;
        color: #202124;
    }

    .preview-content {
        flex: 1;
        overflow: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fafafa;
        position: relative;
    }

    .preview-image {
        max-width: 90%;
        max-height: 85vh;
        object-fit: contain;
        animation: slideIn 0.3s ease;
    }

    .pdf-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        padding: 20px;
    }

    .pdf-page {
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .pdf-canvas {
        display: block;
        max-width: 100%;
    }

    .preview-footer {
        background: white;
        border-top: 1px solid #e0e0e0;
        padding: 12px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
        color: #5f6368;
        flex-wrap: wrap;
        gap: 12px;
    }

    .page-nav {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .page-nav input {
        width: 50px;
        padding: 6px 8px;
        border: 1px solid #dadce0;
        border-radius: 4px;
        text-align: center;
        font-size: 14px;
    }

    .page-nav button {
        background: none;
        border: none;
        color: #1f73e6;
        cursor: pointer;
        padding: 6px 8px;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: background 0.2s;
    }

    .page-nav button:hover {
        background: #f1f3f4;
        color: #1558b0;
    }

    .page-nav button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .zoom-controls {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .zoom-controls button {
        background: none;
        border: none;
        color: #1f73e6;
        cursor: pointer;
        padding: 6px 8px;
        font-size: 16px;
        border-radius: 4px;
        transition: background 0.2s;
    }

    .zoom-controls button:hover {
        background: #f1f3f4;
    }

    .zoom-controls button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .zoom-controls span {
        font-size: 14px;
        min-width: 45px;
        text-align: center;
        color: #5f6368;
    }

    .loading-spinner {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @media (max-width: 992px) {
        .preview-image {
            max-width: 95%;
            max-height: 80vh;
        }

        .preview-content {
            padding: 10px;
        }
    }

    @media (max-width: 768px) {
        .preview-header {
            padding: 12px 16px;
            flex-wrap: wrap;
        }

        .preview-header h5 {
            font-size: 14px;
            flex: 1 1 auto;
            min-width: 0;
        }

        .preview-actions {
            gap: 8px;
            flex: 1 1 auto;
            justify-content: flex-end;
        }

        .preview-actions .btn {
            padding: 6px 8px;
            font-size: 16px;
        }

        .preview-image {
            max-width: 100%;
            max-height: 75vh;
        }

        .preview-footer {
            padding: 12px 16px;
            justify-content: center;
            gap: 16px;
        }

        .page-nav {
            width: 100%;
            justify-content: center;
            order: 1;
        }

        .page-nav input {
            width: 45px;
            padding: 5px 6px;
            font-size: 13px;
        }

        .zoom-controls {
            width: 100%;
            justify-content: center;
            order: 2;
        }

        .zoom-controls button {
            padding: 5px 8px;
            font-size: 15px;
        }

        .zoom-controls span {
            font-size: 13px;
            min-width: 40px;
        }

        .pdf-container {
            padding: 10px;
        }

        .pdf-canvas {
            max-width: 100% !important;
        }
    }

    @media (max-width: 480px) {
        .preview-header {
            padding: 10px 12px;
        }

        .preview-header h5 {
            font-size: 13px;
        }

        .preview-actions .btn {
            padding: 5px 6px;
            font-size: 14px;
        }

        .preview-image {
            max-width: 100%;
            max-height: 70vh;
        }

        .preview-footer {
            padding: 10px 12px;
            font-size: 12px;
            gap: 12px;
        }

        .page-nav input {
            width: 40px;
            padding: 4px 5px;
            font-size: 12px;
        }

        .page-nav span {
            font-size: 12px;
        }

        .page-nav button {
            padding: 4px 6px;
            font-size: 14px;
        }

        .zoom-controls button {
            padding: 4px 6px;
            font-size: 14px;
        }

        .zoom-controls span {
            font-size: 12px;
            min-width: 35px;
        }

        .pdf-page {
            margin-bottom: 12px;
        }

        .pdf-container {
            padding: 8px;
        }

        .loading-spinner span {
            font-size: 13px;
        }

    }

    .dropdown-menu.dropup-menu {
        position: absolute !important;
        bottom: 100% !important;
        top: auto !important;
        right: 0;
        margin-bottom: 8px;
        margin-top: 0;
        transform-origin: bottom;
    }

    .dropdown-menu.show {
        z-index: 1070 !important;
        position: absolute !important;
        bottom: 100% !important;
        top: auto !important;
        margin-bottom: 8px !important;
    }

    .card:has(.dropdown-menu.show) {
        z-index: 1060;
        position: relative;
    }

    .file-item:has(.dropdown-menu.show),
    .item-card:has(.dropdown-menu.show) {
        z-index: 1060;
        position: relative;
    }
</style>

<div class="container-fluid py-4">
    <!-- Breadcrumbs -->
    <div class="row mb-3">
        <div class="col">
            <?php if (!empty($breadcrumbs)): ?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Root</a></li>
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
            <input id="searchInput" class="form-control form-control-lg me-2 rounded-pill px-4 shadow-sm" type="search"
                name="searchInput" placeholder="Cari file atau folder..." aria-label="Search">
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4 position-relative">
        <div class="col d-flex justify-content-center align-items-center gap-2 position-relative">

            <!-- Buat Folder -->
            <button class="btn btn-light border rounded-pill px-3 py-2 shadow-sm hover-elevate" data-bs-toggle="modal"
                data-bs-target="#createFolderModal">
                <i class="bi bi-folder-plus me-2"></i> Buat Folder
            </button>

            <!-- Upload File -->
            <form action="<?= base_url('drive/upload') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="folder_id" value="<?= esc($currentFolder['id'] ?? '') ?>">
                <input type="file" name="file" id="uploadFile" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.xls,.xlsx,.csv"
                    hidden onchange="this.form.submit()">
                <button type="button" class="btn btn-light border rounded-pill px-3 py-2 shadow-sm hover-elevate"
                    onclick="document.getElementById('uploadFile').click()">
                    <i class="bi bi-upload me-2"></i> Upload File
                </button>
            </form>

            <div class="btn-group position-absolute end-0" role="group" aria-label="View toggle">
                <button type="button" id="listViewBtn" class="btn btn-outline-secondary">
                    <i class="bi bi-list"></i>
                </button>
                <button type="button" id="gridViewBtn" class="btn btn-outline-secondary">
                    <i class="bi bi-grid"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Folder & File List -->
    <div id="gridView" class="row">
        <?php if (empty($folders) && empty($files)): ?>
            <!-- Empty state -->
            <div class="col-12 text-center my-5 empty-state">
                <img src="<?= base_url('assets/img/undraw.svg') ?>" alt="Belum ada file atau folder" width="300">
                <h5 class="mt-3 text-muted">
                    Klik <b>Buat Folder</b> atau <b>Upload File</b> untuk menambahkan
                </h5>
            </div>
        <?php else: ?>
            <!-- Loop Folder -->
            <div class="row" id="container-search">
                <?php foreach ($folders as $folder): ?>
                    <div class="col-md-2 col-sm-6 mb-4">
                        <div class="card file-card shadow-sm border-0 h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <!-- Folder Link -->
                                <a href="<?= base_url('drive/f/' . $folder['id']) ?>"
                                    class="text-decoration-none text-dark d-flex align-items-center flex-grow-1 min-w-0">
                                    <i class="bi bi-folder-fill text-warning" style="font-size: 25px;"></i>
                                    <span class="ms-3 text-truncate" title="<?= esc($folder['name']) ?>">
                                        <?= esc($folder['name']) ?>
                                    </span>
                                </a>

                                <!-- Dropdown Menu -->
                                <div class="dropdown ms-2">
                                    <button class="btn btn-sm border-0 text-secondary" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false" title="Options">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropup-menu">
                                        <li>
                                            <button class="dropdown-item" onclick="renameHandler.call(this)"
                                                data-id="<?= $folder['id'] ?>" data-type="folder"
                                                data-name="<?= esc($folder['name']) ?>">
                                                <i class="bi bi-pencil"></i> Rename
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item btn-delete"
                                                data-del-folder="<?= esc($folder['id']) ?>">
                                                <i class="bi bi-trash me-2"></i> Delete
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
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
                    <div class="card file-card border-0 shadow-sm h-100" data-preview-url="<?= $fileUrl ?>"
                        data-filename="<?= esc($file['name']) ?>">
                        <div class="file-preview bg-light d-flex align-items-center justify-content-center style=height:150px; overflow:hidden;"
                            onclick="previewFile('<?= $fileUrl ?>', '<?= esc($file['name']) ?>')">
                            <?php if ($isImage): ?>
                                <img src="<?= $fileUrl ?>" alt="<?= esc($file['name']) ?>" class="img-fluid rounded-top"
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
                                <button class="btn btn-sm border-0 text-secondary" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropup-menu">
                                    <li>
                                        <a class="dropdown-item" href="<?= base_url('drive/download/' . $file['id']) ?>">
                                            <i class="bi bi-download me-2"></i> Download
                                        </a>
                                    </li>
                                    <li>
                                        <button class="dropdown-item" onclick="renameHandler.call(this)"
                                            data-id="<?= $file['id'] ?>" data-type="file" data-name="<?= esc($file['name']) ?>">
                                            <i class="bi bi-pencil"></i> Rename
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

    <div id="listView" class="d-none">
        <?php if (empty($folders) && empty($files)): ?>
            <div class="col-12 text-center my-5 empty-state">
                <img src="<?= base_url('assets/img/undraw.svg') ?>" alt="Belum ada file atau folder" width="300">
                <h5 class="mt-3 text-muted">
                    Klik <b>Buat Folder</b> atau <b>Upload File</b> untuk menambahkan
                </h5>
            </div>
        <?php else: ?>
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Pemilik</th>
                        <th>Diperbarui</th>
                        <th>Ukuran</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    <!-- Loop Folder -->
                    <?php foreach ($folders as $folder): ?>
                        <tr>
                            <td>
                                <a href="<?= base_url('drive/f/' . $folder['id']) ?>"
                                    class="text-decoration-none text-dark d-flex align-items-center">
                                    <i class="bi bi-folder-fill text-warning fs-5 me-2"></i>
                                    <span class="text-truncate" style="max-width: 250px;"><?= esc($folder['name']) ?></span>
                                </a>
                            </td>
                            <td><?= esc($folder['owner'] ?? 'Saya') ?></td>
                            <td><?= date('d M Y', strtotime($folder['updated_at'] ?? $folder['created_at'])) ?></td>
                            <td>–</td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm border-0 text-secondary" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item" onclick="renameHandler.call(this)"
                                                data-id="<?= $folder['id'] ?>" data-type="folder"
                                                data-name="<?= esc($folder['name']) ?>">
                                                <i class="bi bi-pencil"></i> Rename
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item btn-delete"
                                                data-del-folder="<?= esc($folder['id']) ?>">
                                                <i class="bi bi-trash me-2"></i> Delete
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php
                    // Function to format the file size
                    function formatSize($size)
                    {
                        if ($size >= 1073741824) {
                            return number_format($size / 1073741824, 2) . ' GB';
                        } elseif ($size >= 1048576) {
                            return number_format($size / 1048576, 2) . ' MB';
                        } elseif ($size >= 1024) {
                            return number_format($size / 1024, 2) . ' KB';
                        } else {
                            return $size . ' B';
                        }
                    }

                    // Assuming $file['size'] contains the size in bytes
                    ?>
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

                        $fileUrl = base_url('drive/download/' . $file['id']);
                        ?>
                        <tr>
                            <td>
                                <a href="#" onclick="previewFile('<?= $fileUrl ?>','<?= esc($file['name']) ?>')"
                                    class="text-decoration-none text-dark d-flex align-items-center">
                                    <i class="<?= $icon ?> fs-5 me-2"></i>
                                    <span class="text-truncate" style="max-width: 250px;"><?= esc($file['name']) ?></span>
                                </a>
                            </td>
                            <td><?= esc($file['owner'] ?? 'Saya') ?></td>
                            <td><?= date('d M Y', strtotime($file['updated_at'] ?? $file['created_at'])) ?></td>
                            <td><?= formatSize($file['size']) ?? '-' ?></td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm border-0 text-secondary" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="<?= base_url('drive/download/' . $file['id']) ?>">
                                                <i class="bi bi-download me-2"></i> Download
                                            </a>
                                        </li>
                                        <li>
                                            <button class="dropdown-item" onclick="renameHandler.call(this)"
                                                data-id="<?= $file['id'] ?>" data-type="file"
                                                data-name="<?= esc($file['name']) ?>">
                                                <i class="bi bi-pencil"></i> Rename
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item btn-delete" data-del-file="<?= $file['id'] ?>">
                                                <i class="bi bi-trash me-2"></i> Delete
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Preview Modal -->
<div class="preview-container" id="previewModal">
    <div class="preview-header">
        <h5 id="previewFilename">File Name</h5>
        <div class="preview-actions">
            <a class="btn" id="downloadBtn" href="#" download title="Download">
                <i class="bi bi-download"></i>
            </a>
            <button class="btn" onclick="printFile()" title="Print">
                <i class="bi bi-printer"></i>
            </button>
            <button class="btn" onclick="closePreview()" title="Close (ESC)">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>

    <div class="preview-content" id="previewContent">
        <!-- Preview content akan dimasukkan di sini -->
    </div>

    <div class="preview-footer" id="previewFooter" style="display: none;">
        <div class="page-nav">
            <button onclick="previousPage()" id="prevBtn" title="Previous Page">
                <i class="bi bi-chevron-left"></i>
            </button>
            <input type="number" id="pageInput" value="1" onchange="goToPage(this.value)" min="1">
            <span id="pageCount">/ 1</span>
            <button onclick="nextPage()" id="nextBtn" title="Next Page">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
        <div class="zoom-controls">
            <button onclick="zoomOut()" id="zoomOutBtn" title="Zoom Out">
                <i class="bi bi-zoom-out"></i>
            </button>
            <span id="zoomLevel">100%</span>
            <button onclick="zoomIn()" id="zoomInBtn" title="Zoom In">
                <i class="bi bi-zoom-in"></i>
            </button>
        </div>
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

<!-- Modal Rename File/Folder -->
<div class="modal fade" id="renameModal" tabindex="-1" aria-labelledby="renameModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="renameModalTitle">Rename Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="renameForm">
                    <!-- Hidden inputs untuk menyimpan data -->
                    <input type="hidden" id="renameItemId" value="">
                    <input type="hidden" id="renameItemType" value="">

                    <div class="mb-3">
                        <label for="renameItemName" class="form-label">Nama Baru</label>
                        <input type="text" class="form-control" id="renameItemName" placeholder="Masukkan nama baru"
                            autocomplete="off" value="">
                        <small class="text-muted d-block mt-2">
                            💡 Tekan <kbd>Enter</kbd> untuk menyimpan atau klik tombol Simpan
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSaveRename">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div id="lightboxOverlay"
    class="d-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-flex align-items-center justify-content-center"
    style="z-index: 1050;">
    <div id="lightboxContent" class="position-relative bg-white rounded shadow p-2"
        style="max-width: 90%; max-height: 90%;">
        <button id="lightboxClose" class="btn btn-sm btn-light position-absolute top-0 end-0 m-2">
            <i class="bi bi-x-lg"></i>
        </button>
        <div id="lightboxBody" class="text-center"></div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('assets/pdfjs/build/pdf.min.js') ?>"></script>
<script>
    const pdfjsLib = window.pdfjsLib || window.pdfjs || window['pdfjs-dist/build/pdf'];
    pdfjsLib.GlobalWorkerOptions.workerSrc = '<?= base_url('assets/pdfjs/build/pdf.worker.min.js') ?>';

    let currentFile = {
        url: '',
        filename: '',
        type: '',
        pdfDoc: null,
        currentPage: 1,
        totalPages: 0,
        zoom: 1
    };

    /**
     * Fungsi untuk membuka preview file
     * @param {string} filePath - Path file (gambar atau PDF)
     * Contoh: previewURL('/drive/file.jpg') atau previewURL('/drive/file.pdf')
     */
    function previewFile(fileUrl, filename) {
        if (!fileUrl) {
            console.error('File URL is required');
            return;
        }

        const ext = filename.split('.').pop().toLowerCase();
        const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        const pdfExts = ['pdf'];

        let fileType = 'other';
        if (imageExts.includes(ext)) fileType = 'image';
        else if (pdfExts.includes(ext)) fileType = 'pdf';

        currentFile = {
            url: fileUrl,
            filename: filename,
            type: fileType,
            pdfDoc: null,
            currentPage: 1,
            totalPages: 0,
            zoom: 1
        };

        showPreview();
    }

    // Fungsi showPreview tetap sama, tapi pastikan downloadBtn menggunakan URL yang benar
    function showPreview() {
        const modal = document.getElementById('previewModal');
        const content = document.getElementById('previewContent');
        const footer = document.getElementById('previewFooter');
        const downloadBtn = document.getElementById('downloadBtn');

        document.getElementById('previewFilename').textContent = currentFile.filename;
        downloadBtn.href = currentFile.url;
        downloadBtn.download = currentFile.filename;
        content.innerHTML = '';

        if (currentFile.type === 'image') {
            footer.style.display = 'none';
            const img = document.createElement('img');
            img.src = currentFile.url;
            img.className = 'preview-image';
            img.onerror = () => {
                content.innerHTML = '<div class="text-danger text-center"><i class="bi bi-exclamation-circle" style="font-size: 48px;"></i><p class="mt-2">Error loading image</p></div>';
            };
            content.appendChild(img);
        } else if (currentFile.type === 'pdf') {
            footer.style.display = 'flex';
            loadPDF();
        } else {
            content.innerHTML = '<div class="text-warning text-center"><i class="bi bi-file-earmark" style="font-size: 48px;"></i><p class="mt-2">File type not supported for preview</p></div>';
            footer.style.display = 'none';
        }

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // loadPDF tetap sama
    function loadPDF() {
        const content = document.getElementById('previewContent');
        content.innerHTML = '<div class="loading-spinner"><div class="spinner-border text-primary" role="status"></div><span>Loading PDF...</span></div>';

        pdfjsLib.getDocument(currentFile.url).promise.then(pdf => {
            currentFile.pdfDoc = pdf;
            currentFile.totalPages = pdf.numPages;
            currentFile.currentPage = 1;
            currentFile.zoom = 1;

            document.getElementById('pageCount').textContent = `/ ${pdf.numPages}`;
            document.getElementById('pageInput').max = pdf.numPages;
            document.getElementById('zoomLevel').textContent = '100%';
            updatePageButtons();

            renderPage();
        }).catch(err => {
            console.error('PDF loading error:', err);
            content.innerHTML = '<div class="text-danger text-center"><i class="bi bi-exclamation-circle" style="font-size: 48px;"></i><p class="mt-2">Error loading PDF: ' + err.message + '</p></div>';
        });
    }

    function nextPage() {
        if (currentFile.currentPage < currentFile.totalPages) {
            currentFile.currentPage++;
            renderPage();
            updatePageButtons();
        }
    }

    function renderPage() {
        if (!currentFile.pdfDoc) return;

        const content = document.getElementById('previewContent');
        content.innerHTML = '';

        currentFile.pdfDoc.getPage(currentFile.currentPage).then(page => {
            const container = document.createElement('div');
            container.className = 'pdf-container';

            const canvas = document.createElement('canvas');
            canvas.className = 'pdf-canvas';

            const scale = 1.5 * currentFile.zoom;
            const viewport = page.getViewport({ scale: scale });

            canvas.width = viewport.width;
            canvas.height = viewport.height;

            const renderContext = {
                canvasContext: canvas.getContext('2d'),
                viewport: viewport
            };

            page.render(renderContext).promise.then(() => {
                container.appendChild(canvas);
                content.appendChild(container);
            }).catch(err => {
                console.error('Page rendering error:', err);
                content.innerHTML = '<div class="text-danger text-center"><i class="bi bi-exclamation-circle" style="font-size: 48px;"></i><p class="mt-2">Error rendering page</p></div>';
            });
        }).catch(err => {
            console.error('Get page error:', err);
        });

        document.getElementById('pageInput').value = currentFile.currentPage;
    }

    function previousPage() {
        if (currentFile.currentPage > 1) {
            currentFile.currentPage--;
            renderPage();
            updatePageButtons();
        }
    }

    function goToPage(page) {
        const pageNum = parseInt(page);
        if (pageNum >= 1 && pageNum <= currentFile.totalPages) {
            currentFile.currentPage = pageNum;
            renderPage();
            updatePageButtons();
        }
    }

    function updatePageButtons() {
        document.getElementById('prevBtn').disabled = currentFile.currentPage === 1;
        document.getElementById('nextBtn').disabled = currentFile.currentPage === currentFile.totalPages;
    }

    function zoomIn() {
        if (currentFile.type === 'pdf' && currentFile.zoom < 2) {
            currentFile.zoom += 0.25;
            document.getElementById('zoomLevel').textContent = Math.round(currentFile.zoom * 100) + '%';
            renderPage();
        }
    }

    function zoomOut() {
        if (currentFile.type === 'pdf' && currentFile.zoom > 0.5) {
            currentFile.zoom -= 0.25;
            document.getElementById('zoomLevel').textContent = Math.round(currentFile.zoom * 100) + '%';
            renderPage();
        }
    }

    function closePreview() {
        document.getElementById('previewModal').classList.remove('active');
        document.body.style.overflow = 'auto';
        currentFile.pdfDoc = null;
    }

    function printFile() {
        if (currentFile.type === 'image') {
            const printWindow = window.open(currentFile.url);
            printWindow.addEventListener('load', () => {
                printWindow.print();
            });
        } else if (currentFile.type === 'pdf') {
            window.open(currentFile.url);
        }
    }

    // UNTUK GRID VIEW DAN LIST VIEW
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const gridBtn = document.getElementById('gridViewBtn');
    const listBtn = document.getElementById('listViewBtn');


    function showView(viewType) {
        if (viewType === 'list') {
            listView.classList.remove('d-none');
            gridView.classList.add('d-none');
            listBtn.classList.add('active');
            gridBtn.classList.remove('active');
        } else {
            gridView.classList.remove('d-none');
            listView.classList.add('d-none');
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
        }
        localStorage.setItem('driveView', viewType);
    }

    gridBtn.addEventListener('click', () => showView('grid'));
    listBtn.addEventListener('click', () => showView('list'));

    const savedView = localStorage.getItem('driveView');
    if (savedView === 'list') {
        showView('list');
    } else {
        showView('grid');
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closePreview();
        }
    });

    document.getElementById('previewModal').addEventListener('click', (e) => {
        if (e.target.id === 'previewModal') {
            closePreview();
        }
    });

    function renameHandler() {
        const id = this.getAttribute('data-id');
        const type = this.getAttribute('data-type'); // 'file' atau 'folder'
        const name = this.getAttribute('data-name');

        // Cek apakah element ada
        const itemIdEl = document.getElementById('renameItemId');
        const itemTypeEl = document.getElementById('renameItemType');
        const itemNameEl = document.getElementById('renameItemName');
        const modalTitleEl = document.getElementById('renameModalTitle');
        const renameModalEl = document.getElementById('renameModal');

        if (!itemIdEl || !itemTypeEl || !itemNameEl || !renameModalEl) {
            console.error('Element modal tidak ditemukan');
            alert('Terjadi kesalahan: Modal tidak ditemukan');
            return;
        }

        // Set nilai ke input
        itemIdEl.value = id;
        itemTypeEl.value = type;
        itemNameEl.value = name;

        // Update judul modal
        const modalTitle = type === 'file' ? 'Rename File' : 'Rename Folder';
        if (modalTitleEl) {
            modalTitleEl.textContent = modalTitle;
        }

        // Tampilkan modal
        const renameModal = new bootstrap.Modal(renameModalEl);
        renameModal.show();

        // Fokus dan select text
        setTimeout(() => {
            itemNameEl.focus();
            itemNameEl.select();
        }, 100);
    }

</script>
<script>
    $(document).ready(function () {
        // Hapus file
        document.querySelectorAll('[data-del-file]').forEach(btn => {
            btn.addEventListener('click', deleteFileHandler);
        });

        document.querySelectorAll('[data-del-folder]').forEach(btn => {
            btn.addEventListener('click', deleteFolderHandler);
        });

        $(document).on('click', '.btn-rename', renameHandler);

        $('#btnSaveRename').on('click', function () {
            const itemId = document.getElementById('renameItemId')?.value;
            const itemType = document.getElementById('renameItemType')?.value;
            const newName = document.getElementById('renameItemName')?.value;
            if (!itemId || !itemType) {
                alert('Item ID atau tipe tidak valid');
                return;
            }

            if (!newName || !newName.trim()) {
                alert('Nama tidak boleh kosong');
                return;
            }
            const endpoint = itemType === 'file' ? 'drive/renameFile' : 'drive/renameFolder';
            $.ajax({
                url: '<?= base_url("") ?>' + endpoint,
                type: 'POST',
                dataType: 'json',
                data: {
                    id: itemId,
                    name: newName.trim()
                },
                success: function (response) {
                    if (response.success) {
                        // Tutup modal
                        const renameModal = bootstrap.Modal.getInstance(document.getElementById('renameModal'));
                        if (renameModal) {
                            renameModal.hide();
                        }
                        location.reload();
                    } else {
                        alert(response.message || 'Gagal mengubah nama');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error, xhr);
                    let errorMsg = 'Terjadi kesalahan saat mengubah nama';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert(errorMsg);
                }
            });
        });

        // Tekan Enter untuk submit
        $(document).on('keypress', '#renameItemName', function (e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                $('#btnSaveRename').click();
            }
        });

        // Clear modal saat ditutup
        $('#renameModal').on('hidden.bs.modal', function () {
            document.getElementById('renameItemId').value = '';
            document.getElementById('renameItemType').value = '';
            document.getElementById('renameItemName').value = '';
        });
    });

    // Search functionality dengan debounce
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            // Jika kosong, kembali ke halaman normal
            if (query.length === 0) {
                window.location.href = window.location.pathname.split('?')[0];
                return;
            }

            // Minimal 2 karakter untuk search
            if (query.length < 2) {
                return;
            }

            // Debounce: tunggu 500ms sebelum melakukan search
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 500);
        });

        // Event listener untuk Enter key
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                if (query.length >= 2) {
                    performSearch(query);
                }
            }
        });
    }

    /**
     * Fungsi untuk melakukan search
     * @param {string} searchQuery - Query pencarian
     */
    function performSearch(searchQuery) {
        // Show loading indicator
        const fileListContainer = document.getElementById('container-search');
        if (fileListContainer) {
            showLoadingSpinner(fileListContainer);
        }

        fetch(`<?= base_url('drive/search/') ?>${encodeURIComponent(searchQuery)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                updateSearchResults(data, searchQuery);
            })
            .catch(error => {
                console.error('Search error:', error);
                showSearchError('Gagal melakukan pencarian. Silakan coba lagi.');
            });
    }

    /**
     * Tampilkan loading spinner
     */
    function showLoadingSpinner(container) {
        container.innerHTML = `
        <div class="col-12 text-center my-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Mencari file...</p>
        </div>
    `;
    }

    /**
     * Tampilkan error message
     */
    function showSearchError(message) {
        const container = document.getElementById('container-search');
        if (container) {
            container.innerHTML = `
            <div class="col-12 text-center my-5">
                <i class="bi bi-exclamation-circle text-danger" style="font-size: 48px;"></i>
                <p class="mt-2 text-danger">${message}</p>
                <button class="btn btn-outline-primary mt-2" onclick="window.location.reload()">
                    <i class="bi bi-arrow-clockwise me-2"></i> Muat Ulang
                </button>
            </div>
        `;
        }
    }

    /**
     * Update hasil pencarian di UI
     */
    function updateSearchResults(data, searchQuery) {
        const container = document.getElementById('container-search');
        if (!container) return;

        const { folders = [], files = [], query } = data;
        const totalResults = folders.length + files.length;

        if (totalResults === 0) {
            container.innerHTML = `
            <div class="col-12 text-center my-5">
                <i class="bi bi-search text-muted" style="font-size: 48px;"></i>
                <h5 class="mt-3 text-muted">Tidak ada hasil untuk "<strong>${escapeHtml(query || searchQuery)}</strong>"</h5>
                <p class="text-muted">Coba gunakan kata kunci yang berbeda</p>
            </div>
        `;
            return;
        }

        let html = '';

        // Hasil folder
        if (folders.length > 0) {
            html += `
            <div class="col-12 mb-3">
                <h6 class="text-muted text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">
                    <i class="bi bi-folder me-2"></i> Folder (${folders.length})
                </h6>
            </div>
        `;

            folders.forEach(folder => {
                html += `
                <div class="col-md-2 col-sm-6 mb-4">
                    <div class="card file-card shadow-sm border-0 h-100">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <a href="<?= base_url('drive/f/') ?>${folder.id}" 
                               class="text-decoration-none text-dark d-flex align-items-center flex-grow-1 min-w-0">
                                <i class="bi bi-folder-fill text-warning" style="font-size: 25px;"></i>
                                <span class="ms-3 text-truncate" title="${escapeHtml(folder.name)}">
                                    ${highlightSearchTerm(folder.name, searchQuery)}
                                </span>
                            </a>
                            <div class="dropdown ms-2">
                                <button class="btn btn-sm border-0 text-secondary" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false" title="Options">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <button type="button" class="dropdown-item btn-delete"
                                                data-del-folder="${folder.id}">
                                            <i class="bi bi-trash me-2"></i> Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            });
        }

        // Hasil file
        if (files.length > 0) {
            html += `
            <div class="col-12 mb-3 mt-3">
                <h6 class="text-muted text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">
                    <i class="bi bi-file-earmark me-2"></i> File (${files.length})
                </h6>
            </div>
        `;

            files.forEach(file => {
                const ext = file.name.split('.').pop().toLowerCase();
                const icon = getFileIcon(ext);
                const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext);
                const isPDF = ext === 'pdf';
                const fileUrl = `<?= base_url('drive/download/') ?>${file.id}`;

                html += `
                <div class="col-md-2 col-sm-4 col-6 mb-4">
                    <div class="card file-card border-0 shadow-sm h-100">
                        <div class="file-preview bg-light d-flex align-items-center justify-content-center" 
                             style="height:150px; overflow:hidden; cursor: pointer;"
                             onclick="previewFile('${fileUrl}', '${escapeHtml(file.name)}')">
                            ${getFilePreview(isImage, isPDF, fileUrl, file, ext)}
                        </div>
                        <div class="d-flex align-items-center justify-content-between px-2 py-2">
                            <div class="d-flex align-items-center flex-grow-1 overflow-hidden">
                                <i class="${icon} text-danger fs-5 me-2"></i>
                                <span class="text-truncate" style="max-width: 80%;" 
                                      title="${escapeHtml(file.name)}">
                                    ${highlightSearchTerm(file.name, searchQuery)}
                                </span>
                            </div>
                            <div class="dropdown ms-2">
                                <button class="btn btn-sm border-0 text-secondary" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="${fileUrl}">
                                            <i class="bi bi-download me-2"></i> Download
                                        </a>
                                    </li>
                                    <li>
                                        <button class="dropdown-item btn-rename" data-id="${file.id}"
                                                data-name="${escapeHtml(file.name)}">
                                            <i class="bi bi-pencil-square me-2"></i> Rename
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item btn-delete" data-del-file="${file.id}">
                                            <i class="bi bi-trash me-2"></i> Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            });
        }

        container.innerHTML = html;

        // Re-attach event listeners untuk delete dan rename
        attachEventListeners();
    }

    /**
     * Dapatkan file preview HTML
     */
    function getFilePreview(isImage, isPDF, fileUrl, file, ext) {
        if (isImage) {
            return `<img src="${fileUrl}" alt="${escapeHtml(file.name)}" 
                     class="img-fluid rounded-top" 
                     style="max-height: 150px; object-fit: cover;">`;
        } else if (isPDF) {
            return `<i class="bi bi-filetype-pdf" style="font-size: 48px; color: #dc3545;"></i>`;
        } else {
            const icon = getFileIcon(ext);
            return `<i class="${icon}" style="font-size: 48px;"></i>`;
        }
    }

    /**
     * Dapatkan icon berdasarkan extension
     */
    function getFileIcon(ext) {
        const iconMap = {
            'jpg': 'bi bi-image text-danger',
            'jpeg': 'bi bi-image text-danger',
            'png': 'bi bi-image text-danger',
            'gif': 'bi bi-image text-danger',
            'webp': 'bi bi-image text-danger',
            'pdf': 'bi bi-filetype-pdf text-danger',
            'doc': 'bi bi-filetype-docx text-info',
            'docx': 'bi bi-filetype-docx text-info',
            'xls': 'bi bi-filetype-xlsx text-success',
            'xlsx': 'bi bi-filetype-xlsx text-success',
            'csv': 'bi bi-filetype-xlsx text-success',
            'ppt': 'bi bi-file-earmark-ppt text-warning',
            'pptx': 'bi bi-file-earmark-ppt text-warning',
            'zip': 'bi bi-file-earmark-zip text-secondary',
            'rar': 'bi bi-file-earmark-zip text-secondary',
        };
        return iconMap[ext] || 'bi bi-file-earmark-fill text-primary';
    }

    /**
     * Highlight search term dalam hasil
     */
    function highlightSearchTerm(text, searchTerm) {
        const regex = new RegExp(`(${escapeRegex(searchTerm)})`, 'gi');
        return text.replace(regex, '<mark style="background-color: #fff3cd; padding: 2px 4px; border-radius: 2px;">$1</mark>');
    }

    /**
     * Escape HTML special characters
     */
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    /**
     * Escape regex special characters
     */
    function escapeRegex(text) {
        return text.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    /**
     * Re-attach event listeners untuk delete dan rename buttons
     */
    function attachEventListeners() {
        // Delete file
        document.querySelectorAll('[data-del-file]').forEach(btn => {
            btn.removeEventListener('click', deleteFileHandler);
            btn.addEventListener('click', deleteFileHandler);
        });

        // Delete folder
        document.querySelectorAll('[data-del-folder]').forEach(btn => {
            btn.removeEventListener('click', deleteFolderHandler);
            btn.addEventListener('click', deleteFolderHandler);
        });

        // Rename
        document.querySelectorAll('.btn-rename').forEach(btn => {
            btn.removeEventListener('click', renameHandler);
            btn.addEventListener('click', renameHandler);
        });
    }

    /**
     * Handler untuk delete file
     */
    async function deleteFileHandler() {
        if (!confirm('Hapus file ini?')) return;
        const id = this.getAttribute('data-del-file');
        try {
            const res = await fetch(`<?= base_url('drive/moveToTrash/') ?>${id}`, {
                method: 'post'
            });
            if (res.ok) {
                location.reload();
            } else {
                alert('Gagal menghapus file');
            }
        } catch (error) {
            console.error('Delete error:', error);
            alert('Terjadi kesalahan');
        }
    }

    /**
     * Handler untuk delete folder
     */
    async function deleteFolderHandler() {
        if (!confirm('Hapus folder ini?')) return;
        const id = this.getAttribute('data-del-folder');
        try {
            const res = await fetch(`<?= base_url('drive/moveToTrashFolder/') ?>${id}`, {
                method: 'post'
            });
            if (res.ok) {
                location.reload();
            } else {
                alert('Gagal menghapus folder');
            }
        } catch (error) {
            console.error('Delete error:', error);
            alert('Terjadi kesalahan');
        }
    }

</script>
<?= $this->endSection() ?>