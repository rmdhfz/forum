<div class="card">
    <div class="card-body">
        <button type="button" class="btn btn-primary m-1" style="float: right;" data-bs-toggle="modal" data-bs-target="#modal"><i class="ti ti-plus"></i> Tambah </button>
        <h5 class="card-title fw-semibold mb-4">Data Postingan</h5>
        <div class="card-body">
            <table id="table" class="table table-borderless" style="width: 100%;">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">No</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Kategori</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Judul</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Konten</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Status</h6>
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
                <h5 class="modal-title">Form Postingan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form id="form" method="post" autocomplete="off">
                        <input type="hidden" name="id" id="id" />
                        <input type="hidden" name="rt" value="<?php echo time();?>" />
                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori</label>
                            <select id="category" name="category" class="form-control" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="title" name="title" required placeholder="judul konten" minlength="4" maxlength="50" />
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Konten</label>
                            <textarea id="content" name="content" class="form-control" required placeholder="isi konten" style="height: 10rem;"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="" disabled selected> Pilih Status </option>
                                <option value="1"> Publish </option>
                                <option value="0"> Draft </option>
                            </select>
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
            category = $("#category"),
            modal = $("#modal");

        table = $("#table").DataTable({
            serverside: true,
            ajax: {
                type: "get",
                url: "content/post/list",
            },
            language: {
                zeroRecords: "<center> No Data Avalibale </center>",
            },
            responsive: "true",
        });

        async function data_category() {
            await $.get("content/category/data").done((res, xhr, status) => {
                if (res && res.status) {
                    const data = res.data;
                    category.empty();
                    category.append(`<option value='' selected disabled> Pilih Kategori Konten </option>`);
                    $.each(data, function (index, val) {
                        category.append(`<option value='${val.id}'>${val.name}</option>`);
                    });
                }
            });
        }
        data_category();

        form.submit(function (event) {
            event.preventDefault();
            const data = $(this).serialize();
            let id = $("#id").val();
            id === "" ? (url = "content/post/create") : (url = "content/post/update");
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

            $.post("content/post/read", { id: id })
                .done((res, xhr, status) => {
                    if (res && res.status) {
                        const data = res.data;
                        modal.modal("show");
                        $("#id").val(data.id);
                        $("#category").val(data.category);
                        $("#title").val(data.title);
                        $("#content").text(data.content);
                        $("#status").val(data.status);
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
                $.post("content/post/delete", { id: id })
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
