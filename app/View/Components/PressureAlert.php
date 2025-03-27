<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PressureAlert extends Component
{
    public $pressures;

    /**
     * Create a new component instance.
     */
    public function __construct($pressures)
    {
        $this->pressures = $pressures;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.pressure-alert');
    }
}
