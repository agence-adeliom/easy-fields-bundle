import Sortable from 'sortablejs';
window.$ = window.jQuery = require('jquery');


const eaSortableCollectionHandler = function (event) {
    document.querySelectorAll('button.field-sortable_collection-add-button:not(.processed)').forEach((addButton) => {
        const collection = addButton.closest('[data-ea-collection-field]');
        let level = 0;
        let levelItem = addButton;
        if (!collection || addButton.classList.contains('processed')) {
            return;
        }
        collection.dataset.level = EaSortableCollectionProperty.queryParentsSelectorAll('[data-ea-collection-field]', addButton).length;
        EaSortableCollectionProperty.handleAddButton(addButton, collection);
        EaSortableCollectionProperty.updateCollectionItemCssClasses(collection);
        EaSortableCollectionProperty.updateCollectionSortable(collection);
    });

    document.querySelectorAll('button.field-sortable_collection-add-button[disabled]').forEach((addButton) => {
        addButton.disabled = false;
    });

    document.querySelectorAll('button.field-sortable_collection-delete-button').forEach((deleteButton) => {
        deleteButton.addEventListener('click', () => {
            const collection = deleteButton.closest('[data-ea-collection-field]');

            deleteButton.closest('.form-group').remove();
            document.dispatchEvent(new Event('ea.collection.item-removed'));

            EaSortableCollectionProperty.updateCollectionItemCssClasses(collection);
            EaSortableCollectionProperty.updateCollectionSortable(collection);
        });
    });
}

window.addEventListener('DOMContentLoaded', eaSortableCollectionHandler);
document.addEventListener('ea.collection.item-added', eaSortableCollectionHandler);

