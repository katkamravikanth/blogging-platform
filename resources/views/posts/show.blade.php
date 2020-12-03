@extends('layouts.app')

@section('title')
    @if($post)
        {{ $post->title }}
        @if(!Auth::guest() && (Auth::user()->is_admin()))
            <button class="btn" style="float: right"><a href="{{ url('edit/'.$post->slug)}}">Edit Post</a></button>
        @endif
    @else
        Page does not exist
    @endif
@endsection

@section('title-meta')
    <p>{{ !empty($post->publication_date) ? "Published ".$post->publication_date->format('M d,Y \a\t h:i a') : "Created ".$post->created_at->format('M d,Y \a\t h:i a') }} By {{ $post->author->name }}</p>
@endsection

@section('content')
    @if($post)
        <div>
            {!! $post->description !!}
        </div>
    @else
        404 error
    @endif
@endsection