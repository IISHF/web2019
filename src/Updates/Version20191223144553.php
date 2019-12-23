<?php

declare(strict_types=1);

namespace DbUpdates;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191223144553 extends AbstractMigration
{
    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return 'Creates the initial database tables';
    }

    /**
     * {@inheritDoc}
     */
    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE files (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', binary_hash CHAR(40) NOT NULL, name VARCHAR(64) NOT NULL, original_name VARCHAR(128) DEFAULT NULL, size INT UNSIGNED NOT NULL, mime_type VARCHAR(64) NOT NULL, origin VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, INDEX IDX_6354059DB483309 (binary_hash), INDEX idx_file_origin (origin), UNIQUE INDEX uniq_file_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE file_binaries (hash CHAR(40) NOT NULL, data LONGBLOB NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, PRIMARY KEY(hash)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE users (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', first_name VARCHAR(128) NOT NULL, last_name VARCHAR(128) NOT NULL, email VARCHAR(128) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, password_changed DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', confirm_token VARCHAR(64) DEFAULT NULL, reset_password_token VARCHAR(64) DEFAULT NULL, reset_password_until DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_login DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_login_ip VARCHAR(64) DEFAULT NULL, last_login_user_agent VARCHAR(255) DEFAULT NULL, last_logout DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_login_failure DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_login_failure_ip VARCHAR(64) DEFAULT NULL, last_login_failure_user_agent VARCHAR(255) DEFAULT NULL, login_failures SMALLINT UNSIGNED NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, UNIQUE INDEX uniq_user_email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE committees (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(128) NOT NULL, slug VARCHAR(128) NOT NULL, description TEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, UNIQUE INDEX uniq_committee_title (title), UNIQUE INDEX uniq_committee_slug (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE committee_members (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', committee_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', first_name VARCHAR(128) NOT NULL, last_name VARCHAR(128) NOT NULL, country VARCHAR(2) NOT NULL, title VARCHAR(128) DEFAULT NULL, term_type SMALLINT UNSIGNED NOT NULL, term_since SMALLINT UNSIGNED DEFAULT NULL, term_duration SMALLINT UNSIGNED DEFAULT NULL, member_type SMALLINT UNSIGNED NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, INDEX IDX_4E0D736FED1A100B (committee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE national_governing_bodies (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(64) NOT NULL, acronym VARCHAR(16) NOT NULL, slug VARCHAR(128) NOT NULL, ioc_code VARCHAR(3) NOT NULL, country VARCHAR(2) NOT NULL, website VARCHAR(128) DEFAULT NULL, facebook_profile VARCHAR(128) DEFAULT NULL, twitter_profile VARCHAR(128) DEFAULT NULL, instagram_profile VARCHAR(128) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, email VARCHAR(128) NOT NULL, phone_number VARCHAR(35) DEFAULT NULL COMMENT \'(DC2Type:phone_number)\', UNIQUE INDEX uniq_ngb_name (name), UNIQUE INDEX uniq_ngb_email (email), UNIQUE INDEX uniq_ngb_acronym (acronym), UNIQUE INDEX uniq_ngb_slug (slug), UNIQUE INDEX uniq_ngb_ioc_code (ioc_code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE hall_of_fame (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', season SMALLINT UNSIGNED NOT NULL, age_group VARCHAR(16) NOT NULL, event VARCHAR(128) NOT NULL, event_date VARCHAR(64) DEFAULT NULL, championship TINYINT(1) NOT NULL, winner_club VARCHAR(128) NOT NULL, winner_country VARCHAR(2) NOT NULL, host_club VARCHAR(128) DEFAULT NULL, host_country VARCHAR(2) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, INDEX idx_season_age_group (season, championship, age_group), INDEX idx_age_group_season (championship, age_group, season), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE documents (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(128) NOT NULL, slug VARCHAR(128) NOT NULL, tags JSON NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, UNIQUE INDEX uniq_document_title (title), UNIQUE INDEX uniq_document_slug (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE document_versions (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', document_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', file_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', version VARCHAR(128) NOT NULL, slug VARCHAR(128) NOT NULL, valid_from DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', valid_until DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, INDEX IDX_961DB18BC33F7837 (document_id), UNIQUE INDEX UNIQ_961DB18B93CB796C (file_id), UNIQUE INDEX uniq_document_version (document_id, version), UNIQUE INDEX uniq_document_slug (document_id, slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE staff_members (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', first_name VARCHAR(128) NOT NULL, last_name VARCHAR(128) NOT NULL, email VARCHAR(128) NOT NULL, title VARCHAR(128) NOT NULL, roles JSON NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE article_attachments (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', article_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', file_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', type VARCHAR(16) NOT NULL, primary_image TINYINT(1) DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, INDEX IDX_DD4790B17294869C (article_id), INDEX IDX_DD4790B193CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE article_versions (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX idx_article_version_class (object_class), INDEX idx_article_version_date (logged_at), INDEX idx_article_version_user (username), INDEX idx_article_version_version (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE articles (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', legacy_format TINYINT(1) NOT NULL, current_state VARCHAR(16) NOT NULL, slug VARCHAR(128) NOT NULL, title VARCHAR(128) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, body MEDIUMTEXT NOT NULL, tags JSON NOT NULL, author VARCHAR(128) NOT NULL, published_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX idx_article_state_date (current_state, published_at), UNIQUE INDEX uniq_article_slug (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE events (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', host_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', venue_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(64) NOT NULL, slug VARCHAR(128) NOT NULL, season SMALLINT UNSIGNED NOT NULL, age_group VARCHAR(16) NOT NULL, current_state VARCHAR(16) NOT NULL, sanction_number VARCHAR(16) DEFAULT NULL, start_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_date_utc DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_date_utc DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', time_zone VARCHAR(32) DEFAULT NULL, tags JSON NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, level VARCHAR(2) NOT NULL, planned_length SMALLINT UNSIGNED DEFAULT NULL, description TEXT DEFAULT NULL, planned_teams SMALLINT UNSIGNED DEFAULT NULL, INDEX IDX_5387574A1FB8D185 (host_id), INDEX IDX_5387574A40A73EBA (venue_id), INDEX idx_event_season (season, level), UNIQUE INDEX uniq_event_name (season, name), UNIQUE INDEX uniq_event_slug (season, slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE event_games (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', event_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', home_team_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', away_team_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', game_number SMALLINT UNSIGNED NOT NULL, game_type SMALLINT UNSIGNED NOT NULL, date_time_local DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', time_zone VARCHAR(32) NOT NULL, date_time_utc DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', home_team_provisional VARCHAR(64) DEFAULT NULL, away_team_provisional VARCHAR(64) DEFAULT NULL, remarks VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, result_home_goals SMALLINT UNSIGNED DEFAULT NULL, result_away_goals SMALLINT UNSIGNED DEFAULT NULL, INDEX IDX_BE389A1D71F7E88B (event_id), INDEX IDX_BE389A1D9C4C13F6 (home_team_id), INDEX IDX_BE389A1D45185D02 (away_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE event_teams (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', event_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', contact_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, INDEX IDX_D7D9937471F7E88B (event_id), UNIQUE INDEX UNIQ_D7D99374E7A1254A (contact_id), UNIQUE INDEX uniq_event_team (event_id, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE event_team_contacts (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(128) NOT NULL, email VARCHAR(128) NOT NULL, phone_number VARCHAR(35) DEFAULT NULL COMMENT \'(DC2Type:phone_number)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE event_venues (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(64) NOT NULL, rink_info TEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, address_address1 VARCHAR(128) DEFAULT NULL, address_address2 VARCHAR(128) DEFAULT NULL, address_state VARCHAR(64) DEFAULT NULL, address_postal_code VARCHAR(32) DEFAULT NULL, address_city VARCHAR(128) NOT NULL, address_country VARCHAR(2) NOT NULL, UNIQUE INDEX uniq_venue_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE event_applications (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', event_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', venue_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', applicant_club VARCHAR(128) NOT NULL, proposed_start_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', proposed_end_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', time_zone VARCHAR(32) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, contact_name VARCHAR(128) NOT NULL, contact_email VARCHAR(128) NOT NULL, contact_phone_number VARCHAR(35) DEFAULT NULL COMMENT \'(DC2Type:phone_number)\', INDEX IDX_1ADA6C0371F7E88B (event_id), INDEX IDX_1ADA6C0340A73EBA (venue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE event_hosts (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(128) NOT NULL, email VARCHAR(128) NOT NULL, phone_number VARCHAR(35) DEFAULT NULL COMMENT \'(DC2Type:phone_number)\', club VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_by VARCHAR(128) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE files ADD CONSTRAINT FK_6354059DB483309 FOREIGN KEY (binary_hash) REFERENCES file_binaries (hash)'
        );
        $this->addSql(
            'ALTER TABLE committee_members ADD CONSTRAINT FK_4E0D736FED1A100B FOREIGN KEY (committee_id) REFERENCES committees (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE document_versions ADD CONSTRAINT FK_961DB18BC33F7837 FOREIGN KEY (document_id) REFERENCES documents (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE document_versions ADD CONSTRAINT FK_961DB18B93CB796C FOREIGN KEY (file_id) REFERENCES files (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE article_attachments ADD CONSTRAINT FK_DD4790B17294869C FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE article_attachments ADD CONSTRAINT FK_DD4790B193CB796C FOREIGN KEY (file_id) REFERENCES files (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE events ADD CONSTRAINT FK_5387574A1FB8D185 FOREIGN KEY (host_id) REFERENCES event_hosts (id)'
        );
        $this->addSql(
            'ALTER TABLE events ADD CONSTRAINT FK_5387574A40A73EBA FOREIGN KEY (venue_id) REFERENCES event_venues (id)'
        );
        $this->addSql(
            'ALTER TABLE event_games ADD CONSTRAINT FK_BE389A1D71F7E88B FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE event_games ADD CONSTRAINT FK_BE389A1D9C4C13F6 FOREIGN KEY (home_team_id) REFERENCES event_teams (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE event_games ADD CONSTRAINT FK_BE389A1D45185D02 FOREIGN KEY (away_team_id) REFERENCES event_teams (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE event_teams ADD CONSTRAINT FK_D7D9937471F7E88B FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE event_teams ADD CONSTRAINT FK_D7D99374E7A1254A FOREIGN KEY (contact_id) REFERENCES event_team_contacts (id)'
        );
        $this->addSql(
            'ALTER TABLE event_applications ADD CONSTRAINT FK_1ADA6C0371F7E88B FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE event_applications ADD CONSTRAINT FK_1ADA6C0340A73EBA FOREIGN KEY (venue_id) REFERENCES event_venues (id)'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE article_attachments DROP FOREIGN KEY FK_DD4790B17294869C');
        $this->addSql('ALTER TABLE article_attachments DROP FOREIGN KEY FK_DD4790B193CB796C');
        $this->addSql('ALTER TABLE committee_members DROP FOREIGN KEY FK_4E0D736FED1A100B');
        $this->addSql('ALTER TABLE document_versions DROP FOREIGN KEY FK_961DB18B93CB796C');
        $this->addSql('ALTER TABLE document_versions DROP FOREIGN KEY FK_961DB18BC33F7837');
        $this->addSql('ALTER TABLE event_applications DROP FOREIGN KEY FK_1ADA6C0340A73EBA');
        $this->addSql('ALTER TABLE event_applications DROP FOREIGN KEY FK_1ADA6C0371F7E88B');
        $this->addSql('ALTER TABLE event_games DROP FOREIGN KEY FK_BE389A1D45185D02');
        $this->addSql('ALTER TABLE event_games DROP FOREIGN KEY FK_BE389A1D71F7E88B');
        $this->addSql('ALTER TABLE event_games DROP FOREIGN KEY FK_BE389A1D9C4C13F6');
        $this->addSql('ALTER TABLE event_teams DROP FOREIGN KEY FK_D7D9937471F7E88B');
        $this->addSql('ALTER TABLE event_teams DROP FOREIGN KEY FK_D7D99374E7A1254A');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A1FB8D185');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A40A73EBA');
        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_6354059DB483309');
        $this->addSql('DROP TABLE article_attachments');
        $this->addSql('DROP TABLE article_versions');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE committee_members');
        $this->addSql('DROP TABLE committees');
        $this->addSql('DROP TABLE document_versions');
        $this->addSql('DROP TABLE documents');
        $this->addSql('DROP TABLE event_applications');
        $this->addSql('DROP TABLE event_games');
        $this->addSql('DROP TABLE event_hosts');
        $this->addSql('DROP TABLE event_team_contacts');
        $this->addSql('DROP TABLE event_teams');
        $this->addSql('DROP TABLE event_venues');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE file_binaries');
        $this->addSql('DROP TABLE files');
        $this->addSql('DROP TABLE hall_of_fame');
        $this->addSql('DROP TABLE national_governing_bodies');
        $this->addSql('DROP TABLE staff_members');
        $this->addSql('DROP TABLE users');
    }
}
