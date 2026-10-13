export default class LettersSplitter {

    constructor(element, index) {
        this.element = element;
        this.index = index;

        this.word = this.element.textContent.trim();

        this.init();
    }

    init() {
        this.splitLetters();
    }

    splitLetters() {
        let letters = this.word.split('');
        let lettersBlock = '';
        letters.map((letter) => {
            let span = document.createElement('span');
            span.classList.add('letter', 'inline-block', 'relative');
            let span_ = document.createElement('span');
            span_.classList.add('regular', 'inline-block', 'opacity-30');
            let span__ = document.createElement('span');
            span__.classList.add('bold', 'absolute', 'top-0', 'left-0', 'overflow-hidden', 'transition-width', 'w-0');
            span_.textContent = letter;
            span__.textContent = letter;
            span.innerHTML = span_.outerHTML + span__.outerHTML;
            lettersBlock += span.outerHTML;
        })

        this.element.innerHTML = lettersBlock;
    }

    static init() {
        console.log('-- LettersSplitter init --');
        LettersSplitter.items = Array.from(document.querySelectorAll('.split-words.split-letters .word')).map(
            (element, index) => new LettersSplitter(element, index)
        );
    }
}