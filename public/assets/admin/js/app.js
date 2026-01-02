
// Helper: Check if desktop
const isDesktop = (window) => {
    return window.innerWidth > 1200;
}

// Sidebar Logic (Simplified from Mazer)
class Sidebar {
    constructor(el, options = {}) {
        this.sidebarEL = el instanceof HTMLElement ? el : document.querySelector(el)
        this.options = options
        this.init()
    }

    init() {
        document.querySelectorAll(".burger-btn").forEach((el) => el.addEventListener("click", this.toggle.bind(this)))
        document.querySelectorAll(".sidebar-hide").forEach((el) => el.addEventListener("click", this.toggle.bind(this)))
        window.addEventListener("resize", this.onResize.bind(this))

        const toggleSubmenu = (el) => {
            if (el.classList.contains("submenu-open")) {
                el.classList.remove('submenu-open')
                el.classList.add('submenu-closed')
            } else {
                el.classList.remove("submenu-closed")
                el.classList.add("submenu-open")
            }
        }

        let sidebarItems = document.querySelectorAll(".sidebar-item.has-sub")
        for (var i = 0; i < sidebarItems.length; i++) {
            let sidebarItem = sidebarItems[i]
            sidebarItems[i].querySelector(".sidebar-link").addEventListener("click", (e) => {
                e.preventDefault()
                let submenu = sidebarItem.querySelector(".submenu")
                toggleSubmenu(submenu)
            })

            const submenuItems = sidebarItem.querySelectorAll('.submenu-item.has-sub')
            submenuItems.forEach(item => {
                item.addEventListener('click', () => {
                    const submenuLevelTwo = item.querySelector('.submenu')
                    toggleSubmenu(submenuLevelTwo)
                })
            })
        }

        if (typeof PerfectScrollbar == "function") {
            const container = document.querySelector(".sidebar-wrapper")
            const ps = new PerfectScrollbar(container, {
                wheelPropagation: true,
            })
        }

        setTimeout(() => {
            const activeSidebarItem = document.querySelector(".sidebar-item.active");
            if (activeSidebarItem) {
                this.forceElementVisibility(activeSidebarItem);
            }
        }, 300);
    }

    onResize() {
        if (isDesktop(window)) {
            this.sidebarEL.classList.add("active")
            this.sidebarEL.classList.remove("inactive")
        } else {
            this.sidebarEL.classList.remove("active")
        }
        this.deleteBackdrop()
        this.toggleOverflowBody(true)
    }

    toggle() {
        const sidebarState = this.sidebarEL.classList.contains("active")
        if (sidebarState) {
            this.hide()
        } else {
            this.show()
        }
    }

    show() {
        this.sidebarEL.classList.add("active")
        this.sidebarEL.classList.remove("inactive")
        this.createBackdrop()
        this.toggleOverflowBody()
    }

    hide() {
        this.sidebarEL.classList.remove("active")
        this.sidebarEL.classList.add("inactive")
        this.deleteBackdrop()
        this.toggleOverflowBody()
    }

    createBackdrop() {
        if (isDesktop(window)) return
        this.deleteBackdrop()
        const backdrop = document.createElement("div")
        backdrop.classList.add("sidebar-backdrop")
        backdrop.addEventListener("click", this.hide.bind(this))
        document.body.appendChild(backdrop)
    }

    deleteBackdrop() {
        const backdrop = document.querySelector(".sidebar-backdrop")
        if (backdrop) {
            backdrop.remove()
        }
    }

    toggleOverflowBody(active) {
        if (isDesktop(window)) return;
        const sidebarState = this.sidebarEL.classList.contains("active")
        const body = document.querySelector("body")
        if (typeof active == "undefined") {
            body.style.overflowY = sidebarState ? "hidden" : "auto"
        } else {
            body.style.overflowY = active ? "auto" : "hidden"
        }
    }

    isElementInViewport(el) {
        var rect = el.getBoundingClientRect()
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        )
    }

    forceElementVisibility(el) {
        if (!this.isElementInViewport(el)) {
            el.scrollIntoView(false)
        }
    }
}

// Init Sidebar
let sidebarEl = document.getElementById("sidebar")
if (sidebarEl) {
    if (document.readyState !== 'loading') {
        const sidebar = new Sidebar(sidebarEl)
    } else {
        window.addEventListener('DOMContentLoaded', () => new Sidebar(sidebarEl))
    }
}

// Init Feather Icons
document.addEventListener("DOMContentLoaded", function () {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
