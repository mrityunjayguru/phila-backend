<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{ config('adminlte.name', 'CitySight') }} @if(@$page_title) - {{$page_title}} @endif</title>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <h2>Hello, <strong>{{$template_data->user->name}}</strong></h2>
				<p>Please use: <b>{{$template_data->code}}</b> for recover your account</p>
            </div>
        </div>
    </body>
</html>
