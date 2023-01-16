<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

use App\Repository\TokenRepository;

class ApiKeyAuthenticator extends AbstractAuthenticator
{
    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */

    private TokenRepository $tokenRepository;

    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('authorization');
    }

    public function authenticate(Request $request): Passport
    {

        $login = '';

        $token_livetime = 3800;

        $token_key = trim($request->headers->get('authorization'));
        $token_key = md5($token_key);

        if(!$token_key)
        {
            throw new CustomUserMessageAuthenticationException('Не найдено содержимое заголовка authorization');
        }


        $token = $this->tokenRepository->findOneBy(['token_key' => $token_key]);

        if (!$token)
        {
            throw new CustomUserMessageAuthenticationException("Вы не авторизованы");
        }

        if ((time() - $token->getTokenUntill()) > 0 )
        {
            throw new CustomUserMessageAuthenticationException("Время жизни токена истекло, требуется повторная аутентификация");
        }


        $login = $this->tokenRepository->findUserLoginByToken($token_key);


        return new SelfValidatingPassport(new UserBadge($login));

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {


        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];
        
        $response = new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        return $response;
    }
}