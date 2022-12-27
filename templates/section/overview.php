<div class="mt-5" style="margin-bottom: 100px">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 justify-content-center">
			<?php

			foreach ($sections as $section) {
				echo '
            <div class="col">
                <a href="/item/overview?sectionId=' . $section->id . '" role="button" class="btn btn-info text-white w-100 mb-4">
                    <div class="mb-4 col align-items-center">
                        <i class="bi bi-box-arrow-in-right mt-1 align-self-end" style="margin-left: 94%"></i>
                        <p>Section ' . $section->number . '</p>
                        <p class="mb-4">' . $section->itemCount . ' / ' . $section->targetQuantity . ' Items</p>
                    </div>
                </a>
            </div>';
			}
			?>
        </div>
    </div>

    <div class="position-fixed fixed-buttonpos">
    <a href="/stocktaking/overview" role="button" class="btn btn-secondary" data-toggle="tooltip"
       title="Back">
        <img src="/images/arrow.svg" width="32" height="32" class="my-1" alt="Left-Arrow, Back">
    </a>
    <a href="/stocktaking/doExport?id=<?php echo $stocktaking->id; ?>" role="button" class="btn btn-secondary" data-toggle="tooltip"
       title="Export Stocktaking">
        <img src="/images/export.svg" width="32" height="32" class="my-1" alt="Delete Stocktaking">
    </a>
    <a href="/stocktaking/doDelete?id=<?php echo $stocktaking->id; ?>" role="button" class="btn btn-danger" data-toggle="tooltip"
       title="Delete Stocktaking">
        <img src="/images/delete.svg" width="32" height="32" class="my-1" alt="Delete Stocktaking">
    </a>
    </div>
</div>