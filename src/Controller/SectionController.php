<?php

namespace App\Controller;

use App\Authentication\Authentication;
use App\Repository\StocktakingRepository;
use App\Repository\SectionRepository;
use App\Repository\ItemRepository;
use App\View\View;

class SectionController
{
    private $StocktakingRepo;
    private $SectionRepo;
    private $ItemRepo;

    function __construct()
    {
        $this->StocktakingRepo = new StocktakingRepository();
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

        if(is_numeric($_GET['stocktakingId'])) {
            $stocktaking = $this->StocktakingRepo->readByID($_GET['stocktakingId'], $_SESSION['userID']);
            if ($stocktaking != null) {
                $sections = $this->SectionRepo->readByStocktaking($_GET['stocktakingId'], $_SESSION['userID']);

            foreach($sections as $section) {
                $itemCount = $this->ItemRepo->countItemsBySection($section->id, $_SESSION['userID']);
                if ($itemCount->number != null) {
                    $section->itemCount = $itemCount->number;
                } else {
                    $section->itemCount = 0;
                }
            }

            $view = new View('section/overview');
            $view->title = 'Section Overview';
            $view->heading = 'Section Overview of Stocktaking ' . $_GET['stocktakingId'];
            $view->stocktaking = $this->StocktakingRepo->readByID($_GET['stocktakingId'], $_SESSION['userID']);
            $view->sections = $sections;
            $view->display();
            } else {
                header('Location: /default/error?errorid=1&target=/');
            }
        } else {
            header('Location: /default/error?errorid=2&target=/');
        }
    }

    public function doCreate()
    {
        $request = json_decode(file_get_contents('php://input'));

        if (is_string($request->apiKey) && is_string($request->number) && is_string($request->targetQuantity) && is_string($request->branch) && is_string($request->deviceNumber) && is_string($request->stocktaking)) {
            $user = Authentication::authenticateApiKey($request->apiKey);
            if ($user != null) {
                if ($this->StocktakingRepo->readByID($request->stocktaking, $user->id) != null) {
                    if ($this->SectionRepo->readByNumberAndStocktaking($request->number, $request->stocktaking, $user->id) == null) {
                        $this->SectionRepo->create($request->number, $request->targetQuantity, $request->branch, $request->deviceNumber, $request->stocktaking, $user->id);
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

    public function doGetSections() {
        $request = json_decode(file_get_contents('php://input'));

        if (is_string($request->apiKey) && is_string($request->stocktaking)) {
            $user = Authentication::authenticateApiKey($request->apiKey);
            if ($user != null) {
                $stocktaking = $this->StocktakingRepo->readByID($request->stocktaking, $user->id);
                if ($stocktaking != null) {
                    echo json_encode($this->SectionRepo->readByStocktaking($stocktaking->id, $user->id));
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
            $section = $this->SectionRepo->readByID($_GET['id'], $_SESSION['userID']);
            if ($section != null) {
                $this->SectionRepo->deleteById($_GET['id'], $_SESSION['userID']);
                header('Location: /section/overview?id=' . $section->stocktakingId);
            } else {
                header('Location: /default/error?errorid=1&target=/');
            }
        } else {
            header('Location: /default/error?errorid=2&target=/');
        }
    }
}