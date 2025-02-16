@extends('layouts.app')

@section('content')
@php $viewName = 'articles.index'; @endphp

<div class="page-header">
    <h4>
        <a href="{{ route('articles.index') }}">
            포럼
        </a>
        <small>
            / 글 목록
        </small>
    </h4>
</div>

<div class="text-right action__article">
    <a href="{{ route('articles.create') }}" class="btn btn-primary">
        <i class="fa fa-plus-circle"></i>
        새 글 쓰기
    </a>
</div>

<div class="row container__article">
    <div class="col-md-3 sidebar__article">
        <aside>
            <!-- 게시판 좌측 태그 목록 -->
            @include('tags.partial.index')
        </aside>
    </div>

    <div class="col-md-9 list__article">
        <article>
            <!-- 게시글판의 태그 목록 -->
            @forelse($articles as $article)
            @include('articles.partial.article', compact('article'))
            @empty
            <p class="text-center text-danger">
                글이 없습니다.
            </p>
            @endforelse
        </article>

        @if($articles->count())
        <div class="text-center paginator__article">
            {!! $articles->appends(request()->except('page'))->render() !!}
        </div>
        @endif
    </div>
</div>
@stop