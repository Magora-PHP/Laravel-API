<?php


namespace App\Http\ViewModels;


use App\Exceptions\FilterHttpException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Class ApiUserList
 *
 * @SWG\Definition(
 *     required={"code", "data"},
 *     type="json",
 *     example={"code":0,"data":{"users":{{"id":2,"name":"uahaha","email":"bla@bla.bla"}}}}
 * )
 */
class ApiUserList extends ApiViewModel
{

    /**
     * ApiUserList constructor.
     * @param Collection $users
     * @return ApiUserList $this
     */
    public function __construct(Collection $users)
    {
        $this->data = ['users' => $users];
        return $this;
    }

}
