<?php

namespace App\Service\Ssr\Variables;

use App\Service\Ssr\SsrVariable;
use App\Models\Photo;

class IconsBlock extends SsrVariable {
    public function parse()
    {
        $this->getItems();
        return view($this->getViewName(), [
            'items' => $this->getItems(),
        ]);
    }

    public function getItems()
    {
        $items = [];
        $text = trim($this->args->icons, "\n");
        foreach(explode("\n", $text) as $line) {
            $line = trim($line);
            if ($line) {
                list($title, $desc, $icon) = explode('; ', $line);
                $items[] = (object) compact('title', 'desc', 'icon');
            }
        };
        return $items;
    }
}
