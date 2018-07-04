@extends('voyager::master')

@section('content')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-categories"></i> Categories
        </h1>
        <a href="/admin/categories/create" class="btn btn-success btn-add-new">
        <i class="voyager-plus"></i> <span>Добавить</span>
        </a>
    </div>
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th class="actions">{{ __('voyager::generic.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($categories as $category)

                                        <tr>
                                            <td>
                                            </td>
                                            <td>
                                                  @if ($category->children()->count() )

                                                      <a href="{{ route('voyager.categories.index', ['id' => $category->id]) }}">{{ $category->name }}</a>

                                                  @else

                                                      {{ $category->name }}

                                                  @endif
                                            </td>
                                            <td>
                                                  {{ $category->slug }}
                                            </td>

                                            <td class="no-sort no-click" id="bread-actions">
                                                <a href="javascript:;" title="Удалить" class="btn btn-sm btn-danger pull-right delete" data-id="{{ $category->id }}" id="delete-{{ $category->id }}">
                                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Удалить</span>
                                                </a>
                                                <a href="/admin/categories/{{ $category->id }}/edit" title="Изменить" class="btn btn-sm btn-primary pull-right edit">
                                                    <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Изменить</span>
                                                </a>
                                                <a href="/admin/categories/{{ $category->id }}" title="Просмотр" class="btn btn-sm btn-warning pull-right view">
                                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">Просмотр</span>
                                                </a>
                                            </td>
                                        </tr>

                                    @endforeach

                                </tbody>
                            </table>

                            {{ $categories->links() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('css')

<link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">

@stop
@section('javascript')

  <script>
      $(function() {
          $('td').on('click', '.delete', function (e) {
              $('#delete_form')[0].action = '{{ route('voyager.categories.destroy', ['id' => '__id']) }}'.replace('__id', $(this).data('id'));
              $('#delete_modal').modal('show');
          });
      });
  </script>

@stop
