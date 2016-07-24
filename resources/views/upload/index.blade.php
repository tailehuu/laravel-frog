@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Upload</div>
                <div class="panel-body">
                    <form method="POST" action="/upload" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            @if(Session::has('status'))
                                <label for="inputFile" class="text-info">{{ Session::get('status') }}</label>
                            @endif
                            <input type="file" name="inputFile" id="inputFile">
                            <p class="help-block">Support <strong>zip</strong> file only.</p>
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
