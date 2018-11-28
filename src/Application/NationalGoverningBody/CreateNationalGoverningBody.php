<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 09:54
 */

namespace App\Application\NationalGoverningBody;

use Ramsey\Uuid\Uuid;

/**
 * Class CreateNationalGoverningBody
 *
 * @package App\Application\NationalGoverningBody
 */
class CreateNationalGoverningBody extends NationalGoverningBodyProperties
{
    use MutableNationalGoverningBodyCommand;

    /**
     * @var string
     */
    private $id;

    /**
     * @return self
     */
    public static function create(): self
    {
        $id = Uuid::uuid4();
        return new self($id->toString());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
