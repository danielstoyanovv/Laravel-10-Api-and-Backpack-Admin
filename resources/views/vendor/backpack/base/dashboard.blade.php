@extends(backpack_view('blank'))

@php
    $widgets['before_content'][] = [
        'type'        => 'jumbotron',
        'heading'     => trans('backpack::base.welcome'),
        'content'     => trans('backpack::base.use_sidebar'),
        'button_link' => backpack_url('logout'),
        'button_text' => trans('backpack::base.logout'),
    ];
    $widgets['before_content'][] = [
        'type' => 'progress',
        'class' => 'card',
        'value' => $countUsers,
        'description' => 'Registered users'
    ];
    $widgets['before_content'][] = [
        'type' => 'progress',
        'class' => 'card',
        'value' => $activeUsers,
        'description' => 'Active users'
    ];
    $widgets['before_content'][] = [
        'type' => 'progress',
        'class' => 'card',
        'value' => $countPosts,
        'description' => 'Posts'
    ];
@endphp

@section('content')
@endsection
