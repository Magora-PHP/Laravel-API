<?php

namespace App\Http\Controllers;



use App\Http\Requests\PostProfile;
use App\Http\ViewModels\WebUserList;
use App\Services\UserService;

class WebProfileController extends Controller
{


    public function listing()
    {
        $userList = new WebUserList(
            UserService::getAllUsers()
        );

        return $userList->render();
    }

    public function store(PostProfile $request)
    {
        UserService::store($request->all());
        return redirect()->action('WebProfileController@listing');
    }

    public function create()
    {
        return view('user-create');
    }

}
