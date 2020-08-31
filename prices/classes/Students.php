<?php

class Students extends ObjectModel
{
    public $id_students;
    public $name;
    public $bday_date;
    public $is_studying = true;
    public $avg_score;

    public static $definition = array(
        'table' => 'students',
        'primary' => 'id_students',
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'bday_date' => array('type' => self::TYPE_DATE),
            'is_studying' => array('type' => self::TYPE_BOOL),
            'avg_score' => array('type' => self::TYPE_FLOAT),
        ),
    );

    public function __construct($idLang = null, $idShop = null)
    {
        parent::__construct($idLang, $idShop);
    }

    public function getAllNames()
    {
        $return = Db::getInstance()->executeS(
            "SELECT `name` FROM `" . _DB_PREFIX_ . "students`");
        return $return;
    }

    public function getBestScore()
    {
        $return = Db::getInstance()->getValue("SELECT MAX(avg_score) FROM `" . _DB_PREFIX_ . "students`");
        return $return;
    }

    public function getBestStudent()
    {
        $return = Db::getInstance()->getValue("SELECT `*` FROM `" . _DB_PREFIX_ . "students` st
        INNER JOIN (
            SELECT `id_student`, MAX(avg_score) FROM `" . _DB_PREFIX_ . "students`
            GROUP BY `id_students`
            ) st2 ON `st.id_students` = `st2.id_students`");
        return $return;
    }
}