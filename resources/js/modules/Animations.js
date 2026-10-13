import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

export default class Animations {
  constructor(node) {
    this.animations = node;

    this.animateFadeItems = this.animations.querySelectorAll('.animate-fade')
    this.animateRightItems = this.animations.querySelectorAll('.animate-right')
    this.animateLeftItems = this.animations.querySelectorAll('.animate-left')
    this.animateBottomItems = this.animations.querySelectorAll('.animate-bottom')
    this.animateTopItems = this.animations.querySelectorAll('.animate-top')
    this.animateBottomStaggerItems = this.animations.querySelectorAll('.animate-bottom-stagger')
    this.animateLetterStaggerItems = this.animations.querySelectorAll('.animate-letter-stagger')
    this.animateScrollTopStaggerItems = this.animations.querySelectorAll('.animate-scroll-top')
    this.animateInImageItems = this.animations.querySelectorAll('.animate-image')
    this.animateScrollFloatItems = this.animations.querySelectorAll('.animate-scroll-float')

    this.initAnimations()
  }

  initAnimations() {
    console.debug('== Animations components ==')

    // General animations
    console.debug('  -> Animate general components')
    if (this.animateFadeItems.length != 0) {
      animateFade(this.animateFadeItems)
    }
    if (this.animateRightItems.length != 0) {
      animateHorizontal(this.animateRightItems, 40)
    }
    if (this.animateLeftItems.length != 0) {
      animateHorizontal(this.animateLeftItems, -40)
    }
    if (this.animateBottomItems.length != 0) {
      animateVertical(this.animateBottomItems, 20)
    }
    if (this.animateTopItems.length != 0) {
      animateVertical(this.animateTopItems, -20)
    }
    if (this.animateBottomStaggerItems.length != 0) {
      animateBottomStagger(this.animateBottomStaggerItems, 20, 0.2)
    }
    if (this.animateLetterStaggerItems.length != 0) {
      animateLetterStagger(this.animateLetterStaggerItems, 35, 0.1)
    }
    if (this.animateInImageItems.length != 0) {
      animateInImage(this.animateInImageItems)
    }
    if (this.animateScrollTopStaggerItems != false) {
      animateScrollTopStagger(this.animateScrollTopStaggerItems, -15, 0.25)
    }
    if (this.animateScrollFloatItems.length != 0) {
      animateScrollFloat(this.animateScrollFloatItems, 0)
    }
  }

  destroy() { }

  static destroyScrolls() {
    ScrollTrigger.killAll()
  }

  static resetAnimateLetterStagger() {
    console.debug('== Resetting Animate Letter Stagger ==')

    // Reinizializza l'animazione
    Animations.items.forEach((animation) => {
      if (animation.animateLetterStaggerItems.length != 0) {
        animateLetterStagger(animation.animateLetterStaggerItems, 35, 0.1)
      }
    });
  }

  static reset() {
    console.debug('== Resetting Animations components ==')
    Animations.init()
  }

  static init() {
    console.debug('== Initializing Animations components ==');
    Animations.items = Array.from(document.querySelectorAll('.animate')).map(
      (animation) => new Animations(animation)
    )
  }
}


function animateFade(items) {
  items.forEach((item) => {
    let tlItem = gsap.timeline({ paused: true })

    tlItem.from(item, {
      autoAlpha: 0,
      duration: 2,
      ease: 'expo.out',
      delay: 0.5,
    })

    ScrollTrigger.create({
      trigger: item,
      start: 'top 90%',
      end: 'bottom 90%',
      animation: tlItem,
      markers: false,
      toggleActions: 'play none none reverse',
    })
  })
}

function animateHorizontal(items, offset) {
  items.forEach((item) => {
    let tlItem = gsap.timeline({ paused: true })

    tlItem.from(item, {
      autoAlpha: 0,
      duration: 1,
      x: offset,
      ease: 'expo.out',
    })

    ScrollTrigger.create({
      trigger: item,
      start: 'top 90%',
      end: 'bottom 90%',
      animation: tlItem,
      markers: false,
      toggleActions: 'play none none reverse',
    })
  })
}

