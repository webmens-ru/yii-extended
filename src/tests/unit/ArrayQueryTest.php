<?php

// php vendor\bin\phpunit vendor\webmens-ru\yii-extended\src\tests\unit\ArrayQueryTest.php
namespace wm\yii\tests\unit;

use PHPUnit\Framework\TestCase;
use wm\yii\array\Query;
use wm\yii\helpers\ArrayHelper;

class ArrayQueryTest extends TestCase
{
    private $data;

    private $dataObj;

    protected function setUp(): void
    {
        $this->data = [
            ['id' => 1, 'name' => 'John', 'age' => 25, 'active' => true, 'salary' => 1000],
            ['id' => 2, 'name' => 'Jane', 'age' => 30, 'active' => false, 'salary' => 2000],
            ['id' => 3, 'name' => 'Doe', 'age' => 25, 'active' => true, 'salary' => 1500],
            ['id' => 4, 'name' => 'Alice', 'age' => 35, 'active' => false, 'salary' => 2500],
            ['id' => 5, 'name' => 'Bob', 'age' => 40, 'active' => true, 'salary' => 3000],
            ['id' => 6, 'name' => 'Eve', 'age' => null, 'active' => null, 'salary' => null],
        ];

        $this->dataObj = [
            (object)['id' => 1, 'name' => 'John', 'age' => 25, 'active' => true, 'salary' => 1000],
            (object)['id' => 2, 'name' => 'Jane', 'age' => 30, 'active' => false, 'salary' => 2000],
            (object)['id' => 3, 'name' => 'Doe', 'age' => 25, 'active' => true, 'salary' => 1500],
            (object)['id' => 4, 'name' => 'Alice', 'age' => 35, 'active' => false, 'salary' => 2500],
            (object)['id' => 5, 'name' => 'Bob', 'age' => 40, 'active' => true, 'salary' => 3000],
            (object)['id' => 6, 'name' => 'Eve', 'age' => null, 'active' => null, 'salary' => null],
        ];
    }

    public function testWhere()
    {
        $query = new Query($this->data);
        $result = $query->where(['id' => 1])->all();
        $this->assertCount(1, $result);
        $this->assertEquals('John', $result[0]['name']);
    }

    public function testWhereObj()
    {
        $query = new Query($this->dataObj);
        $result = $query->where(['id' => 1])->all();
        $this->assertCount(1, $result);
        $this->assertEquals('John', $result[0]->name);
    }

    public function testAndWhere()
    {
        $query = new Query($this->data);
        $result = $query->andWhere(['>', 'age', 25])->andWhere(['<', 'age', 40])->all();
        $this->assertCount(2, $result);
        $this->assertEquals(['Jane', 'Alice'], array_column($result, 'name'));
    }

    public function testAndWhereObj()
    {
        $query = new Query($this->dataObj);
        $result = $query->andWhere(['>', 'age', 25])->andWhere(['<', 'age', 40])->all();
        $this->assertCount(2, $result);
        $this->assertEquals(['Jane', 'Alice'], ArrayHelper::getColumn($result, 'name'));
    }

    public function testOrWhere()
    {
        $query = new Query($this->data);
        $result = $query->orWhere(['id' => 1])->orWhere(['id' => 3])->all();
        $this->assertCount(2, $result);
        $this->assertEquals(['John', 'Doe'], array_column($result, 'name'));
    }

    public function testLike()
    {
        $query = new Query($this->data);
        $result = $query->andWhere(['like', 'name', 'Al'])->all();
        $this->assertCount(1, $result);
        $this->assertEquals('Alice', $result[0]['name']);
    }

    public function testIn()
    {
        $query = new Query($this->data);
        $result = $query->andWhere(['in', 'id', [1, 2, 3]])->all();
        $this->assertCount(3, $result);
        $this->assertEquals(['John', 'Jane', 'Doe'], array_column($result, 'name'));
    }

    public function testNotIn()
    {
        $query = new Query($this->data);
        $result = $query->andWhere(['not in', 'id', [1, 2, 3]])->all();
        $this->assertCount(3, $result);
        $this->assertEquals(['Alice', 'Bob', 'Eve'], array_column($result, 'name'));
    }

    public function testBetween()
    {
        $query = new Query($this->data);
        $result = $query->andWhere(['between', 'age', 25, 35])->all();
        $this->assertCount(4, $result);
        $this->assertEquals(['John', 'Jane', 'Doe', 'Alice'], array_column($result, 'name'));
    }

    public function testOrder()
    {
        $query = new Query($this->data);
        $result = $query->orderBy(['age' => SORT_DESC])->all();
        $this->assertEquals(['Bob', 'Alice', 'Jane', 'John', 'Doe', 'Eve'], array_column($result, 'name'));
    }

    //TODO Не правильно работает мультисортировка
//    public function testAddOrderBy()
//    {
//        $query = new Query($this->data);
//        $result = $query
//            ->orderBy(['age' => SORT_ASC])
//            ->addOrderBy(['name' => SORT_DESC])
//            ->all();
////        print_r()
//        $this->assertEquals(['Eve','John', 'Doe', 'Jane', 'Alice', 'Bob'], array_column($result, 'name'));
//    }

