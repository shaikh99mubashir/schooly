// === Load PHP theme settings safely ===
const rawThemeSettings = window.themeSettings;
const themeSettings = rawThemeSettings || {};
(function () {


    // === Extract settings with fallback defaults ===
    const themeBackground = themeSettings.theme_background || 'light-mode';
    const savedLayout = themeSettings.theme_content || 'container-fluid';
    const themeShadow = themeSettings.theme_shadow || '';
    const themeColor = themeSettings.theme_color || '#7367f0';
    const themeNavigation = themeSettings.theme_navigation || "expanded";

    const themeClass = themeBackground === 'dark' ? 'dark' : 'light-mode';
    const themeClassNavigation = themeNavigation === 'collapsed' ? 'sidebar-collapse' : '';
    window.themeClass = themeClass;  // ⬅⬅ MAKE IT GLOBAL
    window.themeColor = themeColor;  // ⬅⬅ MAKE IT GLOBAL
    // === Apply theme, shadow, and navigation classes to <body> ===
    function applyBodyClasses() {
        if (!document.body) return;
        const classList = document.body.classList;
        // Remove existing theme and shadow classes
        classList.remove('light-mode', 'dark', 'sidebar-collapse');
        [...classList].forEach(cls => {
            if (cls.startsWith('shadow')) classList.remove(cls);
        });

        classList.add(themeClass);
        if (themeShadow) {
            classList.add(themeShadow);
        }

        if (themeClassNavigation) {
            console.log(themeClassNavigation);
            classList.add(themeClassNavigation);
        }
    }

    // === Apply layout and update icons ===
    function applyLayoutAndIcons() {
        const content = document.querySelector("section.content");
        const layoutIcon = document.getElementById('content-icon');
        const shadowIcon = document.getElementById('iconskins');
        const navigationIcon = document.getElementById('icon_theme_navigation');

        if (content && !content.classList.contains(savedLayout)) {
            content.classList.add(savedLayout);
        }

        if (navigationIcon) {
            const isCollapsed = themeClassNavigation === 'sidebar-collapse';

            navigationIcon.classList.toggle('fa-bars', isCollapsed);
            navigationIcon.classList.toggle('fa-brands', !isCollapsed);
            navigationIcon.classList.toggle('fa-elementor', !isCollapsed);
        }

        if (layoutIcon) {
            layoutIcon.classList.toggle('fa-compress', savedLayout === 'container-fluid');
            layoutIcon.classList.toggle('fa-expand', savedLayout !== 'container-fluid');
        }

        if (shadowIcon) {
            shadowIcon.classList.toggle('fa-border-none', themeShadow === 'shadow-applied');
            shadowIcon.classList.toggle('fa-border-all', themeShadow !== 'shadow-applied');
        }

        // Stop observing once applied
        if (content || layoutIcon || shadowIcon) {
            layoutObserver.disconnect();
        }
    }

    // === Watch for body and content appearance if not yet in DOM ===
    if (document.body) {
        applyBodyClasses();
    } else {
        new MutationObserver((mutations, observer) => {
            if (document.body) {
                applyBodyClasses();
                observer.disconnect();
            }
        }).observe(document.documentElement, {
            childList: true
        });
    }

    const layoutObserver = new MutationObserver(applyLayoutAndIcons);

    layoutObserver.observe(document.documentElement, {
        childList: true,
        subtree: true,
    });

    // Try applying immediately in case elements are already in DOM
    applyLayoutAndIcons();

})();