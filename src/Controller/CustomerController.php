<?php

namespace App\Controller;

use App\Authentication\Authentication;
use App\Repository\CustomerRepository;
use App\View\View;

class CustomerController
{

    private $CustomerRepo;

    function __construct()
    {
        $this->CustomerRepo = new CustomerRepository();
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

        $view = new View('customer/create');
        $view->title = 'Add customer';
        $view->heading = 'Add customer';
        $view->display();
    }

    public function search()
    {
        Authentication::restrictAuthenticated();

        $view = new View('customer/search');
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

        if (is_string($_GET["firstName"]) && is_string($_GET["lastName"])) {
            $view = new View('customer/select');
            $view->title = 'Select customer';
            $view->heading = 'Select customer';
            $view->firstName = $_GET["firstName"];
            $view->lastName = $_GET["lastName"];
            $view->customers = $this->CustomerRepo->readByName($_SESSION["userID"], $_GET["firstName"], $_GET["lastName"]);
            $view->display();
        } else {
            header('Location: /default/error?errorid=1&target=/customer/');
        }
    }

    public function update()
    {
        Authentication::restrictAuthenticated();

        if (is_numeric($_GET["customerId"])) {
            $customer = $this->CustomerRepo->readByID($_SESSION["userID"], $_GET["customerId"]);
            if ($customer != null) {
                $view = new View('customer/update');
                $view->title = 'Update customer';
                $view->heading = 'Update customer';
                $view->customer = $customer;
                $view->display();
            } else {
                header('Location: /default/error?errorid=1&target=/customer/');
            }
        } else {
            header('Location: /default/error?errorid=1&target=/customer/');
        }
    }

    public function delete()
    {
        Authentication::restrictAuthenticated();

        $view = new View('customer/delete');
        $view->title = 'Delete customer';
        $view->heading = 'Delete customer';
        $view->display();
    }

    /* Functions */
    public function doCreate()
    {
        Authentication::restrictAuthenticated();

        if (is_numeric($_POST["customerNumberInput"]) && is_numeric($_POST["storageSpaceInput"]) && is_string($_POST["titleInput"]) && is_string($_POST["firstNameInput"]) && is_string($_POST["lastNameInput"]) && is_string($_POST["streetInput"]) && is_string($_POST["houseNumberInput"]) && is_string($_POST["postCodeInput"]) && is_string($_POST["cityInput"])) {
            $this->CustomerRepo->create($_SESSION["userID"], $_POST["customerNumberInput"], $_POST["storageSpaceInput"], $_POST["titleInput"], $_POST["firstNameInput"], $_POST["lastNameInput"], $_POST["middleNameInput"], $_POST["streetInput"], $_POST["houseNumberInput"], $_POST["postCodeInput"], $_POST["cityInput"]);
            header('Location: /customer');
        } else {
            header('Location: /default/error?errorid=1&target=/customer/add');
        }
    }

    public function doSearch()
    {
        Authentication::restrictAuthenticated();

        if (is_numeric($_POST["customerNumberInput"])) {
            $customer = $this->CustomerRepo->readByCustomerNumber($_SESSION["userID"], $_POST["customerNumberInput"]);
            if ($customer != null) {
                header("Location: /customer/update?customerId=$customer->id");
            } else {
                header("Location: /customer/search?customerNumber={$_POST["customerNumberInput"]}");
            }
        } elseif (is_string($_POST["firstNameInput"]) || is_string($_POST["lastNameInput"])) {
            $customers = $this->CustomerRepo->readByName($_SESSION["userID"], $_POST["firstNameInput"], $_POST["lastNameInput"]);
            if ($customers != null) {
                header("Location: /customer/select?firstName={$_POST["firstNameInput"]}&lastName={$_POST["lastNameInput"]}");
            } else {
                header("Location: /customer/search?firstName={$_POST["firstNameInput"]}&lastName={$_POST["lastNameInput"]}");
            }
        } else {
            header('Location: /default/error?errorid=1&target=/customer/add');
        }
    }

    public function doUpdate()
    {
        Authentication::restrictAuthenticated();

        if (is_numeric($_POST["customerId"]) && is_numeric($_POST["customerNumberInput"]) && is_numeric($_POST["storageSpaceInput"]) && is_string($_POST["titleInput"]) && is_string($_POST["firstNameInput"]) && is_string($_POST["lastNameInput"]) && is_string($_POST["streetInput"]) && is_string($_POST["houseNumberInput"]) && is_string($_POST["postCodeInput"]) && is_string($_POST["cityInput"])) {
            $this->CustomerRepo->update($_SESSION["userID"], $_POST["customerId"], $_POST["customerNumberInput"], $_POST["storageSpaceInput"], $_POST["titleInput"], $_POST["firstNameInput"], $_POST["lastNameInput"], $_POST["middleNameInput"], $_POST["streetInput"], $_POST["houseNumberInput"], $_POST["postCodeInput"], $_POST["cityInput"]);
            header('Location: /customer');
        } else {
            header('Location: /default/error?errorid=1&target=/customer/add');
        }
    }

    public function doCheckCustomerNumberAvailable()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        $customer = $this->CustomerRepo->readByCustomerNumber($_SESSION["userID"], $data->CustomerNumber);
        if ($customer == null || (isset($data->CustomerId) && $data->CustomerId == $customer->id)) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }
}
