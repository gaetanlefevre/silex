<?php

namespace Johndodev\ORM;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntityConverter
{
    /**
     * @var EntityRepository
     */
    protected $repository;

    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function convertId($id)
    {
        $entity = $this->repository->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Entity not found.');
        }

        return $entity;
    }

    public function convertSlug($slug)
    {
        $entity = $this->repository->findOneBy(['slug' => $slug]);

        if (!$entity) {
            throw new NotFoundHttpException('Entity not found.');
        }

        return $entity;
    }
}
