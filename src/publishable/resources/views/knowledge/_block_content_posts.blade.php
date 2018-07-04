<div class="tech_question">
    <div class="wrapper">
        <div class="list_tab f_left">

            @foreach ($categories as $index => $category)

            <div class="{{ $index ? 'con_list_l' : 'con_list_l active' }}">
                <div class="{{ $index ? 'name_tab' : 'name_tab active' }}">
                    <h5>{{ $category->name }}</h5>
                </div>
                <ul class="list_tab_question" style="display: block;">

                    @foreach ($category->posts as $indexPost => $post)

                        <li><a href="#" class="{{ (!$index && !$indexPost ) ? 'bt_answers active' : 'bt_answers' }}" data-answer-id="#answer_{{ $post->id }}">{{ $post->title }}</a></li>

                    @endforeach

                </ul>
            </div>

            @endforeach

        </div>
        <div class="info_answers f_right">

            @foreach ($categories as $indexCategory => $category)
                @foreach ($category->posts as $indexPost => $post)

                    <div class="{{ (!$indexCategory && !$indexPost) ? 'answer_con active' : 'answer_con' }}" id="answer_{{ $post->id }}">
                        <div class="title_answer"><span>{{ $post->title }}</span></div>
                        <div class="content_b">

                            {!! $post->body !!}

                        </div>
                        <div class="bott_info">
                            <h5>Может быть полезно:</h5>
                            <ul>
                                <li><a href="">Безопаность и защита данных</a></li>
                                <li><a href="">Криптовалюты и платформа</a></li>
                                <li><a href="">Дополнительно</a></li>
                            </ul>
                        </div>
                    </div>

                @endforeach
            @endforeach

        </div>
        <div class="clear"></div>
    </div>
</div>
