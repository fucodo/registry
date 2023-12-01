<?php
namespace fucodo\registry\Domain\Repository;

/*
 * This file is part of the fucodo.registry package.
 */

use fucodo\registry\Domain\Model\RegistryEntry;
use Monolog\Registry;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class RegistryEntryRepository extends Repository
{
    public function get(string  $namespace, string $name, $fallback = null): ?RegistryEntry
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

        if ($fallback === null) {
            return null;
        }
        $registryEntry = new RegistryEntry();
        $registryEntry->setValue($fallback);
        $registryEntry->setName($name);
        $registryEntry->setNamespace($namespace);

        return $registryEntry;
    }

    /**
     * @param string $namespace
     * @param string $name
     * @param mixed $fallback
     * @return object|void
     */
    public function getValue(string $namespace, string $name, $fallback = null)
    {
        $returnValue = $this->get($namespace, $name, $fallback);
        if ($returnValue instanceof RegistryEntry) {
            return $returnValue->getValue();
        }
        return $fallback;
    }

    public function set(string  $namespace, string $name, $value)
    {

        $registryEntry = $this->get(
            $namespace,
            $name,
            (new RegistryEntry())->setName($name)->setNamespace($namespace)
        );
        $registryEntry->setValue($value);
        $this->update($registryEntry);
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
