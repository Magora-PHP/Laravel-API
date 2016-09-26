<?php


namespace App\Http\ViewModels;


use App\Exceptions\FilterHttpException;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
/**
 * Class ApiUserStore
 *
 * @SWG\Definition(
 *     required={"code", "data"},
 *     type="json",
 *     example={"code":0,"data":{"user":{ "id":2,"email":"qqq@qqq.qqq2","name":"qqq@qqq.qqq2"}}}
 * )
 */
class ApiUserStore extends ApiViewModel
{
    /**
     * ApiUserStore constructor.
     * @param User $user
     * @return ApiUserStore $this
     */
    public function __construct(User $user)
    {
        $this->data = ['user' => $user];
        $this->setHttpCode(201);
        return $this;
    }

}
