<?php
declare(strict_types=1);

namespace TimAutumnWind;

class InvitaionCode
{

    /**
     * 32 hexadecimal characters
     * Not in ( 0 O 1 I)
     * reserve (Y AND Z)
     * @var string[]
     * @version("1.0")
     */
    private static $dictionaries = array(
        '2', '3', '4', '5', '6', '7', '8', '9',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
        'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R',
        'S', 'T', 'U', 'V', 'W', 'X');


    /**
     * (Y AND Z) The above characters cannot be repeated
     * @var string
     * @version("1.0")
     * @author("Tim-AutumnWind <wxstones@gmail.com>")
     */
    private static $complement = array('Y', 'Z');


    /**
     * Dictionary size
     * @var int
     * @version("1.0")
     * @author("Tim-AutumnWind <wxstones@gmail.com>")
     */
    private static $length = 30;


    /**
     * Minimum length of invitation code
     * @var int
     * @version("1.0")
     * @author("Tim-AutumnWind <wxstones@gmail.com>")
     */
    private static $max = 6;


    /**
     * Initialize customizable generation mode
     * InvitaionCode constructor.
     * @param int $max
     * @param array $dictionaries
     * @param string $complement
     */
    public function __construct($max = 6, $dictionaries = array(), $complement = '')
    {
        if (!empty($max)) {
            self::$max = $max;
        }
        if (!count($dictionaries) > 10) {
            self::$dictionaries = $dictionaries;
            self::$length = count($dictionaries);
        }
        if (!empty($complement)) {
            self::$complement = $complement;
        }

    }

    /**
     * Code an invitation code
     * @param int $id Id
     * @return string
     * @version("1.0")
     * @author("Tim-AutumnWind <wxstones@gmail.com>")
     */
    public function decode(int $id)
    {
        $inviteCode = "";
        $length = self::$length;
        while (floor($id / $length) > 0) {
            $index = floatval($id) % $length;
            $inviteCode .= self::$dictionaries[$index];
            $id = floor($id / $length);
        }
        $index = $id % $length;
        $inviteCode .= self::$dictionaries[$index];
        return $this->mixedInvite($inviteCode);
    }

    /**
     * Mixed invitation code
     * @param string $inviteCode
     * @return string
     * @version("1.0")
     * @author("Tim-AutumnWind <wxstones@gmail.com>")
     */
    private function mixedInvite(string $inviteCode): string
    {
        /** Invitation code length */
        $code_len = strlen($inviteCode);
        if ($code_len < self::$max) {
            /** Get complement */
            $count = count(self::$complement);
            $index = rand(0, $count - 1);
            $inviteCode .= self::$complement[$index];

            /** Random fill, generate the final invitation code */
            for ($i = 0; $i < self::$max - ($code_len + 1); $i++) {
                /** Get random characters */
                $dictIndex = rand(0, self::$length - 1);
                $minxedString = self::$dictionaries[$dictIndex];
                $inviteCode .= $minxedString;
            }
        }
        return $inviteCode;
    }

    /**
     * Decode an invitation code
     * @param string $inviteCode
     * @return float|int
     * @version("1.0")
     * @author("Tim-AutumnWind <wxstones@gmail.com>")
     */
    public function encode(string $inviteCode)
    {
        /** Get the specific meaning of the mapping array */
        $dictionaries = array_flip(self::$dictionaries);

        /** Determine the position of complement character */
        $mixed = strlen($inviteCode);;
        $i = 0;
        while ($i < count(self::$complement)) {
            $item = strpos($inviteCode, self::$complement[$i]);
            if (!empty($item)) {
                $mixed = $item;
                break;
            }
            $i++;
        }

        /** Character mapping Backstepping */
        $encode = 0;
        for ($i = 0; $i < $mixed; $i++) {
            $index = $dictionaries[$inviteCode[$i]];
            $encode += pow(self::$length, $i) * $index;
        }
        return $encode;
    }

}