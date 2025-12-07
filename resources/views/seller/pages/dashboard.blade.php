@extends('seller/layouts/main')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
        Dashboard
    </a>
@endsection
@section('pages')
    <div class="grid gap-4">
        <div class="stats shadow">
            <div class="stat">
                <div class="stat-title">Total Users</div>
                <div class="stat-value">89,400</div>
                <div class="stat-desc">21% more than last month</div>
            </div>

            <div class="stat">
                <div class="stat-title">New Users</div>
                <div class="stat-value">4,200</div>
                <div class="stat-desc">↗︎ 400 (22%)</div>
            </div>

            <div class="stat">
                <div class="stat-title">Active Users</div>
                <div class="stat-value">1,200</div>
                <div class="stat-desc">↘︎ 90 (14%)</div>
            </div>
        </div>
    </div>
    <h1></h1>
@endsection