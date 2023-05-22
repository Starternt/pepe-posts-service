<?php

declare(strict_types=1);

namespace App\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class ApiUserJwtAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    private const HEADER_NAME = 'Authorization';

    public function supports(Request $request): bool
    {
        return $request->headers->has(self::HEADER_NAME);
    }

    public function authenticate(Request $request): Passport
    {
        $token = (string) $request->headers->get(self::HEADER_NAME);
        $test = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImlzcyI6InRlc3QxMjMifQ.eyJpYXQiOjE2ODAzODkzOTcsImV4cCI6MTY4MDQyNTM5Nywicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoic3RhcnRlciIsInVzZXJfaWQiOjF9.OlO1p4GcNwuajhnHz2EHCtroJ7pd3umZUi1YkjQDC4p4h457rqbb9Fxlng7THSx7qEtqtP3T0bDfYQ7XE_CetRzrZiB2yi4IBs_e6dizNgr0LWTzaR-8c2xELIsaEdxun2Cv5p6whtlzg7fmjX0q9Afv92JPLyQdbP_fdwnkljS5UqSmc6rEa_OCjK0owe7zE9PSqmYFdkWu9oYDAcLcV_htwPIOdxKd_71K9JASniovMxEJvH0oBRXphrieO4E_l2WAfw7GQhQdiwt3lfBTwFG0Y14WNp54sF5BHJfEs2m_vyFI-o6Dxl0D-6YNUyz_JnYj4GkcAp6aiyvm8N3SgQ';

        dump(32); exit();
        $jwt = JWT::decode($test, new Key('-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAocgT7rkIQ6hlr96cWHMk
LvoPiyY6GbgPL67Ao8g3FQLyljFKXX5azKxDGi0abfxIrryljRq0V/qx261MiKxS
ssAnfLlz1HyFzsufW/mAifLLa23BazVQwdzF6l28ht6TpYB//1xQTqhs9ui7dk3e
3vORl+gu0Lj5vVrBHcgwQOx1pFRL2dnB2sAu1n3krS8o+0nSQdKvwd6QJGpyUJR2
zeA1wjS4b2RO/Juo62m/AnjUZhRWd35X5tor0YRD3DavovM45bzvmlU6vxEQs/va
MVflzW62XS951qguF8WJePjAfJg4e/b9DuU2+8MWTfmkYNF/ZyfYbvtETsXO6xTA
mwIDAQAB
-----END PUBLIC KEY-----
', 'RS256'));
        dump($jwt); exit();


        $apiUser = $this->apiUserRepository->findOneBy(['apiKey' => $token]);
        if (null === $apiUser) {
            throw new AuthenticationException('Access token not found');
        }

        return new SelfValidatingPassport(new UserBadge($apiUser->getUserIdentifier()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        $errorResponse = [
            'success' => false,
            'data' => [
                'code' => 401,
                'message' => '',
                'errors' => null,
            ],
        ];

        return new JsonResponse($errorResponse, 401);
    }
}
