<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <!-- Search Bar -->
    <div class="row justify-content-center mb-3">
        <div class="col-md-8">
            <form class="d-flex" action="<?= base_url('/search') ?>" method="get">
                <input class="form-control form-control-lg me-2 rounded-pill px-4 shadow-sm" 
                       type="search" 
                       name="q" 
                       placeholder="Cari file atau folder..." 
                       aria-label="Search">
            </form>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col d-flex justify-content-center gap-2">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                <i class="bi bi-folder-plus"></i> Buat Folder
            </button>

            <form action="<?= base_url('/file/upload') ?>" method="post" enctype="multipart/form-data">
                <input type="file" name="file" id="uploadFile" hidden onchange="this.form.submit()">
                <button type="button" class="btn btn-primary" onclick="document.getElementById('uploadFile').click()">
                    <i class="bi bi-upload"></i> Upload File
                </button>
            </form>
        </div>
    </div>

    <!-- Folder & File List -->
    <div class="row">
        <?php if(empty($folders) && empty($files)): ?>
            <!-- Jika kosong -->
            <div class="col-12 text-center my-5 empty-state">
                <img src="<?= base_url('assets/img/undraw.svg') ?>" 
                     alt="Belum ada file atau folder" 
                     width="300">
                <h5 class="mt-3 text-muted">
                    Klik buat folder atau upload file untuk menambahkan
                </h5>
            </div>

        <?php else: ?>
            <!-- Loop Folder -->
            <?php foreach($folders as $folder): ?>
                <div class="col-md-2 col-4 text-center mb-4">
                    <a href="<?= base_url('drive/'.$folder['id']) ?>" 
                       class="text-decoration-none text-dark">
                        <i class="bi bi-folder-fill text-warning" style="font-size:40px;"></i>
                        <p class="mt-2 small text-truncate" 
                           title="<?= esc($folder['name']) ?>">
                            <?= esc($folder['name']) ?>
                        </p>
                    </a>
                </div>
            <?php endforeach; ?>
            
            <!-- Loop File -->
            <?php foreach($files as $file): ?>
                <?php 
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $icon = "bi bi-file-earmark-fill text-primary"; 
            
                    if(in_array($ext, ['jpg','jpeg','png','gif'])) $icon = "bi bi-file-earmark-image text-danger";
                    if(in_array($ext, ['pdf'])) $icon = "bi bi-file-earmark-pdf text-danger";
                    if(in_array($ext, ['doc','docx'])) $icon = "bi bi-file-earmark-word text-info";
                    if(in_array($ext, ['xls','xlsx'])) $icon = "bi bi-file-earmark-excel text-success";
                    if(in_array($ext, ['ppt','pptx'])) $icon = "bi bi-file-earmark-ppt text-warning";
                    if(in_array($ext, ['zip','rar'])) $icon = "bi bi-file-earmark-zip text-secondary";
                ?>
                <div class="col-md-2 col-4 text-center mb-4">
                    <i class="<?= $icon ?>" style="font-size:40px;"></i>
                    <p class="mt-2 small text-truncate" 
                       title="<?= esc($file['name']) ?>">
                        <?= esc($file['name']) ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Buat Folder -->
<div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?= base_url('/folder/create') ?>" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createFolderModalLabel">Buat Folder Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="text" class="form-control" name="folder_name" placeholder="Nama Folder" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Buat</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>
