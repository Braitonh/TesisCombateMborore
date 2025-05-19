<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DashboardCard extends Component
{
    public $imagen, $titulo, $tiempo, $calorias, $badge;

    public function __construct($imagen, $titulo, $tiempo, $calorias, $badge)
    {
        $this->imagen   = $imagen;
        $this->titulo   = $titulo;
        $this->tiempo   = $tiempo;
        $this->calorias = $calorias;
        $this->badge    = $badge;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard-card');
    }
}
