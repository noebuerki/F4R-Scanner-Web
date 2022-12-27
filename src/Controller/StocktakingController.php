<?php

namespace App\Controller;

use App\Authentication\Authentication;
use App\Repository\ItemRepository;
use App\Repository\SectionRepository;
use App\Repository\StocktakingRepository;
use App\View\View;

class StocktakingController
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

        $view = new View('stocktaking/overview');
        $view->title = 'Stocktaking Overview';
        $view->heading = 'Stocktaking Overview';
        $view->stocktakings = $this->StocktakingRepo->readAll($_SESSION['userID']);
        $view->display();
    }

    public function create() {
        Authentication::restrictAuthenticated();

        $view = new View('stocktaking/create');
        $view->title = 'Create Stocktaking';
        $view->heading = 'Create Stocktaking';
        $view->display();
    }

    public function doCreate()
    {
        Authentication::restrictAuthenticated();

        if (is_string($_POST['date']) && is_numeric($_POST['branch'])) {
            $this->StocktakingRepo->create($_POST['date'], $_POST['branch'], $_SESSION['userID']);
            header('Location: /stocktaking/overview');
        } else {
            header('Location: /default/error?errorid=2&target=/');
        }
    }

    public function doGetStocktakings() {
        $request = json_decode(file_get_contents('php://input'));

        if (is_string($request->apiKey)) {
            $user = Authentication::authenticateApiKey($request->apiKey);
            if ($user != null) {
                echo json_encode($this->StocktakingRepo->readAll($user->id));
            } else {
                echo 'forbidden';
            }
        } else {
            echo 'incomplete';
        }
    }

    public function doExport() {
        Authentication::restrictAuthenticated();

        if(is_numeric($_GET['id'])) {
            $stocktaking = $this->StocktakingRepo->readByID($_GET['id'], $_SESSION['userID']);

            if ($stocktaking != null) {

                date_default_timezone_set('Europe/Zurich');
                $date = date('d.m.Y', time());
                $time = date('h:i:s', time());

                $text = "RT00\n1\t\t$date\t$time\t2\t\nRT38\n";

                $sections = $this->SectionRepo->readAll($_SESSION['userID']);
                foreach($sections as $section) {
                    $items = $this->ItemRepo->readBySection($section->id, $_SESSION['userID']);
        
                    foreach($items as $item) {
                        $text .= "$item->barcode\t0\t$stocktaking->branch\t$stocktaking->date\t$section->number\t$item->position\t1,00\t0\t0\t\t0\t0\t0\t0\t0\t\t\n";
                    }
                }
        
                $text .= "\n";
        
                $userId = $_SESSION['userID'];
                $path = "export-$userId.txt";
                $file = fopen($path, "w") or die("Unable to open file!");
                fwrite($file, $text);
                fclose($file);
        
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename='.basename($path));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($path));
                header("Content-Type: text/plain");
                readfile($path);
            } else {
                echo 'no stocktaking';
            }
        } else {
            echo 'no argument';
        }
    }

    public function doDelete()
    {
        Authentication::restrictAuthenticated();

        if (is_int($_GET['id'])) {
            if ($this->StocktakingRepo->readByID($_GET['id'], $_SESSION['userID']) != null) {
                $this->StocktakingRepo->deleteById($_GET['id'], $_SESSION['userID']);
                header('Location: /stocktaking/overview');
            } else {
                header('Location: /default/error?errorid=1&target=/');
            }
        } else {
            header('Location: /default/error?errorid=2&target=/');
        }
    }
}