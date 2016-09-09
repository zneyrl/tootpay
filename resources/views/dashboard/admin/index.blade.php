@extends('layouts.app')

@section('title', Auth::user()->name . ' (' . \App\Models\Role::find(admin())->name . ')')

@section('content')
    <div class="container">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-3 pull-left">
                            <i class="fa fa-users fa-4x" aria-hidden="true"></i>
                        </div>
                        <div class="col-md-9 pull-right text-right">
                            <div class="huge-count"><strong>{{ \App\Models\User::count() }}</strong></div>
                            <div>Users</div>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <a href="{{ route('users.index') }}" target="_blank">View Details<i class="fa fa-arrow-right pull-right" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-3 pull-left">
                            <i class="fa fa-cutlery fa-4x" aria-hidden="true"></i>
                        </div>
                        <div class="col-md-9 pull-right text-right">
                            <div class="huge-count"><strong>{{ \App\Models\Merchandise::available()->get()->count() }}</strong></div>
                            <div>Available Merchandises</div>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <a href="{{ route('merchandises.available.index') }}" target="_blank">View Details<i class="fa fa-arrow-right pull-right" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-3 pull-left">
                            <i class="fa fa-money fa-4x" aria-hidden="true"></i>
                        </div>
                        <div class="col-md-9 pull-right text-right">
                            <div class="huge-count"><strong>{{ \App\Models\Transaction::dailySales(\Carbon\Carbon::now()->toDateString())->count() }}</strong></div>
                            <div>Sales</div>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <a href="{{ route('sales_report.index') }}" target="_blank">View Details<i class="fa fa-arrow-right pull-right" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-3 pull-left">
                            <i class="fa fa-credit-card-alt fa-4x" aria-hidden="true"></i>
                        </div>
                        <div class="col-md-9 pull-right text-right">
                            <div class="huge-count"><strong>{{ \App\Models\TootCard::count()}}</strong></div>
                            <div>Toot Cards</div>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <a href="{{ route('toot_cards.index') }}" target="_blank">View Details<i class="fa fa-arrow-right pull-right" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
@endsection