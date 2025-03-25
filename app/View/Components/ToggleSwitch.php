<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ToggleSwitch extends Component
{
    public $activo;
    public $url;
    public $id;

    public function __construct($activo, $url, $id)
    {
        $this->activo = $activo;
        $this->url = $url;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.toggle-switch');
    }
}
