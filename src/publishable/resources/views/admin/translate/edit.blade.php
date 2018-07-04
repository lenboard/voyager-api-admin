@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('page_title', 'Edit')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-file-text"></i>
        Edit lang file
    </h1>
    <a class="btn btn-success" id="btn-add-fields" href="">Добавить поля</a>
@endsection

@section('breadcrumbs')

  <ol class="breadcrumb hidden-xs">

      @if(count(Request::segments()) == 1)

          <li class="active"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</li>

      @else

          <li class="active">
              <a href="{{ route('voyager.dashboard')}}"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</a>
          </li>

      @endif
      <?php $breadcrumb_url = url(''); ?>
      @for($i = 1; $i <= 2; $i++)
          <?php $breadcrumb_url .= '/' . Request::segment($i); ?>
          @if(Request::segment($i) != ltrim(route('voyager.dashboard', [], false), '/') && !is_numeric(Request::segment($i)))

              @if($i < count(Request::segments()) & $i > 0 && array_search('database',Request::segments())===false)

                  <li class="active"><a
                              href="{{ $breadcrumb_url }}">{{ ucwords(str_replace('-', ' ', str_replace('_', ' ', Request::segment($i)))) }}</a>
                  </li>

              @else

                  <li>{{ ucwords(str_replace('-', ' ', str_replace('_', ' ', Request::segment($i)))) }}</li>

              @endif
          @endif
      @endfor

  </ol>

@endsection

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form" method="post" class="form-edit-add" action="{{ route('lang.file.update', ['cryptFileName' => $cryptFileName]) }}">

                        {{ method_field("PUT") }}
                        {{ csrf_field() }}

                        <div class="panel-body">

                            @if (count($errors) > 0)

                                <div class="alert alert-danger">
                                    <ul>

                                        @foreach ($errors->all() as $error)

                                            <li>{{ $error }}</li>

                                        @endforeach

                                    </ul>
                                </div>

                            @endif
                            @foreach($formElements as $elementName => $elementValue)

                                    <div class="form-group">
                                        <label for="">{{ $elementName }}</label>
                                        <textarea class="form-control" name="{{ $elementName }}">{{ $elementValue }}</textarea>
                                    </div>

                            @endforeach

                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary save">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-info fade" tabindex="-1" id="form" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-data"></i> Добавление полей</h4>
                </div>
                <div class="modal-body" style="overflow:auto; height:500px">
                    <div id="keysManager" class="col-md-12">
                        <form action="{{ route('lang.file.add.keys', compact('cryptFileName')) }}" method="post">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td><strong>Key</strong></td>
                                        <td><strong>Value</strong></td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(row, index) in rows">

                                        <td><input class="form-control" type="text" :name="`key[${index}]`" v-model="row.key"></td>
                                        <td><input class="form-control" type="text" :name="`value[${index}]`" v-model="row.value"></td>
                                        <td>
                                            <a class="btn btn-danger" v-on:click="removeElement(index);" style="cursor: pointer; text-decoration: none;">-</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div>
                                <a href="#" class="btn btn-primary" @click="addRow">+</a>
                            </div>
                            <button class="btn btn-info" data-file="{{ $cryptFileName }}" v-on:click="save" type="submit">Сохранить</button>
                        </form>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-right" data-dismiss="modal">{{ __('voyager::generic.close') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection
@section('javascript')

    <script>
        $(document).on('click', '#btn-add-fields', function( e ) {
            e.preventDefault();

            $('#form').modal('show');
        });

        initTinyMce();

        var app = new Vue({
            el: "#keysManager",
            data: {
                rows: []
            },
            methods: {
                addRow: function() {
                    var elem = document.createElement('tr');
                    this.rows.push({
                        key: "",
                        value: "",
                    });
                },
                removeElement: function(index) {
                    this.rows.splice(index, 1);
                },
                save: function(e) {
                    e.preventDefault();

                    var form = $(e.target).closest('form');

                    $.ajax({
                        url: $(form).attr('action'),
                        data: $(form).serialize(),
                        async: false,
                        dataType: 'json',
                        method: 'post'
                    }).done(function(data) {
                        $(document).find('#keysManager .error').remove();
                        if (!data.status) {

                             data.errors.forEach(function(item, i, arr) {
                              $('<span style="color: #f00;" class="error">' + item.textError + '</span>').insertAfter($(document).find('#keysManager input[name="' + item.nameInput + '"]'));
                            });
                        } else {
                            $('.panel-body').append(data.html);
                            $('#form').modal('hide');
                            $('#keysManager tbody tr').remove();

                            initTinyMce();
                        }
                    });
                }
            }
        });

        function initTinyMce()
        {
            tinymce.init({
              selector: 'textarea',
              menubar:false,
              forced_root_block : false,
            });
        }
    </script>

@endsection
