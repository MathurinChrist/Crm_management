<?php

namespace App\Modules\Sso\Provider;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;

class AzureProvider implements SsoProviderInterface
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
        return 'azure';
    }

    /**
     * Retourne les scopes nécessaires pour le provider Azure.
     */
    public function getScopes(): array
    {
        return ['User.Read'];
    }

    /**
     * @param array $token Tableau contenant les informations du token.
     *
     * @return array Les données utilisateur extraites depuis le provider Azure.
     */
    public function fetchUserData(array $token): array
    {
        $client = $this->clientRegistry->getClient($this->getClientKey());
        $azureUser = $client->fetchUserFromToken($token['token']);
        return $azureUser->toArray();
    }
}
