<?php

use Illuminate\Database\Seeder;
use App\Models\HtmlBlock;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Menu;

class HtmlBlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HtmlBlock::updateOrCreate([
            'name' => 'Блок баннера 1',
            'uri' => 'banner1',
            'content' => '<div id="widget_sp_image-4" class="widget baner_1 widget_sp_image"><a href="https://metropolia.org/" id="" target="_blank" class="widget_sp_image-image-link" title="" rel=""><img width="349" height="876" alt="" class="attachment-full" style="max-width: 100%;" src="http://teampay.lc/wp-content/uploads/2016/04/BANNER1.png"></a></div>',
            'active' => 1,
            'order' => 10,
        ], [
            'uri' => 'banner1',
        ]);

        HtmlBlock::updateOrCreate([
            'name' => 'Блок баннера 2',
            'uri' => 'banner2',
            'content' => '<div id="widget_sp_image-3" class="widget baner_1 widget_sp_image"><a href="https://metropolia.org/invest" id="" target="_self" class="widget_sp_image-image-link" title="" rel=""><img width="349" height="710" alt="" class="attachment-full" style="max-width: 100%;" src="http://teampay.lc/wp-content/uploads/2016/04/BANNER2.png"></a></div>',
            'active' => 1,
            'order' => 20,
        ], [
            'uri' => 'banner2',
        ]);

        HtmlBlock::updateOrCreate([
            'name' => 'Правый блок',
            'uri' => 'right-block',
            'content' => '<div class="block"><div id="text-2" class="widget block_test_1 widget_text"><div class="textwidget"><iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" allowfullscreen="true" class="twitter-timeline twitter-timeline-rendered" style="position: absolute; visibility: hidden; display: block; width: 0px; height: 0px; padding: 0px; border: none;"></iframe><a class="twitter-timeline twitter-timeline-error" href="https://twitter.com/MetropoliaRus" data-widget-id="717115601257369600" data-twitter-extracted-i1526302923294476216="true">Tweets by @MetropoliaRus</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div></div></div>',
            'active' => 1,
            'order' => 10,
        ], [
            'uri' => 'right-block',
        ]);

        $this->createHtmlBlocksBread();

        //Menu Item
        $menu = Menu::where('name', 'admin')->firstOrFail();
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Html blocks',
            'url'     => '',
            'route'   => 'voyager.html-blocks.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-list',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 15,
            ])->save();
        }

        Permission::generateFor('html_blocks');
    }

    private function createHtmlBlocksBread()
    {
        $id = DataType::updateOrCreate([
            'name' => 'html_blocks',
            'slug' => 'html-blocks',
            'display_name_singular' => 'Html Block',
            'display_name_plural' => 'Html Blocks',
            'icon' => 'voyager-list',
            'model_name' => 'App\\Models\\HtmlBlock',
            'generate_permissions' => 1,
            'server_side' => 1,
        ], [
            'name' => 'html_blocks',
            'slug' => 'html-blocks',
        ])->id;

        DataRow::updateOrCreate([
            'data_type_id' => $id,
            'field' => 'id',
            'type' => 'text',
            'display_name' => 'Id',
            'required' => 1,
            'browse' => 0,
            'read' => 0,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'order' => 1,
        ], [
            'data_type_id' => $id,
            'field' => 'id',
            'type' => 'text',
        ]);

        DataRow::updateOrCreate([
            'data_type_id' => $id,
            'field' => 'name',
            'type' => 'text',
            'display_name' => 'Name',
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 2,
        ], [
            'data_type_id' => $id,
            'field' => 'name',
            'type' => 'text',
        ]);

        DataRow::updateOrCreate([
            'data_type_id' => $id,
            'field' => 'uri',
            'type' => 'text',
            'display_name' => 'Uri',
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 3,
        ], [
            'data_type_id' => $id,
            'field' => 'uri',
            'type' => 'text',
        ]);

        DataRow::updateOrCreate([
            'data_type_id' => $id,
            'field' => 'content',
            'type' => 'text',
            'display_name' => 'Content',
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 4,
        ], [
            'data_type_id' => $id,
            'field' => 'content',
            'type' => 'text',
        ]);

        DataRow::updateOrCreate([
            'data_type_id' => $id,
            'field' => 'active',
            'type' => 'text',
            'display_name' => 'Active',
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 5,
        ], [
            'data_type_id' => $id,
            'field' => 'active',
            'type' => 'text',
        ]);

        DataRow::updateOrCreate([
            'data_type_id' => $id,
            'field' => 'order',
            'type' => 'text',
            'display_name' => 'Order',
            'required' => 1,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 1,
            'delete' => 1,
            'order' => 6,
        ], [
            'data_type_id' => $id,
            'field' => 'order',
            'type' => 'text',
        ]);

        DataRow::updateOrCreate([
            'data_type_id' => $id,
            'field' => 'created_at',
            'type' => 'timestamp',
            'display_name' => 'Created At',
            'required' => 0,
            'browse' => 1,
            'read' => 1,
            'edit' => 1,
            'add' => 0,
            'delete' => 1,
            'order' => 7,
        ], [
            'data_type_id' => $id,
            'field' => 'created_at',
            'type' => 'timestamp',
        ]);

        DataRow::updateOrCreate([
            'data_type_id' => $id,
            'field' => 'updated_at',
            'type' => 'timestamp',
            'display_name' => 'Updated At',
            'required' => 0,
            'browse' => 0,
            'read' => 0,
            'edit' => 0,
            'add' => 0,
            'delete' => 0,
            'order' => 8,
        ], [
            'data_type_id' => $id,
            'field' => 'updated_at',
            'type' => 'timestamp',
        ]);
    }
}
