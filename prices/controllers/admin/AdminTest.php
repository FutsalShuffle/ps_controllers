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
        parent::initContent();
        include(_PS_MODULE_DIR_.'prices'.DIRECTORY_SEPARATOR.'/classes/Students.php');
        $students = new Students;
        // $lol1->name = 'Andrey';
        // $lol1->avg_score = 1.6;
        // $lol1->add();
        $names = $students->getAllNames();
        $avg_score = $students->getBestScore();
        $this->context->smarty->assign(
            array('names'=> $names)
        );
        $this->context->smarty->assign(
            array('avg_score' => $avg_score)
        );
        $this->setTemplate('test.tpl');
    }
}