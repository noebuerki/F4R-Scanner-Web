<div class="mt-5" style="margin-bottom: 100px">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 justify-content-center">
			<?php

			foreach ($stocktakings as $stocktaking) {
				echo '
            <div class="col">
                <a href="/section/overview?stocktakingId=' . $stocktaking->id . '" role="button" class="btn btn-info text-white w-100 mb-4">
                    <div class="mb-4 col align-items-center">
                        <i class="bi bi-box-arrow-in-right mt-1 align-self-end" style="margin-left: 94%"></i>
                        <p>Stocktaking</p>
                        <p class="mb-1">Branch ' . $stocktaking->branch . '</p>
                        <p class="mb-4">' . $stocktaking->date . '</p>
                    </div>
                </a>
            </div>';
			}
			?>
        </div>
    </div>

    <div class="position-fixed fixed-buttonpos">
    <a href="/stocktaking/create" role="button" class="btn btn-secondary" data-toggle="tooltip"
       title="Add Stocktaking">
        <img src="/images/add.svg" width="32" height="32" class="my-1" alt="Add Stocktaking">
    </a>
    </div>
</div>