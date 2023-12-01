<?php
namespace fucodo\registry\Domain\Repository;

/*
 * This file is part of the fucodo.registry package.
 */

use fucodo\registry\Domain\Model\RegistryEntry;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class RegistryEntryRepository extends Repository
{
    public function get(string  $namespace, string $name): ?RegistryEntry
    {
        $query = $this->createQuery();
        $result = $query->matching(
            $query->logicalAnd(
                $query->equals('namespace', $namespace),
                $query->equals('name', $name)
            )
        )->execute(false)->getFirst();
        if ($result instanceof RegistryEntry) {
            return $result;
        }
        return null;
    }

    public function add($object)
    {
        $existingObject = $this->get($object->getNamespace(), $object->getName());
        if ($existingObject !== null) {
            $existingObject->setValue($object->getValue());
            $this->update($existingObject);
            return;
        }
        parent::add($object);
    }

    public function update($object) {
        if (!$this->get($object->getNamespace(), $object->getName())) {
            parent::add($object);
        }
        parent::update($object);
    }
}
