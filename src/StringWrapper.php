<?php

namespace Dgame\Wrapper;

use Dgame\Wrapper\Optional\Optional;

/**
 * Class StringWrapper
 * @package Dgame\Wrapper
 */
final class StringWrapper
{
    /**
     * @var string
     */
    private $input;

    /**
     * StringWrapper constructor.
     *
     * @param string|null $input
     */
    public function __construct(string $input = null)
    {
        $this->input = $input;
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->input ?? '';
    }

    /**
     * @param string   $delimeter
     * @param int|null $limit
     *
     * @return ArrayWrapper
     */
    public function explode(string $delimeter, int $limit = null): ArrayWrapper
    {
        return new ArrayWrapper(explode($delimeter, $this->input, $limit));
    }

    /**
     * @param string   $pattern
     * @param int|null $limit
     *
     * @return ArrayWrapper
     */
    public function split(string $pattern, int $limit = null): ArrayWrapper
    {
        return new ArrayWrapper(preg_split($pattern, $this->input, $limit, PREG_SPLIT_NO_EMPTY));
    }

    /**
     * @param int $length
     *
     * @return ArrayWrapper
     */
    public function chunks(int $length): ArrayWrapper
    {
        return new ArrayWrapper(str_split($this->input, $length));
    }

    /**
     * @return ArrayWrapper
     */
    public function chars(): ArrayWrapper
    {
        return $this->chunks(1);
    }

    /**
     * @param int         $length
     * @param string|null $end
     *
     * @return StringWrapper
     */
    public function chunkSplit(int $length, string $end = null): StringWrapper
    {
        return new self(chunk_split($this->input, $length, $end));
    }

    /**
     * @param string $prefix
     *
     * @return bool
     */
    public function beginsWith(string $prefix): bool
    {
        return substr($this->input, 0, strlen($prefix)) === $prefix;
    }

    /**
     * @param string $suffix
     *
     * @return bool
     */
    public function endsWith(string $suffix): bool
    {
        return substr($this->input, -strlen($suffix)) === $suffix;
    }

    /**
     * @param string $prefix
     *
     * @return StringWrapper
     */
    public function prefix(string $prefix): StringWrapper
    {
        $this->input = $prefix . $this->input;

        return $this;
    }

    /**
     * @param string $suffix
     *
     * @return StringWrapper
     */
    public function suffix(string $suffix): StringWrapper
    {
        $this->input .= $suffix;

        return $this;
    }

    /**
     * @param string $pattern
     * @param array  $matches
     *
     * @return bool
     */
    public function matches(string $pattern, array &$matches = []): bool
    {
        return preg_match($pattern, $this->input, $matches) === 1;
    }

    /**
     * @return StringWrapper
     */
    public function toUpper(): StringWrapper
    {
        return new self(strtoupper($this->input));
    }

    /**
     * @return StringWrapper
     */
    public function toLower(): StringWrapper
    {
        return new self(strtolower($this->input));
    }

    /**
     * @return StringWrapper
     */
    public function toUpperFirst(): StringWrapper
    {
        return new self(ucfirst($this->input));
    }

    /**
     * @return StringWrapper
     */
    public function toLowerFirst(): StringWrapper
    {
        return new self(lcfirst($this->input));
    }

    /**
     * @param string|null $mask
     *
     * @return StringWrapper
     */
    public function trim(string $mask = null): StringWrapper
    {
        if ($mask === null) {
            $this->input = trim($this->input);
        } else {
            $this->input = trim($this->input, $mask);
        }

        return $this;
    }

    /**
     * @param string|null $mask
     *
     * @return StringWrapper
     */
    public function leftTrim(string $mask = null): StringWrapper
    {
        if ($mask === null) {
            $this->input = ltrim($this->input);
        } else {
            $this->input = ltrim($this->input, $mask);
        }

        return $this;
    }

    /**
     * @param string|null $mask
     *
     * @return StringWrapper
     */
    public function rightTrim(string $mask = null): StringWrapper
    {
        if ($mask === null) {
            $this->input = rtrim($this->input);
        } else {
            $this->input = rtrim($this->input, $mask);
        }

        return $this;
    }

