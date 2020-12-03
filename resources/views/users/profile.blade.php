@extends('layouts.app')

@section('title')
    {{ $user->name }}
@endsection

@section('content')
    <div>
        <ul class="list-group">
            <li class="list-group-item">
                Joined on {{$user->created_at->format('M d,Y \a\t h:i a') }}
            </li>
            <li class="list-group-item panel-body">
                <table class="table-padding">
                    <style>
                        .table-padding td {
                            padding: 3px 8px;
                        }
                    </style>

                    <tr>
                        <td>Total Posts</td>
                        <td> {{$posts_count}}</td>
                        @if($author && $posts_count)
                            <td><a href="{{ url('/my-all-posts')}}">Show All</a></td>
                        @endif
                    </tr>
                    <tr>
                        <td>Published Posts</td>
                        <td>{{$posts_active_count}}</td>
                        @if($posts_active_count)
                            <td><a href="{{ url('/user/'.$user->id.'/posts')}}">Show All</a></td>
                        @endif
                    </tr>
                    <tr>
                        <td>Posts in Draft </td>
                        <td>{{$posts_draft_count}}</td>
                        @if($author && $posts_draft_count)
                            <td><a href="{{ url('my-drafts')}}">Show All</a></td>
                        @endif
                    </tr>
                </table>
            </li>
        </ul>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Latest Posts</h3>
        </div>
        <div class="panel-body">
            @if(!empty($latest_posts[0]))
                @foreach($latest_posts as $latest_post)
                    <p>
                        <strong><a href="{{ url('/'.$latest_post->slug) }}">{{ $latest_post->title }}</a></strong>
                        <span class="well-sm">On {{ $latest_post->created_at->format('M d,Y \a\t h:i a') }}</span>
                    </p>
                @endforeach
            @else
                <p>You have not written any post till now.</p>
            @endif
        </div>
    </div>
@endsection