<?php
namespace CoreBundle\Controller;

class UcController extends Controller
{
    public function checkAction()
    {
        $this->get('core.uc')->check();
        die();
    }
}