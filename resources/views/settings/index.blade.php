@extends('layouts.settings')

@section('content')
    <script type="application/javascript">
        window.routes = @json($routes)
    </script>
    <div id="settings-spa"></div>
@endsection