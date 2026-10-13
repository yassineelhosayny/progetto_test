
export default class Accordion{
    constructor(node){
        this.accordions = node;
        this.accordionItems = this.accordions.querySelectorAll('.accordion-item');
        this.listeners = []

        this.initAccordions()
    }

    initAccordions() {
		console.debug('  -> Accordions init')

		this.accordionItems.forEach((accordionItem) => {
            let accordionButton = accordionItem.querySelector('.accordion-title');

            const clickHandler = () => {
                if (!accordionItem.classList.contains('open')) {
                    this.accordionItems.forEach((accordion) => {
                        accordion.classList.remove('open')
                    });
                    accordionItem.classList.add('open')
                } else {
                    accordionItem.classList.remove('open')
                }
            };

            // Adding event listener to array
            accordionButton.addEventListener('click', clickHandler)
            this.listeners.push({ button: accordionButton, handler: clickHandler });
        })
    }

    destroy() {
        console.debug('  -> Destroying Accordions');
        this.listeners.forEach(({ button, handler }) => {
            button.removeEventListener('click', handler);
        });
        this.listeners = [];
    }

    static reset() {
        console.debug('== Resetting accordion components ==')
        if (Accordion.items) {
            Accordion.items.forEach(item => item.destroy())
        }
        Accordion.init()
    }

    static init() {
        console.debug('== Initializing accordion components ==');
        Accordion.items = Array.from(document.querySelectorAll('.accordion')).map(
            (accordions) => new Accordion(accordions)
        )
    }
}