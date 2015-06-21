<?php

namespace Clickatell\SMSOptInAndOutBundle\Keyword;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;

class KeywordManager
{
    /**
     * @var array
     */
    protected $keywordStrategies = array();

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->keywordStrategies[] = new SubscribeKeyword($em);
        $this->keywordStrategies[] = new UnSubscribeKeyword($em);
    }

    /**
     * @param array $values
     * @return bool
     */
    public function HandleMoCallback(array $values)
    {
        /** @var KeywordStrategyInterface $class */
        foreach ($this->keywordStrategies as $class)
        {
            if ($class::Supported($values))
            {
                $class->Handle($values);
                return true;
            }
        }
        return false;
    }

}