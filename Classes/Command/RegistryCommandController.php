<?php
namespace fucodo\registry\Command;

/*
 * This file is part of the fucodo.registry package.
 */

use fucodo\registry\Domain\Model\RegistryEntry;
use fucodo\registry\Domain\Repository\RegistryEntryRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;

/**
 * @Flow\Scope("singleton")
 */
class RegistryCommandController extends CommandController
{
    /**
     * @Flow\Inject()
     * @var RegistryEntryRepository
     */
    protected $registry;

    /**
     * @param string|null $namespace
     * @return void
     */
    public function listCommand(?string $namespace = null)
    {
        if ($namespace !== null) {
            $entries = $this->registry->findByNamespace($namespace);
        } else {
            $entries = $this->registry->findAll();
        }

        $rows = [];
        foreach ($entries as $entry) {
            $rows[] = [
                $entry->getNamespace(),
                $entry->getName(),
                $entry->getCreatedAt()->format('Y-m-d H:i:s'),
                $entry->getUpdatedAt()->format('Y-m-d H:i:s'),
                serialize($entry->getValue()),
            ];
        }

        $this->output->outputTable(
            $rows,
            ['Namespace', 'Name', 'Created', 'Updated', 'Value']
        );
    }

    public function setCommand(string $namespace, string $name, string $value)
    {
        $newEntry = new RegistryEntry();
        $newEntry->setNamespace($namespace);
        $newEntry->setName($name);
        $newEntry->setValue($value);
        $this->registry->add($newEntry);
    }
}
