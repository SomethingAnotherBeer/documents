<?php

namespace App\Repository;

use App\Entity\{Document, User};
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Document>
 *
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    public function save(Document $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Document $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function getDocumentByKey(string $document_key):?Document
    {
        return $this->findOneBy(['document_key' => $document_key]);
    }


    public function getPublishedDocuments(int $offset, int $documents_count):array
    {  
        return $this->createQueryBuilder('d')
                ->setFirstResult($offset)
                ->setMaxResults($documents_count)
                ->andWhere("d.document_status = 'published' ")
                ->getQuery()
                ->getResult();
    }


    public function getUserDocuments(int $offset, int $documents_count, User $user):array
    {
        return $this->createQueryBuilder('d')
                ->setFirstResult($offset)
                ->setMaxResults($documents_count)
                ->andWhere("d.user_rel = :user")
                ->setParameter("user", $user)
                ->getQuery()
                ->getResult();
    }   


    public function getUserDocumentsByStatus(int $offset, int $documents_count, User $user, string $document_status):array
    {
        return $this->createQueryBuilder('d')
                ->setFirstResult($offset)
                ->setMaxResults($documents_count)
                ->andWhere("d.user_rel = :user")
                ->andWhere("d.document_status = :document_status")
                ->setParameter("user", $user)
                ->setParameter("document_status", $document_status)
                ->getQuery()
                ->getResult();
    }

    



//    /**
//     * @return Document[] Returns an array of Document objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Document
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
