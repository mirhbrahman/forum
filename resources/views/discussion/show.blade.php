@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <img src="{{$d->user->avatar}}" alt="" width="40px" height="40px">&nbsp;&nbsp;&nbsp;
            <span>{{$d->user->name}} <b>{{$d->created_at->diffForHumans()}}</b></span>

            @if ($d->is_being_watch_by_auth_user())
                <a href="{{route('discussion.unwatch',['id'=>$d->id])}}" class="pull-right btn btn-default">Unwatch</a>
            @else
                <a href="{{route('discussion.watch',['id'=>$d->id])}}" class="pull-right btn btn-default">Watch</a>
            @endif
        </div>

        <div class="panel-body">
            <h4 class="text-center">
                <b>{{$d->title}}</b>
            </h4>
            <hr>
            <p>
                {{$d->content}}
            </p>
        </div>

        <div class="panel-footer">
            <span>{{$d->replies->count()}} Replies</span>
            <a href="{{route('channel',$d->channel->slug)}}" class="btn btn-xs btn-default pull-right">{{$d->channel->title}}</a>
        </div>
    </div>

    @if ($d->replies)
        @foreach ($d->replies as $r)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <img src="{{$r->user->avatar}}" alt="" width="40px" height="40px">&nbsp;&nbsp;&nbsp;
                    <span>{{$r->user->name}} <b>{{$r->created_at->diffForHumans()}}</b></span>
                </div>

                <div class="panel-body">
                    <p>
                        {{$r->content}}
                    </p>
                </div>
                <div class="panel-footer">
                    @if ($r->is_like_by_auth_user())
                        <a href="{{route('reply.unlike',['id'=>$r->id])}}" class="btn btn-xs btn-danger">Unlike</a> <span class="badge btn btn-xs btn-default">Likes: {{$r->likes->count()}}</span>
                    @else
                        <a href="{{route('reply.like',['id'=>$r->id])}}" class="btn btn-xs btn-success">Like <span class="badge">{{$r->likes->count()}}</span></a>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

    <div class="panel panel-default">
        <div class="panel-body">
            @if (Auth::check())
                {{Form::open(['route'=>['discussion.reply',$d->id],'method'=>'post'])}}
                <label for="">Leave a reply...</label>
                {{Form::textarea('content',null,['class'=>'form-control'])}}
                <br>
                {{Form::submit('Leave a reply',['class'=>'btn btn-info pull-right'])}}
                {{Form::close()}}
            @else
                <div class="text-center">
                    <h4>Sing in to leave a reply</h4>
                </div>
            @endif
        </div>
    </div>

@endsection
