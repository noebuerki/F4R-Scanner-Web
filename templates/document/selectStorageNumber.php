<div style="margin-bottom: 100px">

    <div class="container">
        <div class="row row-cols-2 row-cols-md-4 justify-content-center">
            <?php
            foreach ($storageNumbers as $storageNumber) {
                echo '
            <div class="col">
                <a href="/document/invoice?customerId=' . $customerId . '&storageNumber=' . $storageNumber->storageNumber . '" role="button" class="btn btn-primary bg-primary w-100 mb-4">
                        <div class="mb-4 align-items-center" data-toggle="tooltip">
                            <div class="d-flex mt-1 mb-2 justify-content-between">
                                <i class="bi bi-check2-square align-self-end"></i>
                            </div>
                            <p>' . $storageNumber->storageNumber . '</p>
                        </div>
                    </a>      
            </div>
                          
            ';
            }
            ?>
        </div>
    </div>

    <div class="container">
        <div class="row position-fixed fixed-buttonpos">

            <a href="/item/overview?customerId=<?php echo $customerId ?>"
               role="button" class="btn btn-secondary" data-toggle="tooltip" title="Back to search">
                <img src="/images/arrow.svg" width="32" height="32" class="my-1" alt="Back">
            </a>

        </div>
    </div>

</div>