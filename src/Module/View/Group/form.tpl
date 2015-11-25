<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <h1>
                <i class="fa fa-street-view"></i>
                <?php echo $app->t($group->getId() ? 'userGroupEdit' : 'userGroupNew'); ?>
            </h1>
        </div>
        <div class="heading-elements">
            <div class="heading-btn-group">
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
            @if $group->getId()
                <li class="active"><a href="{{ createUrl('User', 'Group', 'edit', [ 'id' => $group->getId() ]) }}">{{ t('userGroupEdit') }}</a></li>
            @else
                <li class="active"><a href="{{ createUrl('User', 'Group', 'add') }}">{{ t('userGroupNew') }}</a></li>
            @endif
        </ul>
    </div>
</div>

<div class="container-fluid">
    <form action="<?php echo $app->createUrl('User', 'Group', $group->getId() ? 'update' : 'save'); ?>" method="post" id="form" class="form-validation">
        <input type="hidden" name="id" value="<?php echo $group->getId(); ?>" />
        <div class="row">
            @if ! $group->getId()
            <div class="col-md-12">
            @else
            <div class="col-md-6">
            @endif
                <div class="panel panel-default">
                    <div class="panel-heading">{{ t('basicInformations') }}</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="name" class="control-label">{{ t('userGroupName') }}</label>
                            <input class="form-control required" type="text" id="name" name="name" autofocus="" value="{{ $group->getName() }}" />
                        </div>
                        <div class="form-group">
                            <label for="announcement" class="control-label">{{ t('userGroupAnnouncement') }}</label>
                            <textarea class="form-control auto-grow" id="announcement" name="announcement">{{ $group->getAnnouncement() }}</textarea>
                        </div>
                        @if ! $group->getId()
                            <div class="form-group">
                                <label for="parent" class="control-label">{{ t('userGroupName') }}</label>
                                <select name="parent" id="parent" class="form-control">
                                    <option value="0">{{ t('userGroupMain') }}</option>
                                    @foreach $groups
                                        <option value="{{ $item->getId() }}"><?php echo str_repeat('&ndash;&nbsp;', $item->depth); ?>{{ $item->getName() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @if $group->getId()
                <div class="col-md-6"><div class="panel panel-default">
                    <div class="panel-heading">{{ t('userGroupUsersList') }}</div>
                    <div class="panel-body">
                        <table class="table table-default table-clicked-rows">
                            <thead>
                                <tr>
                                    <th>{{ t('username') }}</th>
                                    <th>{{ t('nameAndSurname') }}</th>
                                    <th>{{ t('emailAddress') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach $users
                                    <tr data-row-click-target="<?=$app->createUrl('User', 'User', 'edit', [ 'id' => $item->getId() ]); ?>">
                                        <td>{{ $item->getUsername() }}</td>
                                        <td>{{ $item->getName() }}</td>
                                        <td>{{ $item->getEmail() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </form>
</div>
