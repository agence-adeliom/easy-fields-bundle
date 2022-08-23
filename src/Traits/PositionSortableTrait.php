<?php

namespace Adeliom\EasyFieldsBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


trait PositionSortableTrait
{

    /**
     * @Gedmo\TreeLeft
     */
    #[ORM\Column(name: 'lft', type: 'integer')]
    protected $lft;

    /**
     * @Gedmo\TreeLevel
     */
    #[ORM\Column(name: 'lvl', type: 'integer')]
    protected $lvl;

    /**
     * @Gedmo\TreeRight
     */
    #[ORM\Column(name: 'rgt', type: 'integer')]
    protected $rgt;

    /**
     * @Gedmo\TreeRoot
     */
    #[ORM\Column(name: 'root', type: 'integer', nullable: true)]
    protected $root;


    /**
     * @return mixed
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * @param mixed $lft
     */
    public function setLft($lft): void
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

    /**
     * @param mixed $lvl
     */
    public function setLvl($lvl): void
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

    /**
     * @param mixed $rgt
     */
    public function setRgt($rgt): void
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

    /**
     * @param mixed $root
     */
    public function setRoot($root): void
    {
        $this->root = $root;
    }

    public function getSortableData($name)
    {
        return $this->{$name};
    }
}
