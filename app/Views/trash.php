<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-semibold mb-0"><i class="bi bi-trash me-2"></i> Sampah</h5>
    </div>

    <?php if (empty($files) && empty($folders)): ?>
        <div class="empty-trash-state text-center">
            <img src="<?= base_url('assets/img/throw-away.svg') ?>" alt="Belum ada file atau folder" width="300">
            <h5 class="mt-3 text-muted">
                <b>Sampah Kosong</b>
            </h5>
            <p>
                Item yang dipindahkan ke sampah akan dihapus selamanya setelah 30 hari
            </p>
        </div>
    <?php else: ?>

    <div id="row" class="row g-3">
      <?php foreach ($folders as $folder): ?>
        <div class="col-md-2 col-sm-6 mb-4">
          <div class="card file-card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center flex-grow-1 min-w-0">
                <i class="bi bi-folder-fill text-warning fs-4 me-2"></i>
                <span class="text-truncate"><?= esc($folder['name']) ?></span>
              </div>
              <div class="dropdown ms-2">
                <button class="btn btn-sm border-0 text-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <form action="<?= base_url('trash/restore/folder/' . $folder['id']) ?>" method="post">
                      <button type="submit" class="dropdown-item text-success">
                        <i class="bi bi-arrow-counterclockwise me-2"></i> Restore
                      </button>
                    </form>
                  </li>
                  <li>
                    <form action="<?= base_url('trash/delete-permanent/folder/' . $folder['id']) ?>" method="post">
                      <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-trash me-2"></i> Hapus Permanen
                      </button>
                    </form>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

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

        <div class="col-md-2 col-sm-4 col-6 mb-4">
          <div class="card file-card border-0 shadow-sm h-100" ata-preview-url="<?= $fileUrl ?>"
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
                <ul class="dropdown-menu">
                  <li>
                    <form action="<?= base_url('trash/restore/file/' . $file['id']) ?>" method="post">
                      <button type="submit" class="dropdown-item text-success">
                        <i class="bi bi-arrow-counterclockwise me-2"></i> Restore
                      </button>
                    </form>
                  </li>
                  <li>
                    <form action="<?= base_url('trash/delete-permanent/file/' . $file['id']) ?>" method="post">
                      <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-trash me-2"></i> Hapus Permanen
                      </button>
                    </form>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?= $this->endSection() ?>
