<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty number_text modifier plugin
 *
 * Type:     modifier<br>
 * Name:     number_text<br>
 * Purpose:  format numer to string
 * @link http://smarty.php.net/brokenlink
 * @author   Brau <soncco at gmail dot com>
 * @param string
 * @param boolear
 * @return string
 */
function smarty_modifier_number_text($numero, $moneda = true)
{
    return convertir($numero, $moneda);
}

/* vim: set expandtab: */

?>
