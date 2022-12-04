<?php

namespace App\Controller;

use App\Authentication\Authentication;
use App\Repository\ItemRepository;
use App\Repository\SectionRepository;
use App\View\View;

class SectionController
{
    private $SectionRepo;
    private $ItemRepo;

    function __construct()
    {
        $this->SectionRepo = new SectionRepository();
        $this->ItemRepo = new ItemRepository();
    }

    public function index()
    {
        $this->overview();
    }

    public function overview() {
        Authentication::restrictAuthenticated();

        if(is_int($_GET[`sectionId`])) {
            $section = $this->SectionRepo->readByID($_GET[`sectionId`], $_SESSION[`userID`]);

            if($section != null) {
                $items = $this->ItemRepo->readBySection($section->id, $_SESSION['userID']);

                $view = new View('item/overview');
                $view->title = 'Item Overview';
                $view->heading = 'Item Overview of Section ' . $section->number; 
                $view->items = $items;
                $view->display();
            } else {
                header('Location: /default/error?errorid=2&target=/item/overview');
            }
        } else {
            header('Location: /default/error?errorid=2&target=/item/overview');
        }
    }

    public function doCreate() {
        if(Authentication::authenticateApiKey($_POST['apiKey'])) {
            if(is_int($_POST['position']) && is_int($_POST['sectionNumber']) && is_string($_POST['barcode']) && is_int($_POST['scanner'])) {
                $section = $this->SectionRepo->readByNumber($_POST['number'], $_SESSION['userID']);
                
                if($section != null) {
                    $this->ItemRepo->create($_POST['position'], $section->id, $_POST['barcode'], $_SESSION['userID']);
                } else {
                    echo 'non-existent';
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
            if($this->ItemRepo->readByID($_POST['id'], $_SESSION['userID']) != null) {
                $this->ItemRepo->deleteById($_POST['id'], $_SESSION['userID']);
                header('Location: /item/overview');
            } else {
                header('Location: /default/error?errorid=1&target=/item/overview');
            }
        } else {
            header('Location: /default/error?errorid=2&target=/item/overview');
        }
    }
}