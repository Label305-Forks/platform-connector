<?php

namespace WebservicesNl\Test\Soap\Client\Config\Webservices;

use WebservicesNl\Common\Exception\Exception;
use WebservicesNl\Soap\Config\Webservices\Converter;

/**
 * Class ConverterTest
 */
class ConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getFaults
     *
     * @param string $errorCode
     */
    public function testConverter($errorCode)
    {
        $converter = Converter::build();
        $detail = new \stdClass();
        $detail->errorCode = $errorCode;
        $soapFault = new \SoapFault('Server', 'some detailed error message', null, $detail);
        $errorClassFQ = 'WebservicesNl\Common\Exception\\' . str_replace('.', '\\', $errorCode) . 'Exception';

        try {
            $exception = $converter->convertToException($soapFault);
            self::assertInstanceOf($errorClassFQ, $exception);
        } catch (Exception $e) {

            self::assertInstanceOf($errorClassFQ, $e);
        }
    }

    /**
     * @expectedException \WebservicesNl\Common\Exception\ServerException
     * @expectedExceptionMessage Could not convert errorCode: 'FakeClass'
     * @throws \WebservicesNl\Common\Exception\ServerException
     */
    public function testConverterBad()
    {
        $converter = Converter::build();
        $detail = new \stdClass();
        $detail->errorCode = 'FakeClass';
        $soapFault = new \SoapFault('Server', 'some detailed error message', null, $detail);
        $exception = $converter->convertToException($soapFault);
        //self::assertInstanceOf($errorClassFQ, $exception);
    }

    /**
     * @return string
     */
    public function getFaults()
    {
        return [
            ['Client.Authentication'],
            ['Client.Authentication.HostRestriction'],
            ['Client.Authentication.Password'],
            ['Client.Authentication.Username'],
            ['Client.Authorization'],
            ['Client.Input'],
            ['Client.Input.FormatIncorrect'],
            ['Client.Input.Incomplete'],
            ['Client.Input.Invalid'],
            ['Client.Payment'],
            ['Server'],
            ['Server.Data'],
            ['Server.Data.NotFound'],
            ['Server.Data.PageNotFound'],
            ['Server.Unavailable'],
            ['Server.Unavailable.InternalError'],
            ['Server.Unavailable.Temporary'],
        ];
    }
}
