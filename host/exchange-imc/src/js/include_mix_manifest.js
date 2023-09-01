let currentUrl = window.location.href.replace(/\/$/, '');
fetch(currentUrl + '/mix-manifest.json')
    .then(response => response.json())
    .then(data => {
        let regexCss = /\.css\b/;
        let regexJs = /\.js\b/;
        let regexMixManifest = /include_mix_manifest.js\b/;
        Object.entries(data).map(([key, value]) => {
            //     document.querySelectorAll('link[href="' + key + '"]').forEach(element => {
            //         element.setAttribute('href', currentUrl + value);
            //     });
            //     document.querySelectorAll('script[src="' + key + '"]').forEach(element => {
            //         element.setAttribute('src', currentUrl + value);
            //     });

            if (regexCss.test(value)) {
                let linkElement = document.createElement('link');
                linkElement.rel = 'stylesheet';
                linkElement.href = currentUrl + value;
                document.head.append(linkElement);
            }
            if (regexJs.test(value) && !regexMixManifest.test(value)) {
                let scriptElement = document.createElement('script');
                scriptElement.src = currentUrl + value;
                document.body.append(scriptElement);
            }
        })
    })
    .catch(error => {
        console.error('error open mix-manifest.json', error);
    })