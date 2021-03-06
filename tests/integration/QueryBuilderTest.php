<?php

namespace Sparql\Tests\Integration;

use Sparql\QueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @license GNU GPL v2+
 * @author Bene* < benestar.wikimedia@gmail.com >
 */
class QueryBuilderTest extends TestCase {

	private static $prefixes = array(
		'test' => 'http://www.example.com/test#'
	);

	public function testBasicFunctionality() {
		$queryBuilder = new QueryBuilder( self::$prefixes );

		$queryBuilder->select( '?name', '?email' )
			->where( '?person', 'test:name', '?name' )
			->also( 'test:email', '?email' )
			->limit( 10 );

		$this->assertIsExpected( 'basic_functionality', $queryBuilder->format() );
	}

	public function testOptionalFilter() {
		$queryBuilder = new QueryBuilder( self::$prefixes );
		$queryBuilder->select( '?name' )
			->where( '?person', 'test:name', '?name' )
			->optional( '?person', 'test:email', '?email' )
			->filter( '!BOUND (?email)' );

		$this->assertIsExpected( 'optional_filter', $queryBuilder->format() );
	}

	public function testUnion() {
		$queryBuilder = new QueryBuilder( array(
			'dc10' => 'http://purl.org/dc/elements/1.0/',
			'dc11' => 'http://purl.org/dc/elements/1.1/'
		) );

		$queryBuilder->select( '?title', '?author' )
			->union(
				$queryBuilder->newSubgraph()
					->where( '?book', 'dc10:title', '?title' )
					->also( 'dc10:creator', '?author' ),
				$queryBuilder->newSubgraph()
					->where( '?book', 'dc11:title', '?title' )
					->also( 'dc11:creator', '?author' )
			);

		$this->assertIsExpected( 'union', $queryBuilder->format() );
	}

	private function assertIsExpected( $name, $sparql ) {
		$expected = file_get_contents( __DIR__ . '/../data/builder_' . $name . '.rq' );

		$this->assertEquals( $expected, $sparql, 'Query didn\'t match the expected content of integration_' . $name . '.rq' );
	}

}
