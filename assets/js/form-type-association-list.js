import Modal from 'bootstrap/js/src/modal';
window.$ = window.jQuery = require('jquery');

window.addEventListener('DOMContentLoaded', function (event) {
    $('body').on('click', '[js-list-button]', function (e) {
        e.preventDefault();
        AddListProperty.init($(this))
    });

    const modalBody = '.create-entity-modal .content-body';
    $('body').on('click', `${modalBody} a`, function (e) {
        AddListProperty.handleInnerLink(e, $(this));
    });

    $('body').on('submit', `${modalBody} form`, function (e) {
        AddListProperty.handleInnerForm(e, $(this));
    });

    $('body').on('click', `${modalBody} tr`, function (e) {
        e.preventDefault();
        AddListProperty.handleRowSelection($(this));
    });

    $('body').on('click', '[js-list-cancel]', function (e) {
        e.preventDefault();
        AddListProperty.cancel();
    });

    $('body').on('click', '[js-list-select]', function (e) {
        e.preventDefault();
        AddListProperty.selectValues();
    });
});


const AddListProperty = {

    selection: [],
    field: null,
    selectionClass: 'table-primary',
    dataProps: 'ea-ajax-index-url',
    url: null,

    button: null,

    columns: null,

    isMultiple: function () {
        return AddListProperty.field.prop('multiple');
    },

    cancel: function () {
        AddListProperty.selection = [];
        AddListProperty.modalToggle.hide();
    },


    getRowName: function (jRow) {
        if (AddListProperty.columns && Array.isArray(AddListProperty.columns.columns)) {
            let values = [];
            const nbCol = AddListProperty.columns.columns.length;

            for (let i = 0; i < nbCol; i++) {
                const col = AddListProperty.columns.columns[i];
                const val = jRow.find(`td:nth-child(${col})`).text().trim();
                if (val) {
                    values.push(val);
                }
            }
            return values.join(` ${AddListProperty.columns.separator ? AddListProperty.columns.separator : '-'} `)
        }
        return jRow.find(`td:first`).text().trim();
    },


    selectValues: function () {

        if (AddListProperty.selection.length) {

            AddListProperty.field.val(AddListProperty.selection).trigger('change');

            for (let i in AddListProperty.selection) {
                let id = AddListProperty.selection[i];

                let name = AddListProperty.getRowName(AddListProperty.modal.find(`[data-id="${id}"]`));
                let value = {text: name, id: id};
                if ('ea-autocomplete' === AddListProperty.field.data('ea-widget')) {
                    let instance = AddListProperty.field[0].tomselect;
                    instance.addOption({ [instance.settings.valueField]: value.id, [instance.settings.labelField]: value.text ? value.text : ("#" + value.id) })
                    instance.refreshOptions(false)
                    instance.addItem(value.id)
                    instance.refreshItems()
                }

                if ('select2' === AddListProperty.field.data('widget')) {

                    const newOption = new Option(value.text, value.id, true, true);

                    if (!AddListProperty.field.find(`option[value="${value.id}"]`).length) {

                        AddListProperty.field.append(newOption).trigger('change');
                        AddListProperty.field.trigger({
                            type: 'select2:select',
                            params: {data: value}
                        });
                    }
                }

                if (!AddListProperty.isMultiple()) {
                    break;
                }
            }
        }
        AddListProperty.modalToggle.hide();
    },

    handleRowSelection: function (row) {
        AddListProperty.selectRow(row);
    },

    buildFooter: function (listButton) {

        const cancelLabel = listButton.data('cancel-label');
        const validateLabel = listButton.data('validate-label');

        const buttons = [
            {class: 'btn-secondary', data: 'js-list-cancel', icon: '', label: cancelLabel},
            {class: 'btn-primary', data: 'js-list-select', icon: '', label: validateLabel}
        ];

        let html = '';

        for (let i in buttons) {
            const button = buttons[i];
            html += `<button class="btn ${button.class}" ${button.data}>` +
                `<span class="btn-label">` +
                `<i class="action-icon fa ${button.icon}"></i> ${button.label}</span>` +
                `</button>`;
        }
        return html;
    },

    selectRow: function (row) {
        const id = row.data('id');

        if (-1 === AddListProperty.selection.indexOf(id)) {
            if (AddListProperty.isMultiple()) {
                AddListProperty.selection.push(id);
            } else {
                AddListProperty.selection = [id];
            }
        } else {
            AddListProperty.unselectRow(row);
        }
        AddListProperty.manageSelectionDisplay();
        },

    unselectRow: function (row) {
        const id = row.data('id');
        if (-1 !== AddListProperty.selection.indexOf(id)) {
            AddListProperty.selection.splice(AddListProperty.selection.indexOf(id), 1);
        }
    },

    // HTML5 specifies that a <script> tag inserted with innerHTML should not execute
    // https://developer.mozilla.org/en-US/docs/Web/API/Element/innerHTML#Security_considerations
    // That's why we can't use just 'innerHTML'. See https://stackoverflow.com/a/47614491/2804294
    setInnerHTML: function (element, htmlContent) {
        element.innerHTML = htmlContent;
        Array.from(element.querySelectorAll('script')).forEach(oldScript => {
            const newScript = document.createElement('script');
            Array.from(oldScript.attributes)
                .forEach(attr => newScript.setAttribute(attr.name, attr.value));
            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });
    },


    handleInnerLink: function (e, link) {

        const destUrl = link.attr('href');
        const isModal = link.data('modal');

        if (!isModal) {
            e.preventDefault();

            $.get(destUrl, function (data) {
                const html = $(data);
                const body = html.find('.content-body');
                AddListProperty.populateModalBody(body);
            });
        } else {
            let filterButton = e.currentTarget;
            let filterModal = document.querySelector(filterButton.dataset.modal);
            let filterModalBody = filterModal.querySelector('.modal-body');

            $(filterModal).modal({backdrop: false, keyboard: true});
            filterModalBody.innerHTML = '<div class="fa-3x px-3 py-3 text-muted text-center"><i class="fas fa-circle-notch fa-spin"></i></div>';

            $.get(filterButton.getAttribute('href'), function (response) {
                AddListProperty.setInnerHTML(filterModalBody, response);

                let header = $(filterModal).find('.modal-header').html();
                $(filterModal).find('.modal-header').empty();
                header = (header.replace(/data-dismiss="modal"/g, ''));
                $(filterModal).find('.modal-header').append(header);


                $('#modal-apply-button').on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    filterModal.querySelectorAll('.filter-checkbox:not(:checked)').forEach(notAppliedFilter => {
                        notAppliedFilter.closest('.filter-field').remove();
                    });
                    $(filterModalBody).find('form').submit();
                });

                $('#modal-clear-button').on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    filterModal.querySelectorAll('.filter-field').forEach(filterField => {
                        filterField.remove();
                    });
                    $(filterModalBody).find('form').submit();
                });

                $(filterModal).find('form').on('submit', function (e) {
                    AddListProperty.handleInnerForm(e, $(this));
                });
            });

            e.preventDefault();
            e.stopPropagation();
        }
    },

    handleInnerForm: function (e, form) {

        e.preventDefault();
        e.stopPropagation();

        const method = form.attr('method');
        let query = new URLSearchParams(new FormData(form[0])).toString();

        $.ajax({
            url: AddListProperty.url + '&' + query,
            type: method ? method : 'get',
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                const html = $(data);
                const body = html.find('.content-body');
                AddListProperty.populateModalBody(body);
            }
        });
    },

    addSelectors: function (html) {

        let rows = html.find('tr');
        rows.each(function () {
            const row = $(this);

            let actionsCell = row.find('.actions');
            const checkbox = $('<input type="checkbox" />').css('pointer-events', 'none');

            actionsCell.html(checkbox);
            row.css('cursor', 'pointer');
        });

    },

    manageSelectionDisplay: function () {

        let rows = AddListProperty.modal.find('tr');
        rows.removeClass(AddListProperty.selectionClass);
        rows.each(function () {
            let row = $(this);
            row.removeClass(AddListProperty.selectionClass);
            row.find('input[type="checkbox"]').attr('checked', false);
        });

        if (AddListProperty.selection.length) {

            for (let i in AddListProperty.selection) {
                let id = AddListProperty.selection[i];
                let row = AddListProperty.modal.find(`[data-id="${id}"]`);

                row.addClass(AddListProperty.selectionClass);
                row.find('input[type="checkbox"]').attr('checked', true);
            }
        }
    },


    //https://stackoverflow.com/questions/1714786/query-string-encoding-of-a-javascript-object
    serialize: function (obj, prefix) {
        var str = [],
            p;
        for (p in obj) {
            if (obj.hasOwnProperty(p)) {
                var k = prefix ? prefix + "[" + p + "]" : p,
                    v = obj[p];
                str.push((v !== null && typeof v === "object") ?
                    AddListProperty.serialize(v, k) :
                    encodeURIComponent(k) + "=" + encodeURIComponent(v));
            }
        }
        return str.join("&");
    },


    handleListClickButton: function (listButton) {

        const iconElement = listButton.find('.fa');
        const originalIcon = iconElement.attr('class');
        iconElement.removeClass();
        iconElement.addClass('fa fa-spinner fa-spin pr-1');
        listButton.attr('disabled', true);

        let url = AddListProperty.url;
        const filters = listButton.data('filters');
        let query;

        if (filters) {
            query = AddListProperty.serialize({filters: filters});
            url += '&' + query;
        }

        if (url) {
            $.get(url, function (data) {

                const html = $(data);
                const title = html.find('.content-header-title .title').html();
                const body = html.find('.content-body');
                const footer = AddListProperty.buildFooter(listButton);

                AddListProperty.modal.find('.modal-footer').html(footer);
                AddListProperty.modal.find('.modal-title').html(title);

                AddListProperty.populateModalBody(body);

                listButton.attr('disabled', false);
                iconElement.removeClass();
                iconElement.addClass(originalIcon);

                AddListProperty.modalToggle.show()
            });
        }
    },


    populateModalBody: function (html) {

        let options = [
            {
                data: 'show-filter',
                class: 'datagrid-filters'
            },
            {
                data: 'show-search',
                class: 'datagrid-search'
            }
        ];

        for (let i in options) {
            const option = options[i];
            const showOption = AddListProperty.button.data(option.data);
            if (!showOption) {
                $(html).find('.' + option.class).remove();
            }
        }

        AddListProperty.addSelectors(html);
        AddListProperty.modal.find('.modal-body').html(html);
        AddListProperty.manageSelectionDisplay();
    },

    init: function (listButton) {
        const parent = listButton.parents(".form-widget");

        AddListProperty.button = listButton;
        AddListProperty.field = parent.find(`[data-${AddListProperty.dataProps}]`);
        AddListProperty.modal = parent.find('.create-entity-modal');
        AddListProperty.modalToggle = new Modal(AddListProperty.modal[0])
        AddListProperty.url = AddListProperty.field.data(AddListProperty.dataProps);
        AddListProperty.columns = listButton.data('columns');

        let val = AddListProperty.field.val();

        AddListProperty.selection = [];
        if (Array.isArray(val)) {
            for (let i in val) {
                let id = val[i];
                AddListProperty.selection.push(parseInt(id));
            }
        } else {
            AddListProperty.selection = [val];
        }
        AddListProperty.handleListClickButton(listButton);
    },
};
