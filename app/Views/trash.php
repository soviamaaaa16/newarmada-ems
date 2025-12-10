<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-semibold mb-0"><i class="bi bi-trash me-2"></i> Sampah</h5>

    <!-- Button List View dan Grid View -->
    <div class="btn-group" role="group" aria-label="View toggle">
      <button type="button" id="listViewBtn" class="btn btn-outline-secondary">
        <i class="bi bi-list"></i>
      </button>
      <button type="button" id="gridViewBtn" class="btn btn-outline-secondary">
        <i class="bi bi-grid"></i>
      </button>
    </div>
  </div>

  <div id="gridView" class="row">
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
                <!-- Folder Link -->
                <a href="<?= base_url('drive/f/' . $folder['id']) ?>"
                    class="text-decoration-none text-dark d-flex align-items-center flex-grow-1 min-w-0">
                    <i class="bi bi-folder-fill text-warning" style="font-size: 25px;"></i>
                    <span class="ms-3 text-truncate" title="<?= esc($folder['name']) ?>">
                        <?= esc($folder['name']) ?>
                    </span>
                </a>

                <div class="dropdown ms-2">
                  <button class="btn btn-sm border-0 text-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i>
                  </button>
                  <ul class="dropdown-menu">
                    <li>
                      <!-- <form action="" method="post"> -->
                      <button type="button" onclick="restoreFolder(<?= $folder['id'] ?>)" class="dropdown-item text-success">
                        <i class="bi bi-arrow-counterclockwise me-2"></i> Restore
                      </button>
                      <!-- </form> -->
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
      </div>
        
      <div class="row" class="files">
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
              <div
                class="file-preview bg-light d-flex align-items-center justify-content-center style=height:150px; overflow:hidden;"
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
                      <!-- <form action="<?= base_url('trash/restoreFile/' . $file['id']) ?>" method="post"> -->
                      <button type="button" onclick="restoreFile(<?= $file['id'] ?>)" class="dropdown-item text-success">
                        <i class="bi bi-arrow-counterclockwise me-2"></i> Restore
                      </button>
                      <!-- </form> -->
                    </li>
                    <li>
                      <!-- <form action="<?= base_url('trash/delete-permanent/file/' . $file['id']) ?>" method="post"> -->
                      <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-trash me-2"></i> Hapus Permanen
                      </button>
                      <!-- </form> -->
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

  <div id="listView" class="d-none">
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
                                        <!-- <form action="" method="post"> -->
                                        <button type="button" onclick="restoreFolder(<?= $folder['id'] ?>)" class="dropdown-item text-success">
                                          <i class="bi bi-arrow-counterclockwise me-2"></i> Restore
                                        </button>
                                        <!-- </form> -->
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
                                        <!-- <form action="<?= base_url('trash/restoreFile/' . $file['id']) ?>" method="post"> -->
                                        <button type="button" onclick="restoreFile(<?= $file['id'] ?>)" class="dropdown-item text-success">
                                          <i class="bi bi-arrow-counterclockwise me-2"></i> Restore
                                        </button>
                                        <!-- </form> -->
                                      </li>
                                      <li>
                                        <!-- <form action="<?= base_url('trash/delete-permanent/file/' . $file['id']) ?>" method="post"> -->
                                        <button type="submit" class="dropdown-item text-danger">
                                          <i class="bi bi-trash me-2"></i> Hapus Permanen
                                        </button>
                                        <!-- </form> -->
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

<script>
  function restoreFile(fileId) {
    fetch(`<?= base_url('trash/restoreFile/') ?>${fileId}`, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
      }
    })
      .then(response => {
        if (response.ok) {
          location.reload();
        } else {
          alert('Gagal mengembalikan file.');
        }
      })
      .catch(() => {
        alert('Terjadi kesalahan jaringan.');
      });
  }

  function restoreFolder(folderId) {
    fetch(`<?= base_url('trash/restoreFolder/') ?>${folderId}`, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
      }
    })
      .then(response => {
        if (response.ok) {
          location.reload();
        } else {
          alert('Gagal mengembalikan folder.');
        }
      })
      .catch(() => {
        alert('Terjadi kesalahan jaringan.');
      });
  }

  document.querySelectorAll('.dropdown').forEach(drop => {
    drop.addEventListener('show.bs.dropdown', function () {
      document.querySelectorAll('.file-card').forEach(c => c.classList.remove('show-dropdown'));
      this.closest('.file-card')?.classList.add('show-dropdown');
    });
    
    drop.addEventListener('hide.bs.dropdown', function () {
      this.closest('.file-card')?.classList.remove('show-dropdown');
    });
  });

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


</script>
<?= $this->endSection() ?>

