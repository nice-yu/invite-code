<?php
declare(strict_types=1);

namespace TimAutumnWind;

class InvitaionCode
{

    /**
     * 32个进制字符（0,1,O,I 没加入, Y,Z 用于补位）
     * 去除 0 O 1 I
     * 预留 Y 和 Z
     * @var string[]
     * @version("1.0")
     * @author("Tim-AutumnWind <wxstones@gmail.com>")
     */
    private static $dictionaries = array(
        '2', '3', '4', '5', '6', '7', '8', '9',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
        'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R',
        'S', 'T', 'U', 'V', 'W', 'X');


    /**
     * Y Z 为补位字符，不和上述字符重复
     * @var string
     * @version("1.0")
     * @author("Tim-AutumnWind <wxstones@gmail.com>")
     */
    private static $complement = array('Y', 'Z');


    /**
     * 字典大小
     * @var int
     * @version("1.0")
     * @author("Tim-AutumnWind <wxstones@gmail.com>")
     */
    private static $length = 30;


    /**
     * 邀请码最小长度
     * @var int
     * @version("1.0")
     * @author("Tim-AutumnWind <wxstones@gmail.com>")
     */
    private static $max = 6;


    /**
     * 初始化可自定义生成方式
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
     * 编码一个邀请码
     * @param int $id 数字Id
     * @return string
     * @version("1.0")
     * @author("Tim-AutumnWind <wxstones@gmail.com>")
     */
    public function decode(int $id)
    {
        $inviteCode = "";
        $length = self::$length;
        /** 拿到被除次数 */
        while (floor($id / $length) > 0) {
            /** 映射被除次数 */
            $index = floatval($id) % $length;
            $inviteCode .= self::$dictionaries[$index];
            /** 直到除尽 */
            $id = floor($id / $length);
        }
        /** 取模获取数字 */
        $index = $id % $length;
        $inviteCode .= self::$dictionaries[$index];
        return $this->mixedInvite($inviteCode);
    }

    /**
     * 混合邀请码
     * @param string $inviteCode
     * @return string
     * @version("1.0")
     * @author("Tim-AutumnWind <wxstones@gmail.com>")
     */
    private function mixedInvite(string $inviteCode): string
    {
        /** 邀请码长度 */
        $code_len = strlen($inviteCode);
        if ($code_len < self::$max) {
            /** 获取补位符号 */
            $count = count(self::$complement);
            $index = rand(0, $count - 1);
            $inviteCode .= self::$complement[$index];

            /** 随机补位, 生成最终邀请码 */
            for ($i = 0; $i < self::$max - ($code_len + 1); $i++) {
                /** 获取随机字符 */
                $dictIndex = rand(0, self::$length - 1);
                $minxedString = self::$dictionaries[$dictIndex];
                $inviteCode .= $minxedString;
            }
        }
        return $inviteCode;
    }

    /**
     * 解码一个邀请码
     * @param string $inviteCode
     * @return float|int
     * @version("1.0")
     * @author("Tim-AutumnWind <wxstones@gmail.com>")
     */
    public function encode(string $inviteCode)
    {
        /** 获取映射数组的具体含义 */
        $dictionaries = array_flip(self::$dictionaries);

        /** 确定补位字符位置 */
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

        /** 字符映射反推 */
        $encode = 0;
        for ($i = 0; $i < $mixed; $i++) {
            $index = $dictionaries[$inviteCode[$i]];
            $encode += pow(self::$length, $i) * $index;
        }
        return $encode;
    }

}