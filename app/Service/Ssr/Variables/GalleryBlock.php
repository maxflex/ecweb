<?php

namespace App\Service\Ssr\Variables;

use App\Service\Ssr\SsrVariable;
use App\Models\Photo;

class GalleryBlock extends SsrVariable {
    public function parse()
    {
        return view($this->getViewName(), [
            'items' => $this->getItems(),
        ]);
    }

    public function getItems()
    {
        $items = Photo::whereIn('id', explode(',', $this->args->ids))->get()->all();
        // В десктопную версию для зацикливания добавляем элемент
        // 3 фотки в начало и 3 фотки в конец 
        if (! isMobile()) {
            // foreach(range(1, 3) as $i) {
            //     $items
            // }
            // $items[] = $items[0];
            // $items[] = $items[1];
            // $items[] = $items[3];
            // array_unshift($items, $items[count($items) - 2]);
            // array_unshift($items, $items[count($items) - 4]);
            // array_unshift($items, $items[count($items) - 5]);
            // array_unshift($items, $items[count($items) - 6]);
        }
        return $items;
    }
}
