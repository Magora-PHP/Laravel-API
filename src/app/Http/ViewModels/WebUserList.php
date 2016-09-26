<?php


namespace App\Http\ViewModels;


use App\Exceptions\FilterHttpException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class WebUserList extends WebViewModel
{
    protected $view = 'user-list';

    protected $data;

    /**
     * WebUserList constructor.
     * @param Collection $users
     * @return WebUserList $this
     */
    public function __construct(Collection $users)
    {
        $this->data = [
            'users' => $users,
            'count' => $users->count()
        ];

        return $this;
    }

}
