const eaCollectionHandler = function (event) {
    document.querySelectorAll('button.field-collection-add-button').forEach((addButton) => {
        const collection = addButton.closest('[data-ea-collection-field]');

        if (!collection || collection.classList.contains('processed')) {
            return;
        }

        EaCollectionProperty.handleAddButton(addButton, collection);
        EaCollectionProperty.updateCollectionItemCssClasses(collection);
    });

    document.querySelectorAll('button.field-collection-delete-button').forEach((deleteButton) => {
        deleteButton.addEventListener('click', () => {
            const collection = deleteButton.closest('[data-ea-collection-field]');
            const item = deleteButton.closest('.field-collection-item');

            item.remove();
            document.dispatchEvent(new Event('ea.collection.item-removed'));

            EaCollectionProperty.updateCollectionItemCssClasses(collection);
        });
    });
}

window.addEventListener('DOMContentLoaded', eaCollectionHandler);
document.addEventListener('ea.collection.item-added', eaCollectionHandler);

const EaCollectionProperty = {
    handleAddButton: (addButton, collection) => {
        addButton.addEventListener('click', function() {
            const isArrayCollection = collection.classList.contains('field-array');
            // Use a counter to avoid having the same index more than once
            let numItems = parseInt(collection.dataset.numItems);

            // Remove the 'Empty Collection' badge, if present
            const emptyCollectionBadge = this.parentElement.querySelector('.collection-empty');
            if (null !== emptyCollectionBadge) {
                emptyCollectionBadge.outerHTML = isArrayCollection ? '<div class="ea-form-collection-items"></div>' : '<div class="ea-form-collection-items"><div class="accordion"><div class="form-widget-compound"></div></div></div>';
            }

            const formName = this.closest('.ea-edit-form, .ea-new-form').getAttribute('name');

            let panel = 'content';
            let placeholderName = parseInt(collection.dataset.formTypeNamePlaceholder, 10);
            placeholderName = isNaN(placeholderName) ? collection.dataset.formTypeNamePlaceholder : placeholderName;
            let placeholderNumber = placeholderName;
            let attributesRegexp;
            let nameRegexp;

            if(collection.dataset.prototype.match(new RegExp(`${formName}_${panel}`)) !== null){
                // we're inside the content collection, in a second level collection

                // if true, it means it was already in the content when the page was loaded
                // and we need to get the position first to be able to update the name attributes
                // example : Page_content_1_list___name___puce
                if(placeholderName === '__name__'){
                    const parent = collection.closest(`[id^="${formName}_${panel}_"`);
                    const parentPosition = parent.getAttribute('id').replace(`${formName}_${panel}_`, '');
                    placeholderName = parentPosition;
                }else{
                    // then we're in a newly loaded block with already defined position in the prototype
                    // example : Page_content_9_list_9_puce
                }

                attributesRegexp = new RegExp(`(${formName}_${panel}_${placeholderName}_[a-zA-Z0-9]*_)${placeholderNumber}`, 'g');
                nameRegexp = new RegExp(`(${formName}\\[${panel}\\]\\[${placeholderName}\\]\\[[a-zA-Z0-9]*\\]\\[)${placeholderNumber}`, 'g');
            }else{

                // we're not inside the content collection but direclty in a first level collection
                const matches = collection.dataset.prototype.match(new RegExp(`${formName}_([a-zA-Z0-9]+)___name__.*\"`));
                if(matches !== null && matches.length > 1){
                    panel = matches[1];
                    attributesRegexp = new RegExp(`(${formName}_${panel}_)${placeholderName}`, 'g');
                    nameRegexp = new RegExp(`(${formName}\\[${panel}\\]\\[)${placeholderName}`, 'g');
                }
            }

            let newItemHtml = collection.dataset.prototype
                .replace(attributesRegexp, `$1${++numItems}`)
                .replace(nameRegexp, `$1${numItems}`);

            collection.dataset.numItems = numItems;
            const newItemInsertionSelector = isArrayCollection ? '.ea-form-collection-items' : '.ea-form-collection-items .accordion > .form-widget-compound';
            const collectionItemsWrapper = collection.querySelector(newItemInsertionSelector);

            collectionItemsWrapper.insertAdjacentHTML('beforeend', newItemHtml);
            // for complex collections of items, show the newly added item as not collapsed
            if (!isArrayCollection) {
                EaCollectionProperty.updateCollectionItemCssClasses(collection);

                const collectionItems = collectionItemsWrapper.querySelectorAll('.field-collection-item');
                const lastElement = collectionItems[collectionItems.length - 1];
                const lastElementCollapseButton = lastElement.querySelector('.accordion-button');
                lastElementCollapseButton.classList.remove('collapsed');
                const lastElementBody = lastElement.querySelector('.accordion-collapse');
                lastElementBody.classList.add('show');
            }

            document.dispatchEvent(new Event('ea.collection.item-added'));
        });

        collection.classList.add('processed');
    },

    updateCollectionItemCssClasses: (collection) => {
        if (null === collection) {
            return;
        }

        const collectionItems = collection.querySelectorAll('.field-collection-item');
        collectionItems.forEach((item) => item.classList.remove('field-collection-item-first', 'field-collection-item-last'));

        const firstElement = collectionItems[0];
        if (undefined === firstElement) {
            return;
        }
        firstElement.classList.add('field-collection-item-first');

        const lastElement = collectionItems[collectionItems.length - 1];
        if (undefined === lastElement) {
            return;
        }
        lastElement.classList.add('field-collection-item-last');
    }
};