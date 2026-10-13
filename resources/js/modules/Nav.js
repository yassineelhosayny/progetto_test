export default class Nav {

    constructor(item, index) {
        this.item = item;
        this.index = index;
        this.toggleBtn = this.item.querySelector('.sub-menu-opener .label svg');
        this.subMenuList = this.item.querySelector('.sub-menu-list');
        this.subMenuHeight = this.subMenuList.querySelector('.sub-menu-body').offsetHeight;
        this.parentItem = document.querySelector(`[data-item="${this.item.getAttribute('data-parent')}"]`);
        this.childrenItems = this.item.querySelectorAll('.sub-menu[data-parent="'+this.item.getAttribute('data-item')+'"]');

        this.toggle = this.toggle.bind(this);

        this.init();
    }

    init() {
        if(window.innerWidth < 1024) {
            this.addListeners();
        }
        console.log(this.childrenItems)
    }

    addListeners() {
        this.toggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggle();
        });
    }

    toggle() {
        if(document.querySelector('header').classList.contains('open')) {
            if(!this.item.classList.contains('open')) {
                this.item.classList.add('open');
                this.subMenuList.style.opacity = '1';
                this.subMenuList.style.visibility = 'visible';
                this.subMenuList.style.height = `${this.subMenuHeight}px`;

                if(this.item.getAttribute('data-parent') !== '0') {
                    console.log(`adding height to parent ${this.item.getAttribute('data-parent')}`);
                    this.parentItem.querySelector('.sub-menu-list').style.height = 
                    `${this.parentItem.querySelector('.sub-menu-list').offsetHeight + this.subMenuHeight}px`;
                }

            } else {
                this.item.classList.remove('open');
                this.subMenuList.style.opacity = '0';
                this.subMenuList.style.visibility = 'hidden';
                this.subMenuList.style.height = `0`;

                if(this.item.getAttribute('data-parent') !== '0') {
                    this.parentItem.querySelector('.sub-menu-list').style.height = 
                    `${this.parentItem.querySelector('.sub-menu-list').offsetHeight - this.subMenuHeight}px`;
                }

                if(this.childrenItems.length > 0) {
                    this.childrenItems.forEach((child) => {
                        if(child.classList.contains('open')) {
                            child.classList.remove('open');
                            child.querySelector('.sub-menu-list').style.opacity = '0';
                            child.querySelector('.sub-menu-list').style.visibility = 'hidden';
                            child.querySelector('.sub-menu-list').style.height = `0`;
                        }
                    });
                }
            }
        }
    }

    static init() {
        console.debug('Nav initialized');
        // if(window.innerWidth >= 1024) {
            Nav.items = Array.from(document.querySelectorAll('.sub-menu')).map(
                (item, index) =>  new Nav(item, index)
            );
        // }
    }
}