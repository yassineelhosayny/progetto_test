export default class NewsModal {

    constructor(item, index) {
        this.item = item;
        this.index = index;
        this.btn = this.item.querySelector('.btn-container .btn-open');
        this.modal = document.querySelector(`[data-modal="modal-${this.index}"]`);

        this.openModal = this.openModal.bind(this);
        this.closeModal = this.closeModal.bind(this);

        this.init()
    }

    init() {
        this.addListeners();
    }

    addListeners() {
        this.btn.addEventListener('click', this.openModal);
        this.modal.addEventListener('click', this.closeModal);
    }

    openModal() {
        this.modal.classList.add('open');
    }

    closeModal() {
        this.modal.classList.remove('open');
    }

    static init() {
        console.debug('-- NewsModal init --')
        NewsModal.items = Array.from(document.querySelectorAll('.modal-opener')).map(
            (item, index) => new NewsModal(item, index)
        )
    }
}