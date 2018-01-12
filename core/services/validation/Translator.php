<?php
namespace Core\Services\Validation;


class Translator implements  \Illuminate\Contracts\Translation\Translator
{

    /**
     * Get the translation for a given key.
     *
     * @param  string $key
     * @param  array $replace
     * @param  string $locale
     * @return mixed
     */
    public function trans($key, array $replace = [], $locale = null)
    {
        // TODO: Implement trans() method.
    }

    /**
     * Get a translation according to an integer value.
     *
     * @param  string $key
     * @param  int|array|\Countable $number
     * @param  array $replace
     * @param  string $locale
     * @return string
     */
    public function transChoice($key, $number, array $replace = [], $locale = null)
    {
        // TODO: Implement transChoice() method.
    }

    /**
     * Get the default locale being used.
     *
     * @return string
     */
    public function getLocale()
    {
        // TODO: Implement getLocale() method.
    }

    /**
     * Set the default locale.
     *
     * @param  string $locale
     * @return void
     */
    public function setLocale($locale)
    {
        // TODO: Implement setLocale() method.
    }
}