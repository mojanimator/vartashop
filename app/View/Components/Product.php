<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Product extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $product;
    public $img;

    public $type;


    public function __construct($product, $img)
    {
        $this->product = $product;
        $this->img = $img;

//        $this->type = $type;
    }

    /**
     * The properties / methods that should not be exposed to the component template.
     *
     * @var array
     */
    protected $except = ['t1'];

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.product');
    }
}
