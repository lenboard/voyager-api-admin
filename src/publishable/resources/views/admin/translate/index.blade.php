@extends('voyager::master')

@section('page_title')
    List translates
@endsection

@section('css')

    @include('voyager::compass.includes.styles')

@stop

@section('page_header')

    <h1 class="page-title">
        <i class="voyager-file-text"></i> List translates &nbsp;
    </h1>
    <a class="btn btn-success" href="{{ route('lang.file.upgrade') }}">Обновить изменения в файлах</a>
    <a class="btn btn-primary" href="{{ route('lang.file.export-to-db') }}">Экспортировать переводы в базу</a>
    <a class="btn btn-warning" href="{{ route('lang.file.update-db') }}">Обновить переводы в базе</a>
    <a class="btn btn-danger" href="{{ route('lang.file.import-from-db') }}">Перенести переводы из базы</a>

@endsection

@section('content')

    <div class="page-content compass container-fluid">
        <div class="row">
            <div class="col-md-12">
                    <ul class="nav nav-tabs">

                        @foreach($languages as $lang)

                            <li class="{{ ($loop->iteration == 1) ? 'active' : '' }}"><a data-toggle="tab" href="#{{ $lang }}"><i class="voyager-world"></i> {{ $lang }}</a></li>

                        @endforeach

                    </ul>
                    <div class="tab-content">

                        @foreach($languages as $lang)

                            <div id="{{ $lang }}" class="tab-pane fade in {{ ($loop->iteration == 1) ? 'active' : '' }}">
                                <div class="row">

                                    <table class="table table-striped dataTable no-footer" role="grid" aria-describedby="table-log_info">
                                        <tr role="row">
                                            <th>File</th>
                                            <th>Actions</th>
                                        </tr>

                                        @foreach($langFiles[ $lang ] as $fileUrl)

                                            <tr role="row">
                                                <td><i class="voyager-data"></i> {{ $fileUrl[ 'uri' ] }}</td>
                                                <td><a href="{{ route('lang.file.form', ['cryptFileName' => $fileUrl[ 'code' ]]) }}">Edit</a></td>
                                            </tr>

                                        @endforeach

                                    </table>

                                </div>
                            </div>

                        @endforeach

                    </div>
            </div>
        </div>
    </div>

@endsection