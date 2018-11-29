<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 17:16
 */

namespace App\Infrastructure\NationalGoverningBody\ViewModel;

use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;

/**
 * Class ListItem
 *
 * @package App\Infrastructure\NationalGoverningBody\ViewModel
 */
class ListItem implements \JsonSerializable
{
    /**
     * @var NationalGoverningBody
     */
    private $ngb;

    /**
     * @param NationalGoverningBody $ngb
     * @return self
     */
    public static function wrap(NationalGoverningBody $ngb): self
    {
        return new self($ngb);
    }

    /**
     * @param NationalGoverningBody $ngb
     */
    private function __construct(NationalGoverningBody $ngb)
    {
        $this->ngb = $ngb;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'id'           => $this->ngb->getId(),
            'name'         => $this->ngb->getName(),
            'acronym'      => $this->ngb->getAcronym(),
            'country'      => $this->ngb->getCountry(),
            'country_name' => $this->ngb->getCountryName(),
            'ioc_code'     => $this->ngb->getIocCode(),
        ];
    }
}
