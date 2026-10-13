import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

export default class Slider {

    constructor(element, index) {
        this.element = element;
        this.index = index;

        this.nextBtn = this.element.querySelector('.navigation .next')
        this.prevBtn = this.element.querySelector('.navigation .prev')

        this.initCarousel()
    }

    setOptions(){
        let options = {
            slidesPerView: 1,
            spaceBetween: 0,
            modules: [Navigation, Pagination],
            loop: true,
            pagination:{
                el: this.element.querySelector('.swiper-pagination'),
                clickable: true,
                bulletClass: 'swiper-pagination-bullet',
                bulletActiveClass: 'active',
            },
            navigation: {
                nextEl: this.nextBtn,
                prevEl: this.prevBtn,
            },
        }

        return options
    }

    initCarousel(){

        let swiperInstance = new Swiper(this.element, this.setOptions())

        swiperInstance.init();       
        this.swiperInstance = swiperInstance
    }

    static init() {
        console.debug('Initializing Sliders...');
        Slider.items = Array.from(document.querySelectorAll('.swiper--hero')).map((element, index) => {
            console.log(element);
            new Slider(element, index)
        });
    }
}