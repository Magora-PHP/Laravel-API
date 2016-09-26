<?php


namespace App\Http\ViewModels;


use App\Exceptions\FilterHttpException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Common view model for the both api and web parts
 * Class CommonViewModel
 */
abstract class CommonViewModel
{

    protected $models;

    protected $view;

    final public function __construct()
    {
        $this->setView($this->view);
        $this->models = new \stdClass();
    }


    public function addEntity($name, $model)
    {
        if (!isset($this->models->$name)) {
            $this->models->$name = $model;
            return $this;
        }
        throw new \Exception();//todo
    }

    public function render()
    {
        if ($this->isApiCall()) {
            return $this->jsonProps();
        }

        return view(
            $this->view,
            $this->tplProps()
        );
    }

    protected function jsonProps()
    {
        return get_object_vars($this->models);
    }

    protected function tplProps()
    {
        return ['models' => $this->models];
    }

    protected function isApiCall()
    {
        //todo regexp match api prefix
        return request()->route()->getPrefix() == '/api/v1';
    }

    public function setView($view)
    {
        if (!view()->exists($view)) {
            throw new ViewNotFoundException("Tpl $view not found.");
        }
        $this->view = $view;
    }
}