function animateVertical(items, offset) {
  items.forEach((item) => {
    let tlItem = gsap.timeline({ paused: true })

    tlItem.from(item, {
      autoAlpha: 0,
      duration: 2,
      y: offset,
      ease: 'expo.out',
    })

    ScrollTrigger.create({
      trigger: item,
      start: 'top 90%',
      end: 'bottom 90%',
      animation: tlItem,
      markers: false,
      toggleActions: 'play none none reverse',
    })
  })
}

function animateBottomStagger(items, offset, stagger) {
  let tlItem = gsap.timeline({ paused: true })

  // console.log(items)

  tlItem.from(items, {
    autoAlpha: 0,
    duration: 1,
    y: offset,
    ease: 'expo.out',
    stagger: stagger,
    delay: 0.25,
  })

  ScrollTrigger.create({
    trigger: items,
    start: 'top 90%',
    end: 'bottom 90%',
    animation: tlItem,
    markers: false,
    toggleActions: 'play none none reverse',
  })
}

function animateLetterStagger(items, offset, stagger) {
  let tlItem = gsap.timeline({ paused: true })

  // console.log(items)

  tlItem.from(items, {
    autoAlpha: 0,
    duration: 1,
    y: offset,
    rotationX: 180,
    transformOrigin: "0% -50% 50%",
    ease: 'back',
    stagger: stagger,
    delay: 0.25,
  })

  ScrollTrigger.create({
    trigger: items,
    start: 'top 95%',
    end: 'bottom 90%',
    animation: tlItem,
    markers: false,
    toggleActions: 'play none none reverse',
  })
}

function animateScrollTopStagger(items, offset, stagger) {
  let tlItem = gsap.timeline({ paused: true })

  items.forEach((item) => {
    let tlItem = gsap.timeline({ paused: true })
    
    tlItem.fromTo(items, {
      y: 0, // Inizia dalla posizione attuale
    }, {
      duration: 2,
      y: offset,
      // yPercent: offset,
      rotationX: 180,
      transformOrigin: "0% -50% 50%",
      ease: "power2.inOut",
      stagger: stagger,
      delay: 1.2,
    })
  
    ScrollTrigger.create({
      trigger: items,
      animation: tlItem,
      scrub: true, // Attiva lo scrub per un'animazione legata allo scroll
      markers: false,
      toggleActions: 'play none none reverse',
    })

  }) 
}

function animateInImage(items) {
  items.forEach((item) => {
    let tlItem = gsap.timeline({ paused: true })

    const layerItem = item.querySelector('img')

    if (layerItem) {
      tlItem.from(item, {
        autoAlpha: 0,
        duration: 0.25,
        ease: 'Power2.easeOut',
      })
        .from(layerItem, {
          autoAlpha: 0,
          duration: 1,
          ease: 'Power2.easeOut',
        })
        .from(layerItem, {
          delay: -1.2,
          duration: 1.25,
          ease: 'Power2.easeOut',
          scale: 1.05,
        })

      ScrollTrigger.create({
        trigger: item,
        start: 'top 60%',
        end: 'bottom 90%',
        animation: tlItem,
        markers: false,
        toggleActions: 'play none none reverse',
      })
    }
  })
}

function animateScrollFloat(items, offset) {

  let tlItem = gsap.timeline({ paused: true })

  tlItem.fromTo(items, {
    y: 200, // Inizia dalla posizione attuale
    alpha: 0
  }, {
    alpha: 1.5,
    duration: 5,
    y: offset,
    yPercent: 40,
    transformOrigin: "0% -50% 50%",
    ease: "power2.inOut",
    // stagger: {
    //   each: 0.3,
    // }
    delay: 5
  })

  ScrollTrigger.create({
    trigger: items,
    animation: tlItem,
    // start: '50 bottom',

    scrub: true, // Attiva lo scrub per un'animazione legata allo scroll
    markers: false,
    toggleActions: 'play none none reverse',
  })
}