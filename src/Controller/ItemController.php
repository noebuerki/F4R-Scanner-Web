<?php

namespace App\Controller;

use App\Authentication\Authentication;
use App\Repository\CustomerRepository;
use App\Repository\ItemRepository;
use App\Repository\ValuationRepository;
use App\View\View;

class ItemController
{

    private $CustomerRepo;
    private $ItemRepo;
    private $ValuationRepo;

    function __construct()
    {
        $this->CustomerRepo = new CustomerRepository();
        $this->ItemRepo = new ItemRepository();
        $this->ValuationRepo = new ValuationRepository();
    }

    /* Unsecured Views */
    public function index()
    {
        $this->search();
    }

    /* Secured Views */
    public function create()
    {
        Authentication::restrictAuthenticated();

        if (is_numeric($_GET["customerId"])) {
            $view = new View('item/create');
            $view->title = 'Add item';
            $view->heading = 'Add item';
            $view->customerId = $_GET["customerId"];
            $view->display();
        } else {
            header('Location: /default/error?errorid=1&target=/item/');
        }
    }

    public function search()
    {
        Authentication::restrictAuthenticated();

        $view = new View('item/search');
        $view->title = 'Search customer';
        $view->heading = 'Search customer';
        $view->customerNumber = (isset($_GET["customerNumber"])) ? $_GET["customerNumber"] : "";
        $view->firstName = (isset($_GET["firstName"])) ? $_GET["firstName"] : "";
        $view->lastName = (isset($_GET["lastName"])) ? $_GET["lastName"] : "";
        $view->display();
    }

    public function select()
    {
        Authentication::restrictAuthenticated();

        if (is_string($_GET["firstName"]) || is_string($_GET["lastName"])) {
            $view = new View('item/select');
            $view->title = 'Select customer';
            $view->heading = 'Select customer';
            $view->firstName = $_GET["firstName"];
            $view->lastName = $_GET["lastName"];
            $view->customers = $this->CustomerRepo->readByName($_SESSION["userID"], $_GET["firstName"], $_GET["lastName"]);
            $view->display();
        } else {
            header('Location: /default/error?errorid=1&target=/item/');
        }
    }

    public function overview() {
        Authentication::restrictAuthenticated();

        if (is_numeric($_GET["customerId"])) {
            $view = new View('item/overview');
            $view->title = 'Overview';
            $view->heading = 'Overview';
            $view->customer = $this->CustomerRepo->readByID($_SESSION["userID"], $_GET["customerId"]);
            $view->items = $this->ItemRepo->readByCustomer($_SESSION["userID"], $_GET["customerId"]);
            $view->display();
        } else {
            header('Location: /default/error?errorid=1&target=/item/');
        }

    }

    public function update()
    {
        Authentication::restrictAuthenticated();

        if (is_numeric($_GET["itemId"])) {
            $item = $this->ItemRepo->readByID($_SESSION["userID"], $_GET["itemId"]);
            if ($item != null) {
                $view = new View('item/update');
                $view->title = 'Update item';
                $view->heading = 'Update item';
                $view->item = $item;
                $view->display();
            } else {
                header('Location: /default/error?errorid=1&target=/item/');
            }
        } else {
            header('Location: /default/error?errorid=1&target=/item/');
        }
    }

    /* Functions */
    public function doCreate()
    {
        Authentication::restrictAuthenticated();

        if (is_numeric($_POST["customerIdInput"]) && is_numeric($_POST["storageNumberInput"]) && is_numeric($_POST["inStorageInput"]) && is_string($_POST["nameInput"]) && is_numeric($_POST["valueInput"]) && is_numeric($_POST["priceInput"]) && (is_string($_POST["dropOfDateInput"]) || is_string($_POST["pickupDateInput"]))) {
            $this->ItemRepo->create($_SESSION["userID"], $_POST["customerIdInput"], $_POST["storageNumberInput"], $_POST["inStorageInput"], $_POST["nameInput"], $_POST["valueInput"], $_POST["priceInput"], $_POST["dropOfDateInput"], $_POST["pickupDateInput"]);
            header("Location: /item/overview?customerId={$_POST["customerIdInput"]}");
        } else {
            header('Location: /default/error?errorid=1&target=/item/');
        }
    }

    public function doSearch()
    {
        Authentication::restrictAuthenticated();

        if (is_numeric($_POST["customerNumberInput"])) {
            $customer = $this->CustomerRepo->readByCustomerNumber($_SESSION["userID"], $_POST["customerNumberInput"]);
            if ($customer != null) {
                header("Location: /item/overview?customerId=$customer->id");
            } else {
                header("Location: /item/search?customerNumber={$_POST["customerNumberInput"]}");
            }
        } elseif (is_string($_POST["firstNameInput"]) || is_string($_POST["lastNameInput"])) {
            $customers = $this->CustomerRepo->readByName($_SESSION["userID"], $_POST["firstNameInput"], $_POST["lastNameInput"]);
            if ($customers != null) {
                header("Location: /item/select?firstName={$_POST["firstNameInput"]}&lastName={$_POST["lastNameInput"]}");
            } else {
                header("Location: /item/search?firstName={$_POST["firstNameInput"]}&lastName={$_POST["lastNameInput"]}");
            }
        } else {
            header('Location: /default/error?errorid=1&target=/item/');
        }
    }

    public function doUpdate()
    {
        Authentication::restrictAuthenticated();

        if (is_numeric($_POST["itemIdInput"]) && is_numeric($_POST["storageNumberInput"]) && is_numeric($_POST["inStorageInput"]) && is_string($_POST["nameInput"]) && is_numeric($_POST["valueInput"]) && is_numeric($_POST["priceInput"]) && (is_string($_POST["dropOfDateInput"]) || is_string($_POST["pickupDateInput"]))) {
            $item = $this->ItemRepo->readByID($_SESSION["userID"], $_POST["itemIdInput"]);
            if ($item != null) {
                $this->ItemRepo->update($_SESSION["userID"], $_POST["itemIdInput"], $_POST["storageNumberInput"], $_POST["inStorageInput"], $_POST["nameInput"], $_POST["valueInput"], $_POST["priceInput"], $_POST["dropOfDateInput"], $_POST["pickupDateInput"]);
                $target = 'Location: /item/overview?customerId=' . $item->customerId;
                header($target);
            } else {
                header('Location: /default/error?errorid=1&target=/item/');
            }
        } else {
            header('Location: /default/error?errorid=1&target=/item/');
        }
    }

    public function doDelete() {
        Authentication::restrictAuthenticated();

        if (is_numeric($_GET["itemId"])) {
            $item = $this->ItemRepo->readByID($_SESSION["userID"], $_GET["itemId"]);
            if ($item != null) {
                $this->ItemRepo->deleteById($_SESSION["userID"], $_GET["itemId"]);
                $target = 'Location: /item/overview?customerId=' . $item->customerId;
                header($target);
            } else {
                header('Location: /default/error?errorid=1&target=/item/');
            }

        } else {
            header('Location: /default/error?errorid=1&target=/item/');
        }
    }

    public function doCheckStorageNumber() {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        $item = $this->ItemRepo->readFirstByStorageNumber($_SESSION["userID"], $data->StorageNumber);
        echo json_encode($item == null || $item->customerId == $data->CustomerId);
    }

    public function doCalculatePrice()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        $valuation = $this->ValuationRepo->readNextBiggerValue($_SESSION["userID"], $data->Value);
        echo json_encode(($valuation != null) ? $valuation->price : "");
    }
}
