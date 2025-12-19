<div class="d-flex min-vh-100">
  <nav id="sidebar" class="d-none d-md-block bg-light border-end">
    <div class="p-3">
      <!-- <div class="d-flex flex-column align-items-center mb-4 mt-3">
        <img src="<?= base_url('assets/img/MAJ-LOGO-3.png') ?>" alt="Logo" style="height: 50px; width: auto;">
      </div> -->

      <!-- Menu -->
      <ul class="nav flex-column">
        <li class="nav-item mb-2">
          <a href="<?= base_url('/drive') ?>" class="nav-link text-dark">
            <i class="bi bi-folder me-2"></i> Drive Saya
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="<?= base_url('drive/trash') ?>" class="nav-link text-dark">
            <i class="bi bi-trash me-2"></i> Sampah
          </a>
        </li>
        <?php if (auth()->loggedIn()) {
          if (auth()->user()->inGroup('admin', 'superadmin')) { ?>
            <li class="nav-item mb-2">
              <a href="<?= base_url('admin/users') ?>" class="nav-link text-dark">
                <i class="bi bi-person me-2"></i> Manage Users
              </a>
            </li>
          <?php }
        } ?>

        <li class="nav-item mt-4 border-top pt-3">
          <div class="folder-tree-container">
              <div id="folder-tree"></div>
          </div>
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
        <img src="<?= base_url('assets/img/MAJ-LOGO-3.png') ?>" alt="Logo" style="height: 40px; width: auto;">
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

</div>
</body>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const currentFolderId = "<?= $currentFolderId ?? '' ?>";

    fetch("<?= base_url('drive/getFolderTree') ?>")
      .then(res => res.json())
      .then(data => {
        const treeContainer = document.getElementById("folder-tree");
        treeContainer.innerHTML = renderTree(data, currentFolderId);

        enableTreeActions();
      });

    function renderTree(nodes, activeId) {
        let html = "<ul>";
 
        nodes.forEach(n => {
            const hasChildren = n.children && n.children.length > 0;
            const isActive = activeId == n.id;

        html += `
                <li>
                    <div class="folder-node ${isActive ? 'active' : ''}" data-id="${n.id}">
                        ${hasChildren ? `<span class="tree-toggle ${isActive ? 'open' : ''}"></span>` : `<span style="width:12px"></span>`}
                        <i class="bi bi-folder"></i>
                        <span>${n.name}</span>
                    </div>

                    ${hasChildren ? `
                        <div class="tree-children" style="display:${isActive ? 'block' : 'none'}">
                            ${renderTree(n.children, activeId)}
                        </div>` : ""}
                </li>
            `;
      });

      html += "</ul>";
      return html;
    }

    function enableTreeActions() {
      document.querySelectorAll(".tree-toggle").forEach(toggle => {
        toggle.addEventListener("click", function (e) {
          e.stopPropagation();
          const children = this.parentElement.nextElementSibling;

          if (children.style.display === "none") {
            children.style.display = "block";
            this.classList.add("open");
          } else {
            children.style.display = "none";
            this.classList.remove("open");
          }
        });
      });

      document.querySelectorAll(".folder-node").forEach(node => {
        node.addEventListener("click", function () {
          const id = this.getAttribute("data-id");
          window.location.href = "<?= base_url('drive/f/') ?>" + id;
        });
      });
    }
  });
</script>

<style>
  #folder-tree ul {
    list-style: none;
    margin: 0;
  }

  #folder-tree li {
    margin: 2px 0;
  }

  .folder-node {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 4px 4px;
    cursor: pointer;
    border-radius: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .folder-node:hover {
    background: #f0f0f0;
  }

  .folder-node.active {
    background: #e8f0fe;
    font-weight: 600;
  }

  .tree-toggle {
    width: 12px;
    height: 12px;
    border-left: 6px solid #777;
    border-top: 4px solid transparent;
    border-bottom: 4px solid transparent;
    margin-right: 4px;
    cursor: pointer;
    transition: transform 0.2s;
  }

  .tree-toggle.open {
    transform: rotate(90deg);
  }

  .tree-children {
    margin-left: 12px;
  }

#sidebar {
  overflow-y: auto;
  overflow-x: hidden;
  width: 300px;
  flex-shrink: 0
}

  #folder-tree span {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
    display: inline-block;
  }

#folder-tree span {
  display: inline-block;
  padding-right: 10px;
}

</style>