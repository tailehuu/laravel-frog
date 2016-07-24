@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $path }}</div>
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <td>Name</td>
                            <td class="text-right">Size</td>
                        </tr>
                        @foreach($files as $file)
                        <tr>
                            <td>
                                @if($file['isFile'])
                                    <a href="/download?id={{ $file['id'] }}">{{ $file['name'] }}</a>
                                @else
                                    <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> &nbsp; <a href="/?id={{ $file['id'] }}">{{ $file['name'] }}</a>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($file['isFile'])
                                    {{ $file['size'] }} bytes
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
