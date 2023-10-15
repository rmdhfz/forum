<div class="card">
    <div class="card-body">
        <button type="button" class="btn btn-primary m-1" style="float: right;" data-bs-toggle="modal" data-bs-target="#modal"><i class="ti ti-plus"></i> Tambah </button>
        <h5 class="card-title fw-semibold mb-4">Data Akses Pengguna</h5>
        <div class="card-body">
            <table id="table" class="table table-borderless dt table-responsive nowrap" style="width: 100%;">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">No</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">User</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Create</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Read</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Update</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Delete</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Approve</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Reject</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Upload</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Download</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Open Ticket</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Hold Ticket</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Close Ticket</h6>
                        </th>
                        <th class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">Option</h6>
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
                <h5 class="modal-title">Form Akses Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form id="form" method="post" autocomplete="off">
                        <input type="hidden" name="rt" value="<?php echo time();?>" />
                        <input type="hidden" name="id" id="id" />

                        <div class="mb-3">
                            <label for="users" class="form-label">User</label>
                            <select id="users" name="users" class="form-control" required></select>
                        </div>

                        <div class="d-flex">
                            <label for="basic" class="form-label">Basic</label>
                            <div class="d-flex flex-grow-1 justify-content-center mb-3 form-check">
                                <input class="form-check-input me-1" type="checkbox" id="create" name="create" />
                                <label class="form-check-label" for="create">
                                    Create
                                </label>
                            </div>
                            <div class="d-flex flex-grow-1 justify-content-center mb-3 form-check">
                                <input class="form-check-input me-1" type="checkbox" id="read" name="read" checked />
                                <label class="form-check-label" for="read">
                                    Read
                                </label>
                            </div>
                            <div class="d-flex flex-grow-1 justify-content-center mb-3 form-check">
                                <input class="form-check-input me-1" type="checkbox" id="update" name="update" />
                                <label class="form-check-label" for="update">
                                    Update
                                </label>
                            </div>
                            <div class="d-flex flex-grow-1 justify-content-center mb-3 form-check">
                                <input class="form-check-input me-1" type="checkbox" id="deleted" name="deleted" />
                                <label class="form-check-label" for="deleted">
                                    Delete
                                </label>
                            </div>
                        </div>

                        <div class="d-flex">
                            <label for="basic" class="form-label">Managerial</label>
                            <div class="d-flex flex-grow-1 justify-content-center mb-3 form-check">
                                <input class="form-check-input me-1" type="checkbox" id="approve" name="approve" />
                                <label class="form-check-label" for="approve">
                                    Approve
                                </label>
                            </div>
                            <div class="d-flex flex-grow-1 justify-content-center mb-3 form-check">
                                <input class="form-check-input me-1" type="checkbox" id="reject" name="reject" />
                                <label class="form-check-label" for="reject">
                                    Reject
                                </label>
                            </div>
                            <div class="d-flex flex-grow-1 justify-content-center mb-3 form-check">
                                <input class="form-check-input me-1" type="checkbox" id="upload" name="upload" />
                                <label class="form-check-label" for="upload">
                                    Upload
                                </label>
                            </div>
                            <div class="d-flex flex-grow-1 justify-content-center mb-3 form-check">
                                <input class="form-check-input me-1" type="checkbox" id="download" name="download" />
                                <label class="form-check-label" for="download">
                                    Download
                                </label>
                            </div>
                        </div>
                        <div class="d-flex">
                            <label for="basic" class="form-label">Support</label>
                            <div class="d-flex flex-grow-1 justify-content-center mb-3 form-check">
                                <input class="form-check-input me-1" type="checkbox" id="openticket" name="openticket" />
                                <label class="form-check-label" for="openticket">
                                    Open Ticket
                                </label>
                            </div>
                            <div class="d-flex flex-grow-1 justify-content-center mb-3 form-check">
                                <input class="form-check-input me-1" type="checkbox" id="holdticket" name="holdticket" />
                                <label class="form-check-label" for="holdticket">
                                    Hold Ticket
                                </label>
                            </div>
                            <div class="d-flex flex-grow-1 justify-content-center mb-3 form-check">
                                <input class="form-check-input me-1" type="checkbox" id="closeticket" name="closeticket" />
                                <label class="form-check-label" for="closeticket">
                                    Close Ticket
                                </label>
                            </div>
                        </div>
                        <button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary" hidden></button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="$('#submit').click();">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        let table,
            modal = $("#modal"),
            form = $("#form"),
            users = $("#users");

        table = $("#table").DataTable({
            serverside: true,
            responsive: true,
            ajax: {
                type: "get",
                url: "master/access/list",
            },
            language: {
                zeroRecords: "<center> No Data Avalibale </center>",
            },
        });

        async function data_users() {
            await $.get("master/users/data").done((res, xhr, status) => {
                if (res && res.status) {
                    const data = res.data;
                    users.empty();
                    users.append(`<option value='' selected disabled> Select User </option>`);
                    $.each(data, function (index, val) {
                        users.append(`<option value='${val.id}'>${val.name}</option>`);
                    });
                }
            });
        }

        async function main() {
            try {
                await data_users();
            } catch (error) {
                console.error("Terjadi kesalahan:", error);
            }
        }

        main();

        form.submit(function (event) {
            event.preventDefault();
            const data = form.serialize();
            let id = $("#id").val();
            id === "" ? (url = "master/access/create") : (url = "master/access/update");
            console.log(data);
            if (confirm("Are you sure you want to proceed with this input? Please confirm.")) {
                $.post(url, data).done((res, xhr, status) => {
                    notify(res.message, "success");
                    table.ajax.reload();
                    modal.modal("hide");
                    clear();
                });
            }
        });

        table.on("click", "#edit", function (event) {
            event.preventDefault();
            const id = $(this).data("id");
            if (!id) {
                alert("error: failed get id from table");
            }

            $.post("master/access/read", { id: id }).done((res, xhr, status) => {
                if (res && res.status) {
                    const data = res.data;
                    modal.modal("show");
                    $("#id").val(data.id);
                    $("#users").val(data.users_id);
                    $("#create").prop("checked", data.can_create == 1);
                    $("#read").prop("checked", data.can_read == 1);
                    $("#update").prop("checked", data.can_update == 1);
                    $("#deleted").prop("checked", data.can_delete == 1);
                    $("#approve").prop("checked", data.can_approve == 1);
                    $("#reject").prop("checked", data.can_reject == 1);
                    $("#upload").prop("checked", data.can_upload == 1);
                    $("#download").prop("checked", data.can_download == 1);
                    $("#openticket").prop("checked", data.can_open_ticket == 1);
                    $("#holdticket").prop("checked", data.can_hold_ticket == 1);
                    $("#closeticket").prop("checked", data.can_close_ticket == 1);
                }
            });
        });

        table.on("click", "#delete", function (event) {
            event.preventDefault();
            const id = $(this).data("id");
            if (!id) {
                alert("error: failed get id from table");
            }

            if (confirm("Are you sure want to delete this data ?")) {
                $.post("master/access/delete", { id: id }).done((res, xhr, status) => {
                    if (res && res.status) {
                        notify(res.message, "success");
                        table.ajax.reload();
                    }
                });
            }
        });
    });
</script>
