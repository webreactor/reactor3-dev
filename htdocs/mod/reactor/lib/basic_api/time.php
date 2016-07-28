<?php

function tsToDate($time_stamp)
{
    return date(REACTOR_FORMAT_DATE, $time_stamp);
}

function tsToDateTime($time_stamp)
{
    return date(REACTOR_FORMAT_DATETIME, $time_stamp);
}