    /**
     * @param int      $start
     * @param int|null $length
     *
     * @return StringWrapper
     */
    public function substring(int $start, int $length = null): StringWrapper
    {
        return new self(substr($this->input, $start, $length ?? $this->length()));
    }

    /**
     * @param string $needle
     * @param bool   $beforeNeedle
     *
     * @return StringWrapper
     */
    public function fromFirstOccurenceOf(string $needle, bool $beforeNeedle = false): StringWrapper
    {
        return new self(strstr($this->input, $needle, $beforeNeedle));
    }

    /**
     * @param string $needle
     *
     * @return StringWrapper
     */
    public function fromLastOccurenceOf(string $needle): StringWrapper
    {
        return new self(strchr($this->input, $needle));
    }

    /**
     * @param string   $needle
     * @param int|null $pos
     *
     * @return bool
     */
    public function contains(string $needle, int &$pos = null): bool
    {
        return $this->firstPositionOf($needle)->isSome($pos);
    }

    /**
     * @param string $needle
     * @param int    $offset
     *
     * @return Optional
     */
    public function firstPositionOf(string $needle, int $offset = 0): Optional
    {
        return ($pos = strpos($this->input, $needle, $offset)) === false ? none() : some($pos);
    }

    /**
     * @param string $needle
     * @param int    $offset
     *
     * @return Optional
     */
    public function lastPositionOf(string $needle, int $offset = 0): Optional
    {
        return ($pos = strrpos($this->input, $needle, $offset)) === false ? none() : some($pos);
    }

    /**
     * @param string $value
     *
     * @return StringWrapper
     */
    public function before(string $value): StringWrapper
    {
        if ($this->firstPositionOf($value)->isSome($index)) {
            return $this->substring(0, $index);
        }

        return $this;
    }

    /**
     * @param string $value
     *
     * @return StringWrapper
     */
    public function after(string $value): StringWrapper
    {
        if ($this->firstPositionOf($value)->isSome($index)) {
            return $this->substring($index + strlen($value));
        }

        return new self();
    }

    /**
     * @param string $value
     *
     * @return StringWrapper
     */
    public function from(string $value): StringWrapper
    {
        if ($this->firstPositionOf($value)->isSome($index)) {
            return $this->substring($index);
        }

        return new self();
    }

    /**
     * @param string $value
     *
     * @return StringWrapper
     */
    public function until(string $value): StringWrapper
    {
        if ($this->firstPositionOf($value)->isSome($index)) {
            return $this->substring(0, $index);
        }

        return $this;
    }

    /**
     * @param string $input
     *
     * @return StringWrapper
     */
    public function default(string $input): StringWrapper
    {
        if ($this->isEmpty()) {
            $this->input = $input;
        }

        return $this;
    }

    /**
     * @param int $index
     *
     * @return Optional
     */
    public function at(int $index): Optional
    {
        if ($index >= 0 && $index < $this->length()) {
            return some($this->input[$index]);
        }

        return none();
    }

    /**
     * @return StringWrapper
     */
    public function reverse(): StringWrapper
    {
        return new self(strrev($this->input));
    }

    /**
     * @return int
     */
    public function length(): int
    {
        return strlen($this->input);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->input);
    }

    /**
     * @return bool
     */
    public function isNull(): bool
    {
        return $this->input === null;
    }

    /**
     * @param array $replacement
     *
     * @return StringWrapper
     */
    public function replace(array $replacement): StringWrapper
    {
        return new self(str_replace(array_keys($replacement), array_values($replacement), $this->input));
    }

    /**
     * @param int $times
     *
     * @return StringWrapper
     */
    public function repeat(int $times): StringWrapper
    {
        return new self(str_repeat($this->input, $times));
    }

    /**
     * @param array ...$args
     *
     * @return StringWrapper
     */
    public function format(...$args): StringWrapper
    {
        if (!empty($args)) {
            $this->input = sprintf($this->input, ...$args);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function encode(): string
    {
        return htmlspecialchars($this->input);
    }

    /**
     * @param string $input
     *
     * @return bool
     */
    public function isEqualTo(string $input): bool
    {
        return $this->input === $input;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->input;
    }
}