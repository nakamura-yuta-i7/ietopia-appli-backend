<?php

/**
 * Labeled Tab-separated Values Parser / Dumper
 *
 * @see http://ltsv.org/
 */
class Ltsv
{
    /**
     * Convert array to LTSV
     *
     * @param array $hash hash
     * @return string LTSV string
     */
    public static function encode(array $hash)
    {
        $result = array();

        foreach ($hash as $k => $v) {
            if (is_array($v) || is_resource($v) || is_object($v) || $k === '' || strpos($k, ':') !== false) {
                throw new \InvalidArgumentException();
            }
            $result[] = "{$k}:{$v}";
        }
        return implode("\t", $result);
    }

    /**
     * Convert LTSV to array
     *
     * @param string $ltsv LTSV string
     * @return array hash
     */
    public static function decode($ltsv)
    {
        $hash = array();
        $splited = explode("\t", $ltsv);
        foreach ($splited as $col) {
            $splited = explode(':', $col, 2);
            if (count($splited) < 2) {
                throw new \RuntimeException('parse error');
            }
            list($k, $v) = $splited;
            $hash[$k] = $v;
        }
        return $hash;
    }
}
