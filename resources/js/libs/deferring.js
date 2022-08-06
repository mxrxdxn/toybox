/**
 * Enable lazy-loading features.
 *
 * @param images           Enable lazy loading for image tags (add data-lazy="src" to the tag.)
 * @param backgroundImages Enable lazy loading for background images (add data-lazy="src" to the tag.)
 * @param css              Enable lazy loading for CSS (add data-lazy-css="href" to the tag.)
 * @param js               Enable lazy loading for JS (add data-lazy-js="src" to the tag.)
 */
export const lazyLoading = (images = true, backgroundImages = true, css = true, js = true) => {
    // Lazy load images
    if (images === true) {
        lazyLoadImages();
    }

    // Lazy load background images
    if (backgroundImages === true) {
        lazyLoadBackgroundImages();
    }

    // Lazy load CSS
    if (css === true) {
        lazyLoadCSS();
    }

    // Lazy load JS
    if (js === true) {
        lazyLoadJS();
    }
}

const lazyLoadImages = () => {
    let lazyLoadImages;

    if ("IntersectionObserver" in window) {
        lazyLoadImages = document.querySelectorAll("img[data-lazy]");
        let imageObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    let image = entry.target;
                    image.src = image.dataset.lazy;
                    imageObserver.unobserve(image);
                }
            });
        });

        lazyLoadImages.forEach(image => {
            imageObserver.observe(image);
        });
    } else {
        let lazyLoadThrottleTimeout;

        lazyLoadImages = document.querySelectorAll("img[data-lazy]");

        const lazyLoad = () => {
            if (lazyLoadThrottleTimeout) {
                clearTimeout(lazyLoadThrottleTimeout);
            }

            lazyLoadThrottleTimeout = setTimeout(() => {
                let scrollTop = window.pageYOffset;

                lazyLoadImages.forEach(img => {
                    if (img.offsetTop < (window.innerHeight + scrollTop)) {
                        img.src = img.dataset.lazy;
                    }
                });

                if (lazyLoadImages.length === 0) {
                    document.removeEventListener("scroll",          lazyLoad);
                    window.removeEventListener("resize",            lazyLoad);
                    window.removeEventListener("orientationChange", lazyLoad);
                }
            }, 20);
        }

        document.addEventListener("scroll",          lazyLoad);
        window.addEventListener("resize",            lazyLoad);
        window.addEventListener("orientationChange", lazyLoad);
    }
}

const lazyLoadBackgroundImages = () => {
    let lazyLoadBackgroundImages;

    if ("IntersectionObserver" in window) {
        lazyLoadBackgroundImages = document.querySelectorAll("[data-lazy]:not(img)");

        let elementObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    let element = entry.target;
                    element.style.backgroundImage = `url("${element.dataset.lazy}")`;
                    element.removeAttribute('data-lazy');
                    elementObserver.unobserve(element);
                }
            });
        });

        lazyLoadBackgroundImages.forEach(image => {
            elementObserver.observe(image);
        });
    } else {
        let lazyLoadThrottleTimeout;

        lazyLoadBackgroundImages = document.querySelectorAll("[data-lazy]:not(img)");

        const lazyLoad = () => {
            if (lazyLoadThrottleTimeout) {
                clearTimeout(lazyLoadThrottleTimeout);
            }

            lazyLoadThrottleTimeout = setTimeout(() => {
                let scrollTop = window.pageYOffset;

                lazyLoadBackgroundImages.forEach(element => {
                    if (element.offsetTop < (window.innerHeight + scrollTop)) {
                        element.style.backgroundImage = `url("${element.dataset.lazy}")`;
                        element.removeAttribute('data-lazy');
                    }
                });

                if (lazyLoadBackgroundImages.length === 0) {
                    document.removeEventListener("scroll",          lazyLoad);
                    window.removeEventListener("resize",            lazyLoad);
                    window.removeEventListener("orientationChange", lazyLoad);
                }
            }, 20);
        }

        document.addEventListener("scroll",          lazyLoad);
        window.addEventListener("resize",            lazyLoad);
        window.addEventListener("orientationChange", lazyLoad);
    }
}

const lazyLoadCSS = () => {
    let lazyLoadCSS;

    if ("IntersectionObserver" in window) {
        lazyLoadCSS = document.querySelectorAll("[data-lazy-css]");

        let elementObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    let element    = entry.target;
                    let stylesheet = document.createElement('link');

                    stylesheet.setAttribute('rel', 'stylesheet');
                    stylesheet.setAttribute('href', element.dataset.lazyCss)

                    document.querySelector('head').appendChild(stylesheet);

                    elementObserver.unobserve(element);
                }
            });
        });

        lazyLoadCSS.forEach(image => {
            elementObserver.observe(image);
        });
    } else {
        let lazyLoadThrottleTimeout;

        lazyLoadCSS = document.querySelectorAll("[data-lazy-css]");

        const lazyLoad = () => {
            if (lazyLoadThrottleTimeout) {
                clearTimeout(lazyLoadThrottleTimeout);
            }

            lazyLoadThrottleTimeout = setTimeout(() => {
                let scrollTop = window.pageYOffset;

                lazyLoadCSS.forEach(element => {
                    if (element.offsetTop < (window.innerHeight + scrollTop)) {
                        let stylesheet = document.createElement('link');

                        stylesheet.setAttribute('rel', 'stylesheet');
                        stylesheet.setAttribute('href', element.dataset.lazyCss)

                        document.querySelector('head').appendChild(stylesheet);
                    }
                });

                if (lazyLoadCSS.length === 0) {
                    document.removeEventListener("scroll",          lazyLoad);
                    window.removeEventListener("resize",            lazyLoad);
                    window.removeEventListener("orientationChange", lazyLoad);
                }
            }, 20);
        }

        document.addEventListener("scroll",          lazyLoad);
        window.addEventListener("resize",            lazyLoad);
        window.addEventListener("orientationChange", lazyLoad);
    }
}

const lazyLoadJS = () => {
    let lazyLoadJS;

    if ("IntersectionObserver" in window) {
        lazyLoadJS = document.querySelectorAll("[data-lazy-js]");

        let elementObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    let element    = entry.target;
                    let script = document.createElement('script');

                    script.setAttribute('src', element.dataset.lazyJs)

                    document.querySelector('head').appendChild(script);

                    elementObserver.unobserve(element);
                }
            });
        });

        lazyLoadJS.forEach(image => {
            elementObserver.observe(image);
        });
    } else {
        let lazyLoadThrottleTimeout;

        lazyLoadJS = document.querySelectorAll("[data-lazy-js]");

        const lazyLoad = () => {
            if (lazyLoadThrottleTimeout) {
                clearTimeout(lazyLoadThrottleTimeout);
            }

            lazyLoadThrottleTimeout = setTimeout(() => {
                let scrollTop = window.pageYOffset;

                lazyLoadJS.forEach(element => {
                    if (element.offsetTop < (window.innerHeight + scrollTop)) {
                        let script = document.createElement('script');

                        script.setAttribute('src', element.dataset.lazyJs)

                        document.querySelector('head').appendChild(script);
                    }
                });

                if (lazyLoadJS.length === 0) {
                    document.removeEventListener("scroll",          lazyLoad);
                    window.removeEventListener("resize",            lazyLoad);
                    window.removeEventListener("orientationChange", lazyLoad);
                }
            }, 20);
        }

        document.addEventListener("scroll",          lazyLoad);
        window.addEventListener("resize",            lazyLoad);
        window.addEventListener("orientationChange", lazyLoad);
    }
}