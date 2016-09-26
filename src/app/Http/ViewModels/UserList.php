<?php


namespace App\Http\ViewModels;


use App\Exceptions\FilterHttpException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserList extends CommonViewModel
{
    protected $view = 'user-list';

    protected function jsonProps()
    {
        foreach ($this->models->users as $user) {
            $user->foo = 'bar';
            $user->pushTokens;
        }

        return [
            'uahahha' => [
                'users' => $this->models->users,
                'profiles' => $this->models->profiles,
            ],
            'count' => $this->models->users->count()
        ];
    }

    public function addUsers($users)
    {
        ///....

        $this->addEntity('users', $users);
    }
    protected function tplProps()
    {
        return [
            'users' => $this->models->users,
            'count' => $this->models->users->count()
        ];
    }
}
