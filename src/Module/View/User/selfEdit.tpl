<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <h1>
                <i class="fa fa-users"></i>
                {{ t('userEdit') }}
            </h1>
        </div>
        <div class="heading-elements">
            <div class="heading-btn-group">
                <a href="#" data-form-submit="form" data-form-param="apply" class="btn btn-icon-block btn-link-success">
                    <i class="fa fa-save"></i>
                    <span>{{ t('apply') }}</span>
                </a>
                <a href="#" data-form-submit="form" data-form-param="save" class="btn btn-icon-block btn-link-success">
                    <i class="fa fa-save"></i>
                    <span>{{ t('save') }}</span>
                </a>
                <a href="#" class="btn btn-icon-block btn-link-danger app-history-back">
                    <i class="fa fa-remove"></i>
                    <span>{{ t('cancel') }}</span>
                </a>
            </div>
        </div>
        <div class="heading-elements-toggle">
            <i class="fa fa-ellipsis-h"></i>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="{{ createUrl() }}"><i class="fa fa-home"> </i>Verone</a></li>
            <li><a href="{{ createUrl('User', 'User', 'index') }}">{{ t('users') }}</a></li>
            <li class="active"><a href="{{ createUrl('User', 'User', 'selfEdit') }}">{{ t('userEdit') }}</a></li>
        </ul>
    </div>
</div>

<div class="container-fluid">
    <form action="<?php echo $app->createUrl('User', 'User', 'updateSelf'); ?>" method="post" id="form" class="form-validation">
        <input type="hidden" name="id" value="{{ $user->getId() }}" />
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ t('basicInformations') }}</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="email" class="control-label">{{ t('emailAddress') }}</label>
                            <input class="form-control required" type="text" id="email" name="email" value="{{ $user->getEmail() }}" />
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">{{ t('userPersonalInformations') }}</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="firstName" class="control-label">{{ t('firstName') }}</label>
                            <input class="form-control" type="text" id="firstName" name="firstName" value="{{ $user->getFirstName() }}" />
                        </div>
                        <div class="form-group">
                            <label for="lastName" class="control-label">{{ t('lastName') }}</label>
                            <input class="form-control" type="text" id="lastName" name="lastName" value="{{ $user->getLastName() }}" />
                        </div>
                        <div class="form-group">
                            <label for="phone" class="control-label">{{ t('phoneNumber') }}</label>
                            <input class="form-control" type="text" id="phone" name="phone" value="{{ $user->getPhone() }}" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ t('password') }}</div>
                    <div class="panel-body">
                        <div style="padding:40px 0 60px;text-align:center">
                            <button type="button" class="btn btn-primary btn-change-password"data-toggle="modal" data-target="#password-chage-modal"><i class="fa fa-lock"></i> Zmiana hasła</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="password-chage-modal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ t('close') }}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Zmiana hasła</h4>
            </div>
            <div class="modal-body">
                @if $app->request()->query->get('forcePasswordChange')
                    <div class="alert alert-info" role="alert">{{ t('userForcePasswordChangeInfo') }}</div>
                @endif
                <form action="<?php echo $app->createUrl('User', 'User', 'changePassword'); ?>" method="post" id="password-change" class="form-validation">
                    <input type="hidden" name="id" value="{{ $user->getId() }}" />
                    <input type="hidden" name="selfEdit" value="1" />
                    <input type="hidden" name="forcePasswordChange" value="{{ $app->request()->query->get('forcePasswordChange') }}" />
                    <div class="form-group">
                        <label for="password" class="control-label">{{ t('password') }}</label>
                        <input class="form-control required" type="password" id="password" name="password" />
                    </div>
                    <div class="form-group">
                        <label for="passwordRepeat" class="control-label">{{ t('passwordRepeat') }}</label>
                        <input class="form-control required" type="password" id="passwordRepeat" name="passwordRepeat" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ t('close') }}</button>
                <button type="button" class="btn btn-primary" data-form-submit="password-change">{{ t('save') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        APP.FormValidation.bind('password-change', '#passwordRepeat', {
            validate: function(elm, val) {
                if(val != $('#password').val())
                    return false;
            },
            errorText: 'Podane hasła nie są takie same.'
        });
    });
</script>

@if $app->request()->query->get('forcePasswordChange')
<script>
    $(function() {
        $('.btn-change-password').trigger('click');
    });
</script>
@endif
