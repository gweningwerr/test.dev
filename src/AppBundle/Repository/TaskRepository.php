<?php

namespace AppBundle\Repository;

use Doctrine\ORM\NoResultException;

/**
 * TaskRepository
 */
class TaskRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param array $params
     * @return array|null
     */
    public function getTasks($params = [])
    {
        $q = $this->createQueryBuilder('t');

        $q->select('t')
            ->innerJoin('t.status', 's')
            ->innerJoin('t.author', 'a')
            ->innerJoin('t.performer', 'p')
            ->orderBy('t.dateCreate','DESC');

        if (!empty($params['performer'])) {
            $q->andWhere('p.id = :user')
                ->setParameter('user', $params['performer']);
        }

        if (!empty($params['author'])) {
            $q->andWhere('a.id = :user')
                ->setParameter('user', $params['author']);
        }

        if (!empty($params['status'])) {
            $q->andWhere('s.id = :status')
                ->setParameter('status', $params['status']);
        }

        $r = $q->getQuery()
            ->useQueryCache(true)
        ;

        try {
            return $r->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public function getTask($id)
    {
        $q = $this->createQueryBuilder('t');

        $q->select('t')
            ->innerJoin('t.status', 's')
            ->innerJoin('t.author', 'a')
            ->innerJoin('t.performer', 'p')
            ->andWhere('t.id = :id')
            ->setParameter('id', $id);

        $r = $q->getQuery()
            ->useQueryCache(true)
        ;

        try {
            return $r->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}
