// Theme Controller
document.addEventListener("DOMContentLoaded", function () {
    const themeToggle = document.querySelector(".theme-controller");

    if (themeToggle) {
        if (localStorage.theme === "dark") {
            document.documentElement.setAttribute("data-theme", "dark");
            themeToggle.checked = true;
        }

        // Event listener untuk toggle tema
        themeToggle.addEventListener("change", function () {
            if (this.checked) {
                document.documentElement.setAttribute("data-theme", "dark");
                localStorage.theme = "dark";
            } else {
                document.documentElement.setAttribute("data-theme", "light");
                localStorage.theme = "light";
            }
        });
    }

    // Clock functionality
    function updateClock() {
        const clockElement = document.getElementById("clock");
        if (clockElement) {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, "0");
            const minutes = String(now.getMinutes()).padStart(2, "0");
            const seconds = String(now.getSeconds()).padStart(2, "0");
            clockElement.textContent = `${hours}:${minutes}:${seconds}`;
        }
    }

    // Update clock if element exists
    if (document.getElementById("clock")) {
        updateClock();
        setInterval(updateClock, 1000);
    }
});
