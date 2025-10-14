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

    <?= $this->renderSection('css') ?>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Navbar, header, dll -->
    <?= $this->include('layouts/navbar') ?>

    <!-- Konten halaman -->
    <main class="flex-fill" style="margin-top: 130px;">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <?= $this->include('layouts/footer') ?>

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