@extends('layouts.external')

@section('css')
<link rel="stylesheet" href="/post/css/uber-icons.css">
<link rel="stylesheet" href="/post/css/refresh.css">
<link rel="stylesheet" href="/post/css/main-2ebd3f14a4_news.css">
@endsection

@section('title')
Посты
@endsection


@section('content')

<div class="news">
    <div class="news_conteiner">
        <div class="wrapper">
            <div class="left_block f_left">

                @widget('Banners', ['uri' => ['banner1', 'banner2']])

            </div>
            <div class="center_block f_left">

                @foreach ($posts as $post)

                    <div class="news_block">
                        <div class="img">

                            @if ($post->image)

                                <div class="singleImageContainerExternal" style="background-image: url('{{ URL::asset('storage/' . $post->image) }}');"></div>

                            @else

                                <div class="singleImageContainerExternal" style="background-image: url();"></div>

                            @endif

                            <h3><a href="{{ route('news.show', ['id' => $post->id]) }}">{{ $post->title }}</a></h3>
                        </div>
                        <div class="short_content">
                            <p>{{ $post->excerpt }}</p>
                        </div>
                        <div class="footer_news">
                            <div class="data f_left">{{ \Carbon\Carbon::parse($post->created_at)->format('d.m.Y') }}</div>
                            <div class="developments f_left">

                                @if ($post->category && !$post->is_belongs_root_category )

                                    <a href="{{ route('news.category', ['category' => $post->category]) }}" rel="category">{{ $post->category->name }}</a>

                                @endif

                            </div>
                            <div>
                                Tags:

                                @foreach ($post->listTags as $tag)

                                    <a href="{{ route('news.tag', ['tagName' => $tag]) }}">{{ $tag }}</a>

                                    @if (!$loop->last)
                                        |
                                    @endif

                                @endforeach

                            </div>
                            <div class="share f_right"><a href="" class="block transition">Поделиться</a></div>
                            <div class="clear"></div>
                        </div>
                    </div>

                @endforeach

                {{ $posts->links() }}

            </div>
            <div class="right_block f_right">

                @widget('Banners', ['uri' => ['right-block']])

            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

@endsection