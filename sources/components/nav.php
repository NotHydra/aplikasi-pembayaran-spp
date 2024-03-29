<div class="preloader flex-column justify-content-center align-items-center">
    <img src="<?php echo $sourcePath; ?>/public/dist/img/app-logo.png" height="120" width="120">
</div>

<nav class="main-header navbar navbar-expand" id="nav-theme">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" role="button"><i class="fas fa-bars"></i></a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="/<?php echo $originalPath; ?>" class="nav-link">Utama</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item d-none d-sm-inline-block">
            <a class="nav-link" href="/<?php echo $originalPath; ?>/sources/models/pengaturan/data-pribadi"><?php echo $sessionType == "petugas" ? ucwords($sessionUsername) : ucwords($sessionNama); ?> Sebagai <?php echo ucwords($sessionLevel); ?></a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>

<aside class="main-sidebar elevation-4" id="aside-theme" style="height: 100vh;">
    <a href="/<?php echo $originalPath; ?>" class="brand-link">
        <img src="<?php echo $sourcePath; ?>/public/dist/img/app-logo.png" alt="App Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8">
        <span class="brand-text font-weight-light">Pembayaran SPP</span>
    </a>

    <div class="sidebar">
        <a class="user-panel mt-3 pb-3 mb-3 d-flex" style="align-items: center;" href="/<?php echo $originalPath; ?>/sources/models/pengaturan/data-pribadi">
            <div id="aside-user-background-theme" style="width: 2.75rem; padding: 0.5rem; border-radius: 50%; display: flex; justify-content: center;">
                <?php
                if (in_array($sessionLevel, ["siswa", "petugas"])) {
                    $iconName = "fa-user";
                } else if ($sessionLevel == "admin") {
                    $iconName = "fa-user-tie";
                } else if ($sessionLevel == "superadmin") {
                    $iconName = "fa-user-secret";
                };
                ?>

                <i id="aside-user-icon-theme" class="fas <?php echo $iconName; ?>" style="font-size: 1.75rem;"></i>
            </div>

            <div class="info">
                <div class="d-block">
                    <p class="h5 m-0 p-0"><?php echo $sessionType == "petugas" ? ucwords($sessionUsername) : ucwords($sessionNama); ?></p>

                    <?php
                    if ($sessionType == "petugas") {
                    ?>
                        <p class="h6 m-0 p-0" style="opacity: 0.6;"><?php echo ucwords($sessionLevel); ?></p>

                        <?php
                    } else if ($sessionType == "siswa") {
                        $totalNominal = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(spp.nominal) AS `total` FROM spp_detail INNER JOIN spp ON spp_detail.id_spp=spp.id WHERE spp_detail.id_siswa='$sessionId';"))["total"];
                        $totalSudahDibayar = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(pembayaran.jumlah_pembayaran) AS `total` FROM pembayaran INNER JOIN spp_detail ON pembayaran.id_spp_detail=spp_detail.id WHERE spp_detail.id_siswa='$sessionId';"))["total"];

                        if ($totalNominal == $totalSudahDibayar) {
                        ?>
                            <div class="bg-success text-center py-1" style="border-radius: 4px; font-size: 0.8rem">
                                <b>Sudah Lunas</b>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="bg-danger text-center py-1" style="border-radius: 4px; font-size: 0.8rem">
                                <b>Belum Lunas</b>
                            </div>
                    <?php
                        };
                    };
                    ?>
                </div>
            </div>
        </a>

        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <?php
                $pageArray = [
                    1 => [
                        "id" => 1,
                        "title" => "Utama",
                        "icon" => "fas fa-home",
                        "link" => "sources/models/utama",
                        "child" => null,
                    ]
                ];

                if (roleCheckMinimum($sessionLevel, "admin")) {
                    $pageArray[2] = [
                        "id" => 2,
                        "title" => "Petugas",
                        "icon" => "fas fa-user-tie",
                        "link" => "sources/models/petugas",
                        "child" => null
                    ];
                };

                if (roleCheckMinimum($sessionLevel, "petugas")) {
                    $pageArray[3] = [
                        "id" => 3,
                        "title" => "Siswa",
                        "icon" => "fas fa-user",
                        "link" => "sources/models/siswa",
                        "child" => null
                    ];
                };

                if (roleCheckMinimum($sessionLevel, "admin")) {
                    $pageArray[4] = [
                        "id" => 4,
                        "title" => "Instansi",
                        "icon" => "fas fa-building",
                        "link" => null,
                        "child" => [
                            1 => [
                                "id" => 1,
                                "title" => "Rombel",
                                "icon" => "fas fa-archway",
                                "link" => "sources/models/instansi/rombel",
                            ],
                            2 => [
                                "id" => 2,
                                "title" => "Kompetensi Keahlian",
                                "icon" => "fas fa-microchip",
                                "link" => "sources/models/instansi/kompetensi-keahlian",
                            ],
                            3 => [
                                "id" => 3,
                                "title" => "Jurusan",
                                "icon" => "fas fa-wrench",
                                "link" => "sources/models/instansi/jurusan",
                            ],
                            4 => [
                                "id" => 4,
                                "title" => "Tingkat",
                                "icon" => "fas fa-layer-group",
                                "link" => "sources/models/instansi/tingkat",
                            ]
                        ]
                    ];

                    $pageArray[5] = [
                        "id" => 5,
                        "title" => "SPP",
                        "icon" => "fas fa-clipboard",
                        "link" => "sources/models/spp",
                        "child" => null
                    ];
                };

                if (roleCheckSingle($sessionLevel, "siswa")) {
                    $pageArray[6] = [
                        "id" => 6,
                        "title" => "Riwayat",
                        "icon" => "fas fa-history",
                        "link" => "sources/models/riwayat",
                        "child" => null
                    ];
                };

                if (roleCheckMinimum($sessionLevel, "admin")) {
                    $pageArray[7] = [
                        "id" => 7,
                        "title" => "Laporan",
                        "icon" => "fas fa-list",
                        "link" => null,
                        "child" => [
                            1 => [
                                "id" => 1,
                                "title" => "Keseluruhan",
                                "icon" => "fas fa-globe",
                                "link" => "sources/models/laporan/keseluruhan",
                            ],
                            2 => [
                                "id" => 2,
                                "title" => "Keuangan",
                                "icon" => "fas fa-money-bill",
                                "link" => "sources/models/laporan/keuangan",
                            ]
                        ]
                    ];
                };

                $pageArray[8] = [
                    "id" => 8,
                    "title" => "Pengaturan",
                    "icon" => "fas fa-cog",
                    "link" => null,
                    "child" => [
                        1 => [
                            "id" => 1,
                            "title" => "Data Pribadi",
                            "icon" => "fas fa-user-circle",
                            "link" => "sources/models/pengaturan/data-pribadi",
                        ]
                    ]
                ];
                ?>

                <?php
                foreach ($pageArray as $pageObject) {
                    $pageObjectLink = $pageObject["link"];
                ?>
                    <li class="nav-item<?php echo ($pageObject["id"] == $navActive[0] and $navActive[1] != null) ? " menu-open" : ""; ?>">
                        <a href="<?php echo $pageObjectLink != null ? "/$originalPath/$pageObjectLink" : ""; ?>" class="nav-link<?php echo ($pageObject["id"] == $navActive[0]) ? " active" : ""; ?>">
                            <i class="nav-icon <?php echo $pageObject["icon"]; ?>"></i>
                            <p>
                                <?php echo $pageObject["title"]; ?>

                                <?php
                                if ($pageObject["child"] != null) {
                                ?>
                                    <i class="right fas fa-angle-left"></i>
                                <?php
                                };
                                ?>
                            </p>
                        </a>

                        <?php
                        if ($pageObject["child"] != null) {
                        ?>
                            <ul class="nav nav-treeview">
                                <?php
                                foreach ($pageObject["child"] as $pageChildObject) {
                                ?>
                                    <li class="nav-item">
                                        <a href="/<?php echo $originalPath; ?>/<?php echo $pageChildObject["link"]; ?>" class="nav-link<?php echo ($pageObject["id"] == $navActive[0] and $pageChildObject["id"] == $navActive[1]) ? " active" : ""; ?>">
                                            <i class="nav-icon <?php echo $pageChildObject["icon"]; ?>"></i>
                                            <p><?php echo $pageChildObject["title"]; ?></p>
                                        </a>
                                    </li>
                                <?php
                                };

                                if ($pageObject["title"] == "Pengaturan") {
                                ?>
                                    <li class="nav-item">
                                        <a class="nav-link" role="button" onclick="confirmModal('action', changeColorTheme);">
                                            <i class="nav-icon fas" id="icon-theme"></i>
                                            <p id="text-theme"></p>
                                        </a>
                                    </li>
                                <?php
                                };
                                ?>
                            </ul>
                        <?php
                        };
                        ?>
                    </li>
                <?php
                };
                ?>

                <li class="nav-item">
                    <a class="nav-link" role="button" onclick="confirmModal('location', '/<?php echo $originalPath; ?>/sources/models/authentication/logout.php')">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<?php
if ($navActive[1] == null) {
    $pageItemObject = $pageArray[$navActive[0]];
} else {
    $pageItemObject = $pageArray[$navActive[0]]["child"][$navActive[1]];
};
?>