<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class modalpopup extends Component
{
    /**
     * Create a new component instance.
     */
    public $hods;

    public function __construct($hods = null)
{
    $this->hods = $hods ?? collect(); // Default to empty collection if none passed
}
    
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modalpopup');
    }
}


