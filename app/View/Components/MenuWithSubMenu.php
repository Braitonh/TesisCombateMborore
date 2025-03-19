<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MenuWithSubMenu extends Component
{

    public string $id;
    public string $name;
    public string $icon;
    public array $subMenus;

    
    /**
     * Create a new component instance.
     */
    public function __construct(string $id, string $name, string $icon, array $subMenus) {
        $this->id = $id;
        $this->name = $name;
        $this->icon = $icon;
        $this->subMenus = $subMenus;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.menu-with-sub-menu');
    }
}
