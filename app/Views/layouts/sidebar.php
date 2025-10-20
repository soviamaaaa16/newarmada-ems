  <div class="d-flex">
    <nav id="sidebar" class="d-none d-md-block bg-light border-end vh-100">
      <div class="p-3">
        <div class="d-flex flex-column align-items-center mb-4 mt-3">
          <img src="<?= base_url('assets/img/MAJ-LOGO-3.png') ?>" 
               alt="Logo" style="height: 50px; width: auto;">
        </div>

        <!-- Menu -->
        <ul class="nav flex-column">
          <li class="nav-item mb-2">
            <a href="<?= base_url('/') ?>" class="nav-link text-dark">
              <i class="bi bi-folder me-2"></i> Drive Saya
            </a>
          </li>
          <li class="nav-item mb-2">
            <a href="<?= base_url('drive/trash') ?>" class="nav-link text-dark">
              <i class="bi bi-trash me-2"></i> Sampah
            </a>
          </li>
          <li class="nav-item mt-4 border-top pt-3">
            <a href="<?= base_url('guide') ?>" class="nav-link text-dark">
              <i class="bi bi-book me-2"></i> Panduan Pengguna
            </a>
          </li>
          <li class="nav-item mb-2">
            <a href="<?= base_url('logout') ?>" class="nav-link text-dark">
              <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="offcanvas offcanvas-start bg-light" tabindex="-1" id="mobileSidebar">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <div class="d-flex flex-column align-items-center mb-4 mt-3">
          <img src="<?= base_url('assets/img/MAJ-LOGO-3.png') ?>" 
               alt="Logo" style="height: 50px; width: auto;">
        </div>

        <ul class="nav flex-column">
          <li class="nav-item mb-2">
            <a href="<?= base_url('/') ?>" class="nav-link text-dark">
              <i class="bi bi-folder me-2"></i> Drive Saya
            </a>
          </li>
          <li class="nav-item mb-2">
            <a href="<?= base_url('drive/trash') ?>" class="nav-link text-dark">
              <i class="bi bi-trash me-2"></i> Sampah
            </a>
          </li>
          <li class="nav-item mt-4 border-top pt-3">
            <a href="<?= base_url('guide') ?>" class="nav-link text-dark" data-bs-dismiss="offcanvas">
              <i class="bi bi-book me-2"></i> Panduan Pengguna
            </a>
          </li>
          <li class="nav-item mb-2">
            <a href="<?= base_url('logout') ?>" class="nav-link text-dark" data-bs-dismiss="offcanvas">
              <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="flex-grow-1 p-4">
      <button class="btn btn-outline-secondary d-md-none mb-3" 
              type="button" 
              data-bs-toggle="offcanvas" 
              data-bs-target="#mobileSidebar">
        <i class="bi bi-list"></i>
      </button>

      <div>
        <?= $this->renderSection('content') ?>
      </div>
    </div>
  </div>
</body>