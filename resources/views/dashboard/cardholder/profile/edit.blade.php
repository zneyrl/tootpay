@extends('layouts.app')

@section('title', 'Edit - ' . $user->name)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('dashboard.cardholder._partials.sidebar')
            </div>
            <div class="col-md-9">
                <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                        <span class="pull-left">@yield('title')</span>
                        <span class="pull-right">
                            @include('_partials.cancel', ['url' => route('users.profile_index', $user->id)])
                        </span>
                    </div>
                    <div class="panel-body">
                        @include('dashboard.cardholder.profile._partials.form')
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                        <span class="pull-left">Reset Password</span>
                    </div>
                    <div class="panel-body">
                        @include('dashboard.cardholder.profile._partials.password_form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('_partials.spinner')