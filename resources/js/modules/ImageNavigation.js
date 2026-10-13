export default class ImageNavigation {

    constructor(item, index) {
        this.item = item;
        this.index = index;
        this.image = this.item.parentElement.querySelector('.naval-image img')
        this.nextBtn = this.item.querySelector('.next');
        this.prevBtn = this.item.querySelector('.prev');

        this.next = this.next.bind(this);
        this.prev = this.prev.bind(this);

        this.init();
    }

    init() {
        this.addListeners();
    }

    addListeners() {
        this.nextBtn.addEventListener('click', this.next);
        this.prevBtn.addEventListener('click', this.prev);
    }

    next() {
        if(!this.item.classList.contains('translated')) {
            this.image.style.transform = 'translateX(-50%)';
            this.item.classList.add('translated');
        }
    }

    prev() {
        if(this.item.classList.contains('translated')) {
            this.image.style.transform = 'translateX(0)';
            this.item.classList.remove('translated');
        }
    }

    static init() {
        console.debug('-- ImageNavigation initialized --');
        ImageNavigation.items = Array.from(document.querySelectorAll('.image-navigation')).map(
            (item, index) => new ImageNavigation(item, index)
        );
    }
}