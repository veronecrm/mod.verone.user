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
            <li class="active"><a href="{{ createUrl('User', 'User', 'edit', [ 'id' => $user->getId() ]) }}">{{ t('userEdit') }}</a></li>
        </ul>
    </div>
</div>

<div class="container-fluid">
    <form action="<?php echo $app->createUrl('User', 'User', 'update'); ?>" method="post" id="form" class="form-validation" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $user->getId() }}" />
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ t('basicInformations') }}</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="username" class="control-label">{{ t('username') }}</label>
                            <input class="form-control required" type="text" id="username" name="username" autofocus="" value="{{ $user->getUsername() }}" />
                        </div>
                        <div class="form-group">
                            <label for="email" class="control-label">{{ t('emailAddress') }}</label>
                            <input class="form-control required" type="text" id="email" name="email" value="{{ $user->getEmail() }}" />
                        </div>
                        <div class="form-group">
                            <label for="group" class="control-label">{{ t('userGroupName') }}</label>
                            <select name="group" id="group" class="form-control required">
                                @foreach $groups
                                    <option value="{{ $item->getId() }}"<?php echo ($user->getGroup() == $item->getId() ? ' selected="selected"' : ''); ?>><?php echo str_repeat('&ndash;&nbsp;', $item->depth); ?>{{ $item->getName() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="active" class="control-label">{{ t('userActive') }}</label>
                            <select class="form-control" id="active" name="active">
                                <option value="1"<?php echo ($user->getActive() == 1 ? ' selected="selected"' : ''); ?>>{{ t('syes') }}</option>
                                <option value="0"<?php echo ($user->getActive() == 0 ? ' selected="selected"' : ''); ?>>{{ t('sno') }}</option>
                            </select>
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
                            <button type="button" class="btn btn-primary btn-change-password"data-toggle="modal" data-target="#password-chage-modal"><i class="fa fa-lock"></i> {{ t('userPasswordChange') }}</button>
                        </div>
                        <div class="form-group">
                            <label for="forcePasswordChange" class="control-label">{{ t('userForcePasswordChange') }}</label>
                            <select class="form-control" id="forcePasswordChange" name="forcePasswordChange">
                                <option value="1"<?php echo ($user->getForcePasswordChange() == 1 ? ' selected="selected"' : ''); ?>>{{ t('syes') }}</option>
                                <option value="0"<?php echo ($user->getForcePasswordChange() == 0 ? ' selected="selected"' : ''); ?>>{{ t('sno') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">{{ t('userAvatar') }}</div>
                    <div class="panel-body">
                        <div class="user-avatar" style="background-image:url('{{ $user->getAvatarUrl() }}');margin:20px auto;width:64px;height:64px;padding:0;"></div>
                        <div class="hidden" id="avatar-change-container">
                            <div class="form-group">
                                <label for="avatarSource" class="control-label">{{ t('userImageSource') }}</label>
                                <select name="avatarSource" class="form-control">
                                    <option value="1">{{ t('userGenerateByCRM') }}</option>
                                    <option value="2">{{ t('userUploadImage') }}</option>
                                    <option value="3">{{ t('userInsertImageURL') }}</option>
                                </select>
                            </div>
                            <div class="form-group avatar-source-all hidden avatar-source-2">
                                <label for="avatar-image" class="control-label">{{ t('userUploadImage') }}</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <label class="btn btn-primary btn-file">{{ t('selectFile') }}&hellip; <input type="file" name="avatarImage" id="avatar-image" accept="image/*" class="action-focus" /></label>
                                    </span>
                                    <input type="text" class="form-control" id="avatar-image-name" readonly>
                                </div>
                            </div>
                            <div class="form-group avatar-source-all hidden avatar-source-3">
                                <label for="avatarImageUrl" class="control-label">{{ t('userInsertImageURL') }}</label>
                                <input class="form-control action-focus" type="text" id="avatarImageUrl" name="avatarImageUrl" />
                            </div>
                        </div>
                        <div style="padding:40px 0 0;text-align:center" id="avatar-change-btn">
                            <input type="hidden" name="avatarChangeActive" value="0" />
                            <button type="button" class="btn btn-primary"><i class="fa fa-image"></i> {{ t('userAvatarChange') }}</button>
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
                <h4 class="modal-title">{{ t('userPasswordChange') }}</h4>
            </div>
            <div class="modal-body">
                @if $app->request()->query->get('forcePasswordChange')
                    <div class="alert alert-info" role="alert">{{ t('userForcePasswordChangeInfo') }}</div>
                @endif
                <form action="<?php echo $app->createUrl('User', 'User', 'changePassword'); ?>" method="post" id="password-change" class="form-validation">
                    <input type="hidden" name="id" value="{{ $user->getId() }}" />
                    <input type="hidden" name="selfEdit" value="0" />
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
<script>
    $(function() {
        $('#avatar-change-btn').click(function() {
            $('#avatar-change-container').removeClass('hidden');
            $(this).addClass('hidden');
            $('input[name=avatarChangeActive]').val(1);
        });

        $('select[name=avatarSource]').change(function() {
            $('.avatar-source-all').addClass('hidden');
            $('.avatar-source-' + $(this).val()).removeClass('hidden').find('.action-focus').trigger('focus').trigger('click');
        });

        APP.FileInput.create({
            inputTarget: '#avatar-image',
            statusTarget: '#avatar-image-name'
        });
    });
</script>
