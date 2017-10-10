<?php

namespace Sparql\Tests;

use Sparql\QueryBuilder;
use Sparql\QueryExecuter;

/**
 * @covers Sparql\QueryExecuter
 *
 * @license GNU GPL v2+
 * @author Bene* < benestar.wikimedia@gmail.com >
 */
class QueryExecuterTest extends \PHPUnit_Framework_TestCase {

	private function getHttpMock( $params ) {
		$http = $this->getMockBuilder( 'Sparql\Http' )
			->disableOriginalConstructor()
			->getMock();

		$http->expects( $this->once() )
			->method( 'request' )
			->with(
				$this->equalTo( 'test.example.com' ),
				$this->equalTo( $params )
			)
			->will( $this->returnValue( '{"results":"~=[,,_,,]:3"}' ) );

		return $http;
	}

	public function testExecute() {
		$http = $this->getHttpMock( array(
			'query' => 'FooBar',
			'format' => 'json'
		) );

		$queryExecuter = new QueryExecuter( 'test.example.com', array(), $http );
		$result = $queryExecuter->execute( 'FooBar' );

		$this->assertEquals( '~=[,,_,,]:3', $result );
	}

	public function testExecuteCustomParams() {
		$http = $this->getHttpMock( array(
			'fancy-query' => 'FooBar',
			'format-nyan' => 'json'
		) );

		$queryExecuter = new QueryExecuter( 'test.example.com', array(
			'queryParam' => 'fancy-query',
			'formatParam' => 'format-nyan'
		), $http );

		$result = $queryExecuter->execute( 'FooBar' );

		$this->assertEquals( '~=[,,_,,]:3', $result );
	}

	public function testExecuteQueryBuilder() {
		$http = $this->getHttpMock( array(
			'query' => 'SELECT * WHERE { }',
			'format' => 'json'
		) );

		$queryExecuter = new QueryExecuter( 'test.example.com', array(), $http );
		$result = $queryExecuter->execute( new QueryBuilder() );

		$this->assertEquals( '~=[,,_,,]:3', $result );
	}

	public function testExecuteInvalidArgument() {
		$queryExecuter = new QueryExecuter( 'test.example.com' );
		$this->setExpectedException( 'InvalidArgumentException' );

		$queryExecuter->execute( null );
	}

}
