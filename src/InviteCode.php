<?php
declare(strict_types=1);
namespace NiceYu;

class InviteCode
{
    
    /**
     * 32 hexadecimal characters
     * Not in ( 0 O 1 I)
     * reserve (Y AND Z)
     * @var string[]
     */
    private array $dictionaries = array(
        '2', '3', '4', '5', '6', '7', '8', '9',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
        'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R',
        'S', 'T', 'U', 'V', 'W', 'X');
    
    
    /**
     * (Y AND Z) The above characters cannot be repeated
     * @var array
     */
    private array $complement = array('Y', 'Z');
    
    /**
     * Dictionary size
     * @var int
     */
    private int $length = 30;
    
    /**
     * Minimum length of invitation code
     * @var int
     */
    private int $max = 6;
    
    /**
     * Initialize customizable generation mode
     * @param int $max
     * @param array $dictionaries
     * @param array $complement
     */
    public function __construct(int $max = 6, array $dictionaries = array(), array $complement = [])
    {
        if (!empty($max)) {
            $this->max = $max;
        }
        if (!count($dictionaries) > 10) {
            $this->dictionaries = $dictionaries;
            $this->length = count($dictionaries);
        }
        if (!empty($complement)) {
            $this->complement = $complement;
        }
        
    }
    
    /**
     * Code an inviteCode
     * @param int $id Id
     * @return string
     */
    public function encode(int $id): string
    {
        $inviteCode = "";
        $length = $this->length;
        while (floor($id / $length) > 0) {
            $index = floatval($id) % $length;
            $inviteCode .= $this->dictionaries[$index];
            $id = floor($id / $length);
        }
        $index = $id % $length;
        $inviteCode .= $this->dictionaries[$index];
        return $this->mixedInvite($inviteCode);
    }
    
    /**
     * Mixed inviteCode
     * @param string $inviteCode
     * @return string
     */
    private function mixedInvite(string $inviteCode): string
    {
        /** Invitation code length */
        $code_len = strlen($inviteCode);
        if ($code_len < $this->max) {
            /** Get complement */
            $count = count($this->complement);
            $index = rand(0, $count - 1);
            $inviteCode .= $this->complement[$index];
            
            /** Random fill, generate the final invitation code */
            for ($i = 0; $i < $this->max - ($code_len + 1); $i++) {
                /** Get random characters */
                $dictIndex = rand(0, $this->length - 1);
                $gather = $this->dictionaries[$dictIndex];
                $inviteCode .= $gather;
            }
        }
        return $inviteCode;
    }
    
    /**
     * Decode an inviteCode
     * @param string $inviteCode
     * @return int
     */
    public function decode(string $inviteCode): int
    {
        /** Get the specific meaning of the mapping array */
        $dictionaries = array_flip($this->dictionaries);
        
        /** Determine the position of complement character */
        $mixed = strlen($inviteCode);
        $i = 0;
        while ($i < count($this->complement)) {
            $item = strpos($inviteCode, $this->complement[$i]);
            if (!empty($item)) {
                $mixed = $item;
                break;
            }
            $i++;
        }
        
        /** Character mapping decryption */
        $encode = 0;
        for ($i = 0; $i < $mixed; $i++) {
            $index = $dictionaries[$inviteCode[$i]];
            $encode += pow($this->length, $i) * $index;
        }
        return $encode;
    }
    
    /**
     * @return string[]
     */
    public function getDictionaries(): array
    {
        return $this->dictionaries;
    }
    
    /**
     * @param string[] $dictionaries
     */
    public function setDictionaries(array $dictionaries): void
    {
        $this->dictionaries = $dictionaries;
    }
    
    /**
     * @return array
     */
    public function getComplement(): array
    {
        return $this->complement;
    }
    
    /**
     * @param array $complement
     */
    public function setComplement(array $complement): void
    {
        $this->complement = $complement;
    }
    
    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }
    
    /**
     * @param int $max
     */
    public function setMax(int $max): void
    {
        $this->max = $max;
    }
    
}