<?php

namespace App\View\Components;

use App\FormEmbedd;
use Illuminate\View\Component;

class FormEmbeddesLinks extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $forms = FormEmbedd::query()->where('status', '=', '1')->get();

        return view('components.form-embeddes-links', ['forms' => $forms]);
    }
}
