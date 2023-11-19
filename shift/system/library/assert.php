<?php

declare(strict_types=1);

namespace Shift\System\Library;

use Webmozart\Assert\Assert as WebmozartAssert;

/**
 * Non-breaking chain validation with Webmozart Assert.
 *
 * Omit the `$value` from Webmozart Assert method, and move them no new method `check()`.
 * Example: `$this->assert->alnum()->lengthBetween(5, 20)->startsWithLetter()->check('foo123')`.
 *
 * TODO: list all of the assertion on the link
 *
 * @method $this notEmpty()  Check that a value is not empty()
 *
 * @method $this minLength(int $min)  Check that a string has at least a certain number of characters
 * @method $this maxLength(int $max)  Check that a string has at most a certain number of characters
 * @method $this lengthBetween(int $min, int $max)  Check that a string has a length in the given range
 * @method $this digits()  Check that a string contains digits only
 *
 * @link  https://github.com/webmozarts/assert
 */
class Assert
{
    protected $rules = [];

    public function __call($method, $params = [])
    {
        if (!method_exists($this, $method)) {
            $this->rules[$method] = $params;
        }

        return $this;
    }

    /**
     * Validate given input.
     *
     * @param  mixed  $value
     * @param  $this  $exception  Directly throw exception on invalid assert
     * @return bool
     */
    public function check(mixed $value, bool $throwException = false): bool
    {
        $valid = false;

        try {
            foreach ($this->rules as $method => $params) {
                call_user_func_array(
                    [WebmozartAssert::class, $method],
                    array_merge([$value], $params)
                );
            }

            $valid = true;
        } catch (\Webmozart\Assert\InvalidArgumentException $e) {
            if ($throwException) {
                throw new \Shift\System\Exception\AssertException($e->getMessage());
            }
        }

        $this->rules = [];

        return $valid;
    }
}
