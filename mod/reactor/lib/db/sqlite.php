<?php

//ver 1.1

class DB_sqlite {
    var $link;
    var $rez;
    var $err;
    var $sql;

    function DB_sqlite() {
        $this->link = sqlite_open(SITE_DIR . DB_BAZA, 0666, $sqliteerror);
    }

    function sql($z) {
        GLOBAL $_log, $_reactor;
        if (REACTOR_DEBUG_SQL == 1) {
            $t = microtime();
            $tt = time();
        }
        $this->rez = sqlite_unbuffered_query($this->link, $z);
        $this->sql = $z;
        if (!$this->rez) {
            $_log .= ' SQL ERROR ' . $z;
            trigger_error('SQL ERROR ' . $z . ' in ' . $_reactor['module']['name'] . ' ' . sqlite_error_string(sqlite_last_error($this->link)),
                E_USER_ERROR);

            if (REACTOR_DEBUG_SQL == 1) {
                echo "<b style='color:red'> " . (time() - $tt) . ':' . (microtime() - $t) . ": SQL</b> ", $z, '<br>';
            }
        } else {
            if (REACTOR_DEBUG_SQL == 1) {
                echo "<b>" . (time() - $tt) . ':' . (microtime() - $t) . ": SQL</b> ", $z, '<br>';
            }
        }

        return $this->rez;
    }

    function line($t = 1) {
        if ($l = @sqlite_fetch_array($this->rez, $t)) {
            return $l;
        } else {
            return 0;
        }
    }

//assign by Baader
    function matr($a = 1) {
        $r = array();
        while ($t = @sqlite_fetch_array($this->rez, $a)) {
            if ($t != 0) {
                $r[] = $t;
            }
        }
        if (!isset($r)) {
            $r = 0;
        }

        return $r;
    }

    function close() {
        sqlite_close($this->link);
    }

    function last_id() {
        return sqlite_last_insert_rowid($this->link);
    }

    function affected_rows() {
        return sqlite_changes($this->link);
    }

    function pages($sql, $p, $by, &$all) {
        $sql = 'select SQL_CALC_FOUND_ROWS' . substr($sql, 6);
        if ($p == -1) {
            $p = ceil($all / $by);
        }
        if ($p == 0) {
            $this->sql($sql);
        } else {
            $from = ($p - 1) * $by;
            $this->sql($sql . ' limit ' . $from . ',' . $by);
        }

        $rez = $this->matr();
        $this->sql('SELECT FOUND_ROWS() as count');
        $all = $this->line();
        $all = ceil($all['count'] / $by);

        return $rez;
    }

    function pagess($sql, $p, $by, &$all) {
        if ($p == -1) {
            $p = ceil($all / $by);
        }
        if ($p == 0) {
            $this->sql($sql);
        } else {
            $from = ($p - 1) * $by;
            $this->sql($sql . ' limit ' . $from . ',' . $by);
        }
        $t = strpos($sql, 'from');
        $sql = substr($sql, $t);
        $rez = $this->matr();
        $this->sql('SELECT count(*) as count ' . $sql);
        $all = $this->line();
        $all = ceil($all['count'] / $by);

        return $rez;
    }

    function sql_stream($str) {
        $str = $this->query_parser($str);
        foreach ($str as $item) {
            $this->sql($item);
        }
    }

    function query_parser($q) {
        // strip the comments from the query
        while ($n = strpos($q, '--')) {
            $k = @strpos($q, "\n", $n + 1);
            if (!$k) {
                $k = strlen($q);
            }
            $q = substr($q, 0, $n) . substr($q, $k + 1);
        }

        $n = strlen($q);
        $k = 0;
        $queries = array();
        $current_delimiter = '';

        for ($i = 0; $i < $n; $i++) {
            // if this slash escapes something,
            // current delimiter must not be affected
            if ($q[$i] == '\\' &&
                ($q[$i + 1] == '\\' || $q[$i + 1] == "'" || $q[$i + 1] == '"')
            ) {
                $queries[$k] .= $q[$i] . $q[$i + 1];
                $i++;
            } else {

                if ($q[$i] == $current_delimiter) {
                    $current_delimiter = '';
                } elseif ($q[$i] == '`' || $q[$i] == "'" || $q[$i] == '"') {
                    $current_delimiter = $q[$i];
                }

                if ($q[$i] == ';' && $current_delimiter == '') {
                    $queries[$k] = trim($queries[$k]);
                    if (trim(substr($q, $i), "\r \n;") != '') {
                        $k++;
                    }
                } else {
                    @$queries[$k] .= $q[$i];
                }
            }
        }

        return $queries;
    }
}//class end

?>