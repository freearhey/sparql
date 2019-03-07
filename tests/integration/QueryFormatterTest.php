<?php

namespace Sparql\Tests\Integration;

use Sparql\QueryFormatter;
use PHPUnit\Framework\TestCase;

/**
 * @license GNU GPL v2+
 * @author Bene* < benestar.wikimedia@gmail.com >
 */
class QueryFormatterTest extends TestCase {

	/**
	 * @dataProvider provideTestFormat
	 */
	public function testFormat( $name ) {
		$formatter = new QueryFormatter();
		$sparql = file_get_contents( __DIR__ . '/../data/formatter_' . $name . '_in.rq' );
		$expected = file_get_contents( __DIR__ . '/../data/formatter_' . $name . '_out.rq' );

		$this->assertEquals( $expected, $formatter->format( $sparql ),
			'Input from formatter_' . $name . '_in.rq didn\'t produce the expected output.'  );
	}

	public function provideTestFormat() {
		return array(
			'Query without line-breaks' => array( 'one_line' ),
			'Query with lots of spaces' => array( 'many_spaces' ),
			'Query with few spaces' => array( 'few_spaces' )
		);
	}

}
