<script src="<?php echo $sourcePath; ?>/public/plugins/jquery/jquery.min.js"></script>
<script src="<?php echo $sourcePath; ?>/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $sourcePath; ?>/public/dist/js/adminlte.js"></script>

<script>
    function defaultColorTheme() {
        try {
            const bodyElement = document.getElementById("body-theme");
            const navElement = document.getElementById("nav-theme");
            const asideElement = document.getElementById("aside-theme");
            const asideUserBackgroundElement = document.getElementById("aside-user-background-theme");
            const asideUserIconElement = document.getElementById("aside-user-icon-theme");
            const iconElement = document.getElementById("icon-theme");
            const textElement = document.getElementById("text-theme");

            if (localStorage.colorThemeType == "light") {
                bodyElement.classList.add("light-mode");

                navElement.classList.add("navbar-white");
                navElement.classList.add("navbar-light");

                asideElement.classList.add("sidebar-light-primary");
                asideUserBackgroundElement.style.backgroundColor = "#343a40";
                asideUserIconElement.style.color = "white";

                $('.small-box').addClass('bg-white');

                iconElement.classList.add("fa-sun");
                textElement.innerHTML = "Tema Terang";
            } else if (localStorage.colorThemeType == "dark") {
                bodyElement.classList.add("dark-mode");

                navElement.classList.add("navbar-dark");

                asideElement.classList.add("sidebar-dark-primary");
                asideUserBackgroundElement.style.backgroundColor = "#ced4da";
                asideUserIconElement.style.color = "#343a40";

                $('.small-box').addClass('bg-dark');

                iconElement.classList.add("fa-moon");
                textElement.innerHTML = "Tema Gelap";
            };
        } catch (err) {};
    };

    function changeColorTheme() {
        const bodyElement = document.getElementById("body-theme");
        const navElement = document.getElementById("nav-theme");
        const asideElement = document.getElementById("aside-theme");
        const asideUserBackgroundElement = document.getElementById("aside-user-background-theme");
        const asideUserIconElement = document.getElementById("aside-user-icon-theme");
        const iconElement = document.getElementById("icon-theme");
        const textElement = document.getElementById("text-theme");

        if (localStorage.colorThemeType == "light") {
            bodyElement.classList.remove("light-mode");
            bodyElement.classList.add("dark-mode");

            navElement.classList.remove("navbar-white");
            navElement.classList.remove("navbar-light");
            navElement.classList.add("navbar-dark");

            asideElement.classList.remove("sidebar-light-primary");
            asideElement.classList.add("sidebar-dark-primary");
            asideUserBackgroundElement.style.backgroundColor = "#ced4da";
            asideUserIconElement.style.color = "#343a40";

            $('.small-box').removeClass('bg-white');
            $('.small-box').addClass('bg-dark');

            iconElement.classList.remove("fa-sun");
            iconElement.classList.add("fa-moon");

            textElement.innerHTML = "Tema Gelap";
            localStorage.colorThemeType = "dark";
        } else if (localStorage.colorThemeType == "dark") {
            bodyElement.classList.remove("dark-mode");
            bodyElement.classList.add("light-mode");

            navElement.classList.remove("navbar-dark");
            navElement.classList.add("navbar-white");
            navElement.classList.add("navbar-light");

            asideElement.classList.remove("sidebar-dark-primary");
            asideElement.classList.add("sidebar-light-primary");
            asideUserBackgroundElement.style.backgroundColor = "#343a40";
            asideUserIconElement.style.color = "white";

            $('.small-box').removeClass('bg-dark');
            $('.small-box').addClass('bg-white');

            iconElement.classList.remove("fa-moon");
            iconElement.classList.add("fa-sun");

            textElement.innerHTML = "Tema Terang";
            localStorage.colorThemeType = "light";
        };
    };

    if (!localStorage.colorThemeType) {
        localStorage.colorThemeType = "light";
    };

    defaultColorTheme();

    function idle() {
        let idleTime;
        let idleStatus = false;

        window.onload = idleReset;
        window.onmousemove = idleReset;
        window.onmousedown = idleReset;
        window.ontouchstart = idleReset;
        window.ontouchmove = idleReset;
        window.onclick = idleReset;
        window.onkeydown = idleReset;
        window.addEventListener('scroll', idleReset, true);

        function idleAction() {
            idleStatus = true;
            errorModal("Anda terlalu lama idle", "/<?php echo $originalPath ?>/sources/models/authentication/logout.php");
        };

        function idleReset() {
            if (!idleStatus) {
                clearTimeout(idleTime);
                idleTime = setTimeout(idleAction, 600 * 1000);
            }
        };
    };

    const isAuthenticated = <?php echo isset($_SESSION["id"]) ? 1 : 0; ?>;
    if (isAuthenticated) {
        idle();
    };
</script>