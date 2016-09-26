<?php

namespace App\Http\Requests;

use App\Http\ViewModels\ApiError;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

abstract class CommonRequest extends FormRequest
{

    const DEFAULT_ERROR_CODE = 422;

    const CUSTOM_ERROR_CODES = [
        '003' => 'The .* may not be greater than \d+ characters.',
        '013' => 'The .* has already been taken.',
        '010' => 'The .* must be a valid email address.',
        '004' => 'The .* must be at least \d+ characters.',
        '002' => 'The .* field is required.',
    ];

    public function response(array $errors)
    {

        if (substr(Route::current()->getPrefix(), 0, 5) !== '/api/') {
            return parent::response($errors);
        }

        $response = new ApiError();
        $response->setHttpCode(self::DEFAULT_ERROR_CODE);
        $moduleCode = $this->getModuleCode();
        $response->setModuleCode($moduleCode);
        $response->setErrorCode('001');
        $response->setMessage('Validations error');

        array_map(function($field, $messages) use ($response, $moduleCode){
            foreach($messages as $msg){
                $response->addError(
                    $moduleCode,
                    $this->getCodeByMsg($msg),
                    $msg,
                    $field
                );
            }
        }, array_keys($errors), $errors);

        return $response->render();
    }

    protected function getModuleCode()
    {
        $action = $this->route()->getAction();
        preg_match(
            '#App\\\Http\\\Controllers\\\Api([a-zA-Z]+)Controller@#',
            $action['controller'],
            $matches,
            PREG_OFFSET_CAPTURE
        );
        $module = empty($matches[1][0]) ? 'Common' : $matches[1][0];
        return Config::get('codes.' . ucfirst($module), '000');
    }

    protected function getCodeByMsg($msg)
    {
        foreach (self::CUSTOM_ERROR_CODES as $k => $v) {
            preg_match('#' . $v . '#', $msg, $matches, PREG_OFFSET_CAPTURE);
            if (!empty($matches)) {
                return $k;
            }
        }
        return self::DEFAULT_ERROR_CODE;
    }

}
