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
