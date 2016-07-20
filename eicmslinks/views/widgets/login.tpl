<!-- MODULE EicmsLinks Block login -->
<form class="box" id="login_form" method="post" action="{$link->getPageLink('authentication')}">
<h3 class="page-subheading">{l s='Already registered?' mod='eicmslinks'}</h3>
<div class="form_content clearfix">
    <div class="form-group">
        <label for="email">{l s='Email address' mod='eicmslinks'}</label>
        <input type="email" value="" name="email" id="email" data-validate="isEmail" class="is_required validate account_input form-control">
    </div>
    <div class="form-group">
        <label for="passwd">{l s='Password' mod='eicmslinks'}</label>
        <input type="password" value="" name="passwd" id="passwd" data-validate="isPasswd" class="is_required validate account_input form-control">
    </div>
    <p class="lost_password form-group"><a rel="nofollow" title="Recover your forgotten password" href="{$link->getPageLink('password')}">{l s='Forgot your password' mod='eicmslinks'}</a></p>
    <p class="submit">
        <input type="hidden" value="my-account" name="back" class="hidden">						
        <button class="button btn btn-default button-medium" name="SubmitLogin" id="SubmitLogin" type="submit">
                <span>
                        <i class="icon-lock left"></i>
                        {l s='Sign in' mod='eicmslinks'}
                </span>
        </button>
    </p>
</div>
</form>
<!-- /MODULE EicmsLinks Block login -->