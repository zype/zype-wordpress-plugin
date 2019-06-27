<?php get_header(); ?>
<div class="content-wrap user-action-wrap user-profile-wrap">
    <div class="main-heading inner-heading">
        <strong class="title text-uppercase">My Account | Change Password</strong>
    </div>
    <div class="user-wrap">
        <div class="holder-main">
            <div class="user-profile-wrap__content">
                <div class="user-profile-wrap__block">
                    <ul class="user-action user-profile-wrap__menu">
                        <li class="profile">
                            <a href="<?php zype_url('profile'); ?>/">
                                <span class="ico"><i class="fa fa-fw fa-user"></i></span>
                                <span class="text">Profile</span>
                            </a>
                        </li>
                        <li class="change-password active">
                            <a href="<?php zype_url('profile'); ?>/change-password/">
                                <span class="ico"><i class="fa fa-fw fa-lock"></i></span>
                                <span class="text">Change Password</span>
                            </a>
                        </li>
                        <li class="change-credit-card">
                            <a href="<?php zype_url('profile'); ?>/change-credit-card/">
                                <span class="ico"><i class="fa fa-fw fa-credit-card"></i></span>
                                <span class="text">Change Credit Card</span>
                            </a>
                        </li>
                        <li class="subscription">
                            <a href="<?php zype_url('profile'); ?>/subscription/">
                                <span class="ico"><i class="fa fa-fw fa-dollar"></i></span>
                                <span class="text">Subscription</span>
                            </a>
                        </li>
                        <li class="link-device">
                            <a href="<?php zype_url('device_link'); ?>/">
                                <span class="ico"><i class="fa fa-fw fa-link"></i></span>
                                <span class="text">Link Device</span>
                            </a>
                        </li>
                        <li class="log-out">
                            <a href="<?php echo home_url(\Config::get('zype.logout_url')) ?>/">
                                <span class="ico"><i class="fa fa-fw fa-sign-out"></i></span>
                                <span class="text">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="user-profile-wrap__block">
                    <form action="<?php echo admin_url('admin-ajax.php'); ?>" class="user-form nice-form validate-form"
                          method="post" id="change-password-form">
                        <input type="hidden" name="action" value="zype_update_password">
                        <div class="success-section"></div>
                        <div class="error-section"></div>
                        <div class="password-note user-profile-wrap__note">
                            Must contain at least 8 characters including a number, an uppercase letter, and a lowercase letter.
                        </div>
                        <div class="field-section">
                            <div class="zype_flash_messages"></div>
                            <div class="form-group user-profile-wrap__field">
                                <input type="password" class="form-control user-profile-wrap__inp" id="c-password"
                                       name="current_password" placeholder="Current password">
                            </div>
                            <div class="form-group user-profile-wrap__field">
                                <input type="password" class="form-control user-profile-wrap__inp" id="n-password"
                                       name="new_password" placeholder="New password">
                            </div>
                            <div class="form-group user-profile-wrap__field">
                                <input type="password" class="form-control user-profile-wrap__inp" id="cn-password"
                                       name="new_password_confirmation" placeholder="Confirm new password">
                            </div>
                            <button type="submit" class="btn btn-primary user-profile-wrap__button zype-custom-button">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function ($) {
        var zypeAjaxFormPath = "form#change-password-form";
        var zypeErrorSectionFormPath = [zypeAjaxFormPath, ".error-section"].join(' ');
        var zypeSuccessSectionFormPath = [zypeAjaxFormPath, ".success-section"].join(' ');
        var zypeSubmitButtonFormPath = [zypeAjaxFormPath, ".zype-custom-button"].join(' ');
        var zypeSpinnerFormPath      = [zypeAjaxFormPath, ".zype-spinner"].join(' ');

        var zypeCurrentPasswordInput = [zypeAjaxFormPath, "#c-password"].join(' ');
        var zypeNewPasswordInput = [zypeAjaxFormPath, "#n-password"].join(' ');
        var zypeConfirmNewPasswordInput = [zypeAjaxFormPath, "#cn-password"].join(' ');

        var zypeAjaxForm = $(zypeAjaxFormPath);

        zypeAjaxForm.ajaxForm({
            beforeSubmit: function() {
                $(zypeSubmitButtonFormPath).attr('disabled', true);
                $(zypeSubmitButtonFormPath).append('<div class="zype-spinner"></div>');
                $(zypeErrorSectionFormPath).html("");
                $(zypeSuccessSectionFormPath).html("");
            },
            success: function(data) {
                $(zypeSpinnerFormPath).remove();
                $(zypeSubmitButtonFormPath).attr('disabled', false);
                data = $.parseJSON(data);
                if (data.errors.length > 0) {
                    $(zypeErrorSectionFormPath).html(data.errors.join("<br>"));
                } else {
                    $(zypeSuccessSectionFormPath).html('Password changed successfully');
                    $(zypeCurrentPasswordInput).val('');
                    $(zypeNewPasswordInput).val('');
                    $(zypeConfirmNewPasswordInput).val('');
                }
            }
        })
    })
</script>

<?php get_footer(); ?>
