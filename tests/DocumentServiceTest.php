<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use App\Entity\{User, Document};
use App\Repository\{UserRepository, DocumentRepository};
use App\Service\DocumentService;

use App\Data\{DocumentData, DocumentItem};

use App\Exception\DocumentException\{DocumentNotFoundException, DocumentAccessDeniedException, CannotUpdatePublishedDocumentException, DocumentAlreadyPublishedException};


class DocumentServiceTest extends TestCase
{
    
    private UserRepository $userRepository;
    private DocumentRepository $documentRepository;

    public function setUp():void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->documentRepository = $this->createMock(DocumentRepository::class);
    }


    public function testDocumentCreate():void
    {
        $document_payload = '{"actor":"The fox","meta":{"type":"quick","color":"brown"},"actions":[{"action":"jump over","actor":"lazy dog"}]}';
        $user = (new User())->setLogin('John')->setPassword('111');

        //$userRepository = $this->createMock(UserRepository::class);
        //$documentRepository = $this->createMock(DocumentRepository::class);

        $documentService = new DocumentService($this->documentRepository, $this->userRepository);

        $result = $documentService->createDocument(new DocumentData(['document_payload' => $document_payload]), $user);

        $this->assertTrue($result instanceof DocumentItem);

    }



    public function testDocumentPatch():void
    {
        $target_payload = '{"actor":"The fox","meta":{"type":"quick","color":"brown"},"actions":[{"action":"jump over","actor":"lazy dog"}]}';
        $patch_payload = '{"meta":{"type":"cunning","color": null},"actions":[{"action":"eat","actor":"blob"},{"action":"run away"}]}';

        $expected_payload = '{"actor":"The fox","meta":{"type":"cunning"},"actions":[{"action":"eat","actor":"blob"},{"action":"run away"}]}';

        $user = (new User())->setLogin('John')->setPassword('111');
        $targetDocument = (new Document())->setUserRel($user)->setDocumentKey('some_key')->setDocumentStatus('draft')->setDocumentPayload($target_payload)
                            ->setCreateAt(time())->setModifyAt(time());



       // $userRepository = $this->createMock(UserRepository::class);
        //$documentRepository = $this->createMock(DocumentRepository::class);

        $this->documentRepository->expects($this->once())
                        ->method('getDocumentByKey')
                        ->willReturn((new Document())->setUserRel($user)->setDocumentKey('some_key')->setDocumentPayload($target_payload)->setDocumentStatus('draft')
                        ->setCreateAt(time())->setModifyAt(time()));




        $documentService = new DocumentService($this->documentRepository, $this->userRepository);


        $documentItem = $documentService->patchDocument(new DocumentData(['document_payload' => $patch_payload]), $user);

        $this->assertSame($expected_payload, json_encode($documentItem->getDocumentPayload()));

    }


  
    public function testDocumentPatchNotFound():void
    {


        $this->expectException(DocumentNotFoundException::class);
        $this->expectExceptionMessage("Документ не найден");

        $user = (new User())->setLogin('John')->setPassword('111');
        $patch_payload = '{"some":"111"}';

        $this->documentRepository->expects($this->once())
                                ->method('getDocumentByKey')
                                ->willReturn(null);

        $documentService = new DocumentService($this->documentRepository, $this->userRepository);

        $documentService->patchDocument(new DocumentData(['document_payload' => $patch_payload]), $user);


    }



    public function testDocumentPatchAccessDenied():void
    {
        $this->expectException(DocumentAccessDeniedException::class);
        $this->expectExceptionMessage("У вас нет прав на редактирование этого документа");


        $user = (new User())->setLogin('John')->setPassword('111');
        $patch_payload = '{"some":"111"}';

        $this->documentRepository->expects($this->once())
            ->method('getDocumentByKey')
            ->willReturn(
                (new Document())
                    ->setUserRel((new User())->setLogin('Marcus')->setPassword('222'))
                    ->setDocumentKey('some_key')
                    ->setDocumentPayload('{"some":"222"}')
                    ->setDocumentStatus('draft')
                    ->setCreateAt(time())
                    ->setModifyAt(time())
                );

        $documentService = new DocumentService($this->documentRepository, $this->userRepository);

        $documentService->patchDocument(new DocumentData(['document_payload' => $patch_payload]), $user);

    }


    public function testDocumentPatchPublishedDocument():void
    {
        $this->expectException(CannotUpdatePublishedDocumentException::class);
        $this->expectExceptionMessage("Невозможно отредактировать опубликованный документ");

        $user = (new User())->setLogin('John')->setPassword('111');
        $patch_payload = '{"some":"111"}';

        $this->documentRepository->expects($this->once())
            ->method('getDocumentByKey')
            ->willReturn(
                (new Document())
                ->setUserRel($user)
                ->setDocumentKey('some_key')
                ->setDocumentPayload('{"some":"222"}')
                ->setDocumentStatus("published")
                ->setCreateAt(time())
                ->setModifyAt(time())
            );

        $documentService = new DocumentService($this->documentRepository, $this->userRepository);

        $documentService->patchDocument(new DocumentData(['document_payload' => $patch_payload]), $user);

    }


    public function testPublishDocument():void
    {
        $user = (new User())->setLogin('John')->setPassword('111');
        $document_key = 'some_key';

        $document = (new Document())
                    ->setUserRel($user)
                    ->setDocumentKey($document_key)
                    ->setDocumentPayload('{"some":"111"}')
                    ->setCreateAt(time())
                    ->setModifyAt(time() + 3800);


        $this->documentRepository->expects($this->once())
                                ->method('getDocumentByKey')
                                ->willReturn($document);


        $documentService = new DocumentService($this->documentRepository, $this->userRepository);

        $documentItem = $documentService->publishDocument(new DocumentData(['document_key' => $document_key]), $user);

        $this->assertSame('published', $documentItem->getDocumentStatus());

    }


    public function testPublishDocumentNotFound():void
    {
        $this->expectException(DocumentNotFoundException::class);
        $this->expectExceptionMessage("Документ не найден");

        $user = (new User())->setLogin('John')->setPassword('111');
        $document_key = "some_key";

        $this->documentRepository->expects($this->once())
                                ->method('getDocumentByKey')
                                ->willReturn(null);

        $documentService = new DocumentService($this->documentRepository, $this->userRepository);

        $documentService->publishDocument(new DocumentData(['document_key' => $document_key]), $user);

    }


    public function testPublishDocumentAccessDenied():void
    {
        $this->expectException(DocumentAccessDeniedException::class);
        $this->expectExceptionMessage("У вас нет прав доступа к этому документу");

        $user = (new User())->setLogin('John')->setPassword('111');
        $document_key = 'some_key';

        $this->documentRepository->expects($this->once())
                                ->method('getDocumentByKey')
                                ->willReturn(
                                    (new Document())
                                        ->setUserRel((new User())->setLogin('Marcus')->setPassword('111'))
                                        ->setDocumentPayload('{"some":"111"}')
                                        ->setDocumentStatus('draft')
                                        ->setCreateAt(time())
                                        ->setModifyAt(time() + 3800)
                                );

        $documentService = new DocumentService($this->documentRepository, $this->userRepository);

        $documentService->publishDocument(new DocumentData(['document_key' => $document_key]), $user);


    }


    public function testPublishDocumentAlreadyPublished():void
    {
        $this->expectException(DocumentAlreadyPublishedException::class);
        $this->expectExceptionMessage("Данный документ уже опубликован");

        $user = (new User())->setLogin('John')->setPassword('111');
        $document_key = 'some_key';

        $this->documentRepository->expects($this->once())
                                ->method('getDocumentByKey')
                                ->willReturn(
                                    (new Document())
                                        ->setUserRel($user)
                                        ->setDocumentKey($document_key)
                                        ->setDocumentPayload('{"some":"111"}')
                                        ->setDocumentStatus('published')
                                        ->setCreateAt(time())
                                        ->setModifyAt(time() + 3800)
                                );

        $documentService = new DocumentService($this->documentRepository, $this->userRepository);

        $documentService->publishDocument(new DocumentData(['document_key' => $document_key]), $user);

    }



    public function testDeleteDocument():void
    {
        $user = (new User())->setLogin('John')->setPassword('111');
        $document_key = 'some_key';

        $document = (new Document())
                    ->setUserRel($user)
                    ->setDocumentKey($document_key)
                    ->setDocumentStatus('draft')
                    ->setDocumentPayload('{"some": "111"}')
                    ->setCreateAt(time())
                    ->setModifyAt(time());


        $this->documentRepository->expects($this->once())
                                ->method('getDocumentByKey')
                                ->willReturn($document);

        $documentService = new DocumentService($this->documentRepository, $this->userRepository);

        $documentItem = $documentService->deleteDocument(new DocumentData(['document_key' => $document_key]), $user);

        $this->assertTrue($documentItem instanceof DocumentItem);

    }


    public function testDeleteDocumentNotFound():void
    {
        $this->expectException(DocumentNotFoundException::class);
        $this->expectExceptionMessage("Документ не найден");

        $user = (new User())->setLogin('John')->setPassword('111');
        $document_key = 'some_key';

        $this->documentRepository->expects($this->once())
                                ->method('getDocumentByKey')
                                ->willReturn(null);

        $documentService = new DocumentService($this->documentRepository, $this->userRepository);

        $documentService->deleteDocument(new DocumentData(['document_key' => $document_key]), $user);

    }


    public function testDeleteDocumentAccessDenied():void
    {
        $this->expectException(DocumentAccessDeniedException::class);
        $this->expectExceptionMessage("у вас нет прав доступа к этому документу");

        $document_key = 'some_key';

        $currentUser = (new User())->setLogin('John')->setPassword('111');
        $documentOwner = (new User())->setLogin('Marcus')->setPassword('222');
        $document = (new Document())
                            ->setUserRel($documentOwner)
                            ->setDocumentKey($document_key)
                            ->setDocumentStatus('draft')
                            ->setDocumentPayload('{"some": "111"}')
                            ->setCreateAt(time())
                            ->setModifyAt(time());


        $this->documentRepository->expects($this->once())
                                ->method('getDocumentByKey')
                                ->willReturn($document);

        $documentService = new DocumentService($this->documentRepository, $this->userRepository);

        $documentService->deleteDocument(new DocumentData(['document_key' => $document_key]), $currentUser);


    }


}
