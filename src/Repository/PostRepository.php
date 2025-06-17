<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Template;
use App\Entity\TemplateField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function searchByTemplateFields(Template $template, array $queryParams = []): array
    {
        $query = $this->createQueryBuilder('p')
            ->setParameter('template', $template)
            ->andWhere('p.template = :template')
            ->join('p.postValues', 'pv')
            ->join('pv.templateField', 'tf');

        // Fetch available fields for provided template to avoid some issues
        $availableFields = array_map(fn(TemplateField $el) => $el->getSystemName(), $template->getTemplateFields()->toArray());

        // Handle template dynamic fields
        foreach ($queryParams as $key => $value) {
            $value = trim($value);

            // Remove min/max prefix to have raw systemName
            $systemName = str_replace('min_', '', $key);
            $systemName = str_replace('max_', '', $systemName);

            if (empty($value) || !in_array($systemName, $availableFields)) {
                continue;
            }

            if (str_starts_with($key, 'min_')) {
                $systemName = str_replace('min_', '', $key);
                $query->andWhere("pv.value >= :$key")
                    ->setParameter($key, $value);
            } elseif (str_starts_with($key, 'max_')) {
                $systemName = str_replace('max_', '', $key);
                $query->andWhere("pv.value <= :$key")
                    ->setParameter($key, $value);
            } else {
                $query->andWhere("pv.value LIKE :$key")
                    ->setParameter($key, "%$value%");
            }

            $query->andWhere('tf.systemName = :systemName')
                ->setParameter('systemName', $systemName);
        }

        return $query->getQuery()->getResult();
    }
}
