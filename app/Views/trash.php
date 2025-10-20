<?= $this->extend('layouts/master') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
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

    <div id="trashView" class="row g-3">
      <?php foreach ($folders as $folder): ?>
        <div class="col-md-2 col-sm-4 col-6">
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
        <div class="col-md-2 col-sm-4 col-6">
          <div class="card file-card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center flex-grow-1 min-w-0">
                <i class="bi bi-file-earmark text-primary fs-4 me-2"></i>
                <span class="text-truncate"><?= esc($file['name']) ?></span>
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
