<?php

function close()
{
    global $_log, $_mctime, $_log_t;

    if (strpos($_log_t, '/jsonp_request/?interface=basket&action=handler&operation=refresh&') !== false) {
        return;
    }
    if (strpos($_log_t, '/jsonp_request/?interface=catalog_desc_comment&action=get_comment_data&') !== false) {
        return;
    }
    if (strpos($_log_t, '/jsonp_request/?interface=catalog_desc_compare&action=getList&') !== false) {
        return;
    }
    if (strpos($_log_t, '/crossdomain/?t=') !== false) {
        return;
    }

    error_log($_log_t . ' | ' . (microtime(true) - $_mctime) . $_log);
}
