@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Настройки профиля</div>
                    <div class="col-md-12 text-center">
                        <img src="/uploads/avatars/{{$user->avatar}}" style="width: 150px;height: 150px;border-radius: 50%;margin-right: 25px">
                    </div>
                    <div class="card-body">
                        @if(Session::has('message'))
                            <p class="alert alert-info">{{ Session::get('message') }}</p>
                        @endif
                        <form method="POST" action="{{ route('cabinet.profile') }}" enctype="multipart/form-data">
                            @csrf

                            {{ method_field('patch') }}
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right"  >{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" value="{{ old('name') ?: $user->name }}"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name"  autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email') ?: $user->email }}" autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
{{--                            @if (session('status'))--}}
{{--                                <div class="alert alert-success">--}}
{{--                                    {{ session('status') }}--}}
{{--                                </div>--}}
{{--                            @endif--}}
                            <div class="form-group row">
                                <label for="avatar" class="col-md-4 col-form-label text-md-right">Обновления фото профиля</label>

                                <div class="col-md-6">
                                    <input id="avatar" type="file" name="avatar"
                                           class="@error('avatar') is-invalid @enderror">
                                    @if ($errors->has('avatar'))
                                        <span class="alert alert-danger"><strong>{{ $errors->first('avatar') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('password') }}</label>

                                <div class="col-md-6">

                                    <input id="password" type="password" name="password"
                                           class="@error('password') is-invalid @enderror">
                                    @error('password')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror

                                </div>
                            </div>
                                <div class="form-group row">
                                    <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('password_confirmation') }}</label>

                                    <div class="col-md-6">

                                        <input type="password" name="password_confirmation"
                                               class="@error('password_confirmation') is-invalid @enderror" id="password_confirmation ">
                                        @error('password_confirmation')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <input type="submit" class="pull-right btn btn-success" value = "Обновить">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection