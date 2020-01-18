<?php
namespace FilippoFinke;

class RouteParser
{
    private const PATTERN_START = '{';
    private const PATTERN_END = '}';
    private const REGEX_START = ':';

    private $pattern;

    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    public function parse($url)
    {
        $results = [];
        $uIndex = 0;
        $rIndex = 0;
        $parsing = true;
        while ($parsing) {
            if (!isset($this->pattern[$rIndex])) {
                break;
            }

            $rChar = $this->pattern[$rIndex];
            if ($rChar === self::PATTERN_START) {
                $key = "";
                $value = "";

                $regex = "";
                $hasRegex = false;

                $endChar = null;

                /**
                 * TO OPTIMIZE
                 */
                for ($i = $rIndex + 1; $i < strlen($this->pattern); $i++) {
                    $char = $this->pattern[$i];
                    if ($char === self::PATTERN_END) {
                        $rIndex = $i + 1;
                        $endChar = $this->pattern[$i + 1] ?? null;
                        break;
                    } elseif ($char == self::REGEX_START) {
                        $hasRegex = true;
                    } else if($hasRegex) {
                        $regex .= $char;
                    } else {
                        $key .= $char;
                    }
                }

                for ($i = $uIndex; $i < strlen($url); $i++) {
                    $char = $url[$i];
                    if ($char === $endChar) {
                        $uIndex = $i;
                        break;
                    } else {
                        $value .= $char;
                    }
                }

                if($hasRegex && !preg_match('/'.$regex.'/', $value)) {
                    return false;
                }

                $results[$key] = $value;
            } elseif (!isset($url[$uIndex]) || !isset($this->pattern[$rIndex]) || $this->pattern[$rIndex] != $url[$uIndex]) {
                return false;
            }

            $rIndex++;
            $uIndex++;
        }
        return (count($results) > 0)?$results:true;
    }
}
