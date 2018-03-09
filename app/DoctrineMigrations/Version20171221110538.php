<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171221110538 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F7597D3FE');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E7597D3FE');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP INDEX IDX_B6BD307F7597D3FE ON message');
        $this->addSql('ALTER TABLE message CHANGE member_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FA76ED395 ON message (user_id)');
        $this->addSql('DROP INDEX IDX_D8F0A91E7597D3FE ON trick');
        $this->addSql('ALTER TABLE trick CHANGE member_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91EA76ED395 ON trick (user_id)');
        $this->addSql('ALTER TABLE user ADD avatar VARCHAR(255) NOT NULL, ADD picture VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, login VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, description LONGTEXT NOT NULL COLLATE utf8_unicode_ci, picture VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('DROP INDEX IDX_B6BD307FA76ED395 ON message');
        $this->addSql('ALTER TABLE message CHANGE user_id member_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F7597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F7597D3FE ON message (member_id)');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EA76ED395');
        $this->addSql('DROP INDEX IDX_D8F0A91EA76ED395 ON trick');
        $this->addSql('ALTER TABLE trick CHANGE user_id member_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E7597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91E7597D3FE ON trick (member_id)');
        $this->addSql('ALTER TABLE user DROP avatar, DROP picture');
    }
}
