<?php

namespace App\Controller;


use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Annotation\Route;
use App\Modules\RequestDataChecker;
use App\Data\DocumentData;
use App\Service\DocumentService;




class DocumentController extends AbstractController
{
    use RequestDataChecker;

    private DocumentService $documentService;
    private UserInterface $currentUser;


    public function __construct(DocumentService $documentService, Security $security)
    {
        $this->documentService = $documentService;
        $this->currentUser = $security->getUser();


    }


    #[Route('/api/document/create', methods: ['POST'])]
    public function createDocument(Request $request):JsonResponse
    {
        $request_params = $request->toArray();

        $this->checkInner($request_params, ['document_payload']);
        $this->checkRequired($request_params, ['document_payload']);


      
        $document_params = [
            'document_payload' => json_encode($request_params['document_payload']),
        ];


        $response = $this->documentService->createDocument(new DocumentData($document_params), $this->currentUser);

        return $this->json($response, 201)->setEncodingOptions(JSON_UNESCAPED_UNICODE);

    }   

    #[Route('/api/document/{document_key}/patch', methods: ['PATCH'])]
    public function pathDocument(Request $request, string $document_key):JsonResponse
    {
        $request_params = $request->toArray();

        $this->checkInner($request_params, ['document_payload']);
        $this->checkRequired($request_params, ['document_payload']);

        $document_params = [
            'document_key' => $document_key,
            'document_payload' => json_encode($request_params['document_payload']),

        ];

        $response = $this->documentService->pathDocument(new DocumentData($document_params), $this->currentUser);



        return $this->json($response, 201)->setEncodingOptions(JSON_UNESCAPED_UNICODE);

    }

    #[Route('/api/document/{document_key}/publish', methods: ['POST'])]
    public function publishDocument(Request $request, string $document_key):JsonResponse
    {

        $document_params = [
            'document_key' => $document_key,
        ];


        $response = $this->documentService->publishDocument(new DocumentData($document_params), $this->currentUser);

        return $this->json($response, 200)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }



    #[Route('/api/document/read', methods: ['GET'])]
    public function getDocuments(Request $request):JsonResponse
    {
       $page = ($request->query->get('page')) ? $request->query->get('page') : 1;


       $response = $this->documentService->getDocuments($page);

       return $this->json($response, 200)->setEncodingOptions(JSON_UNESCAPED_UNICODE);

    }



}
