<?php

namespace CostAuthentication\Entity; 

use Doctrine\ORM\Mapping as ORM;
use Laminas\Form\Annotation as Annotation;

/**
 * Language
 *
 * @ORM\Table(name="language")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @Annotation\Name("Language")
 * @Annotation\Hydrator("Laminas\Hydrator\ClassMethods")
 * 
 */
class Language
{
    
    /**
     * @ORM\PreUpdate
     */
    public function onUpdate(\Doctrine\ORM\Event\PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();
        echo PHP_EOL . '---------------------------------------';
        echo PHP_EOL . __METHOD__;
        echo PHP_EOL . 'Update the "default_language" field';
        echo PHP_EOL . '---------------------------------------';
        $iDefault = (int) $entity->getDefault();
        if($iDefault){
            $qb = $entityManager->createQueryBuilder()
            ->update(__CLASS__, 'l')
            ->set('l.default', 0)
            ->where('l.id != ?1')
            ->setParameter(1, $entity->getId())
            ->getQuery();
            $p = $qb->execute();
        }
    }
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=15, nullable=false)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Description:"})
     * @Annotation\Filter({"name":"StringTrim"})
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="integer", length=10, nullable=false)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Description:"})
     * @Annotation\Filter({"name":"StringTrim"})
     */
    protected $code;
    
    /**
     * @var string
     *
     * @ORM\Column(name="default_language", type="integer", length=10, nullable=false)
     * @Annotation\Type("Laminas\Form\Element\Radio")
     * @Annotation\Options({"label":"Default"})
     * @Annotation\Attributes({"options":{"1":"Yes","0":"No"}})
     * @Annotation\Required(true)
     */
    protected $default;
    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string   $name
     * @return Language
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set abbreviation
     *
     * @param  string   $abbreviation
     * @return Language
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get abbreviation
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }
 
}
