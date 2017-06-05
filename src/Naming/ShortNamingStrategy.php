<?php
namespace GoetasWebservices\Xsd\XsdToPhp\Naming;

use Doctrine\Common\Inflector\Inflector;
use GoetasWebservices\XML\XSDReader\Schema\Attribute\Attribute;
use GoetasWebservices\XML\XSDReader\Schema\Element\Element;
use GoetasWebservices\XML\XSDReader\Schema\Item;
use GoetasWebservices\XML\XSDReader\Schema\Type\Type;

class ShortNamingStrategy implements NamingStrategy
{
    protected $reservedWords = [
        'int',
        'float',
        'bool',
        'string',
        'true',
        'false',
        'null',
        'resource',
        'object',
        'mixed',
        'numeric',
    ];

    public function getTypeName(Type $type)
    {
        $name = $this->classify($type->getName());
        if ($name && substr($name, -4) !== 'Type') {
            $name .= "Type";
        }
        return $name;
    }

    public function getAnonymousTypeName(Type $type, $parentName)
    {
        return $this->classify($parentName) . "AType";
    }

    public function getPropertyName($item)
    {
        if($item instanceof Attribute){
            return str_replace(".", " ", $item->getName()) . "AttrName";
        } elseif ($item instanceof Element){
            return str_replace(".", " ", $item->getName()) . "ElName";
        }
        return str_replace(".", " ", $item->getName()) . "PropName";
    }

    public function getItemName(Item $item)
    {
        $name = $this->classify($item->getName());
        if (in_array(strtolower($name), $this->reservedWords)) {
            $name .= 'Xsd';
        }
        return $name;
    }

    private function classify($name)
    {
        return Inflector::classify(str_replace(".", " ", $name));
    }
}
