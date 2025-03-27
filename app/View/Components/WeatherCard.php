<?php

namespace App\View\Components;

use Illuminate\View\Component;

class WeatherCard extends Component
{
    public $weather;
    public $placeName;
    public $pressures;

    /**
     * Create a new component instance.
     *
     * @param array $weather
     * @param string|null $placeName
     */
    public function __construct($weather, $placeName = null, $pressures = null)
    {
        $this->weather = $weather;
        $this->placeName = $placeName;
        $this->pressures = collect($pressures); // null対策で常にコレクション化
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.weather-card');
    }
}
