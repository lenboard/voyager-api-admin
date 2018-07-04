<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Menu;

class TranslatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Menu Item
        $menu = Menu::where('name', 'admin')->firstOrFail();
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'Translates',
            'url'     => '/admin/translates',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-world',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 16,
            ])->save();
        }
    }
}