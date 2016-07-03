<?php

namespace Tests\Integration;

class ObjectsTest extends \TestCase
{
    /**
     * @test
     */
    public function createNew()
    {
        $object = ['test_key1' => 'test_value11'];
        $this->json('POST', '/object', $object);
        $this->assertResponseOk();
    }

    /**
     * @test
     * @depends createNew
     */
    public function getByKeyBeforeUpdate()
    {
        $expected = 'test_value11';
        $this->get('/object/test_key1');
        $this->assertResponseOk();
        $actual = $this->response->getContent();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @depends createNew
     */
    public function updateExist()
    {
        usleep(100000);
        $object = ['test_key1' => 'test_value12'];
        $this->json('POST', '/object', $object);
        $this->assertResponseOk();
    }

    /**
     * @test
     * @depends updateExist
     */
    public function getByKeyAfterUpdate()
    {
        $expected = 'test_value12';
        $this->get('/object/test_key1');
        $this->assertResponseOk();
        $actual = $this->response->getContent();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function getByKeyAtMiddleTime()
    {
        $expected = 'test_value1';
        $this->get(sprintf('/object/test_key?timestamp=%d', 1467533000));
        $this->assertResponseOk();
        $actual = $this->response->getContent();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function getByKeyAtLaterTime()
    {
        $expected = 'test_value2';
        $this->get(sprintf('/object/test_key?timestamp=%d', 1467534000));
        $this->assertResponseOk();
        $actual = $this->response->getContent();
        $this->assertEquals($expected, $actual);
    }
}
