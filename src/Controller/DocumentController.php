<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, JsonResponse};
use Symfony\Component\Routing\Annotation\Route;

class DocumentController extends AbstractController
{
    #[Route('/api/document', methods: ['POST'])]
    public function createDocument(Request $request):JsonResponse
    {
        return $this->json(['message'=>'this is create document controller'], 200)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/document/{document_key}', methods: ['PATCH'])]
    public function pathDocument(Request $request, string $document_key):JsonResponse
    {
        return $this->json(['message'=>'this is patch document controller'], 200)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    

}
