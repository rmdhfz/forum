<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <base href="<?php echo base_url(); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Forum Open Source - Komunitas Pengembang Terbuka</title>
        <link rel="shortcut icon" type="image/png" href="assets/images/favicon.ico" />
        <link rel="icon" type="image/png" href="assets/images/favicon.ico" />
        <link rel="stylesheet" href="assets/css/styles.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" />
        <link rel="stylesheet" href="assets/css/bootstrap-toaster.min.css" />

        <script src="assets/libs/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

        <script src="assets/js/offline.min.js"></script>
        <link rel="stylesheet" href="assets/css/offline-theme-chrome.css" />
        <link rel="stylesheet" href="assets/css/offline-language-english.css" />
        <script>
            Offline.options = {
                checkOnLoad: true,
                interceptRequests: true,
                reconnect: {
                    initialDelay: 3,
                    delay: 10
                },
                requests: true
            };

            // Menampilkan pesan Bootstrap jika koneksi hilang
            Offline.on('down', function () {
                const alertContainer = document.getElementById('alertContainer');
                if (!alertContainer) {
                    return;
                }

                const alertHTML = `
                    <div id="alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                    Your Internet Connection is Lost!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                alertContainer.innerHTML = alertHTML;
            });

            // Menyembunyikan pesan Bootstrap jika koneksi kembali
            Offline.on('up', function () {
                const alertContainer = document.getElementById('alertContainer');
                if (alertContainer) {
                    alertContainer.innerHTML = ''; // Hapus pesan jika ada
                }
            });


            function clear() {
                let inputs = document.querySelectorAll("input:not([name='rt']):not([name='<?php echo $this->security->get_csrf_token_name(); ?>']), select");
                inputs.forEach(function (element) {
                    if (element.tagName === "SELECT") {
                        element.selectedIndex = 0;
                    } else {
                        element.value = "";
                    }
                });
            }
            function notify(msg, type){
                if (type === "success") {
                    toastStatus = TOAST_STATUS.SUCCESS;
                }else if (type === "error") {
                    toastStatus = TOAST_STATUS.DANGER;
                }else if (type === "warning") {
                    toastStatus = TOAST_STATUS.WARNING;
                }else if(type === "info"){
                    toastStatus = TOAST_STATUS.INFO;
                }else{
                    toastStatus = TOAST_STATUS.SUCCESS;
                }
                Toast.setTheme(TOAST_THEME.LIGHT);
                let toast = {
                    title: "Information",
                    message: msg,
                    status: toastStatus,
                    timeout: 3500
                }
                Toast.create(toast);
            }
            function format_rupiah(angka, prefix) {
                let number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
            }
            function get_timestamp()
            {
                var now = Date.now(),
                    timestamp = Math.floor(now / 1000);
                    return timestamp.toString();
            }
            $.ajaxSetup({
                beforeSend: function(xhr, settings) {
                    xhr.setRequestHeader('X-Request-Time', get_timestamp());
                    xhr.setRequestHeader('<?php echo $this->security->get_csrf_token_name(); ?>', "<?php echo $this->security->get_csrf_hash() ?>");
                    if (!settings.contentType) {
                        return true;
                    }
                    if (settings.data) {
                        settings.data += '&<?= $this->security->get_csrf_token_name() ?>=' + "<?php echo $this->security->get_csrf_hash() ?>";
                    } else {
                        settings.data = '<?= $this->security->get_csrf_token_name() ?>=' + "<?php echo $this->security->get_csrf_hash() ?>";
                    }
                }
            });
        </script>
        <style>
            .submenu {
                display: none;
                list-style: none;
                padding-left: 20px;
            }
            .form-select {
                background-image: none !important;
            }
            @media only screen and (max-width: 992px) {
                #clock {
                    display: none;
                }
            }
            .red {
                background-color: white;
                color: #d63031;
            }
            .red:hover{
                background-color: #d63031;
                color: white;
            }
            .companyid {
                position: fixed;
                bottom: 15px;
            }
        </style>
    </head>
    <body>
        <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
            <aside class="left-sidebar">
                <div>
                    <div class="brand-logo d-flex align-items-center justify-content-between">
                        <center>
                            <img draggable="false" loading="lazy" class="mt-2 ms-5" src="assets/images/logov1.png" style="width: 150px; height: 150px;" alt="logo" />
                        </center>
                        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                            <i class="ti ti-x fs-8" aria-hidden="true"></i>
                        </div>
                    </div>
                    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                        <ul id="sidebarnav">
                            <li class="nav-small-cap">
                                <center>
                                    <a href="profile">
                                        <?php echo session('user_name') ?>
                                    </a><br />
                                    NIS: <?php echo session('user_nis') ?>
                                </center>
                                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                                <span class="hide-menu">MENU</span>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="dashboard" aria-expanded="false">
                                    <span>
                                        <i class="ti ti-layout-dashboard" aria-hidden="true"></i>
                                    </span>
                                    <span class="hide-menu">Dashboard</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="javascript:void(0)" id="submenu-toggle" onclick="ToggleSubMenu(this)">
                                    <span>
                                        <i class="ti ti-server" aria-hidden="true"></i>
                                    </span>
                                    <span class="hide-menu">Data Master</span>
                                </a>
                                <ul class="submenu" id="submenu">
                                    <li class="sidebar-item ms-3">
                                        <a class="sidebar-link" href="master/users" aria-expanded="false">
                                            <span class="hide-menu">Data Pengguna</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item ms-3">
                                        <a class="sidebar-link" href="master/access" aria-expanded="false">
                                            <span class="hide-menu">Data Akses Pengguna</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="javascript:void(0)" id="submenu-toggle" onclick="ToggleSubMenu(this)">
                                    <span>
                                        <i class="ti ti-clipboard" aria-hidden="true"></i>
                                    </span>
                                    <span class="hide-menu">Data Konten</span>
                                </a>
                                <ul class="submenu" id="submenu">
                                    <li class="sidebar-item ms-3">
                                        <a class="sidebar-link" href="content/category" aria-expanded="false">
                                            <span class="hide-menu">Data Kategori</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item ms-3">
                                        <a class="sidebar-link" href="content/discuss" aria-expanded="false">
                                            <span class="hide-menu">Data Diskusi</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item ms-3">
                                        <a class="sidebar-link" href="content/announcement" aria-expanded="false">
                                            <span class="hide-menu">Data Pengumuman</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="<?php echo base_url('logout');?>" aria-expanded="false">
                                    <span>
                                        <i class="ti ti-logout"></i>
                                    </span>
                                    <span class="hide-menu">Keluar</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>
            <div class="body-wrapper">
                <header class="app-header">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <ul class="navbar-nav">
                            <li class="nav-item d-block d-xl-none">
                                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                                    <i class="ti ti-menu-2"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                                    <div id="clock" style="font-size: 13px;"></div>
                                </a>
                            </li>
                        </ul>
                        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                                <li class="nav-item dropdown">
                                    <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                                        <small><?php echo session('user_name') ?></small> &nbsp; &nbsp; &nbsp;
                                        <?php 
                                        $user_profile = session('user_profile');
                                        if ($user_profile == null) {
                                            $user_profile = base_url('assets/images/profile/user-1.jpg');
                                        }
                                     ?>
                                        <img loading="lazy" draggable="false" src="<?php echo $user_profile; ?>" alt="" width="35" height="35" class="rounded-circle" />
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                                        <div class="message-body">
                                            <a href="profile" class="d-flex align-items-center gap-2 dropdown-item">
                                                <i class="ti ti-user fs-6"></i>
                                                <p class="mb-0 fs-3">Profile Saya</p>
                                            </a>
                                            <a href="<?php echo base_url('logout');?>" class="btn btn-outline-primary mx-3 mt-2 d-block">Keluar</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </header>
                <div class="container-fluid">
                    <nav aria-label="breadcrumb" id="dynamic-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></li>
                            <?php
                                $segments = $this->uri->segment_array(); $currentpath = ''; 
                                foreach ($segments as $segment) { 
                                    $currentpath .= '/' . $segment; 
                                    $segmentName = str_replace('-', ' ', $segment);
                                    $segmentName = ucwords($segmentName); 
                                    if ($segment === end($segments)) { 
                                        echo '<li class="breadcrumb-item active" aria-current="page">' . $segmentName . '</li>'; 
                                    } else { 
                                        echo '<li class="breadcrumb-item"><a href="' . base_url($currentpath) . '">' . $segmentName . '</a></li>'; 
                                    } 
                                } 
                            ?>
                        </ol>
                    </nav>
                    <?php $file .= ".php"; include $file;?>
                    <div class="py-6 px-6 text-center">
                        <center>
                            <small>
                                &copy; 2023. Fahri Rahman<br>
                                SMK Nusantara
                            </small>
                        </center>
                    </div>
                </div>
            </div>
        </div>
        <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/sidebarmenu.js"></script>
        <script src="assets/js/app.min.js"></script>
        <script src="assets/libs/apexcharts/dist/apexcharts.min.js"></script>
        <script src="assets/libs/simplebar/dist/simplebar.js"></script>
        <script src="assets/js/bootstrap-toaster.min.js"></script>
        <script type="text/javascript">
            function ToggleSubMenu(link) {
                const submenu = link.nextElementSibling;
                submenu.style.display = submenu.style.display === "block" ? "none" : "block";
            }
            var H = H || {};
            setInterval(function () {
                var time = H.RealtimeDate();
                $("#clock").html(`<small> ${time} </small>`);
            }, 1e3),
                (H.RealtimeDate = function () {
                    var a = new Date(),
                        b = [];
                    (b[0] = "Januari"),
                        (b[1] = "Februari"),
                        (b[2] = "Maret"),
                        (b[3] = "April"),
                        (b[4] = "Mei"),
                        (b[5] = "Juni"),
                        (b[6] = "Juli"),
                        (b[7] = "Agustus"),
                        (b[8] = "September"),
                        (b[9] = "Oktober"),
                        (b[10] = "November"),
                        (b[11] = "Desember");
                    var currentMonth = b[a.getMonth()],
                        currentYear = a.getFullYear(),
                        currentDate = a.getDate(),
                        c = [];
                    (c[0] = "Minggu"), (c[1] = "Senin"), (c[2] = "Selasa"), (c[3] = "Rabu"), (c[4] = "Kamis"), (c[5] = "Jum'at"), (c[6] = "Sabtu");
                    var currentDay = c[a.getDay()],
                        d = a.getHours(),
                        e = a.getMinutes(),
                        f = a.getSeconds();
                    return currentDay + ", " + currentDate + " " + currentMonth + " " + currentYear + " &sdot; " + (d = (d < 10 ? "0" : "") + d) + " : " + (e = (e < 10 ? "0" : "") + e) + " : " + (f = (f < 10 ? "0" : "") + f);
                });

            $("#modal, .modal").on("hidden.bs.modal", function () {
                clear();
            });

            $(document).ready(function() {
                $("button[data-bs-toggle]").on("click", function () {
                    clear();
                }); 
            });
        </script>
    </body>
</html>
