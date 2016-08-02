<?php

namespace reactor;

class basic_tree extends basic_object
{
    public $img;
    public $data;

    function __construct($table = '', $pkey = '', $fkey = '', $order = '')
    {
        parent::__construct($table, $pkey, $order);

        $this->fkey = $fkey;

        $this->onRestore();
    }

    function configure($table, $fk_value = 0, $order = '', $img_w = '')
    {

        basic_object::configure($table, $fk_value, $order);

        $this->createImage($img_w);
    }

    function moveNode($node_key, $new_parent)
    {
        $this->_db->update(
            $this->table,
            array($this->fkey => $new_parent),
            array($this->pkey => $node_key)
        );

        return 1;
    }

    function createImage($d = '', $rows = '*')
    {
        $t = '';

        if ($d != '') {
            $t .= 'WHERE ' . $d;
        }

        if ($this->order != '') {
            $t .= ' ORDER BY ' . $this->order;
        }

        if ($rows != '*') {
            if (strpos($rows, $this->pkey) === false) {
                $rows .= ',`' . $this->pkey . '`';
            }

            if (strpos($rows, $this->fkey) === false) {
                $rows .= ',`' . $this->fkey . '`';
            }
        }

        $query = $this->_db->sql('SELECT ' . $rows . ' FROM ' . $this->table . ' ' . $t);

        $this->img = $query->matr($this->pkey);

        return 1;
    }

    /**
     * WARNING: FOR USING THIS METHODS YOU HAVE TO CALL createImage() method before
     *
     * @param $row  (path in row name)
     * @param $path (= array(0 => 'root', 1 => 'next'))
     *
     * @return array
     */
    function findPath($row, $path)
    {
        $r          = array();
        $path_level = 0;
        $node_key   = 0;
        $stop       = count($path);
        $do_it      = 1;

        while ($path_level < $stop && $do_it == 1) {
            $do_it = 0;

            foreach ($this->img as $k => $v) {
                if ($v[$this->fkey] == $node_key && $v[$row] == $path[$path_level]) {
                    $r[] = $v;
                    $path_level++;
                    $node_key = $k;
                    $do_it    = 1;

                    if ($path_level == $stop) {
                        break;
                    }
                }
            }
        }

        return $r;
    }

    function delete($node_key, $isStream = 0)
    {
        $query = $this->_db->select($this->table, array($this->pkey => $node_key));

        $t = $query->line();

        if ($t == 0) {
            return 0;
        }

        $keys   = array_keys($this->allChildNodes($node_key));
        $keys[] = $node_key;

        $this->_db->sql(
            'DELETE FROM ' . $this->table . '
            WHERE :pkey IN ("' . implode('","', $keys) . '")',
            array(':pkey' => $this->pkey)
        );

        return $keys;
    }

    /**
     * @param int $node (create tree from this parent node, without parent)
     * @param int $level
     *
     * @return array ("real" tree array)
     */
    function realTree($node = 0, $level = 0)
    {
        if ($level == 0) {
            $level = -1;
        }

        return $this->realTree_r($node, $level);
    }

    function realTree_r($node, $level)
    {
        $r = array();
        if ($level == 0) {
            return $r;
        }
        $level--;
        foreach ($this->img as $k => $v) {
            if ($v[$this->fkey] == $node) {
                $r[$k]['value'] = $v;
                $r[$k]['in']    = $this->realTree_r($k, $level);
            }
        }

        return $r;
    }

    /**
     * @return array (path to node)
     */
    function pathToNode($node_key, $preserve_keys = 1)
    {
        $r = array();
        while (isset($this->img[$node_key])) {
            $r[]      = $this->img[$node_key];
            $node_key = $this->img[$node_key][$this->fkey];
        }

        return array_reverse($r, $preserve_keys);
    }

    function childNodes($parent, $save_keys = false)
    {
        $r = array();

        foreach ($this->img as $k => $v) {
            if ($v[$this->fkey] == $parent) {
                if ($save_keys) {
                    $r[$k] = $v;
                } else {
                    $r[] = $v;
                }
            }
        }

        return $r;
    }

    function allChildNodes($node = 0)
    {
        $this->data = array();

        $this->allChildNodes_r($node);

        $r = $this->data;

        unset($this->data);

        return $r;
    }

    function allChildNodes_r($node = 0)
    {
        foreach ($this->img as $k => $v) {
            if ($v[$this->fkey] == $node) {
                $this->data[$k] = $v;
                $this->allChildNodes_r($k);
            }
        }
    }

    function realArm($node_key, $level = 0)
    {
        $path = $this->pathToNode($node_key);
        if ($level > 0) {
            $path = array_slice($path, 0, $level);
        }

        $r = array();
        $t = &$r;
        foreach ($path as $k => $p) {
            foreach ($this->img as $nk => $node) {
                if ($node[$this->fkey] == $p[$this->fkey]) {
                    $t[$nk]['value'] = $node;
                    $t[$nk]['in']    = array();
                    //if($nk==$k)$tt=&$t[$nk]['in'];
                }
            }
            $t = &$t[$p[$this->pkey]]['in'];
        }

        return $r;
    }

