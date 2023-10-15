<div class="card">
    <div class="card-body">
        <button type="button" class="btn btn-primary m-1" style="float: right;" data-bs-toggle="modal" data-bs-target="#modal"><i class="ti ti-plus"></i> Tambah </button>
        <h5 class="card-title fw-semibold mb-4">Data Pengguna</h5>
        <div class="card-body">
            <table id="table" class="table table-borderless" style="width: 100%;">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">No</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">NIS</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Role</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Nama</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Email</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Profile</h6>
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
                <h5 class="modal-title">Form Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form id="form" method="post" autocomplete="off" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id" />
                        <input type="hidden" name="rt" value="<?php echo time();?>" />
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash() ?>">
                        <div class="row">
                            <div class="mb-3 col-sm-6">
                                <label for="role" class="form-label">Role</label>
                                <select id="role" name="role" class="form-control" required></select>
                            </div>
                            <div class="mb-3 col-sm-6">
                                <label for="nis" class="form-label">Nomor Induk</label>
                                <input type="text" class="form-control" id="nis" name="nis" required placeholder="nomor induk" minlength="6" maxlength="15" pattern="[0-9\s]{6,15}" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-sm-6">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" required placeholder="nama lengkap" minlength="4" maxlength="25" pattern="[a-zA-Z\s]{4,25}" />
                            </div>
                            <div class="mb-3 col-sm-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="email" minlength="5" maxlength="25" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-sm-6">
                                <label for="password" class="form-label">Kata Sandi</label>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="kata sandi" minlength="8" maxlength="10" />
                            </div>
                            <div class="mb-3 col-sm-6">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="" disabled selected> Pilih Status </option>
                                    <option value="1"> Aktif </option>
                                    <option value="0"> Blokir </option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="profile" class="form-label">Profile</label>
                            <input type="file" class="form-control" id="profile" name="profile" required accept="image/*" />
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
            role = $("#role"),
            modal = $("#modal");

        table = $("#table").DataTable({
            serverside: true,
            ajax: {
                type: "get",
                url: "master/users/list",
            },
            language: {
                zeroRecords: "<center> No Data Avalibale </center>",
            },
            responsive: "true",
        });

        async function data_role() {
            await $.get("master/role/data").done((res, xhr, status) => {
                if (res && res.status) {
                    const data = res.data;
                    role.empty();
                    role.append(`<option value='' selected disabled> Pilih Role </option>`);
                    $.each(data, function (index, val) {
                        role.append(`<option value='${val.id}'>${val.name}</option>`);
                    });
                }
            });
        }
        data_role();

        form.submit(function (event) {
            event.preventDefault();
            const data = new FormData(this);
            let id = $("#id").val();
            id === "" ? (url = "master/users/create") : (url = "master/users/update");
            if (confirm("Are you sure you want to proceed with this input? Please confirm.")) {
                $.ajax({
                    url: url,
                    type: "post",
                    data: data,
                    processData: false,
                    contentType: false,
                }).done((res, xhr, status) => {
                    if (res && res.status) {
                        notify(res.message, "success");
                        table.ajax.reload();
                        modal.modal("hide");
                        clear();
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

            $.post("master/users/read", { id: id })
                .done((res, xhr, status) => {
                    if (res && res.status) {
                        const data = res.data;
                        modal.modal("show");
                        $("#id").val(data.id);
                        $("#nis").val(data.nis);
                        $("#name").val(data.name);
                        $("#email").val(data.email);
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
                $.post("master/users/delete", { id: id })
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
