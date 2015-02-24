<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\SearchBundle\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;

/**
 * @author Argyrios Gounaris <agounaris@gmail.com>
 */
class SearchIndexRepository extends EntityRepository
{
    /**
     * @param array $resultSetFromFulltextSearch
     *
     * @return array
     */
    public function hydrateSearchResults($resultSetFromFulltextSearch = array())
    {
        $results = array();
        foreach ($resultSetFromFulltextSearch as $model => $ids) {
            $queryBuilder = $this->em->createQueryBuilder();
            $queryBuilder
                ->select('u')
                ->from($model, 'u')
                ->where('u.id IN (:ids)')
                ->setParameter('ids', $ids)
            ;

            foreach ($queryBuilder->getQuery()->getResult() as $object) {
                $results[] = $object;
            }
        }

        return $results;
    }
}
