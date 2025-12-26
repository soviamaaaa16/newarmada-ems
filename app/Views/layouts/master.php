<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?= $title ?? 'Armada EMS' ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/icoii.png') ?>" />

    <!-- Font Awesome & Google Fonts -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" />

    <!-- Styles -->
    <link href="<?= base_url('css/styles.css') ?>" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="<?= base_url('assets/pdfjs/build/pdf.min.js') ?>"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <?= $this->renderSection('css') ?>
</head>

<body class="app-root">

    <?= $this->include('layouts/navbar') ?>

    <div class="app-wrapper">

        <aside id="sidebar" class="bg-light border-end d-none d-md-block">
            <?= $this->include('layouts/sidebar') ?>
        </aside>

        <?php
            $isEmptyState = empty($folders) && empty($files);
        ?>


        <!-- CONTENT -->
        <main class="flex-fill main-content <?= $isEmptyState ? 'is-empty' : '' ?>">
            <?= $this->renderSection('content') ?>
        </main>

    </div>

    <?= $this->include('layouts/footer') ?>

    <!-- Navbar, header, dll -->

    <!-- Sidebar -->

    <!-- Konten halaman -->
    <!-- <main class="flex-fill">
        <?= $this->renderSection('content') ?>
    </main> -->

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="<?= base_url('js/scripts.js') ?>"></script>
    <?php if (session('success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "<?php echo session('success')['message']; ?>"
            });
        </script>
    <?php endif; ?>

    <?php if (session('errors')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'error',
                text: "<?php echo session('errors')['message']; ?>"
            });
        </script>
    <?php endif; ?>
    <?= $this->renderSection('js') ?>
</body>

</html>