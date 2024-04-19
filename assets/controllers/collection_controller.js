import { Controller } from 'stimulus';

export default class extends Controller {
    static targets = ['items'];

    connect() {
        this.wrapper = this.itemsTarget;
        this.count = this.wrapper.dataset.index;
    }

    addAssociation(event) {
        event.preventDefault();
        const content = this.wrapper.dataset.prototype.replace(/__name__/g, this.count);
        this.wrapper.insertAdjacentHTML('beforeend', content);
        this.count++;
    }

    removeAssociation(event) {
        event.preventDefault();
        event.target.closest('.input-media').remove();
    }
}
