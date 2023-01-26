<?php
namespace App\Service;


use App\Entity\{Document, User};
use App\Data\{DocumentsData, DocumentItem, DocumentItemList};
use App\Repository\{UserRepository, DocumentRepository};

class DocumentsService
{
	private UserRepository $userRepository;
	private DocumentRepository $documentRepository;
	private PaginationService $paginationService;


	public function __construct(UserRepository $userRepository, DocumentRepository $documentRepository, PaginationService $paginationService)
	{
		$this->userRepository = $userRepository;
		$this->documentRepository = $documentRepository;
		$this->paginationService = $paginationService;
	}

	

	public function getDocuments(DocumentsData $documentsData):DocumentItemList
	{
		$documents_count = 5;
		$offset = 0;

		$this->paginationService->setCurrentPage($documentsData->getCurrentPage())->setRowsCount($documents_count);

		$offset = $this->paginationService->getOffset();

		$documents = $this->documentRepository->getPublishedDocuments($offset, $documents_count);

		return $this->getDocumentItemList($documents);
	}



	public function getMyAllDocuments(DocumentsData $documentsData, User $currentUser):DocumentItemList
	{
		$documents_count = 5;
		$offset = 0;


		$this->paginationService->setCurrentPage($documentsData->getCurrentPage())->setRowsCount($documents_count);

		$offset = $this->paginationService->getOffset();

		$documents = $this->documentRepository->getUserDocuments($offset, $documents_count, $currentUser);

		return $this->getDocumentItemList($documents);

	}


	public function getMyDocumentsByStatus(DocumentsData $documentsData, User $currentUser):DocumentItemList
	{
		$documents_count = 5;
		$offset = 0;
		$document_status = $documentsData->getDocumentsStatus();

		$this->paginationService->setCurrentPage($documentsData->getCurrentPage())->setRowsCount($documents_count);

		$offset = $this->paginationService->getOffset();

		$documents = $this->documentRepository->getUserDocumentsByStatus($offset, $documents_count, $currentUser, $document_status);

		return $this->getDocumentItemList($documents);

	}



	private function getDocumentItemList(array $documents):DocumentItemList
	{
		return new DocumentItemList(
			array_map(function(Document $document)
				{
					$createAt = date("Y-m-d H:i:s", $document->getCreateAt());
					$modifyAt = date("Y-m-d H:i:s", $document->getModifyAt());

					return (new DocumentItem())
							->setDocumentKey($document->getDocumentKey())
							->setDocumentStatus($document->getDocumentStatus())
							->setDocumentPayload($document->getDocumentPayload())
							->setCreateAt($createAt)
							->setModifyAt($modifyAt);


				}, $documents)

		);
	}




}

