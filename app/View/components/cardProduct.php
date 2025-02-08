<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class cardProduct extends Component
{
    /**
     * Create a new component instance.
     */
    public $pro;
    public function __construct($pro)
    {
        $this->pro = $pro;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.card-product');
    }
}
