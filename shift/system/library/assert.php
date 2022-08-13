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
     * @param  mixed $value
     * @param  bool  $exception Directly throw exception on invalid assert
     *
     * @return bool
     */
    public function check($value, $exception = false): bool
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
            if ($exception) {
                throw new \Shift\System\Exception\AssertException($e->getMessage());
            }
        }

        $this->rules = [];

        return $valid;
    }
}
