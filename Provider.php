<?php

namespace SocialiteProviders\OneID;

use GuzzleHttp\RequestOptions;
use Laravel\Socialite\Two\User;
use SocialiteProviders\Manager\ConfigTrait;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User as OAuth2User;

class Provider extends AbstractProvider
{
    use ConfigTrait;

    public const IDENTIFIER = 'ONEID';

    protected string $scope = 'one_code';

    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase(rtrim($this->getBaseUrl(), '/') . '/sso/oauth/Authorization.do', $state);
    }

    protected function getTokenUrl(): string
    {
        return rtrim($this->getBaseUrl(), '/') . '/sso/oauth/Authorization.do';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            RequestOptions::FORM_PARAMS => [
                'grant_type' => 'one_access_token_identify',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'access_token' => $token,
                'scope' => $this->getScope(),
            ],
            'headers' => ['Accept' => 'application/json'],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function getCodeFields($state = null): array
    {
        $fields = parent::getCodeFields($state);
        $fields['response_type'] = 'one_code';
        $fields['scope'] = $this->getScope();
        $fields['state'] = $state;
        return $fields;
    }

    protected function getTokenFields($code): array
    {
        $fields = parent::getTokenFields($code);
        $fields['grant_type'] = 'one_authorization_code';
        return $fields;
    }

    protected function mapUserToObject(array $user): User|OAuth2User
    {
        $name = $user['full_name'] ?? trim(($user['first_name'] ?? '') . ' ' . ($user['sur_name'] ?? '') . ' ' . ($user['mid_name'] ?? ''));

        return (new OAuth2User())->setRaw($user)->map([
            'id' => $user['user_id'] ?? $user['pin'] ?? $user['sess_id'] ?? null,
            'nickname' => $user['user_id'] ?? null,
            'name' => $name ?: null,
            'email' => $user['email'] ?? null,
            'avatar' => null,
        ]);
    }

    protected function getBaseUrl(): string
    {
        return $this->getConfig('base_url', 'https://sso.egov.uz');
    }

    protected function getScope(): string
    {
        return (string)($this->getConfig('scope', $this->scope));
    }
}


