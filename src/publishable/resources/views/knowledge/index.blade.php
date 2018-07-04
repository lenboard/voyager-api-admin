@extends('layouts.external')

@section('title')
База знаний
@endsection

@section('content')

<div>
    <div class="knowledge_base_h sub_c_u">
        <div class="wrapper">
            <div class="title">
                <h1>База Знаний</h1>
            </div>
        </div>
    </div>
    <div class="tech_question">
        <div class="wrapper">
            <div class="list_tab f_left">

                @foreach ($categories as $category)

                    <div class="con_list_l">
                        <div class="name_tab">
                            <h5><a href="{{ route('knowledge.list', ['id' => $category->id]) }}">{{ $category->name }}</a></h5>
                        </div>

                    </div>

                @endforeach

            </div>

            <div class="clear"></div>
        </div>
    </div>
    <div class="navigation_base sub_c_bot">
        <div class="wrapper">
            <div class="nav_footer">
                <div class="left_b f_left">
                    <h2>Не удалось найти ответ в Базе Знаний?</h2>
                    <a href="http://front.devm.tech/knowledge_base#feedback" class="d-table">Отправить Обращение офлайн</a>
                </div>
                <div class="right_b f_right">
                    <p>Присоединяйтесь к Метрполии на:</p>
                    <div class="social">
                        <a href="https://www.youtube.com/channel/UCEwXKOm6mtR7KozCZeQbD7A" class="block f_right youtube"></a>
                        <a href="https://twitter.com/metropolianews" class="block f_right twitter"></a>
                        <a href="https://vk.com/metropolia_official" class="block f_right vk"></a>
                        <a href="https://t.me/metropoliarus" class="block f_right telegram"></a>
                        <a href="https://www.instagram.com/metropoliarus/" class="block f_right inst"></a>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>

@endsection