<nav class="navbar main-navbar navbar-light bg-white border-bottom px-3">

  <button class="btn btn-outline-secondary d-md-none" type="button"
          data-bs-toggle="offcanvas"
          data-bs-target="#mobileSidebar">
    <i class="bi bi-list"></i>
  </button>

    <div class="navbar-logos">
        <div class="logo-armada">
            <img src="<?= base_url('assets/img/MAJ-LOGO-3.png') ?>" alt="Logo" style="height: 50px; width: auto;">
        </div>

        <div class="logo-iso">
            <img src="/assets/img/ISO.png" alt="Logo ISO 2">
        </div>
    </div>


  <div class="ms-auto d-flex align-items-center gap-3">

    <!-- <div class="logo-iso">
        <img src="/assets/img/ISO.png" alt="Logo ISO 2">
    </div> -->

    <!-- JAM -->
    <span class="text-muted small" id="clock"></span>

    <!-- LOGOUT -->
    <a href="<?= base_url('logout') ?>" class="nav-link">
      Logout
    </a>

  </div>

</nav>


<script>
    function updateClock() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();

    // Formatting time to add leading zero if necessary
    hours = ('0' + hours).slice(-2);
    minutes = ('0' + minutes).slice(-2);
    seconds = ('0' + seconds).slice(-2);

    // Array of month names
    var monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    var month = monthNames[now.getMonth()];

    // Formatting date
    var day = ('0' + now.getDate()).slice(-2);
    var year = now.getFullYear();

    var clockElement = document.getElementById('clock');
    clockElement.textContent = day + ' ' + month + ' ' + year + ' ' + hours + ':' + minutes + ':' + seconds;

    setTimeout(updateClock, 1000);
  }
  updateClock();
</script>

<style>
    .navbar-logos {
        display: flex;
        align-items: center;
        gap: 12px;
        /* padding-left: 5px;  */
    }

    .logo-armada img {
        height: 45px;
        width: auto;
    }

    .logo-iso img {
        height: 50px;
        width: auto;
    }

    .main-navbar {
        height: 56px;
        position: sticky;
        top: 0;
        z-index: 1030;
        background: #fff;
    }


</style>