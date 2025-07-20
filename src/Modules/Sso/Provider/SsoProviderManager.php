<?php

namespace App\Modules\Sso\Provider;

class SsoProviderManager
{
    /** @var iterable<SsoProviderInterface> */
    private iterable $providers;

    /**
     * @param iterable<SsoProviderInterface> $providers
     */
    public function __construct(iterable $providers)
    {
        $this->providers = $providers;
    }

    public function getProvider(string $providerKey): SsoProviderInterface
    {
        foreach ($this->providers as $provider) {
            if ($provider->getClientKey() === $providerKey) {
                return $provider;
            }
        }
        throw new \InvalidArgumentException("Provider $providerKey not found.");
    }
}
