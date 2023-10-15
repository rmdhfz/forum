<div class="card">
    <div class="card-body">
        <button type="button" class="btn btn-primary m-1" style="float: right;" data-bs-toggle="modal" data-bs-target="#modal"><i class="ti ti-plus"></i> Tambah </button>
        <h5 class="card-title fw-semibold mb-4">Data Kategori Konten</h5>
        <div class="card-body">
            <table id="table" class="table table-borderless" style="width: 100%;">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">No</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Nama</h6>
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
                <h5 class="modal-title">Form Kategori Konten</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form id="form" method="post" autocomplete="off">
                        <input type="hidden" name="id" id="id" />
                        <input type="hidden" name="rt" value="<?php echo time();?>" />
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="kategori konten" minlength="4" maxlength="25" pattern="[a-zA-Z\s]{4,25}" />
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
            modal = $("#modal");

        table = $("#table").DataTable({
            serverside: true,
            ajax: {
                type: "get",
                url: "content/category/list",
            },
            language: {
                zeroRecords: "<center> No Data Avalibale </center>",
            },
            responsive: "true",
        });

        form.submit(function (event) {
            event.preventDefault();
            const data = $(this).serialize();
            let id = $("#id").val();
            id === "" ? (url = "content/category/create") : (url = "content/category/update");
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

            $.post("content/category/read", { id: id })
                .done((res, xhr, status) => {
                    if (res && res.status) {
                        const data = res.data;
                        modal.modal("show");
                        $("#id").val(data.id);
                        $("#name").val(data.name);
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
                $.post("content/category/delete", { id: id })
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
