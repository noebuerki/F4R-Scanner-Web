<?php

namespace App\Controller;

use App\Authentication\Authentication;
use App\Repository\SectionRepository;
use App\View\View;

class SectionController
{
    private $SectionRepo;

    function __construct()
    {
        $this->SectionRepo = new SectionRepository();
    }

    public function index()
    {
        $this->overview();
    }

    public function overview() {
        Authentication::restrictAuthenticated();

        $sections = $this->SectionRepo->readAll($_SESSION['userID']);

        $view = new View('section/overview');
        $view->title = 'Section Overview';
        $view->heading = 'Section Overview';
        $view->sections = $sections;
        $view->display();
    }

    public function doCreate() {
        if(Authentication::authenticateApiKey($_POST['apiKey'])) {
            if(is_int($_POST['number']) && is_int($_POST['targetNumber']) && is_int($_POST['scanner'])) {
                if($this->SectionRepo->readByNumber($_POST['number'], $_SESSION['userID']) == null) {
                    $this->SectionRepo->create($_POST['number'], $_POST['targetNumber'], $_POST['scanner'], $_SESSION['userID']);
                } else {
                    echo 'taken';
                }
            } else {
                echo 'incomplete';
            }
        } else {
            echo 'forbidden';
        }
    }

    public function doDelete() {
        Authentication::restrictAuthenticated();

        if(is_int($_POST['id'])) {
            if($this->SectionRepo->readByID($_POST['id'], $_SESSION['userID']) != null) {
                $this->SectionRepo->deleteById($_POST['id'], $_SESSION['userID']);
                header('Location: /section/overview');
            } else {
                header('Location: /default/error?errorid=1&target=/section/overview');
            }
        } else {
            header('Location: /default/error?errorid=2&target=/section/overview');
        }
    }
}