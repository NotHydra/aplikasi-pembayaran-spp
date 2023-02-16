<?php
function numberToCurrency($number)
{
    return "Rp" . number_format($number, 0, ',', '.');
};
