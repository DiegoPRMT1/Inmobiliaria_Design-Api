<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605111707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property ADD real_state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEF2BD545C FOREIGN KEY (real_state_id) REFERENCES real_state (id)');
        $this->addSql('CREATE INDEX IDX_8BF21CDEF2BD545C ON property (real_state_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDEF2BD545C');
        $this->addSql('DROP INDEX IDX_8BF21CDEF2BD545C ON property');
        $this->addSql('ALTER TABLE property DROP real_state_id');
    }
}
