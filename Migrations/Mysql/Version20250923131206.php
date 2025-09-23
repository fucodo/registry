<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250923131206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1027Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1027Platform'."
        );

        $this->addSql('ALTER TABLE fucodo_registry_domain_model_registryentry DROP INDEX UNIQ_DD2AAC5433E16B565E237E06, ADD INDEX IDX_DD2AAC5433E16B565E237E06 (namespace, name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DD2AAC547D3656A433E16B565E237E06 ON fucodo_registry_domain_model_registryentry (account, namespace, name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1027Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1027Platform'."
        );

        $this->addSql('ALTER TABLE fucodo_registry_domain_model_registryentry DROP INDEX IDX_DD2AAC5433E16B565E237E06, ADD UNIQUE INDEX UNIQ_DD2AAC5433E16B565E237E06 (namespace, name)');
        $this->addSql('DROP INDEX UNIQ_DD2AAC547D3656A433E16B565E237E06 ON fucodo_registry_domain_model_registryentry');
    }
}
