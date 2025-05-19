/**
 * Enable lazy-loading features.
 *
 * @param images           Enable lazy loading for image tags (add data-lazy="src" to the tag.)
 * @param backgroundImages Enable lazy loading for background images (add data-lazy="src" to the tag.)
 * @param css              Enable lazy loading for CSS (add data-lazy-css="href" to the tag.)
 * @param js               Enable lazy loading for JS (add data-lazy-js="src" to the tag.)
 */
export const lazyLoading = (images = true, backgroundImages = true, css = true, js = true, attributes = true) => {
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

    // Lazy load any attribute
    if (attributes === true) {
        lazyLoadAttributes();
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
                let scrollTop = window.scrollY;

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
                let scrollTop = window.scrollY;

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

                    element.removeAttribute("data-lazy-css");

                    document.querySelector('head').appendChild(stylesheet);

                    elementObserver.unobserve(element);
                }
            });
        });

        lazyLoadCSS.forEach(element => {
            elementObserver.observe(element);
        });
    } else {
        let lazyLoadThrottleTimeout;

        lazyLoadCSS = document.querySelectorAll("[data-lazy-css]");

        const lazyLoad = () => {
            if (lazyLoadThrottleTimeout) {
                clearTimeout(lazyLoadThrottleTimeout);
            }

            lazyLoadThrottleTimeout = setTimeout(() => {
                let scrollTop = window.scrollY;

                lazyLoadCSS.forEach(element => {
                    if (element.offsetTop < (window.innerHeight + scrollTop)) {
                        let stylesheet = document.createElement('link');

                        stylesheet.setAttribute('rel', 'stylesheet');
                        stylesheet.setAttribute('href', element.dataset.lazyCss)

                        element.removeAttribute("data-lazy-css");

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
                    element.removeAttribute("data-lazy-js");

                    document.querySelector('head').appendChild(script);

                    elementObserver.unobserve(element);
                }
            });
        });

        lazyLoadJS.forEach(element => {
            elementObserver.observe(element);
        });
    } else {
        let lazyLoadThrottleTimeout;

        lazyLoadJS = document.querySelectorAll("[data-lazy-js]");

        const lazyLoad = () => {
            if (lazyLoadThrottleTimeout) {
                clearTimeout(lazyLoadThrottleTimeout);
            }

            lazyLoadThrottleTimeout = setTimeout(() => {
                let scrollTop = window.scrollY;

                lazyLoadJS.forEach(element => {
                    if (element.offsetTop < (window.innerHeight + scrollTop)) {
                        let script = document.createElement('script');

                        script.setAttribute('src', element.dataset.lazyJs)

                        element.removeAttribute("data-lazy-js");

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

const lazyLoadAttributes = () => {
    let lazyLoadAttributes;

    if ("IntersectionObserver" in window) {
        lazyLoadAttributes = document.querySelectorAll("[data-lazy-attr]");

        let elementObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    let element = entry.target;
                    let attributes = JSON.parse(element.getAttribute("data-lazy-attr"));

                    for (let key in attributes) {
                        if (attributes.hasOwnProperty(key)) {
                            let value = attributes[key];

                            element.setAttribute(key, value);
                        }
                    }

                    element.removeAttribute('data-lazy-attr');
                    elementObserver.unobserve(element);
                }
            });
        });

        lazyLoadAttributes.forEach(element => {
            elementObserver.observe(element);
        });
    } else {
        let lazyLoadThrottleTimeout;

        lazyLoadAttributes = document.querySelectorAll("[data-lazy-attr]");

        const lazyLoad = () => {
            if (lazyLoadThrottleTimeout) {
                clearTimeout(lazyLoadThrottleTimeout);
            }

            lazyLoadThrottleTimeout = setTimeout(() => {
                let scrollTop = window.scrollY;

                lazyLoadAttributes.forEach(element => {
                    if (element.offsetTop < (window.innerHeight + scrollTop)) {

                        let attributes = JSON.parse(element.getAttribute("data-lazy-attr"));

                        for (let key in attributes) {
                            if (attributes.hasOwnProperty(key)) {
                                let value = attributes[key];

                                element.setAttribute(key, value);
                            }
                        }

                        element.removeAttribute('data-lazy-attr');
                    }
                });

                if (lazyLoadAttributes.length === 0) {
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
};
