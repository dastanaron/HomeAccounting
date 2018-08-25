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
                            @if (empty($socialNetwork->social_id))
                                <div class="vk">
                                    <script type="text/javascript" src="//vk.com/js/api/openapi.js?153"></script>
                                    <script type="text/javascript">
                                        VK.init({apiId: 6123763});
                                    </script>

                                    <!-- VK Widget -->
                                    <div id="vk_auth"></div>
                                    <script type="text/javascript">
                                        VK.Widgets.Auth("vk_auth", {"onAuth":function(data) {
                                                axios.post('/callback/vk-authorize', data)
                                                    .then(response=> {
                                                        //console.log(response.data);
                                                        window.location.reload();
                                                    })
                                                    .catch(error => {
                                                        console.error(error);
                                                    });
                                            }
                                        });

                                    </script>
                                </div>
                                @else
                                <div class="vk">
                                    <h4>Аккаунт vk.com присоединен</h4>
                                    <table border="0">
                                        <tr>
                                            <td>Имя/Фамилия:</td><td>{{ $socialNetwork->first_name }} {{ $socialNetwork->last_name }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><img src="{{ $socialNetwork->photo }}" /></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><button id="vkDelete" class="btn btn-primary" data-uuid="{{ $socialNetwork->social_id }}" onclick="deleteAccount(this)">Удалить привязку</button></td>
                                            <script type="text/javascript">

                                                function deleteAccount (elem) {
                                                    let socialId = elem.getAttribute('data-uuid');
                                                    axios.post('/callback/vk-unauthorize', {uid: socialId})
                                                        .then(response=> {
                                                            window.location.reload();
                                                        })
                                                        .catch(error => {
                                                            console.error(error);
                                                        });
                                                };
                                            </script>
                                        </tr>
                                    </table>
                                </div>
                            @endif
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
