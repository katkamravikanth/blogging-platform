@extends('layouts.app')

@section('title')
    {{$title}}
@endsection

@section('content')
    @if (session('status'))
        <div class="flash alert-info">
            <p class="panel-body">
                @if(session('status') === true)
                    Successfully added #{{ session('added') }} and found duplicates #{{ session('duplicates') }}
                @else
                    {{ session('message') }}
                @endif
            </p>
        </div>
    @endif

    @if ( !$posts->count() )
        There is no post till now. Login and write a new post now!!!
    @else
        <div class="row">
            @foreach( $posts as $post )
                <div class="list-group mb-2 w-100">
                    <div class="list-group-item">
                        <h3><a href="{{ url('/'.$post->slug) }}">{{ $post->title }}</a>
                            @if(!Auth::guest() && (Auth::user()->is_admin()))
                                <button class="btn" style="float: right"><a href="{{ url('edit/'.$post->slug)}}">Edit Post</a></button>
                            @endif
                        </h3>
                        {{ $post->publication_date->format('M d,Y \a\t h:i a') }} By {{ $post->author->name }}
                    </div>
                    <div class="list-group-item">
                        <article>
                            {!! Str::limit($post->description, $limit = 500, $end = '....... <a href='.url("/".$post->slug).'>Read More</a>') !!}
                        </article>
                    </div>
                </div>
            @endforeach

            {!! $posts->render() !!}
        </div>
    @endif
@endsection