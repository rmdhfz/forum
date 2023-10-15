<script src="assets/js/signature_pad.min.js"></script>
<style type="text/css">
    canvas#signature-pad {
        background: #fff;
        width: 100%;
        height: 100%;
    }
</style>
<div class="card">
    <div class="card-body">
        <div class="container">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <form id="change-profile" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="rt" value="<?php echo time(); ?>">
                            <?php
                                $employee_profile = session('employee_profile');
                                if ($employee_profile == null) {
                                    $employee_profile = base_url('assets/images/profile/user-1.jpg');
                                }
                            ?>
                            <center>
                                <img id="profileimg" loading="lazy" draggable="false" src="<?php echo $employee_profile; ?>" alt="profile" class="img-fluid" width="350px" height="450px" />
                                <input type="file" id="profile-picture" name="profile" class="form-control mt-2" accept="image/*">
                            </center>
                            <div class="d-flex justify-content-between">
                                <button id="upload" name="upload" value="1" class="btn btn-sm btn-primary mt-3" disabled>
                                    <i class="ti ti-upload"></i> &nbsp; Change Profile
                                </button>
                                <a id="changepwd" name="changepwd" href="javascript:void(0)" class="btn btn-sm btn-warning mt-3 ms-3" data-bs-toggle="modal" data-bs-target="#modal">
                                    <i class="ti ti-password"></i> &nbsp; Change Password
                                </a> <br>
                            </div>
                        </form>
                        <div class="form-check form-switch">
                          <label class="form-check-label mt-5 mb-2" for="2fa"> Enable 2FA Authentication</label>
                          <?php $is_2fa = session('user_is2fa'); ?>
                          <input class="form-check-input mt-5 mb-2" type="checkbox" id="2fa" name="2fa" <?php echo $is_2fa == 0 ? "" : "checked"; ?>> <br>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="container">
                            <div class="row mt-3">
                                <h4 class="col-sm-6">Personal Information</h4>
                                <a href="javascript:void(0)" id="uploadesign" class="btn col-sm-6 red" data-bs-toggle="modal" data-bs-target="#modalesign" style="float: right;">
                                    <i class="ti ti-upload" aria-hidden="true"></i>
                                    Upload E-Sign
                                </a> 
                            </div> <hr>
                            <form id="form-profile" class="ms-1" method="post" autocomplete="off">
                                <input type="hidden" name="rt" value="<?php echo time(); ?>">
                                <div class="mb-3">
                                    <label for="nip">NIP</label>
                                    <input type="text" id="nip" name="nip" class="form-control" value="<?php echo session('employee_nip'); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" class="form-control" value="<?php echo session('employee_name'); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="gender">Gender</label>
                                    <select id="gender" name="gender" class="form-control" required>
                                        <option value="" disabled> Select Gender </option>  
                                        <option value="1" <?php echo session('employee_gender') == 1 ? 'selected' : ''; ?>> Male </option>  
                                        <option value="0" <?php echo session('employee_gender') == 0 ? 'selected' : ''; ?>> Female </option>  
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" value="<?php echo encrypt_data_display(session('employee_email')); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo encrypt_data_display(session('employee_phone')); ?>">
                                </div>
                                  <small>
                                      Note: This action will log you out.
                                  </small>
                                <button type="submit" id="submit" name="submit" value="1" class="btn btn-primary mt-3" style="float: right;"> 
                                    <i class="ti ti-link" aria-hidden="true"></i>
                                    Save Changes 
                                </button>
                            </form>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" id="modal" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form Update Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <form id="form-change-password" method="post" autocomplete="off">
            <input type="hidden" name="rt" value="<?php echo time(); ?>">
            <div class="mb-3">
              <label for="oldpwd" class="form-label">Old Password</label>
              <input type="password" class="form-control" id="oldpwd" name="oldpwd" required placeholder="old password" autofocus="true">
            </div>
            <div class="row">
                <div class="mb-3 col-sm-6">
                  <label for="newpwd" class="form-label">New Password</label>
                  <input type="password" class="form-control" id="newpwd" name="newpwd" required placeholder="new password">
                  <div id="resultcheckpassword"></div>
                </div>
                <div class="mb-3 col-sm-6">
                  <label for="confirmpwd" class="form-label">Confirm Password</label>
                  <input type="password" class="form-control" id="confirmpwd" name="confirmpwd" required placeholder="confirm password">
                  <div id="resultmatchpwd"></div>
                </div>
            </div>
            <small>
                <ul class="ms-3">
                    <li>- contains symbol</li>
                    <li>- at least 8 characters</li>
                    <li>- contains lower and uppercase</li>
                </ul>
            </small>
            <button type="submit" id="btnsubmitnewpwd" name="submit" value="submit" class="btn btn-primary" hidden></button>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="submitnewpwd" onclick="$('#btnsubmitnewpwd').click();" disabled>Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" id="modalesign" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form Upload E-Sign</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <form id="form-esign" method="post" autocomplete="off">
            <input type="hidden" name="rt" value="<?php echo time(); ?>">
            <div class="container">
                <div class="mb-3">
                  <label for="esign" class="form-label">E-Sign</label>
                  <?php 
                    $employee_esign = session('employee_esign');
                    if ($employee_esign) { ?>
                        <img id="previewesign" alt="esign" loading="lazy" draggable="false" style="width: 400px; height: 200px;" src="<?php echo DEFAULT_PATH_UPLOAD.session('employee_company_name').'/'.'esign/'.$employee_esign ?>?_=<?php echo time(); ?>">
                        <canvas id="signature-pad" width="400" height="200" hidden></canvas> 
                    <?php } else { ?>
                        <canvas id="signature-pad" width="400" height="200"></canvas> 
                    <?php } ?> <br>
                  <a href="javascript:void(0)" id="clearesign" style="float: right;"><span> Clear signature</span></a>
                </div>
            </div>
            <button type="submit" id="submitesign" name="submit" value="submit" class="btn btn-primary" hidden></button>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="submitnewpwd" onclick="$('#submitesign').click();">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        
        let profile = $("#profile-picture"), profileimg = $("#profileimg"), upload = $("#upload"), fa2 = $("#2fa"), formchangeprofile = $("#change-profile"), formprofile = $("#form-profile"), formchangepwd = $("#form-change-password"), newpwd = $("#newpwd"), confirmPwd = $("#confirmpwd"), resultcheckpwd = $("#resultcheckpassword"), submitnewpwd = $("#submitnewpwd, #btnsubmitnewpwd"), resultmatchpwd = $("#resultmatchpwd");

        profile.on('change', function() {
            upload.removeAttr('disabled');
            profileimg.removeAttr('src');
            if (this.files) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    profileimg.attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        let previousCheckedState = fa2.is(':checked');

        fa2.on('change', function() {
            if (fa2.is(':checked')) {
                if (confirm('Are you sure you want to enable 2FA Authentication ?')) {
                    enable_2fa();
                    // Perbarui keadaan kotak centang sebelumnya
                    previousCheckedState = true;
                } else {
                    // Kembalikan kotak centang ke keadaan sebelumnya
                    fa2.prop('checked', previousCheckedState);
                }
            } else {
                if (confirm('Are you sure you want to disable 2FA Authentication ?')) {
                    disable_2fa();
                    // Perbarui keadaan kotak centang sebelumnya
                    previousCheckedState = false;
                } else {
                    // Kembalikan kotak centang ke keadaan sebelumnya
                    fa2.prop('checked', previousCheckedState);
                }
            }
        });

        function enable_2fa()
        {
            $.post('profile/enable_2fa').done((res,xhr,status) => {
                notify('2FA has been successfully enabled.', 'success');
                $.get('ping');
                location.reload();
            })
        }

        function disable_2fa()
        {
            $.post('profile/disable_2fa').done((res,xhr,status) => {
                notify('2FA has been successfully disabled.', 'success');
                $.get('ping');
                location.reload();
            })
        }

        formchangeprofile.submit(function(event) {
            event.preventDefault();
            const data = new FormData(this);
            if (confirm("Are you sure you want to proceed with this input? Please confirm.")) {
                $.ajax({
                    url: "profile/changeimg",
                    type: 'post',
                    data: data,
                    processData: false,
                    contentType: false,
                }).done((res,xhr,status) => {
                    alert('thank you');
                    $.get('ping');
                    setTimeout(() => {
                        location.reload();
                    }, 200);
                })
            }
        });

        formprofile.submit(function(event) {
            event.preventDefault();
            const data = formprofile.serialize();
            if (confirm("Are you sure you want to proceed with this input? Please confirm.")) {
                $.post('profile/update', data).done((res,xhr,status) => {
                    if (res && res.status) {
                        notify(res.message, "success");
                        $.get('ping');
                        location.reload();
                    }
                })
            }
        });

        function check_new_password() {
            if (newpwd.val() !== confirmPwd.val()) {
                submitnewpwd.attr('disabled', true);
                resultmatchpwd.html(`<small class='text-danger'> Password not match </span>`);
            }else{
                resultmatchpwd.html('');
                submitnewpwd.removeAttr('disabled');
            }
        }

        newpwd.on('keyup', function(event) {
            event.preventDefault();
            const pwd = $(this).val();
            if (!pwd) {
                newpwd.val('');
                resultcheckpwd.html('');
                return;
            }

            $.post('internal/user/check/pwd', {password: pwd}).done((res,xhr,status) => {
                if (res && res.status) {
                    resultcheckpwd.html('');
                }
            }).fail((xhr,res,err) => {
                if (xhr.status === 400) {
                    resultcheckpwd.html(`<small class='text-danger'>${xhr.responseJSON.message}</span>`);
                } else if (xhr.status === 404) {
                    alert('Not Found: ' + xhr.responseJSON.message);
                } else if (xhr.status === 500) {
                    alert('Internal Server Error: ' + xhr.responseJSON.message);
                } else {
                    alert('Error ' + xhr.status + ': ' + xhr.responseJSON.message);
                }
            })
        });

        confirmPwd.on('keyup', function(event) {
            event.preventDefault();
            if (!confirmPwd.val()) {
                resultmatchpwd.html('');
                return;
            }
            check_new_password();
        });

        formchangepwd.submit(function(event) {
            event.preventDefault();
            check_new_password();

            const data = formchangepwd.serialize();
            if (confirm("Are you sure you want to proceed with this input? Please confirm.")) {
                $.post('profile/changepwd', data).done((res,xhr,status) => {
                    notify('Profile Data has been successfully updated.', 'success');
                    $.get('ping');
                    location.reload();
                })
            }
        });

        /*e-sign*/
        let signatured = $("#signatured"),
            formesign = $("#form-esign"),
            modalesign = $("#modalesign"),
            canvas = $("#signature-pad"),
            previewesign = $("#previewesign");
        
        let signaturePad;
        modalesign.on('shown.bs.modal', function(event) {
            event.preventDefault();
            let parentWidth = $(canvas).parent().outerWidth(),
                parentHeight = $(canvas).parent().outerHeight();
            
            canvas.attr("width", parentWidth+'px')
                  .attr("height", parentHeight+'px');

            signaturePad = new SignaturePad(canvas[0], {
                backgroundColor: 'rgb(255, 255, 255)'
            });
        });

        modalesign.on('hidden.bs.modal', function(event) {
            event.preventDefault();
            <?php 
            if (session('employee_esign')) { ?>
                canvas.attr('hidden', true);
                previewesign.removeAttr('hidden');
            <?php } ?>
            signaturePad.clear();
        });

        document.getElementById("clearesign").addEventListener('click', function(){
            clearesign_esign();
        })

        function clearesign_esign()
        {
            <?php 
            if (session('employee_esign')) { ?>
                previewesign.attr('hidden', true);
                canvas.removeAttr('hidden');
            <?php } ?>
            signaturePad.clear();
            signatured.text('');
            signatured.val('');
        }

        formesign.submit(function(event) {
            event.preventDefault();
            const esigndata = signaturePad.toDataURL();
            if (confirm("Are you sure you want to proceed with this input? Please confirm.")) {
                $.post('profile/upload/esign', {esigndata: esigndata, rt: "<?php echo time(); ?>"}).done((res,xhr,status) => {
                    notify("Successfully upload esign", "success");
                    modalesign.modal('hide');
                    $.get('ping');
                    location.reload();
                }).fail((xhr,res,err) => {
                    modalesign.modal('hide');
                    if (xhr.status == 400 || xhr.status == 404 || xhr.status == 500) {
                        notify(xhr.responseJSON.message, "error");    
                    }
                })
            }
        });
        /*e-sign*/
    });
</script>
