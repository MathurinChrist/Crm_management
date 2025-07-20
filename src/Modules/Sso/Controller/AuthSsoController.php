<?php

namespace App\Modules\Sso\Controller;

use App\Modules\Sso\Provider\SsoProviderManager;
use App\Security\Repository\UserRepository;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthSsoController extends AbstractController
{
    private SsoProviderManager $ssoProviderManager;
    private ClientRegistry $clientRegistry;

    public function __construct(
        SsoProviderManager $ssoProviderManager,
        ClientRegistry $clientRegistry
    ) {
        $this->ssoProviderManager = $ssoProviderManager;
        $this->clientRegistry = $clientRegistry;
    }

    #[Route('/auth/{provider}', name: 'auth_provider', methods: 'GET')]
    public function connect(string $provider, Request $request): RedirectResponse
    {
        $ssoProvider = $this->ssoProviderManager->getProvider($provider);
        $callbackUrl = $_ENV['API_BASE_URL'] . '/auth/callback/' . $provider;
        return $this->clientRegistry
            ->getClient($ssoProvider->getClientKey())
            ->redirect($ssoProvider->getScopes(), ['redirect_uri' => $callbackUrl]);
    }

    #[Route('/auth/callback/{provider}', name: 'auth_callback', methods: 'GET')]
    public function ssoCallback(
        string $provider,
        Request $request,
        UserRepository $userRepository,
        JWTTokenManagerInterface $tokenManager
    ): RedirectResponse {
        $code = $request->query->get('code');
        if (empty($code)) {
            throw new AuthenticationException('Code parameter is missing', 400);
        }

        $ssoProvider = $this->ssoProviderManager->getProvider($provider);
        $httpClient = $this->clientRegistry->getClient($ssoProvider->getClientKey());
        $httpClient->setAsStateless();
        $accessToken = $httpClient->getAccessToken();
        $userInformation = $ssoProvider->fetchUserData(['token' => $accessToken]);

        $userName = $provider === 'google' ? 'email' : 'upn';

        $user = $userRepository->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $userInformation[$userName])
            ->getQuery()
            ->getResult();
        $user = $user[0] ?? null;


        if ($user !== null) {
            $token = $tokenManager->create($user);
            return $this->redirect($_ENV['FRONT_BASE_URL'] . '/authentication/loadingPage?token=' . $token);
        }
        return $this->redirect( $_ENV['FRONT_BASE_URL'] . '/authentication/loadingPage');
    }
}
