<?php

namespace App\View\Components\Chart;

use Illuminate\View\Component;

class Line extends Component
{
    public $labels;
    public $datasets;
    public $aspectRatio;

    public function __construct($labels = [], $datasets = [], $aspectRatio = 2)
    {
        $this->labels = $labels;
        $this->datasets = $datasets;
        $this->aspectRatio = $aspectRatio;
    }

    public function render()
    {
        return view('components.chart.line');
    }
}