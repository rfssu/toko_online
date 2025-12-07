globalThis.objectFlat = function (object) {
    const getEntries = (data, prefix = "") =>
        Object.entries(data).flatMap(([key, val]) =>
            Object(val) === val
                ? getEntries(val, `${prefix}${key}.`)
                : [[`${prefix}${key}`, val]]
        );
    return Object.fromEntries(getEntries(object));
};

globalThis.dump = function () {
    Array.from(arguments).forEach((i) => console.debug(i));
};

globalThis.unique = (prefix, length) => {
    prefix ??= "";
    length ??= 36;
    return `${prefix}${Date.now()}`.toString(length);
};

globalThis.empty = function () {
    return Array.from(arguments).every((e) => {
        if (e === null) {
            return true;
        }
        if (typeof e === "undefined") {
            return true;
        }
        if (typeof e === "boolean" && e == false) {
            return true;
        }
        if (typeof e === "string" && e.trim() === "") {
            return true;
        }
        return false;
    });
};

globalThis.slug = (str, len = 256) => {
    return str
        .replace(/[^a-zA-Z0-9]/g, "-")
        .replace(/-{2,}/g, "-")
        .replace(/^-{1,}/, "")
        .replace(/-{1,}$/, "")
        .substr(0, len)
        .trim();
};

globalThis.debounce = (func, wait, immediate) => {
    var timeout;
    return function () {
        let context = this,
            params = arguments;
        let fn_later = function () {
            timeout = null;
            if (!immediate) func.apply(context, params);
        };
        let callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(fn_later, wait);
        if (callNow) func.apply(context, params);
    };
};

globalThis.jsonFromScript = function (elem) {
    if ($.isPlainObject(elem)) {
        return elem;
    }
    if (typeof elem === "string") {
        elem = document.querySelector(elem);
    }
    if (elem && elem.tagName === "SCRIPT" && elem.type === "application/json") {
        return JSON.parse(elem.textContent);
    }
    return null;
};

globalThis.jsonScriptToFormFields = function (formElem, jsonData) {
    jsonData = jsonFromScript(jsonData);

    if (typeof formElem === "string") {
        formElem = document.querySelector(formElem);
    }

    if (!formElem || !jsonData) return;

    let foundFields = [];
    for (let name in jsonData) {
        $(formElem)
            .find(`[name="${name}"]`)
            .each(function () {
                foundFields.push(this);
                if (
                    this.tagName === "INPUT" &&
                    /radio|checkbox/.test(this.type)
                ) {
                    if (this.value === jsonData[name]) {
                        this.checked = true;
                    }
                } else if (this.type !== "file") {
                    this.value = jsonData[name] ?? "";
                }
            });
    }

    const flatData = objectFlat(jsonData);
    $(formElem)
        .find("[name]")
        .each(function () {
            if (foundFields.includes(this)) return;

            let name = this.name
                .replace("][", ".")
                .replace("[", ".")
                .replace("]", "");
            if (name in flatData) {
                if (
                    this.tagName === "INPUT" &&
                    /radio|checkbox/.test(this.type)
                ) {
                    if (this.value === flatData[name]) {
                        this.checked = true;
                    }
                } else {
                    this.value = flatData[name] ?? "";
                }
            }
        });

    return jsonData;
};

class FormFieldMultiple {
    constructor(options) {
        this.models = [];
        this.actions = {};
        this.modelCallback = $.noop;
        this.modelFieldCallback = $.noop;
        this.fieldPrefix;
        this.fnUnique = () => {
            this.fnUniqueCounter ??= 0;
            this.fnUniqueCounter++;
            return (Date.now() + this.fnUniqueCounter).toString(36);
        };
        this.options(options);

        if (typeof options.wrapper === "string") {
            options.wrapper = document.querySelector(options.wrapper);
        }
        this.wrapper = options.wrapper;

        let template = options.wrapper.firstElementChild;
        this.template = template.cloneNode(true);
        template.remove();

        this.models.forEach((model) => {
            this.render(model);
        });
        if (this.models.length === 0) {
            this.render();
        }
    }

    options(options) {
        options ??= {};
        for (const [k, v] of Object.entries(options)) {
            this[k] = v;
        }
    }

    model(model) {
        model ??= {};
        model.id ??= null;
        return model;
    }

