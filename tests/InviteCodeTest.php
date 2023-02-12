<?php
declare(strict_types=1);

namespace NiceYu\Tests\InviteCode;

use NiceYu\InviteCode\InviteCode;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * @coversDefaultClass \NiceYu\InviteCode\InviteCode
 */
final class InviteCodeTest extends TestCase
{
    /**
     * @covers ::encode
     * @covers ::getMax
     * @covers ::setComplement
     * @covers ::setDictionaries
     * @covers ::confusionInviteCode
     */
    public function testMultipleSeparatorLetters():void
    {
        $id = mt_rand(999, 99999);
        $complement = array('S', 'T', 'U', 'V', 'W', 'X', 'Z', 'Y');
        $dictionaries = array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
            '2', '3', '4', '5', '6', '7', '8', '9',
            'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R',
        );
        $class = (new InviteCode())->setComplement($complement)->setDictionaries($dictionaries);
        $encode = $class->encode($id);
        $this->assertEquals(strlen($encode),$class->getMax());
    }

    /**
     * @covers ::encode
     * @covers ::getMax
     * @covers ::getDictionaries
     * @covers ::confusionInviteCode
     */
    public function testInviteCodeMaxEncode():void
    {
        $class  = new InviteCode();
        $count  = count($class->getDictionaries());
        $value  = $class->getMax();
        $encode = $class->encode(pow($count,$value) - 1);
        $this->assertEquals(strlen($encode),$class->getMax());
    }

    /**
     * @covers ::encode
     * @covers ::getMax
     * @covers ::confusionInviteCode
     */
    public function testInviteCodeEncode():void
    {
        $class  = new InviteCode();
        $encode = $class->encode(1);
        $this->assertEquals(strlen($encode),$class->getMax());
    }

    /**
     * @covers ::encode
     * @covers ::decode
     * @covers ::confusionInviteCode
     */
    public function testInviteCodeDecode():void
    {
        $id     = 1;
        $class  = new InviteCode();
        $encode = $class->encode($id);
        $decode = $class->decode($encode);
        $this->assertEquals($decode,$id);
    }

    /**
     * @covers ::encode
     * @covers ::getMax
     * @covers ::getDictionaries
     */
    public function testInviteCodeErrorCapture():void
    {
        $this->expectException(UnexpectedValueException::class);
        $class = new InviteCode();
        $max   = $class->getMax();
        $dist  = $class->getDictionaries();
        $class->encode((count($dist) ** $max) + 1);
    }

    /**
     * @covers ::setMax
     * @covers ::setComplement
     * @covers ::setDictionaries
     * @covers ::getMax
     * @covers ::getComplement
     * @covers ::getDictionaries
     */
    public function testInvitationCodeSettings():void
    {
        /** assertion class */
        $class = new InviteCode();
        $this->assertInstanceOf(InviteCode::class, $class);

        /** assertion dictionaries */
        $dictionaries = array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
            '2', '3', '4', '5', '6', '7', '8', '9',
            'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R',
            'S', 'T', 'U', 'V', 'W', 'X'
        );
        $class->setDictionaries($dictionaries);
        $this->assertEquals($class->getDictionaries(),$dictionaries);

        /** assertion complement */
        $complement = array('Z', 'Y');
        $class->setComplement($complement);
        $this->assertEquals($class->getComplement(),$complement);

        /** assertion max */
        $max = 5;
        $class->setMax($max);
        $this->assertEquals($class->getMax(),$max);
    }
}