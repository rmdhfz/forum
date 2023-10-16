<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <base href="<?php echo base_url(); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Selamat Datang | Forum Open Source - Komunitas Pengembang Terbuka</title>

        <meta
            name="description"
            content="Forum ini adalah wadah komunitas yang didedikasikan untuk mempromosikan dan mendiskusikan pengembangan perangkat lunak sumber terbuka (open source). Kami menyambut semua kalangan, dari pemula hingga ahli, yang tertarik untuk berbagi pengetahuan, ide, dan pengalaman terkait perangkat lunak open source."
        />
        <meta name="keywords" content="forum, programming forum, forum programming" />
        <meta name="author" content="Hafiz Ramadhan" />

        <meta property="og:title" content="Forum Open Source - Komunitas Pengembang Terbuka" />
        <meta property="og:description" content="Bergabunglah dengan komunitas pengembang open source, bagikan ide, dan pelajari lebih banyak tentang perangkat lunak sumber terbuka di Forum Open Source kami." />
        <meta property="og:image" content="assets/images/favicon.ico" />
        <meta property="og:url" content="/" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Forum Open Source - Komunitas Pengembang Terbuka" />
        <meta name="twitter:description" content="Bergabunglah dengan komunitas pengembang open source, bagikan ide, dan pelajari lebih banyak tentang perangkat lunak sumber terbuka di Forum Open Source kami." />
        <meta name="twitter:image" content="assets/images/favicon.ico" />

        <link rel="canonical" href="<?php echo base_url(); ?>" />
        <link rel="shortcut icon" type="image/png" href="assets/images/favicon.ico" />
        <link rel="icon" type="image/png" href="assets/images/favicon.ico" />

        <!-- bootstrap 5.0.2 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />

        <!-- font awesome 6.0.0 free -->
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"
            integrity="sha512-yFjZbTYRCJodnuyGlsKamNE/LlEaEAxSUDe5+u61mV8zzqJVFOH7TnULE2/PP/l5vKWpUNnF4VGVkXh3MjgLsg=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        ></script>

        <!-- bootstrap bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

        <!-- jquery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

        <script type="text/javascript">
            $.ajaxSetup({
                beforeSend: function (xhr, settings) {
                    xhr.setRequestHeader("<?php echo $this->security->get_csrf_token_name(); ?>", "<?php echo $this->security->get_csrf_hash() ?>");
                    if (settings.data) {
                        settings.data += "&<?= $this->security->get_csrf_token_name() ?>=" + "<?php echo $this->security->get_csrf_hash() ?>";
                    } else {
                        settings.data = "<?= $this->security->get_csrf_token_name() ?>=" + "<?php echo $this->security->get_csrf_hash() ?>";
                    }
                },
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
                    <?php
                    if (session('is_login')) { ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $post->title; ?></h5>
                            <hr />
                            <p class="card-text"><?php echo $post->content; ?></p><hr>
                        </div> 
                        <div class="container">
                            <div class="row">
                                <h5>Komentar</h5>
                                <div class="col-sm-2">
                                    <img draggable="false" loading="lazy" src="<?php echo session('user_profile') ?>" width="75" height="95" class="rounded-circle" alt="profile">
                                </div>
                                <div class="col-sm-8">
                                    <form id="form-comment" autocomplete="off" method="post">
                                        <input type="hidden" name="rt" id="rt" value="<?php echo time(); ?>">
                                        <input type="hidden" name="post_id" value="<?php echo $post->id; ?>">
                                        <div class="form-group">
                                            <textarea id="comment" name="comment" class="form-control" placeholder="komentar kamu" required></textarea>
                                        </div>
                                        <button type="submit" name="submit" id="submit" hidden="1"></button>
                                    </form>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-flat btn-primary" onclick="$('#submit').click()">Submit</button>
                                </div>
                            </div>
                        </div> <br>
                        <div class="container">
                            <?php  
                                if ($comment != null) {
                                    foreach ($comment as $com) { ?>
                                        <div class="container">
                                            <div class="card mb-3">
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <img draggable="false" loading="lazy" src="<?php echo $com->profile; ?>" width="75" height="95" class="rounded-circle" alt="profile">
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="card-body">
                                                            <div class="d-flex w-100 justify-content-between">
                                                                <h5 class="card-title"><?php echo $com->user; ?></h5>
                                                                <small><?php echo sometime_ago(strtotime($com->created_at)); ?></small>
                                                            </div>
                                                            <p class="card-text"><?php echo $com->comment; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-sm-4 mt-4">
                    <h5>Pengumuman Terbaru</h5>
                    <hr />
                    <div id="announcement"></div>
                </div>
                <div class="row">
                    <div class="offset-sm-8 col-sm-4 mt-4">
                        <h5>Topik Terbaru</h5>
                        <hr />
                        <div id="newtopics"></div>
                        <div class="mt-2">
                            <small>SMK Nusantara &copy; 2023</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#formlogin").submit(function (event) {
                    event.preventDefault();
                    $.post("verify", $(this).serialize()).done((res, xhr, status) => {
                        if (res && res.status) {
                            window.location = res.data.redirect;
                        }
                    });
                });

                async function announcement() {
                    await $.get("api/announcement").done((res, xhr, status) => {
                        if (res && res.status) {
                            const data = res.data;
                            $.each(data, function (index, val) {
                                if (val.id !== null) {
                                    $("#announcement").append(`
                                    <div class="card">
                                      <div class="card-body">
                                        <h5 class="card-title">${val.title}</h5> <hr>
                                        
                                        <small> ${val.created_by} - ${val.created_at} </small> <br> <br>

                                        <p class="card-text">${val.content}</p> <hr>
                                        <a href="post/${val.id}/${val.title.replace(/ /g, "-").toLowerCase()}" class="btn btn-primary" style="float: right;"> 
                                            Baca Pengumuman
                                        </a>
                                        <p>${val.views} tayangan</p>
                                      </div>
                                    </div>
                                `);
                                }
                            });
                        }
                    });
                }
                announcement();

                async function newtopics() {
                    await $.get("api/content/newtopics").done((res, xhr, status) => {
                        if (res && res.status) {
                            const data = res.data;
                            $.each(data, function (index, val) {
                                if (val.id !== null) {
                                    $("#newtopics").append(`
                                    <div class="card">
                                      <div class="card-body">
                                        <h5 class="card-title">${val.title}</h5> <hr>
                                        
                                        <small> ${val.created_by} - ${val.created_at} </small> <br> <br>

                                        <p class="card-text">${val.content}</p> <hr>
                                        <a href="post/${val.id}/${val.title.replace(/ /g, "-").toLowerCase()}" class="btn btn-primary" style="float: right;"> 
                                            Komentar
                                        </a>
                                        <p>${val.views} tayangan</p>
                                      </div>
                                    </div>
                                `);
                                }
                            });
                        }
                    });
                }
                newtopics();

                $("#form-comment").submit(function(event) {
                    event.preventDefault();
                    const data = $(this).serialize();
                    $.post('post/comment', data).done((res,xhr,status) => {
                        if (res && res.status) {
                            alert("Komentar berhasil ditambahkan");
                            setTimeout(() => {
                                window.location.reload(0);
                            }, 500);
                        }
                    })
                });
            });
        </script>
    </body>
</html>
