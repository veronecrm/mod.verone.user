<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <h1>
                <i class="fa fa-shield"></i>
                {{ t('userPermission') }}
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
            <li class="active"><a href="{{ createUrl('User', 'Permission', 'index', [ 'module' => $currentModule ]) }}">{{ t('userPermission') }}</a></li>
        </ul>
    </div>
</div>

<div class="with-static-sidebar" role="tabpanel">
    <div class="static-sidebar">
        <div class="list-group">
            @foreach $modules as $module
                <a class="list-group-item <?php echo ($module->getName() == $currentModule ? 'active' : ''); ?>" href="{{ createUrl('User', 'Permission', 'index', [ 'module' => $module->getName() ]) }}">{{ $module->getNameLocale() }}</a>
            @endforeach
        </div>
    </div>
    <div class="content tab-content">
        <form action="<?php echo $app->createUrl('User', 'Permission', 'save'); ?>" method="post" id="form">
            <input type="hidden" name="module" value="{{ $currentModule }}" />
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">{{ t('setPermission') }}</div>
                            <div class="panel-body">
                                <?php echo $generator->generateFor($currentModule); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
