<?php

class AdminTestController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
    }

    public function initContent()
    {
        include(_PS_MODULE_DIR_.'prices'.DIRECTORY_SEPARATOR.'/classes/Students.php');
        $students = new Students;
        $students->name = 'Andrey';
        $students->avg_score = 5;
        $students->add();
        $names = $students->getAllNames();
        $avg_score = $students->getBestScore();
        $this->context->smarty->assign(
            array('names'=> $names,
            'avg_score' => $avg_score,
            )
        );
        $this->content .= $this->context->smarty->fetch('../modules/prices/views/templates/admin/test.tpl');
        parent::initContent();

    }
}