<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190813142547 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE photos (id INT AUTO_INCREMENT NOT NULL, monster_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, file VARCHAR(191) NOT NULL, name VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_876E0D9C5FF1223 (monster_id), UNIQUE INDEX UQ_photos_1 (file), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9C5FF1223 FOREIGN KEY (monster_id) REFERENCES monsters (id)');
        $this->addSql('DROP TABLE photo');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, monster_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, file VARCHAR(240) NOT NULL COLLATE utf8mb4_unicode_ci, name VARCHAR(60) NOT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_14B78418C5FF1223 (monster_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418C5FF1223 FOREIGN KEY (monster_id) REFERENCES monsters (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE photos');
    }
}