    render(model) {
        let unique = this.fnUnique();
        model = this.model(model);
        model.unique = unique;

        let that = this;
        let template = this.template.cloneNode(true);

        $(template).appendTo(this.wrapper).attr("id", unique);
        $(template)
            .find("[data-action]")
            .each(function () {
                $(this).on("click", function () {
                    that.actions[this.dataset.action].call(
                        that,
                        this,
                        template,
                        model
                    );
                });
            });

        $(template)
            .find("[name]")
            .each(function () {
                let element = this;

                let key = element.name;
                let val = model[key] ?? null;

                element.name = `${that.fieldPrefix}[${
                    model.id ?? unique
                }][${key}]`;
                element.value = val;
                element.dataset.field = key;

                element.dataset.errorSelector = element.name
                    .replace("][", ".")
                    .replace("[", ".")
                    .replace("]", "");

                that.modelFieldCallback.call(that, element, model);
            });

        that.modelCallback.call(that, template, model);
    }
}
if (globalThis.FormFieldMultiple === undefined) {
    globalThis.FormFieldMultiple = FormFieldMultiple;
}

globalThis.windowDialog = (element, event, features) => {
    event?.preventDefault();

    let href = element.dataset.href || element.href;
    if (empty(href)) {
        console.error(
            `attribute href tidak ditemukan, pada element <${element.tagName}>!`
        );
        return;
    }

    features ??= {};
    features = {
        ...{
            popup: 1,
            status: 0,
            location: 0,
            titlebar: 0,
            toolbar: 0,
            menubar: 0,
            width: 768,
            height: 432,
        },
        ...features,
    };

    window.open(
        href,
        element.target !== "" ? element.target : `dialog${Date.now()}`,
        Object.entries(features)
            .map(([k, v]) => `${k}=${v}`)
            .join(",")
    );
    return;
};

globalThis.modalFormAjax = (element, event) => {
    event.preventDefault();

    if (element.tagName != "A") {
        console.error(`harus pake <A>, bukan pake <${element.tagName}>!`);
        return;
    }

    let modalElem = document.querySelector("#modal-form-ajax");
    if (empty(modalElem)) {
        console.error(`element "#modal-form-ajax" tidak ditemukan!`);
        return;
    }

    let toggleCheckbox = document.querySelector("#close-modal");
    if (toggleCheckbox) {
        toggleCheckbox.checked = true;
    }

    let modalContent = modalElem.querySelector(".modal-content");
    if (modalContent) {
        modalContent.innerHTML =
            '<div class="flex justify-center items-center p-8"><span class="loading loading-spinner loading-lg"></span></div>';

        fetch(element.href)
            .then((response) => response.text())
            .then((html) => {
                modalContent.innerHTML = html;
            })
            .catch((error) => {
                modalContent.innerHTML = `<div class="alert alert-error"><span>Error: ${error.message}</span></div>`;
            });
    }

    return;
};

globalThis.modalDeleteConfirm = function (elem, event, callback) {
    event?.preventDefault();

    if (elem.tagName != "A") {
        console.error(`harus pake <A>, bukan pake <${elem.tagName}>!`);
        return;
    }

    let modalElem = document.querySelector("#modal-delete-confirm");
    if (empty(modalElem)) {
        console.error(`element "#modal-delete-confirm" tidak ditemukan!`);
        return;
    }

    let toggleCheckbox = document.querySelector("#close-modal-delete");
    if (toggleCheckbox) {
        toggleCheckbox.checked = true;
    }

    // Set form action
    let form = modalElem.querySelector("form");
    if (form) {
        form.action = elem.href;
    }

    // Update data-key elements
    modalElem.querySelectorAll("[data-key]").forEach(function (element) {
        let val = element.dataset.val;
        dump(elem, elem.dataset);
        if (element.dataset.key in elem.dataset) {
            val = elem.dataset[element.dataset.key];
        }
        element.textContent = val;
    });
};

