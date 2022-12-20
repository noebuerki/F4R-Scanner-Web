<div style="margin-bottom: 100px">

<div class="d-flex justify-content-center mt-5">
    <div class="card bg-light mb-3">
        <div class="card-body">

            <form action="/item/doUpdate" method="post" class="needs-validation" novalidate>

                <div class="form-group mb-2">
                    <input required type="number" placeholder="Position" class="form-control" data-toggle="tooltip"
                           title="Enter Position" value="<?php echo $item->position ?>"
                           name="positionInput">
                    <div class="invalid-feedback">Enter Position</div>
                </div>

                <div class="form-group mb-2">
                    <input required type="text" placeholder="Barcode" class="form-control" data-toggle="tooltip"
                           title="Enter Barcode" value="<?php echo $item->barcode ?>"
                           name="barcodeInput">
                    <div class="invalid-feedback">Enter Barcode</div>
                </div>

                <button type="submit" class="btn btn-secondary" data-toggle="tooltip" title="Login">
                    Update <i class="bi bi-arrow-repeat"></i>
                </button>

            </form>
        </div>
    </div>
</div>

    <div class="position-fixed fixed-buttonpos">
    <a href="/item/overview?sectionId=<?php echo $item->sectionId ?>" role="button" class="btn btn-secondary" data-toggle="tooltip"
       title="Back">
        <img src="/images/arrow.svg" width="32" height="32" class="my-1" alt="Left-Arrow, Back">
    </a>
    <a href="/item/doDelete?id=<?php echo $item->id; ?>" role="button" class="btn btn-danger" data-toggle="tooltip"
       title="Delete Item">
        <img src="/images/delete.svg" width="32" height="32" class="my-1" alt="Delete Item">
    </a>
    </div>
</div>