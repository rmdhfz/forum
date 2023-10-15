<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <base href="<?php echo base_url(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang | Forum Open Source - Komunitas Pengembang Terbuka</title>
    
    <meta name="description" content="Forum ini adalah wadah komunitas yang didedikasikan untuk mempromosikan dan mendiskusikan pengembangan perangkat lunak sumber terbuka (open source). Kami menyambut semua kalangan, dari pemula hingga ahli, yang tertarik untuk berbagi pengetahuan, ide, dan pengalaman terkait perangkat lunak open source.">
    <meta name="keywords" content="forum, programming forum, forum programming">
    <meta name="author" content="Hafiz Ramadhan">

    <meta property="og:title" content="Forum Open Source - Komunitas Pengembang Terbuka">
    <meta property="og:description" content="Bergabunglah dengan komunitas pengembang open source, bagikan ide, dan pelajari lebih banyak tentang perangkat lunak sumber terbuka di Forum Open Source kami.">
    <meta property="og:image" content="<?php echo base_url('logo.jpg') ?>">
    <meta property="og:url" content="<?php echo base_url(); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Forum Open Source - Komunitas Pengembang Terbuka">
    <meta name="twitter:description" content="Bergabunglah dengan komunitas pengembang open source, bagikan ide, dan pelajari lebih banyak tentang perangkat lunak sumber terbuka di Forum Open Source kami.">
    <meta name="twitter:image" content="<?php echo base_url('logo.jpg') ?>">

    <link rel="canonical" href="<?php echo base_url(); ?>">
    <link rel="icon" type="image/png" href="favicon.png">

    <!-- bootstrap 5.0.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- font awesome 6.0.0 free -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js" integrity="sha512-yFjZbTYRCJodnuyGlsKamNE/LlEaEAxSUDe5+u61mV8zzqJVFOH7TnULE2/PP/l5vKWpUNnF4VGVkXh3MjgLsg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- bootstrap bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                xhr.setRequestHeader('<?php echo $this->security->get_csrf_token_name(); ?>', "<?php echo $this->security->get_csrf_hash() ?>");
                if (settings.data) {
                    settings.data += '&<?= $this->security->get_csrf_token_name() ?>=' + "<?php echo $this->security->get_csrf_hash() ?>";
                } else {
                    settings.data = '<?= $this->security->get_csrf_token_name() ?>=' + "<?php echo $this->security->get_csrf_hash() ?>";
                }
            }
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <a class="navbar-brand" href="<?php echo base_url(); ?>"><b> Forum Open Source </b></a>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-sm-8 mt-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">
                        Forum Terbuka | 
                        <button type="button" class="btn btn-flat btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modallogin">
                            <i class="fas fa-sign-in-alt" aria-hidden="true"></i> Masuk
                        </button>
                    </h5>
                    <hr>
                    <p class="card-text">Selamat datang kembali di Forum Terbuka.</p>
                  </div>
                </div>
            </div>
            <div class="col-sm-4 mt-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Pengumuman</h5><hr>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                  </div>
                </div>
            </div>
            <div class="col-sm-8"></div>
            <div class="col-sm-4 mt-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Topik Terbaru</h5><hr>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                  </div>
                </div>
            </div>
            <div class="col-sm-8"></div>
            <div class="col-sm-4 mt-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Bagian Iklan</h5><hr>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                  </div>
                </div>
                <div class="mt-2">
                    <small>SMK Nusantara &copy; 2023</small>
                </div>
            </div>
        </div>
    </div>

    <!-- modal login -->
    <div class="modal fade" id="modallogin" tabindex="-1" role="dialog" aria-labelledby="modallogin" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Masuk Forum</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="container">
                <form id="formlogin" autocomplete="off" method="post">
                    <input type="hidden" name="rt" id="rt" value="<?php echo time(); ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="email kamu" minlength="4" maxlength="35" />
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="kata sandi kamu" minlength="4" maxlength="35" />
                    </div>
                    <button type="submit" id="loginbtn" value="1" hidden></button>
                </form>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" onclick="$('#loginbtn').click()">Login Sekarang</button>
          </div>
        </div>
      </div>
    </div>
    <!-- modal login -->
    <script type="text/javascript">
        $(document).ready(function() {
            $("#formlogin").submit(function(event) {
                event.preventDefault();
                $.post('verify', $(this).serialize()).done((res,xhr,status) => {
                    if (res && res.status) {
                        window.location = res.data.redirect;
                    }
                })
            });
        });
    </script>
</body>
</html>