// Utils
export function generateRandomCode() {
    return Math.random().toString(36).slice(2, 10).toUpperCase();
};

export function generateSecureRandomCode(length) {
    const array = new Uint8Array(length / 2);
    window.crypto.getRandomValues(array);
    return Array.from(array, byte => ('0' + byte.toString(16)).slice(-2)).join('').toUpperCase();
};

// Function not necessary to comprehend
export function scrollToSmoothly(scrollTo, duration) {
    var start = window.scrollY || document.documentElement.scrollTop,
        change = scrollTo - start,
        currentTime = 0,
        increment = 20;

    var animateScroll = function () {
        currentTime += increment;
        var val = Math.easeInOutQuad(currentTime, start, change, duration);
        window.scrollTo(0, val);
        if (currentTime < duration) {
            requestAnimationFrame(animateScroll);
        }
    };

    animateScroll();
}

// Not necessary to comprehend
// Add the easeInOutQuad function to the Math object for smooth interpolation
Math.easeInOutQuad = function (t, b, c, d) {
    t /= d / 2;
    if (t < 1) return c / 2 * t * t + b;
    t--;
    return -c / 2 * (t * (t - 2) - 1) + b;
};

export function createElement(tag, attributes, ...children) {
    const element = document.createElement(tag);
    for (const key in attributes) {
        element.setAttribute(key, attributes[key]);
    }
    for (const child of children) {
        element.appendChild(child);
    }
    return element;
}

export function onlyNumber(input) {
    // input.addEventListener('keydown', function(event) {
    //     if (event.key.length === 1 && !/[0-9]/.test(event.key)) {
    //         event.preventDefault();
    //     }
    // });
    input.addEventListener('input', function(event) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
}

export function encodeQueryString(query) {
    const replacements = {
        ' ': '%20',
        '"': '%22',
        '#': '%23',
        '$': '%24',
        '%': '%25',
        '&': '%26',
        '\'': '%27',
        '(': '%28',
        ')': '%29',
        '*': '%2A',
        '+': '%2B',
        ',': '%2C',
        '/': '%2F',
        ':': '%3A',
        ';': '%3B',
        '<': '%3C',
        '=': '%3D',
        '>': '%3E',
        '?': '%3F',
        '@': '%40',
        '[': '%5B',
        '\\': '%5C',
        ']': '%5D',
        '^': '%5E',
        '_': '%5F',
        '`': '%60',
        '{': '%7B',
        '|': '%7C',
        '}': '%7D',
        '~': '%7E'
    };

    return query.split('').map(char => replacements[char] || char).join('');
}
