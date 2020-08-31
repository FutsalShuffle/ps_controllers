<?php

class Test extends ObjectModel
{
    public $string;

    public static $definition = array(
        'table' => 'testmodule',
        'primary' => 'id_testmodule',
        'fields' => array(
            'string' => array('type' => self::TYPE_STRING,),
        ),
    );

    public function __construct($idLang = null, $idShop = null)
    {
        parent::__construct($idLang, $idShop);
    }

    public function getAll()
    {
        $query = Db::getInstance()->executeS(
            "SELECT *
            FROM `" . _DB_PREFIX_ . "testmodule`");
        return $query;
    }

}