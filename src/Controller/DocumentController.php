<?php

namespace App\Controller;

use App\Authentication\Authentication;
use App\Repository\CustomerRepository;
use App\Repository\ItemRepository;
use App\View\View;

class DocumentController
{

    private $CustomerRepo;
    private $ItemRepo;

    function __construct()
    {
        $this->CustomerRepo = new CustomerRepository();
        $this->ItemRepo = new ItemRepository();
    }

    public function selectStorageNumber()
    {
        Authentication::restrictAuthenticated();

        if (is_numeric($_GET["customerId"])) {
            $view = new View('document/selectStorageNumber');
            $view->title = 'Select storage number';
            $view->heading = 'Select storage number';
            $view->customerId = $_GET["customerId"];
            $view->storageNumbers = $this->ItemRepo->readStorageNumbersByCustomer($_SESSION["userID"], $_GET["customerId"]);
            $view->display();
        } else {
            header('Location: /default/error?errorid=1&target=/');
        }
    }

    public function invoice()
    {
        Authentication::restrictAuthenticated();

        if (is_numeric($_GET["customerId"])) {
            $customer = $this->CustomerRepo->readByID($_SESSION["userID"], $_GET["customerId"]);
            if ($customer != null) {
                if (is_numeric($_GET["storageNumber"])) {
                    $view = new View("document/invoice");
                    $view->isDocument = true;
                    $view->title = "Invoice";
                    $view->customer = $this->CustomerRepo->readByID($_SESSION["userID"], $_GET["customerId"]);
                    $view->items = $this->ItemRepo->readByCustomer($_SESSION["userID"], $_GET["customerId"]);
                    $view->template = '<?php echo $customer->title ?>';
                    $view->display();
                } else {
                    $storageNumbers = $this->ItemRepo->readStorageNumbersByCustomer($_SESSION["userID"], $customer->id);
                    if (count($storageNumbers) > 1) {
                        $target = 'Location: /document/selectStorageNumber?customerId=' . $customer->id;
                    } else {
                        $target = 'Location: /document/receipt?customerId=' . $customer->id . "&storageNumber=" . $storageNumbers[0]->storageNumber;
                    }
                    header($target);
                }
            } else {
                header('Location: /default/error?errorid=1&target=/');
            }
        } else {
            header('Location: /default/error?errorid=1&target=/');
        }
    }
}