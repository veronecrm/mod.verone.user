<?php $app->assetter()->load('jstree'); ?>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <h1>
                <i class="fa fa-street-view"></i>
                {{ t('userGroups') }}
            </h1>
        </div>
        <div class="heading-elements">
            <div class="heading-btn-group">
                <a href="{{ createUrl('User', 'Group', 'add') }}" class="btn btn-icon-block btn-link-success">
                    <i class="fa fa-plus"></i>
                    <span>{{ t('userGroupNew') }}</span>
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
            <li class="active"><a href="{{ createUrl('User', 'Group', 'index') }}">{{ t('userGroups') }}</a></li>
        </ul>
        <ul class="breadcrumb-elements">
            <li><a href="#" data-toggle="modal" data-target="#groups-update-tree"><i class="fa fa-tree"></i> {{ t('userGroupChangeTreeView') }}</a></li>
        </ul>
        <div class="breadcrumb-elements-toggle">
            <i class="fa fa-unsorted"></i>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-default table-clicked-rows table-responsive">
                <thead>
                    <tr>
                        <th class="text-center span-1"><input type="checkbox" name="select-all" data-select-all="input_group" /></th>
                        <th>{{ t('userGroupName') }}</th>
                        <th class="text-right">{{ t('action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach $groups
                        <tr data-row-click-target="<?php echo $app->createUrl('User', 'Group', 'edit', [ 'id' => $item->getId() ]); ?>">
                            <td class="text-center hidden-xs"><input type="checkbox" name="input_group" value="{{ $item->getId() }}" /></td>
                            <td data-th="{{ t('userGroupName') }}"><?php echo str_repeat('&ndash;&nbsp;&nbsp;&nbsp;', $item->depth); ?>{{ $item->getName() }}</td>
                            <td data-th="{{ t('action') }}" class="app-click-prevent">
                                <div class="actions-box">
                                    <div class="btn-group right">
                                        <a href="<?php echo $app->createUrl('User', 'Group', 'edit', [ 'id' => $item->getId() ]); ?>" class="btn btn-default btn-xs btn-main-action" title="{{ t('edit') }}"><i class="fa fa-pencil"></i></a>
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" >
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu with-headline right">
                                            <li class="headline">{{ t('moreOptions') }}</li>
                                            <li><a href="<?php echo $app->createUrl('User', 'Group', 'edit', [ 'id' => $item->getId() ]); ?>" title="{{ t('edit') }}"><i class="fa fa-pencil"></i> {{ t('edit') }}</a></li>
                                            @if $item->isLast
                                                <li role="separator" class="divider"></li>
                                                <li class="item-danger"><a href="#" data-toggle="modal" data-target="#group-delete" data-href="<?php echo $app->createUrl('User', 'Group', 'delete', [ 'id' => $item->getId() ]); ?>" title="{{ t('delete') }}"><i class="fa fa-remove danger"></i> {{ t('delete') }}</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="group-delete" tabindex="-1" role="dialog" aria-labelledby="group-delete-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content modal-danger">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ t('close') }}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="group-delete-modal-label">{{ t('userGroupDeleteConfirmationHeader') }}</h4>
            </div>
            <div class="modal-body">
                {{ t('userGroupDeleteConfirmationContent') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ t('close') }}</button>
                <a href="#" class="btn btn-danger">{{ t('syes') }}</a>
            </div>
        </div>
    </div>
</div>
<script>
    var groupsList = [];

    $(document).ready(function() {
        $('#users-groups-tree').jstree({
            'core': {
                 'check_callback': true,
                 'multiple'      : false
             },
            'plugins': [ 'wholerow', 'dnd' ]
        });

        $('#users-groups-tree').jstree(true).open_all();
        $('#users-groups-tree').on('move_node.jstree', function() {
            window.groupsList = [];
            $('#users-groups-tree').jstree(true).open_all();
            $('#users-groups-tree input').remove();
            var list = createList($('#users-groups-tree > ul'), 0);

            for(var i in list)
            {
                $('#users-groups-tree').append('<input type="hidden" name="parents[' + list[i].id + ']" value="' + list[i].parent + '" />');
            }
        });

        $('#group-delete').on('show.bs.modal', function (event) {
            $(this).find('.modal-footer a').attr('href', $(event.relatedTarget).attr('data-href'));
        });
    });

    function createList(target, parent) {
        target.find('> li').each(function() {
            window.groupsList.push({
                'id'    : $(this).attr('id').split('-')[1],
                'parent': parent
            });

            createList($(this).find('> ul'), $(this).attr('id').split('-')[1]);
        });

        return window.groupsList;
    };
</script>

<div class="modal fade" id="groups-update-tree" tabindex="-1" role="dialog" aria-labelledby="groups-update-tree-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ t('close') }}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="groups-update-tree-modal-label">{{ t('userGroupChangeTreeView') }}</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo $app->createUrl('User', 'Group', 'updateTree'); ?>" method="post" id="groups-tree-save">
                    <div id="users-groups-tree">
                        <ul>
                            <?php
                                $lastDepth  = 0;
                                
                                foreach($groups as $i => $item)
                                {
                                    echo '<li id="group-'.$item->getId().'"><span>'.$item->getId().' - '.$item->getName().'</span>';
                                    
                                    if(isset($groups[$i+1]->depth) && $groups[$i+1]->depth > $lastDepth)
                                    {
                                        echo  '<ul>';
                                        $lastDepth = $groups[$i+1]->depth;
                                    }
                                    elseif(isset($groups[$i+1]->depth) && $groups[$i+1]->depth < $lastDepth)
                                    {
                                        echo  str_repeat('</ul>', $lastDepth - $groups[$i+1]->depth);
                                        $lastDepth = $groups[$i+1]->depth;
                                    }
                                    else
                                    {
                                        echo  '</li>';
                                    }
                                }
                            ?>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ t('close') }}</button>
                <a href="#" class="btn btn-primary" data-form-submit="groups-tree-save">{{ t('save') }}</a>
            </div>
        </div>
    </div>
</div>
