<div class="card-header">
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0"><?php echo $pageItemObject["title"]; ?></h1>
        </div>

        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="/<?php echo $originalPath; ?>/<?php echo $pageItemObject["link"]; ?>"><?php echo $pageItemObject["title"]; ?></a>
                </li>

                <?php
                if ($extraTitle != null) {
                    foreach ($extraTitle as $extraTitleObject) {
                ?>
                        <li class="breadcrumb-item active">
                            <?php
                            if ($extraTitleObject["link"] != null) {
                            ?>
                                <a href="/<?php echo $originalPath; ?>/<?php echo $extraTitleObject["link"]; ?>"><?php echo $extraTitleObject["title"]; ?></a>
                            <?php
                            } else {
                                echo $extraTitleObject["title"];
                            };
                            ?>
                        </li>
                <?php
                    };
                };
                ?>
            </ol>
        </div>
    </div>
</div>