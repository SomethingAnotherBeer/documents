<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Annotation\Route;
use App\Data\DocumentsData;
use App\Service\DocumentsService;


class DocumentsController extends AbstractController
{
    private DocumentsService $documentsService;
    private UserInterface $currentUser;

    public function __construct(DocumentsService $documentsService, Security $security)
    {
        $this->documentsService = $documentsService;
        $this->currentUser = $security->getUser();
    }


    #[Route('/api/documents/get', methods: ['GET'])]
    public function getDocuments(Request $request):JsonResponse
    {
        $page = ($request->query->get('page')) ? $request->query->get('page') : 1;

        $documents_params = [
            'current_page' => $page,
        ];

        $response = $this->documentsService->getDocuments(new DocumentsData($documents_params));

        return $this->json($response, 200)->setEncodingOptions(JSON_UNESCAPED_UNICODE);

    }

    #[Route('/api/documents/my', methods: ['GET'])]
    public function getMyDocuments(Request $request):JsonResponse
    {
        $documents_params = [
            'current_page' => ($request->query->get('page')) ? $request->query->get('page') : 1,
        ];

        $response = $this->documentsService->getMyAllDocuments(new DocumentsData($documents_params), $this->currentUser);

        return $this->json($response, 200)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/documents/my/{documents_status}', methods:['GET'])]
    public function getMyDocumentsByStatus(Request $request, string $documents_status):JsonResponse
    {
        $documents_params = [
            'documents_status' => $documents_status,
            'current_page' => ($request->query->get('page')) ? $request->query->get('page') : 1,
        ];


        $response = $this->documentsService->getMyDocumentsByStatus(new DocumentsData($documents_params), $this->currentUser);

        return $this->json($response, 200)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    

}
