<?php


namespace App\Http\ViewModels;

/**
 * Class ApiError
 *
 *  @SWG\Definition(
 *     required={"code", "message", "errors"},
 *     type="json",
 * )
 */
class ApiError
{

    /**
     * response status
     * @var int
     */
    protected $httpCode = 400;

    /**
     * Error code
     * @SWG\Property(type="string", title="code")
     * @var string
     */
    protected $errorCode = '400';

    /**
     * Module code
     * @SWG\Property(type="string", title="code")
     * @var string
     */
    protected $moduleCode = '000';

    /**
     * Business error message
     * @SWG\Property(type="string", title="code")
     * @var string
     */
    protected $message = 'Epic fail';


    /**
     * @SWG\Property(type="object")
     * @var array
     */
    protected $errors = [];


    /**
     * @param int $code
     * @return ApiError $this
     */
    public function setHttpCode(int $code)
    {
        $this->httpCode = $code;
        return $this;
    }

    /**
     * @param string $errorCode
     * @return ApiError $this
     */
    public function setErrorCode(string $errorCode)
    {
        $this->errorCode = $this->checkCode($errorCode);
        return $this;
    }

    /**
     * @param string $moduleCode
     * @return ApiError $this
     */
    public function setModuleCode(string $moduleCode)
    {
        $this->moduleCode = $this->checkCode($moduleCode);
        return $this;
    }


    /**
     * @param string $message
     * @return ApiError $this
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param string $moduleCode
     * @param string $errorCode
     * @param string $message
     * @param string $field
     * @throws ErrorCodeInvalidException
     */
    public function addError(
        string $moduleCode,
        string $errorCode,
        string $message,
        string $field
    )
    {
        $error = [
            'code' => $this->checkCode($moduleCode) . '-' . $this->checkCode($errorCode),
            'message' => $message,
            'field' => $field
        ];
        array_push($this->errors, $error);
    }

    /**
     * @param string $code
     * @return string
     * @throws ErrorCodeInvalidException
     */
    protected function checkCode(string $code)
    {
        $isValid = true;
        $isValid = $isValid && strlen($code) === 3;
        $isValid = $isValid && is_numeric($code);
        if (!$isValid) {
            throw new ErrorCodeInvalidException(); //todo implement
        }
        return $code;
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function render()
    {
        return response([
            'code' => $this->moduleCode . '-' . $this->errorCode,
            'message' => $this->message,
            'errors' => $this->errors
        ], $this->httpCode);
    }

}
