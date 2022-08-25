<?php

namespace Adeliom\EasyFieldsBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait PositionSortableTrait
{
    #[ORM\Column(name: 'lft', type: \Doctrine\DBAL\Types\Types::INTEGER)]
    #[Gedmo\TreeLeft]
    protected ?int $lft = null;

    #[ORM\Column(name: 'lvl', type: \Doctrine\DBAL\Types\Types::INTEGER)]
    #[Gedmo\TreeLevel]
    protected ?int $lvl = null;

    #[ORM\Column(name: 'rgt', type: \Doctrine\DBAL\Types\Types::INTEGER)]
    #[Gedmo\TreeRight]
    protected ?int $rgt = null;

    #[ORM\Column(name: 'root', type: \Doctrine\DBAL\Types\Types::INTEGER, nullable: true)]
    #[Gedmo\TreeRoot]
    protected ?int $root = null;


    /**
     * @return mixed
     */
    public function getLft()
    {
        return $this->lft;
    }

    public function setLft(mixed $lft): void
    {
        $this->lft = $lft;
    }

    /**
     * @return mixed
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    public function setLvl(mixed $lvl): void
    {
        $this->lvl = $lvl;
    }

    /**
     * @return mixed
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    public function setRgt(mixed $rgt): void
    {
        $this->rgt = $rgt;
    }

    /**
     * @return mixed
     */
    public function getRoot()
    {
        return $this->root;
    }

    public function setRoot(mixed $root): void
    {
        $this->root = $root;
    }

    public function getSortableData($name)
    {
        return $this->{$name};
    }
}
