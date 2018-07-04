<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Post;
use App\Models\Post\Category;

class PostsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        //Data Type
        $dataType = $this->dataType('slug', 'posts');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'posts',
                'display_name_singular' => __('voyager::seeders.data_types.post.singular'),
                'display_name_plural'   => __('voyager::seeders.data_types.post.plural'),
                'icon'                  => 'voyager-news',
                'model_name'            => 'TCG\\Voyager\\Models\\Post',
                'policy_name'           => 'TCG\\Voyager\\Policies\\PostPolicy',
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
            ])->save();
        }

        //Data Rows
        $postDataType = DataType::where('slug', 'posts')->firstOrFail();
        $dataRow = $this->dataRow($postDataType, 'id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => __('voyager::seeders.data_rows.id'),
                'required'     => 1,
                'browse'       => 0,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'details'      => '',
                'order'        => 1,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'author_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager::seeders.data_rows.author'),
                'required'     => 1,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 0,
                'delete'       => 1,
                'details'      => '',
                'order'        => 2,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'category_id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager::seeders.data_rows.category'),
                'required'     => 1,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 0,
                'details'      => '',
                'order'        => 3,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'title');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager::seeders.data_rows.title'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '',
                'order'        => 4,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'excerpt');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text_area',
                'display_name' => __('voyager::seeders.data_rows.excerpt'),
                'required'     => 1,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '',
                'order'        => 5,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'body');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'rich_text_box',
                'display_name' => __('voyager::seeders.data_rows.body'),
                'required'     => 1,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '',
                'order'        => 6,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'image');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'image',
                'display_name' => __('voyager::seeders.data_rows.post_image'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => json_encode([
                    'resize' => [
                        'width'  => '1000',
                        'height' => 'null',
                    ],
                    'quality'    => '70%',
                    'upsize'     => true,
                    'thumbnails' => [
                        [
                            'name'  => 'medium',
                            'scale' => '50%',
                        ],
                        [
                            'name'  => 'small',
                            'scale' => '25%',
                        ],
                        [
                            'name' => 'cropped',
                            'crop' => [
                                'width'  => '300',
                                'height' => '250',
                            ],
                        ],
                    ],
                ]),
                'order' => 7,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'slug');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager::seeders.data_rows.slug'),
                'required'     => 1,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => json_encode([
                    'slugify' => [
                        'origin'      => 'title',
                        'forceUpdate' => true,
                    ],
                    'validation' => [
                        'rule'  => 'unique:posts,slug',
                    ],
                ]),
                'order' => 8,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'meta_description');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text_area',
                'display_name' => __('voyager::seeders.data_rows.meta_description'),
                'required'     => 1,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '',
                'order'        => 9,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'meta_keywords');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text_area',
                'display_name' => __('voyager::seeders.data_rows.meta_keywords'),
                'required'     => 1,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '',
                'order'        => 10,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'status');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'select_dropdown',
                'display_name' => __('voyager::seeders.data_rows.status'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => json_encode([
                    'default' => 'DRAFT',
                    'options' => [
                        'PUBLISHED' => 'published',
                        'DRAFT'     => 'draft',
                        'PENDING'   => 'pending',
                    ],
                ]),
                'order' => 11,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'created_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager::seeders.data_rows.created_at'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'details'      => '',
                'order'        => 12,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'updated_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => __('voyager::seeders.data_rows.updated_at'),
                'required'     => 0,
                'browse'       => 0,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'details'      => '',
                'order'        => 13,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'seo_title');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => __('voyager::seeders.data_rows.seo_title'),
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '',
                'order'        => 14,
            ])->save();
        }
        $dataRow = $this->dataRow($postDataType, 'featured');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'checkbox',
                'display_name' => __('voyager::seeders.data_rows.featured'),
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '',
                'order'        => 15,
            ])->save();
        }
        $dataRow = $this->dataRow($postDataType, 'popular');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'checkbox',
                'display_name' => 'Popular',
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '',
                'order'        => 16,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'tags');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text_area',
                'display_name' => 'Tags',
                'required'     => 1,
                'browse'       => 0,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '',
                'order'        => 17,
            ])->save();
        }

        //Menu Item
        $menu = Menu::where('name', 'admin')->firstOrFail();
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('voyager::seeders.menu_items.posts'),
            'url'     => '',
            'route'   => 'voyager.posts.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-news',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 6,
            ])->save();
        }

        //Permissions
        Permission::generateFor('posts');

        //Content
        $post = $this->findPost('новый-партнер-indoor-russia');
        if (!$post->exists) {
            $post->fill([
                'title'            => 'Новый партнер - INDOOR RUSSIA',
                'author_id'        => 0,
                'category_id'      => Category::CATEGORY_NEWS_ID,
                'seo_title'        => null,
                'excerpt'          => '27 мая состаялось подписание договора о сотрудничестве между OOO Рус Индор, федеральным оператором рекламы в бизнес-центрах высшего класса.',
                'body'             => base64_decode('Mjcg0LzQsNGPINGB0L7RgdGC0LDRj9C70L7RgdGMINC/0L7QtNC/0LjRgdCw0L3QuNC1INC00L7Qs9C+0LLQvtGA0LAg0L4g0YHQvtGC0YDRg9C00L3QuNGH0LXRgdGC0LLQtSDQvNC10LbQtNGDwqBPT08g0KDRg9GBINCY0L3QtNC+0YAsINGE0LXQtNC10YDQsNC70YzQvdGL0Lwg0L7Qv9C10YDQsNGC0L7RgNC+0Lwg0YDQtdC60LvQsNC80Ysg0LIg0LHQuNC30L3QtdGBLdGG0LXQvdGC0YDQsNGFINCy0YvRgdGI0LXQs9C+INC60LvQsNGB0YHQsC4g0JrQvtC80L/QsNC90LjRj8Kg0Y/QstC70Y/QtdGC0YHRjyDRgdC+0LHRgdGC0LLQtdC90L3QuNC60L7QvCDRgNC10LrQu9Cw0LzQvdGL0YUg0LrQvtC90YHRgtGA0YPQutGG0LjQuSDQsiDQsdC40LfQvdC10YEt0YbQtdC90YLRgNCw0YUg0LrQu9Cw0YHRgdCwIEIrLyDQkCAvIEErINCyINCc0J7QodCa0JLQlSDQuCDQs9C+0YDQvtC00LDRhSDQvNC40LvQu9C40L7QvdC90LjQutCw0YUg0KDQvtGB0YHQuNC4LiDQoNCw0LfQstC40LLQsNC10YIg0YHQvtCx0YHRgtCy0LXQvdC90YPRjiDRgdC10YLRjCDRgNC10LrQu9Cw0LzQvdGL0YUg0LrQvtC90YHRgtGA0YPQutGG0LjQuSDRgSAyMDEyINCz0L7QtNCwLsKgPCEtLW1vcmUtLT4NCiAgICAgICAgICAgICAgICAgINCh0YDQtdC00Lgg0LrRgNGD0L/QvdC10LnRiNC40YU6INC+0YTQuNGB0L3Ri9C1INC90LXQsdC+0YHQutGA0ZHQsdGLINCc0L7RgdC60LLQsCDQodCY0KLQmCAo0JHQsNGI0L3RjyDQpNC10LTQtdGA0LDRhtC40Y8sINCc0LXRgNC60YPRgNC40LksINCY0LzQv9C10YDQuNGPINCi0LDRg9GN0YAsINCT0L7RgNC+0LQg0KHRgtC+0LvQuNGGKSwg0KbQtdC90YLRgCDQnNC10LbQtNGD0L3QsNGA0L7QtNC90L7QuSDRgtC+0YDQs9C+0LLQu9C4LCDQkdCw0YjQvdGPIDIwMDAsINCT0L7Qu9C00LXQvSDQk9C10LnRgiwg0JvQtdGB0L3QsNGPINCf0LvQsNC30LAsINCU0L7QvNC90LjQutC+0LIsINCb0LXQs9C40L7QvSDQuCDQtNGALg0KDQogICAgICAgICAgICAgICAgPGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgd3AtaW1hZ2UtMjY3IiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNS9vZmlzXzEtMTAyNHg2ODMuanBnIiBhbHQ9Im9maXNfMSIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0MzQiIC8+IDxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTI2OCIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDUvb2Zpc18zLTEwMjR4NjgzLmpwZyIgYWx0PSJvZmlzXzMiIHdpZHRoPSI2NTAiIGhlaWdodD0iNDM0IiAvPiA8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0yNjkiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA1L29maXNfNS0xMDI0eDY4My5qcGciIGFsdD0ib2Zpc181IiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjQzNCIgLz4NCg0KICAgICAgICAgICAgICAgIDxiPtCf0L7Rh9C10LzRgyDRgNC10LrQu9Cw0LzQsCDQsiDQsdC40LfQvdC10YHigJPRhtC10L3RgtGA0LDRhSDQuNC90YLQtdGA0LXRgdC90LAg0YDQtdC60LvQsNC80L7QtNCw0YLQtdC70Y/QvDwvYj46INC+0YTQuNGB0L3Ri9C1INGG0LXQvdGC0YDRiyDigJMg0LfQsNC60YDRi9GC0YvQtSDQuCDRgtGA0YPQtNC90L7QtNC+0YHRgtGD0L/QvdGL0LUg0L7QsdGK0LXQutGC0YssINCz0LTQtSDRgdC+0YHRgNC10LTQvtGC0L7Rh9C10L3QsCDQsNGD0LTQuNGC0L7RgNC40Y8g0YEg0LLRi9GB0L7QutC40Lwg0YPRgNC+0LLQvdC10Lwg0LTQvtGF0L7QtNC+0LIg0Lgg0L/QvtGC0YDQtdCx0LvQtdC90LjRjy4g0KDQtdC60LvQsNC80LAg0LIg0LHQuNC30L3QtdGBLdGG0LXQvdGC0YDQsNGFINC+0YLQutGA0YvQstCw0LXRgiDQtNC+0YHRgtGD0L8g0Log0L/Qu9Cw0YLQtdC20LXRgdC/0L7RgdC+0LHQvdC+0Lkg0LDRg9C00LjRgtC+0YDQuNC4IOKAkyDQutC+0YDQv9C+0YDQsNGC0LjQstC90YvQvCDRgdC+0YLRgNGD0LTQvdC40LrQsNC8ICg4MSUpLCDQotCe0J8t0LzQtdC90LXQtNC20LXRgNCw0LwgKDE1JSkg0Lgg0LLQu9Cw0LTQtdC70YzRhtCw0LwgKDQlKSDQutGA0YPQv9C90LXQudGI0LjRhSDRgNC+0YHRgdC40LnRgdC60LjRhSDQutC+0LzQv9Cw0L3QuNC5INC4INC80LXQttC00YPQvdCw0YDQvtC00L3Ri9GFINC60L7RgNC/0L7RgNCw0YbQuNC5OiBDb2NhLdChb2xhLCDQndC+0YDQuNC70YzRgdC60LjQuSDQvdC40LrQtdC70YwsIE1pY3Jvc29mdCwg0K/QvdC00LXQutGBLCBVbmlsZXZlciwg0KDQvtGB0L3QtdGE0YLRjCwgVG90YWwsINCQ0Y3RgNC+0YTQu9C+0YIsIE1haWwgR3JvdXAsIFNhbXN1bmcsINCa0LDRgdC/0LXRgNGB0LrQuNC5LCBCTVcsIFZvbGtzd2FnZW4gR3JvdXAsIExHLCBNb3JnYW4gU3RhbmxleSwgQmFyY2xheXMsINCS0YvQvNC/0LXQu9Ca0L7QvCwg0JXQstGA0L7RgdC10YLRjCwgQ2FwaXRhbCBHcm91cCDQuCDQtdGJ0LUgPGI+0KLQntCfLTI1MCDQutGA0YPQv9C90LXQudGI0LjRhSDQutC+0LzQv9Cw0L3QuNC5PC9iPtGA0LDQsdC+0YLQvtC00LDRgtC10LvQtdC5INC40LzQtdGO0YIgPGI+0YjRgtCw0LEt0LrQstCw0YDRgtC40YDRiyDQuCDQvtGE0LjRgdGLINCyINCx0LjQt9C90LXRgS3RhtC10L3RgtGA0LDRhTwvYj4uDQogICAgICAgICAgICAgICAg0J7RhdCy0LDRgiDQv9C+INCc0L7RgdC60LLQtSDigJMg0LHQvtC70LXQtSAzMjAgMDAwINC60L7RgNC/0L7RgNCw0YLQuNCy0L3Ri9GFINGA0LDQsdC+0YLQvdC40LrQvtCyLg0KDQogICAgICAgICAgICAgICAgPGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgd3AtaW1hZ2UtMjY2IiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNS8wMTdfMTI3OXg5NTUtMTAyNHg3NjUuanBnIiBhbHQ9IjAxN18xMjc5eDk1NSIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0ODUiIC8+DQoNCiAgICAgICAgICAgICAgICDQoNGL0L3QvtC6INGA0LXQutC70LDQvNGLINGP0LLQu9GP0LXRgtGB0Y8g0YfQsNGB0YLRjNGOINGN0LrQvtC90L7QvNC40YfQtdGB0LrQvtC5INGB0LjRgdGC0LXQvNGLINC4INC+0LHQtdGB0L/QtdGH0LjQstCw0LXRgiDQvtCx0YnQtdGB0YLQstC10L3QvdGD0Y4g0L/QvtGC0YDQtdCx0L3QvtGB0YLRjCDQsiDRgNC10LrQu9Cw0LzQvdGL0YUg0YPRgdC70YPQs9Cw0YUuINCe0L0g0L/RgNC10LTRgdGC0LDQstC70Y/QtdGCINGB0L7QsdC+0Lkg0YHQsNC80L7RgdGC0L7Rj9GC0LXQu9GM0L3Ri9C5INGB0LXQutGC0L7RgCDRjdC60L7QvdC+0LzQuNC60LgsINCy0LrQu9GO0YfQsNGO0YnQuNC5INGI0LjRgNC+0LrRg9GOINGB0LjRgdGC0LXQvNGDINGN0LrQvtC90L7QvNC40YfQtdGB0LrQuNGFLCDQv9GA0LDQstC+0LLRi9GFLCDRgdC+0YbQuNC+LdC60YPQu9GM0YLRg9GA0L3Ri9GFINC4INC40L3Ri9GFIG/RgtC90L7RiNC10L3QuNC5LCDQutC+0YLQvtGA0YvQtSDQstC+0LfQvdC40LrQsNGO0YIg0Lgg0YDQsNC30LLQuNCy0LDRjtGC0YHRjyDQvNC10LbQtNGDINC+0YHQvdC+0LLQvdGL0LzQuCDRgdGD0LHRitC10LrRgtCw0LzQuCDRjdGC0L7Qs9C+INGA0YvQvdC60LAg4oCUINGA0LXQutC70LDQvNC+0LTQsNGC0LXQu9GP0LzQuCwg0YDQtdC60LvQsNC80L4t0L/RgNC+0LjQt9Cy0L7QtNC40YLQtdC70Y/QvNC4LCDRgNC10LrQu9Cw0LzQvi3RgNCw0YHQv9GA0L7RgdGC0YDQsNC90LjRgtC10LvRj9C80Lgg0Lgg0L/QvtGC0YDQtdCx0LjRgtC10LvRj9C80Lgg0YDQtdC60LvQsNC80YsuINCc0LXQttC00YMg0L3QuNC80Lgg0YHRg9GJ0LXRgdGC0LLRg9C10YIg0YLQtdGB0L3QsNGPINGN0LrQvtC90L7QvNC40YfQtdGB0LrQsNGPINCy0LfQsNC40LzQvtGB0LLRj9C30YwsINC60L7RgtC+0YDQsNGPINGP0LLQu9GP0LXRgtGB0Y8g0L7RgdC90L7QstC+0Lkg0YDQtdC60LvQsNC80L3QvtCz0L4g0L/RgNC+0YbQtdGB0YHQsCDQuCDRhNGD0L3QutGG0LjQvtC90LjRgNC+0LLQsNC90LjRjyDRgNGL0L3QutCwINCyINGG0LXQu9C+0LwuINCSINGN0LrQvtC90L7QvNC40YfQtdGB0LrQvtC5INGB0LjRgdGC0LXQvNC1INGA0YvQvdC+0Log0YDQtdC60LvQsNC80Ysg0YHRg9GJ0LXRgdGC0LLRg9C10YIg0LIg0LXQtNC40L3RgdGC0LLQtSDRgSDQtNGA0YPQs9C40LzQuCDRgNGL0L3QutCw0LzQuCDQuCDRgNCw0LfQstC40LLQsNC10YLRgdGPINCyINGA0LDQvNC60LDRhSDQvtCx0YnQuNGFINC30LDQutC+0L3QvtCyINGA0YvQvdC+0YfQvdC+0Lkg0Y3QutC+0L3QvtC80LjQutC4LiDQndCwINGA0YvQvdC60LUg0YDQtdC60LvQsNC80YssINC60LDQuiDQuCDQvdCwINC00YDRg9Cz0LjRhSDRgNGL0L3QutCw0YUsINC00LXQudGB0YLQstGD0Y7RgiDRgtCw0LrQuNC1INGP0LLQu9C10L3QuNGPLCDQutCw0Log0YHQv9GA0L7RgSDQuCDQv9GA0LXQtNC70L7QttC10L3QuNC1INC90LAg0YDQtdC60LvQsNC80L3Ri9C1INGD0YHQu9GD0LPQuCwg0LjQt9C00LXRgNC20LrQuCDQuCDQv9GA0LjQsdGL0LvRjCwg0LrQvtC90YrRjtC90LrRgtGD0YDQsCDRgNGL0L3QutCwLCDRhtC10L3QsCDQuCDRhtC10L3QvtC+0LHRgNCw0LfQvtCy0LDQvdC40LUsINC60L7QvdC60YPRgNC10L3RhtC40Y8g0Lgg0YLQsNC6INC00LDQu9C10LUuDQoNCiAgICAgICAgICAgICAgICA8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0yNjUiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA1LzAzXzEyNzl4OTU5LTEwMjR4NzY4LmpwZyIgYWx0PSIwM18xMjc5eDk1OSIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0ODciIC8+DQoNCiAgICAgICAgICAgICAgICDQktGB0LvQtdC00YHRgtCy0LjQtSDQv9GA0L7RhtC10YHRgdCwINGA0LDQt9Cy0LjRgtC40Y8sINC60LDQuiDQvtCx0YnQtdGB0YLQstCwLCDRgtCw0Log0Lgg0L7RgtC90L7RiNC10L3QuNC5LCDRgdCy0Y/Qt9Cw0L3QvdGL0YUg0YEg0YDQsNC30LvQuNGH0L3Ri9C80Lgg0LDRgdC/0LXQutGC0LDQvNC4INC00LXRj9GC0LXQu9GM0L3QvtGB0YLQuCDRgNGL0L3QutC+LdC+0LHRgNCw0LfRg9GO0YnQuNGFINGB0YPQsdGK0LXQutGC0L7Qsiwg0YTRg9C90LrRhtC40Lgg0YDRi9C90LrQsCDRgNC10LrQu9Cw0LzRiyDQv9C+0YHRgtC+0Y/QvdC90L4g0YPRgdC70L7QttC90Y/RjtGC0YHRjy4g0JIg0Y3RgtC+0Lwg0YDQsNC30LTQtdC70LUg0L/RgNC10LTRgdGC0LDQstC70LXQvdCwINC40L3RhNC+0YDQvNCw0YbQuNGPINC4INC+0YHQvdC+0LLQvdGL0LUg0YHQstC10LTQtdC90LjRjyDQviDRgNGL0L3QutC1INGA0LXQutC70LDQvNGLLCDQtdCz0L4g0YDQtdCz0YPQu9C40YDQvtCy0LDQvdC40LgsINC40L3RhNGA0LDRgdGC0YDRg9C60YLRg9GA0LUsINGA0LDQt9C70LjRh9C90YvRhSDQsNGB0L/QtdC60YLQsNGFINGE0YPQvdC60YbQuNC+0L3QuNGA0L7QstCw0L3QuNGPLCDQutC70Y7Rh9C10LLRi9GFINGE0LDQutGC0L7RgNCw0YUsINCy0LvQuNGP0Y7RidC40YUg0L3QsCDQtdCz0L4g0YDQsNC30LLQuNGC0LjQtSwg0LAg0YLQsNC60LbQtSDRgNGL0L3QutC+LdC+0LHRgNCw0LfRg9GO0YnQuNGFINGB0YPQsdGK0LXQutGC0LDRhSDQuCDQuNGFINCy0LfQsNC40LzQvtC00LXQudGB0YLQstC40LguDQoNCiAgICAgICAgICAgICAgICA8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0yNjQiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA1LzAyXzEyNzl4ODk1LTEwMjR4NzE3LmpwZyIgYWx0PSIwMl8xMjc5eDg5NSIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0NTUiIC8+DQoNCiAgICAgICAgICAgICAgICDQmtC+0LzQv9Cw0L3QuNGPIEluZG9vciBSdXNzaWHCoNGP0LLQu9GP0LXRgtGB0Y8g0LvQuNC00LXRgNC+0Lwg0LIg0YHQstC+0LXQuSDQvtGC0YDQsNGB0LvQuCwg0LDQutGC0LjQstC90L4g0LLQvdC10LTRgNGP0LXRgiDQuCDRgNCw0LfRgNCw0LHQsNGC0YvQstCw0LXRgiDQvdC+0LLRi9C1INGC0LXRhdC90L7Qu9C+0LPQuNC4LiDQnNGLINGD0LLQtdGA0LXQvdGLLCDRh9GC0L4g0LHQuNC30L3QtdGBINC/0LDRgNGC0L3QtdGAINGC0LDQutC+0LPQviDQutC70LDRgdGB0LAg0L7RgtC60YDQvtC10YIg0L3QvtCy0YvQtSDQstC+0LfQvNC+0LbQvdC+0YHRgtC4INC60LDQuiDQtNC70Y8g0L/RgNC+0LTQstC40LbQtdC90LjRjyDQutC+0LzQv9Cw0L3QuNC4LCDRgtCw0Log0Lgg0LTQu9GPINGD0LLQtdC70LjRh9C10L3QuNGPINC40L3QstC10YHRgtC40YbQuNC+0L3QvdC+0LPQviDQv9C+0YDRgtGE0LXQu9GPINC40L3QstC10YHRgtC+0YDQvtCyINCc0LXRgtGA0L7Qv9C+0LvQuNC4Lg0K0J7Qt9C90LDQutC+0LzRjNGC0LXRgdGMINC/0L7QtNGA0L7QsdC90LXQtSDRgSDQsdC40LfQvdC10YEg0L/Qu9Cw0L3QvtC8LCDRgNC10LfRg9C70YzRgtCw0YLQsNC80LgsINC00L7QutGD0LzQtdC90YLQsNGG0LjQtdC5INC4INGD0YfRgNC10LTQuNGC0LXQu9GP0LzQuCDQutC+0LzQv9Cw0L3QuNC4IDxhIGhyZWY9Imh0dHBzOi8vbWV0cm9wb2xpYS5vcmcvcHJvamVjdHMvNSIgdGFyZ2V0PSJfYmxhbmsiPtCyINGA0LDQt9C00LXQu9C1INC/0LDRgNGC0L3QtdGA0LAgSW5kb29yIFJ1c3NpYTwvYT4NCg0KPGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgd3AtaW1hZ2UtMjYxIiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNS9EU0MwMzYxOS0xMDI0eDU3NS5qcGciIGFsdD0iRFNDMDM2MTkiIHdpZHRoPSI2NTAiIGhlaWdodD0iMzY1IiAvPjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTI2MiIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDUvRFNDMDM2MjMtMTAyNHg1NzUuanBnIiBhbHQ9IkRTQzAzNjIzIiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjM2NSIgLz48aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0yNjMiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA1L0RTQzAzNjM2LTEwMjR4NzA1LmpwZyIgYWx0PSJEU0MwMzYzNiIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0NDgiIC8+PGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgd3AtaW1hZ2UtMjcwIiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNS9waG90bzgwMjY2NjEzMTgwMDMwMzU2Ny0xLTc0NHgxMDI0LmpwZyIgYWx0PSJwaG90bzgwMjY2NjEzMTgwMDMwMzU2NyAoMSkiIHdpZHRoPSI2NTAiIGhlaWdodD0iODk1IiAvPg=='),
                'image'            => 'posts/DSC03636.jpg',
                'slug'             => 'новый-партнер-indoor-russia',
                'meta_description' => 'This is the meta description',
                'meta_keywords'    => 'keyword1, keyword2, keyword3',
                'status'           => 'PUBLISHED',
                'featured'         => 0,
                'created_at'       => '2016-05-28 17:49:51',
            ])->save();
        }

        $post = $this->findPost('привет-мир');
        if (!$post->exists) {
            $post->fill([
                'title'            => 'Открытие первого франчайзинг офиса в России',
                'author_id'        => 0,
                'category_id'      => Category::CATEGORY_NEWS_ID,
                'seo_title'        => null,
                'excerpt'          => '<strong>22 февраля 2016 года, в бизнес центре Москва-Сити, состоялось открытие первого франчайзинг офиса компании Metroploy LTD в России.</strong>',
                'body'             => base64_decode('PHN0cm9uZz4yMiDRhNC10LLRgNCw0LvRjyAyMDE2INCz0L7QtNCwLCDQsiDQsdC40LfQvdC10YEg0YbQtdC90YLRgNC1INCc0L7RgdC60LLQsC3QodC40YLQuCwg0YHQvtGB0YLQvtGP0LvQvtGB0Ywg0L7RgtC60YDRi9GC0LjQtSDQv9C10YDQstC+0LPQviDRhNGA0LDQvdGH0LDQudC30LjQvdCzINC+0YTQuNGB0LAg0LrQvtC80L/QsNC90LjQuCBNZXRyb3Bsb3kgTFREINCyINCg0L7RgdGB0LjQuC48L3N0cm9uZz48IS0tbW9yZS0tPg0KDQo8aHIgLz4NCjxwIHN0eWxlPSJwYWRkaW5nLWxlZnQ6IDMwcHg7Ij48c3Ryb25nPsKgPC9zdHJvbmc+PHN0cm9uZz7QlNC40YDQtdC60YLQvtGAINC+0YTQuNGB0LAg4oCTIDxhIGhyZWY9Imh0dHBzOi8vbWV0cm9wb2xpYS5vcmcvZmluZG9mZmljZSIgdGFyZ2V0PSJfYmxhbmsiPtCR0LDRgtGL0YAg0JjRgdCw0LHQsNC10LI8L2E+LsKgPC9zdHJvbmc+0JLQvtC10L3QvdGL0Lkg0YjRgtGD0YDQvNCw0L0uINCX0LDQvNC10YHRgtC40YLQtdC70Ywg0L3QsNGH0LDQu9GM0L3QuNC60LAg0LTQtdC/0LDRgNGC0LDQvNC10L3RgtCwINGB0L7RhdGA0LDQvdC10L3QuNGPINGN0YLQvdC+0LPRgNCw0YTQuNGH0LXRgdC60L7Qs9C+INC4INC00YPRhdC+0LLQvdC+0LPQviDQvdCw0YHQu9C10LTQuNGPINCyINC60L7QvNC40YHRgdC40Lgg0L/QviDQsdC+0YDQsdC1INGBINC60L7RgNGA0YPQv9GG0LjQtdC5LiDQoNGD0LrQvtCy0L7QtNC40YLQtdC70Ywg0LrQvtC80LjRgtC10YLQsCDQv9C+INGB0L7RhtC40LDQu9GM0L3QvtC5INC/0L7Qu9C40YLQuNC60LUg0LIg0JzQntCeINCc0JDQnyAo0JzQtdC20YDQtdCz0LjQvtC90LDQu9GM0L3QsNGPINCe0LHRidC10YHRgtCy0LXQvdC90LDRjyDQntGA0LPQsNC90LjQt9Cw0YbQuNGPINCc0L7RgdC60L7QstGB0LrQsNGPINCQ0YHRgdC+0YbQuNCw0YbQuNGPINCf0YDQtdC00L/RgNC40L3QuNC80LDRgtC10LvQtdC5KS4g0J7QsdC70LDQtNCw0LTQtdC70Ywg0L/QtdGA0LLQvtC5INGE0YDQsNC90YjQuNC30Ysg0LrQvtC80L/QsNC90LjQuCBNZXRyb3BvbHkgTFREINCyINCg0L7RgdGB0LjQudGB0LrQvtC5INCk0LXQtNC10YDQsNGG0LjQuC48L3A+DQo8c3Ryb25nPjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTE2NCIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDIvTEVBREVSLUhPTERFUi1QSUMtMTAyNHg2NzYuanBnIiBhbHQ9IkxFQURFUiBIT0xERVIgUElDIiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjQyOSIgLz48L3N0cm9uZz4NCg0KPGhyIC8+DQoNCtCR0LDRgtGL0YAg0JjRgdCw0LHQsNC10LIgLSDQvtCx0LvQsNC00LDRgtC10LvRjCDQv9C10YDQstC+0Lkg0YTRgNCw0L3RiNC40LfRiyDQutC70LDRgdGB0LAgwqvQnNC10YLRgNC+0L/QvtC70LjRj8K7INCyINCh0J3Qky4g0JIg0LHQu9C40LbQsNC50YjQuNC1INC00L3QuCDQsdGD0LTQtdGCINC/0YDQvtCy0LXQtNC10L3QviDQsdGA0LXQvdC00LjRgNC+0LLQsNC90LjQtSDQvtGE0LjRgdCwINC4INC+0L0g0LHRg9C00LXRgiDQs9C+0YLQvtCyINC6INC/0YDQuNC10LzRgyBWSVAg0LrQu9C40LXQvdGC0L7QsiDQuCDQv9Cw0YDRgtC90LXRgNC+0LIuDQoNCjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTgzIiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wMi8yLTEtMTAyNHg2MDMuanBnIiBhbHQ9IiIgd2lkdGg9IjY1MCIgaGVpZ2h0PSIzODMiIC8+DQoNCtCi0LXQu9C10YTQvtC9INC/0YDQuNC10LzQvdC+0Lkg0L7RhNC40YHQsDrCoDxzdHJvbmc+0LMuINCc0L7RgdC60LLQsCA4IDkyOS05ODktNzYtNTI8L3N0cm9uZz4NCg0KPHN0cm9uZz7QkNC00YDQtdGBINC+0YTQuNGB0LA8L3N0cm9uZz4NCjxzcGFuIHN0eWxlPSJjb2xvcjogIzAwMDAwMDsiPjxhIHN0eWxlPSJjb2xvcjogIzAwMDAwMDsiIGhyZWY9Imh0dHBzOi8vd3d3Lmdvb2dsZS5ydS9tYXBzL3BsYWNlLyVEMCVBMSVEMCVCNSVEMCVCMiVEMCVCNSVEMSU4MCVEMCVCRCVEMCVCMCVEMSU4RislRDAlOTElRDAlQjAlRDElODglRDAlQkQlRDElOEYvQDU1Ljc1MTM1NzQsMzcuNTMyODkxMywxNnovZGF0YT0hNG0yITNtMSExczB4MDoweDI3NTgyZWZmYmQyZDdhOTY/c2hvcnR1cmw9MSIgdGFyZ2V0PSJfYmxhbmsiPtCc0L7RgdC60LLQsCDQodC40YLQuCAo0YHRgtCw0L3RhtC40Y8g0Lwu0JzQtdC20LTRg9C90LDRgNC+0LTQvdCw0Y8pLCDQodC10LLQtdGA0L3QsNGPINCR0LDRiNC90Y8sINGD0Lsu0KLQtdGB0YLQvtCy0YHQutCw0Y8sINC0LjEwLCDQv9C+0LTRitC10LfQtCDihJYxLCDRjdGC0LDQtiAxOSwg0L7RhNC40YEgMTkyNC4g0YLQtdC7LiA4IDkyOS05ODktNzYtNTI8L2E+PC9zcGFuPg0KDQo8aHIgLz4NCg0K0JfQsNC/0LjRgdGMINC90LAg0LLRgdGC0YDQtdGH0YMg0LbQtdC70LDRgtC10LvRjNC90LDCoNC30LDRgNCw0L3QtdC1INCyINGC0LXRh9C10L3QuNC4INGB0YPRgtC+0Log0LTQviDQktCw0YjQtdCz0L4g0LLQuNC30LjRgtCwLg0KPHN0cm9uZz7QnNC10YLRgNC+0L/QvtC70LjRjyDQttC00LXRgiDQktCw0YEhwqDQlNC+0LHRgNC+INC/0L7QttCw0LvQvtCy0LDRgtGMINCyINCc0L7RgdC60LLRgyE8L3N0cm9uZz4NCg0KPGltZyBjbGFzcz0id3AtaW1hZ2UtNzUgYWxpZ25sZWZ0IiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wMi80LTEwMjR4NzA2LmpwZyIgYWx0PSI0IiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjQ0OCIgLz4NCg0KJm5ic3A7'),
                'image'            => '',
                'slug'             => 'привет-мир',
                'meta_description' => 'This is the meta description',
                'meta_keywords'    => 'keyword1, keyword2, keyword3',
                'status'           => 'PUBLISHED',
                'featured'         => 0,
                'created_at'       => '2016-02-22 01:09:01',
            ])->save();
        }

        $post = $this->findPost('заключены-договора-инвестирования');
        if (!$post->exists) {
            $post->fill([
                'title'     => 'Заключены договора инвестирования',
                'author_id' => 0,
                'category_id' => Category::CATEGORY_NEWS_ID,
                'seo_title' => null,
                'excerpt'   => 'Подписаны соглашения о сотрудничестве между компанией Metropolia LTD и компаниями партнерами.',
                'body'      => 'Подписаны соглашения о сотрудничестве между компанией Metropolia LTD и компаниями партнерами. Информация по проектам, которые являются объектами инвестиций, будет в ближайшее время размещена на официальном сайте представительства франшизы в СНГ. Уже сейчас с ними можно ознакомиться в офисе.',
                'image'            => '',
                'slug'             => 'заключены-договора-инвестирования',
                'meta_description' => 'this be a meta descript',
                'meta_keywords'    => 'keyword1, keyword2, keyword3',
                'status'           => 'PUBLISHED',
                'featured'         => 0,
                'created_at'       => '2016-02-29 10:57:20',
            ])->save();
        }

        $post = $this->findPost('первое-публичное-мероприятия-в-рф-з');
        if (!$post->exists) {
            $post->fill([
                'title'     => 'Первое публичное мероприятия в РФ',
                'author_id' => 0,
                'category_id'=> Category::CATEGORY_NEWS_ID,
                'seo_title' => null,
                'excerpt'   => '13 марта состоится первое закрытое мероприятие, на котором руководитель франчайзинг офиса в Москве Батыр Исабаев расскажет о некоторых основных механизмах и принципах работы франшизы.',
                'body'      => '13 марта состоится первое закрытое мероприятие, на котором руководитель франчайзинг офиса в Москве Батыр Исабаев расскажет о некоторых основных механизмах и принципах работы франшизы. По итогам проведения мероприятия будут представлены фото и видео отчеты.',
                'image'            => '',
                'slug'             => 'первое-публичное-мероприятия-в-рф-з',
                'meta_description' => 'Meta Description for sample post',
                'meta_keywords'    => 'keyword1, keyword2, keyword3',
                'status'           => 'PUBLISHED',
                'featured'         => 0,
                'created_at'       => '2016-03-03 10:58:00',
            ])->save();
        }

        $post = $this->findPost('активная-работа-и-интересные-события');
        if (!$post->exists) {
            $post->fill([
                'title'     => 'Активная работа, интересные события...',
                'author_id' => 0,
                'category_id' => Category::CATEGORY_NEWS_ID,
                'seo_title' => null,
                'excerpt'   => 'Компания активно развивается и набирает с каждым днем все больше новых держателей франшиз, инвесторов и надежных партнеров. Из последних отчетов стоит упомянуть интервью представителя компании, Батыра Исабаева, первого держателя франшизы класса "Метрополия", крупному экономическому изданию',
                'body'      => base64_decode('0JrQvtC80L/QsNC90LjRj8Kg0LDQutGC0LjQstC90L4g0YDQsNC30LLQuNCy0LDQtdGC0YHRjyDQuCDQvdCw0LHQuNGA0LDQtdGCINGBINC60LDQttC00YvQvCDQtNC90LXQvCDQstGB0LUg0LHQvtC70YzRiNC1INC90L7QstGL0YUg0LTQtdGA0LbQsNGC0LXQu9C10Lkg0YTRgNCw0L3RiNC40LcsINC40L3QstC10YHRgtC+0YDQvtCyINC4INC90LDQtNC10LbQvdGL0YUg0L/QsNGA0YLQvdC10YDQvtCyLiDQmNC3INC/0L7RgdC70LXQtNC90LjRhSDQvtGC0YfQtdGC0L7QssKg0YHRgtC+0LjRgiDRg9C/0L7QvNGP0L3Rg9GC0Ywg0LjQvdGC0LXRgNCy0YzRjiDQv9GA0LXQtNGB0YLQsNCy0LjRgtC10LvRjyDQutC+0LzQv9Cw0L3QuNC4LCDQkdCw0YLRi9GA0LAg0JjRgdCw0LHQsNC10LLQsCwg0L/QtdGA0LLQvtCz0L4g0LTQtdGA0LbQsNGC0LXQu9GPINGE0YDQsNC90YjQuNC30Ysg0LrQu9Cw0YHRgdCwICLQnNC10YLRgNC+0L/QvtC70LjRjyIsINC60YDRg9C/0L3QvtC80YMg0Y3QutC+0L3QvtC80LjRh9C10YHQutC+0LzRgyDQuNC30LTQsNC90LjRjjwhLS1tb3JlLS0+DQoNCjxociAvPg0KDQo8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0yODIiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA2L1NjcmVlbi1TaG90LTIwMTYtMDYtMDktYXQtMjAuNDEuNDAtMTAyNHg2NzAuanBnIiBhbHQ9IlNjcmVlbiBTaG90IDIwMTYtMDYtMDkgYXQgMjAuNDEuNDAiIHdpZHRoPSI2NTAiIGhlaWdodD0iNDI1IiAvPg0KDQo8aHIgLz4NCg0K0JIg0L/Rg9Cx0LvQuNC60LDRhtC40Lgg0Lgg0LLQuNC00LXQvi3QuNC90YLQtdGA0LLRjNGOINCx0YPQtNGD0YIg0L7Qt9Cy0YPRh9C10L3RiyDQvtGB0L3QvtCy0L3Ri9C1INCy0L7Qv9GA0L7RgdGLINC4INC/0YDQvtCx0LvQtdC80LDRgtC40LrQsCDQuNC90LLQtdGB0YLQuNGG0LjQvtC90L3QvtCz0L4g0YDRi9C90LrQsCDRgdGC0YDQsNC9INCg0L7RgdGB0LjQuCDQuCDQodCd0JMsINCwINGC0LDQuiDQttC1INGA0LDRgdGB0LrQsNC30LDQvdC+INGA0LXRiNC10L3QuNC1INC60L7RgtC+0YDQvtC1INC/0YDQtdC00LvQsNCz0LDQtdGCINGB0LDQvNCwwqDQutC+0LzQv9Cw0L3QuNGPLg0KDQo8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0yODMiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA2L1NjcmVlbi1TaG90LTIwMTYtMDYtMDktYXQtMjAuNDEuMjQtMTAyNHg3MTYuanBnIiBhbHQ9IlNjcmVlbiBTaG90IDIwMTYtMDYtMDkgYXQgMjAuNDEuMjQiIHdpZHRoPSI2NTAiIGhlaWdodD0iNDU0IiAvPjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTI4MSIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDYvU2NyZWVuLVNob3QtMjAxNi0wNi0wOS1hdC0yMC40Mi4yNi0xMDI0eDYzMC5qcGciIGFsdD0iU2NyZWVuIFNob3QgMjAxNi0wNi0wOSBhdCAyMC40Mi4yNiIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0MDAiIC8+DQoNCtCf0YDQvtC00LLQuNC20LXQvdC40LUg0L/Qu9Cw0YLRhNC+0YDQvNGLINC/0YDQvtGF0L7QtNC40YIg0LjRgdC60LvRjtGH0LjRgtC10LvRjNC90L4g0L3QsCDRgdCw0LzRi9GFINC00L7QstC10YDQuNGC0LXQu9GM0L3Ri9GFINC4INGB0LXRgNGM0LXQt9C90YvRhSDQv9C70L7RidCw0LTQutCw0YUg0LrQsNC6INC+0L3Qu9Cw0LnQvSDRgtCw0Log0Lgg0L7RhNC70LDQudC9INCw0YPQtNC40YLQvtGA0LjQuC4g0J/QvtGB0YLQtdC/0LXQvdC90LDRjywg0L/RgNC+0YDQsNCx0L7RgtCw0L3QvdCw0Y8g0YHRgtGA0LDRgtC10LPQuNGPINGA0LDRgdGI0LjRgNC10L3QuNGPwqDRgdC/0L7RgdC+0LHRgdGC0LLRg9C10YIg0LIg0L/QtdGA0LLRg9GOINC+0YfQtdGA0LXQtNGMINC60LDRh9C10YHRgtCy0LXQvdC90L7QvNGDINGA0L7RgdGC0YMg0LrQu9C40LXQvdGC0L7QsiDQuCDQsdC40LfQvdC10YEg0L/QsNGA0YLQvdC10YDQvtCyINC/0LvQsNGC0YTQvtGA0LzRiy4NCg0KPGhyIC8+DQoNCjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTI3OSIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDYvU2NyZWVuLVNob3QtMjAxNi0wNi0wOS1hdC0yMC41Mi41Ny0xMDI0eDY0MS5qcGciIGFsdD0iU2NyZWVuIFNob3QgMjAxNi0wNi0wOSBhdCAyMC41Mi41NyIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0MDciIC8+PGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgd3AtaW1hZ2UtMjgwIiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNi9TY3JlZW4tU2hvdC0yMDE2LTA2LTA5LWF0LTIwLjUyLjM0LTEwMjR4Njc4LnBuZyIgYWx0PSJTY3JlZW4gU2hvdCAyMDE2LTA2LTA5IGF0IDIwLjUyLjM0IiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjQzMSIgLz4NCg0KPGhyIC8+DQoNCtCR0YvQu9C+INC/0YDQvtCy0LXQtNC10L3QviDQvdC10YHQutC+0LvRjNC60L4g0L/Rg9Cx0LvQuNGH0L3Ri9GFINC80LXRgNC+0L/RgNC40Y/RgtC40Lkg0LTQu9GPINGA0Y/QtNC+0LLRi9GFINC40L3QstC10YHRgtC+0YDQvtCyLMKg0LfQsNC40L3RgtC10YDQtdGB0L7QstCw0L3QvdGL0YUsIMKg0LIg0YDQsNC30LLQuNGC0LjQuCDRgdCy0L7QtdCz0L4g0LHQuNC30L3QtdGB0LAsINC70LjRhi4g0KLQsNC6INC20LUg0LHRi9C70Lgg0YPRgdC/0LXRiNC90L4g0L/RgNC+0LLQtdC00LXQvdGLINC/0LXRgNC10LPQvtCy0L7RgNGLINC4INGB0LXRgNC40Y/CoNC/0YDQuNCy0LDRgtC90YvRhSDQstGB0YLRgNC10Ycg0YEg0LrRgNGD0L/QvdGL0LzQuCDQv9GA0LXQtNGB0YLQsNCy0LjRgtC10LvRj9C80Lgg0LzQuNC90LjRgdGC0LXRgNGB0YLQstCwINCg0KQuDQoNCtCh0LvQtdC00YPRjtGJ0LjQuSDRjdGC0LDQvyDRgNCw0LfQstC40YLQuNGPINCc0LXRgtGA0L7Qv9C+0LvQuNC4IC0g0LHQvtC70LXQtSDQv9C70L7RgtC90LDRjyDQuNC90YLQtdCz0YDQsNGG0LjRjyDQsdC70L7QutGH0LXQudC9INGC0LXRhdC90L7Qu9C+0LPQuNC5INCywqDRgNGL0L3QvtC6IENNQsKg0LjQvdCy0LXRgdGC0LjRhtC40LksINCwINGC0LDQuiDQttC1INCy0YvRhdC+0LQg0L3QsCDQutGA0YPQv9C90YvQtSDQuNC90LLQtdGB0YLQuNGG0LjQvtC90L3Ri9C1INGE0L7RgNGD0LzRiywg0YHRgtCw0YDRgtCw0L8g0YHQvtCx0YDQsNC90LjRjyDQuCDRhNC40L3QsNC90YHQvtCy0YvQtSDQv9C70L7RidCw0LTQutC4LCDQvdCw0YbQtdC70LXQvdC90YvQtSDQvdCwINCx0LjQt9C90LXRgSDQstC70LDQtNC10LvRjNGG0LXQsiDQstGL0YHQvtC60L7Qs9C+INGD0YDQvtCy0L3Rjy4NCg0K0J7RgdGC0LDQstCw0LnRgtC10YHRjCDQvdCwINGB0LLRj9C30Lgg0Lgg0YHQu9C10LTQuNGC0LUg0LfQsCDQvdC+0LLQvtGB0YLRj9C80Lgg0LrQvtC80L/QsNC90LjQuCDQvdCwIDxhIGhyZWY9Imh0dHBzOi8vdmsuY29tL21ldHJvcG9saWFfb2ZmaWNpYWwiIHRhcmdldD0iX2JsYW5rIj7QvtGE0LjRhtC40LDQu9GM0L3QvtC8INC60LDQvdCw0LvQtSAi0JzQtdGC0YDQvtC/0L7Qu9C40LgiINCS0LrQvtC90YLQsNC60YLQtQ0KPC9hPtCc0Ysg0L/RgNC10LTQu9Cw0LPQsNC10Lwg0LHQtdC30L7Qv9Cw0YHQvdGL0Lkg0LHQuNC30L3QtdGBLiDQnNGLINC00LDQtdC8INC90LDQtNC10LbQvdGL0LUg0LjQvdCy0LXRgdGC0LjRhtC40LguDQoNCiZuYnNwOw=='),
                'image'            => 'posts/Screen-Shot-2016-06-09-at-20.40.57.jpg',
                'slug'             => 'активная-работа-и-интересные-события',
                'meta_description' => 'this be a meta descript',
                'meta_keywords'    => 'keyword1, keyword2, keyword3',
                'status'           => 'PUBLISHED',
                'featured'         => 0,
                'created_at'       => '2016-06-10 07:00:16',
            ])->save();
        }

        $post = $this->findPost('открытия-нового-направления-эксклюз');
        if (!$post->exists) {
            $post->fill([
                'title'     => 'Открытие нового направления - эксклюзивная недвижимость',
                'author_id' => 0,
                'category_id' => Category::CATEGORY_NEWS_ID,
                'seo_title' => null,
                'excerpt'   => '<strong>Открытие нового направления - эксклюзивная недвижимость</strong>',
                'body'      => base64_decode('PHN0cm9uZz7QntGC0LrRgNGL0YLQuNC1INC90L7QstC+0LPQviDQvdCw0L/RgNCw0LLQu9C10L3QuNGPIC0g0Y3QutGB0LrQu9GO0LfQuNCy0L3QsNGPINC90LXQtNCy0LjQttC40LzQvtGB0YLRjDwvc3Ryb25nPg0KDQrQn9C+0YHQu9C10LTQvdC40LUg0L3QtdGB0LrQvtC70YzQutC+INC80LXRgdGP0YbQtdCyINC80Ysg0YPRgdC40LvQtdC90L3QviDQs9C+0YLQvtCy0LjQu9C4INC90L7QstC+0LUg0L3QsNC/0YDQsNCy0LvQtdC90LjQtSDQtNC10Y/RgtC10LvRjNC90L7RgdGC0Lgg0LPRgNGD0L/Qv9GLINC60L7QvNC/0LDQvdC40LkgTWV0cnBvcG9saWEg0Lgg0L3QsNC60L7QvdC10YYg0LPQvtGC0L7QstGLINC10LPQviDQsNC90L7QvdGB0LjRgNC+0LLQsNGC0YwuDQoNCjQg0LDQstCz0YPRgdGC0LAgMjAxNiDQs9C+0LTQsCDQsiDQnNC+0YHQutCy0LUg0LHRi9C70L4g0LfQsNGA0LXQs9C40YHRgtGA0LjRgNC+0LLQsNC90L4g0J7QsdGJ0LXRgdGC0LLQviDRgSDQvtCz0YDQsNC90LjRh9C10L3QvdC+0Lkg0L7RgtCy0LXRgtGB0YLQstC10L3QvdC+0YHRgtGM0Y4gItCc0LXRgtGA0L7Qv9C+0LvQuNGPIiwg0YHRgtCw0LLRiNC10LUg0L7RhNC40YbQuNCw0LvRjNC90YvQvCDRgNC+0YHRgdC40LnRgdC60LjQvCDQv9Cw0YDRgtC90LXRgNC+0Lwg0JrQvtC80L/QsNC90LjQuCBNZXRyb3BvbGlhIEx0ZC4NCg0KPGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgc2l6ZS1sYXJnZSB3cC1pbWFnZS0yOTgiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA5L3Bob3RvXzIwMTYtMDktMjJfMTItMjMtMzgtMS03NDR4MTAyNC5qcGciIGFsdD0icGhvdG9fMjAxNi0wOS0yMl8xMi0yMy0zOCIgd2lkdGg9Ijc0NCIgaGVpZ2h0PSIxMDI0IiAvPg0KDQo8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciBzaXplLWxhcmdlIHdwLWltYWdlLTI5OSIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDkvSU1HXzQ0MzMtMS0xMDI0eDc2OC5qcGciIGFsdD0iSU1HXzQ0MzMiIHdpZHRoPSIxMDI0IiBoZWlnaHQ9Ijc2OCIgLz4NCg0K0J7RgdC90L7QstC+0L3QvtC5INCy0LjQtCDQtNC10Y/RgtC10LvRjNC90L7RgdGC0Lgg0J7QntCeICLQnNC10YLRgNC+0L/QvtC70LjRjyIgLSDRgNC40Y3Qu9GC0L7RgNGB0LrQuNC1INGD0YHQu9GD0LPQuCwg0LIg0YfQsNGB0YLQvdC+0YHRgtC4INC/0YDQvtC00LDQttCwINC/0L4g0Y3QutGB0LrQu9GO0LfQuNCy0L3Ri9C8INCU0L7Qs9C+0LLQvtGA0LDQvCDRgSDQt9Cw0YHRgtGA0L7QudGJ0LjQutCw0LzQuCDQtNC+0YXQvtC00L3QvtC5INC90LXQtNCy0LjQttC40LzQvtGB0YLQuCwg0Y/QstC70Y/RjtGJ0LXQudGB0Y8g0LIg0YLQvtC8INGH0LjRgdC70LUg0LjQvdCy0LXRgdGC0LjRhtC40L7QvdC90L7QuSDQsNC70YzRgtC10YDQvdCw0YLQuNCy0L7QuSDRgtGA0LDQtNC40YbQuNC+0L3QvdGL0Lwg0LHQsNC90LrQvtCy0YHQutC40Lwg0LLQutC70LDQtNCw0LwuDQoNCtCe0J7QniAi0JzQtdGC0L/RgNC+0L/QvtC70LjRjyIg0L/RgNC10LTQu9Cw0LPQsNC10Lwg0YHQstC+0LjQvCDQutC70LjQtdC90YLQsNC8INC40L3QstC10YHRgtC40YDQvtCy0LDQvdC40LUg0LIg0LvQuNC60LLQuNC00L3Rg9GOINC90LXQtNCy0LjQttC40LzQvtGB0YLRjCDRgSDQs9Cw0YDQsNC90YLQuNGA0L7QstCw0L3QvdGL0Lwg0LTQvtGF0L7QtNC+0Lwg0LTQviAzNiUg0LPQvtC00L7QstGL0YUg0LIg0YDRg9Cx0LvRj9GFINC90LAg0L7RgdC90L7QstCw0L3QuNC4INC00L7Qs9C+0LLQvtGA0L7QsiDQv9GA0L7RhtC10L3RgtC90L7Qs9C+INC30LDQudC80LAuINCe0YHQvdC+0LLQvdGL0Lwg0L/RgNC10LjQvNGD0YnQtdGB0YLQstC+0Lwg0YLQsNC60L7Qs9C+INC40L3QstC10YHRgtC40YDQvtCy0LDQvdC40Y8g0Y/QstC70Y/QtdGC0YHRjyDQvNC40L3QuNC80LDQu9GM0L3Ri9C5ICLQstGF0L7QtCIg0LIg0YDQsNC30LzQtdGA0LUg0LzQtdC90LXQtSAkMTAwMCAo0LIg0YDRg9Cx0LvQtdCy0L7QvCDRjdC60LLQuNCy0LDQu9C10L3RgtC1KSwg0LAg0YLQsNC60LbQtSDQs9Cw0YDQsNC90YLQuNC4wqDQsiDQstC40LTQtSDQodGC0YDQsNGF0L7QstC+0LPQviDQpNC+0L3QtNCwINCa0L7QvNC/0LDQvdC40LggTWV0cm9wb2xpYSBMdGQuDQoNCtCa0L7QvNC/0LDQvdC40Lkg0YPQttC1INC30LDQutC70Y7Rh9C10L0g0YDRj9C0INGN0LrRgdC60LvRjtC30LjQstC90YvRhSDQtNC+0LPQvtCy0L7RgNC+0LIg0YEg0LfQsNGB0YLRgNC+0LnRidC40LrQsNC80LgsINCyINGC0L7QvCDRh9C40YHQu9C1IC0g0LTQvtCz0L7QstC+0YAg0Y3QutGB0LrQu9GO0LfQuNCy0L3QvtC5INC00LjRgdGC0YDQuNCx0YzRjtGG0LjQuCDQvdCwINGC0LXRgNGA0LjRgtC+0YDQuNC4INCh0J3QkyDRgSDQvtC00L3QuNC8INC40Lcg0LrRgNGD0L/QvdC10LnRiNC40YUg0LDQs9C10L3RgtGB0YLQsiDQv9C+INC/0YDQvtC00LDQttC1INC60YPRgNC+0YDRgtC90L7QuSDQvdC10LTQstC40LbQuNC80L7RgdGC0LggLSDQutC+0LzQv9Cw0L3QuNC4IEludGVybmF0aW9uYWwgUGFydG5lcnNoaXAgUHJvZ3JhbSAoSVBQKS4NCg0KPGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgc2l6ZS1mdWxsIHdwLWltYWdlLTMwMCIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDkvSVBQLUxvbmctQmVhY2gtTWF5MTUtMS5qcGciIGFsdD0iSVBQLUxvbmctQmVhY2gtTWF5MTUiIHdpZHRoPSI5NjAiIGhlaWdodD0iNjQwIiAvPg0KDQo8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciBzaXplLWxhcmdlIHdwLWltYWdlLTMwMSIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDkvcGhvdG9fMjAxNi0wOS0yMl8xMi0yNC0zMy0xLTEwMjR4NzI4LmpwZyIgYWx0PSJwaG90b18yMDE2LTA5LTIyXzEyLTI0LTMzIiB3aWR0aD0iMTAyNCIgaGVpZ2h0PSI3MjgiIC8+DQoNCtChINC90LDRgdGC0L7Rj9GJ0LXQs9C+INC80L7QvNC10L3RgtCwINC/0LDRgNGC0L3QtdGA0Ysg0LrQvtC80L/QsNC90LjQuCDQvNC10YLRgNC+0L/QvtC70LjRjyDQvNC+0LPRg9GCINC90LUg0YLQvtC70YzQutC+INGB0L7QstC10YDRiNCw0YLRjCDQstGL0LPQvtC00L3Ri9C1INC40L3QstC10YHRgtC40YbQuNC4INCyINGA0YPQsdC70Y/RhSDQuCDQutGA0LjQv9GC0L7QstCw0LvRjtGC0LUsINC90L4g0Lgg0L/RgNC40L7QsdGA0LXRgtCw0YLRjCDQvdC10LTQstC40LbQuNC80L7RgdGC0Ywg0L/QviDRhtC10L3QsNC8LCDQstC+INC80L3QvtCz0L4g0L3QuNC20LUg0YDRi9C90L7Rh9C90YvRhS4g0JHQvtC70LXQtSDRgtC+0LPQviAtINC/0LDRgNGC0L3QtdGA0Ysg0LrQvtC80L/QsNC90LjQuCDQvNC+0LPRg9GCINGB0YLQsNGC0Ywg0L/QvtC70L3QvtGG0LXQvdC90YvQvNC4INGA0LjQtdC00YLQvtGA0LDQvNC4INC4INC/0L7Qu9GD0YfQsNGC0Ywg0LrQvtC80LjRgdGB0LjQvtC90L3QvtC1INCy0L7Qt9C90LDQs9GA0LDQttC00LXQvdC40LUg0LfQsCDQv9GA0L7QtNCw0LbRgyDQvtCx0YrQtdC60YLQvtCyINCyINGA0LDQt9C80LXRgNC1INC00L4gNiUg0L7RgiDQuNGFINGB0YLQvtC40LzQvtGB0YLQuCwg0LLQutC70Y7Rh9Cw0Y8g0LzQvdC+0LPQvtGD0YDQvtCy0L3QtdCy0YPRjiDQv9Cw0YDRgtC90LXRgNGB0LrRg9GOINC/0YDQvtCz0YDQsNC80LzRgy4NCg0K0JHQvtC70LXQtSDQv9C+0LTRgNC+0LHQvdGD0Y4g0LjQvdGE0L7RgNC80LDRhtC40Y4g0L7QsdGK0LXQutGC0LDRhSDQvdC10LTQstC40LbQuNC80L7RgdGC0Lgg0LrQvtC80L/QsNC90LjQuMKg0J7QntCeICLQnNC10YLRgNC+0L/QvtC70LjRjyIg0LzQvtC20L3QviDQv9C+0LvRg9GH0LjRgtGMINC90LAg0YHQsNC50YLQtTogd3d3LtC80LXRgtGA0L7Qv9C+0LvQuNGPLtGA0YPRgQ=='),
                'image'            => 'posts/IMG_4433-1.jpg',
                'slug'             => 'открытия-нового-направления-эксклюз',
                'meta_description' => 'this be a meta descript',
                'meta_keywords'    => 'keyword1, keyword2, keyword3',
                'status'           => 'PUBLISHED',
                'featured'         => 0,
                'created_at'       => '2016-09-22 12:43:57',
            ])->save();
        }

        $post = $this->findPost('новый-партнер-компании-ооо-регион-ко');
        if (!$post->exists) {
            $post->fill([
                'title'     => 'Новый партнер компании - ООО "Регион Контракт"',
                'author_id' => 0,
                'category_id' => Category::CATEGORY_NEWS_ID,
                'seo_title' => null,
                'excerpt'   => 'Заключен договор между Metropolia LTD и ООО "Регион Контракт" - крупным холдингом в сфере производства и оптовой реализации в нефте-газовом секторе.',
                'body'      => base64_decode('0JfQsNC60LvRjtGH0LXQvSDQtNC+0LPQvtCy0L7RgCDQvNC10LbQtNGDIE1ldHJvcG9saWEgTFREINC4INCe0J7QniAi0KDQtdCz0LjQvtC9INCa0L7QvdGC0YDQsNC60YIiIC0g0LrRgNGD0L/QvdGL0Lwg0YXQvtC70LTQuNC90LPQvtC8INCyINGB0YTQtdGA0LUg0L/RgNC+0LjQt9Cy0L7QtNGB0YLQstCwINC4INC+0L/RgtC+0LLQvtC5INGA0LXQsNC70LjQt9Cw0YbQuNC4INCyINC90LXRhNGC0LUt0LPQsNC30L7QstC+0Lwg0YHQtdC60YLQvtGA0LUuwqDQkdGL0LvQsCDQv9GA0L7QstC10LTQtdC90LAg0L/RgNC+0LTRg9C60YLQuNCy0L3QsNGPINCy0YHRgtGA0LXRh9CwINGBINGA0YPQutC+0LLQvtC00YHRgtCy0L7QvCDQutC+0LzQv9Cw0L3QuNC4LiDQn9C+0LvRg9GH0LXQvdGLINGE0L7RgtC+LdC80LDRgtC10YDQuNCw0LvRiyDQuCDQvtGC0YfQtdGC0Ysg0L4g0YDQsNCx0L7RgtC1INC+0YDQs9Cw0L3QuNC30LDRhtC40Lgg0LfQsCDQv9C+0YHQu9C10LTQvdC40Lkg0LPQvtC0Lg0KDQo8IS0tbW9yZS0tPg0KDQo8ZW0+4oCiINCa0L7QvNC/0LDQvdC40Y8g0YHQvtGC0YDRg9C00L3QuNGH0LDQtdGCINC4INC40LzQtdC10YIgPGEgaHJlZj0iaHR0cHM6Ly9tZXRyb3BvbGlhLm9yZy9wcm9qZWN0cy9tb3JlIj7Qt9Cw0LrQu9GO0YfQtdC90L3Ri9C1INC00L7Qs9C+0LLQvtGA0LA8L2E+INGB0L4g0KHQu9Cw0LLRj9C90YHQutC40LwsINCY0LvRjNC40L3RgdC60LjQvCwg0JzQsNGA0LjQudGB0LrQuNC5INC4INC00YDRg9Cz0LjQvNC4INCd0J/QlyDQuCDQv9GA0L7QuNC30LLQvtC00LjRgtC10LvRj9C80LguIDwvZW0+DQo8ZW0+4oCiINCg0LXQsNC70LjQt9Cw0YbQuNGPINGB0YvRgNGM0Y8g0L7RgdGD0YnQtdGB0YLQstC70Y/QtdGC0YHRjyDQvdC1INGC0LXRgNGA0LjRgtC+0YDQuNC4wqDQnNC+0YHQutCy0YssINCc0L7RgdC60L7QstGB0LrQvtC5LCDQkdGA0Y/QvdGB0LrQvtC5INC4INCS0L7RgNC+0L3QtdC20YHQutC+0Lkg0L7QsdC70LDRgdGC0LXQuS4gPC9lbT4NCjxlbT7igKIg0JTQvtGF0L7QtNC90L7RgdGC0Ywg0LHQuNC30L3QtdGB0LAg0YHQvtGB0YLQsNCy0LvRj9C10YLCoNC+0YIgMjAg0LTQviAzMCUg0LIg0LfQsNCy0LjRgdC40LzQvtGB0YLQuCDQvtGCINGB0LXQt9C+0L3QsC48L2VtPg0KDQrQk9C70LDQstC90L7QuSDQv9GA0LjRh9C40L3QvtC5INCy0YvQsdC+0YDQsCDQsdC40LfQvdC10YEg0L/RgNC+0LXQutGC0LAgItCg0LXQs9C40L7QvSDQmtC+0L3RgtGA0LDQutGCIiwg0LrQsNC6INC+0LTQvdC+0LPQviDQuNC3INC/0LXRgNCy0YvRhSDQv9Cw0YDRgtC90LXRgNC+0LIg0LrQvtC80L/QsNC90LjQuCwg0L/QvtGB0LvRg9C20LjQu9CwINCy0YvRgdC+0LrQsNGPINGB0LXQt9C+0L3QvdCw0Y8g0LTQvtGF0L7QtNC90L7RgdGC0YwsINGP0LLQvdGL0LUg0L/QtdGA0YHQv9C10LrRgtC40LLRiyDRgNCw0LfQstC40YLQuNGPINCx0LjQt9C90LXRgdCwINC4INGA0LDRgdGI0LjRgNC10L3QuNGPINGB0LXRgtC4INC/0L7RgdGA0LXQtNGB0YLQstC+0Lwg0L/RgNC40LLQu9C10YfQtdC90LjRjyDQutGA0YPQv9C90YvRhSDQuNC90LLQtdGB0YLQuNGG0LjQuSDRh9C10YDQtdC3wqBDTUIg0L/Qu9Cw0YLRhNC+0YDQvNGDICLQnNC10YLRgNC+0L/QvtC70LjRjyIuDQoNCtCS0YHRjiDQv9C+0LTRgNC+0LHQvdGD0Y4g0LjQvdGE0L7RgNC80LDRhtC40Y4g0L4g0LTQvtC60YPQvNC10L3RgtCw0YbQuNC4INC60L7QvNC/0LDQvdC40LgsINGD0YfRgNC10LTQuNGC0LXQu9GM0L3Ri9C1INC00L7QutGD0LzQtdC90YLRiywg0L/RgNCw0LLQsCDQvdCwINGB0L7QsdGB0YLQstC10L3QvdC+0YHRgtGMLCDQstGLINC80L7QttC10YLQtSDQuNC30YPRh9C40YLRjCA8YSBocmVmPSJodHRwczovL21ldHJvcG9saWEub3JnL3Byb2plY3RzIiB0YXJnZXQ9Il9ibGFuayI+0LIg0YDQsNC30LTQtdC70LUgItC/0L7RgNGC0YTQvtC70LjQviI8L2E+INCy0YvQsdGA0LDQsiDRgdC+0L7RgtCy0LXRgtGB0YLQstGD0Y7RidC40Lkg0LHQuNC30L3QtdGBINC/0YDQvtC10LrRgi4='),
                'image'            => '',
                'slug'             => 'новый-партнер-компании-ооо-регион-ко',
                'meta_description' => 'this be a meta descript',
                'meta_keywords'    => 'keyword1, keyword2, keyword3',
                'status'           => 'PUBLISHED',
                'featured'         => 0,
                'created_at'       => '2016-04-04 20:25:55',
            ])->save();
        }

        $post = $this->findPost('первый-круглый-стол-для-бизнес-владел');
        if (!$post->exists) {
            $post->fill([
                'title'     => 'Круглый стол "Развитие механизмов CMB в СНГ"',
                'author_id' => 0,
                'category_id' => Category::CATEGORY_NEWS_ID,
                'seo_title' => null,
                'excerpt'   => 'В понедельник 4 апреля на базе офиса компании Metropolia LTD в Москве (деловой центр Москва-Сити) состоялось первое экспертное обусждения продвижения и развития механизма CMB (Crowd meets business) в СНГ.',
                'body'      => base64_decode('0JIg0L/QvtC90LXQtNC10LvRjNC90LjQuiA0INCw0L/RgNC10LvRjyDQvdCwINCx0LDQt9C1INC+0YTQuNGB0LAg0LrQvtC80L/QsNC90LjQuCBNZXRyb3BvbGlhIExURCDQsiDQnNC+0YHQutCy0LUgKNC00LXQu9C+0LLQvtC5INGG0LXQvdGC0YAg0JzQvtGB0LrQstCwLdCh0LjRgtC4KSDRgdC+0YHRgtC+0Y/Qu9C+0YHRjCDQv9C10YDQstC+0LUg0Y3QutGB0L/QtdGA0YLQvdC+0LUg0L7QsdGD0YHQttC00LXQvdC40Y8g0L/RgNC+0LTQstC40LbQtdC90LjRjyDQuCDRgNCw0LfQstC40YLQuNGPINC80LXRhdCw0L3QuNC30LzQsCBDTUIgKENyb3dkIG1lZXRzIGJ1c2luZXNzKSDQsiDQodCd0JMuINCR0YvQu9C4INC/0YDQuNCz0LvQsNGI0LXQvdGLINC/0YDQtdC00YHRgtCw0LLQuNGC0LXQu9C4INGA0LDQt9C70LjRh9C90YvRhSDQsdC40LfQvdC10YEt0L7RgtGA0LDRgdC70LXQuSDQuCDRgdGC0YDRg9C60YLRg9GAINGBINGG0LXQu9GM0Y4g0YTQvtGA0LzQuNGA0L7QstCw0L3QuNGPINC80LDQutGB0LjQvNCw0LvRjNC90L4g0L/QvtC70L3QvtCz0L4g0Lgg0LLRgdC10YHRgtC+0YDQvtC90L3QtdCz0L4g0L7QsdGB0YPQttC00LXQvdC40Y8g0L/QtdGA0YHQv9C10LrRgtC40LIsINGN0YLQsNC/0L7QsiDRgNCw0LfQstC40YLQuNGPINC4ICLQv9C+0LTQstC+0LTQvdGL0YUg0LrQsNC80L3QtdC5Ii4NCtCY0YLQvtCz0Lgg0L7QsdGB0YPQttC00LXQvdC40Y8sINC/0YDQtdC00LvQvtC20LXQvdC40Y8g0Lgg0LfQsNC80LXRh9Cw0L3QuNGPINCx0YvQu9C4INGB0YTQvtGA0LzQuNGA0L7QstCw0L3RiyDQsiDQstC40LTQtSDQv9GA0L7RgtC+0LrQvtC70LAg0Lgg0L/RgNC40L3Rj9GC0Ysg0LrQvtC80L/QsNC90LjQuSDQtNC70Y8g0L7QsdGA0LDQsdC+0YLQutC4INC4INCy0L3QtdC00YDQtdC90LjRjy4NCjwhLS1tb3JlLS0+DQoNCjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTk0IiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNC9JTUdfMjAxNi0wNC0wNS0wOTA5MjUtMTAyNHg1MTAuanBnIiBhbHQ9IklNR18yMDE2LTA0LTA1IDA5OjA5OjI1IiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjMyMyIgLz4NCg0K0J7RgdC90L7QstC90YvQtSDQs9C+0YHRgtC4INC4INCy0LXQtNGD0YnQuNC1INC80LXRgNC+0L/RgNC40Y/RgtC40Y86DQoNCjxzdHJvbmc+0JHQsNGC0YvRgCDQmNGB0LDQsdCw0LXQsiA8L3N0cm9uZz4tINCS0L7QtdC90L3Ri9C5INGI0YLRg9GA0LzQsNC9LiDQl9Cw0LzQtdGB0YLQuNGC0LXQu9GMINC90LDRh9Cw0LvRjNC90LjQutCwINC00LXQv9Cw0YDRgtCw0LzQtdC90YLQsCDRgdC+0YXRgNCw0L3QtdC90LjRjyDRjdGC0L3QvtCz0YDQsNGE0LjRh9C10YHQutC+0LPQviDQuCDQtNGD0YXQvtCy0L3QvtCz0L4g0L3QsNGB0LvQtdC00LjRjyDQsiDQutC+0LzQuNGB0YHQuNC4INC/0L4g0LHQvtGA0YzQsdC1INGBINC60L7RgNGA0YPQv9GG0LjQtdC5LiDQoNGD0LrQvtCy0L7QtNC40YLQtdC70Ywg0LrQvtC80LjRgtC10YLQsCDQv9C+INGB0L7RhtC40LDQu9GM0L3QvtC5INC/0L7Qu9C40YLQuNC60LUg0LIg0JzQntCeINCc0JDQnyAo0JzQtdC20YDQtdCz0LjQvtC90LDQu9GM0L3QsNGPINCe0LHRidC10YHRgtCy0LXQvdC90LDRjyDQntGA0LPQsNC90LjQt9Cw0YbQuNGPINCc0L7RgdC60L7QstGB0LrQsNGPINCQ0YHRgdC+0YbQuNCw0YbQuNGPINCf0YDQtdC00L/RgNC40L3QuNC80LDRgtC10LvQtdC5KS4g0J7QsdC70LDQtNCw0YLQtdC70Ywg0L/QtdGA0LLQvtC5INGE0YDQsNC90YjQuNC30Ysg0LrQvtC80L/QsNC90LjQuCBNZXRyb3BvbGlhwqBMVEQg0LIg0KDQvtGB0YHQuNC50YHQutC+0Lkg0KTQtdC00LXRgNCw0YbQuNC4Lg0KDQo8c3Ryb25nPtCc0LDQvNC40Lkg0JDQu9C10LrRgdCw0L3QtNGAINCR0L7RgNC40YHQvtCy0LjRhzwvc3Ryb25nPiDigJMg0Y7RgNC40YHRgiwgMTgg0LvQtdGCINC/0YDQsNC60YLQuNC60LgsINC90Lgg0L7QtNC90L7Qs9C+INC/0YDQvtC40LPRgNCw0L3QvdC+0LPQviDQtNC10LvQsC4gKNCd0LDQv9GA0LDQstC70LXQvdC40LUg4oCTINC60L7RgNGA0YPQv9GG0LjRjywg0LzQvtGI0LXQvdC90LjRh9C10YHRgtCy0L4sINCW0JrQpS4pDQoNCjxzdHJvbmc+0KPQs9C70LjRh9C40L0g0JLQu9Cw0LTQuNC80LjRgCDQmNCy0LDQvdC+0LLQuNGHPC9zdHJvbmc+IOKAkyDQn9GA0LXQtNC/0YDQuNC90LjQvNCw0YLQtdC70YwswqDQoNC10LbQuNGB0YHQtdGALCDQodGG0LXQvdCw0YDQuNGB0YIuINCh0YLRgNC+0LjRgtC10LvRjNGB0YLQstC+INC60YDRg9C/0L3Ri9GFINC+0LHRitC10LrRgtC+0LIg0LIg0JzQvtGB0LrQstC1Lg0KDQo8c3Ryb25nPtCa0YDRg9GC0L7QsiDQlNC80LjRgtGA0LjQuSDQktCw0YHQuNC70YzQtdCy0LjRhzwvc3Ryb25nPiDigJMg0L/QvtC80L7RidC90LjQuiDQtNC10L/Rg9GC0LDRgtCwINCz0L7RgdGD0LTQsNGA0YHRgtCy0LXQvdC90L7QuSDQtNGD0LzRiywg0LrRgNGD0LPQu9GL0LUg0YHRgtC+0LvRiyDQsiDQs9C+0YHRg9C00LDRgNGB0YLQstC10L3QvdC+0Lkg0LTRg9C80LUuINCf0LXRgNC10LTQsNGH0LAgwqvQk9C+0LLQvtGA0Y/RgiDQn9GA0L7RhNC10YHRgdC40L7QvdCw0LvRi8K7INC90LAg0L3QsNGA0L7QtNC90L7QvCDRgNCw0LTQuNC+Lg0KDQo8c3Ryb25nPtCT0LXQvtGA0LPQuNC5INCk0LDRgNC00LfQuNC90L7Qsjwvc3Ryb25nPiDigJMg0JjQvdC00LjQstC40LTRg9Cw0LvRjNC90YvQuSDQv9GA0LXQtNC/0YDQuNC90LjQvNCw0YLQtdC70YwsINCw0LLRgtC+0YAg0Lgg0LLQtdC00YPRidC40Lkg0YLQtdC70LXQv9GA0L7QtdC60YLQsCAi0KHQtdC60YDQtdGC0Ysg0KPRgdC/0LXRhdCwIiwg0L/RgNC+0LXQutGCIMKr0KTQsNCx0YDQuNC60LAg0JfQstGR0LfQtMK7LCDQstC40LTQtdC+LdC/0YDQvtC10LrRgiA8YSBocmVmPSJodHRwOi8veG4tLWIxYWVka3NtZXQueG4tLXAxYWkvIiB0YXJnZXQ9Il9ibGFuayI+0JzQvtGB0LLQuNC00LXQvi7RgNGEPC9hPg0KDQo8c3Ryb25nPtCa0LDQudC80LDQvSDQodGD0LvQtdC50LzQsNC90L7QstC40Yc8L3N0cm9uZz4NCg0KPHN0cm9uZz7QkNC90LTRgNC10Lkg0KHRgtCw0L3RjNC60L48L3N0cm9uZz4g4oCTINGC0LDQvNC+0LbQtdC90L3QvtC1INC+0YTQvtGA0LzQu9C10L3QuNC1ICjQsdGA0L7QutC10YAt0YTRgNC40LvQsNC90YEpLg0KDQo8c3Ryb25nPtCS0Y/Rh9C10YHQu9Cw0LIg0JPRg9GB0LXQsjwvc3Ryb25nPiDigJPCq9Ci0JzQmiDQk9GA0YPQv9C/wrssINGA0YPQutC+0LLQvtC00LjRgtC10LvRjCDQv9GA0L7QtdC60YLQvtCywqDQsiDQodC60L7Qu9C60L7QstC+Lg0KDQo8c3Ryb25nPtCQ0LvQtdC60YHQtdC5INCh0LzQvtGA0L7QtNC40L08L3N0cm9uZz4g4oCTINCh0KDQniwg0YHQtdGA0LjQudC90YvQuSDQv9GA0LXQtNC/0YDQuNC90LjQvNCw0YLQtdC70YwuDQoNCjxociAvPg0KDQo8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0xMDUiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA0L0HQkkEtMTAyNHg1MTAuanBnIiBhbHQ9IkHQkkEiIHdpZHRoPSI2NTAiIGhlaWdodD0iMzIzIiAvPg0KDQo8aHIgLz4NCg0K0J3QvtCy0YvQtSDQvNC10YDQvtC/0YDQuNGP0YLQuNGPINC/0YDQvtGF0L7QtNGP0YIgMiDRgNCw0LfQsCDQsiDQvdC10LTQtdC70Y4g0LIg0LPQu9Cw0LLQvdC+0Lwg0L7RhNC40YHQtSDQutC+0LzQv9Cw0L3QuNC4INCyINC60L7QvNC/0LvQtdC60YHQtSDQnNC+0YHQutCy0LAt0KHQuNGC0LguINCR0YPQtNGD0YnQuNC1INC/0LDRgNGC0L3QtdGA0YssINC40L3QstC10YHRgtC+0YDRiywg0Lgg0LvQuNC00LXRgNGLLCDQttC10LvQsNGO0YnQuNC1INC+0LrQsNC30LDRgtGM0YHRjyDRgyDQuNGB0YLQvtC60L7QsiDRgNCw0LfQstC40YLQuNGPICLQnNC10YLRgNC+0L/QvtC70LjQuCIg0LzQvtCz0YPRgiDQv9C+0LTQsNGC0Ywg0LfQsNGP0LLQutGDINC90LAg0L/QtdGA0YHQvtC90LDQu9GM0L3Rg9GOINCy0YHRgtGA0LXRh9GDINC4INGD0YfQsNGB0YLQuNC1INCyIFZJUCDQutGA0YPQs9C70L7QvCDRgdGC0L7Qu9C1Lg0KPHN0cm9uZz7QntGB0YLQsNCy0YzRgtC1INGB0LLQvtGOINC30LDRj9Cy0LrRgyDQvdCwINGB0L7QsdC10YHQtdC00L7QstCw0L3QuNC1wqDQsiA8YSBocmVmPSJodHRwczovL21ldHJvcG9saWEub3JnL2NvbnRhY3QiIHRhcmdldD0iX2JsYW5rIj7RgNCw0LfQtNC10LvQtSDQutC+0L3RgtCw0LrRgtGLLjwvYT48L3N0cm9uZz4NCg0KPGhyIC8+DQoNCjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTEwMiIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDQvMi0xLWNvcHktMi0xMDI0eDYwMy5qcGciIGFsdD0iMiAoMSkgY29weSIgd2lkdGg9IjY1MCIgaGVpZ2h0PSIzODMiIC8+DQoNCiZuYnNwOw=='),
                'image'            => '',
                'slug'             => 'первый-круглый-стол-для-бизнес-владел',
                'meta_description' => 'this be a meta descript',
                'meta_keywords'    => 'keyword1, keyword2, keyword3',
                'status'           => 'PUBLISHED',
                'featured'         => 0,
                'created_at'       => '2016-04-07 21:00:29',
            ])->save();
        }

        $post = $this->findPost('партнер-компании-ооо-регион-контакт');
        if (!$post->exists) {
            $post->fill([
                'title'     => 'Подписание контракта с ООО "Регион-Контакт"',
                'author_id' => 0,
                'category_id' => Category::CATEGORY_NEWS_ID,
                'seo_title' => null,
                'excerpt'   => 'Были успешно подписаны финальные пакеты документов с текущим партнером компании, нефтегазовым комплексом "Регион-Контакт". Встреча прошла в главном офисе компании в Москве, где в дружественной атмосфере были подписаны соглашения о сотрудничестве между обоими организациями.',
                'body'      => base64_decode('0JHRi9C70Lgg0YPRgdC/0LXRiNC90L4g0L/QvtC00L/QuNGB0LDQvdGLINGE0LjQvdCw0LvRjNC90YvQtSDQv9Cw0LrQtdGC0Ysg0LTQvtC60YPQvNC10L3RgtC+0LIg0YEg0YLQtdC60YPRidC40Lwg0L/QsNGA0YLQvdC10YDQvtC8INC60L7QvNC/0LDQvdC40LgsINC90LXRhNGC0LXQs9Cw0LfQvtCy0YvQvCDQutC+0LzQv9C70LXQutGB0L7QvCAi0KDQtdCz0LjQvtC9LdCa0L7QvdGC0LDQutGCIi4g0JLRgdGC0YDQtdGH0LAg0L/RgNC+0YjQu9CwINCyINCz0LvQsNCy0L3QvtC8INC+0YTQuNGB0LUg0LrQvtC80L/QsNC90LjQuCDQsiDQnNC+0YHQutCy0LUsINCz0LTQtSDQsiDQtNGA0YPQttC10YHRgtCy0LXQvdC90L7QuSDQsNGC0LzQvtGB0YTQtdGA0LUg0LHRi9C70Lgg0L/QvtC00L/QuNGB0LDQvdGLINGB0L7Qs9C70LDRiNC10L3QuNGPINC+INGB0L7RgtGA0YPQtNC90LjRh9C10YHRgtCy0LUg0LzQtdC20LTRgyDQvtCx0L7QuNC80Lgg0L7RgNCz0LDQvdC40LfQsNGG0LjRj9C80LguwqA8IS0tbW9yZS0tPg0KDQo8aHIgLz4NCg0KW2VtYmVkXWh0dHBzOi8vd3d3LnlvdXR1YmUuY29tL3dhdGNoP3Y9WjRzQWpUSzU0Tmsmbm9odG1sNT1GYWxzZVsvZW1iZWRdDQoNCjxpbWcgY2xhc3M9IndwLWltYWdlLTExMyBhbGlnbmxlZnQiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA0L142QzA1RjlGOEQ0M0UwRTk1ODk2MTc1MjQ3MjcwODk1LTc0NXgxMDI0LmpwZyIgYWx0PSJeNkMwNUY5RjhENDNFMEU5NTg5NjE3NTI0NzI3MDg5NSIgd2lkdGg9IjQwMCIgaGVpZ2h0PSI1NTAiIC8+DQoNCjxpbWcgY2xhc3M9IndwLWltYWdlLTExNSBhbGlnbmxlZnQiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA0L15EOEVBMTJBQTcxNTNBMDlGRDNDRTkyMkI1QUMxOUFGLTc0NXgxMDI0LmpwZyIgYWx0PSJeRDhFQTEyQUE3MTUzQTA5RkQzQ0U5MjJCNUFDMTlBRiIgd2lkdGg9IjQ1MCIgaGVpZ2h0PSI2MTkiIC8+PGltZyBjbGFzcz0id3AtaW1hZ2UtMTE0IGFsaWdubGVmdCIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDQvXjdDQjM4NkU1QjFDQTIwMEE1OEFGRjNERDY4OTg1ODAtNzQ1eDEwMjQuanBnIiBhbHQ9Il43Q0IzODZFNUIxQ0EyMDBBNThBRkYzREQ2ODk4NTgwIiB3aWR0aD0iNDUwIiBoZWlnaHQ9IjYxOSIgLz48aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0xMTEiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA0L0lNR18xNDY1LTEwMjR4NzY4LmpwZyIgYWx0PSJJTUdfMTQ2NSIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0ODgiIC8+DQoNCiZuYnNwOw=='),
                'image'            => '',
                'slug'             => 'партнер-компании-ооо-регион-контакт',
                'meta_description' => 'this be a meta descript',
                'meta_keywords'    => 'keyword1, keyword2, keyword3',
                'status'           => 'PUBLISHED',
                'featured'         => 0,
                'created_at'       => '2016-04-09 23:55:59',
            ])->save();
        }

        $post = $this->findPost('123');
        if (!$post->exists) {
            $post->fill([
                'title'     => 'Фото-отчет с закрытого мероприятия "VIP M-Партнер"',
                'author_id' => 0,
                'category_id' => Category::CATEGORY_NEWS_ID,
                'seo_title' => null,
                'excerpt'   => 'Начало работы Метрополии в России. Успешно проведено мероприятие для будущих бизнес партнеров и инвесторов компании. Результативное событие..',
                'body'      => base64_decode('0J3QsNGH0LDQu9C+INGA0LDQsdC+0YLRiyDQnNC10YLRgNC+0L/QvtC70LjQuCDQsiDQoNC+0YHRgdC40LguINCj0YHQv9C10YjQvdC+INC/0YDQvtCy0LXQtNC10L3QviDQvNC10YDQvtC/0YDQuNGP0YLQuNC1INC00LvRjyDQsdGD0LTRg9GJ0LjRhSDQsdC40LfQvdC10YEg0L/QsNGA0YLQvdC10YDQvtCyINC4INC40L3QstC10YHRgtC+0YDQvtCyINC60L7QvNC/0LDQvdC40LguINCg0LXQt9GD0LvRjNGC0LDRgtC40LLQvdC+0LUg0YHQvtCx0YvRgtC40LUuINCd0L7QstGL0LUg0LTQvtCz0L7QstC+0YDQsCDRgSDQv9GA0L7QtdC60YLQsNC80LgsINC60L7QvdGC0YDQsNC60YLRiywg0LHQuNC30L3QtdGBINCy0LvQsNC00LXQu9GM0YbRiyDQuCDQv9GA0LXQtNC/0YDQuNC90LjQvNCw0YLQtdC70LgsINCz0L7RgtC+0LLRi9C1INC6INGB0L7RgtGA0YPQtNC90LjRh9C10YHRgtCy0YMsINCwINGC0LDQuiDQttC1INC60YDRg9C/0L3Ri9C1INC40L3QstC10YHRgtC+0YDRiy4NCg0KPCEtLW1vcmUtLT4NCg0KPGhyIC8+DQoNCjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTE0MyIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDQvTUVFVDgtNS0xMDI0eDUzNy5qcGciIGFsdD0iTUVFVDgtNSIgd2lkdGg9IjY1MCIgaGVpZ2h0PSIzNDEiIC8+DQoNCtCf0L7QtNCz0L7RgtC+0LLQutCwINC6INC80LXRgNC+0L/RgNC40Y/RgtC40Y4g0L/RgNC+0YXQvtC00LjQu9CwINCyINGC0LXRh9C10L3QuNC4IDIt0YUg0LTQvdC10LkuwqDQkiDRgtC10YfQtdC90LjQuCDQvdC10LTQtdC70Lgg0LHRi9C70Lgg0L/RgNC40LPQu9Cw0YjQtdC90Ysg0LLQsNC20L3Ri9C1INGE0LjQs9GD0YDRiyDQv9C+0LvQuNGC0LjRh9C10YHQutC+0Lkg0LTQtdGP0YLQtdC70YzQvdC+0YHRgtC4LCDRgNCw0LfQu9C40YfQvdGL0YUg0LHQuNC30L3QtdGBINC+0YLRgNCw0YHQu9C10Lkg0Lgg0LzQtdC00LjQsCwg0YHQvtGG0LjQsNC70YzQvdC+0Lkg0LDQutGC0LjQstC90L7RgdGC0Lgg0L7QsdGJ0LXRgdGC0LLQsC4g0J7RgtGH0LjRidC10L3QvdCw0Y8g0LjQvtC90LjQt9C40YDQvtCy0LDQvdC90LDRjyDQstC+0LTQsCAi0JzQtdGC0YDQvtC/0L7Qu9C40Y8iINC/0L7RgdC70YPQttC40LvQsCDQvtGC0LvQuNGH0L3Ri9C8INC90LDRh9Cw0LvQvtC8INC/0LXRgNC10LQg0L/RgNCw0LfQtNC90LjRh9C90YvQvCDRgdGC0L7Qu9C+0LwuDQoNCtCe0YHQvdC+0LLQvdGL0LUg0LPQvtGB0YLQuCDQvNC10YDQvtC/0YDQuNGP0YLQuNGPOg0KDQo8c3Ryb25nPtCX0LDQtNGA0LDQvdC+0Lwg0JDQsdC00YPQuyDQkNC70Lg8L3N0cm9uZz4NCtCn0LvQtdC9INGB0L7QstC10YLQsCDQtNC40YDQtdC60YLQvtGA0L7QsiDQvNC10LbQtNGD0L3QsNGA0L7QtNC90YvRhSDRgdC10YLQuCDRjdC60YHQv9C10YDRgtC+0LIg0KHQvtGO0LfQutC+0L3RgdCw0LvRgiwg0JPQtdC9LtC00LjRgNC10LrRgtC+0YAg0L7QvtC+INGC0LXRhdC/0YDQvtC8LCDQntCe0J4g0L/Qu9Cw0L3QtdGC0LAg0L/QvtGB0YPQtNGLDQrQlNC40YDQtdC60YLQvtGAIMK3INChIDIwMTAg0LMuINC/0L4g0L3QsNGB0YLQvtGP0YnQtdC1INCy0YDQtdC80Y8NCg0KPHN0cm9uZz7QmtC+0L3QvtC90LLQsCDQkNC70LXQvdCwPC9zdHJvbmc+DQrQv9C+0LzQvtGJ0L3QuNC6INCy0LjRhtC1LdC/0YDQtdC30LjQtNC10L3RgtCwINCyINCQ0YHRgdC+0YbQuNCw0YbQuNGPINC+0YDQs9Cw0L3QuNC30LDRhtC40Lkg0LIg0YHRhNC10YDQtSDRjdC60L7QvdC+0LzQuNGH0LXRgdC60L7QuSDQsdC10LfQvtC/0LDRgdC90L7RgdGC0Lgg0Lgg0L/RgNC+0YLQuNCy0L7QtNC10LnRgdGC0LLQuNGPINC60L7RgNGA0YPQv9GG0LjQuCDCq9CR0LXQt9C+0L/QsNGB0L3QvtGB0YLRjCDQkdC40LfQvdC10YHQsMK7ICjRgNC10LPQuNC+0L3QsNC70YzQvdGL0YUg0KLQn9CfINCg0KQpDQoNCjxzdHJvbmc+0JDQu9C10LrRgdCw0L3QtNGA0L7QstGB0LrQuNC5INCQ0LvQtdC60YHQsNC90LTRgCDQmNGB0LDQutC+0LLQuNGHPC9zdHJvbmc+DQrQoNGD0LrQvtCy0L7QtNC40YLQtdC70Ywg0JHQuNC30L3QtdGBLdC60LvRg9Cx0LANCg0KPHN0cm9uZz7QnNC+0YHQutC+0LLRgdC60LjQuSDQntC70LXQszwvc3Ryb25nPg0K0KDRg9C60L7QstC+0LTQuNGC0LXQu9GMINC/0YDQvtC10LrRgtCwINCyIDxhIGhyZWY9Imh0dHA6Ly93d3cueG4tLWMxYWJ2a2JkaC54bi0tcDFhaS8iIHRhcmdldD0iX2JsYW5rIj53d3cu0J/QoNCe0JTQntCb0JMu0KDQpDwvYT4uIDxhIGhyZWY9Imh0dHA6Ly93d3cub2VzLWtvbWl0ZXQucnUvIiB0YXJnZXQ9Il9ibGFuayI+d3d3Lm9lcy1rb21pdGV0LnJ1PC9hPg0KDQo8c3Ryb25nPtCt0L3QtNC4INCb0LjQsdC10YDQvNCw0L08L3N0cm9uZz4NCtC80LXRhtC10L3QsNGCLCDQv9GA0LXQtNGB0YLQsNCy0LjRgtC10LvRjCDQtdCy0YDQtdC50YHQutC+0Lkg0LTQuNCw0YHQv9C+0YDRiw0KDQo8c3Ryb25nPtCf0LDQu9Cw0LTRjNC10LIg0J7Qu9C10LMg0J3QuNC60L7Qu9Cw0LXQstC40Yc8L3N0cm9uZz4NCkNoaWVmIGJ1c2luZXNzIGRldmVsb3BtZW50IG9mZmljZXIg0LIgQ0lQIFNvdXppbnZlc3QsINCt0LrRgdC/0LXRgNGCINCyINCU0LXQu9C+0LLQvtC5INGB0L7QstC10YIg0L/QviDRgdC+0YLRgNGD0LTQvdC40YfQtdGB0YLQstGDINGBINCh0LXRgNCx0LjQtdC5INC4INCt0LrRgdC/0LXRgNGCINCyINCU0LXQu9C+0LLQvtC5INGB0L7QstC10YIg0L/QviDRgdC+0YLRgNGD0LTQvdC40YfQtdGB0YLQstGDINGBINCY0YHQv9Cw0L3QuNC10LkNCg0KPHN0cm9uZz7QmtC+0L3QvtCy0LDQu9C+0LIg0J7Qu9C10LMg0K3QtNGD0LDRgNC00L7QstC40Yc8L3N0cm9uZz4NCtCf0L7QvNC+0YnQvdC40Log0YHQtdC90LDRgtC+0YDQsCDQntGA0LvQvtCy0YHQutC+0Lkg0L7QsdC70LDRgdGC0LgNCg0KPHN0cm9uZz7QnNCw0LzQuNC5INCQ0LvQtdC60YHQsNC90LTRgCDQkdC+0YDQuNGB0L7QstC40Yc8L3N0cm9uZz4NCtGO0YDQuNGB0YINCg0KPHN0cm9uZz7Qm9C10LLRiNC40L0g0KHRgtCw0L3QuNGB0LvQsNCyINCu0YDRjNC10LLQuNGHDQo8L3N0cm9uZz4NCg0KPHN0cm9uZz7Qm9Cw0YDQuNGB0LAg0JTQvtC80LHQsNC10LLQsA0KPC9zdHJvbmc+PHN0cm9uZz7QotCw0YLQsNGD0YDQvtCyINCS0LDQtNC40Lw8L3N0cm9uZz4NCjxzdHJvbmc+0KDQvtC80LDQvdC+0LIg0JLRj9GH0LXRgdC70LDQsiDQnNC40YXQsNC50LvQvtCy0LjRhzwvc3Ryb25nPg0KDQo8aHIgLz4NCg0KPGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgd3AtaW1hZ2UtMTQ1IiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNC9NRUVUOC0zLTY4M3gxMDI0LmpwZyIgYWx0PSJNRUVUOC0zIiB3aWR0aD0iNjUwIiBoZWlnaHQ9Ijk3NSIgLz4NCg0KPGhyIC8+DQoNCjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTEyOSIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDQvTUVFVDgtMTktMTAyNHg2MTAuanBnIiBhbHQ9Ik1FRVQ4LTE5IiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjM4NyIgLz4gPGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgd3AtaW1hZ2UtMTMwIiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNC9NRUVUOC0xOC0xMDI0eDc1MS5qcGciIGFsdD0iTUVFVDgtMTgiIHdpZHRoPSI2NTAiIGhlaWdodD0iNDc3IiAvPg0KDQo8aHIgLz4NCg0KPHN0cm9uZz7QndCw0YfQsNC70L4g0LzQtdGA0L7Qv9GA0LjRj9GC0LjRjyDQuCDQutGA0YPQs9C70L7Qs9C+INGB0YLQvtC70LAuIDwvc3Ryb25nPtCh0L/QuNC60LXRgDog0JHQsNGC0YvRgCDQmNGB0LDQsdCw0LXQsi4NCg0KPGhyIC8+DQoNCjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTEzNCIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDQvTUVFVDgtMTQtMTAyNHg2MzQuanBnIiBhbHQ9Ik1FRVQ4LTE0IiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjQwMiIgLz4gPGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgd3AtaW1hZ2UtMTM1IiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNC9NRUVUOC0xMy0xMDI0eDUyMy5qcGciIGFsdD0iTUVFVDgtMTMiIHdpZHRoPSI2NTAiIGhlaWdodD0iMzMyIiAvPiA8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0xMzYiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA0L01FRVQ4LTEyLVBSVi0xLTEwMjR4NTc5LmpwZyIgYWx0PSJNRUVUOC0xMi1QUlYiIHdpZHRoPSI2NTAiIGhlaWdodD0iMzY3IiAvPiA8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0xMzciIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA0L01FRVQ4LTExLTEwMjR4NjM2LmpwZyIgYWx0PSJNRUVUOC0xMSIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0MDQiIC8+IDxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTEzOCIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDQvTUVFVDgtMTAtMTAyNHg1NDUuanBnIiBhbHQ9Ik1FRVQ4LTEwIiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjM0NiIgLz4NCg0KPGhyIC8+DQoNCjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTEzMyIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDQvTUVFVDgtMTUtMTAyNHg3MjcuanBnIiBhbHQ9Ik1FRVQ4LTE1IiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjQ2MiIgLz48aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0xNDAiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA0L01FRVQ4LTgtMTAyNHg5MTYuanBnIiBhbHQ9Ik1FRVQ4LTgiIHdpZHRoPSI2NTAiIGhlaWdodD0iNTgyIiAvPjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTEyOCIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDQvTUVFVDgtMjAtMTAyNHg2MTIuanBnIiBhbHQ9Ik1FRVQ4LTIwIiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjM4OCIgLz48aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0xMzIiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA0L01FRVQ4LTE2LTEwMjR4NzM3LmpwZyIgYWx0PSJNRUVUOC0xNiIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0NjgiIC8+IDxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTE0MSIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDQvTUVFVDgtNy0xMDI0eDc5MC5qcGciIGFsdD0iTUVFVDgtNyIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI1MDIiIC8+IDxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTEzMSIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDQvTUVFVDgtMTctMTAyNHg1ODQuanBnIiBhbHQ9Ik1FRVQ4LTE3IiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjM3MSIgLz4gPGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgd3AtaW1hZ2UtMTQ0IiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNC9NRUVUOC00LTEwMjR4NTY1LmpwZyIgYWx0PSJNRUVUOC00IiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjM1OSIgLz48aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0xMjUiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA0L01FRVQ4LTEwMjR4NTk5LmpwZyIgYWx0PSJNRUVUOCIgd2lkdGg9IjY1MCIgaGVpZ2h0PSIzODAiIC8+DQoNCjxociAvPg0KDQrQn9GA0L7QtNGD0LrRgtC40LLQvdCw0Y8g0LTQtdC70L7QstCw0Y8g0LLRgdGC0YDQtdGH0LAg0L/RgNC40L3QtdGB0LvQsCDQutC+0LzQv9Cw0L3QuNC4INC80L3QvtC20LXRgdGC0LLQviDQv9C+0LvQtdC30L3Ri9GFINC30L3QsNC60L7QvNGB0YLQsiDQuCDQsdC40LfQvdC10YEg0L/RgNC10LTQu9C+0LbQtdC90LjQuS4NCg0K0JLRgdC1INC40L3QstC10YHRgtC+0YDRiyDQuCDQsdC40LfQvdC10YEg0LLQu9Cw0LTQtdC70YzRhtGLLCDQttC10LvQsNGO0YnQuNC1INC+0YTQvtGA0LzQuNGC0Ywg0LTQvtCz0L7QstC+0YAg0L3QsCDRgdC+0YLRgNGD0LTQvdC40YfQtdGB0YLQstC+INGBwqDQutC+0LzQv9Cw0L3QuNC10LkgLCDQvNC+0LPRg9GCINC/0L7QtNCw0YLRjCDQt9Cw0Y/QstC60YMg0L3QsMKg0YPRh9Cw0YHRgtC40LUg0LLCoNC30LDQutGA0YvRgtC+0Lkg0LLRgdGC0YDQtdGH0LUgIlZJUC3QmNGB0YLQvtC6Iiwg0L/RgNC+0YXQvtC00Y/RidC10Lkg0LIg0LPQu9Cw0LLQvdC+0Lwg0L7RhNC40YHQtSDQnNC10YLRgNC+0L/QvtC70LjRjyDQsiDQutC+0LzQv9C70LXQutGB0LUg0JzQvtGB0LrQstCwLdCh0LjRgtC4INGD0LbQtSDQvdCwINGB0LvQtdC00YPRjtGJ0LXQuSDQvdC10LTQtdC70LUuDQoNCtCf0L4g0LLQvtC/0YDQvtGB0LDQvCDQt9Cw0L/QuNGB0Lgg0L3QsCDQt9Cw0LrRgNGL0YLRi9C1INC80LXRgNC+0L/RgNC40Y/RgtC40Y8g0YHQstGP0LbQuNGC0LXRgdGMINGBINC90LDQvNC4INGH0LXRgNC10LfCoDxhIGhyZWY9Imh0dHBzOi8vbWV0cm9wb2xpYS5vcmcvY29udGFjdCIgdGFyZ2V0PSJfYmxhbmsiPtGB0YLRgNCw0L3QuNGG0YMg0JrQvtC90YLQsNC60YLRizwvYT4g0LvQuNCx0L4g0L/QvtC30LLQvtC90LjRgtC1INC/0L4g0YLQtdC70LXRhNC+0L3RgzoNCjxzdHJvbmc+0LMuINCc0L7RgdC60LLQsCA4IDkyOS05ODktNzYtNTI8L3N0cm9uZz4NCjxzdHJvbmc+0JDQtNGA0LXRgSDQvtGE0LjRgdCwOjwvc3Ryb25nPg0KPGEgY2xhc3M9ImJsb2NrIHRyYW5zaXRpb24iIGhyZWY9Imh0dHBzOi8vZ29vLmdsL21hcHMvOTlDTHRYZGNxYUsyIiB0YXJnZXQ9Il9ibGFuayI+0JzQvtGB0LrQstCwINCh0LjRgtC4ICjRgdGC0LDQvdGG0LjRjyDQvC7QnNC10LbQtNGD0L3QsNGA0L7QtNC90LDRjyks0KHQtdCy0LXRgNC90LDRjyDQkdCw0YjQvdGPLNGD0Lsu0KLQtdGB0YLQvtCy0YHQutCw0Y8s0LQuMTAs0L/QvtC00YrQtdC30LQg4oSWMSzRjdGC0LDQtiAxOSwg0L7RhNC40YEgMTkyNC48L2E+DQoNCiZuYnNwOw0KDQombmJzcDsNCg0KJm5ic3A7DQoNCiZuYnNwOw0KDQombmJzcDsNCg0KJm5ic3A7'),
                'image'            => 'posts/MEET8-12-PRV.jpg',
                'slug'             => '123',
                'meta_description' => 'this be a meta descript',
                'meta_keywords'    => 'keyword1, keyword2, keyword3',
                'status'           => 'PUBLISHED',
                'featured'         => 0,
                'created_at'       => '2016-04-10 23:55:33',
            ])->save();
        }

        $post = $this->findPost('панельная-дискуссия');
        if (!$post->exists) {
            $post->fill([
                'title'     => 'Панельная дискуссия',
                'author_id' => 0,
                'category_id' => Category::CATEGORY_NEWS_ID,
                'seo_title' => null,
                'excerpt'   => 'Во вторник 19 апреля на базе бизнес-зала компании Метрополия состоялась панельная дискуссия "CMB - будущее систем эффективного финансирования". На текущем этапе деятельности платформа ставит перед собой одну из главных целей - получить максимально широкую и непредвзятую обратную связь от представителей ниш СНГ, нуждающихся в финансировании, а также условиях, востребованных серьезными инвесторами.',
                'body'      => base64_decode('0JLQviDQstGC0L7RgNC90LjQuiAxOSDQsNC/0YDQtdC70Y8g0L3QsCDQsdCw0LfQtSDQsdC40LfQvdC10YEt0LfQsNC70LAg0LrQvtC80L/QsNC90LjQuCDQnNC10YLRgNC+0L/QvtC70LjRjyDRgdC+0YHRgtC+0Y/Qu9Cw0YHRjCDQv9Cw0L3QtdC70YzQvdCw0Y8g0LTQuNGB0LrRg9GB0YHQuNGPICJDTUIgLSDQsdGD0LTRg9GJ0LXQtSDRgdC40YHRgtC10Lwg0Y3RhNGE0LXQutGC0LjQstC90L7Qs9C+INGE0LjQvdCw0L3RgdC40YDQvtCy0LDQvdC40Y8iLiDQndCwINGC0LXQutGD0YnQtdC8INGN0YLQsNC/0LUg0LTQtdGP0YLQtdC70YzQvdC+0YHRgtC4INC/0LvQsNGC0YTQvtGA0LzQsCDRgdGC0LDQstC40YIg0L/QtdGA0LXQtCDRgdC+0LHQvtC5INC+0LTQvdGDINC40Lcg0LPQu9Cw0LLQvdGL0YUg0YbQtdC70LXQuSAtINC/0L7Qu9GD0YfQuNGC0Ywg0LzQsNC60YHQuNC80LDQu9GM0L3QviDRiNC40YDQvtC60YPRjiDQuCDQvdC10L/RgNC10LTQstC30Y/RgtGD0Y4g0L7QsdGA0LDRgtC90YPRjiDRgdCy0Y/Qt9GMwqDQvtGCINC/0YDQtdC00YHRgtCw0LLQuNGC0LXQu9C10Lkg0L3QuNGIINCh0J3Qkywg0L3Rg9C20LTQsNGO0YnQuNGF0YHRjyDQsiDRhNC40L3QsNC90YHQuNGA0L7QstCw0L3QuNC4LCDQsCDRgtCw0LrQttC1INGD0YHQu9C+0LLQuNGP0YUsINCy0L7RgdGC0YDQtdCx0L7QstCw0L3QvdGL0YUg0YHQtdGA0YzQtdC30L3Ri9C80Lgg0LjQvdCy0LXRgdGC0L7RgNCw0LzQuC48IS0tbW9yZS0tPg0KDQrQndCwINC00LjRgdC60YPRgdC40Lgg0LIg0YLQvtC8INGH0LjRgdC70LUg0L/RgNC40YHRg9GC0YHRgtCy0L7QstCw0LvQuDoNCg0KPHN0cm9uZz7Qk9GA0LjQs9C+0YDQuNC5INCk0LXQtNC+0Lo8L3N0cm9uZz4gLSDQv9C+0LvQutC+0LLQvdC40LosINC/0YDQtdC00YHRgtCw0LLQuNGC0LXQu9GMINCd0LDRhtC40L7QvdCw0LvRjNC90L7QuSDQk9Cy0LDRgNC00LjQuCDQv9GA0Lgg0J/RgNC10LfQuNC00LXQvdGC0LUg0KDQpA0KPHN0cm9uZz7Qk9GD0YDQsNC8INCh0LXQv9C40LDRiNCy0LjQu9C4PC9zdHJvbmc+ICjQvdCwINGE0L7RgtC+KSAtINC/0YDQtdC00YHQtdC00LDRgtC10LvRjCDQvNC10LbQtNGD0L3QsNGA0L7QtNC90L7Qs9C+INC+0YLQtNC10LvQsCDQutC+0LvQu9C10LPQuNC4INGE0YPRgtCx0L7Qu9GM0L3Ri9GFINCw0YDQsdC40YLRgNC+0LIg0KDQpNChLCDQstC40YbQtS3Qv9GA0LXQt9C40LTQtdC90YIg0J3QodCk0JssINGN0LrRgS0g0LPQu9Cw0LLQvdGL0Lkg0LDRgNCx0LjRgtGAINCg0KQNCjxzdHJvbmc+0JDQu9C10LrRgdCw0L3QtNGAINCl0LDRgNC40YLQvtC90L7Qsjwvc3Ryb25nPiAt0LPQu9Cw0LLQvdGL0Lkg0YDQtdC60LTQsNC60YLQvtGAINC20YPRgNC90LDQu9CwICLQp9C10LvQvtCy0LXQuiDQtNC10LvQsCIg0LIg0JzQvtGB0LrQstC1DQo8c3Ryb25nPtCa0LjRgNC40LvQuyDQnNCw0LzQtdC90YLRjNC10LI8L3N0cm9uZz4g4oCT0LDQtNCy0L7QutCw0YIsINGD0L/RgNCw0LLQu9GP0Y7RidC40Lkg0L/QsNGA0YLQvdGR0YAg0LIg0LDQtNCy0L7QutCw0YLRgdC60L7QvCDQsdGO0YDQviDCq9Cc0LDQvNC10L3RgtGM0LXQsiDQotCw0YLQsNGA0LjQvdC+0LLQsCDQuCDQv9Cw0YDRgtC90ZHRgNGLwrsuDQo8c3Ryb25nPtCc0LDRgNC40L3QsCDQl9C10YTQuNGA0LrQuNC90LA8L3N0cm9uZz4g4oCTINCx0LjQt9C90LXRgeKAk9Cw0L3Qs9C10LsNCjxzdHJvbmc+0KfQtdGA0LzQtdC9INCU0LfQvtGC0L7Qsjwvc3Ryb25nPiAtINC+0YHQvdC+0LLQsNGC0LXQu9GMINGB0LXRgNCy0LjRgdCwINC/0L4g0L/QvtC00LHQvtGA0YMg0Y3QutGB0L/QtdGA0YLQvtCyINC00LvRjyDQodCc0JggKNC/0YDQvtC10LrRgiDCq9Cd0LDQudC00ZHQvCDQrdC60YHQv9C10YDRgtCwwrspLg0K0Lgg0LTRgNGD0LPQuNC1INC/0YDQtdC00YHRgtCw0LLQuNGC0LXQu9C4INCx0LjQt9C90LXRgdCwLg=='),
                'image'            => 'posts/Untitled-1.jpg',
                'slug'             => 'панельная-дискуссия',
                'meta_description' => 'this be a meta descript',
                'meta_keywords'    => 'keyword1, keyword2, keyword3',
                'status'           => 'PUBLISHED',
                'featured'         => 0,
                'created_at'       => '2016-04-20 18:47:58',
            ])->save();
        }

        $post = $this->findPost('новый-партнер-компании-ооо-тепломакс');
        if (!$post->exists) {
            $post->fill([
                'title'     => 'Новый партнер компании - ООО "ТеплоМакс"',
                'author_id' => 0,
                'category_id' => Category::CATEGORY_NEWS_ID,
                'seo_title' => null,
                'excerpt'   => '22 апреля состоялось подписание соглашения с компанией ООО "ТеплоМакс" - ведущей тепло-технчиеской организацией Владимирской области',
                'body'      => base64_decode('MjIg0LDQv9GA0LXQu9GPINGB0L7RgdGC0L7Rj9C70L7RgdGMINC/0L7QtNC/0LjRgdCw0L3QuNC1INGB0L7Qs9C70LDRiNC10L3QuNGPINGBINC60L7QvNC/0LDQvdC40LXQuSDQntCe0J4gItCi0LXQv9C70L7QnNCw0LrRgSIgLSDQstC10LTRg9GJ0LXQuSDRgtC10L/Qu9C+LdGC0LXRhdC90YfQuNC10YHQutC+0Lkg0L7RgNCz0LDQvdC40LfQsNGG0LjQtdC5INCS0LvQsNC00LjQvNC40YDRgdC60L7QuSDQvtCx0LvQsNGB0YLQuC48IS0tbW9yZS0tPtCa0L7QvNC/0LDQvdC40Y8g0J7QntCeICLQotC10L/Qu9C+0JzQsNC60YEiINGA0LDQsdC+0YLQsNC10YIg0L3QsCDRgNGL0L3QutC1INGC0LXQv9C70L7RjdC90LXRgNCz0LXRgtC40LrQuCDQoNC+0YHRgdC40LnRgdC60L7QuSDQpNC10LTQtdGA0LDRhtC40Lgg0YEgMjAwMyDQs9C+0LTQsC4g0JjQt9C90LDRh9Cw0LvRjNC90L4g0LrQvtC80L/QsNC90LjRjyDRgdC/0LXRhtC40LDQu9C40LfQuNGA0L7QstCw0LvQsNGB0Ywg0L3QsCDQuNC80L/QvtGA0YLQtSDQvtCx0L7RgNGD0LTQvtCy0LDQvdC40Y8g0LrRgNGD0L/QvdC10LnRiNC40YUg0LfQsNGA0YPQsdC10LbQvdGL0YUg0L/RgNC+0LjQt9Cy0L7QtNC40YLQtdC70LXQuSDRgtC10L/Qu9C+0LLRi9GFINCw0LPRgNC10LPQsNGC0L7QsiAoRGFuZm9zcywgQnVkZXJ1c3MsIFZpZXNzbWFubiDQuCDRgi7QtC4pLiDQn9C+INC80LXRgNC1INGA0L7RgdGC0LAg0Lgg0YDQsNC30LLQuNGC0LjRjyDQv9C+0LzQuNC80L4g0L/QvtGB0YLQsNCy0L7QuiDQvtCx0L7RgNGD0LTQvtCy0LDQvdC40Y8g0LrQvtC80L/QsNC90LjRjyDRgNC10LDQu9C40LfQvtCy0LDQu9CwINC+0YLQtNC10LvRjNC90YvQtSDQvdCw0L/RgNCw0LLQu9C10L3QuNGPINC/0L4g0L/RgNC+0LXQutGC0LjRgNC+0LLQsNC90LjRjiwg0YPRgdGC0LDQvdC+0LLQutC1LCDQv9GD0YHQutC+LdC90LDQu9Cw0LTQutC1INC4INGN0LrRgdC/0LvRg9Cw0YLQsNGG0LjQuCDRgtC10L/Qu9C+0YLQtdGF0L3QuNGH0LXRgdC60LjRhSDQuNC90LbQtdC90LXRgNC90YvRhSDRgdC40YHRgtC10Lwg0LvRjtCx0L7QuSDRgdC70L7QttC90L7RgdGC0LguDQoNCjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTE5NCIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDQvcGhvdG83Nzk2Mzc5OTg5MjI4Njg2NjctNzY4eDEwMjQuanBnIiBhbHQ9InBob3RvNzc5NjM3OTk4OTIyODY4NjY3IiB3aWR0aD0iNjUwIiBoZWlnaHQ9Ijg2NyIgLz4NCg0KPGhyIC8+DQoNCtCd0LAg0YLQtdC60YPRidC40Lkg0LzQvtC80LXQvdGCINGP0LLQu9GP0LXRgtGB0Y8g0LLQtdC00YPRidC10Lkg0L7RgtGA0LDRgdC70LXQstC+0Lkg0L7RgNCz0LDQvdC40LfQsNGG0LjQtdC5INCS0LvQsNC00LjQvNC40YDRgdC60L7QuSDQvtCx0LvQsNGB0YLQuC4g0JrQvtC80L/QsNC90LjRjyDQstGL0L/QvtC70L3Rj9C10YIg0L/QvtC70L3Ri9C5INGB0L/QtdC60YLRgCDRgdC/0LXQutGC0YAg0YPRgdC70YPQsyDQvtGCINGA0LDQt9GA0LDQsdC+0YLQutC4INC/0YDQvtC10LrRgtCwINC00L4g0YHQtNCw0YfQuCDQvtCx0YrQtdC60YLQsCDQsiDRjdC60YHQv9C70YPQsNGC0LDRhtC40Y4uDQoNCtCi0LXRgdC90L4g0YHQvtGC0YDRg9C00L3QuNGH0LDQtdGCINGBINCf0YDQsNCy0LjRgtC10LvRjNGB0YLQstC+0Lwg0JLQu9Cw0LTQuNC80LjRgNGB0LrQvtC5INC+0LHQu9Cw0YHRgtC4INC4INCf0YDQsNCy0LjRgtC10LvRjNGB0YLQstCw0LzQuCDQsdC70LjQt9C70LXQttCw0YnQuNGFINGA0LXQs9C40L7QvdC+0LIuDQrQntGB0L3QvtCy0L3QvtC5INC+0LHRitC10Lwg0YDQsNCx0L7RgiDRgdC+0YHRgtCw0LLQu9GP0LXRgiDQstC+0LfQstC10LTQtdC90LjQtSDQutC+0YLQtdC70YzQvdGL0YUg0LTQu9GPINC60YDRg9C/0L3Ri9GFINC/0YDQvtC80YvRiNC70LXQvdC90YvRhSDQvtCx0YrQtdC60YLQvtCyINGA0LXQs9C40L7QvdCwLg0KDQo8aHIgLz4NCg0K0J3QsNGD0LrQsCDRgtC10L/Qu9C+0Y3QvdC10YDQs9C10YLQuNC60LAg0LjQt9GD0YfQsNC10YIg0LfQsNC60L7QvdC+0LzQtdGA0L3QvtGB0YLQuCDQv9GA0L7RhtC10YHRgdC+0LIg0Lgg0Y/QstC70LXQvdC40LksINGB0LLRj9C30LDQvdC90YvRhSDRgSDQv9C+0LvRg9GH0LXQvdC40LXQvCwg0L/RgNC10L7QsdGA0LDQt9C+0LLQsNC90LjQtdC8LCDQv9C10YDQtdC00LDRh9C10LksINGA0LDRgdC/0YDQtdC00LXQu9C10L3QuNC10Lwg0Lgg0LjRgdC/0L7Qu9GM0LfQvtCy0LDQvdC40LXQvCDRgtC10L/Qu9C+0LLQvtC5INGN0L3QtdGA0LPQuNC4PHNwYW4gaWQ9Im1vcmUtMjQ1Ij48L3NwYW4+LiDQkiDQsdC40LfQvdC10YEt0L/Qu9Cw0L3RiyDQv9GA0LXQtNC/0YDQuNGP0YLQuNC5INGC0LXQv9C70L7RjdC90LXRgNCz0LXRgtC40LrQuCDQstGF0L7QtNC40YIg0YHQvtCy0LXRgNGI0LXQvdGB0YLQstC+0LLQsNC90LjQtSDQvNC10YLQvtC00L7QsiDQv9GA0L7Qs9C90L7Qt9C40YDQvtCy0LDQvdC40Y8sINC/0LvQsNC90LjRgNC+0LLQsNC90LjRjyDQuCDRjdC60YHQv9C70YPQsNGC0LDRhtC40Lgg0YDQsNCx0L7Rh9C40YUg0YHQuNGB0YLQtdC8LiDQptC10LvRjNGOINGC0LDQutC40YUg0L3QsNGD0YfQvdC+LdC40YHRgdC70LXQtNC+0LLQsNGC0LXQu9GM0YHQutC40YUg0YDQsNCx0L7RgiDRj9Cy0LvRj9C10YLRgdGPINC/0L7QstGL0YjQtdC90LjQtSDQutC/0LQg0YDQsNC30LvQuNGH0L3Ri9GFINGB0YLQsNC90YbQuNC5INC4INGD0LzQtdC90YzRiNC10L3QuNC1INC40YUg0LLRgNC10LTQvdC+0LPQviDQstC+0LfQtNC10LnRgdGC0LLQuNGPINC90LAg0L7QutGA0YPQttCw0Y7RidGD0Y4g0YHRgNC10LTRgy4NCg0K0JIg0Y3RgtC+0Lwg0YHQvNGL0YHQu9C1LCDQsdC+0LvRjNGI0LUg0LLRgdC10LPQviDQvtC/0LDRgdC10L3QuNC5INCy0YvQt9GL0LLQsNGO0YIg0LDRgtC+0LzQvdGL0LUg0Y3Qu9C10LrRgtGA0L7RgdGC0LDQvdGG0LjQuCwg0LjQu9C4INCQ0K3QoS4g0KLQtdC/0LvQvtGN0L3QtdGA0LPQtdGC0LjQutCwINCy0LrQu9GO0YfQsNC10YIg0Y3Qu9C10LrRgtGA0L7RgdGC0LDQvdGG0LjQuCwg0LjRgdGC0L7Rh9C90LjQutC+0Lwg0Y3Qu9C10LrRgtGA0L7RjdC90LXRgNCz0LjQuCDQsiDQutC+0YLQvtGA0YvRhSDRgdC70YPQttC40YIg0YLQtdC/0LvQvi4g0JjRhSDRgNCw0LfQvdC+0LLQuNC00L3QvtGB0YLRjNGOINGP0LLQu9GP0LXRgtGB0Y8g0JDQrdChLiDQoNCw0LfRg9C80LXQtdGC0YHRjywg0LHQuNC30L3QtdGBLdC/0LvQsNC90Ysg0JDQrdChINGP0LLQu9GP0Y7RgtGB0Y8g0LTQvtC60YPQvNC10L3RgtCw0LzQuCDQs9C+0YHRg9C00LDRgNGB0YLQstC10L3QvdC+0LPQviDRg9GA0L7QstC90Y8uDQoNCtCi0LXQv9C70L7RjdC90LXRgNCz0LXRgtC40LrQsCDQvdC10YDQsNC30YDRi9Cy0L3QviDRgdCy0Y/Qt9Cw0L3QsCDRgdC+INCy0YHQtdC5INGN0L3QtdGA0LPQvtGB0LjRgdGC0LXQvNC+0Lkg0YHRgtGA0LDQvdGLLiDQntC90LAg0LLRhdC+0LTQuNGCINCyINGC0L7Qv9C70LjQstC90L4t0Y3QvdC10YDQs9C10YLQuNGH0LXRgdC60LjQuSDQutC+0LzQv9C70LXQutGBINGB0YLRgNCw0L3Riy4g0JHQuNC30L3QtdGBLdC/0LvQsNC90Ysg0YHQvtGB0YLQsNCy0LvRj9C10YLRgdGPINC00LvRjyDQstGB0LXRhSDQv9GA0LXQtNC/0YDQuNGP0YLQuNC5LCDQvtGC0L3QvtGB0Y/RidC40YXRgdGPINC6INGN0L3QtdGA0LPQtdGC0LjQutC1INCy0L7QvtCx0YnQtSDQuCDRgtC10L/Qu9C+0Y3QvdC10YDQs9C10YLQuNC60LUg0LIg0YfQsNGB0YLQvdC+0YHRgtC4Lg0KDQo8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0xOTIiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA0L3Bob3RvNzc5NjM3OTk4OTIyODY4NjczLmpwZyIgYWx0PSJwaG90bzc3OTYzNzk5ODkyMjg2ODY3MyIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0ODgiIC8+DQoNCjxzdHJvbmc+0KHQvtGC0YDRg9C00L3QuNGH0LXRgdGC0LLQviDRgSDQvtGA0LPQsNC90LjQt9Cw0YbQuNC10LksINGA0LDQsdC+0YLQsNGO0YnQtdC5INCyINC+0LTQvdC+0Lkg0LjQtyDRgdCw0LzRi9GFwqDQstCw0LbQvdGL0YXCoNC+0YLRgNCw0YHQu9C10LnCoNGN0LrQvtC90L7QvNC40LrQuCDQstGB0LXQuSDRgdGC0YDQsNC90YssINC/0L7Qt9Cy0L7Qu9C40YIg0LrQvtC80L/QsNC90LjQuCDQnNC10YLRgNC+0L/QvtC70LjRjyDQstGL0LLQtdGB0YLQuCDQuNC90LLQtdGB0YLQuNGG0LjQvtC90L3Ri9C5INC/0L7RgNC+0LMg0L3QsCDRgdC+0LLQtdGA0YjQtdC90L3QviDQvdC+0LLRi9C5INGD0YDQvtCy0LXQvdGMINC4INC/0YDQuNCy0LvQtdGH0Ywg0L3QsCDQv9C70LDRgtGE0L7RgNC80YMg0LrQsNC/0LjRgtCw0LvCoNGE0LjQt9C40YfQtdGB0LrQuNGFINC70LjRhtCwwqDQutGA0YPQv9C90L7Qs9C+INGE0L7RgNC80LDRgtCwLjwvc3Ryb25nPg0KDQrQkiDQsdC70LjQttCw0LnRiNC40YUg0L/Qu9Cw0L3QsNGFINC60L7QvNC/0LDQvdC40LggLSDQstGL0YXQvtC0INC90LAg0YDQtdCw0LvQuNC30LDRhtC40Y4g0LrRgNGD0L/QvdGL0YUg0L/RgNC+0LXQutGC0L7QsiDRgSDRg9GB0YLQsNC90L7QstC60LDQvNC4INGC0LXQv9C70L7QstGL0YUg0L/QuNGC0LDRjtGJ0LjRhSDQv9GD0L3QutGC0L7Qsiwg0LzQvtGJ0L3QvtGB0YLRj9C80Lgg0YHQstGL0YjQtSAxMDAg0JzQktGCLiDQndCwINGB0YLQsNC00LjQuCDQv9C+0LTQv9C40YHQsNC90LjRjyDQvdCw0YXQvtC00LjRgtGB0Y8g0YDRj9C0INC60YDRg9C/0L3Ri9GFINCz0L7RgdGD0LTQsNGA0YHRgtCy0LXQvdC90YvRhSDQuCDQutC+0LzQvNC10YDRh9C10YHQutC40YUg0LrQvtC90YLRgNCw0LrRgtC+0LIuINCh0YPRidC10YHRgtCy0LXQvdC90L7QtSDRg9Cy0LXQu9C40YfQtdC90LjQtSDQutCw0L/QuNGC0LDQu9GM0L3Ri9GFINC30LDRgtGA0LDRgiDQvdCwINGA0LXQsNC70LjQt9Cw0YbQuNGOINC/0L7QtNC+0LHQvdGL0YUg0L/RgNC+0LXQutGC0L7QsiDRgtGA0LXQsdGD0LXRgiDQtNC+0L/QvtC70L3QuNGC0LXQu9GM0L3QvtCz0L4g0LjQvdCy0LXRgdGC0LjRhtC40L7QvdC90L7Qs9C+INC4INGA0LXRgdGD0YDRgdC90L7Qs9C+INC90LDRgdGL0YnQtdC90LjRjy4NCg0KPGhyIC8+DQoNCjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTE5MyIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDQvcGhvdG83Nzk2Mzc5OTg5MjI4Njg2NzAuanBnIiBhbHQ9InBob3RvNzc5NjM3OTk4OTIyODY4NjcwIiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjQ5MyIgLz4gPGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgd3AtaW1hZ2UtMTk1IiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNC9waG90bzc3OTYzNzk5ODkyMjg2ODY2Ni03NjV4MTAyNC5qcGciIGFsdD0icGhvdG83Nzk2Mzc5OTg5MjI4Njg2NjYiIHdpZHRoPSI2NTAiIGhlaWdodD0iODcwIiAvPiA8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0xOTYiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA0L3Bob3RvNzc5NjM3OTk4OTIyODY4NjY1LmpwZyIgYWx0PSJwaG90bzc3OTYzNzk5ODkyMjg2ODY2NSIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI2MTEiIC8+DQoNCjxociAvPg0KDQrQoSDRg9GH0YDQtdC00LjRgtC10LvRjNC90YvQvNC4INC00L7QutGD0LzQtdC90YLQsNC80Lgg0LrQvtC80L/QsNC90LjQuCwg0LrQvtC/0LjQtdC5INGB0L7Qs9C70LDRiNC10L3QuNGPINC80LXQttC00YMg0L/Qu9Cw0YLRhNC+0YDQvNC+0Lkg0JzQtdGC0YDQv9C+0LvQuNGPINC4INC/0YDQvtC10LrRgtC+0LwsINCwINGC0LDQuiDQttC1wqDQv9C+0LvQvdC+0Lkg0Y7RgNC40LTQuNGH0LXRgdC60L7QuSDQuNC90YTQvtGA0LzQsNGG0LjQtdC5INC+INCx0LjQt9C90LXRgdC1INC80L7QttC90L4g0LHRg9C00LXRgiDQvtC30L3QsNC60L7QvNC40YLRjNGB0Y8g0LIg0YDQsNC30LTQtdC70LUgPGEgaHJlZj0iaHR0cHM6Ly9tZXRyb3BvbGlhLm9yZy9wcm9qZWN0cyIgdGFyZ2V0PSJfYmxhbmsiPiLQv9C+0YDRgtGE0L7Qu9C40L4iPC9hPiDQsiDRgdC10LrRhtC40Lgg0L/RgNC+0LXQutGC0LAg0J7QntCeICLQotC10L/Qu9C+0JzQsNC60YEiDQoNCjxociAvPg0KDQo8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0xOTEiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA0L9Ch0LrQsNC9X9C00L7Qs9C+0LLQvtGA0LBf0YFf0JzQtdGC0YDQvtC/0L7Qu9C40LXQuMyGX9C/0L7RgdC7LTc0NXgxMDI0LmpwZyIgYWx0PSLQodC60LDQvV/QtNC+0LPQvtCy0L7RgNCwX9GBX9Cc0LXRgtGA0L7Qv9C+0LvQuNC10LjMhl/Qv9C+0YHQuyIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI4OTQiIC8+DQoNCiZuYnNwOw=='),
                'image'            => 'posts/2277081_5.jpg',
                'slug'             => 'новый-партнер-компании-ооо-тепломакс',
                'meta_description' => 'this be a meta descript',
                'meta_keywords'    => 'keyword1, keyword2, keyword3',
                'status'           => 'PUBLISHED',
                'featured'         => 0,
                'created_at'       => '2016-04-23 22:57:32',
            ])->save();
        }

        $post = $this->findPost('идея-ведет-за-собой-лучших');
        if (!$post->exists) {
            $post->fill([
                'title'     => 'Идея - ведет за собой лучших',
                'author_id' => 0,
                'category_id' => Category::CATEGORY_NEWS_ID,
                'seo_title' => null,
                'excerpt'   => 'Последние 2 недели перед началом праздников выдались особенно продуктивны и принесли с собой новые лица, результаты, инвестиции и бизнес партнеров. Московская франчайзинг сеть Метрополии в лице владельца франшизы Исабаева Батыра и команды лидеров провела более 50 личных встреч и 6 мероприятий в течении 2х недель',
                'body'      => base64_decode('0J/QvtGB0LvQtdC00L3QuNC1IDIg0L3QtdC00LXQu9C4INC/0LXRgNC10LQg0L3QsNGH0LDQu9C+0Lwg0L/RgNCw0LfQtNC90LjQutC+0LIg0LLRi9C00LDQu9C40YHRjCDQvtGB0L7QsdC10L3QvdC+INC/0YDQvtC00YPQutGC0LjQstC90Ysg0Lgg0L/RgNC40L3QtdGB0LvQuCDRgSDRgdC+0LHQvtC5INC90L7QstGL0LUg0LvQuNGG0LAsINGA0LXQt9GD0LvRjNGC0LDRgtGLLCDQuNC90LLQtdGB0YLQuNGG0LjQuCDQuCDQsdC40LfQvdC10YEg0L/QsNGA0YLQvdC10YDQvtCyLiDQnNC+0YHQutC+0LLRgdC60LDRjyDRhNGA0LDQvdGH0LDQudC30LjQvdCzINGB0LXRgtGMINCc0LXRgtGA0L7Qv9C+0LvQuNC4INCyINC70LjRhtC1INCy0LvQsNC00LXQu9GM0YbQsCDRhNGA0LDQvdGI0LjQt9GLINCY0YHQsNCx0LDQtdCy0LAg0JHQsNGC0YvRgNCwINC4INC60L7QvNCw0L3QtNGLINC70LjQtNC10YDQvtCyINC/0YDQvtCy0LXQu9CwINCx0L7Qu9C10LUgNTAg0LvQuNGH0L3Ri9GFINCy0YHRgtGA0LXRhyDQuCA2INC80LXRgNC+0L/RgNC40Y/RgtC40Lkg0LIg0YLQtdGH0LXQvdC40LggMtGFINC90LXQtNC10LvRjDwhLS1tb3JlLS0+DQrQkdGL0LvQviDQv9GA0LjQstC70LXRh9C10L3QviA0INC90L7QstGL0YUg0LLQu9Cw0LTQtdC70YzRhtCwINGE0YDQsNC90YfQsNC50LfQuNC90LMg0YHQtdGC0LXQuSDQutC70LDRgdGB0LAgItCc0YPQvdC40YbQuNC/0LDQu9GM0L3QsNGPIiDQsCDRgtCw0Log0LbQtSDQsdC40LfQvdC10YEg0L/QsNGA0YLQvdC10YAg0LIg0LvQuNGG0LUg0YHQtdGC0Lgg0YHRgtC+0LzQsNGC0L7Qu9C+0LPQuNGH0LXRgdC60LjRhSDQutC70LjQvdC40LogLSAi0JrQu9GD0LEg0JPQvtC70LvQuNCy0YPQtNGB0LrQuNGFINGD0LvRi9Cx0L7QuiIgKNC00LDQvdC90YvQuSDQv9GA0L7QtdC60YIg0L3QsNGF0L7QtNC40YLRgdGPINC90LAg0YDQsNGB0YHQvNC+0YLRgNC10L3QuNC4INC4INCy0LXRgNC+0Y/RgtC90L4g0LHRg9C00LXRgiDQstC90LXQtNGA0LXQvSDQvdCwINC/0LvQsNGC0YTQvtGA0LzRgyDQsiDQsdC70LjQttCw0LnRiNC10Lwg0LHRg9C00YPRidC10LwpDQoNCjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTIyMCIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDUvNi0xMDI0eDYzMC5qcGciIGFsdD0iNiIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0MDAiIC8+DQoNCjxociAvPg0KDQrQndCwINC80LXRgNC+0L/RgNC40Y/RgtC40Y/RhSDQv9GA0LjRgdGD0YLRgdGC0LLQvtCy0LDQu9C4INCx0LjQt9C90LXRgdC80LXQvdGLINC60YDRg9C/0L3QvtCz0L4g0Lgg0YHRgNC10LTQvdC10LPQviDRg9GA0L7QstC90Y8sINGO0YDQuNGB0YLRiywg0L/RgNC10LTRgdGC0LDQstC40YLQtdC70Lgg0LPQvtGB0YPQtNCw0YDRgdGC0LLQtdC90L3Ri9GFINC+0YDQs9Cw0L3QvtCyLCDQstC70LDQtNC10LvRjNGG0Ysg0LrRgNGD0L/QvdGL0YUg0YHRgtGA0YPQutGC0YPRgCDQutC+0LzQv9Cw0L3QuNC5INGB0LXRgtC10LLQvtCz0L4g0LzQsNGA0LrQtdGC0LjQvdCz0LAsINCwINGC0LDQuiDQttC1INC00YDRg9Cz0LjQtSDQttC10LvQsNGO0YnQuNC1INC90LDRh9Cw0YLRjCDQsdC40LfQvdC10YEg0LvQuNCx0L4g0L3QsNC50YLQuCDRhNC40L3QsNC90YHQuNGA0L7QstCw0L3QuNC1INC90LAg0YHQstC+0Lkg0L/RgNC+0LXQutGCLg0KDQo8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0yMjMiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA1LzktMTAyNHg2ODIuanBnIiBhbHQ9IjkiIHdpZHRoPSI2NTAiIGhlaWdodD0iNDMzIiAvPg0KDQo8aHIgLz4NCg0K0J3QvtCy0YvQuSDRjdGC0LDQvyDQsiDRgNCw0LfQstC40YLQuNC4INC60L7QvNC/0LDQvdC40LggLSDQutC+0LzQsNC90LTQvdCw0Y8g0YDQsNCx0L7RgtCwINCy0YHQtdGFINGB0YPRidC10YHRgtCy0YPRjtGJ0LjRhcKg0YDQtdCz0LjQvtC90L7Qsiwg0LTQtdGA0LbQsNGC0LXQu9C10Lkg0YTRgNCw0L3Rh9Cw0LnQt9C40L3QsyDQv9GA0L7Qs9GA0LDQvNC8INC90LAg0L/QvtC40YHQuiDQuNC90LLQtdGB0YLQvtGA0L7Qsiwg0LHQuNC30L3QtdGBINC+0LHRitC10LrRgtC+0LIg0LTQu9GPINC/0LvQsNGC0YTQvtGA0LzRiyDQuCDQvdC+0LLRi9GFINC/0LDRgNGC0L3QtdGA0L7Qsiwg0L3QsNGG0LXQu9C10L3QvdGL0YUg0L3QsCDRgdC+0LfQtNCw0L3QuNC1INGB0LLQvtC10Lkg0YHQtdGC0LguDQoNCjxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTIyMiIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDUvcGhvdG84Mjc3OTA4NzQ0MzgxMTczMzgtMi0xMDI0eDU2Ny5qcGciIGFsdD0icGhvdG84Mjc3OTA4NzQ0MzgxMTczMzgiIHdpZHRoPSI2NTAiIGhlaWdodD0iMzYwIiAvPiA8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0yMjEiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA1LzctMTAyNHg1NjUuanBnIiBhbHQ9IjciIHdpZHRoPSI2NTAiIGhlaWdodD0iMzU4IiAvPiA8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0yMTkiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA1LzQtMTAyNHg1OTguanBnIiBhbHQ9IjQiIHdpZHRoPSI2NTAiIGhlaWdodD0iMzgwIiAvPiA8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0yMTgiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA1LzItMTAyNHg0NzMuanBnIiBhbHQ9IjIiIHdpZHRoPSI2NTAiIGhlaWdodD0iMzAwIiAvPiA8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0yMTciIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA1L9GGMy0xMDI0eDU1OS5qcGciIGFsdD0i0YYzIiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjM1NSIgLz4gPGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgd3AtaW1hZ2UtMjE1IiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNS84LTEwMjR4NjgwLmpwZyIgYWx0PSI4IiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjQzMiIgLz4gPGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgd3AtaW1hZ2UtMjE0IiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNS8xLTEwMjR4NjEwLmpwZyIgYWx0PSIxIiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjM4NyIgLz4NCg0KJm5ic3A7DQoNCiZuYnNwOw=='),
                'image'            => 'posts/photo827790874438117338-2.jpg',
                'slug'             => 'идея-ведет-за-собой-лучших',
                'meta_description' => 'this be a meta descript',
                'meta_keywords'    => 'keyword1, keyword2, keyword3',
                'status'           => 'PUBLISHED',
                'featured'         => 0,
                'created_at'       => '2016-05-04 21:05:27',
            ])->save();
        }

        $post = $this->findPost('новый-партнер-ооо-клуб-голливудских');
        if (!$post->exists) {
            $post->fill([
                'title'     => 'Новый партнер - ООО "Клуб Голливудских Улыбок"',
                'author_id' => 0,
                'category_id' => Category::CATEGORY_NEWS_ID,
                'seo_title' => null,
                'excerpt'   => '18 мая в торжественной обстановке состоялось подписание соглашений с компанией ООО "Клуб Голливудских Улыбок" - прогрессивно развивающейся сетью стоматологических и косметологических клиник.',
                'body'      => base64_decode('MTgg0LzQsNGPINCyINGC0L7RgNC20LXRgdGC0LLQtdC90L3QvtC5INC+0LHRgdGC0LDQvdC+0LLQutC1INGB0L7RgdGC0L7Rj9C70L7RgdGMINC/0L7QtNC/0LjRgdCw0L3QuNC1INGB0L7Qs9C70LDRiNC10L3QuNC5INGBINC60L7QvNC/0LDQvdC40LXQuSDQntCe0J4gItCa0LvRg9CxINCT0L7Qu9C70LjQstGD0LTRgdC60LjRhSDQo9C70YvQsdC+0LoiIC0g0L/RgNC+0LPRgNC10YHRgdC40LLQvdC+INGA0LDQt9Cy0LjQstCw0Y7RidC10LnRgdGPINGB0LXRgtGM0Y4g0YHRgtC+0LzQsNGC0L7Qu9C+0LPQuNGH0LXRgdC60LjRhSDQuCDQutC+0YHQvNC10YLQvtC70L7Qs9C40YfQtdGB0LrQuNGFINC60LvQuNC90LjQui48IS0tbW9yZS0tPg0KDQo8aHIgLz4NCg0KPGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgd3AtaW1hZ2UtMjQwIiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNS9TY3JlZW4tU2hvdC0yMDE2LTA1LTIwLWF0LTIyLjA2LjQ5LWNvcHktMTAyNHg2NzIuanBnIiBhbHQ9IlNjcmVlbiBTaG90IDIwMTYtMDUtMjAgYXQgMjIuMDYuNDkgY29weSIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0MjYiIC8+DQoNCtCf0YDQvtC10LrRgiDRhNGD0L3QutGG0LjQvtC90LjRgNGD0LXRgiDRgSAyMDA2INCz0L7QtNCwLiDQmtC70LjQvdC40LrQuCDRgdC/0LXRhtC40LDQu9C40LfQuNGA0YPRjtGC0YHRjyDQvdCwINC40L3QvdC+0LLQsNGG0LjQvtC90L3Ri9GFINC4INC/0YDQvtCz0YDQtdGB0YHQuNCy0L3Ri9GFINC/0L7QtNGF0L7QtNCw0YUg0LIg0LzQtdC00LjRhtC40L3QtSAtINGA0YPQutC+0LLQvtC00YHRgtCy0L4g0L7RgdGD0YnQtdGB0YLQstC70Y/RjtGCINGC0YDQuCDQutCw0L3QtNC40LTQsNGC0LAg0LzQtdC00LjRhtC40L3RgdC60LjRhSDQvdCw0YPQuiAo0Lou0Lwu0L0uKSwg0YDQsNC30YDQsNCx0L7RgtCw0L3RiyDQuCDQt9Cw0L/QsNGC0LXQvdGC0L7QstCw0L3RiyDRgdC+0LHRgdGC0LLQtdC90L3Ri9C1INC40LfQvtCx0YDQtdGC0LXQvdC40Y8g0LIg0L7QsdC70LDRgdGC0Lgg0L7RgNGC0L7Qv9C10LTQuNGH0LXRgdC60L7QuSDRgdGC0L7QvNCw0YLQvtC70L7Qs9C40LguINCf0L7QvNC40LzQviDQvtGB0L3QvtCy0L3Ri9GFINCy0LjQtNC+0LIg0YPRgdC70YPQsywg0YDQtdCw0LvQuNC30YPRjtGC0YHRjyDRhtC40LrQu9GLINGC0YDQtdC90LjQvdCz0L7QsiDQuCDQvNCw0YHRgtC10YAt0LrQu9Cw0YHRgdC+0LIg0LIg0L7QsdC70LDRgdGC0Lgg0YHRgtC+0LzQsNGC0L7Qu9C+0LPQuNC4INC4INCw0L/Qv9Cw0YDQsNGC0L3QvtC5INC60L7RgdC80LXRgtC+0LvQvtCz0LjQuC4NCg0KPGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgd3AtaW1hZ2UtMjQxIiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNS9TY3JlZW4tU2hvdC0yMDE2LTA1LTIwLWF0LTIyLjA3LjU4LTEwMjR4NjI3LmpwZyIgYWx0PSJTY3JlZW4gU2hvdCAyMDE2LTA1LTIwIGF0IDIyLjA3LjU4IiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjM5OCIgLz4NCg0K0KHQsNC80YvQvNC4INC00L7RgNC+0LPQuNC80Lgg0LIg0L/Qu9Cw0YLQvdC+0Lkg0LzQtdC00LjRhtC40L3QtSDRj9Cy0LvRj9GO0YLRgdGPINGB0YLQvtC80LDRgtC+0LvQvtCz0LjRh9C10YHQutC40LUg0YPRgdC70YPQs9C4LiDQkdC+0LvRjNGI0LUg0LLRgdC10LPQviDQv9GA0LjQsdGL0LvQuCDQv9GA0LjQvdC+0YHQuNGCINGC0LXRgNCw0L/QuNGPIC0gNTYlINC+0YIg0LLRi9GA0YPRh9C60LgsINC90LAg0LLRgtC+0YDQvtC8INC80LXRgdGC0LUg0L7RgNGC0L7Qv9C10LTQuNGPIC0gMjklLiDQndCw0YfQuNC90LDRgtGMINGN0LrRgdC/0LXRgNGC0Ysg0YHQvtCy0LXRgtGD0Y7RgiDRgSDQutC70LjQvdC40LrQuCwg0LrQvtGC0L7RgNCw0Y8g0YHQv9C10YbQuNCw0LvQuNC30LjRgNGD0LXRgtGB0Y8g0L3QsCDRgtC10YDQsNC/0LXQstGC0LjRh9C10YHQutC40YUg0YPRgdC70YPQs9Cw0YUg0Lgg0L7RgdC90LDRidC10L3QsCDQtNCy0YPQvNGPINGD0YHRgtCw0L3QvtCy0LrQsNC80LguINCh0YDQvtC60Lgg0L7QutGD0L/QsNC10LzQvtGB0YLQuCDQutC70LjQvdC40LrQuCDigJMg0L/Rj9GC0Ywg0LvQtdGCLCDQvdC+INCyINC00LDQu9GM0L3QtdC50YjQtdC8INC+0L3QuCDQvNC+0LPRg9GCINGD0LLQtdC70LjRh9C40YLRjNGB0Y8g0L/QviDQv9GA0LjRh9C40L3QtSDQvdCw0YHRi9GJ0LXQvdC40Y8g0YDRi9C90LrQsC4NCg0KPGltZyBjbGFzcz0iYWxpZ25jZW50ZXIgd3AtaW1hZ2UtMjQyIiBzcmM9Imh0dHBzOi8vbmV3cy5tZXRyb3BvbGlhLm9yZy93cC1jb250ZW50L3VwbG9hZHMvMjAxNi8wNS8yMzExMy0xMDI0eDY0NS5qcGciIGFsdD0iMjMxMTMiIHdpZHRoPSI2NTAiIGhlaWdodD0iNDA5IiAvPiA8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0yNDMiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA1LzI5ODQyLTEwMjR4NjYwLmpwZyIgYWx0PSIyOTg0MiIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0MTkiIC8+IDxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTI0NSIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDUvMzEyNDIzNC0xMDI0eDYzOC5qcGciIGFsdD0iMzEyNDIzNCIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0MDUiIC8+IDxpbWcgY2xhc3M9ImFsaWduY2VudGVyIHdwLWltYWdlLTI0NiIgc3JjPSJodHRwczovL25ld3MubWV0cm9wb2xpYS5vcmcvd3AtY29udGVudC91cGxvYWRzLzIwMTYvMDUvMzEyMzU1NTUtMTAyNHg2MzguanBnIiBhbHQ9IjMxMjM1NTU1IiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjQwNSIgLz4NCg0K0J/QvtGC0L7QuiDQv9Cw0YbQuNC10L3RgtC+0LIg0L3QvtCy0L7QuSDQutC70LjQvdC40LrQtSDQv9C+0LzQvtCz0YPRgiDQvtCx0LXRgdC/0LXRh9C40YLRjCDQu9C40LHQviDQtNC+0LrRgtC+0YDQsCDRgSDRgdC+0LHRgdGC0LLQtdC90L3QvtC5INC60LvQuNC10L3RgtGB0LrQvtC5INCx0LDQt9C+0LksINC70LjQsdC+INGA0LXQutC70LDQvNCwLiDQn9C10YDQstGL0YUg0L/QvtGB0LXRgtC40YLQtdC70LXQuSDQv9C+0LzQvtCz0LDQtdGCINC/0YDQuNCy0LvQtdGH0Ywg0YDQtdC60LvQsNC80LAsINCwINC00LDQu9GM0YjQtSDRg9C20LUg0LTQtdC50YHRgtCy0YPQtdGCINGB0LDRgNCw0YTQsNC90L3QvtC1INGA0LDQtNC40L4uDQoNCtCl0L7RgNC+0YjQtdC5INC30LDQs9GA0YPQt9C60L7QuSDQutC70LjQvdC40LrQuCDRgdGH0LjRgtCw0LXRgtGB0Y8g0L3QtSDQvNC10L3QtdC1IDQg0L/QsNGG0LjQtdC90YLQvtCyINC90LAg0L7QtNC90L7Qs9C+INCy0YDQsNGH0LAg0LjQu9C4INC/0L4gOCDQvdCwINC+0LTQvdGDINGD0YHRgtCw0L3QvtCy0LrRgywgMTAg0L/QsNGG0LjQtdC90YLQvtCyIOKAkyDRjdGC0L4g0L3QvtGA0LzQsCwg0L/RgNC4INC60L7RgtC+0YDQvtC5INC60LvQuNC90LjQutCwINGB0LXQsdGPINC+0LrRg9C/0LDQtdGCINC4INC/0YDQuNC90L7RgdC40YIg0L/RgNC40LHRi9C70YwuINCf0LjQutC4INC/0L7RgdC10YnQsNC10LzQvtGB0YLQuCDigJQg0YPRgtGA0L4gKDguMDAtMTEuMDApINC4INCy0LXRh9C10YAgKDE2LjAwLTIwLjAwKS4NCg0K0JIg0YLQtdGA0LDQv9C40Lgg0L3QsCDQv9C10YDQstC+0Lwg0LzQtdGB0YLQtSDQv9C+INC/0L7Qv9GD0LvRj9GA0L3QvtGB0YLQuCDRgdGC0L7QuNGCINC70LXRh9C10L3QuNC1INC60LDRgNC40LXRgdCwLiDQndCwINCy0YLQvtGA0L7QvCDigJQg0L7RgdC70L7QttC90LXQvdC90L7Qs9C+INC60LDRgNC40LXRgdCwICjQv9GD0LvRjNC/0LjRgiwg0L/QtdGA0LjQvtC00L7QvdGC0LjRgiksINC30LDRgtC10Lwg0LjQtNC10YIg0LPQuNCz0LjQtdC90LAg0L/QvtC70L7RgdGC0Lgg0YDRgtCwLtCd0L7QstC+0Lkg0LrQu9C40L3QuNC60LUsINC10YHQu9C4INC+0L3QsCDQuNC30L3QsNGH0LDQu9GM0L3QviDQvdC1INC+0YDQuNC10L3RgtC40YDQvtCy0LDQvdCwINC90LAg0L/QsNGG0LjQtdC90YLQvtCyIFZJUCwg0L/RgNC40LTQtdGC0YHRjyDQvdCw0YfQuNC90LDRgtGMINGBINC90LjQt9C60L7Qs9C+INGG0LXQvdC+0LLQvtCz0L4g0YHQtdCz0LzQtdC90YLQsC4g0KHRgtC+0LjQvNC+0YHRgtGMINC70LXRh9C10L3QuNGPINC+0LTQvdC+0LPQviDQt9GD0LHQsCDQsdGD0LTQtdGCINCx0LvQuNC30LrQsCDQuiDRgNCw0YHRhtC10L3QutCw0Lwg0LPQvtGB0YPQtNCw0YDRgdGC0LLQtdC90L3Ri9GFINC60LvQuNC90LjQuiAoMS00INGC0YvRgS4g0YDRg9CxLikuINCh0YDQtdC00L3QuNC5INGH0LXQuiDQsiDQutC70LjQvdC40LrQtSDQsdGD0LTQtdGCINC+0LrQvtC70L4gMSw1INGC0YvRgS4g0YDRg9CxLiDQt9CwINC/0YDQuNC10LwsINGC0LDQuiDQutCw0Log0LTQvtGA0L7Qs9C+0LUg0LvQtdGH0LXQvdC40LUg0L7QsdGL0YfQvdC+INC/0YDQvtCy0L7QtNGP0YIg0LIg0L3QtdGB0LrQvtC70YzQutC+INGN0YLQsNC/0L7QsiDQuCDQutCw0LbQtNGL0Lkg0L7Qv9C70LDRh9C40LLQsNGO0YIg0L7RgtC00LXQu9GM0L3Qvi4g0JzQtdGB0Y/Rh9C90YvQuSDQvtCx0L7RgNC+0YIg0YHRgtC+0LzQsNGC0L7Qu9C+0LPQuNC4INGBINC00LLRg9C80Y8g0YPRgdGC0LDQvdC+0LLQutCw0LzQuCDQuCDQv9C+0YLQvtC60L7QvCDQv9C+0YHQtdGC0LjRgtC10LvQtdC5IDE2INGH0LXQu9C+0LLQtdC6INCyINC00LXQvdGMIOKAlCDQvtC60L7Qu9C+IDcwMC03NTAg0YLRi9GBLiDRgNGD0LEuINCi0LDQutCw0Y8g0LrQu9C40L3QuNC60LAg0L7QutGD0L/QuNGC0YzRgdGPINC30LAg0L/Rj9GC0Ywg0LvQtdGCLg0KDQrQrdC60YHQv9C10YDRgtGLINGD0LHQtdC20LTQtdC90YssINGH0YLQviDQstGB0LvQtdC0INC30LAg0YLQtdGA0LDQv9C40LXQuSDQvdC+0LLQvtC5INC60LvQuNC90LjQutC1INGB0YLQvtC40YIg0LLQvdC10LTRgNGP0YLRjCDQvtGA0YLQvtC/0LXQtNC40Y4sINC+0YDRgtC+0LTQvtC90YLQuNGOLCDRhdC40YDRg9GA0LPQuNGOINC4INGCLiDQtC4g0KfQtdC8INCx0L7Qu9GM0YjQtSDRg9GB0LvRg9CzINC/0YDQtdC00L7RgdGC0LDQstC70Y/QtdGCINGB0YLQvtC80LDRgtC+0LvQvtCz0LjRjywg0YLQtdC8INGD0LTQvtCx0L3QtdC1INC+0L3QsCDQtNC70Y8g0L/QsNGG0LjQtdC90YLQsCDQuCDQstGL0LPQvtC00L3QtdC1INC00LvRjyDQstC70LDQtNC10LvRjNGG0LAuwqDQndC10LrQvtGC0L7RgNGL0LUg0LjQs9GA0L7QutC4INGN0YLQvtCz0L4g0YDRi9C90LrQsCDRgdGH0LjRgtCw0Y7Rgiwg0YfRgtC+INC60LvQuNC90LjQutCwINC80L7QttC10YIg0YDQsNC30LLQuNCy0LDRgtGMINGC0L7RgNCz0L7QstC70Y4g0YDQsNGB0YXQvtC00L3Ri9C80Lgg0LzQsNGC0LXRgNC40LDQu9Cw0LzQuCDQuCDQvtCx0L7RgNGD0LTQvtCy0LDQvdC40LXQvC4NCg0K0J/RgNCw0LrRgtC40YfQtdGB0LrQuCDQstGB0LUg0LLQu9Cw0LTQtdC70YzRhtGLINGD0LLQtdGA0LXQvdGLLCDRh9GC0L4g0LHRg9C00YPRidC10LUg0YHRgtC+0LzQsNGC0L7Qu9C+0LPQuNC4INCy0L4g0LLQvdC10LTRgNC10L3QuNC4INC90L7QstGL0YUg0YLQtdGF0L3QvtC70L7Qs9C40LksINC60L7RgtC+0YDRi9C1INC/0L7Qt9Cy0L7Qu9GP0Y7RgiDRg9Cy0LXQu9C40YfQuNGC0Ywg0L/QvtGC0L7QuiDQv9Cw0YbQuNC10L3RgtC+0LIuINCS0YvQsdC+0YAg0YHQtdGC0Lgg0YHRgtC+0LzQsNGC0L7Qu9C+0LPQuNGH0LXRgdC60LjRhSDQutC70LjQvdC40LrCoNCe0J7QniAi0JrQu9GD0LEg0JPQvtC70LvQuNCy0YPQtNGB0LrQuNGFINCj0LvRi9Cx0L7QuiIsINCyINC60LDRh9C10YHRgtCy0LUg0LHQuNC30L3QtdGBINC/0LDRgNGC0L3QtdGA0LAg0LrQvtC80L/QsNC90LjQuCDQnNC10YLRgNC+0L/QvtC70LjRjyDQv9C+0LfQstC+0LvQuNGCINC90LDQvCDQvdCw0YfQsNGC0Ywg0L7RhdCy0LDRgiDRgtCw0LrQvtCz0L4g0L/RgNC40LHRi9C70YzQvdC+0LPQviDRgdC10LrRgtC+0YDQsCwg0LrQsNC6INC80LXQtNC40YbQuNC90LAuINCS0YHQtdGB0YLQvtGA0L7QvdC90Y/RjyDQv9C+0LzQvtGJ0Ywg0LIg0YPQu9GD0YfRiNC10L3QuNC4INGE0YDQsNC90YfQsNC50LfQuNC90LMg0L/RgNC+0LPRgNCw0LzQvNGLINC/0LDRgNGC0L3QtdGA0LAg0LAg0YLQsNC6INC20LUg0LzQsNGA0LrQtdGC0LjQvdCzINC/0L7Qt9C40YbQuNC+0L3QuNGA0L7QstCw0L3QuNGPINC+0L3Qu9Cw0LnQvSwg0L/QvtC80L7QttC10YIg0LHQuNC30L3QtdGB0YMg0LPQtdC90LXRgNC40YDQvtCy0LDRgtGMINC10YnQtSDQsdC+0LvRjNGI0LjQuSDQutCw0L/QuNGC0LDQuyDRh9C10Lwg0YHQtdC50YfQsNGBLg0KDQo8aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0yNDciIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA1L1NjcmVlbi1TaG90LTIwMTYtMDUtMjAtYXQtMjIuMDcuMzMtY29weS0xMDI0eDY4Mi5qcGciIGFsdD0iU2NyZWVuIFNob3QgMjAxNi0wNS0yMCBhdCAyMi4wNy4zMyBjb3B5IiB3aWR0aD0iNjUwIiBoZWlnaHQ9IjQzMyIgLz48aW1nIGNsYXNzPSJhbGlnbmNlbnRlciB3cC1pbWFnZS0yMzkiIHNyYz0iaHR0cHM6Ly9uZXdzLm1ldHJvcG9saWEub3JnL3dwLWNvbnRlbnQvdXBsb2Fkcy8yMDE2LzA1LzIyLTEwMjR4NjQ2LmpwZyIgYWx0PSIyMiIgd2lkdGg9IjY1MCIgaGVpZ2h0PSI0MTAiIC8+'),
                'image'            => 'posts/22.jpg',
                'slug'             => 'новый-партнер-ооо-клуб-голливудских',
                'meta_description' => 'this be a meta descript',
                'meta_keywords'    => 'keyword1, keyword2, keyword3',
                'status'           => 'PUBLISHED',
                'featured'         => 0,
                'created_at'       => '2016-05-21 08:38:24',
            ])->save();
        }
    }

    /**
     * [post description].
     *
     * @param [type] $slug [description]
     *
     * @return [type] [description]
     */
    protected function findPost($slug)
    {
        return Post::firstOrNew(['slug' => $slug]);
    }

    /**
     * [dataRow description].
     *
     * @param [type] $type  [description]
     * @param [type] $field [description]
     *
     * @return [type] [description]
     */
    protected function dataRow($type, $field)
    {
        return DataRow::firstOrNew([
                'data_type_id' => $type->id,
                'field'        => $field,
            ]);
    }

    /**
     * [dataType description].
     *
     * @param [type] $field [description]
     * @param [type] $for   [description]
     *
     * @return [type] [description]
     */
    protected function dataType($field, $for)
    {
        return DataType::firstOrNew([$field => $for]);
    }
}
