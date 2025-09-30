<?php

namespace Aslnbxrz\OneID;

use Illuminate\Support\Facades\Log;
use Throwable;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use SocialiteProviders\Manager\ConfigTrait;

final class OneIDLogout
{
    use ConfigTrait;

    public function handle($accessTokenOrSessionId): void
    {
        $client = new Client();
        try {
            $client->post(rtrim($this->getConfig('base_url', 'https://sso.egov.uz'), '/') . '/sso/oauth/Authorization.do', [
                RequestOptions::FORM_PARAMS => [
                    'grant_type' => 'one_log_out',
                    'client_id' => $this->getConfig('client_id'),
                    'client_secret' => $this->getConfig('client_secret'),
                    'access_token' => $accessTokenOrSessionId,
                    'scope' => $this->getConfig('scope', 'one_code'),
                ],
                'headers' => ['Accept' => 'application/json'],
            ]);
        } catch (Throwable $e) {
            Log::error('OneIDSocialiteThrow', [$e->getMessage()]);
        }
    }
}