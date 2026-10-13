export default class MenuItem {

    constructor(item, index) {
        this.item = item;
        this.index = index;
        this.submenuChildren = this.item.querySelector('.menu-item-children');
        this.bodyList = this.submenuChildren.querySelector('.sub-menu-body li');
        this.arrow = this.item.querySelector('.sub-menu-opener .label svg');
        
        this.show = this.show.bind(this);
        this.hide = this.hide.bind(this);

        this.init();
    }

    init(){
        if(window.innerWidth >= 1024){
            this.addListeners();
        }
    }

    addListeners(){
        this.item.addEventListener('mouseleave', this.show)
        this.item.addEventListener('mouseenter', this.hide)
    }

    hide() {
        if(this.item.getAttribute('data-item') == this.bodyList?.getAttribute('data-parent')){
            this.submenuChildren.classList.add('lg:opacity-100');
        }
        this.rotate180();
    }

    show() {
        if(this.item.getAttribute('data-item') == this.bodyList?.getAttribute('data-parent')){
            this.submenuChildren.classList.remove('lg:opacity-100');
        }
        this.rotate0();
    }

    rotate180() {
        this.arrow.classList.add('rotate-180');
    }

    rotate0(){
        this.arrow.classList.remove('rotate-180');
    }

    static init() {
        MenuItem.items = Array.from(document.querySelectorAll('.sub-menu')).map(
            (item, index) => new MenuItem(item, index)
        );
    }
}