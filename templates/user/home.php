<div class="container mb-5">
    <div class="row pt-5">
        <div class="col-sm">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body justify-content-between">
                    <p class="card-title">Stocktakings</p>
                    <p class="card-text">
                        <?php

                        if (empty($stocktakings)) {
                            echo "No Stocktakings";
                        } else {
                            echo count($stocktakings);
                        }

                        ?>
                    </p>
                </div>
            </div>
            <div class="card text-white bg-primary mb-3">
                <div class="card-body justify-content-between">
                    <p class="card-title">Scanned Sections</p>
                    <p class="card-text">
                        <?php

                        if ($sections->number == 0) {
                            echo 'No Sections';
                        } else {
                            echo $sections->number;
                        }

                        ?>
                    </p>
                </div>
            </div>
            <div class="card text-white bg-primary mb-3">
                <div class="card-body justify-content-between">
                    <p class="card-title">Scanned Items</p>
                    <p class="card-text">
                        <?php

                        if ($items->number == 0) {
                            echo 'No Items';
                        } else {
                            echo $items->number;
                        }

                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-sm">
            <?php

            if (!empty($stocktakings)) {
                foreach ($stocktakings as $stocktaking) {
                    echo '
                    <a href="/section/overview?stocktakingId=' . $stocktaking->id . '" class="card text-white bg-info mb-3" style="text-decoration: none">
                        <div class="card-body justify-content-between">
                            <p class="card-title">Stocktaking</p>
                            <p class="card-text">'
                        . $stocktaking->date . ' ' . $stocktaking->time .
                        '</p>
                        </div>
                    </a>';
                }
            } else {
                echo '
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body justify-content-between">
                            <p class="card-title">Stocktaking</p>
                            <p class="card-text">
                            No Stocktakings
                            </p>
                        </div>
                    </div>';
            }

            ?>
        </div>
    </div>
</div>