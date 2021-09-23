import Modal from 'bootstrap/js/src/modal';
window.$ = window.jQuery = require('jquery');

window.addEventListener('DOMContentLoaded', function (event) {
    $('body').on('click', '[js-new-ajax-button]', function(e) {
            e.preventDefault();
            AddNewProperty.handleAddClickButton($(this));
    });
});


const AddNewProperty = {

    modal: null,

    serializeForm: function(form) {
        const formData = new FormData(form[0]);
        return formData;
    },

    setModalContent(widget, data){

        const field = widget.find('[data-ea-ajax-new-endpoint-url]');
        const url = field.data('ea-ajax-new-endpoint-url');
        const html = $(data);
        const title = html.find('.content-header-title .title').html();
        const form = html.find('.ea-new-form');

        const footer = html.find('.page-actions');
        footer.find('[value="saveAndAddAnother"]').remove();

        const modal = widget.find('.create-entity-modal');

        modal.find('.modal-title').html(title);
        modal.find('.modal-body').html(form);
        modal.find('.modal-footer').html(footer);

        form.on('submit', function(e){
            e.preventDefault();

            const jForm = $(this);
            const data = AddNewProperty.serializeForm(jForm);
            data.append('ea[newForm][btn]', 'saveAndContinue');
            data.append('fromModal', 1);
            const method = jForm.attr('method');

            $.ajax({
                url: url,
                type: method,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                headers: {'X-Requested-With': 'AssociationField'},
                success: function (result, status, xhr) {
                    let id = xhr.getResponseHeader("x-crud-entity-id");
                    let name = xhr.getResponseHeader("x-crud-entity-name");
                    if(id && name){
                        let instance = field[0].tomselect;
                        instance.addOption({ [instance.settings.valueField]: id, [instance.settings.labelField]: name })
                        instance.refreshOptions(false)
                        instance.addItem(id)
                        instance.refreshItems()
                        modal[0].bsModal.hide();
                    }else(
                        AddNewProperty.setModalContent(widget, result)
                    )
                }
            });
        });
    },

    handleAddClickButton: function(addButton) {

        const parent = addButton.parents(".form-widget");
        const modal = parent.find('.create-entity-modal');
        modal[0].bsModal = new Modal(modal[0])

        const field = parent.find('[data-ea-ajax-new-endpoint-url]');
        const url = field.data('ea-ajax-new-endpoint-url');

        const iconElement = addButton.find('.fa');
        const originalIcon = iconElement.attr('class');
        iconElement.removeClass();
        iconElement.addClass('fa fa-spinner fa-spin pr-1');
        addButton.attr('disabled', true);

        if(url) {
            $.get(url, function(data){

                AddNewProperty.setModalContent(parent, data)

                modal[0].bsModal.show();

                addButton.attr('disabled', false);
                iconElement.removeClass();
                iconElement.addClass(originalIcon);
            });
        }
    }
};
