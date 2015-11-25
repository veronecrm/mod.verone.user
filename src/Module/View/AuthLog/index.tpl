<?php $app->assetter()->load('datetimepicker'); ?>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <h1>
                <i class="fa fa-user-secret"></i>
                {{ t('userAuthLog') }}
            </h1>
        </div>
        <div class="heading-elements">
            <div class="heading-btn-group">
                <a href="#" class="btn btn-icon-block btn-link-default" data-toggle="collapse" data-target="#adv-search-form">
                    <i class="fa fa-search"></i>
                    <span>{{ t('search') }}</span>
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
            <li class="active"><a href="{{ createUrl('User', 'AuthLog', 'index') }}">{{ t('userAuthLog') }}</a></li>
        </ul>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php
                $request = $app->request();
            ?>
            <form action="" method="get" id="adv-search-form"<?php echo ($request->get('search') != 1 ? ' class="collapse"' : ' class="collapse in"'); ?>>
                <input type="hidden" name="mod" value="User" />
                <input type="hidden" name="cnt" value="AuthLog" />
                <input type="hidden" name="act" value="index" />
                <div class="panel panel-default">
                    <div class="panel-heading">{{ t('advancedSearch') }}</div>
                    <div class="panel-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="userId" class="control-label">{{ t('user') }}</label>
                                        <select name="userId" id="userId" class="form-control">
                                            <option value="">-</option>
                                            @foreach $users
                                                <option value="{{ $item->getId() }}"<?php echo ($request->get('userId') == $item->getId() ? ' selected="selected"' : ''); ?>>{{ $item->getName() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="loginFrom" class="control-label">{{ t('userSearchLoginBetween') }}</label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon">{{ t('from') }}</span>
                                                        <input class="form-control" type="text" id="loginFrom" name="loginFrom" value="{{ $request->get('loginFrom', date('Y-m-d', strtotime('now - 1 month'))) }}" />
                                                        <span class="input-group-addon calendar-open">
                                                            <span class="fa fa-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">&nbsp;</label>
                                                    <div class="input-group date">
                                                        <span class="input-group-addon">{{ t('to') }}</span>
                                                        <input class="form-control" type="text" id="loginTo" name="loginTo" value="{{ $request->get('loginTo', date('Y-m-d')) }}" />
                                                        <span class="input-group-addon calendar-open">
                                                            <span class="fa fa-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="#" class="btn btn-success" data-form-submit="adv-search-form" data-form-param="search">{{ t('search') }}</a>
                                <a href="<?php echo $app->createUrl('User', 'AuthLog', 'index'); ?>" class="btn"><i class="fa fa-remove"></i> {{ t('cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <table class="table table-default">
                <thead>
                    <tr>
                        <th>{{ t('userAuthLogSessionId') }}</th>
                        <th>{{ t('user') }}</th>
                        <th>{{ t('userIPAddress') }}</th>
                        <th>{{ t('userUserAgent') }}</th>
                        <th>{{ t('userLoginDate') }}</th>
                        <th>{{ t('userLogoutDate') }}</th>
                        <th>{{ t('userAuthLogLogoutType') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($elements as $item): ?>
                        <tr>
                            <td>{{ $item->getSessionId() }}</td>
                            <td>{{ $item->getUserId() }}</td>
                            <td>{{ $item->getIp() }}</td>
                            <td>{{ $item->getUserAgent() }}</td>
                            <td><?php echo $item->getLoginDate() ? date('Y-m-d H:i:s', $item->getLoginDate()) : $app->t('userAuthLogLogoutType0'); ?></td>
                            <td><?php echo $item->getLogoutDate() ? date('Y-m-d H:i:s', $item->getLogoutDate()) : $app->t('userAuthLogLogoutType0'); ?></td>
                            <td><span <?php echo $item->getLogoutType() == 3 ? ' style="color:#D9534F;"' : ''; ?>><?php echo $item->getLogoutType() ? $app->t('userAuthLogLogoutType'.$item->getLogoutType()) : ''; ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div>{{ $pagination|raw }}</div>
            <div class="clearfix"></div>
            <p>
                <strong>{{ t('userAuthLogLogoutType1') }}</strong> - {{ t('userAuthLogLogoutType1MoreInfo') }}<br />
                <strong>{{ t('userAuthLogLogoutType2') }}</strong> - {{ t('userAuthLogLogoutType2MoreInfo') }}<br />
                <strong>{{ t('userAuthLogLogoutType0') }}</strong> - {{ t('userAuthLogLogoutType0MoreInfo') }}<br />
                <span style="color:#D9534F;"><strong>{{ t('userAuthLogLogoutType3') }}</strong> - {{ t('userAuthLogLogoutType3MoreInfo') }}</span>
            </p>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#loginFrom, #loginTo')
            .datetimepicker({format:'YYYY-MM-DD', defaultDate:'<?php echo date('Y-m-d'); ?>'})
            .parent()
            .find('.input-group-addon.calendar-open')
            .click(function() {
                $(this).parent().find('input').trigger('focus');
            });
    });
</script>
