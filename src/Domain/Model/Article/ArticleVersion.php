<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 10:27
 */

namespace App\Domain\Model\Article;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;

/**
 * Class ArticleVersion
 *
 * @package App\Domain\Model\Article
 *
 * @ORM\Entity(repositoryClass="ArticleVersionRepository")
 * @ORM\Table(
 *      name="article_versions",
 *      indexes={
 *          @ORM\Index(name="idx_article_version_class", columns={"object_class"}),
 *          @ORM\Index(name="idx_article_version_date", columns={"logged_at"}),
 *          @ORM\Index(name="idx_article_version_user", columns={"username"}),
 *          @ORM\Index(name="idx_article_version_version", columns={"object_id", "object_class", "version"})
 *      }
 *  )
 */
class ArticleVersion extends AbstractLogEntry
{

}
