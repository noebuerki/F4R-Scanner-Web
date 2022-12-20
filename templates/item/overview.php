<div class="mt-5" style="margin-bottom: 100px">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 justify-content-center">
			<?php

			foreach ($items as $item) {
				echo '
            <div class="col">
                <a href="/item/detail?id=' . $item->id . '" role="button" class="btn btn-info text-white w-100 mb-4">
                    <div class="mb-4 col align-items-center">
                        <i class="bi bi-box-arrow-in-right mt-1 align-self-end" style="margin-left: 94%"></i>
                        <p class="mb-1">Item ' . $item->position . ' / ' . $section->targetQuantity . '</p>
                        <p class=mb-4">' . htmlentities($item->barcode) . '</p>
                    </div>
                </a>
            </div>';
			}
			?>
        </div>
    </div>

    <div class="position-fixed fixed-buttonpos">
    <a href="/section/overview?stocktakingId=<?php echo $section->stocktakingId ?>" role="button" class="btn btn-secondary" data-toggle="tooltip"
       title="Back">
        <img src="/images/arrow.svg" width="32" height="32" class="my-1" alt="Left-Arrow, Back">
    </a>
    <a href="/section/doDelete?id=<?php echo $section->id; ?>" role="button" class="btn btn-danger" data-toggle="tooltip"
       title="Delete Section">
        <img src="/images/delete.svg" width="32" height="32" class="my-1" alt="Delete Section">
    </a>
    </div>
</div>