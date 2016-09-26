<?php


namespace App\Services;

use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use League\OAuth2\Client\Provider\Facebook;


class SocialService
{
    /** @var array */
    protected $providers;

    public function __construct()
    {

        $fb = new Facebook([
            'clientId'          => '',
            'clientSecret'      => '',
            'redirectUri'       => $this->getRedirectUrl('facebook'),
            'graphApiVersion'   => 'v2.2',
        ]);
        $fb->name = 'facebook';

        $this->providers = [
            'facebook' => $fb,
        ];
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->providers)) {
            return $this->providers[$name];
        }
        $e = new InvalidArgumentException("Social provider $name not supported.");
        $e->moduleCode = Config::get('codes.Auth', '000');
        $e->field = 'social';
        throw $e;
    }

    /**
     * @param string $soc
     * @return string
     */
    protected function getRedirectUrl($soc)
    {
        return 'http://localhost';
    }
}
