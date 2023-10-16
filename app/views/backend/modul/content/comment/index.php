<div class="card">
    <div class="card-body">
        <button type="button" class="btn btn-primary m-1" style="float: right;" data-bs-toggle="modal" data-bs-target="#modal"><i class="ti ti-plus"></i> Tambah</button>
        <h5 class="card-title fw-semibold mb-4">Data Postingan</h5>
        <div class="card-body">
            <table id="table" class="table table-borderless" style="width: 100%;">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">No</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">User</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Comment</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Pukul</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Opsi</h6>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" id="modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Komentar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form id="form" method="post" autocomplete="off">
                        <input type="hidden" name="id" id="id" />
                        <input type="hidden" name="rt" value="<?php echo time();?>" />
                        <div class="mb-3">
                            <label for="user" class="form-label">User</label>
                            <select id="user" name="user" class="form-control" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="post" class="form-label">Post</label>
                            <select id="post" name="post" class="form-control" required></select>
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Komentar</label>
                            <textarea id="comment" name="comment" class="form-control" required></textarea>
                        </div>
                        <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" hidden></button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="$('#submit').click();">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        let table,
            form = $("#form"),
            user = $("#user"),
            post = $("#post"),
            modal = $("#modal");

        table = $("#table").DataTable({
            serverside: true,
            ajax: {
                type: "get",
                url: "content/comment/list",
            },
            language: {
                zeroRecords: "<center> No Data Avalibale </center>",
            },
            responsive: "true",
        });

        async function data_users() {
            await $.get("master/users/data").done((res, xhr, status) => {
                if (res && res.status) {
                    const data = res.data;
                    user.empty();
                    user.append(`<option value='' selected disabled> Pilih Pengguna </option>`);
                    $.each(data, function (index, val) {
                        user.append(`<option value='${val.id}'>${val.name}</option>`);
                    });
                }
            });
        }
        data_users();

        async function data_post() {
            await $.get("content/post/data").done((res, xhr, status) => {
                if (res && res.status) {
                    const data = res.data;
                    post.empty();
                    post.append(`<option value='' selected disabled> Pilih Postingan </option>`);
                    $.each(data, function (index, val) {
                        post.append(`<option value='${val.id}'>${val.title}</option>`);
                    });
                }
            });
        }
        data_post();

        form.submit(function (event) {
            event.preventDefault();
            const data = $(this).serialize();
            let id = $("#id").val();
            id === "" ? (url = "content/comment/create") : (url = "content/comment/update");
            if (confirm("Are you sure you want to proceed with this input? Please confirm.")) {
                $.post(url, data)
                    .done((res, xhr, status) => {
                        notify(res.message, "success");
                        table.ajax.reload();
                        modal.modal("hide");
                        clear();
                    })
                    .fail((xhr, res, err) => {
                        table.ajax.reload();
                        modal.modal("hide");
                        clear();
                        if (xhr.status == 400 || xhr.status == 404 || xhr.status == 500) {
                            notify(xhr.responseJSON.message, "error");
                        }
                    });
            }
        });

        table.on("click", "#edit", function (event) {
            event.preventDefault();
            const id = $(this).data("id");
            if (!id) {
                alert("error: failed get id from table");
            }

            $.post("content/comment/read", { id: id })
                .done((res, xhr, status) => {
                    if (res && res.status) {
                        const data = res.data;
                        modal.modal("show");
                        $("#id").val(data.id);
                        $("#user").val(data.user);
                        $("#post").val(data.post);
                        $("#comment").text(data.comment);
                    }
                })
                .fail((xhr, res, err) => {
                    table.ajax.reload();
                    modal.modal("hide");
                    clear();
                    if (xhr.status == 400 || xhr.status == 404 || xhr.status == 500) {
                        notify(xhr.responseJSON.message, "error");
                    }
                });
        });

        table.on("click", "#delete", function (event) {
            event.preventDefault();
            const id = $(this).data("id");
            if (!id) {
                alert("error: failed get id from table");
            }
            if (confirm("Apakah Anda yakin ingin menghapus data ini ?")) {
                $.post("content/comment/delete", { id: id })
                    .done((res, xhr, status) => {
                        if (res && res.status) {
                            notify(res.message, "success");
                            table.ajax.reload();
                            clear();
                        }
                    })
                    .fail((xhr, res, err) => {
                        table.ajax.reload();
                        modal.modal("hide");
                        clear();
                        if (xhr.status == 400 || xhr.status == 404 || xhr.status == 500) {
                            notify(xhr.responseJSON.message, "error");
                        }
                    });
            }
        });
    });
</script>
