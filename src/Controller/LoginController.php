<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Annotation\Route;

use App\Service\LoginService;
use App\Data\UserData;
use App\Modules\RequestDataChecker;


class LoginController extends AbstractController
{

    use RequestDataChecker;

    private LoginService $loginService;
    private Security $security;

    public function __construct(LoginService $loginService, Security $security)
    {
        $this->loginService = $loginService;
        $this->security = $security;
    }

    
    #[Route('/api/login', methods: ['POST'])]
    public function login(Request $request):JsonResponse
    {
        $request_params = $request->toArray();

        $this->checkInner($request_params, ['login', 'password']);
        $this->checkRequired($request_params, ['login', 'password']);

        $response = $this->loginService->login(new UserData($request_params));

        return $this->json($response, 201)->setEncodingOptions(JSON_UNESCAPED_UNICODE); 

    }   


    #[Route('/api/logout', methods: ['POST'])]
    public function logout():JsonResponse
    {
        $current_user = $this->security->getUser();

        $response = $this->loginService->logout($current_user);

        return $this->json($response, 200)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }   

    

}