    function realArmUp($node_key, $level = 0)
    {
        foreach ($this->img as $nk => $node) {
            if ($node[$this->fkey] == $node_key) {
                $node_key = $nk;
                break;
            }
        }

        return $this->realArm($node_key, $level);
    }

    function level($l_s, $l_e = 0, $from_node = 0)
    {
        if ($l_e == 0) {
            $l_e = $l_s;
        }
        static $lev = 0;
        $r = array();
        foreach ($this->img as $k => $v) {
            if ($v[$this->fkey] == $from_node) {
                if ($lev >= $l_s && $lev <= $l_e) {
                    $r[$k]['value'] = $v;
                    $lev++;
                    $r[$k]['in'] = $this->level($l_s, $l_e, $k);
                    $lev--;
                }
            }
        }

        return $r;
    }

    function friendNodes($node_key)
    {
        $r  = array();
        $fk = $this->img[$node_key][$this->fkey];
        foreach ($this->img as $k => $item) {
            if ($item[$this->fkey] == $fk) {
                $r[$k] = $item;
            }
        }

        return $r;
    }

    function reindex($level, $childs = '', $k_left = '', $k_right = '')
    {
        $this->_db->update(
            $this->table,
            array(
                $k_left  => 0,
                $k_right => 0,
                $level   => 0,
                $childs  => 0,
            )
        );

        $this->_db->update(
            $this->table,
            array($level => 1),
            array($this->fkey => 0)
        );

        do {
            $query = $this->_db->sql(
                'UPDATE
                    ' . $this->table . ' a,
                    ' . $this->table . ' b
                SET a.`' . $level . '` = b.`' . $level . '` + 1
                WHERE a.`' . $this->fkey . '` = b.`' . $this->pkey . '`
                AND a.`' . $level . '`  = 0
                AND b.`' . $level . '` > 0'
            );
        } while ($query->count() > 0);

        if ($childs == '') {
            return 1;
        }

        $query = $this->_db->sql(
            'SELECT MAX(:level) AS maxlevel
            FROM ' . $this->table,
            array(':level' => $level)
        );

        $line = $query->line();

        $maxlevel = $line['maxlevel'];

        /**
         * first childs
         */

        $this->_db->sql('TRUNCATE TABLE `' . T_REACTOR_TREE_SUP . '`');

        $this->_db->sql(
            'INSERT INTO `' . T_REACTOR_TREE_SUP . '`
            SELECT
                t.`' . $this->fkey . '` AS pk_node,
                COUNT(*) AS childs
            FROM ' . $this->table . ' t
            GROUP BY t.`' . $this->fkey . '`'
        );

        $this->_db->sql(
            'UPDATE
                ' . $this->table . ' a,
                `' . T_REACTOR_TREE_SUP . '` b
            SET a.`' . $childs . '` = b.childs
            WHERE a.`' . $this->pkey . '` = b.pk_node'
        );

        /**
         * all childs
         */

        for ($i = $maxlevel; $i > 1; $i--) {
            $this->_db->sql('TRUNCATE TABLE `' . T_REACTOR_TREE_SUP . '`');

            $this->_db->sql(
                'INSERT INTO ' . T_REACTOR_TREE_SUP . '
                SELECT
                    t.`' . $this->fkey . '` AS pk_node,
                    COUNT(*) + SUM(t.`' . $childs . '`) AS childs
                FROM ' . $this->table . ' t
                WHERE t.`' . $level . '` = ' . $i . '
                GROUP BY t.`' . $this->fkey . '`'
            );

            $this->_db->sql(
                'UPDATE
                    ' . $this->table . ' a,
                    `' . T_REACTOR_TREE_SUP . '` b
                SET a.`' . $childs . '` = b.`' . $childs . '`
                WHERE a.`' . $this->pkey . '` = b.`pk_node`'
            );
        }

        if ($k_left == '' || $k_right == '') {
            return 2;
        }

        /**
         * lefts rights
         */

        $this->_db->sql('set @a:=0');

        $this->_db->sql('set @gr:=0');

        $this->_db->sql(
            'UPDATE ' . $this->table . '
            SET
                `' . $k_left . '` = @a+1,
                `' . $k_right . '` = (@a:=(`' . $k_left . '` + `' . $childs . '` * 2 + 1))
            WHERE `' . $level . '` = 1'
        );

        for ($i = 2; $i <= $maxlevel; $i++) {
            $this->_db->sql(
                'UPDATE
                    ' . $this->table . ' a,
                    ' . $this->table . ' b
                SET a.`' . $k_left . '` = b.`' . $k_left . '` + 1
                WHERE a.`' . $this->fkey . '` = b.`' . $this->pkey . '`
                AND a.`' . $level . '` = ' . $i
            );

            $this->_db->sql(
                'UPDATE ' . $this->table . '
                SET
                    `' . $k_right . '`= @a:=if(`' . $this->fkey . '`=@gr,@a+1,`' . $k_left . '`),
                    `' . $k_right . '`= @gr:=`' . $this->fkey . '`,
                    `' . $k_left . '`= @a,
                    `' . $k_right . '`= @a:=`' . $k_left . '` + `' . $childs . '` * 2 + 1
                WHERE `' . $level . '`=' . $i . '
                ORDER BY `' . $this->fkey . '`'
            );
        }

        return 3;
    }
}