$.fn.formAjaxSubmit = function (options) {
    options ??= {};

    const viewErrors = function (formElem, errors) {
        let foundFields = [];
        for (let name in errors) {
            $(formElem)
                .find(`[name]`)
                .each(function () {
                    const fieldElem = this;
                    if (
                        name === fieldElem.name ||
                        name === fieldElem.dataset.errorName
                    ) {
                        foundFields.push(name);
                        $(fieldElem).addClass("input-error");

                        let errorElem = $(fieldElem).next(".text-error").get(0);

                        if (!errorElem) {
                            errorElem = $(fieldElem)
                                .siblings(".error-message")
                                .get(0);
                        }

                        if (errorElem) {
                            $(errorElem).html(errors[name]);
                        } else {
                            fieldElem.title = errors[name];
                        }
                    }
                });
        }

        let voidErrors = {};
        let notFoundFields = Object.keys(errors).filter(
            (e) => !foundFields.includes(e)
        );
        notFoundFields.forEach((field) => {
            voidErrors[field] = errors[field];
        });
        if (notFoundFields.length > 0) {
            alert(JSON.stringify(voidErrors));
        }
    };

    this.on("submit", function (event) {
        event.preventDefault();
        const formElem = this;

        $(formElem)
            .find(`[name]`)
            .each(function () {
                const fieldElem = this;
                $(fieldElem)
                    .removeClass("input-success")
                    .removeClass("input-error")
                    .removeAttr("title");
            });

        $.ajax({
            url: formElem.action,
            method: formElem.method,
            dataType: "json",
            data: $(formElem).serialize(),
        })
            .done(function (_data, _textStatus, _jqXHR) {
                if (options.doneCallback) {
                    options.doneCallback({
                        ...arguments,
                        formElem: formElem,
                    });
                } else {
                    formElem.submit();
                }
                dump("done");
            })
            .fail((jqXHR, _textStatus, _errorThrown) => {
                if (jqXHR.status === 200) {
                    if (options.doneCallback) {
                        options.doneCallback({
                            ...arguments,
                            formElem: formElem,
                        });
                    } else {
                        formElem.submit();
                    }
                }
                if (
                    jqXHR.status === 422 &&
                    jqXHR.responseJSON &&
                    jqXHR.responseJSON.errors
                ) {
                    if (options.failCallback) {
                        options.failCallback({
                            ...arguments,
                            formElem: formElem,
                        });
                    } else {
                        viewErrors(formElem, jqXHR.responseJSON.errors);
                    }
                }
            })
            .always(() => {
                $(formElem)
                    .find(`[name]:not(.input-error)`)
                    .each(function () {
                        const fieldElem = this;
                        $(fieldElem).addClass("input-success");
                    });
            });
    });
};

$.fn.pjaxCreate = function (options) {
    options ??= {};

    let container = this.get(0);
    if (empty(container.id)) {
        console.error("container pjax harus punya ID!");
        return;
    }

    $(container, document).on(
        "click",
        `a[href]:not([data-pjax="0"])`,
        (event) => {
            $.pjax.click(event, `#${container.id}`, options);
        }
    );

    $(container, document).on(
        "submit",
        `form[action]:not([data-pjax="0"])`,
        (event) => {
            $.pjax.submit(event, `#${container.id}`, options);
        }
    );
};

if ($.pjax) {
    $.extend($.pjax.defaults, {
        timeout: 1000 * 1.5,
        scrollTo: false,
    });
}

document.addEventListener("DOMContentLoaded", function () {
    let modalFormAjax = document.querySelector("#modal-form-ajax");
    let toggleCheckbox = document.querySelector("#close-modal");

    if (toggleCheckbox && modalFormAjax) {
        let modalContent = modalFormAjax.querySelector(".modal-content");

        toggleCheckbox.addEventListener("change", function () {
            if (!this.checked && modalContent) {
                modalContent.innerHTML = "";
            }
        });

        modalFormAjax.addEventListener("click", function (e) {
            if (e.target === this) {
                toggleCheckbox.checked = false;
            }
        });
    }

    let modalDeleteConfirm = document.querySelector("#modal-delete-confirm");
    let toggleCheckboxDelete = document.querySelector("#close-modal-delete");

    if (toggleCheckboxDelete && modalDeleteConfirm) {
        modalDeleteConfirm.addEventListener("click", function (e) {
            if (e.target === this) {
                toggleCheckboxDelete.checked = false;
            }
        });
    }
});

$(function () {
    let elem = document.querySelector(document.body.dataset.wait ?? "#wait");
    if (elem) {
        $(document).ajaxSend(() => {
            elem.classList.remove("hidden");
            elem.classList.add("flex");
        });
        $(document).ajaxComplete(() => {
            elem.classList.add("hidden");
            elem.classList.remove("flex");
        });
        $(() => {
            elem.classList.add("hidden");
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("[data-tip]").forEach(function (element) {
        if (!element.classList.contains("tooltip")) {
            element.classList.add("tooltip");
        }
    });
});
