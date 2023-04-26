<?php
function replace_space_with_underline($string): array|string
{
    return str_replace(' ' , '_' , $string);
}
