<?php


namespace App\Http\ViewModels;


use App\Exceptions\FilterHttpException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

abstract class WebViewModel
{

    /**
     * Tpl name
     * @var string
     */
    protected $view;

    /** @var array|\JsonSerializable  */
    protected $data = [];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws ViewNotFoundException
     */
    public function render()
    {
        if (!view()->exists($this->view)) {
            throw new ViewNotFoundException("Tpl $this->view not found.");
        }
        return view($this->view, $this->data);
    }

}
