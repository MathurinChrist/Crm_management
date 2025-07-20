<?php

namespace App\Modules\Sso\Provider;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;

class GoogleProvider implements SsoProviderInterface
{
    private ClientRegistry $clientRegistry;

    public function __construct(ClientRegistry $clientRegistry)
    {
        $this->clientRegistry = $clientRegistry;
    }

    /**
     * Retourne la clé du client utilisée pour identifier ce provider.
     */
    public function getClientKey(): string
    {
        return 'google';
    }

    /**
     * Retourne les scopes nécessaires pour le provider google.
     */
    public function getScopes(): array
    {
        return  [
            'openid',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
        ];
    }

    /**
     * Récupère les données utilisateur depuis le token fourni.
     *
     * @param array $token Tableau contenant les informations du token.
     *
     * @return array Les données utilisateur extraites depuis le provider google.
     */
    public function fetchUserData(array $token): array
    {
        $client = $this->clientRegistry->getClient($this->getClientKey());
        $googleUser = $client->fetchUserFromToken($token['token']);
        return $googleUser->toArray();
    }
}
