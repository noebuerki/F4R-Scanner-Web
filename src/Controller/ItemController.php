<?php

namespace App\Controller;

use App\Authentication\Authentication;
use App\Repository\ItemRepository;
use App\Repository\SectionRepository;
use App\View\View;

class ItemController
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

    public function overview()
    {
        Authentication::restrictAuthenticated();

        if (is_numeric($_GET['sectionId'])) {
            $section = $this->SectionRepo->readByID($_GET['sectionId'], $_SESSION['userID']);
            if ($section != null) {
                $items = $this->ItemRepo->readBySection($section->id, $_SESSION['userID']);

                $view = new View('item/overview');
                $view->title = 'Item Overview';
                $view->heading = 'Item Overview of Section ' . $section->number;
                $view->section = $section;
                $view->items = $items;
                $view->display();
            } else {
                header('Location: /default/error?errorid=2&target=/');
            }
        } else {
            header('Location: /default/error?errorid=2&target=/');
        }
    }

    public function detail() {
        Authentication::restrictAuthenticated();

        if (is_numeric($_GET['id'])) {
            $item = $this->ItemRepo->readByID($_GET['id'], $_SESSION['userID']);
            if ($item != null) {

                $view = new View('item/detail');
                $view->title = 'Item Detail';
                $view->heading = 'Item Detail';
                $view->item = $item;
                $view->display();
            } else {
                header('Location: /default/error?errorid=2&target=/');
            }
        } else {
            header('Location: /default/error?errorid=2&target=/');
        }
    }

    public function doCreate()
    {
        $request = json_decode(file_get_contents('php://input'));

        if (is_string($request->apiKey) && is_string($request->position) && is_string($request->barcode) && is_string($request->section) && is_string($request->stocktaking)) {
            $user = Authentication::authenticateApiKey($request->apiKey);
            if ($user != null) {
                $section = $this->SectionRepo->readByNumberAndStocktaking($request->section, $request->stocktaking, $user->id);

                if ($section != null) {
                    $item = $this->ItemRepo->readByPosition($request->position, $section->id, $user->id);

                    if ($item == null) {
                        $this->ItemRepo->create($request->position, $request->barcode, $section->id, $user->id);
                    } else {
                        echo 'taken';
                    }
                } else {
                    echo 'non-existent';
                }
            } else {
                echo 'forbidden';
            }
        } else {
            echo 'incomplete';
        }
    }

    public function doDelete()
    {
        Authentication::restrictAuthenticated();

        if (is_numeric($_GET['id'])) {
            $item = $this->ItemRepo->readByID($_GET['id'], $_SESSION['userID']);
            if ($item != null) {
                $this->ItemRepo->deleteById($_GET['id'], $_SESSION['userID']);
                header('Location: /item/overview?sectionId=' . $item->sectionId);
            } else {
                header('Location: /default/error?errorid=1&target=/');
            }
        } else {
            header('Location: /default/error?errorid=2&target=/');
        }
    }
}