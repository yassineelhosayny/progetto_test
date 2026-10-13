export default class WordsSplitter {

    constructor(block, index) {
        this.block = block;
        this.index = index;

        this.punctuation = ['.', ',', '!', '?', ';', ':'];
        this.init();
    }

    init() {
        this.splitWords();
    }

    splitWords() {
        let text = this.block.textContent.trim();
        let words = text.split(' ');
        let wordsBlock = '';
        console.log(words);

        words.map((word) => {
            let span = document.createElement('span');
            span.classList.add('word', 'inline-block', 'mx-1', 'text-2xl');
            span.textContent = word;
            wordsBlock += span.outerHTML;
        })

        this.block.innerHTML = wordsBlock;
    }

    static init() {
        console.debug('-- WordsSplitter init --');
        WordsSplitter.items = Array.from(document.querySelectorAll('.split-words')).map(
            (block, index) => new WordsSplitter(block, index)
        );
    }
}