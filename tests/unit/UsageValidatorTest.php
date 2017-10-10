<?php

namespace Sparql\Tests;

use Sparql\UsageValidator;

/**
 * @covers Sparql\UsageValidator
 *
 * @license GNU GPL v2+
 * @author Bene* < benestar.wikimedia@gmail.com >
 */
class UsageValidatorTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider provideDataOk
	 */
	public function testValidate_variablesOk( array $defined, array $used ) {
		$usageValidator = new UsageValidator();
		$usageValidator->trackDefinedVariables( $defined );
		$usageValidator->trackUsedVariables( $used );
		$usageValidator->validate();

		$this->assertTrue( true );
	}

	/**
	 * @dataProvider provideDataOk
	 */
	public function testValidate_prefixesOk( array $defined, array $used ) {
		$usageValidator = new UsageValidator();
		$usageValidator->trackDefinedPrefixes( $defined );
		$usageValidator->trackUsedPrefixes( $used );
		$usageValidator->validate();

		$this->assertTrue( true );
	}

	public function provideDataOk() {
		return array(
			array( array(), array() ),
			array( array( 'foo', 'bar' ), array() ),
			array( array( 'foo', 'bar' ), array( 'foo' ) ),
			array( array( 'foo', 'bar' ), array( 'foo', 'bar' ) ),
		);
	}

	public function provideDataInvalid() {
		return array(
			array( array(), array( 'foo', 'bar' ) ),
			array( array( 'foo' ), array( 'foo', 'bar' ) ),
			array( array( 'bar' ), array( 'foo', 'bar' ) ),
		);
	}

	public function provideTrackVariablesParsing() {
		return array(
			array( 'foo ?abc bar', '?abc' ),
			array( 'foo $abc bar', '?abc' ),
			array( '?foo abc $bar', '?foo, ?bar' ),
			array( 'foo.?abc', '?abc' ),
			array( '?foo$bar', '?foo' ),
			array( '?foo AS ?bar', '?foo' )
		);
	}

	public function provideTrackPrefixesParsing() {
		return array(
			array( 'abc foo:bar def', array( 'foo' ) ),
			array( 'foo:abc xxx bar:def', array( 'foo', 'bar' ) ),
			array( 'abc.foo:bar', array( 'foo' ) )
		);
	}

}
