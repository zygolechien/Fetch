<?php

/*
 * This file is part of the Fetch package.
 *
 * (c) Robert Hafner <tedivm@tedivm.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fetch;

/**
 * This library is a wrapper around the Imap library functions included in php.
 *
 * @package Fetch
 * @author  Robert Hafner <tedivm@tedivm.com>
 * @author  Sergey Linnik <linniksa@gmail.com>
 */
final class MIME
{
    /**
     * @param string $text
     * @param string $targetCharset
     *
     * @return string
     */
    public static function decode($text, $targetCharset = 'utf-8')
    {
        if (null === $text) {
            return null;
        }

        $result = '';

        foreach (imap_mime_header_decode($text) as $word) {
            if($word->charset == 'default') {
                $word->charset = 'ascii';
            }
            $result .= MIME::convertStringEncoding($word->text, $word->charset, $targetCharset);
            }

        return $result;
    }

    /**
     * Converts a string from one encoding to another.
     * @param string $string
     * @param string $fromEncoding
     * @param string $toEncoding
     * @return string Converted string if conversion was successful, or the original string if not
     */
    protected static function convertStringEncoding($string, $fromEncoding, $toEncoding) {
        $convertedString = null;
        if($string && $fromEncoding != $toEncoding) {
            $convertedString = @iconv($fromEncoding, $toEncoding . '//IGNORE', $string);
            if(!$convertedString && extension_loaded('mbstring')) {
                $convertedString = @mb_convert_encoding($string, $toEncoding, $fromEncoding);
            }
        }
        return $convertedString ?: $string;
    }
}
