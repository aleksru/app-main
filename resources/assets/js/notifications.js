window.iziToast = require('izitoast');


/**
 * Настройки iziToast для всплывающего и модального сообщения.
 *
 * @type {{notification: {maxWidth: number, layout: number, position: string, theme: string, transitionIn: string, transitionOut: string, transitionInMobile: string, transitionOutMobile: string}, modal: {layout: number, timeout: boolean, overlay: boolean, close: boolean, overlayClose: boolean, position: string, maxWidth: number, transitionIn: string, transitionOut: string, transitionInMobile: string, transitionOutMobile: string}}}
 */
const settings = {
    notification: {
        maxWidth: 400,
        layout: 2,
        position: 'topRight',
        theme: 'light',
        transitionIn: 'bounceInDown',
        transitionOut: 'fadeOutUp',
        transitionInMobile: 'fadeInUp',
        transitionOutMobile: 'fadeOutDown',
        timeout: 4000,
    },

    modal: {
        layout: 2,
        timeout: false,
        overlay: true,
        close: true,
        overlayClose: true,
        position: 'center',
        maxWidth: 450,
        transitionIn: 'bounceInDown',
        transitionOut: 'fadeOutUp',
        transitionInMobile: 'fadeInUp',
        transitionOutMobile: 'fadeOutDown',
    }
}

module.exports = {

    /**
     * Выводит успешное сообщение.
     *
     * @param message
     * @param options
     * @returns {string}
     */
    success(message, options = { }) {
        let id = options.id || 'toast-' + (new Date().getUTCMilliseconds());
        iziToast.show(Object.assign(settings[options.type || 'notification'], {
            id: id,
            title: options.title || 'Успех!',
            message: message,
            timeout: options.timeout || 4000,
            color: '#81c784',
            icon: 'fa fa-check',
            buttons: [],
        }));
        return id;
    },

    /**
     * Выводит сообщение ошибки.
     *
     * @param message
     * @param options
     * @returns {string}
     */
    error(message, options = { }) {
        let id = options.id || 'toast-' + (new Date().getUTCMilliseconds());
        iziToast.show(Object.assign(settings[options.type || 'notification'], {
            id: id,
            title: options.title || 'Ошибка!',
            message: message,
            timeout: options.timeout || 4000,
            color: '#ef5350',
            icon: 'fa fa-ban',
            buttons: [],
        }));
        return id;
    },

    /**
     * Выводит сообщение предупреждения.
     *
     * @param message
     * @param options
     * @returns {string}
     */
    warning(message, options = { }) {
        let id = options.id || 'toast-' + (new Date().getUTCMilliseconds());
        iziToast.show(Object.assign(settings[options.type || 'notification'], {
            id: id,
            title: options.title || 'Предупреждение!',
            message: message,
            timeout: options.timeout || 4000,
            color: '#ffb74d',
            icon: 'fa fa-warning',
            buttons: [],
        }));
        return id;
    },

    /**
     * Выводит информационное сообщение.
     *
     * @param message
     * @param options
     * @returns {string}
     */
    info(message, options = { }) {
        let id = options.id || 'toast-' + (new Date().getUTCMilliseconds());
        iziToast.show(Object.assign(settings[options.type || 'notification'], {
            id: id,
            title: options.title || 'Внимание!',
            message: message,
            timeout: options.timeout || 4000,
            color: '#4fc3f7',
            icon: 'fa fa-info',
            buttons: [],
        }));
        return id;
    },

    /**
     * Выводит сообщение загрузки.
     *
     * @param message
     * @param options
     * @returns {string}
     */
    loading(message, options = { }) {
        let id = options.id || 'toast-' + (new Date().getUTCMilliseconds());
        iziToast.show(Object.assign(settings[options.type || 'notification'], {
            id: id,
            title: options.title || 'Загрузка...',
            message: message,
            timeout: options.timeout || false,
            color: '#ffb74d',
            icon: 'fa fa-spinner fa-spin',
            animateInside: false,
            buttons: [],
        }));
        return id;
    },

    /**
     * Выводит диалоговое окно подтверждения.
     *
     * @param message
     * @param confirm
     * @param cancel
     * @param options
     * @returns {string}
     */
    confirm(message, confirm, cancel, options = { }) {
        let id = options.id || 'toast-' + (new Date().getUTCMilliseconds());
        iziToast.show(Object.assign(settings[options.type || 'modal'], {
            id: id,
            title: options.title || 'Внимание!',
            message: message,
            timeout: options.timeout || false,
            color: '#fff176',
            icon: 'fa fa-question',
            buttons: [
                [`<button><b>Да</b></button>`, function (instance, toast) {
                    instance.hide({
                        transitionOut: 'fadeOutUp',
                        transitionOutMobile: 'fadeOutDown',
                    }, toast);
                    if (typeof confirm === 'function')
                        confirm();
                }],
                [`<button>Нет</button>`, function (instance, toast) {
                    instance.hide({
                        transitionOut: 'fadeOutUp',
                        transitionOutMobile: 'fadeOutDown',
                    }, toast);
                    if (cancel && typeof cancel === 'function')
                        cancel();
                }, true]
            ]
        }));
        return id;
    },

    /**
     * Скрывает сообщение.
     *
     * @param id
     * @returns {*}
     */
    hide(id) {
        if (!id)
            return null;
        let toast = document.querySelector(`#${id}`);
        if (toast) {
            iziToast.hide({
                transitionOut: 'fadeOutUp',
                transitionOutMobile: 'fadeOutDown',
            }, toast);
        }
        return true;
    },
};