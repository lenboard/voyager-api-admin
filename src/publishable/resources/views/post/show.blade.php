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
    <div class="open_news_conteiner">
        <div class="wrapper">

            <div class="left_block f_left">
                <div class="open_news">
                    <h3>{{ $post->title }}</h3>

                    @if ($post->image)

                          <!--<img src="{{ URL::asset('storage/' . $post->image) }}">-->

                    @endif

                    <div class="footer_img">
                        <div class="data f_left">{{ \Carbon\Carbon::parse($post->created_at)->format('d.m.Y')}}</div>
                        <div class="developments f_left">

                            @if ($post->category && !$post->is_belongs_root_category)

                                <a href="{{ route('news.category', ['category' => $post->category]) }}" rel="category">{{ $post->category->name }}</a>

                            @endif

                        </div>
                        <div class="share f_right"><a href="" class="block transition">Поделиться</a></div>
                        <div class="clear"></div>

                        @if ($post->image)

                            <div class="singleImageContainer" style="background-image: url({{ URL::asset('storage/' . $post->image) }});"></div>

                        @endif

                        <p>{!! $post->body !!}</p>

                        <div>
                            Tags:

                            @foreach ($post->listTags as $tag)

                                <a href="{{ route('news.tag', ['tagName' => $tag]) }}">{{ $tag }}</a>

                                @if (!$loop->last)
                                    |
                                @endif

                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
            <div class="right_block f_right">
                <a href="#" class="block transition join_us">НАЧАТЬ СОТРУДНИЧЕСТВО</a>

                @if ($dayNews->count())

                    <h4>Новости дня</h4>
                    <div class="right_news">
                        <div id="newofday_widget-2" class="widget widget_newofday_widget">

                            @foreach ($dayNews as $news)

                                <div class="right_news">
                                    <img src="">
                                    <div class="data">{{ \Carbon\Carbon::parse($news->created_at)->format('d.m.Y')}}</div>
                                    <h3>{{ $news->title }}</h3>
                                    <p>{{ str_limit($news->excerpt, $limit = 150, $end = '...') }}</p>
                                    <a href="{{ route('news.show', ['id' => $news->id]) }}" class="block transition learn_more">Читать далее...</a>
                                </div>

                            @endforeach

                        </div>
                    </div>

                @endif
                @if ($lastPosts->count())

                    <h4>Последние</h4>
                    <div class="right_news_last">
                        <div id="lastnews_widget-2" class="widget widget_lastnews_widget">
                            <div class="right_news_last">

                                @foreach ($lastPosts as $post)

                                    <div class="data">{{ \Carbon\Carbon::parse($post->created_at)->format('d.m.Y')}}</div>
                                    <h3>{{ $post->title }}</h3>
                                    <p>{{ str_limit($post->excerpt, $limit = 150, $end = '...') }}</p>
                                    <a href="{{ route('news.show', ['id' => $post->id]) }}" class="block transition learn_more">Читать далее...</a>

                                @endforeach

                            </div>
                        </div>
                    </div>

                @endif

            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
@endsection