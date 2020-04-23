export function setCookie(name, value, options = {}) {
    options = {
        path: '/',
        ...options,
    };

    if (options.expires instanceof Date) {
        options.expires = options.expires.toUTCString();
    }

    let updatedCookie = encodeURI(name) + "=" + encodeURIComponent(value);

    for (let optionKey in options) {
        updatedCookie += "; " + optionKey;
        let optionValue = options[optionKey];
        if (optionValue !== true) {
            updatedCookie += "=" + optionValue;
        }
    }

    document.cookie = updatedCookie;
}

export function getCookie(name) {
    function escape(s) {
        // eslint-disable-next-line no-useless-escape
        return s.replace(/([.*+?\^${}()|\[\]\/\\])/g, '\\$1');
    }

    const match = document.cookie.match(RegExp('(?:^|;\\s*)' + escape(name) + '=([^;]*)'));
    return match ? decodeURIComponent(match[1]) : null;
}

export function deleteCookie(name) {
    setCookie(name, "", {
        'max-age': -1
    });
}
