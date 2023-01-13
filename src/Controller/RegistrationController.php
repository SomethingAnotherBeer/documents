<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RegistrationService;
use App\Modules\RequestDataChecker;
use App\Data\UserData;


class RegistrationController extends AbstractController
{
    use RequestDataChecker;


    private RegistrationService $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    #[Route('/api/registration')]
    public function registration(Request $request):JsonResponse
    {   
        $request_params = $request->toArray();
        $this->checkInner($request_params, ['login', 'password']);
        $this->checkRequired($request_params, ['login', 'password']);

        $response = $this->registrationService->registration(new UserData($request_params));

        return $this->json($response, 201)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
