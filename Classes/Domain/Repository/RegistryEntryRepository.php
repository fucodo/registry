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
    /**
     * @Flow\Inject
     * @var \Neos\Flow\Security\Context
     */
    protected $securityContext;

    /**
     * @Flow\InjectConfiguration(package="fucodo.registry", path="defaults")
     * @var array
     */
    protected array $defaults = [];

    /**
     * @Flow\InjectConfiguration(package="fucodo.registry", path="defaultsForAccounts")
     * @var array
     */
    protected array $defaultsForAccounts = [];

    public function get(string  $namespace, string $name, $fallback = null): ?RegistryEntry
    {
        return $this->getForAccount(null, $namespace, $name, $fallback);
    }

    public function getForCurrentAccount(string  $namespace, string $name, $fallback = null): ?RegistryEntry
    {
        $account = $this->securityContext->getAccount();
        if ($account === null) {
            if ($fallback instanceof RegistryEntry) {
                return $fallback;
            }
            $registryEntry = new RegistryEntry();
            $registryEntry->setValue($fallback);
            $registryEntry->setName($name);
            $registryEntry->setNamespace($namespace);
            $registryEntry->setAccount($account);
            return $registryEntry;
        }
        $entry = $this->getForAccount($account->getAccountIdentifier(), $namespace, $name, $fallback);
        if ($entry instanceof RegistryEntry) {
            return $entry;
        }
        return null;

    }

    public function getForAccount(?string $account, string $namespace, string $name, $fallback = null): ?RegistryEntry
    {
        $query = $this->createQuery();
        $result = $query->matching(
            $query->logicalAnd(
                $query->equals('account', $account),
                $query->equals('namespace', $namespace),
                $query->equals('name', $name)
            )
        )->execute(false)->getFirst();
        if ($result instanceof RegistryEntry) {
            return $result;
        }

        if ($fallback === null) {
            return $this->getDefaultFromSettings($account, $namespace, $name);
        }
        $registryEntry = new RegistryEntry();
        $registryEntry->setValue($fallback);
        $registryEntry->setName($name);
        $registryEntry->setNamespace($namespace);
        $registryEntry->setAccount($account);

        return $registryEntry;
    }

    public function getDefaultFromSettings(?string $account, string $namespace, string $name): ?RegistryEntry
    {
        $source = $this->defaultsForAccounts;
        if ($account === null) {
            $source = $this->defaults;
        }

        if (isset($source[$namespace][$name])) {
            $r = new RegistryEntry();
            $r->setName($name)
                ->setNamespace($namespace)
                ->setValue($source[$namespace][$name]['default']);
            return $r;
        }
        return null;
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

    /**
     * @param string $namespace
     * @param string $name
     * @param mixed $fallback
     * @return object|void
     */
    public function getValueForCurrentAccount(string $namespace, string $name, $fallback = null)
    {
        $returnValue = $this->getForCurrentAccount($namespace, $name, $fallback);
        if ($returnValue instanceof RegistryEntry) {
            return $returnValue->getValue();
        }
        return $fallback;
    }

    public function set(string  $namespace, string $name, $value): void
    {

        $registryEntry = $this->get(
            $namespace,
            $name,
            (new RegistryEntry())->setName($name)->setNamespace($namespace)
        );
        $registryEntry->setValue($value);
        $this->add($registryEntry);
    }

    public function setForCurrentAccount(string  $namespace, string $name, $value): void
    {
        $account = $this->securityContext->getAccount();
        if ($account === null) {
            return;
        }

        $registryEntry = $this->getForCurrentAccount(
            $namespace,
            $name,
            (new RegistryEntry())->setName($name)->setNamespace($namespace)->setAccount($account->getAccountIdentifier())
        );

        $registryEntry->setValue($value);

        if($this->persistenceManager->isNewObject($registryEntry)) {
            $this->add($registryEntry);
        } else {
            $this->update($registryEntry);
        }
    }

    public function add($object): void
    {
        if ($object instanceof RegistryEntry === false) {
            throw new \InvalidArgumentException('The given object is not a RegistryEntry', 1618181818);
        }

        // for account based entries
        if ($object->getAccount() !== null) {
            $existingObject = $this->getForAccount($object->getAccount(), $object->getNamespace(), $object->getName());
            if ($existingObject !== null) {
                $existingObject->setValue($object->getValue());
                $this->save($existingObject);
                return;
            }
        }

        // for global entries
        $existingObject = $this->get($object->getNamespace(), $object->getName());
        if ($existingObject !== null) {
            $existingObject->setValue($object->getValue());
            $this->save($existingObject);
            return;
        }

        $this->save($object);
    }

    protected function save(RegistryEntry $object)
    {
        if ($this->persistenceManager->isNewObject($object)) {
            $this->persistenceManager->allowObject($object);
            parent::add($object);
        } else {
            $this->persistenceManager->allowObject($object);
            parent::update($object);
        }
    }

    public function update($object): void
    {
        if ($object instanceof RegistryEntry === false) {
            throw new \InvalidArgumentException('The given object is not a RegistryEntry', 1618181818);
        }
        if (!$this->getForAccount($object->getAccount(), $object->getNamespace(), $object->getName())) {
            $this->persistenceManager->allowObject($object);
            parent::add($object);
        }
        $this->persistenceManager->allowObject($object);
        parent::update($object);
    }
}
