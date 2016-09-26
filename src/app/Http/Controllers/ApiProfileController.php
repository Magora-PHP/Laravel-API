<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostProfile;
use App\Http\ViewModels\ApiUserList;
use App\Http\ViewModels\ApiUserStore;
use App\Services\UserService;


class ApiProfileController extends Controller
{

    const MODULE = 'profile';

    /**
     * @SWG\Tag(
     *   name="profile",
     *   description="module code xxx",
     * ),
     */





    /**
     * @SWG\Get(
     *     path="profiles",
     *     tags={"profile"},
     *     @SWG\Response(
     *         response="200",
     *         description="success",
     *         @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/ApiUserList"))
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listing()
    {
        $userList = (new ApiUserList(
            UserService::getAllUsers()
        ))
            ->setBusinessCode('100500')
            ->setHttpCode(404);
        return $userList->render();
    }


    /**
     * @SWG\Post(
     *     path="profiles",
     *     tags={"profile"},
     *     @SWG\Response(
     *         response="201",
     *         description="success",
     *         @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/ApiUserStore"))
     *     ),
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         description="user email",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="user password",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="name",
     *         in="formData",
     *         description="user name",
     *         required=true,
     *         type="string"
     *     ),
     * )
     *
     * @param PostProfile $postProfileRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PostProfile $request)
    {
        $user = new ApiUserStore(UserService::store($request->all()));
        return $user->render();
    }
}
