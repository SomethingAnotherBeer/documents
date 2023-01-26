<?php 
namespace App\Service;

use App\Entity\{Document, User};
use App\Repository\{DocumentRepository, UserRepository};
use App\Data\{DocumentData, DocumentItem, DocumentItemList, PaginationData};
use Symfony\Component\Security\Core\User\UserInterface;
use \DateTimeImmutable;
use \DateTimeZone;
use App\Exception\DocumentException\{DocumentNotFoundException, CannotUpdatePublishedDocumentException, DocumentAccessDeniedException, DocumentAlreadyPublishedException};

class DocumentService
{
	private DocumentRepository $documentRepository;
	private UserRepository $userRepository;
	private PaginationService $paginationService;


	public function __construct(DocumentRepository $documentRepository, UserRepository $userRepository)
	{
		$this->documentRepository = $documentRepository;
		$this->userRepository = $userRepository;

	}


	public function createDocument(DocumentData $documentData, User $currentUser):DocumentItem
	{
		$document_key = $this->generateDocumentKey();
		$document_payload = $documentData->getDocumentPayload();
		$document_status = 'draft';
		$createAt = (new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow')))->getTimestamp();
		$modifyAt = $createAt;

		$document = (new Document())
					->setUserRel($currentUser)
					->setDocumentKey($document_key)
					->setDocumentPayload($document_payload)
					->setDocumentStatus($document_status)
					->setCreateAt($createAt)
					->setModifyAt($modifyAt);


		$this->documentRepository->save($document, true);

		return $this->getDocumentItem($document);



	}


	public function patchDocument(DocumentData $documentData, User $currentUser):DocumentItem
	{
		$updated_payload = [];
		$old_paytload = [];
		$new_payload = [];

		$modifyAt = 0;

		$document_key = $documentData->getDocumentKey();

		$document = $this->documentRepository->getDocumentByKey($document_key);


		if (!$document)
		{
			throw new DocumentNotFoundException("Документ не найден");
		}

		if ($document->getUserRel() !== $currentUser)
		{
			throw new DocumentAccessDeniedException("У вас нет прав на редактирование этого документа");
		}

		if('published' === $document->getDocumentStatus())
		{
			throw new CannotUpdatePublishedDocumentException("Невозможно отредактировать опубликованный документ");
		}


		$old_payload = json_decode($document->getDocumentPayload(), true);
		$new_payload = json_decode($documentData->getDocumentPayload(), true);

		$updated_payload = $this->getUpdatedContent($old_payload, $new_payload);
		$document->setDocumentPayload(json_encode($updated_payload));

		$modifyAt = (new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow')))->getTimestamp();
		$document->setModifyAt($modifyAt);

		$this->documentRepository->save($document, true);

		return $this->getDocumentItem($document);

	}


	public function publishDocument(DocumentData $documentData, User $currentUser):DocumentItem
	{
		$document_key = $documentData->getDocumentKey();

		$document = $this->documentRepository->getDocumentByKey($document_key);

		if (!$document)
		{
			throw new DocumentNotFoundException("Документ не найден");
		}

		if ($document->getUserRel() !== $currentUser)
		{
			throw new DocumentAccessDeniedException("У вас нет прав доступа к этому документу");
		}

		if ('published' === $document->getDocumentStatus())
		{
			throw new DocumentAlreadyPublishedException("Данный документ уже опубликован");
		}


		$document->setDocumentStatus('published');

		$this->documentRepository->save($document, true);

		return $this->getDocumentItem($document);

	}


	public function deleteDocument(DocumentData $documentData, User $currentUser):DocumentItem
	{
		$document_key = $documentData->getDocumentKey();

		$document = $this->documentRepository->getDocumentByKey($document_key);

		if (!$document)
		{
			throw new DocumentNotFoundException("Документ не найден");

		}

		if ($document->getUserRel() !== $currentUser)
		{
			throw new DocumentAccessDeniedException("у вас нет прав доступа к этому документу");
		}

		$this->documentRepository->remove($document, true);

		return $this->getDocumentItem($document);

	}




	private function generateDocumentKey():string
	{
		$document_key_len = 32;
		$document_key = '';

		for($i = 0; $i < $document_key_len; $i++)
		{
			if (($i >= 8 && $i % 4 == 0) && $i <= 20)
			{
				$document_key.= '-';
			}

			$document_key.= (rand(0,1)) ? rand(0, 9) : chr(rand(97, 122));

		}


		return $document_key;

	}



	private function getUpdatedContent(mixed $target, mixed $patch):mixed
	{
		if (is_array($patch) && !array_filter(array_keys($patch), fn($key) => is_int($key))) //Проверка, является ли переменная patch хэшем
		{
			if (!is_array($target) || !array_filter(array_keys($target), fn($key) => !is_int($key))) //Проверка, что переменная target не является хэшем
			{
				$target = [];
			}

			foreach ($patch as $key => $value)
			{
				if (!$value)
				{


					if (array_key_exists($key, $target))
					{

						unset($target[$key]);
					}
				}

				else
				{
					$target[$key] = $this->getUpdatedContent($target[$key], $value);
				}

			}

			return $target;
		}
		else
		{
			return $patch;
		}


	}




	private function getDocumentItem(Document $document):DocumentItem
	{
		$createAt = date("Y-m-d H:i:s", $document->getCreateAt());
		$modifyAt = date("Y-m-d H:i:s", $document->getModifyAt());

		return (new DocumentItem())
				->setDocumentKey($document->getDocumentKey())
				->setDocumentPayload($document->getDocumentPayload())
				->setDocumentStatus($document->getDocumentStatus())
				->setCreateAt($createAt)
				->setModifyAt($modifyAt);

	}


}
