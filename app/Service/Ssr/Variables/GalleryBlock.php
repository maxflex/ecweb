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
        if ($this->args->ids) {
            return Photo::whereIn('id', explode(',', $this->args->ids))->get()->all();
        }
        return Photo::all();
    }
}
