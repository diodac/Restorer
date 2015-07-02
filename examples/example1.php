<?php
require __DIR__ . '/../vendor/autoload.php';

use Diodac\Restorer\ObjectRestorer;
use Diodac\Restorer\Property\Object;
use Diodac\Restorer\Property\ValueObjectCollection;
use Diodac\Restorer\SerializeStrategy\MultiKey;
use Diodac\Restorer\SerializeStrategy\SingleKey;
use Diodac\Restorer\Property\ValueObject;
use Diodac\Restorer\Property\Value;

class Entity
{
    private $id;
    private $name;
    private $categories;
    private $cost;
    private $secondHalf;
    private $extraElements;

    public function __construct($name, $cost)
    {
        $this->name = $name;
        $this->cost = $cost;
        $this->categories = [];
        $this->extraElements = [];
    }

    public function addCategory(Category $category)
    {
        $this->categories[$category->getValue()] = $category;
    }

    /**
     * @param Category|int $category
     */
    public function removeCategory($category)
    {
        if (!is_numeric($category)) {
            $category = $category->getValue();
        }
        if (isset($this->categories[$category])) {
            unset($this->categories[$category]);
        }
    }

    public function setSecondHalf(Entity $entity)
    {
        $this->secondHalf = $entity;
    }

    public function addElement(Entity $entity)
    {
        $this->extraElements[] = $entity;
    }

    /**
     * @return array
     */
    public function getElements()
    {
        return $this->extraElements;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}

class Currency
{
    private $symbol;

    public  function __construct($symbol) {
        $this->symbol = $symbol;
    }

    public function getValue()
    {
        return $this->symbol;
    }

    function __toString()
    {
        return $this->symbol;
    }
}

class Money
{
    private $amount;
    private $currency;

    public function __construct($amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function asArray()
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency->getValue(),
        ];
    }
}

class Category
{
    const CATEGORY_1 = 1;
    const CATEGORY_2 = 2;
    const CATEGORY_3 = 3;

    private $category;
    private $names = [
        1 => 'CATEGORY_1',
        'CATEGORY_2',
        'CATEGORY_3',
    ];

    public function __construct($category)
    {
        if (!isset($this->names[$category])) {
            throw new InvalidArgumentException;
        }
        $this->category = $category;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->category;
    }

    function __toString()
    {
        return $this->names[$this->category];
    }
}

class MoneyConstructor implements \Diodac\Restorer\Property\ValueObjectConstructor
{
    public function construct($data)
    {
        return new Money($data['amount'], new Currency($data['currency']));
    }
}

class MoneySerializator implements \Diodac\Restorer\Property\ValueObjectSerializator
{
    /**
     * @param Money $obj
     * @return array
     */
    public function serialize($obj)
    {
        return $obj->asArray();
    }
}

class CategoryConstructor implements \Diodac\Restorer\Property\ValueObjectConstructor
{
    public function construct($data)
    {
        return new Category($data['category']);
    }
}

class InCollectionCategorySerializator implements \Diodac\Restorer\Property\ValueObjectSerializator
{
    /**
     * @param Category $obj
     * @return int
     */
    public function serialize($obj)
    {
        return [
            'category' => $obj->getValue(),
        ];
    }
}

$restorer = new ObjectRestorer('Entity', [
    new Value('id', new SingleKey('id')),
    new Value('name', new SingleKey('name')),
    new ValueObject('cost', new MoneyConstructor(), new MoneySerializator(), new MultiKey(['cost_amount' => 'amount', 'cost_currency' => 'currency'])),
    new ValueObjectCollection('categories', [
        'category' => ['Category', new CategoryConstructor(), new InCollectionCategorySerializator()],
    ], new SingleKey('categories')),
    new Object('secondHalf', 'Entity', [
            new Value('id', new SingleKey('id')),
            new Value('name', new SingleKey('name')),
            new ValueObject('cost', new MoneyConstructor(), new MoneySerializator(), new MultiKey(['cost_amount' => 'amount', 'cost_currency' => 'currency'])),
            new ValueObjectCollection('categories', [
                'category' => ['Category', new CategoryConstructor(), new InCollectionCategorySerializator()],
            ], new SingleKey('categories')),
        ], new SingleKey('second_half')),
    new \Diodac\Restorer\Property\ObjectCollection('extraElements', [
        'entity' => new ObjectRestorer('Entity', [
                new Value('id', new SingleKey('id')),
                new Value('name', new SingleKey('name')),
                new ValueObject('cost', new MoneyConstructor(), new MoneySerializator(), new MultiKey(['cost_amount' => 'amount', 'cost_currency' => 'currency'])),
                new ValueObjectCollection('categories', [
                    'category' => ['Category', new CategoryConstructor(), new InCollectionCategorySerializator()],
                ], new SingleKey('categories')),
            ], new SingleKey('second_half')),
    ], new SingleKey('elements')),
]);

$obj1 = new Entity('entity1', new Money(123, new Currency('PLN')));
$obj1->addCategory(new Category(Category::CATEGORY_1));
$obj1->addCategory(new Category(Category::CATEGORY_3));

$obj2 = new Entity('entity2', new Money(321, new Currency('PLN')));
$obj2->addCategory(new Category(Category::CATEGORY_2));

$obj1->setSecondHalf($obj2);
$obj2->setSecondHalf($obj1);

$obj1->addElement($obj1);
$obj1->addElement($obj2);

$serializer = new \Diodac\Restorer\ObjectSerializator($restorer);

$serialized = $serializer->serialize($obj1);

//print_r($serialized);
var_dump($restorer->create($serialized));