@extends('layouts.external')

@section('title')
{{ $mainCategory->name }}
@endsection

@section('content')

<div class="sub_c_main">
    <div class="knowledge_base_h sub_c_u">
        <div class="wrapper">
            <div class="title">
                <h1>База Знаний</h1>
            </div>
        </div>
    </div>
    <div class="table_contents">
        <div class="wrapper">
            <div class="go_back f_left"><a href="<?= route('knowledge'); ?>" class="d-table">{{ $mainCategory->name }}</a></div>
            <div class="con_search f_right">
                <form action="{{ route('knowledge.search') }}" id="search-form" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="categoryId" value="{{ $mainCategory->id }}">
                    <input type="search" name="searchQuery" id="search-input" placeholder="Поиск по теме">
                    <input type="submit">
                </form>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    @include('knowledge._block_content_posts', compact('categories'))

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
@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/jquery.mark.min.js"></script>

    <script type="text/javascript">
        $(document).on('submit', '#search-form', function( e ) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize()
            }).done(function(data) {
                $(document).find('.tech_question').replaceWith(data);
                $(document).find(".list_tab_question, .list_tab_question p").hide();
                let searchValue = $('#search-input').val();
                $(document).find("div.content_b").mark(searchValue, {
                    "element": "span",
                    "className": "highlight"
                });
                $(document).find("div.list_tab").mark(searchValue, {
                    "element": "span",
                    "className": "highlight"
                });

                if ($(document).find(".tech_question .con_list_l").hasClass("active")) {
                    $(document).find(".tech_question .con_list_l.active").find("ul").css("display", "block")
                }
            });
        });
    </script>

@endsection