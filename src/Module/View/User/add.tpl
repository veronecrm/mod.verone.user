<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <h1>
                <i class="fa fa-users"></i>
                {{ t('userNew') }}
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
            <li class="active"><a href="{{ createUrl('User', 'User', 'add') }}">{{ t('userNew') }}</a></li>
        </ul>
    </div>
</div>

<div class="container-fluid">
    <form action="<?php echo $app->createUrl('User', 'User', 'save'); ?>" method="post" id="form" class="form-validation" autocomplete="off">
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
                        <div class="form-group">
                            <label for="password" class="control-label">{{ t('password') }}</label>
                            <input class="form-control required" type="password" id="password" name="password" />
                        </div>
                        <div class="form-group">
                            <label for="passwordRepeat" class="control-label">{{ t('passwordRepeat') }}</label>
                            <input class="form-control required" type="password" id="passwordRepeat" name="passwordRepeat" />
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
            </div>
        </div>
    </form>
</div>

<script>
    $(function() {
        APP.FormValidation.bind('form', '#passwordRepeat', {
            validate: function(elm, val) {
                if(val != $('#password').val())
                    return false;
            },
            errorText: 'Podane hasła nie są takie same.'
        });
    });
</script>