    public function testLimitOffset()
    {
        $query = new Query($this->data);
        $result = $query->orderBy(['age' => SORT_ASC])->offset(1)->limit(2)->all();
        $this->assertCount(2, $result);
        $this->assertEquals(['John', 'Doe'], array_column($result, 'name'));
    }

    public function testIndexBy()
    {
        $query = new Query($this->data);
        $result = $query->where(['>', 'age', 30])->indexBy('id')->all();
        $this->assertEquals(['Alice', 'Bob'], array_values(array_column($result, 'name')));
        $this->assertArrayHasKey(4, $result);
        $this->assertArrayHasKey(5, $result);
    }

    public function testExists()
    {
        $query = new Query($this->data);
        $this->assertTrue($query->where(['id' => 1])->exists());
        $this->assertFalse($query->where(['id' => 999])->exists());
    }

    public function testCount()
    {
        $query = new Query($this->data);
        $this->assertEquals(6, $query->count());
        $this->assertEquals(2, $query->where(['>', 'age', 30])->count());
    }

//    public function testColumn()
//    {
//        $query = new Query($this->data);
//        $names = $query->column('name');
//        $this->assertEquals(['John', 'Jane', 'Doe', 'Alice', 'Bob', 'Eve'], $names);
//
//        $query = new Query($this->data);
//        $ids = $query->indexBy('name')->column('id');
//        $this->assertEquals([
//            'John' => 1,
//            'Jane' => 2,
//            'Doe' => 3,
//            'Alice' => 4,
//            'Bob' => 5,
//            'Eve' => 6,
//        ], $ids);
//    }

    public function testFilterWhere()
    {
        $query = new Query($this->data);
        $result = $query->filterWhere(['age' => 25])->all();
        $this->assertCount(2, $result);

        $query = new Query($this->data);
        $result = $query->filterWhere(['age' => null])->all();
        $this->assertCount(6, $result);

        $query = new Query($this->data);
        $result = $query->filterWhere(['age' => ''])->all();
        $this->assertCount(6, $result); // empty string is filtered out
    }

    public function testFilterCompare()
    {
        $query = new Query($this->data);
        $result = $query->andFilterCompare('age', 25, '=')->all();
        $this->assertCount(2, $result);
        $this->assertEquals(['John', 'Doe'], array_column($result, 'name'));

        $query = new Query($this->data);
        $result = $query->andFilterCompare('age', 25, '>')->all();
        $this->assertCount(3, $result);
        $this->assertEquals(['Jane', 'Alice', 'Bob'], array_column($result, 'name'));

        $query = new Query($this->data);
        $result = $query->andFilterCompare('age', [25,30], 'in')->all();
        $this->assertCount(3, $result);
        $this->assertEquals(['John', 'Jane', 'Doe'], array_column($result, 'name'));

        $query = new Query($this->data);
        $result = $query->andFilterCompare('name', 'e', 'like')->all();
        $this->assertCount(4, $result);
        $this->assertEquals(['Jane', 'Doe', 'Alice', 'Eve',], array_column($result, 'name'));

        $query = new Query($this->data);
        $result = $query->andFilterCompare('age', null, 'isNull')->all();
        $this->assertCount(1, $result);
        $this->assertEquals(['Eve',], array_column($result, 'name'));

        $query = new Query($this->data);
        $result = $query->andFilterCompare('age', null, 'isNotNull')->all();
        $this->assertCount(5, $result);
        $this->assertEquals(['John', 'Jane', 'Doe', 'Alice', 'Bob',], array_column($result, 'name'));
    }

    public function testNotCondition()
    {
        $query = new Query($this->data);
        $result = $query->where(['not', ['id' => 1]])->all();
        $this->assertCount(5, $result);

        $result = $query->where(['not', ['>', 'age', 30]])->all();
        $this->assertCount(4, $result); // includes null age
    }

    public function testEmptyData()
    {
        $query = new Query([]);
        $this->assertCount(0, $query->all());
        $this->assertEquals(0, $query->count());
        $this->assertFalse($query->exists());
    }

    public function testSubObjectFilter()
    {
        $query = new Query(
            [
                (object)['id' => 1, 'title' => 'title1', 'status' => 'start', 'deal' => (object)['id' => 15, 'status' => 1]],
                (object)['id' => 2, 'title' => 'title2', 'status' => 'start', 'deal' => (object)['id' => 17, 'status' => 2]],
                (object)['id' => 3, 'title' => 'title3', 'status' => 'start', 'deal' => (object)['id' => 18, 'status' => 1]],
            ]
        );
        $result = $query->where(['=', 'deal.status', 1])->orderBy(['id' => SORT_DESC])->limit(1)->offset(1)->all();
        $this->assertCount(1, $result);
        $this->assertEquals([1], ArrayHelper::getColumn($result, 'id'));
    }

//    public function testEmulateExecution()
//    {
//        $query = new Query($this->data);
//        $query->emulateExecution();
//        $this->assertCount(0, $query->all());
//        $this->assertEquals(0, $query->count());
//        $this->assertFalse($query->exists());
//    }
}