const EaSortableCollectionProperty = {
    queryParentsSelector: function(selector, elm){
        for(var parent = elm.parentElement; parent != null; parent = parent.parentElement) {
            if(parent.matches && parent.matches(selector)) return parent;
        }
        return null;
    },
    queryParentsSelectorAll: function(selector, elm){
        var result = [];
        for(var parent = elm.parentElement; parent != null; parent = parent.parentElement) {
            if(typeof selector == "undefined" || selector === "")
                result.push(parent);
            else if(parent.matches && parent.matches(selector))
                result.push(parent);
        }
        return result;
    },
    handleAddButton: (addButton, collection) => {
        addButton.addEventListener('click', function(e) {
            const isArrayCollection = collection.classList.contains('field-array');
            // Use a counter to avoid having the same index more than once
            const numItems = parseInt(collection.dataset.numItems, 10) + 1;

            // Remove the 'Empty Collection' badge, if present
            const emptyCollectionBadge = this.parentElement.querySelector('.collection-empty');
            if (null !== emptyCollectionBadge) {
                emptyCollectionBadge.outerHTML = isArrayCollection ? '<div class="ea-form-collection-items"></div>' : '<div class="ea-form-collection-items"><div class="accordion border-0 shadow-none"><div class="form-widget-compound"><div></div></div></div></div>';
            }

            const formName = this.closest('.ea-edit-form').getAttribute('name');
            // TODO : Should be dynamic ?
            const panel = 'content';

            const formTypeNamePlaceholder = parseInt(collection.dataset.formTypeNamePlaceholder, 10);
            const attributesRegexp = new RegExp(`(${formName}_${panel}_${formTypeNamePlaceholder}_[a-zA-Z0-9]*_)${formTypeNamePlaceholder}`, 'g');
            const nameRegexp = new RegExp(`(${formName}\\[${panel}\\]\\[${formTypeNamePlaceholder}\\]\\[[a-zA-Z0-9]*\\]\\[)${formTypeNamePlaceholder}`, 'g');

            let newItemHtml = collection.dataset.prototype
                .replace(attributesRegexp, `$1${numItems}`)
                .replace(nameRegexp, `$1${numItems}`);

            collection.dataset.numItems = numItems;
            const newItemInsertionSelector = isArrayCollection ? '.ea-form-collection-items' : '.ea-form-collection-items .accordion > .form-widget-compound > div';
            const collectionItemsWrapper = collection.querySelector(newItemInsertionSelector);

            EaSortableCollectionProperty.setInnerHTML(collectionItemsWrapper, newItemHtml).then(() => {
                // for complex collections of items, show the newly added item as not collapsed
                if (!isArrayCollection) {
                    EaSortableCollectionProperty.updateCollectionItemCssClasses(collection);
                    EaSortableCollectionProperty.updateCollectionSortable(collection);

                    const collectionItems = collectionItemsWrapper.querySelectorAll('.field-sortable_collection-item');
                    const lastElement = collectionItems[collectionItems.length - 1];
                    const lastElementCollapseButton = lastElement.querySelector('.accordion-button');
                    lastElementCollapseButton.classList.remove('collapsed');
                    const lastElementBody = lastElement.querySelector('.accordion-collapse');
                    lastElementBody.classList.add('show');
                }

                document.dispatchEvent(new Event('ea.collection.item-added'));
            })
        });

        addButton.classList.add('processed');
    },
    updateCollectionSortable: (collection) => {
        if (null === collection) {
            return;
        }

        if(collection.querySelector(".ea-form-collection-items .accordion > .form-widget-compound > div")){
            if(collection.sortable){
                collection.sortable.destroy();
                collection.sortable = null;
            }

            collection.sortable = Sortable.create(collection.querySelector(".ea-form-collection-items .accordion > .form-widget-compound > div"),{
                handle: '.field-sortable_collection-drag-button',
                direction: 'vertical',
                onEnd: function (evt) {
                    EaSortableCollectionProperty.updateCollectionItemCssClasses(collection);
                },
            });
        }
    },
    updateCollectionItemCssClasses: (collection) => {
        if (null === collection) {
            return;
        }

        const collectionItems = collection.querySelectorAll('.field-sortable_collection-item');
        collectionItems.forEach((item, key) => {
            item.querySelectorAll('[name]').forEach((input) => {
                if (!input.name){
                    return;
                }
                let index = input.name.match(/\[\d+\]/g);
                if (index){
                    var i = 0;
                    input.name = input.name.replace(/\[\d+\]/g,function (match, pos, original) {
                        i++;
                        return (i == collection.dataset.level) ? '['+key+']' : match;
                    })
                }
            })
        })

        collectionItems.forEach((item) => item.classList.remove('field-sortable_collection-item-first', 'field-sortable_collection-item-last'));


        const firstElement = collectionItems[0];
        if (undefined === firstElement) {
            return;
        }
        firstElement.classList.add('field-sortable_collection-item-first');

        const lastElement = collectionItems[collectionItems.length - 1];
        if (undefined === lastElement) {
            return;
        }
        lastElement.classList.add('field-sortable_collection-item-last');
    },
    loadStyle(src) {
        return new Promise(function (resolve, reject) {
            let link = document.createElement('link');
            link.href = src;
            link.rel = 'stylesheet';

            link.onload = () => resolve(link);
            link.onerror = () => reject(new Error(`Style load error for ${src}`));

            document.head.append(link);
        });
    },
    loadScript(src) {
        return new Promise(function (resolve, reject) {
            let script = document.createElement('script');
            script.src = src;
            script.type = "text/javascript"

            script.onload = () => resolve(script);
            script.onerror = () => reject(new Error(`Style load error for ${src}`));

            document.head.append(script);
        });
    },
    setInnerHTML(elm, html) {
        var tmp = document.createElement("div");
        tmp.innerHTML = html;
        let remote = [];
        Array.from(tmp.querySelectorAll("script")).forEach( oldScript => {
            if(oldScript.src){
                remote.push(EaSortableCollectionProperty.loadScript(oldScript.src));
            }
        });

        return Promise.all(remote).then(values => {
            elm.insertAdjacentHTML('beforeend', html);
            Array.from(elm.lastElementChild.querySelectorAll("script")).forEach( oldScript => {
                if(!oldScript.src){
                    const newScript = document.createElement("script");
                    Array.from(oldScript.attributes).forEach( attr => newScript.setAttribute(attr.name, attr.value) );
                    newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                }
            });
        });
    }

};
