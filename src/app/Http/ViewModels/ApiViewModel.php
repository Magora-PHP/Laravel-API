<?php


namespace App\Http\ViewModels;

/**
 * Class ApiViewModel
 */
abstract class ApiViewModel
{

    /**
     * response status
     * @var int
     */
    protected $httpCode = 200;

    /**
     * Business code
     * @SWG\Property(type="string", title="code")
     * @var string
     */
    protected $code = '0';

    /**
     * Response data
     * @SWG\Property(type="object")
     * @var array
     */
    protected $data = [];


    /**
     * @param int $code
     * @return ApiViewModel $this
     */
    public function setHttpCode(int $code)
    {
        $this->httpCode = $code;
        return $this;
    }

    /**
     * @param string $code
     * @return ApiViewModel $this
     */
    public function setBusinessCode(string $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function render()
    {
        return response([
            'code' => $this->code,
            'data' => empty($this->data) ? new \stdClass() : $this->data
        ], $this->httpCode);
    }

}
