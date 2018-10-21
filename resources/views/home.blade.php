@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ trans('auth.Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ trans('auth.loginSuccess') }}

                        <h2>Присоединить аккаунты соц сетей</h2>
                        <div class="social-networks-accounts">
                            Убраны привязки к соц сетям, функциона остался, но более не используется
                        </div>
                    <h2>PUSH-уведомления</h2>
                    <div id="push-on" class="btn btn-primary">Включить уведомления</div>
                    <div id="push-off" class="btn btn-primary">Отключить уведомления</div>
                    <div id="info-push"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('serviceworker/firebase.js') }}" defer></script>
<script src="{{ asset('js/push.js') }}" defer></script>
@endsection
