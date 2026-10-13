import gsap from "gsap";
import { ScrollTrigger } from "gsap/all";
gsap.registerPlugin(ScrollTrigger);

export default class LettersAnimation {

    constructor(element, index) {
        this.element = element;
        this.index = index;

        this.boldLetters = this.element.querySelectorAll('.word .letter .bold');

        this.init();
    }

    init(){
        this.animate();
    }

    animate() {
        const tl = gsap.timeline();

        this.boldLetters.forEach((boldLetter, i) => {
            tl.to(boldLetter, {
                width: '100%',
                duration: 0.4,
                ease: 'power2.out',
                delay: i * 0.6
            }, 0);

            ScrollTrigger.create({
                animation: tl,
                trigger: boldLetter,
                start: 'top 100%',
                end: 'bottom top+=300',
                toggleActions: 'play none none none',
                scrub: true,
            });
        });
    }
    
    static init() {
        console.debug('-- LettersAnimation init --');
        LettersAnimation.items = Array.from(document.querySelectorAll('.split-letters')).map(
            (element, index) => new LettersAnimation(element, index)
        );
    }
}