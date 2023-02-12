<?php
declare(strict_types=1);
namespace NiceYu\InviteCode;

use UnexpectedValueException;

class InviteCode
{
    /**
     * zh: 26个字母 + 10个数字
     * zh: 去除容易混淆的 ( 0 O 1 I )
     * zh: 保留2个分割字母 ( Y Z )
     * en: 26 letters + 10 numbers
     * en: Remove easily confused ( 0 O 1 I )
     * en: Keep 2 split letters ( Y Z )
     * @var array|string[]
     */
    private array $dictionaries = array(
        '2', '3', '4', '5', '6', '7', '8', '9',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
        'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R',
        'S', 'T', 'U', 'V', 'W', 'X'
    );
    
    /**
     * zh: 分割字母
     * en: Split letter
     * @var array
     */
    private array $complement = array('Y', 'Z');
    
    /**
     * zh: 邀请码的长度
     * en: Length of invitation code
     * @var int
     */
    private int $max = 6;

    /**
     * zh: 加密生成邀请码
     * en: Encrypted generation of invitation code
     * @param int $id
     * @return string
     */
    public function encode(int $id):string
    {
        $code = "";
        $char = $this->dictionaries;
        $lens = count($char);

        /**
         * zh: 超出最大计算量
         * en: exceed calc max
         */
        if($id > ($lens ** $this->max)){
            $message = "Unexpected value: $id, the maximum number of digits that can be calculated by the current value of $this->max digits is ". ($lens ** $this->max);
            throw new UnexpectedValueException($message);
        }

        while (intval($id / $lens) > 0){
            $index = $id % $lens;
            $code .= $char[$index];
            $id    = intval($id / $lens);
        }
        $index = $id % $lens;
        $code .= $char[$index];

        return $this->confusionInviteCode($code);
    }

    /**
     * zh: 混淆邀请码
     * en: confusion Invite Code
     * @param string $code
     * @return string
     */
    private function confusionInviteCode(string $code):string
    {
        $len = strlen($code);
        if ($len < $this->max){
            /**
             * zh: 填充分割字母
             * en: fill split letter
             */
            $count = count($this->complement);
            $index = mt_rand(0, $count - 1);
            $code .= $this->complement[$index];

            /**
             * zh: 填充随机字母
             * en: Fill in random letters
             */
            $char = $this->dictionaries;
            $lens = count($char);
            for ($i = 0; $i < $this->max - ($len + 1); $i++) {
                $index = mt_rand(0, $lens - 1);
                $code .= $char[$index];
            }
        }
        return $code;
    }

    /**
     * zh: 混淆邀请码
     * en: confusion Invite Code
     * @param string $code
     * @return int
     */
    public function decode(string $code):int
    {
        /**
         * zh: 获取映射数组的具体含义
         * en: Get the specific meaning of the mapping array
         */
        $char = array_flip($this->dictionaries);

        /**
         * zh: 获得补位字母位置
         * en: Get the padding letter position
         */
        $mixed = strlen($code);
        $i = 0;
        while ($i < count($this->complement)) {
            $item = strpos($code, $this->complement[$i]);
            if (!empty($item)) {
                $mixed = $item;
                break;
            }
            $i++;
        }

        /**
         * zh: 字母对应解密
         * en: Letter correspondence decryption
         */
        $count = count($char);
        $encode = 0;
        for ($i = 0; $i < $mixed; $i++) {
            $index = $char[$code[$i]];
            $encode += pow($count, $i) * $index;
        }
        return $encode;
    }

    /**
     * zh: 获取字符对照表
     * en: Get character comparison table
     * @return array
     */
    public function getDictionaries(): array
    {
        return $this->dictionaries;
    }

    /**
     * zh: 获取分割字母
     * en: get split letter
     * @return array
     */
    public function getComplement(): array
    {
        return $this->complement;
    }

    /**
     * zh: 获取最大位数
     * en: get the maximum number of digits
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }

    /**
     * zh: 设置字符对照表
     * en: Set character comparison table
     * @param array $dictionaries
     * @return $this
     */
    public function setDictionaries(array $dictionaries): self
    {
        $this->dictionaries = $dictionaries;
        return $this;
    }

    /**
     * zh: 设置分割字母
     * en: set split letter
     * @param array $complement
     * @return $this
     */
    public function setComplement(array $complement): self
    {
        $this->complement = $complement;
        return $this;
    }

    /**
     * zh: 设置最大位数
     * en: Set the maximum number of digits
     * @param int $max
     * @return $this
     */
    public function setMax(int $max): self
    {
        $this->max = $max;
        return $this;
    }
}