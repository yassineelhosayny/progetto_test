export default class Menu {
    constructor(menu, index) {
        this.menu = menu;
        this.index = index;
        this.hamburger = this.menu.querySelector('.header-container .toggle-buttons .hamburger');
        this.closeButton = this.menu.querySelector('.header-container .nav-container .fixed .nav-wrapper .nav-header .cross');
        this.logoContainer = this.menu.querySelector('.header-container .header-logo');
        this.logo = this.logoContainer.querySelector('a img');

        this.toggleMenu = this.toggleMenu.bind(this);
        this.closeMenu = this.closeMenu.bind(this);

        this.init();
    }

    init() {
        this.scrollHide();
        this.addListeners();
    }

    addListeners() {
        this.hamburger.addEventListener('click', this.toggleMenu);
        this.closeButton.addEventListener('click', this.closeMenu);
    }

    scrollHide() {
        let scrollTop = window.innerHeight / 3;
        const menu = this.menu;

        window.addEventListener('scroll', () => {
            if (window.scrollY > scrollTop) {
                if(!menu.classList.contains('reduced')) {
                    menu.classList.add('reduced');
                }
            } else {
                if (menu.classList.contains('reduced')) {
                    menu.classList.remove('reduced');
                }
            }
        });
    }

    toggleMenu() {
        this.menu.classList.toggle('open');
        this.menu.style.transform = 'none';
        this.menu.style.transition = 'none';
        // document.body.classList.toggle('overflow-h');
    }

    closeMenu() {
        this.menu.classList.remove('open');
        // document.body.classList.remove('overflow-h');
    }

    static init() {
        console.debug('Menu initialized');
        Menu.items = Array.from(document.querySelectorAll('#header-menu')).map((menu, index) => {
            console.log(menu);
            new Menu(menu, index);
        });
    }
}