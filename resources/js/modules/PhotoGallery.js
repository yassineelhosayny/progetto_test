import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

export default class PhotoGallery {

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
            spaceBetween: 20,
            modules: [Navigation, Pagination],
            loop: true,
            navigation: {
                nextEl: this.nextBtn,
                prevEl: this.prevBtn,
            },
            pagination:{
                el: document.querySelector('.swiper-pagination--photo-gallery'),
                clickable: true,
                bulletClass: 'swiper-pagination-bullet',
                bulletActiveClass: 'active',
            }
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
        PhotoGallery.items = Array.from(document.querySelectorAll('.swiper--photo-gallery')).map((element, index) => {
            console.log(element);
            new PhotoGallery(element, index)
        });
    }
}